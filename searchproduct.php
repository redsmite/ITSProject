<?php
session_start();
include'functions.php';
include'connection.php';
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
// Sort by Farm
echo'<div class="select-farm">
Select Farm: <select id="selectFarm"';

if(isset($_GET['search'])){
	$search = $_GET['search'];

	if(isset($_GET['select'])){
		$criteria = $_GET['select'];
		$sql = "SELECT category FROM tblcategory WHERE categoryid='$criteria'";
		$result = $conn->query($sql);
		$fetch = $result->fetch_object();
		if(!$fetch){
			die('This category doesn\'t exists.');
		}
	}

	if(!isset($criteria)){
		$limit = "";
	}else{
		$limit = " AND t1.categoryid='$criteria'";			
	}
	echo 'search="'.$search.'"';
	echo 'select="'.$limit.'"';
}

echo'onchange="selectFarm()">
<option disabled selected>Select Farm</option>';

$sql = "SELECT farmid, farmname FROM tblfarm";
$result = $conn->query($sql);
while($row = $result->fetch_object()){
	$farmid = $row->farmid;
	$farm = $row->farmname;
	echo '<option value="'.$farmid.'">'.$farm.'</option>';
}
echo'</select></div>';
// Sort by Price
echo'<div class="select-farm">
Sort by Price: <select id="selectPriceOrder"';

if(isset($_GET['search'])){
	$search = $_GET['search'];

	if(isset($_GET['select'])){
		$criteria = $_GET['select'];
		$sql = "SELECT category FROM tblcategory WHERE categoryid='$criteria'";
		$result = $conn->query($sql);
		$fetch = $result->fetch_object();
		if(!$fetch){
			die('This category doesn\'t exists.');
		}
	}

	if(!isset($criteria)){
		$limit = "";
	}else{
		$limit = " AND t1.categoryid='$criteria'";			
	}
	echo 'search="'.$search.'"';
	echo 'select="'.$limit.'"';
}

echo'onchange="selectPriceOrder()">
<option disabled selected>Select Order</option>
<option value="1">from lowest</option>
<option value="2">from highest</option>
</select></div>';
?>
		</select>
		<div class="my-products">
<?php
	
	if(isset($_GET['search'])){
		$search = $_GET['search'];

		if(isset($_GET['select'])){
			$criteria = $_GET['select'];
			$sql = "SELECT category FROM tblcategory WHERE categoryid='$criteria'";
			$result = $conn->query($sql);
			$fetch = $result->fetch_object();
			if(!$fetch){
				die('This category doesn\'t exists.');
			}
			$category = $fetch->category;
			echo '<h1>'.$category.'</h1>';
		}

		if(!isset($criteria)){
			$limit = "";
		}else{
			$limit = " AND t1.categoryid='$criteria'";			
		}
		$string = "WHERE productname LIKE '%$search%' $limit AND is_approved = 1 AND is_available = 1
			ORDER BY view,dateposted DESC";
		showProduct($string);
	}
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
