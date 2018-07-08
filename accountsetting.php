<?php
	session_start();
	include'functions.php';
	user_access();
	updateStatus();
	//Get Profile Info
	require_once'connection.php';
	
	if(isset($_SESSION['id'])){
		$id = $_SESSION['id'];
		$sql="SELECT userid,username,password,email,lastupdate FROM tbluser WHERE userid='$id'";
		
		$result=$conn->query($sql);

		if($result->num_rows == 0){
			die('<div id="thanks-message"><p>This username doesn\'t exist.</p></div>');
		}

		$rows=$result->fetch_object();
		
		$id=$rows->userid;
		$username=$rows->username;
		$password=$rows->password;
		$email=$rows->email;
		$lastupdate=$rows->lastupdate;
		$timestamp = date("Y-m-d H:i:s");
		mysqli_close($conn);
	}
	//End of Get Profile Info
	addSidebar();
	addLogin();
	setupCookie();
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
</head>
<body>
	<div class="main-container">
	<!-- Header -->
	<?php
		addheader();
	?>
	<!-- Main Content -->
		<div class="other-content">
			<h1><i class="fas fa-cog"></i> Account Settings</h1>
			<div class="edit-form">
				<center>
				<form action="" id="edit-username" method="post">
					<div>
						<input type="hidden" id="hidden" <?php echo 'value="'.$lastupdate.'"'?> />
						<input type="hidden" id="hidden2" <?php echo 'value="'.$timestamp.'"'?> />
						<label for="">New Username</label><br>
						<p>*You can only change your username once per month</p>
						<p>*Must not exceed 20 characters and not include spaces or special characters.</p>
						<input type="text" <?php echo 'value="'.$username.'"'?> required id="edit-name" name="edit-name" placeholder="Enter New Username...">
					</div>
					<div>
						<button type="submit" name="username-submit" id="username-submit">Submit</button>
					</div>
					<div id="error-message2"></div>
				</form>
				</center>
			</div>
			<div class="edit-form">
				<center>
				<form action="" id="edit-email" method="post">
					<div>
						<label for="">New Email</label><br>
						<input type="text" <?php echo 'value="'.$email.'"'?> required id="editemail" name="edit-email" placeholder="Enter New Username...">
					</div>
					<div>
						<button type="submit" name="email-submit" id="email-submit">Submit</button>
					</div>
					<div id="error-message4"></div>
				</form>
				</center>
			</div>
			<div class="edit-form">
				<center>
				<form action="" id="edit-password" method="post">
					<div>
						<input type="hidden" id="hidden3" <?php echo 'value="'.$password.'"'?> />
						<label for="">Old Password(for verification)</label><br>
						<input type="password" required id="edit-oldpassword" name="edit-oldpassword" placeholder="Enter Old Password...">
					</div>
					<div>
						<label for="">New Password</label><br>
						<input type="password" required id="edit-newpassword" name="edit-newpassword" placeholder="Enter New Password...">
					</div>
					<div>
						<label for="">Re-type New Password</label><br>
						<input type="password" required id="edit-retype" name="edit-retype" placeholder="Retype New Password...">
					</div>
					<div>
						<button type="submit" name="password-submit" id="password-submit">Submit</button>
					</div>
					<div id="error-message3"></div>
				</form>
				</center>
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
		ajaxLogin();
		AjaxEditUser();
		AjaxEditEmail();
		AjaxEditPass();
	</script>
</body>
</html>