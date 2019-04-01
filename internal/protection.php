<?php
//  USER AREA
session_start();

if ($_SESSION["user_level"] != 3) {
  header("Location: ../index.php");
}
?>
