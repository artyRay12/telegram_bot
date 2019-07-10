<?php
  //require("index.php");
  include('vendor/autoload.php');
  $chat_id = $result["message"]["chat"]["id"];
  $chatID = 843008832;
  use Telegram\Bot\Api;
  $telegram = new Api('713953239:AAFiRmir3z-JsMnDMmGdQ4twvV2nzLpADGs');
  $telegram->sendMessage(['chat_id' => $chat_id, 'text' => "Привет"]);
