<?php
  include("../protection.php");
  include("../../connection.php");

  $id = $_GET["bf"];

  
   
  $sql = "UPDATE users SET user_status = 0 WHERE user_id = ?;";
  $sth = $conn -> prepare($sql);
  $sth -> bindParam(1, $id, PDO::PARAM_INT);
  $sth -> execute();
  header("Location: index.php");
?>