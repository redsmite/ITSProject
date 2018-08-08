<?php
session_start();
include'functions.php';
include'connection.php';
addSidebar();
addLogin();
setupCookie();
updateStatus();
chattab();

if(isset($_GET['id'])){
	$id = $_GET['id'];
}else{
	die('This farm doesn\'t exist');
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
</head>
<body onscroll="scrollOpacity()">
	<div class="main-container">
	<!-- Header -->
	<?php
		addheader();
	?>
	<!-- Main Content -->
	<div class="other-content">
<?php
if(isset($_SESSION['id'])){
	if($_SESSION['type']==3 OR $_SESSION['type']==4){
		echo'<a href="addproduct.php" class="white"><div class="add-product-button">
		<i class="fas fa-plus"></i> Add Product
		</div></a>';
	}
}
echo '<div class="my-products">';
$sql = "SELECT farmname FROM tblfarm WHERE farmid = '$id'";
$result = $conn->query($sql);
$fetch = $result->fetch_object();
$farmname = $fetch->farmname;

echo '<h3>'.$farmname.' Farm</h3>';

$string = "WHERE t1.farmid = '$id' AND is_available = 1 AND is_approved = 1";
showProduct($string);
echo'</div>';
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