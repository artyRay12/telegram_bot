<?php
    require_once("config.php");
    require_once("engineLifeIsAPI.php");

    function dbInit(): mysqliDB {
        $db = new mysqliDb(HEROKU_DB_HOST, HEROKU_DB_USER, HEROKU_DB_PASS, HEROKU_DB_NAME);
        $db->autoReconnect = TRUE;
        return $db;
    } 

    function createNewAccount($db, $userID, $userName): void {
        $query = "insert into users(userID, userName, userScore, currentQuest, maxScore, rightAnswer) values('$userID', '$userName', '0', '0', '0', '0')" or die(mysqli_error($db));
        $db->query($query);
    }

    function resetTheGame($db, $userID): void {
        $data = Array('userScore' => 0);
        $db->where('userID', $userID);
        $db->update('users', $data);

        $data = Array('currentQuest' => 0);
        $db->where('userID', $userID);
        $db->update('users', $data);
    }
    
    function pushRightAnswerInDB($db, $userID, $questionRequest): void {
        $data = Array('rightAnswer' => $questionRequest["data"]["answers"][0]);
        $db->where('userID', $userID);
        $db->update('users', $data);
    }
   
     function addPoint($db, $userID): void {
        $data = Array('userScore' => $db->inc(20),);
        $db->where('userID', $userID);
        $db->update('users', $data);
    }

   function getInfoByID($collumnName, $db, $userID): string  {
       if ($collumnName == USER_ID) {
            $db->where('userID', $userID);
            $userInfo = $db->getOne("users", null, "userID");
            return isset($userInfo["userID"]) ? $userInfo["userID"] : "";
       } else {
            $db->where('userID', $userID);
            $dbInfo = $db->get("users", null, $collumnName);
            return isset($dbInfo[0]["$collumnName"]) ? $dbInfo[0]["$collumnName"] : "";
       }
   }
       
    function increaseQuestCounter($db, $userID): void {
        $data = Array('currentQuest' => $db->inc(1),);
        $db->where('userID', $userID);
        $db->update('users', $data);
    }

    function addPersonalRecord($db, $userID): void {
        $data = Array('maxScore' => getInfoByID(USER_SCORE, $db, $userID));
        $db->where('userID', $userID);
        $db->update('users', $data);
    }

    function getScoreByPlace($db, $place): string {
        $db->where('place', $place);
        $score = $db->getOne("topplayers", null, "score");
        return isset($score["Score"]) ? $score["Score"] : "";
    }

    function getUserInfoByPlace($db, $place ): array {
        $dbRequest = ['userID', 'userName', 'Score'];
        $db->where('place', $place);
        $dbUser =  $db->getOne("topplayers", null, $dbRequest);
        return $dbUser;
    }

    function replaceRecords($db, $from, $where): void {
        $userData = [];
        $userData = getUserInfoByPlace($db, $from);
        $data = Array('userID' => $userData["userID"],
                      'userName' => $userData["userName"],
                      'Score' => $userData["Score"]);
        $db->where('place', $where);
        $db->update('topplayers', $data);
    }

    function putNewRecord($db, $userID, $firstName, $lastName, $score, $placeForChange): void {
        $data = Array('userID' =>$userID,
                      'userName' => $firstName . " " . $lastName,
                      'Score' => $score);
        $db->where('place', $placeForChange);
        $db->update('topplayers', $data);
    }

    function showTopPlayers($db, $telegram, $chat_id, $reply_markup) {
       $info = [];
       for($i = 1; $i <= 3; $i++) {
          $info = getUserInfoByPlace($db, $i);
           $telegram->sendMessage(['chat_id' => $chat_id,
                                   'text' => $i . ". " . $info["userName"] . ": " . $info["Score"]
                                   . " баллов",
                                   'reply_markup' => $reply_markup]);
       }
    }


?>
