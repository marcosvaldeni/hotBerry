<?php
  include("../util/protectionLevel2.php");
  require_once("../util/connection.php");
  require_once("../util/init.php");
	$msg = "";
	$schedule;

	if ($_POST) {

		$starts = strtotime($_POST['daytime']);
		$ends = $starts + $_POST['time'];
		$user_id = $_SESSION["user_id"];
		$keycode = $_SESSION["keycode"];

		$sql = "select * from schedules INNER JOIN relation ON schedules.relation_id = relation.relation_id
		where schedules.schedule_start <= :start and schedules.schedule_end >= :start or schedules.schedule_start <= :end and schedules.schedule_end >= :end having relation.keycode_key = :keycode;";
		$stmt = $conn -> prepare($sql);
		$stmt -> bindValue(':start', $starts, PDO::PARAM_INT);
		$stmt -> bindValue(':end', $ends, PDO::PARAM_INT);
		$stmt -> bindValue(':keycode', $keycode, PDO::PARAM_STR);
		$stmt -> execute();
		$row = $stmt->fetch();

		if ($row) {
			$msg = "r";
			$schedule = 'Scheduled for '.date("H:i:s", $row['schedule_start']).' to '.date("H:i:s", $row['schedule_end']).' on '.date(" l jS F Y", $row['schedule_end']);
		} else {

			$sql = "CALL createSchedule(?, ?, ?, ?);";
			$sth = $conn -> prepare($sql);
			$sth -> bindParam(1, $user_id, PDO::PARAM_INT);
			$sth -> bindParam(2, $keycode, PDO::PARAM_STR);
			$sth -> bindParam(3, $starts, PDO::PARAM_INT);
			$sth -> bindParam(4, $ends, PDO::PARAM_INT);

			$sth -> execute();
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>

	<?php include("../util/header.php"); ?> 

</head>
<body>

	<?php include("../util/nav.php"); ?> 

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
            <i class="fa fa-arrow-left"></i> Back
          </a>
        </div>
				<?php include("../util/boilerBtn.php"); ?>
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

  <section id="datetime">
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
									<label for="datetime">Date and Time:</label>
									<input type="datetime-local" class="form-control"  name="daytime">
                </div>
								<div class="form-group">
									<label for="duration">Duration</label>
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

	<?php include("../util/boilerModal.php"); ?>

	<?php include("../util/footer.php"); ?>  

</body>
</html>
