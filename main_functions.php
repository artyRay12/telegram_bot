<?php
require_once("config.php");
require_once("engineLifeIsAPI.php");
require_once("db_handler.php");
require_once("config.php");
require_once("engineLifeIsAPI.php");


function isNewPlayer($db, $userID): bool {
    $userIDfromDB = getInfoByID(USER_ID, $db, $userID);
    if ($userIDfromDB) {
        return FALSE;
    } else {
        return TRUE;
    }
}

function isRightAnswer($db, $userID, $text): bool {
    $rightAnswer = getInfoByID(RIGHT_ANSWER, $db, $userID);
    if ($text == $rightAnswer) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function isTestCompleted($db, $userID): bool {
    $currentQuestID = getInfoByID(CURRENT_QUEST_ID, $db, $userID);
    if ( $currentQuestID < 10) {
        return false;
    } else {
        return true;
    }
}

function isNewRecord($db, $userID): bool {
    $score = getInfoByID(USER_SCORE, $db, $userID);
    $maxScore = getInfoByID(USER_MAX_SCORE, $db, $userID);
    if ($score > $maxScore) {
        return true;
    } else {
        return false;
    }
}

function addNewGlobalRating($db, $userID, $firstName, $lastName, $userName): void {
    $placeFound = FALSE;
    $placeForChange = 1;
    $score = getInfoByID(USER_SCORE, $db, $userID);
    $scoreFromTop = "";
    while ($placeFound == FALSE):
        $scoreFromTop = getScoreByPlace($db, $placeForChange);
        if ($placeForChange == 4) {
            $placeFound = TRUE;
        }
        if ($scoreFromTop < $score) {
            $placeFound = TRUE;
        } elseif ($scoreFromTop == $score) {
            $placeFound = TRUE;
        }
        if ($placeFound == FALSE) {
            $placeForChange = $placeForChange + 1;
        }
    endwhile;

    if ($placeForChange == 1) {
        replaceRecords($db, 2, 3);
        replaceRecords($db, 1, 2);
        putNewRecord($db, $userID, $firstName, $lastName, $score, $placeForChange);
    } elseif ($placeForChange == 2) {
        replaceRecords($db, 2, 3);
        putNewRecord($db, $userID, $firstName, $lastName, $score, $placeForChange);
    } elseif ($placeForChange == 3) {
        putNewRecord($db, $userID, $firstName, $lastName, $score, $placeForChange);
    }
}
?>
