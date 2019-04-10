<div class="col-md-2">
  <?php
    $sql = "SELECT schedules.schedule_id FROM schedules
    INNER JOIN relation ON schedules.relation_id = relation.relation_id
    where schedules.schedule_start <= :now and schedules.schedule_end >= :now
    and relation.keycode_key = :keycode;";
    $stmt = $conn -> prepare($sql);
    $stmt -> bindValue(':keycode', $_SESSION["keycode"], PDO::PARAM_INT);
    $stmt -> bindValue(':now', time(), PDO::PARAM_INT);
    $stmt -> execute();
    $row = $stmt->fetch();
  ?>
  <a href="#" class="btn btn-<?php if($row){echo 'success';} else {echo 'danger';} ?> btn-block" data-toggle="modal" data-target="#turnon">
    <i class="fa fa-toggle-<?php if($row){echo 'on';} else {echo 'off';} ?>"></i> <?php if($row){echo 'On';} else {echo 'Off';} ?>
  </a>
</div>