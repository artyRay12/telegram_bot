<?php

  $questLevels = rand(2, 4);
  $questionsRequest = "https://engine.lifeis.porn/api/millionaire.php?q=$questLevels";
  $update = json_decode(file_get_contents($questionsRequest), JSON_OBJECT_AS_ARRAY);

    function getPosibleAnswers($update, $userID, $db): array
    {
        $answersID = [];
        $isAnswersReady = FALSE;
        $answersCounter = 0;
        pushRightAnswerInDB($db, $userID, $update);
        while ($isAnswersReady == FALSE):
            $randID = rand(0, 3);
            if (in_array($randID, $answersID)) {
            } else {
                array_push($answersID, $randID);
                $answersCounter = $answersCounter + 1;
            }
            if ($answersCounter == 4)
                $isAnswersReady = TRUE;
        endwhile;
        return [[$update["data"]["answers"][$answersID[0]], $update["data"]["answers"][$answersID[1]]], [$update["data"]["answers"][$answersID[2]], $update["data"]["answers"][$answersID[3]]]];
    }



?>






