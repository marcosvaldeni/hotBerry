<?php
include("../protection.php");
include_once("../../connection.php");

$time = time()-3600;

if ($_POST) {

  $stards = time() - 3600; 
  $ends = time() - 3600; 
  $ends += $_POST["ends"];
  $user_id = $_SESSION["user_id"];
  $keycode = $_SESSION["keycode"];
  
  $sql = "CALL createSchedule(?, ?, ?, ?);";
  $sth = $conn -> prepare($sql);
  $sth -> bindParam(1, $user_id, PDO::PARAM_INT);
  $sth -> bindParam(2, $keycode, PDO::PARAM_INT);
  $sth -> bindParam(3, $stards, PDO::PARAM_INT);
  $sth -> bindParam(4, $ends, PDO::PARAM_INT);

  $sth -> execute();
}

if ($_POST) {

	$user_email = $_POST['user_email'];
	$user_level = $_POST['user_level'];
	$keycode_id = $_SESSION["keycode_id"];

	$msg;
	if ($user_pass == $user_pass2) {

		if ($user_email == "" || $user_pass == "" || $user_pass2 == "") {
				$msg = "Some fields could be empty!";
		}else{
			$user_pass = password_hash($user_pass2,PASSWORD_DEFAULT);

			$sql = "INSERT INTO users (user_email, user_pass, user_level, keycode_id) VALUES (?, ?, ?, ?);";
			$sth = $conn -> prepare($sql);
			$sth -> bindParam(1, $user_email, PDO::PARAM_STR);
			$sth -> bindParam(2, $user_pass, PDO::PARAM_STR);
			$sth -> bindParam(3, $user_level, PDO::PARAM_STR);
			$sth -> bindParam(4, $keycode_id, PDO::PARAM_STR);

			$sth->execute();
			header("Location: add.php?r=ok");
		}

	}else {
		$msg = "The passwords does not match!";
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>HotBerry</title>
  <link rel="stylesheet" href="../../css/font-awesome.min.css">
  <link rel="stylesheet" href="../../css/bootstrap.css">
  <link rel="stylesheet" href="../../css/style.css">
</head>
<body>

	<!-- NAVBAR -->
	<nav class="navbar navbar-expand-sm navbar-dark bg-dark p-0">
		<div class="container">
			<a href="../" class="navbar-brand">HotBerry</a>
			<button class="navbar-toggler" data-toggle="collapse" data-target="#navbarNav">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarNav">
				<ul class="navbar-nav">
					<li class="nav-item px-2">
						<a href="../" class="nav-link">Dashboard</a>
					</li>
					<li class="nav-item px-2">
						<a href="../book/" class="nav-link">Books</a>
					</li>
					<li class="nav-item px-2">
						<a href="index.php" class="nav-link active">Users</a>
					</li>
				</ul>
				<ul class="navbar-nav ml-auto">
					<li class="nav-item dropdown mr-3">
						<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
							<i class="fa fa-user"></i> Welcome 
							<?php
							if ($_SESSION["user_name"] == "") {
								echo $_SESSION["user_email"];
							} else {
								echo $_SESSION["user_name"];
							}
							?>
						</a>
						<div class="dropdown-menu">
							<a href="../profile.php" class="dropdown-item">
								<i class="fa fa-user-circle"></i> Profile
							</a>
							<a href="../pass.php" class="dropdown-item">
								<i class="fa fa-lock"></i> Password
							</a>
						</div>
					</li>
					<li class="nav-item">
						<a href="../../logout.php" class="nav-link">
							<i class="fa fa-user-times"></i> Logout
						</a>
					</li>
				</ul>
			</div>
		</div>
	</nav>

  <header id="main-header" class="py-2 bg-primary text-white">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <h1><i class="fa fa-users"></i> Adding New User</h1>
        </div>
      </div>
    </div>
  </header>

  <!-- ACTIONS -->
  <section id="action" class="py-4 mb-4 bg-light">
    <div class="container">
      <div class="row">
				<div class="col-md-2 mr-auto">
          <a href="index.php" class="btn btn-light btn-block">
            <i class="fa fa-arrow-left"></i> Back
          </a>
        </div>
				<div class="col-md-2">
					<?php
					$sql = "SELECT * FROM schedules INNER JOIN  relation ON schedules.relation_id = relation.relation_id
					where schedule_start <= :now and schedule_end >= :now having relation.keycode_key = :keycode;";
					$stmt = $conn -> prepare($sql);
					$stmt -> bindValue(':keycode', $_SESSION["keycode"], PDO::PARAM_INT);
					$stmt -> bindValue(':now', $time, PDO::PARAM_INT);
					$stmt -> execute();
					$row = $stmt->fetch();
					?>
          <a href="#" class="btn btn-<?php if($row){echo 'success';} else {echo 'danger';} ?> btn-block" data-toggle="modal" data-target="#turnon">
            <i class="fa fa-toggle-<?php if($row){echo 'on';} else {echo 'off';} ?>"></i> <?php if($row){echo 'On';} else {echo 'Off';} ?>
          </a>
				</div>

      </div>
    </div>
  </section>

  <!-- BOOK REGISTER -->
	<?php if (isset($_GET['r'])) {?>
	<section id="info">
		<div class="container">
			<div class="row">
				<div class="col-md-12 m-auto">
					<div class="alert alert-success alert-dismissible fade show">
							<button class="close" data-dismiss="alert" type="button">
									<span>&times;</span>
							</button>
							<strong>Successful registration!</strong>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php } ?>
	<?php if (isset($msg)) { ?>
	<section id="info">
		<div class="container">
			<div class="row">
				<div class="col-md-12 m-auto">
					<div class="alert alert-danger alert-dismissible fade show">
							<button class="close" data-dismiss="alert" type="button">
									<span>&times;</span>
							</button>
							<strong><?php echo $msg; ?></strong>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php } ?>
  <section id="profile">
    <div class="container">
      <div class="row">
        <div class="col-md-6 m-auto">
          <div class="card">
            <div class="card-header">
              <h4>New User</h4>
            </div>
            <div class="card-body">
              <form action="" method="POST">
                <div class="form-group">
                  <label for="email">Email:</label>
                  <input type="text" class="form-control" name="user_email">
                </div>
								<div class="form-group">
									<label for="email">Level:</label>
										<div class="input-group">
											<select name="user_level" class="form-control">
											<option value="2">User</option>
											<option value="1">Admin</option>
											</select>
										</div>
								</div>
								<div class="form-group">
                  <br/>
									<button class="btn btn-primary" type="submit" <?php if ($_SESSION["user_level"] == 2) {echo "value='2' name='user_level'";} ?>>Save</button>
									<a href="index.php" class="btn btn-secondary">Cancel</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <footer id="main-footer" class="bg-dark text-white mt-5 p-5">
    <div class="conatiner">
      <div class="row">
        <div class="col">
          <p class="lead text-center">Copyright &copy; 2019 HotBerry</p>
        </div>
      </div>
    </div>
  </footer>

  <script src="../../js/jquery.min.js"></script>
  <script src="../../js/popper.min.js"></script>
  <script src="../../js/bootstrap.min.js"></script>
</body>
</html>
