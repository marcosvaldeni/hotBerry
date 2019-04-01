<?php
include("connection.php");
$msg = "";

if (isset($_GET['r'])) {
  $msg = $_GET['r'];
}

if ($_POST) {

  $email = $_POST["email"];
  $pass = $_POST["pass"];

  if (!empty($email) || !empty($pass)) {

    $sql = "SELECT * FROM users WHERE user_email = :email LIMIT 1";
    $stmt = $conn -> prepare($sql);
    $stmt -> bindValue(':email', $email, PDO::PARAM_STR);
    $stmt -> execute();
    $result = $stmt->fetchAll();

    if (!empty($result)) {

      foreach($result as $row) {

        if(password_verify($pass,$row['user_pass'])){
          session_start();
  
          $_SESSION["user_id"] = $row['user_id'];
          $_SESSION["user_name"] = $row['user_name'];
          $_SESSION["user_email"] = $row['user_email'];
          $_SESSION["user_level"] = $row['user_level'];
          $_SESSION["keycode_id"] = $row['keycode_id'];
          
          header("Location: admin/");

          echo $_SESSION["user_email"].'<br/>';
  
        }else{

          $msg = "error";
        }
      }

    } else {

      $msg = "error";
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>hotBerry</title>
  <link rel="stylesheet" href="css/font-awesome.min.css">
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <nav class="navbar navbar-expand-sm navbar-dark bg-dark p-0">
    <div class="container">
      <a href="index.php" class="navbar-brand">OutBox</a>
      <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
      </div>
    </div>
  </nav>

  <header id="main-header" class="py-2 bg-primary text-white">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <h1><i class="fa fa-user"></i> hotBerry</h1>
        </div>
      </div>
    </div>
  </header>

  <!-- ACTIONS -->
  <section id="action" class="py-4 mb-4 bg-light">
    <div class="container">
      <div class="row">
        <div class="col-md-3 mr-auto">
        <button class="btn btn-primary" data-toggle="modal" data-target="#register">Register</button>
        </div>
      </div>
    </div>
  </section>

  <!-- LOGIN -->
  <br/><br/>
  <?php if ($msg == "ie") {?>
  <section id="info">
    <div class="container">
      <div class="row">
        <div class="col-md-6 mx-auto">
          <div class="alert alert-danger alert-dismissible fade show">
              <button class="close" data-dismiss="alert" type="button">
                  <span>&times;</span>
              </button>
              <strong>Invalid Email!</strong>
          </div>
        </div>
      </div>
    </div>
  </section>
  <?php } ?>

  <?php if ($msg == "er") {?>
  <section id="info">
    <div class="container">
      <div class="row">
        <div class="col-md-6 mx-auto">
          <div class="alert alert-danger alert-dismissible fade show">
              <button class="close" data-dismiss="alert" type="button">
                  <span>&times;</span>
              </button>
              <strong>Email already registered!</strong>
          </div>
        </div>
      </div>
    </div>
  </section>
  <?php } ?>

  <?php if ($msg == "ps") {?>
  <section id="info">
    <div class="container">
      <div class="row">
        <div class="col-md-6 mx-auto">
          <div class="alert alert-danger alert-dismissible fade show">
              <button class="close" data-dismiss="alert" type="button">
                  <span>&times;</span>
              </button>
              <strong>Password must have at least six characters!</strong>
          </div>
        </div>
      </div>
    </div>
  </section>
  <?php } ?>

  <?php if ($msg == "mp") {?>
  <section id="info">
    <div class="container">
      <div class="row">
        <div class="col-md-6 mx-auto">
          <div class="alert alert-danger alert-dismissible fade show">
              <button class="close" data-dismiss="alert" type="button">
                  <span>&times;</span>
              </button>
              <strong>Passwords does not math!</strong>
          </div>
        </div>
      </div>
    </div>
  </section>
  <?php } ?>

  <?php if ($msg == "ik") {?>
  <section id="info">
    <div class="container">
      <div class="row">
        <div class="col-md-6 mx-auto">
          <div class="alert alert-danger alert-dismissible fade show">
              <button class="close" data-dismiss="alert" type="button">
                  <span>&times;</span>
              </button>
              <strong>Key is invalid!</strong>
          </div>
        </div>
      </div>
    </div>
  </section>
  <?php } ?>

  <?php if ($msg == "ok") {?>
  <section id="info">
    <div class="container">
      <div class="row">
        <div class="col-md-6 mx-auto">
          <div class="alert alert-success alert-dismissible fade show">
              <button class="close" data-dismiss="alert" type="button">
                  <span>&times;</span>
              </button>
              <strong>Congratulations You're Registered!</strong>
          </div>
        </div>
      </div>
    </div>
  </section>
  <?php } ?>

  <?php if ($msg == "error") {?>
  <section id="info">
    <div class="container">
      <div class="row">
        <div class="col-md-6 mx-auto">
          <div class="alert alert-danger alert-dismissible fade show">
              <button class="close" data-dismiss="alert" type="button">
                  <span>&times;</span>
              </button>
              <strong>Invalid username or password.</strong>
          </div>
        </div>
      </div>
    </div>
  </section>
  <?php } ?>

  <section id="login">
    <div class="container">
      <div class="row">
        <div class="col-md-6 mx-auto">
          <div class="card">
            <div class="card-header">
              <h4>Account Login</h4>
            </div>
            <div class="card-body">
              <form action="" method="POST">
                <div class="form-group">
                  <br/>
                  <input type="text" name="email" class="form-control" placeholder="eMail">
                </div>
                <div class="form-group">
                  <br/>
                  <input type="password" name="pass" class="form-control" placeholder="Password">
                </div>
                <br/>
                <input type="submit" class="btn btn-primary btn-block" value="Login">
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <br/><br/>

  <!-- REGISTER MODAL -->
  <div class="modal" id="register">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Create Account</h5>
          <button class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form id="modal-form" action="register.php" method="POST">
          <div class="form-group">
              <label for="email">eMail:</label>
              <input type="text" placeholder="eMail" class="form-control" name="email">
            </div>
            <div class="form-group">
              <label for="password">Password:</label>
              <input type="password" placeholder="At least six characters" class="form-control" name="pass">
            </div>
            <div class="form-group">
              <label for="password">Repeat Password:</label>
              <input type="password" placeholder="Repeat Password" class="form-control" name="pass2">
            </div>
            <div class="form-group">
              <label for="key">HotBerry Key:</label>
              <input type="text" placeholder="Key" class="form-control" name="key">
            </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary" type="submit">Register</button>
        </div>
        </form>
      </div>
    </div>
  </div>

  <footer id="main-footer" class="bg-dark text-white mt-5 p-5">
    <div class="conatiner">
      <div class="row">
        <div class="col">
          <p class="lead text-center">Copyright © 2019 HotBerry</p>
        </div>
      </div>
    </div>
  </footer>

  <script src="js/jquery.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
</body>
</html>
