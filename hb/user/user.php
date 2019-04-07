<?php
  include("../util/protection.php");
  include("../util/connection.php");
  include("../util/functions.php");

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

  if (isset($_POST['turnOn'])) {
    boilerDirect($_POST["ends"], $_SESSION["user_id"], $_SESSION["keycode"], $conn);
  }
?>
<!DOCTYPE html>
<html>
<head>
  <?php include("../util/header.php"); ?>
</head>
<body>

  <?php include("../util/nav.php"); ?>

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
        <div class="col-md-2 mr-auto">
          <a href="add.php" class="btn btn-primary btn-block">
            <i class="fa fa-plus"></i> Add User
          </a>
        </div>
        <?php include("../util/boilerBtn.php"); ?>
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

  <br>
  <br>

  <?php include("../util/boilerModal.php"); ?>
  <?php include("../util/footer.php"); ?>

</body>
</html>