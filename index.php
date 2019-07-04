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
$start = FALSE;
$question = "";
$questionNumber = 0;
$score = "";
$endIsNear = 0;
$heroku_schema = 'heroku_fcc9304d7d4cb18';
$heroku_host = 'eu-cdbr-west-02.cleardb.net';
$heroku_userName = 'bb3a6b14f5f759';
$heroku_pass = '8b5a0204';
$questText = "";
$questNumber = 0;
$questDinId = "";
$endRequest = "";
$questIdRequest = "";
$questIdDb = "";
$answer1 = "";
$answer2 = "";
$answer3 = "";
$answer4 = "";
$valute = 5.2;
$buttonRequest = "";
$id = "";
$db = new MysqliDb ($heroku_host, $heroku_userName, $heroku_pass, $heroku_schema);

/*$endpoint = 'latest';
$access_key = 'f22838f03ab3c8f3ff5f7e119f870dfe';




$ch = curl_init('http://data.fixer.io/api/'.$endpoint.'?access_key='.$access_key.'');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  // Store the data:
  $json = curl_exec($ch);
  curl_close($ch);
  // Decode JSON response:
  $exchangeRates = json_decode($json, true);
  // Access the exchange rate values, e.g. GBP:
  $valute = $questionNumber * $exchangeRates['rates']['TND'];*/

//===========Анализ ответов===============
function anwerAnalys($text, $questDinId, $score, $answer1, $answer2, $answer3, $answer4, $db, $userID) {
  function ScoreUp($db, $userID) {                
      $data = Array ('userScore' => $db->inc(20),);
      $db->where ('userID', $userID);
      $db->update ('users', $data);
  }
                   
  if ($questDinId == 1 AND $text == "Хангикьот") {  //$text == $answer1
      ScoreUp($db, $userID);
  } elseif($questDinId == 2 AND $text == "На га*но") { //$text == $answer1
      ScoreUp($db, $userID);
  } elseif($questDinId == 3 AND $text == "Она без полотенца") { //$text == $answer1
      ScoreUp($db, $userID);
  } elseif($questDinId == 4 AND $text == "Шарманка") { //$text == $answer2
      ScoreUp($db, $userID);
  } elseif($questDinId == 5 AND $text == "Датская ватрушка с сыром") { //$text == $answer1
      ScoreUp($db, $userID);
  } elseif($questDinId == 6 AND $text == "Как я встретил вашу маму") { //$text == $answer1
      ScoreUp($db, $userID);
  } elseif($questDinId == 7 AND $text == "Эдвард") { //$text == $answer1
      ScoreUp($db, $userID);
  }
  return;
}
//=====================Проверка userID==================
function checkUserID($db, $userID, $name, $id) {
  $db -> where("userID", $userID);
  $userData = $db->getOne("users");
  if ($userData) {
  } else {
  $data = Array ("userID" => $userID,
               "userName" => $name,
               "userScore" => 0,
                "currentQuest" => 0);
    $id = $db->insert ('users', $data);
  }
  return;
} 
//=-==---=-==-=-=-=-=-----=-==-=-=-=-=-=-=-=-=-=-

try {
  if ($text == "/start") {
    checkUserID($db, $userID, $name, $id);
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
    //----===Получаем очки пользователя
    $scoreRequest = Array("userScore");
    $db->where('userID', $userID);
    $scoreDb = $db->get("users", null, $scoreRequest);
    $score = isset($scoreDb[0]["userScore"]) ? $scoreDb[0]["userScore"] : "";
   
    
    //----===Получаем номер вопроса
    $questIdRequest = Array("currentQuest"); //Массив для с полем для запроса
    $db->where('userID', $userID);
    $questIdDb = $db->get ("users", null, $questIdRequest);//получаем номер квеста 
    $questDinId = isset($questIdDb[0]["currentQuest"]) ? $questIdDb[0]["currentQuest"] : "";
    
    //Анализ ответа изходя из номера вопроса
    anwerAnalys($text, $questDinId, $score, $answer1, $answer2, $answer3, $answer4, $db, $userID);
    
    //---===Получаем кнопки исходя из номера вопроса===---
    $buttonRequest = Array('questAnswer0', 'questAnswer1', 'questAnswer2', 'questAnswer3');
    $buttondb = $db->get("questions", null, $buttonRequest);
    
    $answer1 = isset($buttondb[$questDinId]["questAnswer0"]) ? $buttondb[$questDinId]["questAnswer0"] : "";
    $answer2 = isset($buttondb[$questDinId]["questAnswer1"]) ? $buttondb[$questDinId]["questAnswer1"] : "";
    $answer3 = isset($buttondb[$questDinId]["questAnswer2"]) ? $buttondb[$questDinId]["questAnswer2"] : "";
    $answer4 = isset($buttondb[$questDinId]["questAnswer3"]) ? $buttondb[$questDinId]["questAnswer3"] : "";
    
    $keyboard = [[$answer1, $answer2], [$answer3, $answer4]];
    $reply_markup = $telegram->replyKeyboardMarkup(['keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => true]);
     
 
    //----===Получаем впорос
    $questTextRequest = Array ("questText");
    $questDb = $db->get ("questions", null, $questTextRequest);
    $questText = $questText = isset($questDb[$questDinId]["questText"]) ? $questDb[$questDinId]["questText"] : "";  
    
    //==Получаем данные о конце викторины
    $endRequest = Array("endIsNear");
    $db->where('userID', $userID);
    $scoreDb = $db->get("users", null, $endRequest);
    $endIsNear = isset($scoreDb[0]["endIsNear"]) ? $scoreDb[0]["endIsNear"] : "";

  
    //----===Увеличиваю счетчик вопроса
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
   $telegram->sendMessage(['chat_id' => $chat_id, 'text' => "Вы набрали всего лишь: " . $score . " баллов", 'reply_markup' => $reply_markup]);
  } else {
   $telegram->sendMessage(['chat_id' => $chat_id, 'text' => $questText, 'reply_markup' => $reply_markup]);
  }
} catch (Exeptions $e) {
}

?>
