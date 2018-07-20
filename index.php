<?php
	session_start();
	include'functions.php';
	require_once'connection.php';
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
	<!-- Showcase -->
		<div class="main-search">
			<div id="browse-category" onclick="showCategory()">Browse Category
			</div>
			<div id="category-modal" onclick="hideCategory()"></div>
			<div id="category-slide">
<?php
	$sql = "SELECT categoryid,category FROM tblcategory WHERE status =1";
	$result = $conn->query($sql);
	while($row = $result->fetch_object()){
		$category = $row->category;
		$id = $row->categoryid;

		echo '<p value="'.$id.'">'.$category.'</p>';
	}
?>
			</div>
			<form id="main-search-form">
				<div>
					<input type="text" id="main-search" placeholder="Search for Products...">
					<select  id="main-select" >
						<option disabled selected>Select Category</option>
<?php
// Select Category
	$sql = "SELECT categoryid,category FROM tblcategory WHERE status =1";
	$result = $conn->query($sql);
	while($row = $result->fetch_object()){
		$category = $row->category;
		$id = $row->categoryid;

		echo '<option value="'.$id.'">'.$category.'</option>';
	}
?>
					</select>
					<i class="fas fa-search"></i>
				</div>
			</form>
		</div>
	<!-- Content -->
		<div class="main-content">
			<div class="main-content-grid">
				<div class="announcement">
<?php
$sql="SELECT title,content,t1.datecreated,username FROM tblannouncement AS t1
LEFT JOIN tbluser
	ON userid = author
ORDER BY announceid DESC
LIMIT 1";
$result= $conn->query($sql);
while($row=$result->fetch_object()){
	$title = $row->title;
	$content = $row->content;
	$date = date('D, F j Y g:i A',strtotime($row->datecreated));
	$author = $row->username;

	echo '<h2 id="announcement-title">'.$title.'</h2>
	<p>Posted on: '.$date.' by: <a href="profile.php?name='.$author.'">'.$author.'</a></p>
	<div class="announce-content">'.substr(nl2br($content), 0, 450);
	if(strlen($content) > 450 ){
		echo'...';
	}

	echo'<br>
		<a class="center" id="announcement-comment" href="announcement.php">Read More</a>
		</div>';
}
?>
				</div>
				<div class="advertisement">
					<div class="advertisement-inner">
						<img src="img/neiman_marcus.gif" alt="advertisement">
					</div>
				</div>
				<div class="content-body">
					<h3>Products</h3>
<?php
$sql = "SELECT category, productname, description, farmname, username, dateposted, price, img FROM tblproduct as t1
LEFT JOIN tblcategory as t2
	ON t1.categoryid = t2.categoryid
LEFT JOIN tbluser as t3
	ON t1.userid = t3.userid
LEFT JOIN tblfarm as t4
	ON t1.farmid = t4.farmid
ORDER BY dateposted DESC
LIMIT 15";
$result = $conn->query($sql);
while($row = $result->fetch_object()){
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

	echo'<div class="product">
	<div class="product-img-wrap">
		<img src="'.$img.'" alt="Product Image">
	</div>
	<p class="product-title">'.$product.'</p>

	<p>';

	starsystem(100);

	echo'
	</p>

	<p class="product-category">'.$category.'</p>
	<p class="product-desc">Description: '.substr($desc,0,30).' ...</p>
	<p class="product-location">'.$farm.'</p>
	<p class="product-seller"> Seller:<a href="profile.php?name='.$user.'" class="black">'.$user.'</a></p>
	<p class="product-date">'.$date.'</p>
	<p class="product-price">₱'.$price.' / kg</p>
	<div class="add-to-cart"><i class="fas fa-shopping-cart"></i> Add to Cart</div>
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
		let search= document.getElementById('main-search');
		search.focus();
		modal();
		ajaxLogin();
	</script>
</body>
</html>