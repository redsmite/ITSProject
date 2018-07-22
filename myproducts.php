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
<body>
	<div class="main-container">
	<!-- Header -->
	<?php
		addheader();
	?>
	<!-- Content -->
	<div class="other-content">
		<h1><a class="btp" href="profile.php?name=<?php echo $username ?>">Back to <?php echo $username ?>'s Profile</a></h1>
		<h3><?php echo $username;?>'s Products</h3>
		<div class="my-products">
<?php
$sql = "SELECT productid,category, productname, description, farmname, username, dateposted, price, img, rating FROM tblproduct as t1
LEFT JOIN tblcategory as t2
	ON t1.categoryid = t2.categoryid
LEFT JOIN tbluser as t3
	ON t1.userid = t3.userid
LEFT JOIN tblfarm as t4
	ON t1.farmid = t4.farmid
WHERE t1.userid = '$getid'
ORDER BY dateposted DESC";
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
	<p class="product-price">₱'.$price.' / kg</p>
	<div class="add-to-cart" value="'.$id.'" onclick="addThistoCart(this)"><i class="fas fa-shopping-cart"></i> Add to Cart</div>
	</div>
	</div>';
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
	</script>
</body>
</html>