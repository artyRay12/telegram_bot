<?php
  $chatID = 843008832;
  require('index.php');
  $telegram->sendMessage(['chat_id' => $chatID, 'text' => "Привет",
    'reply_markup' => $reply_markup]);




