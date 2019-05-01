<?php
require_once("hb/util/connection.php"); 
require_once("hb/util/init.php");
require_once("hb/util/functions.php");

$err;

if(isset($_GET['r'])) {
  $err = checkError($_GET['r']);
}

if ($_POST) {

  $email = $_POST["email"];
  $pass = $_POST["pass"];

  if (!empty($email) || !empty($pass)) {

    $sql = "SELECT users.user_id, users.user_name, users.user_email, users.user_pass, relation.relation_level as user_level, relation.keycode_key FROM users 
            INNER JOIN  relation ON relation.user_id = users.user_id WHERE users.user_email = :email LIMIT 1;";
    $stmt = $conn -> prepare($sql);
    $stmt -> bindValue(':email', $email, PDO::PARAM_STR);
    $stmt -> execute();
    $row = $stmt->fetch();

    if (!empty($row)) {

      if(password_verify($pass, $row['user_pass'])){
        session_start();

        $_SESSION["user_id"] = $row['user_id'];
        $_SESSION["user_name"] = $row['user_name'];
        $_SESSION["user_email"] = $row['user_email'];
        $_SESSION["user_level"] = $row['user_level'];
        $_SESSION["keycode"] = $row['keycode_key'];
        $_SESSION["selected"] = false;

        $sql = "SELECT count(*) as devices FROM relation
        INNER JOIN users ON relation.user_id = users.user_id
        WHERE users.user_id = :id;";
        $stmt = $conn -> prepare($sql);
        $stmt -> bindValue(':id', $_SESSION["user_id"], PDO::PARAM_INT);
        $stmt -> execute();
        $devices = $stmt->fetch();

        $_SESSION["devices"] = $devices['devices'];
        
        header("Location: hb/");

      }else{
        $err = checkError(9);
      }
    }

  } else {

    $err = checkError(9);
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include("hb/util/header.php"); ?> 
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
          <h1><i class="fa fa-user"></i> HotBerry</h1>
        </div>
      </div>
    </div>
  </header>

  <!-- ACTIONS -->
  <section id="action" class="py-4 mb-4 bg-light">
    <div class="container">
      <div class="row">
        <div class="col-md-3">
        <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#register">Register</button>
        </div>
      </div>
    </div>
  </section>

  <?php if (isset($err)) {?>
	<section id="info">
		<div class="container">
			<div class="row">
				<div class="col-md-6 m-auto">
					<div class="alert alert-<?= $err[0]?> alert-dismissible fade show">
							<button class="close" data-dismiss="alert" type="button">
									<span>&times;</span>
							</button>
							<strong><?= $err[1]?></strong>
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
                  <small class="form-text text-muted"><a href="">Forgot Password</a></small>
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
  <div class="modal fade" id="register" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Create Account</h5>
          <button class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form id="modal-form" action="hb/util/register.php" method="POST">
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

  <?php include("hb/util/footer.php"); ?> 
  <!--
  <script>
    $(document).ready(function() {
      $('#register').modal('show');
    });
  </script>
  -->
</body>
</html>
