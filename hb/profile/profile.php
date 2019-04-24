<?php
  include("../util/protectionLevel2.php");
  require_once("../util/connection.php");
  require_once("../util/init.php");

  $msg = "";
  $user_id = $_SESSION["user_id"];

  $sql = "SELECT * FROM users WHERE user_id = :id";
  $stmt = $conn -> prepare($sql);
  $stmt -> bindValue(':id', $user_id, PDO::PARAM_STR);
  $stmt -> execute();
  $user = $stmt->fetch();

  if ($_POST) {

    if ($_POST['user_name'] == "" || $_POST['user_email'] == "" ) {
      $msg = "error";
    }else{
    
      $user_name = $_POST['user_name'];
      $user_email = $_POST['user_email'];
    
      $upd = $conn -> prepare("UPDATE users SET user_name = :user_name, user_email = :user_email WHERE user_id = :user_id");
    
      $upd -> bindValue(":user_name", $user_name);
      $upd -> bindValue(":user_email", $user_email);
      $upd -> bindValue(":user_id", $user_id);
      $upd -> execute();

      $_SESSION["user_name"] = $user_name;
      $_SESSION["user_email"] = $user_email;
    
      header("Location: profile.php");
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
          <h1><i class="fa fa-user"></i> Edit Profile</h1>
        </div>
      </div>
    </div>
  </header>

  <!-- ACTIONS -->
  <section id="action" class="py-4 mb-4 bg-light">
    <div class="container">
      <div class="row">
        <div class="col-md-2">
          <a href="../" class="btn btn-light btn-block">
            <i class="fa fa-arrow-left"></i> Back
          </a>
        </div>
        <div class="col-md-2 mr-auto">
          <a href="pass.php" class="btn btn-success btn-block">
            <i class="fa fa-lock"></i> Change Password
          </a>
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
              <h4>Edit Profile</h4>
            </div>
            <div class="card-body">
              <form action="profile.php" method="POST">
                <div class="form-group">
                  <label for="name">Name</label>
                  <input type="text" class="form-control" name="user_name" value="<?= $user['user_name']; ?>">
                </div>
                <div class="form-group">
                  <label for="email">Email</label>
                  <input type="text" class="form-control" name="user_email" value="<?= $user['user_email']; ?>">
                </div>
								<div class="form-group">
                  <br/>
									<button class="btn btn-primary" name="save_btn" type="submit">Save</button>
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
