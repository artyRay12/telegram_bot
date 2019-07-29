<?php

  $questLevels = rand(2, 4);
  $questionsRequest = "https://engine.lifeis.porn/api/millionaire.php?q=$questLevels";
  $questionRequest = json_decode(file_get_contents($questionsRequest), JSON_OBJECT_AS_ARRAY);

  function getPosibleAnswers($questionRequest, $userID, $db): array { //получаем варианты ответов с API
       $answersID = [];
       $isAnswersReady = FALSE;
       $answersCounter = 0;
       pushRightAnswerInDB($db, $userID, $questionRequest);  //сохраняем правильный вариант ответа в БД, чтобы потом сравнить.
       while ($isAnswersReady == FALSE):  //этот цикл замешивает числа от 0-3 рандомно в массив, чтобы варианты ответа выводились в разном порядке
           $randID = rand(0, 3);
           if (in_array($randID, $answersID)) {
           } else {
               array_push($answersID, $randID);
               $answersCounter = $answersCounter + 1;
           }
           if ($answersCounter == 4)
               $isAnswersReady = TRUE;
       endwhile;
       return [[$questionRequest["data"]["answers"][$answersID[0]],
              $questionRequest["data"]["answers"][$answersID[1]]],
              [$questionRequest["data"]["answers"][$answersID[2]],
              $questionRequest["data"]["answers"][$answersID[3]]]];
  }

  function getQuestText($questionRequest): string {
       return $questionRequest["data"]["question"];
    }
?>






