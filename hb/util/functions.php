<?php
  //  This function is used to create a boiler task. It calls
  // the Stored Procedure that is explained in details in the
  // Database section
  function boilerDirect($duration, $user_id, $keycode, $conn) {
    $stards = time(); 
    $ends = time(); 
    $ends += $duration;
    
    $sql = "CALL createSchedule(?, ?, ?, ?);";
    $sth = $conn -> prepare($sql);
    $sth -> bindParam(1, $user_id, PDO::PARAM_INT);
    $sth -> bindParam(2, $keycode, PDO::PARAM_STR);
    $sth -> bindParam(3, $stards, PDO::PARAM_INT);
    $sth -> bindParam(4, $ends, PDO::PARAM_INT);
    $sth -> execute();
  }

  // This functions takes care of all the error messages in the application
  // It works like an error dictionary
  // It returns a string. An information to the CSS/ bootstrap
  // and a message to the user
  function checkError($int) {

    if ($int == 1) {
      $err[0] = "danger";
      $err[1] = "Email already registered!";
    } elseif ($int == 2) {
      $err[0] = "success";
      $err[1] = "Successful registration!";
    } elseif ($int == 3) {
      $err[0] = "danger";
      $err[1] = "Invalid Email!";
    }elseif ($int == 4) {
      $err[0] = "danger";
      $err[1] = "Email already registered!";
    } elseif ($int == 5) {
      $err[0] = "danger";
      $err[1] = "Password must have at least six characters!";
    } elseif ($int == 6) {
      $err[0] = "danger";
      $err[1] = "Passwords does not math!";
    } elseif ($int == 7) {
      $err[0] = "danger";
      $err[1] = "Key is invalid!";
    } elseif ($int == 8) {
      $err[0] = "danger";
      $err[1] = "Congratulations You're Registered!";
    } elseif ($int == 9) {
      $err[0] = "danger";
      $err[1] = "Invalid username or password.";    
    } elseif ($int == 10) {
      $err[0] = "danger";
      $err[1] = "Your account has been deleted, please contact your admin!";
    } else {
      $err[0] = "warning";
      $err[1] = "Error 500";
    }
    
    return $err;

  }

  // This function sends a message to the user's email
  // acoording to the system's needs
  function emailUser($email, $message) {
    
    $from = "system@hotberry.co";
    $to = $_POST["eMail"];
    $generatePass = md5(date("y-m-d h:i:s"));
    $code = substr($generatePass,0,7);
    $pass = md5($code);
    $subject = "Register on Hotberry.co";
    $message = "
        Thank you for registering at hotBerry
        Here is the access data
        Password: $pass";
    $headers = "From:". $from;        
    mail($to, $subject, $message, $headers);

  }

?>