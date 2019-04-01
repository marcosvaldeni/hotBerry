<?php
include("protection.php");
include_once("../connection.php");
$msg = "";
$user_id = $_SESSION["user_id"];

$sql = "SELECT * FROM users WHERE user_id = :id";
$stmt = $conn -> prepare($sql);
$stmt -> bindValue(':id', $user_id, PDO::PARAM_STR);
$stmt -> execute();
$row = $stmt->fetch();

if ($_POST) {

  if ($_POST['user_name'] == "" || $_POST['user_email'] == "" ) {
    $msg = "error";
  }else{
  
    $user_name = $_POST['user_name'];
    $user_email = $_POST['user_email'];
  
    $upd = $conn -> prepare("UPDATE users SET user_name = :user_name, user_email = :user_email WHERE user_id = :user_id");
  
    $upd -> bindValue(":user_name", $user_name);
    $upd -> bindValue(":user_email", $user_email);
    $upd -> bindValue(":user_id", $user_id);
    $upd -> execute();

    $_SESSION["user_name"] = $user_name;
    $_SESSION["user_email"] = $user_email;
  
    header("Location: profile.php");
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
  <link rel="stylesheet" href="../css/font-awesome.min.css">
  <link rel="stylesheet" href="../css/bootstrap.css">
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>

	<!-- NAVBAR -->
	<nav class="navbar navbar-expand-sm navbar-dark bg-dark p-0">
		<div class="container">
			<a href="index.php" class="navbar-brand">HotBerry</a>
			<button class="navbar-toggler" data-toggle="collapse" data-target="#navbarNav">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarNav">
				<ul class="navbar-nav">
            <li class="nav-item px-2">
              <a href="index.php" class="nav-link">Dashboard</a>
            </li>
            <li class="nav-item px-2">
              <a href="book/" class="nav-link">Historic</a>
            </li>
            <li class="nav-item px-2">
              <a href="member/" class="nav-link">Users</a>
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
							<a href="pass.php" class="dropdown-item">
								<i class="fa fa-lock"></i> Password
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

  <header id="main-header" class="py-2 bg-primary text-white">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <h1><i class="fa fa-user"></i> Edit Profile</h1>
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
        <div class="col-md-3">
          <a href="pass.php" class="btn btn-success btn-block">
            <i class="fa fa-lock"></i> Change Password
          </a>
        </div>
      </div>
    </div>
  </section>

  <?php if ($msg == "error") {?>
  <section id="info">
    <div class="container">
      <div class="row">
        <div class="col-md-6 mx-auto">
          <div class="alert alert-danger alert-dismissible fade show">
              <button class="close" data-dismiss="alert" type="button">
                  <span>&times;</span>
              </button>
              <strong>Empty fields are not allowed!</strong>
          </div>
        </div>
      </div>
    </div>
  </section>
  <?php } ?>

  <!-- PROFILE EDIT -->
  <section id="profile">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h4>Edit Profile</h4>
            </div>
            <div class="card-body">
              <form action="profile.php" method="POST">
                <div class="form-group">
                  <label for="name">Name</label>
                  <input type="text" class="form-control" name="user_name" value="<?= $row['user_name']; ?>">
                </div>
                <div class="form-group">
                  <label for="email">Email</label>
                  <input type="text" class="form-control" name="user_email" value="<?= $row['user_email']; ?>">
                </div>
								<div class="form-group">
                  <br/>
									<button class="btn btn-primary" name="save_btn" type="submit">Save</button>
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

  <script src="../js/jquery.min.js"></script>
  <script src="../js/popper.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>
</body>
</html>
