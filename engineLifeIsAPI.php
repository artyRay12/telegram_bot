<?php

  $questLevels = rand(2, 4);
  $questionsRequest = "https://engine.lifeis.porn/api/millionaire.php?q=$questLevels";
  $update = json_decode(file_get_contents($questionsRequest), JSON_OBJECT_AS_ARRAY);








?>






