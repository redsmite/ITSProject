<?php
session_start();
include'functions.php';
require_once'connection.php';
user_access();
adminaccess();
admingoback();
chattab();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
 	<meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">
  	<link rel="stylesheet" href="css/fontawesome-all.css">
	<title><?php companytitle()?></title>
<body>
	<div class="container">
		<!-- Header -->
		<?php
			addheader();
		?>
	<!-- Login Form -->
		<div class="other-content">
			<h1>Site Admin</h1>
			<div class="container">
				<div class="content-box">
					<h2><span id="highlight-text">Admin</span> Login</h2>
					<div class="form">
						<center>
							<form action="#" method="post">
								<div>
									<label for="name">Name</label><br>
									<input type="text" autocomplete="off" name="admin-name">
								</div>
								<div>
									<label for="email">Password</label><br>
									<input type="password" name="admin-password">
								</div>
								<button type="submit" name="submit">Submit</button>
								<?php
if(isset($_POST['submit'])){
	$name=$conn->real_escape_string($_POST['admin-name']);
	$password=$conn->real_escape_string($_POST['admin-password']);

	$sql="SELECT name,password FROM tbladmin WHERE name='$name' and password='$password'";
	$result=$conn->query($sql);
	if($result->num_rows==0){
		echo'<div id="error-message"><i class="fas fa-exclamation-circle"></i>Admin login failed.</div>';
	} else {
		$_SESSION['admin']='IchigoParfait';
		header('Location: adminpanel.php');
	}
}
?>
							</form>
						</center>	
					</div>
				</div>
			</div>
		</div>
	<!-- Footer -->
		<?php
			addfooter();
		?>
	<!-- End of Container -->
	</div>
	<script src="js/main.js"></script>
</body>
</html>