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
  $sql = "SELECT * FROM users WHERE user_email = :email;";
  $stmt = $conn -> prepare($sql);
  $stmt -> bindValue(':email', $_POST['email'], PDO::PARAM_STR);
  $stmt -> execute();
  $result = $stmt->fetchAll();

  if ($result) {
    $check = false;
    header("Location: index.php?r=er");
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
  $sql = "SELECT * FROM keycodes WHERE keycode_key = :key HAVING keycode_used = 0 LIMIT 1;";
  $stmt = $conn -> prepare($sql);
  $stmt -> bindValue(':key', $_POST['key'], PDO::PARAM_STR);
  $stmt -> execute();
  $result = $stmt->fetchAll();

  if ($result) {

    foreach($result as $row){
      $keycode_id = $row['keycode_id'];
      echo $keycode_id.'<br>';
    }

  } else {
    $check = false;
    header("Location: index.php?r=ik");
  }
}

echo $keycode_id.'<br>';

if ($check) {

  $hashedPass = password_hash($pass,PASSWORD_DEFAULT);
  $level = 1;

  echo $keycode_id.'<br>';

  $upd = $conn -> prepare("UPDATE keycodes SET keycode_used = :keycode_used WHERE keycode_id = :id");
  $upd -> bindValue(":id", $keycode_id);
  $upd -> bindValue(":keycode_used", true);
  $upd -> execute();

  $sql = "INSERT INTO users (user_email, user_pass, user_level, keycode_id) VALUES (?, ?, ?, ?);";
  $sth = $conn -> prepare($sql);
  $sth -> bindParam(1, $email, PDO::PARAM_STR);
  $sth -> bindParam(2, $hashedPass, PDO::PARAM_STR);
  $sth -> bindParam(3, $level, PDO::PARAM_INT);
  $sth -> bindParam(4, $keycode_id, PDO::PARAM_INT);

  $sth -> execute();
  header("Location: index.php?r=ok");

} else {
  header("Location: index.php");
}
?>