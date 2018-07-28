<?php
session_start();
include'functions.php';
require_once'connection.php';

addSidebar();
addLogin();
setupCookie();
updateStatus();
adminpanelAccess();
chattab();

$id = $_SESSION['id'];

$sql = "SELECT name FROM tbladmin";
$result = $conn->query($sql);
$fetch = $result->fetch_object();
$name = $fetch->name;

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
	<div class="other-content">
		<a class="btp" href="adminpanel.php">Back to Admin Panel</a>
		<h1><i class="fas fa-cog"></i> Admin Settings</h1>
		<div class="edit-form">
			<form id="update-admin">
				<div>
					<h1>Admin Account Settings</h1>
					<p>Admin Name</p>
					<input type="text" required value="<?php if(!$result){}else{echo $name;}?>" id="admin-name">
				</div>
				<div>
					<p>Old Password (For Validation)</p>
					<input type="password" required id="old-pass">
				</div>
				<div>
					<p>New Password</p>
					<input type="password" id="new-pass">
				</div>
					<p>Confirm Password</p>
					<input type="password" id="confirm-pass">
				<div>
					<br>
					<button>Submit</button>
				</div>
				<div id="error-message2"></div>
			</form>
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
		adminSetting();
	</script>
</body>
</html>