<?php
    require_once("config.php");

    function dbInit(): mysqliDB {
        $db = new mysqliDb(HEROKU_DB_HOST, HEROKU_DB_USER, HEROKU_DB_PASS, HEROKU_DB_NAME);
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

    function pushRightAnswerInDB($db, $userID, $update): void {
        $data = Array ('rightAnswer' => $update["data"]["answers"][0]);
        $db->where ('userID', $userID);
        $db->update ('users', $data);
        return;
    }

    function getRightAnwerFromDB($db, $userID, $update): string {
        $db->where('userID', $userID);
        $questIdDb = $db->get ("users", null, "rightAnswer");//получаем номер квеста
        return isset($questIdDb[0]["rightAnswer"]) ? $questIdDb[0]["rightAnswer"] : "";
    }

    function isRightAnswer($db, $userID, $update, $text): bool {
        $rightAnswer = getRightAnwerFromDB($db, $userID, $update);
        if ($text == $rightAnswer) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function addPoint($db, $userID): void {
        $data = Array ('userScore' => $db->inc(20),);
        $db->where ('userID', $userID);
        $db->update ('users', $data);
     }

     function getPosibleAnswers($update): array {
         $answersID = [];
         $isAnswersReady = FALSE;
         $answersCounter = 0;
         while ($isAnswersReady == FALSE):
             $randID = rand(0, 3);
             if (in_array($randID, $answersID)){
             } else {
                 array_push($answersID, $randID);
                 $answersCounter = $answersCounter + 1;
             }
             if ($answersCounter == 4)
                 $isAnswersReady = TRUE;
         endwhile;
         return [[$update["data"]["answers"][$answersID[0]], $update["data"]["answers"][$answersID[1]]], [$update["data"]["answers"][$answersID[2]], $update["data"]["answers"][$answersID[3]]]];
     }

     function isLastQuestion($db, $userID): bool
     {
         //Получаем номер текущего вопроса
         $db->where('userID', $userID);
         $questIdDb = $db->get("users", null, "currentQuest");//получаем номер квеста
         $questDinId = isset($questIdDb[0]["currentQuest"]) ? $questIdDb[0]["currentQuest"] : "";

         if ($questDinId < 10) {
             return false;
         } else {
             return true;
         }
     }

     function testFinish($db, $userID): bool{
         $endIsNear = "";
         //==Получаем данные о конце викторины
         $db->where('userID', $userID);
         $scoreDb = $db->get("users", null, "endIsNear");
         $endIsNear = isset($scoreDb[0]["endIsNear"]) ? $scoreDb[0]["endIsNear"] : "";
         if ($endIsNear == 0) {
             return false;
         } else {
             return true;
         }
     }

?>