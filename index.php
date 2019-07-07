<?php
    include('vendor/autoload.php');
    use Telegram\Bot\Api;

    //require('bd_functions.php');
    require('config.php');
    require('db_handler.php');
    require('main_functions.php');

    $telegram = new Api('713953239:AAFiRmir3z-JsMnDMmGdQ4twvV2nzLpADGs');
    $result = $telegram -> getWebhookUpdates();
    $text = $result["message"]["text"]; //Текст сообщения
    $chat_id = $result["message"]["chat"]["id"]; //Уникальный идентификатор пользователя
    $userName = $result["message"]["from"]["username"]; //Юзернейм пользователя
    $userID = $result['message']['from']['id'];
    $db = dbInit();

    //=-==---=
    if ($text == START_COMMAND) {
        if (isNewplayer($db, $userID)) {
            createNewAccount($db, $userID, $userName);
        } else {
            resetTheGame($db, $userID);
        }
    }

    if(isTestCompleted($db, $userID) == FALSE) {

        if (isRightAnswer($db, $userID, $update, $text)) {
            addPoint($db, $userID);
        }

        $keyboard = getPosibleAnswers($update, $userID, $db);
        $reply_markup = $telegram->replyKeyboardMarkup(['keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => true]);

        increaseQuestCounter($db, $userID);

        $telegram->sendMessage(['chat_id' => $chat_id, 'text' => getQuestText($update), 'reply_markup' => $reply_markup]);

    } else {
        $keyboard = [[START_COMMAND]];
        $reply_markup = $telegram->replyKeyboardMarkup(['keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => true]);

        if (isRightAnswer($db, $userID, $update, $text)) {
          addPoint($db, $userID);
        }

        if (isNewRecord($db, $userID)) {
            addPersonalRecord($db, $userID);
        }

        $telegram->sendMessage(['chat_id' => $chat_id, 'text' => "Вы набрали всего лишь: " . getUserScore($db, $userID) . " баллов", 'reply_markup' => $reply_markup]);
 }

?>