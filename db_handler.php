<?php
    require_once("config.php");

    function dbInit(): mysqliDB {
        $db = new mysqlibDb(HEROKU_DB_HOST, HEROKU_DB_USER, HEROKU_DB_PASS, HEROKU_DB_NAME);
        $db->autoReconnect = TRUE;
        return $db;
    }

    function isNewPlayer($db, $userID, $userName): bool {
        $db->where('userID', $userID);
        $scoreDb = $db->getOne("users", null, "userID");
        if ($scoreDb["userID"]) {
          return FALSE;
        } else {
           return TRUE;
        }
    }

    function createNewAccount($db, $userID, $userName) :void {
        $query = "insert into users(userID, userName, userScore, currentQuest, maxScore, EndIsNear) values($userID, '$name', 0, 0, 0, 0)";
        $db->query($query);
        return;
    }


    function resetTheGame($db, $userID): void {
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
        return;
    }
?>