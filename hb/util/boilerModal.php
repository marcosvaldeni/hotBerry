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
          $stmt -> bindValue(':now', time(), PDO::PARAM_STR);
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