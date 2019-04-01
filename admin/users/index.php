 <?php
//  CONTROL AREA
include("../protection.php");
include("../../connection.php");
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    	<title>HotBerry</title>
      <link rel="stylesheet" href="../../css/font-awesome.min.css">
      <link rel="stylesheet" href="../../css/bootstrap.css">
      <link rel="stylesheet" href="../../css/style.css">
  </head>
  <body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-sm navbar-dark bg-dark p-0">
      <div class="container">
        <a href="index.php" class="navbar-brand">HotBerry</a>
        <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item px-2">
              <a href="index.php" class="nav-link">Dashboard</a>
            </li>
            <li class="nav-item px-2">
              <a href="historic.php" class="nav-link">Historic</a>
            </li>
            <li class="nav-item px-2">
              <a href="users.php" class="nav-link active">Users</a>
            </li>
          </ul>
          <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown mr-3">
              <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-user"></i> Welcome
                <?php
								if ($_SESSION["user_name"] == "") {
									echo $_SESSION["user_email"];
								} else {
									echo $_SESSION["user_name"];
								}
							  ?>
              </a>
              <div class="dropdown-menu">
                <a href="profile.php" class="dropdown-item">
                  <i class="fa fa-user-circle"></i> Profile
                </a>
                <a href="pass.php" class="dropdown-item">
                  <i class="fa fa-lock"></i> Password
                </a>
              </div>
            </li>
            <li class="nav-item">
              <a href="../logout.php" class="nav-link">
                <i class="fa fa-user-times"></i> Logout
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

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
          <div class="col-md-2">
            <a href="add.php" class="btn btn-primary btn-block">
              <i class="fa fa-plus"></i> Add User
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- POSTS -->
    <section id="posts">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h4>All Users:</h4>
              </div>
              <?php
              $sql = "SELECT * FROM users WHERE keycode_id = :keycode_id";
              $stmt = $conn -> prepare($sql);
              $stmt -> bindValue(':keycode_id', $_SESSION["keycode_id"], PDO::PARAM_STR);
              $stmt -> execute();
              $result = $stmt->fetchAll();
              ?>
              <table class="table table-striped">
                <thead class="thead-inverse">
                  <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Historic</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                <?php foreach($result as $row) { ?>
                  <tr>
                    <td><?php echo $row['user_name']; ?></td>
                    <td><?php echo $row['user_email']; ?></td>
                    <td>Historic</td>
                    <td>ok</td>
                  </tr>
                <?php } ?>
                </tbody>
              </table>
            </div>
          </div>            
        </div>
      </div>
    </div>
  </section>

  <footer id="main-footer" class="bg-dark text-white mt-5 p-5">
    <div class="conatiner">
      <div class="row">
        <div class="col">
          <p class="lead text-center">Copyright &copy; 2019 HotBerry</p>
        </div>
      </div>
    </div>
  </footer>

  <script src="../../js/jquery.min.js"></script>
  <script src="../../js/popper.min.js"></script>
  <script src="../../js/bootstrap.min.js"></script>
</body>
</html>