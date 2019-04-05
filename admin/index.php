 <?php
//  CONTROL AREA
include("protection.php");
include("../connection.php");
include("util/functions.php");
include("util/timezone.php");

if (isset($_POST['turnOn'])) {
  boilerDirect($_POST["ends"], $_SESSION["user_id"], $_SESSION["keycode"], $time, $conn);
}

if (isset($_POST['selected'])) {
  $_SESSION["selected"] = true;
  $_SESSION["keycode"] = $_POST['keycode'];
}

$dataPoints = array( 
	array("y" => 7.00,"label" => "March" ),
	array("y" => 12,"label" => "April" ),
	array("y" => 28,"label" => "May" ),
	array("y" => 18,"label" => "June" ),
	array("y" => 41,"label" => "July" )
);

$sql = "SELECT keycode_key as keycode, relation_comment as name FROM relation WHERE user_id = :id;";
$stmt = $conn -> prepare($sql);
$stmt -> bindValue(':id', $_SESSION["user_id"], PDO::PARAM_INT);
$stmt -> execute();
$devices = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    	<title>HotBerry</title>
      <link rel="stylesheet" href="../css/font-awesome.min.css">
      <link rel="stylesheet" href="../css/bootstrap.css">
      <link rel="stylesheet" href="../css/style.css">
      <script>
        window.onload = function() {
        
        var chart = new CanvasJS.Chart("chartContainer", {
          animationEnabled: true,
          axisY: {
            title: "",
            prefix: "$",
            suffix:  "k"
          },
          data: [{
            type: "bar",
            yValueFormatString: "€#,##0",
            indexLabel: "{y}",
            indexLabelPlacement: "inside",
            indexLabelFontWeight: "bolder",
            indexLabelFontColor: "white",
            dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
          }]
        });
        chart.render();
        
        }
      </script>
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
              <a href="index.php" class="nav-link active">Dashboard</a>
            </li>
            <li class="nav-item px-2">
              <a href="schedule/" class="nav-link">Schedules</a>
            </li>
            <li class="nav-item px-2">
              <a href="historic/" class="nav-link">Historic</a>
            </li>
            <?php if ($_SESSION["user_level"] == 1) { ?> 
            <li class="nav-item px-2">
              <a href="users/" class="nav-link">Users</a>
            </li>
            <?php } ?> 
            <?php if (sizeof($devices) > 1 || $_SESSION["user_level"] == 1) { ?>
            <li class="nav-item px-2">
              <a href="users/" class="nav-link">Devices</a>
            </li>
            <?php } ?> 
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
                <a href="profile.php" class="dropdown-item">
                  <i class="fa fa-user-circle"></i> Profile
                </a>
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
            <h1><i class="fa fa-gear"></i> Dashboard</h1>
          </div>
        </div>
      </div>
    </header>

    <!-- ACTIONS -->
    <section id="action" class="py-4 mb-4 bg-light">
      <div class="container">
        <div class="row">
          <div class="col-md-2 mr-auto">
            <a href="schedule/add.php" class="btn btn-primary btn-block">
              <i class="fa fa-plus"></i> Add
            </a>
          </div>
          <?php include("util/boilerBtn.php"); ?> 
        </div>
      </div>
    </section>

    <!-- POSTS -->
    <section id="posts">
      <div class="container">
        <div class="row">
          <div class="col-md-7">
            <div class="card">
              <div class="card-header">
                <h4>Graphic</h4>
              </div>
              <div id="chartContainer" style="height: 370px; width: 100%;"></div>
            </div>
          </div>
          <div class="col-md-5">
            <div class="card">
              <div class="card-header">
                <h4>Today's Schedule</h4>
              </div>
              <?php
              $dataFormat = "%d/%m/%Y";
              $sql = "SELECT users.user_email AS email,users.user_name AS name, schedules.schedule_start AS start, schedules.schedule_end AS end FROM schedules
              INNER JOIN  relation ON schedules.relation_id = relation.relation_id
              INNER JOIN  users ON users.user_id = relation.user_id  
              WHERE FROM_UNIXTIME(schedule_start, :dataF) = FROM_UNIXTIME((SELECT UNIX_TIMESTAMP(NOW())), :dataF)
              AND relation.keycode_key = :keycode 
              ORDER BY schedule_end;";
              $stmt = $conn -> prepare($sql);
              $stmt -> bindValue(':keycode',  $_SESSION["keycode"], PDO::PARAM_STR);
              $stmt -> bindValue(':dataF',  $dataFormat, PDO::PARAM_STR);
              $stmt -> execute();
              $result = $stmt->fetchAll();
              ?>
              <table class="table table-striped">
                <thead class="thead-inverse">
                  <tr>
                    <th>Login</th>
                    <th>Starts</th>
                    <th>Ends</th>
                  </tr>
                </thead>
                <tbody>
                <?php foreach ($result as $row) { ?>
                  <tr>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo(date("H:i:s", $row['start'])); ?></td>
                    <td><?php echo(date("H:i:s", $row['end'])); ?></td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
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
                <button class="btn btn-primary" type="submit" name="turnOn" value="true">Save</button>
              </div>
            </form>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>

    <?php if (sizeof($devices) > 1 && $_SESSION["selected"] == false) { ?>
    <!-- SELECT MODAL -->
    <div class="modal" id="select">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Please, select a device:</h5>
            <button class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <form id="modal-form" action="" method="POST">
              <div class="form-group">
                <select name="keycode" class="form-control">
                  <?php $count = 1; foreach ($devices as $row) { ?>
                  <option value="<?= $row['keycode']?>">Device <?php echo $count++.' '.$row['name'].$row['keycode']; ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="modal-footer">
                <button class="btn btn-primary" type="submit" name="selected" value="true">Save</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <?php } ?>

    <?php include("util/footer.php"); ?>                 

    <script src="../js/jquery.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script>
      $(document).ready(function() {
        $('#select').modal('show');
      });
    </script>
  </body>
</html>