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

function addNewGlobalRating($db, $userID, $userName) {
    $placeFound = FALSE;
    $placeForChange = 1;
    $score = getUserScore($db, $userID);
    while ($placeFound == FALSE):

        $scoreByPlace = getScoreByPlace($db, $placeForChange);
        if ($scoreByPlace < $score) {
            $placeFound = TRUE;
        } elseif ($scoreByPlace == $score) {
            $placeFound = TRUE;
        }
        if ($placeFound == FALSE) {
            $placeForChange = $placeForChange + 1;
        }
    endwhile;

    function replaceRecords($db, $from, $where) {
        $userData = [];
        $userData = getUserInfoByPlace($db, $from);
        replaceRecords($db, $userData, $where);
    }

    if ($placeForChange == 1) {
        replaceRecords($db, 1, 2);
        replaceRecords($db, 2, 3);
        $data = Array('userID' =>$userID,
            'userName' => $userName,
            'Score' => $score);
        $db->where('place', $placeForChange);
        $db->update('topplayers', $data);
    } elseif ($placeForChange == 2) {
        replaceRecords($db, 2, 3);
        $data = Array('userID' =>$userID,
            'userName' => $userName,
            'Score' => $score);
        $db->where('place', $placeForChange);
        $db->update('topplayers', $data);
    } else {
        $data = Array('userID' =>$userID,
            'userName' => $userName,
            'Score' => $score);
        $db->where('place', $placeForChange);
        $db->update('topplayers', $data);
    }
}




?>