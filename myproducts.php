<?php
	session_start();
	include'functions.php';
	require_once'connection.php';

	if(!isset($_GET['id'])){
		die('This page doesn\'t exist.');
	}else{
		$getid=$_GET['id'];
		$sql = "SELECT username FROM tbluser WHERE userid='$getid' AND usertypeid IN(3,4)";
		$result = $conn->query($sql);
		$fetch = $result->fetch_object();

		if(!$fetch){
			die('This page doesn\'t exist.');
		}else{
			$username = $fetch->username;
		}
	}

	if(isset($_SESSION['id'])){
		$tuserid = $_SESSION['id'];
	}else{
		$tuserid = 'none';
	}

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
		<h1><a class="btp" href="profile.php?id=<?php echo $_GET['id'] ?>">Back to <?php echo $username ?>'s Profile</a></h1>
		<h3><?php echo $username;?>'s Products</h3>
		<div class="my-products">
<?php
	$string = "WHERE t1.userid = '$getid'";
	showProduct($string);
?>
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
		ajaxLogin();
	</script>
</body>
</html>