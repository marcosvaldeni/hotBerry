<?php
include("../protection.php");
include_once("../../connection.php");
$msg = "";
$schedule;

if ($_POST) {

	$starts = strtotime($_POST['daytime']);
	$ends = $starts + $_POST['time'];
	$user_id = $_SESSION["user_id"];
	$keycode_id = $_SESSION["keycode_id"];

	$sql = "select * from schedule where schedule_start <= :start and schedule_end >= :start or schedule_start <= :end and schedule_end >= :end;";
	$stmt = $conn -> prepare($sql);
	$stmt -> bindValue(':start', $starts, PDO::PARAM_STR);
	$stmt -> bindValue(':end', $ends, PDO::PARAM_STR);
	$stmt -> execute();
	$row = $stmt->fetch();

	if ($row) {
		$msg = "r";
		$schedule = 'Scheduled for '.date("H:i:s", $row['schedule_start']).' to '.date("H:i:s", $row['schedule_end']).' on '.date(" l jS F Y", $row['schedule_end']);
	} else {

		$sql = "INSERT INTO schedule (schedule_start, schedule_end, user_id, keycode_id) VALUES (?, ?, ?, ?);";
		$sth = $conn -> prepare($sql);
		$sth -> bindParam(1, $starts, PDO::PARAM_STR);
		$sth -> bindParam(2, $ends, PDO::PARAM_STR);
		$sth -> bindParam(3, $user_id, PDO::PARAM_INT);
		$sth -> bindParam(4, $keycode_id, PDO::PARAM_INT);
		
		$sth -> execute();
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
						<a href="index.php" class="nav-link">Users</a>
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
          <h1><i class="fa fa-users"></i> New Programming</h1>
        </div>
      </div>
    </div>
  </header>

  <!-- ACTIONS -->
  <section id="action" class="py-4 mb-4 bg-light">
    <div class="container">
      <div class="row">
        <div class="col-md-3 mr-auto">
          <a href="../" class="btn btn-light btn-block">
            <i class="fa fa-arrow-left"></i> Back To Dashboard
          </a>
        </div>
      </div>
    </div>
  </section>

	<?php if ($msg == "r") { ?>
	<section id="info">
		<div class="container">
			<div class="row">
				<div class="col-md-8 m-auto">
					<div class="alert alert-danger alert-dismissible fade show">
							<button class="close" data-dismiss="alert" type="button">
									<span>&times;</span>
							</button>
							<strong><?php echo $schedule; ?></strong>
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
              <h4>Please, select Day and Time</h4>
            </div>
						<br/>
            <div class="card-body">
              <form action="" method="POST">
                <div class="form-group">
									<label for="email">Date and Time:</label>
									<input type="datetime-local" class="form-control"  name="daytime">
                </div>
								<div class="form-group">
									<label for="email">Duration</label>
									<select name="time" class="form-control">
										<option value="900">15 minutes</option>
										<option value="1800">30 minutes</option>
										<option value="3600">60 minutes</option>
									</select>
                </div>
								<div class="form-group">
                  <br/>
									<button class="btn btn-primary" type="submit">Save</button>
									<a href="index.php" class="btn btn-secondary">Cancel</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

	<br/>

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
