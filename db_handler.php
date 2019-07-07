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



?>