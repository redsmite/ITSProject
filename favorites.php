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
	$id = $_SESSION['id'];
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
		<h1><a class="btp" href="profile.php?id=<?php echo $id ?>">Back to your Profile</a></h1>
		<h3><i class="fas fa-heart"></i> Favorites</h3>
		<div class="my-products">
<?php
	$sql = "SELECT productid FROM tblfavorite WHERE userid = '$id'";
	$result = $conn->query($sql);
	$products = '';
	while($row = $result->fetch_object()){
		$productid = $row->productid;
		$products .= $productid.',';
	}
	$products = rtrim($products,',');
	$string = "WHERE productid IN ($products) AND is_available = 1 AND is_approved= 1 ORDER BY dateposted DESC";
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