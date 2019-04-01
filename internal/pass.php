<?php
include("protection.php");
include_once("../connection.php");

$msg = '';
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
		$row =  $conn -> query("SELECT * FROM users where user_id = $user_id") -> fetch(PDO::FETCH_ASSOC);

		if(password_verify($user_pass,$row['user_pass'])){
			$pass1 = password_hash($pass2,PASSWORD_DEFAULT);
			$upd = $conn -> prepare("UPDATE users SET user_pass = :user_pass WHERE user_id = :user_id");

			$upd -> bindValue(":user_pass", $pass1);
			$upd -> bindValue(":user_id", $user_id);
			$upd -> execute();

			$msg = "ok";
		}else {
			$msg = "erro";
		}
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>CCT Library</title>
	<link rel="stylesheet" href="../css/font-awesome.min.css">
	<link rel="stylesheet" href="../css/bootstrap.css">
	<link rel="stylesheet" href="../css/style.css">
</head>
<body>

	<!-- NAVBAR -->
	<nav class="navbar navbar-expand-sm navbar-dark bg-dark p-0">
		<div class="container">
			<a href="index.html" class="navbar-brand">CCT Library</a>
			<button class="navbar-toggler" data-toggle="collapse" data-target="#navbarNav">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarNav">
				<ul class="navbar-nav">
					<li class="nav-item px-2">
						<a href="index.php" class="nav-link">Dashboard</a>
					</li>
					<li class="nav-item px-2">
						<a href="#" class="nav-link">Books</a>
					</li>
					<li class="nav-item px-2">
						<a href="historic.php" class="nav-link">Historic</a>
					</li>
				</ul>
				<ul class="navbar-nav ml-auto">
					<li class="nav-item dropdown mr-3">
						<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
							<i class="fa fa-user"></i> Welcome <?php echo $_SESSION["user_name"]; ?>
						</a>
						<div class="dropdown-menu">
							<a href="profile.php" class="dropdown-item">
								<i class="fa fa-user-circle"></i> Profile
							</a>
						</div>
					</li>
					<li class="nav-item">
						<a href="../logout.php" class="nav-link">
							<i class="fa fa-user-times"></i> Logout
						</a>
					</li>
				</ul>
			</div>
		</div>
	</nav>

	<header id="main-header" class="py-2 bg-success text-white">
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
								<strong>The passwords do not match!</strong>
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
									<button class="btn btn-success" name="login_btn" type="submit">Save</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

		<footer id="main-footer" class="bg-dark text-white mt-5 p-5">
			<div class="container">
				<div class="row">
					<div class="col">
						<p class="lead text-center">Copyright &copy; 2017 CCT Library</p>
					</div>
				</div>
			</div>
		</footer>

		<script src="../js/jquery.min.js"></script>
		<script src="../js/popper.min.js"></script>
		<script src="../js/bootstrap.min.js"></script>
		<script src="https://cdn.ckeditor.com/4.7.1/standard/ckeditor.js"></script>
		<script>
				CKEDITOR.replace( 'editor1' );
		</script>

	</body>
	</html>
