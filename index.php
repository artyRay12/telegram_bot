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

$questLevels = rand(2, 4);
$questionsRequest = "https://engine.lifeis.porn/api/millionaire.php?q=$questLevels";
$questSite = "$questionsRequest";
$update = json_decode(file_get_contents($questSite), JSON_OBJECT_AS_ARRAY);
$db = dbInit();




//=-==---=
if ($text == START_COMMAND) {
    if (isNewplayer($db, $userID, $userName)) {
        createNewAccount($db, $userID, $userName);
    } else {
        resetTheGame($db, $userID);
    }
}

if(isLastQuestion($db, $userID) == FALSE) {
    //Сравниваем верный ответ с ответов пользователя
    //if (isRightAnswer($db, $userID, $update, $text)) {
        addPoint($db, $userID);
    //}

    pushRightAnswerInDB($db, $userID, $userID);

    $questText = $update["data"]["question"];

    $keyboard = getPosibleAnswers($update);
    $reply_markup = $telegram->replyKeyboardMarkup(['keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => true]);


     increaseQuestCounter($db, $update, $userID);

    $telegram->sendMessage(['chat_id' => $chat_id, 'text' => $questText, 'reply_markup' => $reply_markup]);

} else {
        $keyboard = [[START_COMMAND]];
        $reply_markup = $telegram->replyKeyboardMarkup(['keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => true]);

        if (isRightAnswer($db, $userID, $update, $text)) {
          addPoint($db, $userID);
        }

        if (isNewRecord($db, $userID)) {
            addPersonalRecord($db, $scoreDb, $userID);
        }

        $telegram->sendMessage(['chat_id' => $chat_id, 'text' => "Вы набрали всего лишь: " . getUserScore($db, $userID) . " баллов", 'reply_markup' => $reply_markup]);


 }

?>