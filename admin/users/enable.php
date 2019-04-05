<?php
  include("../protection.php");
  include("../../connection.php");

  $id = $_GET["bf"];

  $sql = "SELECT users.user_id FROM users
  INNER JOIN relation ON users.user_id = relation.user_id
  WHERE users.user_id = :id AND relation.keycode_key = :keycode AND relation.relation_level = 2;";
  $stmt = $conn -> prepare($sql);
  $stmt -> bindValue(':keycode', $_SESSION["keycode"], PDO::PARAM_INT);
  $stmt -> bindValue(':id', $id, PDO::PARAM_INT);
  $stmt -> execute();
  $row = $stmt->fetch();

  if ($row) {
   
    $sql = "UPDATE users SET user_status = 1 WHERE user_id = ?;";
    $sth = $conn -> prepare($sql);
    $sth -> bindParam(1, $id, PDO::PARAM_INT);
    $sth -> execute();
    header("Location: index.php");

  } else {
      
    header("Location: index.php");
  }
?>