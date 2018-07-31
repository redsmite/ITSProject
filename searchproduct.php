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
		<div class="my-products">
<?php
	if(isset($_SESSION['id'])){
		if($_SESSION['type']==3 OR $_SESSION['type']==4){
			echo'<a href="addproduct.php" class="white"><div class="add-product-button">
			<i class="fas fa-plus"></i> Add Product
			</div></a>';
		}
	}
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

		$sql = "SELECT productid,category, productname, description, farmname, username, dateposted, price, img, rating FROM tblproduct as t1
LEFT JOIN tblcategory as t2
	ON t1.categoryid = t2.categoryid
LEFT JOIN tbluser as t3
	ON t1.userid = t3.userid
LEFT JOIN tblfarm as t4
	ON t1.farmid = t4.farmid
WHERE productname LIKE '%$search%' $limit AND is_approved = 1 AND is_available = 1
ORDER BY view,dateposted DESC";
$result = $conn->query($sql);
while($row = $result->fetch_object()){
	$id = $row->productid;
	$category = $row->category;
	$product = $row->productname;
	$desc = $row->description;
	$farm = $row->farmname;
	$user = $row->username;
	$date = date('F j, Y',strtotime($row->dateposted));
	$price = $row->price;
	$img = $row->img;
	if(!$img){
		$img='img/default2.jpg';
	}
	$rating = $row->rating;

	echo'
	<div class="product">
	<a href="product.php?id='.$id.'">
	<div class="product-img-wrap">
		<img src="'.$img.'" alt="Product Image">
	</div>
	<p class="product-title">'.$product.'</p>
	</a>
	<div class="product-content">
	<a href="product.php?id='.$id.'">
	<p>';

	starsystem($rating);

	echo'
	</p>

	<p class="product-category">'.$category.'</p>
	<p class="product-desc">Description: '.substr($desc,0,30).' ...</p>
	</a>
	<p class="product-price">₱'.number_format($price,2).' / kg</p>
	<div class="add-to-cart" value="'.$id.'" onclick="addThistoCart(this)"><i class="fas fa-shopping-cart"></i> Add to Cart</div>
	</div>
	</div>';
}
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
