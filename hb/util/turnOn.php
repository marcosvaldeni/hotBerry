<?php

  $stards = time(); 


  $ends = $_POST["ends"] + time();
  $user_id = $_SESSION["user_id"]
  $keycode = $_SESSION["keycode"]  


  $sql = "CALL createSchedule(?, ?, ?, ?);";
  $sth = $conn -> prepare($sql);
  $sth -> bindParam(1, $user_id, PDO::PARAM_INT);
  $sth -> bindParam(2, $keycode, PDO::PARAM_STR);
  $sth -> bindParam(3, $stards, PDO::PARAM_INT);
  $sth -> bindParam(4, $ends, PDO::PARAM_INT);
  $sth -> execute();
?>