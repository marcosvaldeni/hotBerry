<?php
include("protection.php");
include_once("../connection.php");

$user_id = $_SESSION["user_id"];
$row =  $conn -> query("SELECT * FROM users where user_id = $user_id") -> fetch(PDO::FETCH_ASSOC);

$msg = '';
if (isset($_POST['login_btn'])) {

	$user_name = $_POST['user_name'];
	$user_email = $_POST['user_email'];
	$user_pass = $_POST['user_pass'];

	if ($user_name == "" || $user_email =="" ) {
		echo $msg = "Please add user name and password";
	}else{

		$upd = $conn -> prepare("UPDATE users SET user_name = :user_name, user_email = :user_email WHERE user_id = :user_id");

		$upd -> bindValue(":user_name", $user_name);
		$upd -> bindValue(":user_email", $user_email);
		$upd -> bindValue(":user_id", $user_id);
		$upd -> execute();

	    header("Location:../index.php");
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
						<a href="index.html" class="nav-link active">Dashboard</a>
					</li>
					<li class="nav-item px-2">
						<a href="#" class="nav-link">Books</a>
					</li>
					<li class="nav-item px-2">
						<a href="user/" class="nav-link">Historic</a>
					</li>
				</ul>
				<ul class="navbar-nav ml-auto">
					<li class="nav-item dropdown mr-3">
						<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
							<i class="fa fa-user"></i> Welcome <?php echo $_SESSION["user_name"]; ?>
						</a>
						<div class="dropdown-menu">
							<a href="pass.php" class="dropdown-item">
								<i class="fa fa-user-circle"></i> Password
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
					<h1><i class="fa fa-user"></i> Profile</h1>
				</div>
			</div>
		</div>
	</header>

	<!-- ACTIONS -->
	<section id="action" class="py-4 mb-4 bg-light">
		<div class="container">
			<div class="row">
				<div class="col-md-6 ml-auto">
					<br/>
				</div>
			</div>
		</div>
	</section>
	<br/><br/><br/>
	<center>
		<form action="" method="POST">
			First Name:
			<br/>
			<input type="text" name="user_name" value="<?= $row['user_name']; ?>" />
			<br/>
			email:
			<br/>
			<input type="text" name="user_email" value="<?= $row['user_email']; ?>" />
			<br/>
			<br/>
			<input class="btn btn-success" type="submit" name="login_btn" value= 'Save'/>
		</form>
	</center>
	<br/><br/><br/><br/><br/>
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
