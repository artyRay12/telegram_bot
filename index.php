<?php
include('vendor/autoload.php');
use Telegram\Bot\Api;
use Fadion\Fixerio\Exchange;
use Fadion\Fixerio\Currency;
/*$exchange = new Exchange();
$exchange->key('f22838f03ab3c8f3ff5f7e119f870dfe');
$exchange->base(Currency::USD);
$exchange->symbols(Currency::EUR, Currency::GBP);
$rates = $exchange->get();*/
$telegram = new Api('713953239:AAFiRmir3z-JsMnDMmGdQ4twvV2nzLpADGs');
$result = $telegram -> getWebhookUpdates();
$text = $result["message"]["text"]; //Текст сообщения
$chat_id = $result["message"]["chat"]["id"]; //Уникальный идентификатор пользователя
$name = $result["message"]["from"]["username"]; //Юзернейм пользователя
$keyboard = [["Start"]]; //Клавиатура
$start = FALSE;
function pringMsg($msg) {
    return "Game is started!!!!!!";
}
if ($text) {
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
        $start = TRUE;
    }
}
$telegram->sendMessage(['chat_id' => $chat_id, 'text' => $reply, 'reply_markup' => $reply_markup]);
if ($start == TRUE) {
    $keyboard = [["Борщ с перчиком"], ["Щи с чесночком"]];
    $reply = "Если ты был супом, то каким супом ты бы был";
    $reply_markup = $telegram->replyKeyboardMarkup(['keyboard' => $keyboard,
        'resize_keyboard' => true,
        'one_time_keyboard' => true]);
}
$telegram->sendMessage(['chat_id' => $chat_id, 'text' => $reply, 'reply_markup' => $reply_markup]);
/*if ($start) {
    $keyboard = [["Какая нахрен разница"]]; //Клавиатура
    $reply_markup = $telegram->replyKeyboardMarkup(['keyboard' => $keyboard,
        'resize_keyboard' => true,
        'one_time_keyboard' => true]);
    if ($text) {
        if ($text == "Какая нахрен разница") {
            $reply = "Правильно!";
        }
    }
    $telegram->sendMessage(['chat_id' => $chat_id, 'text' => $reply, 'reply_markup' => $reply_markup]);
}*/
/*$rates = (new Exchange())->key("f22838f03ab3c8f3ff5f7e119f870dfe")->symbols(Currency::USD, Currency::GBP)->get();
print $rates['EUR'];
print $rates[Currency::GBP];*/
?>
