<?php
  include("../util/protection.php");
  require_once("../util/connection.php");
  require_once("../util/init.php");

  $msg = "";
 
  if (isset($_GET['kc'])) {

    $sql = "SELECT keycode_comment AS name, keycodes.keycode_key AS keycode FROM keycodes 
    INNER JOIN relation ON relation.keycode_key = keycodes.keycode_key
    WHERE user_id = :id and keycodes.keycode_key = :key;";
    $stmt = $conn -> prepare($sql);
    $stmt -> bindValue(':id', $_SESSION["user_id"], PDO::PARAM_INT);
    $stmt -> bindValue(':key', $_GET['kc'], PDO::PARAM_STR);
    $stmt -> execute();
    $device = $stmt->fetch();

    if (!$device) {
      header("Location: index.php");
    }

  } else {

    header("Location: index.php");

  }

  if ($_POST) {

    if ($_POST['name'] == "") {
      $msg = "error";
    }else{
    
      $name = $_POST['name'];
      $keycode = $_GET['kc'];
    
      $upd = $conn -> prepare("UPDATE keycodes SET keycode_comment = :comment WHERE keycode_key = :key");
      $upd -> bindValue(":comment", $name);
      $upd -> bindValue(":key", $keycode);
      $upd -> execute();

      header("Location: index.php");
    }
  }
  
  if (isset($_POST['turnOn'])) {
    boilerDirect($_POST["ends"], $_SESSION["user_id"], $_SESSION["keycode"], $conn);
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
          <h1><i class="fa fa-user"></i> Edit Device</h1>
        </div>
      </div>
    </div>
  </header>

  <!-- ACTIONS -->
  <section id="action" class="py-4 mb-4 bg-light">
    <div class="container">
      <div class="row">
        <div class="col-md-2">
          <a href="index.php" class="btn btn-light btn-block">
            <i class="fa fa-arrow-left"></i> Back
          </a>
        </div>
        </div>  
        <?php include("../util/boilerBtn.php"); ?>
      </div>
    </div>
  </section>

  <?php if ($msg == "error") {?>
  <section id="info">
    <div class="container">
      <div class="row">
        <div class="col-md-6 mx-auto">
          <div class="alert alert-danger alert-dismissible fade show">
              <button class="close" data-dismiss="alert" type="button">
                  <span>&times;</span>
              </button>
              <strong>Empty fields are not allowed!</strong>
          </div>
        </div>
      </div>
    </div>
  </section>
  <?php } ?>

  <!-- PROFILE EDIT -->
  <section id="profile">
    <div class="container">
      <div class="row">
        <div class="col-md-6 mx-auto">
          <div class="card">
            <div class="card-header">
              <h4>Edit Device <?= $device['keycode'] ?></h4>
            </div>
            <div class="card-body">
              <form action="" method="POST">
                <div class="form-group">
                  <label for="name">Name</label>
                  <input type="text" class="form-control" name="name" value="<?= $device['name'] ?>">
                </div>
                <div class="form-group">
                  <br/>
			            	<button class="btn btn-primary" name="save_btn" type="submit" value="<?= $device['keycode'] ?>">Save</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

	<?php include("../util/footer.php"); ?>  

</body>
</html>
