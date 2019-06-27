<?php
  include('vendor/autoload.php'); 
  use Telegram\Bot\Api; 
  $telegram = new Api('831061547:AAFwm0s2dLQIWLhRHJljKVVRv4aTzwpbgI0'); 
  $result = $telegram -> getWebhookUpdates(); 
  $text = $result["message"]["text"];
  $chat_id = $result["message"]["chat"]["id"];  
  $name = $result["message"]["from"]["username"];
  if($text) {
  if ($text == "/start") {
    $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => "Добро пожаловать!" ]);
  } else{
    	$telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => "Отправьте текстовое сообщение." ]);
    }
  }
?>
