<?php
include("connection.php");

$email = $_POST['email'];
$pass = $_POST['pass'];
$pass2 = $_POST['pass2'];
$key = $_POST['key'];

$check = true;
$keycode_id;

if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
  $check = false;
  header("Location: index.php?r=ie");
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
    header("Location: index.php?r=".$row['checking']);
  }
}

if ($check == true && strlen($_POST['pass']) < 6) {
  $check = false;
  header("Location: index.php?r=ps");
}

if ($check == true && $_POST['pass'] != $_POST['pass2']) {
  $check = false;
  header("Location: index.php?r=pm");
}


if ($check) {

  $hashedPass = password_hash($pass,PASSWORD_DEFAULT);

  $sql = "CALL createAdmin(?, ?, ?, @err);";
  $sth = $conn -> prepare($sql);
  $sth -> bindParam(1, $key, PDO::PARAM_STR);
  $sth -> bindParam(2, $email, PDO::PARAM_STR);
  $sth -> bindParam(3, $hashedPass, PDO::PARAM_STR);


  $sth -> execute();
  header("Location: index.php?r=ok");
} 
?>