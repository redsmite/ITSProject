<?php
session_start();
include'functions.php';
require_once'connection.php';
user_access();
adminaccess();
admingoback();
addSidebar();
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
<body onscroll="scrollOpacity()">
	<div class="main-container">
		<!-- Header -->
		<?php
			addheader();
		?>
	<!-- Login Form -->
		<div class="other-content">
			<h1>Site Admin</h1>
			<div class="container">
				<h2><span id="highlight-text">Admin</span> Login</h2>
				<div class="form">
					<center>
						<form id="admin-login" method="post">
							<div>
								<label for="name">Name</label><br>
								<input type="text" autocomplete="off" id="admin-name">
							</div>
							<div>
								<label for="email">Password</label><br>
								<input type="password" id="admin-password">
							</div>
							<button type="submit" name="submit">Submit</button>
							<div id="error-message3"></div>
						</form>
					</center>	
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
	<script>
		let input = document.getElementById('admin-name');
		input.focus();
		adminLogin();
	</script>
</body>
</html>