 <?php
  // Import of components and additional pages
  include("../util/protectionLevel2.php");
  require_once("../util/connection.php");
  require_once("../util/functions.php");

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
          <h1><i class="fa fa-tags"></i> Devices</h1>
        </div>
      </div>
    </div>
  </header>

  <section id="action" class="py-4 mb-4 bg-light">
    <div class="container">
      <div class="row">
        <div class="col-md-2 mr-auto"></div>
          <!-- Call the boiler button component -->
          <?php include("../util/boilerBtn.php"); ?>
        </div>
    </div>
  </section>

  <section id="device">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h4>Devices</h4>
            </div>
            <?php
              $sql = "SELECT keycode_comment AS name, keycodes.keycode_key AS keycode FROM keycodes 
              INNER JOIN relation ON relation.keycode_key = keycodes.keycode_key
              WHERE user_id = :id;";
              $stmt = $conn -> prepare($sql);
              $stmt -> bindValue(':id', $_SESSION["user_id"], PDO::PARAM_INT);
              $stmt -> execute();
              $devices = $stmt->fetchAll();
            ?>
            <table class="table table-striped">
              <thead class="thead-inverse">
                <tr>
                  <th>Name</th>
                  <th>Key</th>
                  <th>Edit</th>
                  <th>Starts</th>
                </tr>
              </thead>
              <tbody>
              <?php foreach ($devices as $row) { ?>
                <tr>
                  <td><?= $row['name'] ?></td>
                  <td><?= $row['keycode'] ?></td>
                  <td>
                    <a href="device.php?kc=<?= $row['keycode'] ?>" class="btn btn-light btn-block ">
                      <i class="fa fa-check"></i> Edit
                    </a>
                  </td>
                  <td>
                    <a href="#" class="btn alert-<?php if ($row['keycode'] == $_SESSION['keycode']) { echo "primary"; } else { echo "light"; } ?> btn-block">
                      <?php if ($row['keycode'] == $_SESSION['keycode']) { echo "Select"; } else { echo "<i class=\"fa fa-check\"></i> Selected"; } ?> 
                    </a>
                  </td>
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
  <!-- Call the footer component -->
  <?php include("../util/footer.php"); ?> 

</body>
</html>