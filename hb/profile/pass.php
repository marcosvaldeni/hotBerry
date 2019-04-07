<?php
	require_once("../util/protection.php");
	require_once("../util/connection.php");
	require_once("../util/functions.php");

	$msg = "";
	if (isset($_POST['login_btn'])) {

		$user_id = $_SESSION["user_id"];
		$user_pass = $_POST['user_pass'];
		$pass1 = $_POST['pass1'];
		$pass2 = $_POST['pass2'];


		if ($user_pass == "" || $pass1 == "" || $pass2 == "") {
			$msg = "erro1";
		}elseif( $pass1 != $pass2) {
			$msg = "erro2";
		}else{
			$user_id = $_SESSION["user_id"];
			$sql = "SELECT * FROM users WHERE user_id = :id";
			$stmt = $conn -> prepare($sql);
			$stmt -> bindValue(':id', $user_id, PDO::PARAM_STR);
			$stmt -> execute();
			$row = $stmt->fetch();

			if(password_verify($user_pass,$row['user_pass'])){
				$passHash = password_hash($pass2,PASSWORD_DEFAULT);
				$upd = $conn -> prepare("UPDATE users SET user_pass = :pass WHERE user_id = :id");
				$upd -> bindValue(":pass", $passHash);
				$upd -> bindValue(":id", $user_id);
				$upd -> execute();

				$msg = "ok";
			}else {
				$msg = "erro";
			}
		}
	}

	if (isset($_POST['turnOn'])) {
    boilerDirect($_POST["ends"], $_SESSION["user_id"], $_SESSION["keycode"], $conn);
  }
?>
<!DOCTYPE html>
<html>
<head>
	<?php include("../util/header.php"); ?> 
</head>
<body>

	<?php include("../util/nav.php"); ?> 
	
	<header id="main-header" class="py-2 bg-primary text-white">
		<div class="container">
			<div class="row">
				<div class="col-md-6">
					<h1><i class="fa fa-lock"></i> Password</h1>
				</div>
			</div>
		</div>
	</header>

	<!-- ACTIONS -->
	<section id="action" class="py-4 mb-4 bg-light">
		<div class="container">
			<div class="row">
				<div class="col-md-3 mr-auto">
					<a href="index.php" class="btn btn-light btn-block">
						<i class="fa fa-arrow-left"></i> Back To Dashboard
					</a>
				</div>
				<?php include("../util/boilerBtn.php"); ?>
			</div>
		</div>
	</section>

	<!-- PASSWORD EDIT -->
	<?php if ($msg == "erro") {?>
  <section id="info">
    <div class="container">
      <div class="row">
        <div class="col-md-5 m-auto">
          <div class="alert alert-danger alert-dismissible fade show">
              <button class="close" data-dismiss="alert" type="button">
                  <span>&times;</span>
              </button>
              <strong>Invalid password!</strong>
          </div>
        </div>
      </div>
    </div>
  </section>
  <?php } ?>
	<?php if ($msg == "erro1") {?>
	<section id="info">
		<div class="container">
			<div class="row">
				<div class="col-md-5 m-auto">
					<div class="alert alert-warning alert-dismissible fade show">
							<button class="close" data-dismiss="alert" type="button">
									<span>&times;</span>
							</button>
							<strong>Please, the password must have at least six characters.</strong>
					</div>
				</div>
			</div>
		</div>
	</section>
<?php } ?>	<?php if ($msg == "erro2") {?>
		<section id="info">
			<div class="container">
				<div class="row">
					<div class="col-md-5 m-auto">
						<div class="alert alert-warning alert-dismissible fade show">
								<button class="close" data-dismiss="alert" type="button">
										<span>&times;</span>
								</button>
								<strong>The passwords does not match!</strong>
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
				<div class="col-md-5 m-auto">
					<div class="alert alert-success alert-dismissible fade show">
							<button class="close" data-dismiss="alert" type="button">
									<span>&times;</span>
							</button>
							<strong>Password changed successfully!</strong>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php } ?>
	<section id="password">
		<div class="container">
			<div class="row">
				<div class="col-md-5 m-auto">
					<div class="card">
						<div class="card-header">
							<h4>Edit Profile</h4>
						</div>
						<div class="card-body">
							<form action="" method="POST">
								<div class="form-group">
									<label for="password">Old Password:</label>
									<input type="password" class="form-control" name="user_pass">
								</div>
								<div class="form-group">
									<label for="password">New Password:</label>
									<input type="password" class="form-control" name="pass1">
								</div>
								<div class="form-group">
									<label for="password">Repeat Password:</label>
									<input type="password" class="form-control" name="pass2">
								</div>
								<div class="form-group">
									<br/>
									<button class="btn btn-primary" name="login_btn" type="submit">Save</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php include("../util/boilerModal.php"); ?>

	<?php include("../util/footer.php"); ?>  

</body>
</html>
