<?php
	require_once("../util/protection.php");
	require_once("../util/connection.php");

  if (isset($_GET["jm"])) {
    $id = $_GET["jm"];

    $sql = "SELECT users.user_id FROM users
    INNER JOIN relation ON users.user_id = relation.user_id
    WHERE users.user_id = :id AND relation.keycode_key = :keycode AND relation.relation_level = 2;";
    $stmt = $conn -> prepare($sql);
    $stmt -> bindValue(':keycode', $_SESSION["keycode"], PDO::PARAM_INT);
    $stmt -> bindValue(':id', $id, PDO::PARAM_INT);
    $stmt -> execute();
    $row = $stmt->fetch();

    if ($row) {

      $sql = "UPDATE users SET user_deleted = 1 WHERE user_id = ?;";
      $sth = $conn -> prepare($sql);
      $sth -> bindParam(1, $id, PDO::PARAM_INT);
      $sth -> execute();
      header("Location: index.php");

    } else {

      header("Location: index.php");
    }
  }

  if (isset($_GET["bf"])) {
    $user_id = $_GET["bf"];
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
    $stmt -> bindValue(':id', $_GET['bf'], PDO::PARAM_INT);
    $stmt -> execute();
    $row = $stmt->fetch();
  ?>
  <!-- BOOK DELETE -->
  <section id="info">
    <div class="container">
      <div class="row">
        <div class="col-md-8 m-auto">
          <div class="alert alert-danger alert-dismissible fade show">
              <button class="close" data-dismiss="alert" type="button">
                  <span>&times;</span>
              </button>
              <center>
                <strong>Are you sure, you want to delete this user?</strong>
              </center>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section id="delete">
    <div class="container">
      <div class="row">
        <div class="col-md-8 m-auto">
          <div class="card">
            <div class="card-header">
              <h4>Delete User</h4>
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
                  <a href="delete.php?jm=<?= $_GET['bf'] ?>" class="btn btn-danger">Delete</a>
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