 <?php
	include("../util/protection.php");
	require_once("../util/connection.php");
  require_once("../util/init.php");
  require_once("../util/functions.php");

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

  <!-- USER TABLE -->
  <section id="users">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h4>All Users</h4>
            </div>
            <?php
            $sql = "SELECT users.user_id, users.user_name AS name, 
            users.user_email AS email, 
            relation.relation_level AS level, 
            users.user_status AS status, 
            users.user_deleted AS deleted FROM users
            INNER JOIN relation ON users.user_id = relation.user_id
            WHERE relation.keycode_key = :keycode;";
            $stmt = $conn -> prepare($sql);
            $stmt -> bindValue(':keycode', $_SESSION["keycode"], PDO::PARAM_STR);
            $stmt -> execute();
            $result = $stmt->fetchAll();
            ?>
            <table class="table table-striped">
              <thead class="thead-inverse">
                <tr>
                  <th>Name</th>
                  <th>Login</th>
                  <th>Status</th>       
                  <th>Delete</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                foreach ($result as $row) { 
                  if ($row['deleted'] == 0) {
                    if ($row['level'] == 1) { ?>
                      <tr>
                        <td ><?= $row['name'] ?></td>
                        <td ><?php echo  $row['email']; ?></td>
                        <td >
                          <a href="#" class="btn alert-success btn-block">
                            <i class="fa fa-check"></i> Enable
                          </a>
                        </td>
                        <td>
                          <a href="#" class="btn alert-danger btn-block">
                            <i class="fa fa-trash"></i> Delete
                          </a>
                        </td>
                      </tr>
                    <?php } else { ?>
                      <tr>
                        <td >
                          <a href="user.php?gb=<?= $row['user_id'] ?>">
                            <?php echo  $row['name']; ?>
                          </a>
                        </td>
                        <td >
                          <a href="user.php?gb=<?= $row['user_id'] ?>">
                            <?php echo  $row['email']; ?>
                          </a>
                        </td>
                        <td >
                          <?php if ($row['status'] == 0) { ?>
                          <a href="enable.php?bf=<?= $row['user_id'] ?>" class="btn btn-danger btn-block">
                            <i class="fa fa-check"></i> Disabled
                          </a>
                          <?php } else { ?>
                            <a href="disable.php?bf=<?= $row['user_id'] ?>" class="btn btn-success btn-block">
                            <i class="fa fa-check"></i> Enabled
                            </a>
                          <?php } ?>
                        </td>
                        <td>
                          <a href="delete.php?bf=<?= $row['user_id'] ?>" class="btn btn-danger btn-block">
                            <i class="fa fa-trash"></i> Delete
                          </a>
                        </td>
                      </tr>
                    <?php 
                    }
                  }
                } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>

  <?php include("../util/boilerModal.php"); ?>

  <?php include("../util/footer.php"); ?>  

</body>
</html>