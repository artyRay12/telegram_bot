<?php
include('vendor/autoload.php');
use Telegram\Bot\Api;

//require('bd_functions.php');
require('config.php');
require('db_handler.php');
require('engineLifeIsAPI.php');

$telegram = new Api('713953239:AAFiRmir3z-JsMnDMmGdQ4twvV2nzLpADGs');
$result = $telegram -> getWebhookUpdates();
$text = $result["message"]["text"]; //Текст сообщения
$chat_id = $result["message"]["chat"]["id"]; //Уникальный идентификатор пользователя
$userName = $result["message"]["from"]["username"]; //Юзернейм пользователя
$userID = $result['message']['from']['id'];

$score = "";
$endIsNear = 0;
$maxScore = 0;
$questText = "";
$questNumber = 0;
$questDinId = "";
$isAnswersReady = FALSE;
$answersCounter = 0;
$questIdDb = "";

$questLevels = rand(2, 4);
$questionsRequest = "https://engine.lifeis.porn/api/millionaire.php?q=$questLevels";
$questSite = "$questionsRequest";
$update = json_decode(file_get_contents($questSite), JSON_OBJECT_AS_ARRAY);
$db = dbInit();

function addPersonalRecord($db, $scoreDb, $maxScore, $score, $userID) {
    $db->where('userID', $userID);
    $scoreDb = $db->get("users", null, "maxScore");
    $maxScore = isset($scoreDb[0]["maxScore"]) ? $scoreDb[0]["maxScore"] : "";
    if ($score > $maxScore) {
        $data = Array('maxScore' => $score);
        $db->where('userID', $userID);
        $db->update('users', $data);
    }
    return;
}


//=-==---=
if ($text == START_COMMAND) {
    if (isNewplayer($db, $userID, $userName)) {
        createNewAccount($db, $userID, $userName);
    } else {
        resetTheGame($db, $userID);
    }
}

if($questDinId <= 10) {
    //Сравниваем верный ответ с ответов пользователя
    if (isRightAnswer($db, $userID, $update, $text)) {
        addPoint($db, $userID);
    }

    pushRightAnswerInDB($db, $userID, $userID);

    $questText = $update["data"]["question"];
    $keyboard = getPosibleAnswers($update);
    $reply_markup = $telegram->replyKeyboardMarkup(['keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => true]);


    //----===Увеличиваю счетчик вопроса!
    if(isLastQuestion($db, $userID) == FALSE) {
        increaseQuestCounter($db, $update, $userID);
    } else {
        //Кончились вопросы
        $data = Array ('EndIsNear' => 1);
        $db->where('userID', $userID);
        $db->update ('users', $data);
    }
    $telegram->sendMessage(['chat_id' => $chat_id, 'text' => $questText, 'reply_markup' => $reply_markup]);

} else {
    //if (isFinish($db, $userID)) {
        $keyboard = [["/start"]];
        $reply_markup = $telegram->replyKeyboardMarkup(['keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => true]);
        //----===Получаем очки пользователя
        $db->where('userID', $userID);
        $scoreDb = $db->get("users", null, "userScore");
        $score = isset($scoreDb[0]["userScore"]) ? $scoreDb[0]["userScore"] : "";

        addPersonalRecord($db, $scoreDb, $maxScore, $score, $userID);

        $telegram->sendMessage(['chat_id' => $chat_id, 'text' => "Вы набрали всего лишь: " . $score . " баллов", 'reply_markup' => $reply_markup]);
   // } else {

    }

?>