<?php
  include("../protection.php");
  include("../../connection.php");

  $time = time()-3600;

  if (isset($_GET["pr"])) {
    $id = $_GET["pr"];
    $keycode = $_SESSION["keycode"];

    $sql = "UPDATE relation SET relation_level = 1 WHERE user_id = ? and keycode_key = ?;";
    $sth = $conn -> prepare($sql);
    $sth -> bindParam(1, $id, PDO::PARAM_INT);
    $sth -> bindParam(2, $keycode, PDO::PARAM_STR);
    $sth -> execute();
    header("Location: index.php");
  }

  if (isset($_GET["gb"])) {
    $user_id = $_GET["gb"];
  } else {
    header("Location: index.php");
  }

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
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    	<title>HotBerry</title>
      <link rel="stylesheet" href="../../css/font-awesome.min.css">
      <link rel="stylesheet" href="../../css/bootstrap.css">
      <link rel="stylesheet" href="../../css/style.css">
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
              <a href="../" class="nav-link">Dashboard</a>
            </li>
            <li class="nav-item px-2">
              <a href="../schedules/" class="nav-link">Schedules</a>
            </li>
            <li class="nav-item px-2">
              <a href="../historic/" class="nav-link">Historic</a>
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
            <h1><i class="fa fa-users"></i> Users</h1>
          </div>
        </div>
      </div>
    </header>

    <!-- ACTIONS -->
    <section id="action" class="py-4 mb-4 bg-light">
      <div class="container">
        <div class="row">
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
          <div class="col-md-2">
            <a href="add.php" class="btn btn-primary btn-block">
              <i class="fa fa-plus"></i> Add User
            </a>
          </div>
        </div>
      </div>
    </section>

    <?php
    $sql = "SELECT user_name as name, user_email as email FROM users where user_id = :id;";
    $stmt = $conn -> prepare($sql);
    $stmt -> bindValue(':id', $_GET['gb'], PDO::PARAM_INT);
    $stmt -> execute();
    $row = $stmt->fetch();
    ?>
    <!-- USER INFO -->
    <section id="user">
      <div class="container">
        <div class="row">
          <div class="col-md-8 m-auto">
            <div class="card">
              <div class="card-header">
                <h4>Promote User</h4>
              </div>
              <div class="card-body">
                <form action="" method="POST">
                  <div class="form-group">
                    <p class="h6">Name</p>
                    <p><?= $row['name'] ?></p>
                  </div>
                  <div class="form-group">
                    <p class="h6">Login</p>
                    <p><?= $row['email'] ?></p>
                  </div>
                  <div class="form-group">
                    <br/>
                    <a href="user.php?pr=<?= $_GET['gb'] ?>" class="btn btn-danger">Turn this user an Admin</a>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- BOILER MODAL -->
    <div class="modal" id="turnon">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Turn On the Boiler</h5>
            <button class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <?php
            $sql = "select * from schedules where schedule_start <= :now and schedule_end >= :now;";
            $stmt = $conn -> prepare($sql);
            $stmt -> bindValue(':now', $time, PDO::PARAM_STR);
            $stmt -> bindValue(':id', $_SESSION["user_id"], PDO::PARAM_STR);
            $stmt -> execute();
            $row = $stmt->fetch();
            if ($row) { 
            ?>
            <form id="modal-form" action="cancel.php" method="POST">
              <div class="form-group">
                <div class="alert alert-danger fade show">
                  <strong>
                    Boiler ON from <?= date("H:i:s", $row['schedule_start']); ?> to <?= date("H:i:s", $row['schedule_end']); ?>
                  </strong>
                </div>
              </div>
              <div class="modal-footer">
                <button class="btn btn-primary" type="submit">Save</button>
              </div>
            </form>
          <?php } else { ?>
            <form id="modal-form" action="" method="POST">
              <div class="form-group">
                <select name="ends" class="form-control">
                  <option value="900">15 minutes</option>
                  <option value="1800">30 minutes</option>
                  <option value="3600">60 minutes</option>
                </select>
              </div>
              <div class="modal-footer">
                <button class="btn btn-primary" type="submit">Save</button>
              </div>
            </form>
          <?php } ?>
          </div>
        </div>
      </div>
    </div>

    <br>
    <br>

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