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
            echo $i  . " место";
            $placeFound = TRUE;
        } elseif ($scoreByPlace == $score) {
            echo "равно" . ($placeForChange) . "месту";
            $placeFound = TRUE;
        }
        $placeForChange = $placeForChange + 1;
    endwhile;

    function SecondReplaceThird($db): string {
        $userData = [];
        $userData = getUserInfoByPlace($db, 2);
        replaceRecords($db, $userData, 3);
        return '123';
    }

    function firstReplaceSecond($db): void {
        $userData = [];
        $userData = getUserInfoByPlace($db, 1);
        replaceRecords($db, $userData, 2);
    }

    if ($placeForChange == 1) {
        SecondReplaceThird($db);
        firstReplaceSecond($db);
        $data = Array('userID' =>$userID,
            'userName' => $userName,
            'Score' => $score);
        $db->where('place', $placeForChange);
        $db->update('topplayers', $data);
    } elseif ($placeForChange == 2) {
        SecondReplaceThird($db);
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