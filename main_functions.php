<?php
require_once("config.php");
require_once("engineLifeIsAPI.php");
require_once("db_handler.php");
require_once("config.php");
require_once("engineLifeIsAPI.php");


function isNewPlayer($db, $userID, $userName): bool
{
    $userTest = getUserID($db, $userID);
    if ($userTest) {
        return FALSE;
    } else {
        return TRUE;
    }
}

function isRightAnswer($db, $userID, $update, $text): bool
{
    $rightAnswer = getRightAnwerFromDB($db, $userID, $update);
    if ($text == $rightAnswer) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function addPoint($db, $userID): void
{
    $data = Array('userScore' => $db->inc(20),);
    $db->where('userID', $userID);
    $db->update('users', $data);
    return;
}

function isLastQuestion($db, $userID): bool
{
    //Получаем номер текущего вопроса
    $db->where('userID', $userID);
    $questIdDb = $db->get("users", null, "currentQuest");//получаем номер квеста
    $questDinId = isset($questIdDb[0]["currentQuest"]) ? $questIdDb[0]["currentQuest"] : "";

    if ($questDinId < 10) {
        return false;
    } else {
        return true;
    }
}

function increaseQuestCounter($db, $update, $userID): void
{
    $data = Array('currentQuest' => $db->inc(1),);
    $db->where('userID', $userID);
    $db->update('users', $data);
    return;
}

function getUserScore($db, $userID): int
{
    $db->where('userID', $userID);
    $scoreDb = $db->get("users", null, "userScore");
    return isset($scoreDb[0]["userScore"]) ? $scoreDb[0]["userScore"] : "";
}

function isNewRecord($db, $userID): bool
{
    $score = getUserScore($db, $userID);
    $db->where('userID', $userID);
    $scoreDb = $db->get("users", null, "maxScore");
    $maxScore = isset($scoreDb[0]["maxScore"]) ? $scoreDb[0]["maxScore"] : "";
    if ($score > $maxScore) {
        return true;
    } else {
        return false;
    }
}

function addPersonalRecord($db, $userID): void
{
    $data = Array('maxScore' => getUserScore($db, $userID));
    $db->where('userID', $userID);
    $db->update('users', $data);
    return;
}


?>