<?php 
	include("../util/protection.php");
  include("../util/init.php");
  include("../util/connection.php");

  $msg = "";
  $email = $_POST['user_email'];
  $pass = mb_substr(password_hash($email,PASSWORD_DEFAULT), 0, 6);
  $keycode = $_SESSION["keycode"];


  if ($email == "") {
      $msg = "em";
  }else{
    
    $hashedPass = password_hash($pass,PASSWORD_DEFAULT);

    $sql = "CALL createUser(?,?,?);";
    $sth = $conn -> prepare($sql);
    $sth -> bindParam(1, $email, PDO::PARAM_STR);
    $sth -> bindParam(2, $hashedPass, PDO::PARAM_STR);
    $sth -> bindParam(3, $keycode, PDO::PARAM_STR);

    $sth->execute();
    $msg = "ok";
    //emailUser($email, $pass);

    echo $msg;
    header("Location: add.php?r=".$msg);
  }


?>