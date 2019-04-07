<?php
	include("../util/protection.php");
	require_once("../util/connection.php");
	require_once("../util/init.php");

	$msg = "";
	$act = "";

  if (isset($_POST['turnOn'])) {
    boilerDirect($_POST["ends"], $_SESSION["user_id"], $_SESSION["keycode"], $conn);
	}
	
	if(isset($_GET['r'])) {
		$act = $_GET['r'];
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
          <h1><i class="fa fa-users"></i> Adding New User</h1>
        </div>
      </div>
    </div>
  </header>

  <!-- ACTIONS -->
  <section id="action" class="py-4 mb-4 bg-light">
    <div class="container">
      <div class="row">
				<div class="col-md-2 mr-auto">
          <a href="index.php" class="btn btn-light btn-block">
            <i class="fa fa-arrow-left"></i> Back
          </a>
        </div>
				<?php include("../util/boilerBtn.php"); ?>
      </div>
    </div>
  </section>

  <!-- BOOK REGISTER -->
	<?php if (isset($_GET['r'])) {?>
	<section id="info">
		<div class="container">
			<div class="row">
				<div class="col-md-12 m-auto">
					<div class="alert alert-success alert-dismissible fade show">
							<button class="close" data-dismiss="alert" type="button">
									<span>&times;</span>
							</button>
							<strong>Successful registration!</strong>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php } ?>
	<?php if (isset($msg)) { ?>
	<section id="info">
		<div class="container">
			<div class="row">
				<div class="col-md-12 m-auto">
					<div class="alert alert-danger alert-dismissible fade show">
							<button class="close" data-dismiss="alert" type="button">
									<span>&times;</span>
							</button>
							<strong><?php echo $msg; ?></strong>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php } ?>
  <section id="profile">
    <div class="container">
      <div class="row">
        <div class="col-md-6 m-auto">
          <div class="card">
            <div class="card-header">
              <h4>New User</h4>
            </div>
            <div class="card-body">
              <form action="create.php" method="POST">
                <div class="form-group">
                  <label for="email">Email:</label>
                  <input type="text" class="form-control" name="user_email">
                </div>
								<div class="form-group">
									<label for="email">Level:</label>
										<div class="input-group">
											<select name="user_level" class="form-control">
											<option value="2">User</option>
											<option value="1">Admin</option>
											</select>
										</div>
								</div>
								<div class="form-group">
                  <br/>
									<button class="btn btn-primary" type="submit" <?php if ($_SESSION["user_level"] == 2) {echo "value='2' name='user_level'";} ?>>Save</button>
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
