<?php
require_once("config.php");
require_once("engineLifeIsAPI.php");
require_once("db_handler.php");
require_once("config.php");
require_once("engineLifeIsAPI.php");


function isNewPlayer($db, $userID): bool {
    $userIDfromDB = getUserID($db, $userID);
    if ($userIDfromDB) {
        return FALSE;
    } else {
        return TRUE;
    }
}

function isRightAnswer($db, $userID, $update, $text): bool {
    $rightAnswer = getRightAnwerFromDB($db, $userID, $update);
    if ($text == $rightAnswer) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function isTestCompleted($db, $userID): bool {
    $currentQuestID = getCurrentQuestId($db, $userID);
    if ( $currentQuestID < 10) {
        return false;
    } else {
        return true;
    }
}

function isNewRecord($db, $userID): bool {
    $score = getUserScore($db, $userID);
    $maxScore = getUserMaxScore($db, $userID);
    if ($score > $maxScore) {
        return true;
    } else {
        return false;
    }
}

function addNewGlobalRating($db, $userID, $userName): void {
    $placeFound = FALSE;
    $placeForChange = 1;
    $score = getUserScore($db, $userID);
    $scoreByPlace = "";
    echo $scoreByPlace;
    while ($placeFound == FALSE):
        $scoreByPlace = getScoreByPlace($db, $placeForChange);
        if ($placeForChange == 4) {
            $placeFound = TRUE;
        }
        if ($scoreByPlace < $score) {
            $placeFound = TRUE;
        } elseif ($scoreByPlace == $score) {
            $placeFound = TRUE;
        }
        if ($placeFound == FALSE) {
            $placeForChange = $placeForChange + 1;
        }
    endwhile;

    if ($placeForChange == 1) {
        replaceRecords($db, 2, 3);
        replaceRecords($db, 1, 2);
        putNewRecord($db, $userID, $userName, $score, $placeForChange);
    } elseif ($placeForChange == 2) {
        replaceRecords($db, 2, 3);
        putNewRecord($db, $userID, $userName, $score, $placeForChange);
    } elseif ($placeForChange == 3) {
        putNewRecord($db, $userID, $userName, $score, $placeForChange);
    }
}




?>