<?php
include('vendor/autoload.php');
use Telegram\Bot\Api;
$telegram = new Api('713953239:AAFiRmir3z-JsMnDMmGdQ4twvV2nzLpADGs');
$result = $telegram -> getWebhookUpdates();
$text = $result["message"]["text"];
$chat_id = $result["message"]["chat"]["id"];
$name = $result["message"]["from"]["username"];
if($text) {
  if ($text == "/start") {
    $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' =>'Добро пожаловать, ' . $name . '!' ]);
  } else{
    	 $reply = "Добро пожаловать, ".$name."!";
    }
  }
?>
