<?php
  require_once("connection.php");
  require_once("init.php");

  $email = $_POST['email'];
  $pass = $_POST['pass'];
  $pass2 = $_POST['pass2'];
  $key = $_POST['key'];

  $check = true;
  $keycode_id;
  $err;

  if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $check = false;
    $err = 3;
  }

  if ($check) {
    $sql = "SELECT checkEmailAndKey(:email, :codekey) as checking";
    $stmt = $conn -> prepare($sql);
    $stmt -> bindValue(':email', $_POST['email'], PDO::PARAM_STR);
    $stmt -> bindValue(':codekey', $_POST['key'], PDO::PARAM_STR);
    $stmt -> execute();
    $row = $stmt->fetch();

    if ($row['checking'] != "ok") {
      $check = false;

      $err = $row['checking'];
    }
  }

  if ($check == true && strlen($_POST['pass']) < 6) {
    $check = false;
    $err = 5;
  }

  if ($check == true && $_POST['pass'] != $_POST['pass2']) {
    $check = false;
    $err = 6;
  }

  if ($check) {

    $hashedPass = password_hash($pass,PASSWORD_DEFAULT);

    $sql = "CALL createAdmin(?, ?, ?, @err);";
    $sth = $conn -> prepare($sql);
    $sth -> bindParam(1, $key, PDO::PARAM_STR);
    $sth -> bindParam(2, $email, PDO::PARAM_STR);
    $sth -> bindParam(3, $hashedPass, PDO::PARAM_STR);


    $sth -> execute();
    header("Location: ".$BASE."/?r=8");
  } else {
    header("Location: ".$BASE."/index.php?r=".$err);
  }
?>