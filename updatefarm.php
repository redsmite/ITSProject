<?php
session_start();
include'functions.php';
require_once'connection.php';
if(isset($_GET['id'])){
$id = $_GET['id'];
}else{
	die('This page doesn\'t exist.');
}
addSidebar();
addLogin();
setupCookie();
updateStatus();
adminpanelAccess();
chattab();

$sql = "SELECT farmid,farmname,address,status FROM tblfarm WHERE farmid='$id'";
$result = $conn->query($sql);
while($row=$result->fetch_object()){
	$id = $row->farmid;
	$name = $row->farmname;
	$address = $row->address;
	$status = $row->status;
}

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
	<div class="main-container">
	<!-- Header -->
	<?php
		addheader();
	?>
	<div class="other-content">
		<a class="btp" href="adminpanel.php">Back to Admin Panel</a>
		<h1>Update <?php if(!$result){}echo $name ?></h1>
		<div class="edit-form">
			<form id="update-farm-form">
				<div>
					<p>Farm Name</p>
					<input type="text" value="<?php if(!$result){}else{echo $name;}?>" id="farm-name">
				</div>
				<div>
					<p>Address</p>
					<textarea id="farm-address"><?php if(!$result){}else{echo $address;}?></textarea>
				</div>
				<div>
					<p>Status</p>
					<?php
					if(!$result){}else{
						if($status==1){
					echo'<input checked type="checkbox" id="status">;';
					}else{
					echo'<input type="checkbox" id="status">';
					}
					}
					?>
				</div>
				<input type="hidden" value="<?php if(!$result){}else{echo $id;}?>" id="farm-id">
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
		updateFarm();
	</script>
</body>
</html>