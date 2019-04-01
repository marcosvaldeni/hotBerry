<?php
//  MEMBER AREA
include("protection.php");
include("../connection.php");
$search;
if ($_GET && $_GET['search']) {
	$search = $_GET['search'];
	$opt = $_GET['opt'];
	$stmt = $conn -> prepare("SELECT * FROM books WHERE ".$opt." like '%".$search."%';");
	$stmt -> execute();
	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
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
						<a href="index.html" class="nav-link active">Dashboard</a>
					</li>
					<li class="nav-item px-2">
						<a href="#" class="nav-link">Books</a>
					</li>
					<li class="nav-item px-2">
						<a href="historic.php" class="nav-link">Historic</a>
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
					<h1><i class="fa fa-book"></i> Books</h1>
				</div>
			</div>
		</div>
	</header>

	<!-- ACTIONS -->
	<section id="action" class="py-4 mb-4 bg-light">
		<div class="container">
			<div class="row">
				<div class="col-md-6 ml-auto">
					<form action="" method="GET">
						<div class="input-group">
							<select name="opt" class="form-control">
							<option value="book_title">Title</option>
							<option value="book_author">Author</option>
							<option value="book_ISBN">ISBN</option>
							</select>
							<input type="text" class="form-control" name="search" placeholder="Search">
							<span class="input-group-btn">
								<button class="btn btn-success" type="submit">Search</button>
							</span>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>


	<?php
	if(isset($search)){
	?>
	<!-- POSTS -->
	<section id="posts">
		<div class="container">
			<div class="row">
				<div class="col">
					<div class="card">
						<div class="card-header">
							<h4>Books Search</h4>
						</div>
						<table class="table table-striped">
							<thead class="thead-inverse">
								<tr>
									<th>Title</th>
									<th>Author</th>
									<th>ISBN-10</th>
									<th>Avaliable</th>
								</tr>
							</thead>
							<tbody>

								<?php foreach($rows as $row){ ?>
								<tr>
									<td><?php echo $row['book_title']; ?></td>
									<td><?php echo $row['book_author']; ?></td>
									<td><?php echo $row['book_ISBN']; ?></td>
									<td><?php if ($row['book_avaliable'] == 1) { ?>
										<span class="btn btn-success">Available</span>
									<?php }else{?>
										<span class="btn btn-danger">Unavailable</span>
									<?php } ?>
									</td>
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

<?php }else{ ?>

<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>

<?php } ?>

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

</body>
</html>
