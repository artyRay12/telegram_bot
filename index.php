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
$text = $result["message"]["text"];
$chat_id = $result["message"]["chat"]["id"];
$name = $result["message"]["from"]["username"];
$keyboard = [["Начать викторину"]]; //Клавиша которая нихера не появляется

/*$rates = (new Exchange())->key("f22838f03ab3c8f3ff5f7e119f870dfe")->symbols(Currency::USD, Currency::GBP)->get();
print $rates['EUR'];
print $rates[Currency::GBP];*/

//----====KEYBOARD TEST====-----
$keyboard = [
    ['7', '8', '9'],
    ['4', '5', '6'],
    ['1', '2', '3'],
         ['0']
];

$reply_markup = $telegram->replyKeyboardMarkup([
	'keyboard' => $keyboard, 
	'resize_keyboard' => true, 
	'one_time_keyboard' => true
]);

$response = $telegram->sendMessage([
	'chat_id' => 'CHAT_ID', 
	'text' => 'Hello World', 
	'reply_markup' => $reply_markup
]);

$messageId = $response->getMessageId();
//-=-=-=-=-=-=-=-=--=-=-=-=


if($text) {
    if ($text == "/start" and $name) {
        $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' =>'Добро пожаловать, ' . $name . '!' ]);
    } elseif($text == "/start" and !$name) {
        $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' =>'Добро пожаловать, незнакомец!' ]);
    } elseif ($text == "/sayHello") {
        $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => "Hello World" ]);
    } else {
        $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => "Отправьте текстовое сообщение." ]);
    }
}
?>
