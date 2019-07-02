<?php
include('vendor/autoload.php');
use Telegram\Bot\Api;


$telegram = new Api('713953239:AAFiRmir3z-JsMnDMmGdQ4twvV2nzLpADGs');
$result = $telegram -> getWebhookUpdates();
$text = $result["message"]["text"]; //Текст сообщения
$chat_id = $result["message"]["chat"]["id"]; //Уникальный идентификатор пользователя
$name = $result["message"]["from"]["username"]; //Юзернейм пользователя
$keyboard = [["Start"]]; //Клавиатура
$start = FALSE;
$question = "";
$questionNumber = 0;
$posAnswer0 = "";
$posAnswer1 = "";
$ykey = 'trnsl.1.1.20190701T123556Z.a709b3fe483b8b73.382884258e396ec33cbc5dfd6b98f7f28f65d49a';
$heroku_schema = 'heroku_fcc9304d7d4cb18';
$heroku_host = 'eu-cdbr-west-02.cleardb.net';
$heroku_userName = 'bb3a6b14f5f759';
$heroku_pass = '8b5a0204';
$questText = "";
$questNumber = 0;
$questDinId = 0;
$questIdRequest = "";
$answer1 = "";
$answer2 = "";
$answer3 = "";
$answer4 = "";
$buttonRequest = "";
$db = new MysqliDb ($heroku_host, $heroku_userName, $heroku_pass, $heroku_schema);

//if ($text) {
  if ($text == "/start") {
    $keyboard = [["Начать игру"]];
    $reply_markup = $telegram->replyKeyboardMarkup(['keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => true]);
    $data = Array ('dynamicQuestID' => 0);
    $db->update ('questions', $data);
    $telegram->sendMessage(['chat_id' => $chat_id, 'text' => 'Test was reloaded', 'reply_markup' => $reply_markup]);
  } else {
    
    //----===Берем questID
    $questIdRequest = Array("dynamicQuestID"); //Массив для с полем для запроса
    $questDb = $db->get ("questions", null, $questIdRequest);//получаем номер квеста
    $questDinId = $questDb[0]["dynamicQuestID"];
    
    //---===Получаем кнопки===---
    $buttonRequest = Array('questAnswer0', 'questAnswer1', 'questAnswer2', 'questAnswer3');
    $buttondb = $db->get("questions", null, $buttonRequest);
    //$answer1 = $buttondb[$questDinId]["questAnswer0"];
    //$answer2 = $buttondb[$questDinId]["questAnswer1"];
    //$answer3 = $buttondb[$questDinId]["questAnswer2"];
    //$answer4 = $buttondb[$questDinId]["questAnswer3"];

   /* $keyboard = [[  $buttondb[$questDinId]["questAnswer0"], 
                  $buttondb[$questDinId]["questAnswer1"]], 
                  [$buttondb[$questDinId]["questAnswer2"], 
                   $buttondb[$questDinId]["questAnswer3"]]];
    $reply_markup = $telegram->replyKeyboardMarkup(['keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => true]);*/
    
    //----===Берем questText
    $questTextRequest = Array ("questText");
    $questDb = $db->get ("questions", null, $questTextRequest);
    //$questText = $questDb[$questDinId]["questText"];
    //echo $questText;
    $telegram->sendMessage(['chat_id' => $chat_id, 'text' => $questDb[$questDinId]["questText"], 'reply_markup' => $reply_markup]);

    //----===Меняем questID
    $data = Array ('dynamicQuestID' => $db->inc(1),);
    $db->where ('dynamicQuestID', $questDinId);
    $db->update ('questions', $data);
 }
 


//----======Перевод через Fixer io=====------
// set API Endpoint and API key
/*$endpoint = 'latest';
$access_key = 'f22838f03ab3c8f3ff5f7e119f870dfe';

// Initialize CURL:
$ch = curl_init('http://data.fixer.io/api/'.$endpoint.'?access_key='.$access_key.'');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Store the data:
$json = curl_exec($ch);
curl_close($ch);

// Decode JSON response:
$exchangeRates = json_decode($json, true);

// Access the exchange rate values, e.g. GBP:
$questionNumber = $questionNumber * $exchangeRates['rates']['RUB'];
echo $questionNumber;

if ($text AND $start == FALSE) {
    if ($text == "/start") {
        $reply = "Welcome";
        $reply_markup = $telegram->replyKeyboardMarkup(['keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => true]);
    } elseif ($text == "/sayHello") {
        if ($name) {
            $reply = 'Hello, ' . $name . '!';
        } else {
            $reply = 'Hello, stranger!';
        }
    }elseif ($text == "Start") {
        $reply = "We Starting!";
        //$keyboard = [[$posAnswer0], [$posAnswer1]];
        //$start = TRUE;
    } else {
        $questionNumber = $text;
        $questionNumber = $questionNumber * $exchangeRates['rates']['RUB'];
        $reply = $questionNumber . "rub";
    }
}
$telegram->sendMessage(['chat_id' => $chat_id, 'text' => $reply, 'reply_markup' => $reply_markup]);*/


?>
