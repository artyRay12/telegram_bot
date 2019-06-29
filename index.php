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
/*$result = $telegram -> getWebhookUpdates();
$text = $result["message"]["text"];
$chat_id = $result["message"]["chat"]["id"];
$name = $result["message"]["from"]["username"];
$keyboard = [["Начать викторину"]]; //Клавиша которая нихера не появляется*/

 $result = $telegram -> getWebhookUpdates(); //Передаем в переменную $result полную информацию о сообщении пользовате
  $text = $result["message"]["text"]; //Текст сообщения
  $chat_id = $result["message"]["chat"]["id"]; //Уникальный идентификатор пользователя
  $name = $result["message"]["from"]["username"]; //Юзернейм пользователя
  $keyboard = [["Start"], ["Quit"], ["Say Hello"]]; //Клавиатура
  if ($text) {
    
    if ($text == "Say Hello" or $text == "/start") {
      if ($name != "") {
        $reply = "Hello, ". $name . "!";
      }
    
      else {
        $reply = "Hello, stranger!";
      }
      $reply_markup = $telegram->replyKeyboardMarkup([ 'keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => false ]);
      $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply, 'reply_markup' => $reply_markup ]);
    }
    if ($text == "Search question") {
      $reply = "Answer here";
      $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply, 'reply_markup' => $reply_markup ]);
      $writeBookName = true;
    }
    if ($text !== "Answer here" and $text !== "Say Hello" and $text !== "My library" and $text !== "/start") {
      $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => searchBook($text)]);
    }
  }


/*$rates = (new Exchange())->key("f22838f03ab3c8f3ff5f7e119f870dfe")->symbols(Currency::USD, Currency::GBP)->get();
print $rates['EUR'];
print $rates[Currency::GBP];*/

//----====KEYBOARD TEST====-----
//$reply_markup = $telegram->replyKeyboardMarkup([ 'keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => false ]);
//$telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply, 'reply_markup' => $reply_markup ]);

/*/-=-=-=-=-=-=-=-=--=-=-=-=
if($text) {
    if ($text == "/start" and $name) {
         $reply_markup = $telegram->replyKeyboardMarkup([ 'keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => false ]);
         $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply, 'reply_markup' => $reply_markup ]);
    } elseif($text == "/start" and !$name) {
        $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' =>'Добро пожаловать, незнакомец!' ]);
    } elseif ($text == "/sayHello") {
        $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => "Hello World" ]);
    } else {
        $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => "Отправьте текстовое сообщение." ]);
    }
}*/
?>
