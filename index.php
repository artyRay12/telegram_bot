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


if($text) {
    if ($text == "/start" and $name) {
      $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' =>'Добро пожаловать, ' . $name . '!' ]);
      $reply_markup = $telegram->replyKeyboardMarkup([ 'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => true ]);
      $telegram->sendMessage([ 'chat_id' => $chat_id,
            'text' => $reply,
            'reply_markup' => $reply_markup ]);
    } elseif($text == "/start" and !$name) {
      $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' =>'Добро пожаловать!' . $result['message']['from']['first_name'] ]);
    } elseif ($text == "/sayHello") {
      $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => "Hello World" ]);
    } else {
     	$telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => "Запрос не является командой" ]);
    }
} 
/*
$rates = (new Exchange())->key("f22838f03ab3c8f3ff5f7e119f870dfe")->symbols(Currency::USD, Currency::GBP)->get();
print $rates['EUR'];
print $rates[Currency::GBP];*/
?>
