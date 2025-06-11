<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username']) or $_SESSION["logged_in"]!=="true") {
    // Redirect to the login page or handle unauthorized access
    header("Location: /login.html");
    exit();
}

$username = htmlspecialchars($_SESSION['username']);
$perms = $_SESSION["perms"];
if(isset($_GET["page"])){
	$page=htmlspecialchars($_GET["page"]);
}else{
	$page="dashboard.php"; //this is actually the Dashboard
}

?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
	 <title>Server Manager (<?php echo(str_replace("_"," ",explode(".",$page))[0]); ?>)</title>
</head>
<body>
	<!-- navbar -->
	<nav class="navbar navbar-dark bg-dark">
	  <div class="container-fluid">
		<span class="navbar-text">
		  Server Manager (<?php echo(str_replace("_"," ",explode(".",$page))[0]); ?>)
		</span>
		<span class="navbar-text text-center">
		  <?php echo($username); ?>
		</span>
		<span class="navbar-text text-right">
			<a href="/login/logout.php">Logout</a>
		</span>
	  </div>
	</nav>

	<div class="container-fluid">
	  <div class="row">
		<!-- sidebar -->
		<div class="col-2">
		  <p>Home</p>
		  <ul>
			<li><a href="index.php?page=dashboard.php">Dashboard</a></li>
			<li><a href="index.php?page=tasks.php">Tasks</a></li>
		  </ul>
		</div>

		<!-- main part, with iframe -->
		<div class="col-10" >
		 <!-- iframe -->
			<iframe src="<?php echo(str_replace(["://","http"],"",$page)); ?>" width="100%" height="1000px" frameborder="0" style="overflow:hidden"></iframe>
		</div>
	  </div>
	</div>
</body>
</html>
