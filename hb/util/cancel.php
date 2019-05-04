<?php 
  // Import of components and companion pages
  include("../util/protectionLevel2.php");
  include("../util/init.php");
  include("../util/connection.php");

	$sql = "SELECT COUNT(*) as number, schedules.schedule_id FROM schedules
	INNER JOIN relation ON schedules.relation_id = relation.relation_id
	where schedules.schedule_start <= :now and schedules.schedule_end >= :now
	and relation.keycode_key = :keycode;";
	$stmt = $conn -> prepare($sql);
	$stmt -> bindValue(':keycode', $_SESSION["keycode"], PDO::PARAM_INT);
	$stmt -> bindValue(':now', time(), PDO::PARAM_INT);
	$stmt -> execute();
	$row = $stmt->fetch();

	echo $row['number'];

	if ($row['number'] == 1) {

		$sql = "UPDATE schedules SET schedule_end = :now WHERE schedule_id = :id;";
		$stmt = $conn -> prepare($sql);
		$stmt -> bindValue(':id', $row['schedule_id'], PDO::PARAM_INT);
		$stmt -> bindValue(':now', (time()-5), PDO::PARAM_INT);
		$stmt -> execute();

		header("Location: ".$BASE."/hb/index.php?r=13");

	} else {

		header("Location: ".$BASE."/hb");
	}

?>