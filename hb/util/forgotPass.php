<?php 
  // Import of components and companion pages
  include("../util/init.php");
  include("../util/connection.php");

  $email = $_POST['email'];
  $pass = mb_substr(md5(time()), 0, 6);
  echo $email;

  $sql = "SELECT COUNT(*) as number FROM users WHERE user_email = :email;";
  $stmt = $conn -> prepare($sql);
  $stmt -> bindValue(':email', $email, PDO::PARAM_STR);
  $stmt -> execute();
  $row = $stmt->fetch();

  //Checks if the email field is empty
  if ($email == "") {

    header("Location: ".$BASE."/index.php?r=3");
  }elseif ($row['number'] == 0) {

    header("Location: ".$BASE."/index.php?r=1");
  } else {
    
    $hashedPass = password_hash($pass,PASSWORD_DEFAULT);

    $sql = "UPDATE users SET user_pass = ? WHERE user_email = ?;";
    $sth = $conn -> prepare($sql);
    $sth -> bindParam(1, $hashedPass, PDO::PARAM_STR);
    $sth -> bindParam(2, $email, PDO::PARAM_STR);
    $sth->execute();

    $subject = "Reset password on Hotberry.co";
    $message = "
        Thanks for use hotBerries!
        Follow your data to access:
        Your login: $email
        Your password: $pass";
    $headers = "From: system@hotberries.co";        
    mail($email, $subject, $message, $headers);

    header("Location: ".$BASE."/index.php?r=2");
  }

?>