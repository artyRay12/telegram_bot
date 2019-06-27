<?php
include('vendor/autoload.php');
use Telegram\Bot\Api;
$telegram = new Api('713953239:AAFiRmir3z-JsMnDMmGdQ4twvV2nzLpADGs');
$result = $telegram -> getWebhookUpdates();
$text = $result["message"]["text"];
$chat_id = $result["message"]["chat"]["id"];
$name = $result["message"]["from"]["username"];
if($text) {
    if ($text == "/sayhello") {
        if (srtlen($name) != 0)
            $telegram->sendMessage(['chat_id' => $chat_id, 'text' => "Добро пожаловать!, " . $name . "!"]);
        else
            $telegram->sendMessage(['chat_id' => $chat_id, 'text' => "Добро пожаловать незнакомец"]);
    else
    {
    $reply = "Введите что-то другое"
    }
}
?>

