<?php
include('vendor/autoload.php');
use Telegram\Bot\Api;
//require('bd_functions.php');

$telegram = new Api('713953239:AAFiRmir3z-JsMnDMmGdQ4twvV2nzLpADGs');
$result = $telegram -> getWebhookUpdates();
$text = $result["message"]["text"]; //Текст сообщения
$chat_id = $result["message"]["chat"]["id"]; //Уникальный идентификатор пользователя
$name = $result["message"]["from"]["username"]; //Юзернейм пользователя
$userID = $result['message']['from']['id']; 
$score = "";
$endIsNear = 0;
$maxScore = 0;
$heroku_schema = 'heroku_fcc9304d7d4cb18';
$heroku_host = 'eu-cdbr-west-02.cleardb.net';
$heroku_userName = 'bb3a6b14f5f759';
$heroku_pass = '8b5a0204';
$questText = "";
$questNumber = 0;
$questDinId = "";
$isAnswersReady = FALSE;
$answersCounter = 0;
$questIdDb = "";
$db = new MysqliDb ($heroku_host, $heroku_userName, $heroku_pass, $heroku_schema);
$questLevels = rand(2, 4);

$questionsRequest = "https://engine.lifeis.porn/api/millionaire.php?q=$questLevels";

$questSite = "$questionsRequest";
$update = json_decode(file_get_contents($questSite), JSON_OBJECT_AS_ARRAY);

function addPersonalRecord($db, $scoreDb, $maxScore, $score, $userID) {
  $db->where('userID', $userID);
  $scoreDb = $db->get("users", null, "maxScore");
  $maxScore = isset($scoreDb[0]["maxScore"]) ? $scoreDb[0]["maxScore"] : "";
  if ($score > $maxScore) {
    $data = Array ('maxScore' => $score);
    $db->where ('userID', $userID);
    $db->update ('users', $data);
  }
}

/*function addGlobalRecord($db, $scoreDb, $maxScore, $score, $userID) {
  
  
  
}*/

function checkUserID($db, $userID, $name) {
  $db->where('userID', $userID);
  $scoreDb = $db->getOne("users", null, "userID");
  if ($scoreDb["userID"]) {
  } else {
    $query = "insert into users(userID, userName, userScore, currentQuest, maxScore, EndIsNear) values($userID, '$name', 0, 0, 0, 0)";
    $db->query($query);
  }
  return;
} 
//=-==---=


if ($text == "/start") {
    checkUserID($db, $userID, $name);
    //---==Refresh currQuest
    $data = Array ('currentQuest' => 0);
    $db->where('userID', $userID);
    $db->update ('users', $data);
    //---===Refresh score
    $data = Array('userScore' => 0);
    $db->where('userID', $userID);
    $db->update('users', $data);
    //--==Refresh EndIsNear
    $data = Array ('EndIsNear' => 0);
    $db->where('userID', $userID);
    $db->update ('users', $data);
  }
  if($questDinId <= 7) {  
    //Получаем верный ответ из БД
    $db->where('userID', $userID);
    $questIdDb = $db->get ("users", null, "rightAnswer");//получаем номер квеста 
    $rightAnswer = isset($questIdDb[0]["rightAnswer"]) ? $questIdDb[0]["rightAnswer"] : "";
     
    
    //Сравниваем верный ответ с ответов пользователя
    if ($text == $rightAnswer) {
      $data = Array ('userScore' => $db->inc(20),);
      $db->where ('userID', $userID);
      $db->update ('users', $data);
    }
    
    // Получаю кнопки и вопрос
     //Cохраняем верный ответ в БД чтобы потом сравнить с ответом от пользователя
    $data = Array ('rightAnswer' => $update["data"]["answers"][0]);
    $db->where ('userID', $userID);
    $db->update ('users', $data);
      
      //Смешиваю варианты ответа
      $answersID = [];
      while ($isAnswersReady == FALSE):
        $randID = rand(0, 3);
        if (in_array($randID, $answersID)){
        } else {
          array_push($answersID, $randID);
          $answersCounter = $answersCounter + 1;
        }
          if ($answersCounter == 4)
            $isAnswersReady = TRUE;
      endwhile;
      
      
    $questText = $update["data"]["question"];
    $answer1 = $update["data"]["answers"][$answersID[0]];
    $answer2 = $update["data"]["answers"][$answersID[1]];
    $answer3 = $update["data"]["answers"][$answersID[2]];
    $answer4 = $update["data"]["answers"][$answersID[3]];
    $keyboard = [[$answer1, $answer2], [$answer3, $answer4]];
    $reply_markup = $telegram->replyKeyboardMarkup(['keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => true]);
      
   
    
    
    
    //Получаем номер текущего вопроса
    $db->where('userID', $userID);
    $questIdDb = $db->get ("users", null, "currentQuest");//получаем номер квеста 
    $questDinId = isset($questIdDb[0]["currentQuest"]) ? $questIdDb[0]["currentQuest"] : "";
      
    //==Получаем данные о конце викторины
    $db->where('userID', $userID);
    $scoreDb = $db->get("users", null, "endIsNear");
    $endIsNear = isset($scoreDb[0]["endIsNear"]) ? $scoreDb[0]["endIsNear"] : "";

    //----===Увеличиваю счетчик вопроса!
    if($questDinId < 7) {
      $data = Array ('currentQuest' => $db->inc(1),);
      $db->where('userID', $userID);
      $db->update ('users', $data);
    } else {
      //Кончились вопросы
      $data = Array ('EndIsNear' => 1);
      $db->where('userID', $userID);
      $db->update ('users', $data);
    }
      
      
  }

  if ($endIsNear == 1) {
    $keyboard = [["/start"]];
    $reply_markup = $telegram->replyKeyboardMarkup(['keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => true]);
    //----===Получаем очки пользователя
    $db->where('userID', $userID);
    $scoreDb = $db->get("users", null, "userScore");
    $score = isset($scoreDb[0]["userScore"]) ? $scoreDb[0]["userScore"] : "";
    
    addPersonalRecord($db, $scoreDb, $maxScore, $score, $userID);
    
    $telegram->sendMessage(['chat_id' => $chat_id, 'text' => "Вы набрали всего лишь: " . $score . " баллов", 'reply_markup' => $reply_markup]);
  } else {
    $telegram->sendMessage(['chat_id' => $chat_id, 'text' => $questText, 'reply_markup' => $reply_markup]);
  }


  

?>
