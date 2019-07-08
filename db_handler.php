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

    function createNewAccount($db, $userID, $userName): void {
      $query = "insert into users(userID, userName, userScore, currentQuest, maxScore, rightAnswer) values($userID, '$userName', 0, 'empty', 0, 'epmty')";
      $db->query($query);
      return;
    }

    function resetTheGame($db, $userID): void {
        $data = Array('userScore' => 0);
        $db->where('userID', $userID);
        $db->update('users', $data);

        $data = Array('currentQuest' => 0);
        $db->where('userID', $userID);
        $db->update('users', $data);
    }


    function pushRightAnswerInDB($db, $userID, $update): void {
        $data = Array('rightAnswer' => $update["data"]["answers"][0]);
        $db->where('userID', $userID);
        $db->update('users', $data);
        return;
    }

    function getRightAnwerFromDB($db, $userID): string {
        $db->where('userID', $userID);
        $rightAnswer = $db->get("users", null, "rightAnswer");//получаем номер квеста
        return isset($rightAnswer[0]["rightAnswer"]) ? $rightAnswer[0]["rightAnswer"] : "";
    }

    function addPoint($db, $userID): void {
        $data = Array('userScore' => $db->inc(20),);
        $db->where('userID', $userID);
        $db->update('users', $data);
        return;
    }

    function getCurrentQuestId($db, $userID): string {
        $db->where('userID', $userID);
        $questId = $db->get("users", null, "currentQuest");//получаем номер квеста
        return isset($questId[0]["currentQuest"]) ? $questId[0]["currentQuest"] : "";
    }

    function increaseQuestCounter($db, $userID): void {
        $data = Array('currentQuest' => $db->inc(1),);
        $db->where('userID', $userID);
        $db->update('users', $data);
        return;
    }

    function getUserScore($db, $userID): string {
        $db->where('userID', $userID);
        $scoreDb = $db->get("users", null, "userScore");
        return isset($scoreDb[0]["userScore"]) ? $scoreDb[0]["userScore"] : "";
    }

    function getUserMaxScore($db, $userID): string {
        $db->where('userID', $userID);
        $scoreDb = $db->get("users", null, "maxScore");
        return isset($scoreDb[0]["maxScore"]) ? $scoreDb[0]["maxScore"] : "";
    }

    function addPersonalRecord($db, $userID): void {
        $data = Array('maxScore' => getUserScore($db, $userID));
        $db->where('userID', $userID);
        $db->update('users', $data);
        return;
    }

   /* function getScoreByPlace($db, $place): string {
        $db->where('place', $place);
        $score = $db->getOne("topplayers", null, "score");
        return isset($score["Score"]) ? $score["Score"] : "";
    }

    function getUserInfoByPlace($db, $place ): array {
        $dbRequest = ['userID', 'userName', 'Score'];
        $db->where('place', $place);
        $dbUser =  $db->getOne("topplayers", null, $dbRequest);
        return $dbUser;
    }*/

    function getInfoByPlace($info, $db, $place) {
        if ($info == ALL_INFORMATION){
            $dbRequest = ['userID', 'userName', 'Score'];
            $db->where('place', $place);
            $dbUser =  $db->getOne("topplayers", null, $dbRequest);
            return $dbUser;
        }elseif ($info == SCORE) {
            $db->where('place', $place);
            $score = $db->getOne("topplayers", null, "score");
            return isset($score["Score"]) ? $score["Score"] : "";
        }
    }


    function replaceRecords($db, $from, $where): void {
        $userData = [];
        $userData = getInfoByPlace(ALL_INFORMATION, $db, $from);
        $data = Array('userID' => $userData["userID"],
            'userName' => $userData["userName"],
            'Score' => $userData["Score"]);
        $db->where('place', $where);
        $db->update('topplayers', $data);
    }

    function putNewRecord($db, $userID, $userName, $score, $placeForChange): void {
        $data = Array('userID' =>$userID,
            'userName' => $userName,
            'Score' => $score);
        $db->where('place', $placeForChange);
        $db->update('topplayers', $data);
    }

    function showTopPlayers($db, $telegram, $chat_id, $reply_markup) {
       $info = [];
       for($i = 1; $i <= 3; $i++) {
          $info = getInfoByPlace(ALL_INFORMATION, $db, $i);
           $telegram->sendMessage(['chat_id' => $chat_id,
                                   'text' => $i . ". " . $info["userName"] . ": " . $info["Score"]
               . " баллов",
               'reply_markup' => $reply_markup]);
       }
    }

?>