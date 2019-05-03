<?php
  // Import of components and companion pages
  include("../util/protectionLevel1.php");
	require_once("../util/connection.php");
  require_once("../util/init.php");

  $id = $_GET["bf"];

  $sql = "UPDATE users SET user_status = 0 WHERE user_id = ?;";
  $sth = $conn -> prepare($sql);
  $sth -> bindParam(1, $id, PDO::PARAM_INT);
  $sth -> execute();
  header("Location: index.php");
?>