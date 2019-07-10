<?php
  use Telegram\Bot\Api;
  include('vendor/autoload.php');
  require('db_handler.php');
  $db = dbInit();
  $telegram = new Api('713953239:AAFiRmir3z-JsMnDMmGdQ4twvV2nzLpADGs');

  $usersID = $db->rawQuery('SELECT DISTINCT(userID) FROM ' . 'users');
  $length = count($usersID);
  for($i = 0; $i < $length; $i++) {
      $telegram->sendMessage(['chat_id' => $usersID[$i]["userID"], 'text' => "Привет"]);
  }
