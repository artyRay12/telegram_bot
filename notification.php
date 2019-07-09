<?php
  $chatID = 843008832;
  use Telegram\Bot\Api;
  $telegram = new Api('713953239:AAFiRmir3z-JsMnDMmGdQ4twvV2nzLpADGs');
  require("index.php");
  $telegram->sendMessage(['chat_id' => $chatID, 'text' => "Привет"]);
