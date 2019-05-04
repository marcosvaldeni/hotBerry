 <?php
// Import of components and additional pages
include("util/protectionLevel2.php");
include("util/connection.php");
include("util/functions.php");

$err;

if(isset($_GET['r'])) {
  $err = checkError($_GET['r']);
}

if (isset($_POST['turnOn'])) {
  boilerDirect($_POST["ends"], $_SESSION["user_id"], $_SESSION["keycode"], $conn);
}

if (isset($_POST['selected'])) {
  $_SESSION["selected"] = true;
  $_SESSION["keycode"] = $_POST['keycode'];
}

$sql = "SELECT users.user_email AS email,users.user_name AS name, 
sum(schedule_end-schedule_start) as timeOn FROM schedules
INNER JOIN  relation ON schedules.relation_id = relation.relation_id
INNER JOIN  users ON users.user_id = relation.user_id  
WHERE relation.keycode_key = :keycode  group by users.user_id;";
$stmt = $conn -> prepare($sql);
$stmt -> bindValue(':keycode',  $_SESSION["keycode"], PDO::PARAM_STR);
$stmt -> execute();
$result = $stmt->fetchAll();

$dataPoints;
for ($i=0; $i < sizeof($result); $i++) { 
  $time = ($result[$i]['timeOn']/60);
  $hours = round($time / 60, 2);
  $dataPoints[$i] = array("y" => $hours, "label" => $result[$i]['email'] );
  
}

$sql = "SELECT keycode_key as keycode FROM relation WHERE user_id = :id;";
$stmt = $conn -> prepare($sql);
$stmt -> bindValue(':id', $_SESSION["user_id"], PDO::PARAM_INT);
$stmt -> execute();
$devices = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
  <head>
    <?php include("util/header.php"); ?> 
    <script>
      window.onload = function() {
      
      var chart = new CanvasJS.Chart("chartContainer", {
        animationEnabled: true,
        axisY: {
          title: "",
          prefix: "",
          suffix:  " Hours"
        },
        data: [{
          type: "bar",
          yValueFormatString: "0#.##",
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

  <?php include("util/nav.php"); ?>  

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
            <i class="fa fa-plus"></i> Add Boiler Task
          </a>
        </div>
        <?php include("util/boilerBtn.php"); ?> 
      </div>
    </div>
  </section>

  <!--This is a generic error message that receives one of the
  errors from function.php acoording to the number, currently from 1 to 9.
  This way if we need to add an error message we just add it in function.php and
  call here-->
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

  <!-- POSTS -->
  <section id="posts">
    <div class="container">
      <div class="row">
        <div class="col-md-7">
          <div class="card">
            <div class="card-header">
              <h4>Boiler System Usage</h4>
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

  <?php include("util/boilerModal.php"); ?>

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
                <option value="<?= $row['keycode']?>">Device <?php echo $count++.' '.$row['keycode']; ?></option>
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

  <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#select').modal('show');
    });
  </script>
</body>
</html>