<?php
// CONTROL AREA
session_start();

if ($_SESSION["user_level"] != 1) {
  header("Location: ../index.php");
}
?>
