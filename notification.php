<?php
  use Telegram\Bot\Api;
  $telegram = new Api('713953239:AAFiRmir3z-JsMnDMmGdQ4twvV2nzLpADGs');

  $usersID = $db->rawQuery('SELECT DISTINCT(userID) FROM ' . 'users');
  $length = count($usersID);
  for($i = 0; $i < $length; $i++) {
      $telegram->sendMessage(['chat_id' => $usersID[$i]["userID"], 'text' => "Привет"]);
  }
