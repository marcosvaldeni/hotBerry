<?php
//  MEMBER AREA
include("protection.php");
include("../connection.php");
$user_id = $_SESSION["user_id"];

$stmt = $conn -> prepare("SELECT users.user_name,
													books.book_title,
													books.book_author,
													borrowedbooks.borrowedbook_checkout,
													borrowedbooks.borrowedbook_exreturn,
													borrowedbooks.borrowedbook_checkin,
													borrowedbooks.borrowedbook_avaliable
													FROM borrowedbooks
													INNER join users ON users.user_id = borrowedbooks.user_id
													INNER join books ON books.book_id = borrowedbooks.book_id
													WHERE users.user_id = ".$user_id."
													ORDER BY borrowedbooks.borrowedbook_exreturn DESC;");

$stmt -> execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
	<title>CCT Library</title>
	<link rel="stylesheet" href="../css/font-awesome.min.css">
	<link rel="stylesheet" href="../css/bootstrap.css">
	<link rel="stylesheet" href="../css/style.css">
</head>
<body>

	<!-- NAVBAR -->
	<nav class="navbar navbar-expand-sm navbar-dark bg-dark p-0">
		<div class="container">
			<a href="index.html" class="navbar-brand">CCT Library</a>
			<button class="navbar-toggler" data-toggle="collapse" data-target="#navbarNav">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarNav">
				<ul class="navbar-nav">
					<li class="nav-item px-2">
						<a href="index.php" class="nav-link">Dashboard</a>
					</li>
					<li class="nav-item px-2">
						<a href="#" class="nav-link">Books</a>
					</li>
					<li class="nav-item px-2">
						<a href="historic.php" class="nav-link active">Historic</a>
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
								<i class="fa fa-user-circle"></i> Password
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

	<header id="main-header" class="py-2 bg-success text-white">
		<div class="container">
			<div class="row">
				<div class="col-md-6">
					<h1><i class="fa fa-book"></i> Historic</h1>
				</div>
			</div>
		</div>
	</header>

	<!-- ACTIONS -->
	<section id="action" class="py-4 mb-4 bg-light">
		<div class="container">
			<div class="row">
				<div class="col-md-6 ml-auto">
					<div class="input-group">
						<br/>
						<br/>
							</form>
					</div>
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
							<h4>Latest Books Loaned</h4>
						</div>
						<table class="table table-striped">
							<thead class="thead-inverse">
								<tr>
									<th>Title</th>
									<th>Author</th>
									<th>Take Date</th>
									<th>Expected Date</th>
									<th>Returned Date</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody>

								<?php foreach($rows as $row){
									$dia = date("Y-m-d", strtotime($row['borrowedbook_exreturn']));
									$borrowedbook_exreturn = new DateTime(date("Y-m-d", strtotime($row['borrowedbook_exreturn'])));
									$exreturn = $borrowedbook_exreturn->format('U');
									?>
								<tr>
									<th><?php echo $row['book_title']; ?></th>
									<th><?php echo $row['book_author']; ?></th>
									<th><?php echo date("d/m/Y", strtotime($row['borrowedbook_checkout'])); ?></th>
									<th><?php echo date("d/m/Y", strtotime($row['borrowedbook_exreturn'])); ?></th>
									<th>
										<?php if ($row['borrowedbook_checkin'] == null) {
														echo "...";
													}else{
														echo date("d/m/Y", strtotime($row['borrowedbook_checkin']));
													} ?>
									</th>
									<th>
										<?php if ($row['borrowedbook_avaliable'] == 1) { ?>
											<span class="btn btn-success">Returned</span>
										<?php }else{
														if ($exreturn < time()) { ?>
															<span class="btn btn-danger">Over Due</span>
										 <?php }else{ ?>
															<span class="btn btn-warning">Unreturned</span>
										 <?php }
										 			} ?>
									</th>
								</tr>
							<?php } ?>

							</tbody>
						</table>
					</nav>
				</div>
			</div>
		</div>
	</div>
</section>

	<footer id="main-footer" class="bg-dark text-white mt-5 p-5">
		<div class="container">
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
	<script src="https://cdn.ckeditor.com/4.7.1/standard/ckeditor.js"></script>
	<script>
			CKEDITOR.replace( 'editor1' );
	</script>

</body>
</html>
