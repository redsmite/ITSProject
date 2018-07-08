<?php
	session_start();
	include'functions.php';
	user_nonAccess();
	addSidebar();
	addLogin();
	setupCookie();
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
</head>
<body>
	<div class="main-container">
	<!-- Header -->
	<?php
		addheader();
	?>
	<!-- Contact Form -->
		<div class="other-content">
			<h1>Register</h1>
			<div class="container">
				<div class="content-box">
					<h2><span id="highlight-text">Create</span> an Account</h2>
					<div class="form">
						<center>
						<form action="registerprocess.php" id="reg-form" method="post">
							<div class="grid-register">
								<div>
									<label for="">Username</label><br>
									<small><i>*Must not exceed 20 characters and not include spaces or special characters</i></small>
									<input type="text" required id="reg-name" placeholder="Enter Username...">
								</div>
								<div>
									<label for="">Email</label><br>
									<input type="email" required id="reg-email" placeholder="Enter Email...">
								</div>
								<div>
									<label for="">Password</label><br>
									<small><i>*Must be atleast 8 character or longer.</i></small>
									<input type="password" required id="reg-password" placeholder="Enter Password...">
								</div>
								<div>
									<label for="">Confirm Password</label><br>
									<input type="password" required id="reg-retype" placeholder="Retype Password...">
								</div>
								<div>
									<button type="submit" name="contact-button" id="contact-button">Submit</button>
								</div>
								<div id="error-message2"></div>
							</div>	
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
	<script>
		modal();
		ajaxRegister();
		ajaxLogin();
	</script>
</body>
</html>