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

function isLastQuestion($db, $userID): bool
{
    $currentQuestID = getCurrentQuestId($db, $userID);
    if ( $currentQuestID < 10) {
        return false;
    } else {
        return true;
    }
}

function isNewRecord($db, $userID): bool
{
    $score = getUserScore($db, $userID);
    $maxScore = getUserMaxScore($db, $userID);
    if ($score > $maxScore) {
        return true;
    } else {
        return false;
    }
}




?>