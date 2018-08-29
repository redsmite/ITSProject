<?php
	session_start();
	include'functions.php';
	require_once'connection.php';
	user_access();
	addSidebar();
	addLogin();
	setupCookie();
	updateStatus();
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
<body onscroll="scrollOpacity()">
	<div class="main-container">
	<!-- Header -->
	<?php
		addheader();
	?>
	<!-- Content -->
	<div class="other-content">
		<h1><a class="btp" href="profile.php?id=<?php echo $_SESSION['id'] ?>">Back to Your Profile</a></h1>
<?php
// Countdown timer
$sql = "SELECT cutoff FROM tblcutoff";
	$result = $conn->query($sql);
	$fetch = $result->fetch_object();
	$cutoff = $fetch->cutoff;

	$cutoff2 = strtotime($cutoff);
	$datenow = date('Y-m-d H:i:s');
	$now = strtotime('now');
	$diff = $cutoff2 - $now;
	$diff = gmdate("H:i:s", $diff);
	echo '<h3 id="cutoff-time" value="'.$cutoff.'" now="'.$datenow.'">Cut Off Time in: '.$diff.' </h3>';
// Order Tracking
	echo'<h2>Order Tracking</h2>
	<div style="color:white; text-align:center; margin-top:10px;">Select Status: <select onchange="selectOrderStatus()" id="selectStatus">
	<option selected disabled>Select Status...</option> 
	<option value="0">Pending</option>
	<option value="1">Approved</option>
	<option value="4">Completed</option>
	<option value="3">Cancelled</option>
	<option value="1">Rejected</option>
	</select></div>
	';

	echo'<div id="orderTrackingBody">
		</div>';

?>
	</div>
	<!-- Footer -->
		<?php
			addfooter();
		?>
	<!-- End of Container -->
	</div>
	<script src="js/main.js"></script>
	<script>
		cutoffCountdown();
	</script>
</body>
</html>