<?php
include('vendor/autoload.php');
use Telegram\Bot\Api;
use Fadion\Fixerio\Exchange;
use Fadion\Fixerio\Currency;
$telegram = new Api('713953239:AAFiRmir3z-JsMnDMmGdQ4twvV2nzLpADGs');
$result = $telegram -> getWebhookUpdates();
$text = $result["message"]["text"]; //Текст сообщения
$chat_id = $result["message"]["chat"]["id"]; //Уникальный идентификатор пользователя
$name = $result["message"]["from"]["username"]; //Юзернейм пользователя
$keyboard = [["Start"]]; //Клавиатура
$start = FALSE;
$question = "Если ты был супом, то каким супом ты бы был";
$questionNumber = 1;
$posAnswer0 = "Борщ с перчиком";
$posAnswer1 = "Щи с чесночком";
function pringMsg($msg) {
    return "Game is started!!!!!!";
}
if ($text AND $start == FALSE) {
    if ($text == "/start") {
        $reply = "Welcome";
        $reply_markup = $telegram->replyKeyboardMarkup(['keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => true]);
    } elseif ($text == "/sayHello") {
        if ($name) {
            $reply = 'Hello, ' . $name . '!';
        } else {
            $reply = 'Hello, stranger!';
        }
    }elseif ($text == "Start") {
        $reply = pringMsg($reply);
        $keyboard = [[$posAnswer0], [$posAnswer1]];
        $start = TRUE;
    }
}
$telegram->sendMessage(['chat_id' => $chat_id, 'text' => $reply, 'reply_markup' => $reply_markup]);
$reply_markup = $telegram->replyKeyboardMarkup(['keyboard' => $keyboard,
    'resize_keyboard' => true,
    'one_time_keyboard' => true]);

function getQuestById($questionNumber, $question) {
    if ($questionNumber == 0) {
        return "Если ты бы был супом, то каким супом ты бы был?";
    }
    if ($questionNumber == 1) {
        return "На что похож твой код?";
    }
    if ($questionNumber == 2) {
        return "Как вы относитесь к Иисусу?";
    }
}
function getPosAnswersById($questionNumber){
    if ($questionNumber == 1) {
        return array("На дерьмо", "На гавно");
    }
    if ($questionNumber == 1) {
        return array("Отличный мужик", "Странный тип");
    }
}
function answerAnalisys($questionNumber) {
    if ($questionNumber == 1 AND (($text == $posAnswer0) OR ($text = $posAnswer0)))
        echo "<br/>You fucking damn right";
}
while ($start == TRUE) {
    $keyboard = [[$posAnswer0], [$posAnswer1]];
    $question = getQuestById($questionNumber, $question); //Меняю вопрос
    $telegram->sendMessage(['chat_id' => $chat_id, 'text' => $question, 'reply_markup' => $reply_markup]);  //печатаю вопрос

    list($posAnswer0, $posAnswer1) = getPosAnswersById($questionNumber);// меняю кнопки
    $reply_markup = $telegram->replyKeyboardMarkup(['keyboard' => $keyboard,
        'resize_keyboard' => true,
        'one_time_keyboard' => true]);

    answerAnalisys($questionNumber); // анализ ответа
    $questionNumber = $questionNumber + 1;
    if ($questionNumber == 2)
        $start = FALSE;
}
?>
