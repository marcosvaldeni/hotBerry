 <?php
   // Import of components and additional pages
  include("../util/protectionLevel2.php");
  require_once("../util/connection.php");
  require_once("../util/init.php");

	if (isset($_POST['turnOn'])) {
    boilerDirect($_POST["ends"], $_SESSION["user_id"], $_SESSION["keycode"], $conn);
  }
?>
<!DOCTYPE html>
<html>
<head>
   <!-- Call the header component -->
  <?php include("../util/header.php"); ?> 
</head>
<body>

  <!-- Call the navbar component -->
  <?php include("../util/nav.php"); ?> 

  <header id="main-header" class="py-2 bg-primary text-white">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <h1><i class="fa fa-history"></i> Historic</h1>
        </div>
      </div>
    </div>
  </header>

  <!-- ACTIONS -->
  <section id="action" class="py-4 mb-4 bg-light">
    <div class="container">
      <div class="row">
        <div class="col-md-3 mr-auto">
        </div>
        <?php include("../util/boilerBtn.php"); ?>
      </div>
    </div>
  </section>

  <!-- Historic table -->
  <section id="historic">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h4>Schedule's Historic</h4>
            </div>
            <?php
              $sql = "SELECT relation.relation_id, users.user_id, schedules.schedule_id, users.user_name AS name, users.user_email AS email, schedule_start, schedule_end  FROM schedules
              INNER JOIN relation ON schedules.relation_id = relation.relation_id
              INNER JOIN users ON relation.user_id = users.user_id
              WHERE relation.keycode_key = :keycode and schedules.schedule_end < :now ORDER BY schedules.schedule_end;";
              $stmt = $conn -> prepare($sql);
              $stmt -> bindValue(':keycode', $_SESSION["keycode"], PDO::PARAM_STR);
              $stmt -> bindValue(':now', time(), PDO::PARAM_INT);
              $stmt -> execute();
              $result = $stmt->fetchAll();
            ?>
            <table class="table table-striped">
              <thead class="thead-inverse">
                <tr>
                  <th>Name</th>
                  <th>Login</th>
                  <th>Starts</th>
                  <th>Ends</th>
                  <th>Duration</th>
                </tr>
              </thead>
              <tbody>
              <?php foreach ($result as $row) { ?>
                <tr>
                  <td><?php echo  $row['name']; ?></td>
                  <td><?php echo  $row['email']; ?></td>
                  <td><?php echo(date("d/m/Y H:i:s", $row['schedule_start'])); ?></td>
                  <td><?php echo(date("d/m/Y H:i:s", $row['schedule_end'])); ?></td>
                  <td><?php echo(date(" H:i:s",($row['schedule_end'] - $row['schedule_start'] - 3600))); ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Call boiler modal page -->
  <?php include("../util/boilerModal.php"); ?>

  <?php
    for ($i=0; $i < (4-sizeof($result)); $i++) {
      echo '</br></br>';
    }
  ?>
  <!-- Call the footer component -->
  <?php include("../util/footer.php"); ?>  

</body>
</html>