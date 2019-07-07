<?php
    require_once("config.php");
    require_once("engineLifeIsAPI.php");

    function dbInit(): mysqliDB {
        $db = new mysqliDb(HEROKU_DB_HOST, HEROKU_DB_USER, HEROKU_DB_PASS, HEROKU_DB_NAME);
        $db->autoReconnect = TRUE;
        return $db;
    }

    function getUserID($db, $userID): string {
      $db->where('userID', $userID);
      $userInfo = $db->getOne("users", null, "userID");
      $userInfo["userID"] = isset($userInfo["userID"]) ? $userInfo["userID"] : "";
      return $userInfo["userID"];
    }

    function createNewAccount($db, $userID, $userName): void
    {
      $query = "insert into users(userID, userName, userScore, currentQuest, maxScore, EndIsNear) values($userID, '$userName', 0, 0, 0, 0)";
      $db->query($query);
      return;
    }

    function resetTheGame($db, $userID): void
    {
        $data = Array('userScore' => 0);
        $db->where('userID', $userID);
        $db->update('users', $data);

        $data = Array('currentQuest' => 0);
        $db->where('userID', $userID);
        $db->update('users', $data);
    }


    function pushRightAnswerInDB($db, $userID, $update): void
    {
        $data = Array('rightAnswer' => $update["data"]["answers"][0]);
        $db->where('userID', $userID);
        $db->update('users', $data);
        return;
    }

    function getRightAnwerFromDB($db, $userID, $update): string
    {
        $db->where('userID', $userID);
        $questIdDb = $db->get("users", null, "rightAnswer");//получаем номер квеста
        return isset($questIdDb[0]["rightAnswer"]) ? $questIdDb[0]["rightAnswer"] : "";
    }



?>