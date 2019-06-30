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
//$keyboard = [["Start"]]; //Клавиатура
if ($text) {    
  if ($text == "Say Hello" or $text == "/start") {
    if ($name != "") {
      $reply = "Hello, ". $name . "!";
    } else {
      $reply = "Hello, stranger!";
    }
    //$reply_markup = $telegram->replyKeyboardMarkup([ 'keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => false ]);
    //$telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply, 'reply_markup' => $reply_markup ]);
    startGame();
  }
}

function startGame() {
  
  $keyboard = array(
      array(array('callback_data'=>'/butt1','text'=>'Кнопка 1')),
      array(array('callback_data'=>'/buut2','text'=>'Кнопка 2')),
  );

  $reply_markup = $telegram->replyKeyboardMarkup([ 
      'keyboard' => $keyboard, 
      'resize_keyboard' => true, 
      'one_time_keyboard' => false 
  ]);


  $telegram->sendMessage(array(
    'chat_id' => $chat_id,
      'text' => 'Нажмите на одну из кнопок:',
      'reply_markup' => $reply_markup,
  ));
}
 
/*$rates = (new Exchange())->key("f22838f03ab3c8f3ff5f7e119f870dfe")->symbols(Currency::USD, Currency::GBP)->get();
print $rates['EUR'];
print $rates[Currency::GBP];*/
?>
