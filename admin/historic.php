 <?php
//  CONTROL AREA
include("protection.php");
include("../connection.php");

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    	<title>CCT Library</title>
      <link rel="stylesheet" href="../css/font-awesome.min.css">
      <link rel="stylesheet" href="../css/bootstrap.css">
      <link rel="stylesheet" href="../css/style.css">
  </head>
  <body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-sm navbar-dark bg-dark p-0">
      <div class="container">
        <a href="index.php" class="navbar-brand">CCT Library</a>
        <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item px-2">
              <a href="index.php" class="nav-link active">Dashboard</a>
            </li>
            <li class="nav-item px-2">
              <a href="book/" class="nav-link">Books</a>
            </li>
            <li class="nav-item px-2">
              <a href="member/" class="nav-link">Users</a>
            </li>
            <li class="nav-item px-2">
              <a href="staff/" class="nav-link">Staffs</a>
            </li>
          </ul>
          <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown mr-3">
              <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-user"></i> Welcome <?php echo $_SESSION["user_name"]; ?>
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
            <h1><i class="fa fa-warning"></i> Over View</h1>
          </div>
        </div>
      </div>
    </header>

    <!-- ACTIONS -->
    <section id="action" class="py-4 mb-4 bg-light">
      <div class="container">
        <div class="row">
          <div class="col-md-3 mr-auto">
  					<a href="index.php" class="btn btn-light btn-block">
  						<i class="fa fa-arrow-left"></i> Back To Dashboard
  					</a>
  				</div>
        </div>
      </div>
    </section>

    <!-- POSTS -->
    <section id="posts">
      <div class="container">
        <div class="row">
          <div class="col">
            <div class="card">
              <div class="card-header">
                <h4>Latest Over Due Books</h4>
              </div>
              <table class="table table-striped">
                <thead class="thead-inverse">
                  <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>User</th>
                    <th>e-Mail</th>
                    <th>Loan Date</th>
                    <th>Expected Return</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $stmt = $conn -> prepare("SELECT books.book_title,
		                                               books.book_author,
		                                               users.user_name,
		                                               users.user_email,
		                                               borrowedbooks.borrowedbook_checkout,
		                                               borrowedbooks.borrowedbook_exreturn
		                                               FROM borrowedbooks
		                                               INNER JOIN users
		                                               ON borrowedbooks.user_id = users.user_id
		                                               AND borrowedbooks.borrowedbook_avaliable = 0
		                                               INNER JOIN books
		                                               ON borrowedbooks.book_id = books.book_id
                                                   WHERE borrowedbook_exreturn < current_timestamp()
		                                               ORDER BY borrowedbooks.borrowedbook_exreturn;");
                  $stmt -> execute();
                  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                  $count = 8;
                  foreach($rows as $row){
                    $count--;
                  ?>
                  <tr>
                    <td><?php echo $row['book_title']; ?></td>
                    <td><?php echo $row['book_author']; ?></td>
                    <td><?php echo $row['user_name']; ?></td>
                    <td><?php echo $row['user_email']; ?></td>
                    <td><?php echo date("d/m/Y", strtotime($row['borrowedbook_checkout'])); ?></td>
                    <td><?php echo date("d/m/Y", strtotime($row['borrowedbook_exreturn'])); ?></td>
                  </tr>
            <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
    <?php
    while ($count) {
        echo "<br/>";
        $count--;
    }
    ?>

    <footer id="main-footer" class="bg-dark text-white mt-5 p-5">
      <div class="conatiner">
        <div class="row">
          <div class="col">
            <p class="lead text-center">Copyright &copy; 2017 CCT Library</p>
          </div>
        </div>
      </div>
    </footer>

    <script src="../js/jquery.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
  </body>
</html>
