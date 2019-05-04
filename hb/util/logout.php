<?php
  // Import of components and additional pages
  require_once("init.php");
  session_start();
  
  // Destroys all existing sessions.
  session_destroy();
  header("Location:  ".$BASE."/index.php?r=12");
 ?>
