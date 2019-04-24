<?php
  require_once("init.php");
  session_start();

  if ($_SESSION["user_level"] > 2 || $_SESSION["user_level"] < 1) {
    header("Location:  ".$BASE);
  }
?>
