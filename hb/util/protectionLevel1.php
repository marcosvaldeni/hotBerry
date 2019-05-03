<?php
  // Import of components and additional pages
  require_once("init.php");
  session_start();

    // If the user did not have enough credentials will be sent to the home page
  if ($_SESSION["user_level"] != 1) {
    header("Location:  ".$BASE);
  }
?>
