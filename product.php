<?php
session_start();
include'functions.php';
require_once'connection.php';


if(isset($_GET['id'])){
	$id = $_GET['id'];
}else{
	die('This page doesn\'t exist');
}

if(isset($_SESSION['id'])){
	$tuserid = $_SESSION['id'];
	$tusertype = $_SESSION['type'];
}else{
	$tuserid = 'none';
	$tusertype = 'none';
}

$sql = "SELECT productid,category, productname, description, farmname, username, t1.userid, dateposted, price, view, img, rating, is_approved, is_available, low, prevailing, high FROM tblproduct as t1
LEFT JOIN tblcategory as t2
	ON t1.categoryid = t2.categoryid
LEFT JOIN tbluser as t3
	ON t1.userid = t3.userid
LEFT JOIN tblfarm as t4
	ON t1.farmid = t4.farmid
WHERE productid='$id'";
$result = $conn->query($sql);
$row = $result->fetch_object();
if(!$row){
	die('This page doesn\'t exist');
}else{
	$id = $row->productid;
	$category = $row->category;
	$product = $row->productname;
	$desc = $row->description;
	$farm = $row->farmname;
	$userid = $row->userid;
	$user = $row->username;
	$date = date('F j, Y',strtotime($row->dateposted));
	$price = $row->price;
	$view = $row->view;
	$img = $row->img;
	if(!$img){
		$img='img/default2.jpg';
	}
	$ProductRating = $row->rating;
	$low = $row->low;
	$prev = $row->prevailing;
	$high = $row->high;
	$approved = $row->is_approved;
	if($approved == 0 AND $userid!=$tuserid AND $tusertype!=4){
		die('This product is currently not approved by the admin.');
	}
	$available = $row->is_available;
	if($available == 1){
		$available = 'Available';
	}else{
		if($userid!=$tuserid AND $tusertype!=4){
			die('This product is not available.');
		}else{
			$available = 'Not Available';
		}
	}
}

// Rating count
$sql="SELECT ratingid FROM tblrating WHERE productid='$id' ";
$result = $conn->query($sql);
$votercount = $result->num_rows;

// Product view
if(!isset($_SESSION['id'])){

	$sql = "UPDATE tblproduct SET view=view+1 WHERE productid='$id'";
	$result = $conn->query($sql);

}else if($userid!=$_SESSION['id']){
	$sql = "UPDATE tblproduct SET view=view+1 WHERE productid='$id'";
	$result = $conn->query($sql);
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
		<div class="product-grid">
			<div class="product-main-left">
				<div class="product-image-wrap">
					<img src="<?php echo $img ?>">
				</div>

				<div class="product-main-content">
					<h1><?php echo $product; ?></h1>
					<?php starsystem($ProductRating);
					echo '<p>(Rated by '.number_format($votercount);
					if($votercount==1){
						echo ' user';
					}else{
					 echo ' users';
					}
					echo')</p>'?>
					<p class="product-price">₱ <?php echo $price; ?> / kg</p>
					<ul>
						<li><b>Category:</b> <?php echo $category;?></li>
						<li><b>Farm:</b> <?php echo $farm ?></li>
						<li><b>Description:</b> <?php echo $desc;?></li>
						<li><b>Seller:</b> <a href="profile.php?id=<?php echo $userid; ?>" class="black"> <?php echo $user; ?></a></li>
						<li><b>Date Posted:</b> <?php echo $date?></li>
						<li><b>Status:</b> <?php echo $available?></li>
						<li><b>Views:</b> 
							<?php
							if(isset($_SESSION['id'])){ 
								 if($_SESSION['id']==$userid){
								 	echo number_format($view);
								 }else{
								 	echo number_format($view+1);
								 } 
							 }else{
								 echo number_format($view+1);
							 }
							 ?>
						</li>
					</ul>
<?php
	if($available != 'Not Available' AND $approved != 0){
	// Add-to-Cart
		echo'<div class="add-to-cart" value="'. $id.'" onclick="addThistoCart(this)"><i class="fas fa-shopping-cart"></i> Add to Cart
						</div>';

	// Add to favorite
	if(isset($_SESSION['id'])){
		$Favuserid = $_SESSION['id'];
		$sql = "SELECT favid FROM tblfavorite WHERE userid = '$Favuserid' AND productid = '$id'";
		$result = $conn->query($sql);
		$count = $result->num_rows;
		if($count==0){
			echo'<div class="red-cart" id="favorite-button" value="'. $id.'" onclick="addToFavorite(this)"><i class="far fa-heart"></i>  Favorite</div>';
		}else{
			echo'<div class="red-cart" id="favorite-button" value="'. $id.'" onclick="removeFavorite(this)"><i class="fas fa-heart"></i> Unfavorite</div>';
		}
		}
	}
// 5 star rating
	if(isset($_SESSION['id'])){
	$myid = $_SESSION['id'];
	$sql="SELECT rating FROM tblrating WHERE userid='$myid' AND productid='$id'";
	$result = $conn->query($sql);
	if(!$result->num_rows){
	echo'<div id="rate-this" onmouseleave="starLeave()">
		<h1>Rate This</h1>
		<i id="star1" class="fas fa-star" onmouseover="star1hover()" onclick="clickstar(this)" value="'.$_SESSION['id'].'" name="'.$_GET['id'].'" rating="1"></i>
		<i id="star2" class="fas fa-star" onmouseover="star2hover()" onclick="clickstar(this)" value="'.$_SESSION['id'].'" name="'.$_GET['id'].'" rating="2"></i>
		<i id="star3" class="fas fa-star" onmouseover="star3hover()" onclick="clickstar(this)" value="'.$_SESSION['id'].'" name="'.$_GET['id'].'" rating="3"></i>
		<i id="star4" class="fas fa-star" onmouseover="star4hover()" onclick="clickstar(this)" value="'.$_SESSION['id'].'" name="'.$_GET['id'].'" rating="4"></i>
		<i id="star5" class="fas fa-star" onmouseover="star5hover()" onclick="clickstar(this)" value="'.$_SESSION['id'].'" name="'.$_GET['id'].'" rating="5"></i>
	</div>';
	}else{
		$fetch = $result->fetch_object();
		$rating = $fetch->rating;
		echo'<div id="rate-this" onmouseleave="starLeave()">';
		if($rating == 1){
			$string='Star';
		}else{
			$string='Stars';
		}
		echo'<p>You rated this <br>'.$rating.' '.$string.'</p>
		<i id="star1" class="fas fa-star" onmouseover="star1hover()" onclick="updatestar(this)" value="'.$_SESSION['id'].'" name="'.$_GET['id'].'" rating="1"></i>
		<i id="star2" class="fas fa-star" onmouseover="star2hover()" onclick="updatestar(this)" value="'.$_SESSION['id'].'" name="'.$_GET['id'].'" rating="2"></i>
		<i id="star3" class="fas fa-star" onmouseover="star3hover()" onclick="updatestar(this)" value="'.$_SESSION['id'].'" name="'.$_GET['id'].'" rating="3"></i>
		<i id="star4" class="fas fa-star" onmouseover="star4hover()" onclick="updatestar(this)" value="'.$_SESSION['id'].'" name="'.$_GET['id'].'" rating="4"></i>
		<i id="star5" class="fas fa-star" onmouseover="star5hover()" onclick="updatestar(this)" value="'.$_SESSION['id'].'" name="'.$_GET['id'].'" rating="5"></i>
		</div>';
	}
}else{
	echo'<div id="rate-this" onmouseleave="starLeave()" onclick="showLogin()">
		<h1>Rate This</h1>
		<i id="star1" class="fas fa-star" onmouseover="star1hover()"></i>
		<i id="star2" class="fas fa-star" onmouseover="star2hover()"></i>
		<i id="star3" class="fas fa-star" onmouseover="star3hover()"></i>
		<i id="star4" class="fas fa-star" onmouseover="star4hover()"></i>
		<i id="star5" class="fas fa-star" onmouseover="star5hover()"></i>
	</div>';
}
?>
				</div>
				<div class="product-reviews">
					<br><hr>
<?php
if(isset($_SESSION['id'])){
	echo'<form id="review-form">
	<div>
		<textarea id="review-text" required placeholder="What do you think about this product..."></textarea>
	</div>
	<input type="hidden" id="review-product" value="'.$id.'">
	<input type="hidden" id="review-user" value="'.$_SESSION['id'].'">
	<div>
		<center><button onclick="sendReview()">Submit</button>
		</center>
	</div>
	</form>';
}
?>
					<div id="reviews">
<?php
$sql="SELECT reviewid FROM tblreviews WHERE productid='$id'";
$result=$conn->query($sql);

$rows=$result->num_rows;
$page_rows = 10;
$last = ceil($rows/$page_rows);
if($last < 1){
	$last = 1;
}
$pagenum = 1;
if(isset($_GET['pn'])){
	$pagenum = preg_replace('#[^0-9]#', '', $_GET['pn']);
}
if ($pagenum < 1) { 
    $pagenum = 1; 
} else if ($pagenum > $last) { 
    $pagenum = $last; 
}
$limit = 'LIMIT ' .($pagenum - 1) * $page_rows .',' .$page_rows;

$sql = "SELECT t1.userid, reviewid, review, username, imgpath, dateposted, likes
FROM tblreviews AS t1
LEFT JOIN tbluser AS t2
	ON t1.userid = t2.userid
WHERE productid='$id'
ORDER BY likes DESC $limit";
$result = $conn->query($sql);
$textline1 = "<i class='fas fa-comments'></i> Reviews(<b>".number_format($rows)."</b>)";
$textline2 = "Page <b>$pagenum</b> of <b>$last</b>";
$paginationCtrls = '';
if($last != 1){
	if ($pagenum > 1) {
        $previous = $pagenum - 1;
		$paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?id='.$_GET['id'].'&pn='.$previous.'">Previous</a> &nbsp; &nbsp; ';
		// Render clickable number links that should appear on the left of the target page number
		for($i = $pagenum-4; $i < $pagenum; $i++){
			if($i > 0){
		        $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?id='.$_GET['id'].'&pn='.$i.'">'.$i.'</a> &nbsp; ';
			}
	    }
    }
    $paginationCtrls .= ''.$pagenum.' &nbsp; ';
	for($i = $pagenum+1; $i <= $last; $i++){
		$paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?id='.$_GET['id'].'&pn='.$i.'">'.$i.'</a> &nbsp; ';
		if($i >= $pagenum+4){
			break;
		}
	}
	    if ($pagenum != $last) {
        $next = $pagenum + 1;
        $paginationCtrls .= ' &nbsp; &nbsp; <a href="'.$_SERVER['PHP_SELF'].'?id='.$_GET['id'].'&pn='.$next.'">Next</a> ';
    }
}
 echo'<h2>  '.$textline1.'</h2>
  <p>  '.$textline2.' </p>
  <div id="pagination_controls"> '.$paginationCtrls.'</div>';

if($result->num_rows==0){
	echo'<h3>No reviews yet . . .</h3>';
}
while($row = $result->fetch_object()){
	$Rid = $row->reviewid;
	$Ruserid = $row->userid;
	$review = $row->review;
	$Rusername = $row->username;
	$Rimg = $row->imgpath;
	if(!$Rimg){
		$Rimg = 'img/default.png';
	}
	$Rdate = $row->dateposted;
	$likes = $row->likes;

	echo'<div class="review-list">
	<div class="review-header">
	<a href="profile.php?id='.$Ruserid.'">
	<div class="review-tn">
		<img src="'.$Rimg.'">
	</div>
	<span class="white">'.$Rusername.'</span></a>
	<span class="review-date">'.date('M j, Y g:i A',strtotime($Rdate)).'</span>
	</div>
	<p class="find-this-helpful">'.number_format($likes).' people find this review helpful</p>
	<p>'.nl2br(createlink($review)).'</p>';
	if(isset($_SESSION['id'])){
	// Find this helpful
	$Sid = $_SESSION['id'];
	$sql2 = "SELECT likeid FROM tbllikes WHERE userid='$Sid' AND reviewid='$Rid'";
	$result2 = $conn->query($sql2);
	if($result2->num_rows==0){
		echo '<div class="helpful" id="helpful-'.$Rid.'" ><p onclick="likeReview(this)" value="'.$Rid.'">Do you find this review helpful? <i class="far fa-thumbs-up"></i><p></div>';
		}else{
		echo '<div class="helpful" id="helpful-'.$Rid.'" ><p onclick="undoLike(this)" value="'.$Rid.'">You find this review helpful. Undo<p></div>';
		}
	}
	echo'</div>';
	if(isset($_SESSION['id'])){
		if($_SESSION['id']==$Ruserid){
			echo '<p class="button-control" value="'.$Rid.'" onclick="deleteReview(this)">Delete</p>';
		}
	}
}
?>
					</div>
				</div>
			</div>
			<div class="product-main-right">
<?php
	if($approved==0){
	echo'<h3>This product is currently not approved by the admin.</h3>';
	}
	if(isset($_SESSION['id'])){
		// Approve Product

		if($_SESSION['type']==4){

		if($approved == 0){
			echo'<div id="approve-button" onclick="approveProduct(this)" value="'.$id.'" class="add-product-button"><i class="far fa-thumbs-up"></i> Approve</div>';
			}else{
			echo'<div id="remove-product" onclick="removeProduct(this)" value="'.$id.'" class="add-product-button"><i class="fas fa-minus-circle"></i> Remove</div>';
			}
		}

		// Add Product
		if($_SESSION['type']==3 OR $_SESSION['type']==4){
			echo'<a href="addproduct.php" class="white"><div class="add-product-button">
			<i class="fas fa-plus-circle"></i> Add Product
			</div></a>';
		}

// Update Product
		if($_SESSION['id']==$userid){
			//activate/deactivate product
			if($available == 'Not Available'){
			echo'<div id="activate-button" onclick="activateProduct(this)" value="'.$id.'" class="add-product-button"><i class="fas fa-star"></i> Available</div>';
			}else{
			echo'<div id="deactivate-button" onclick="deactivateProduct(this)" value="'.$id.'" class="add-product-button"><i class="far fa-star"></i> Not Available</div>';
			}
echo '
<div onclick="showUpdateProductForm()" class="add-product-button">Update Product</div>
<div class="edit-form" style="display:none;">
			<form method="post" id="add-product-form" enctype="multipart/form-data">
				<h1>Update Product</h1>
				<div>		
					<p>Select Category</p>
					<select name="category" required onchange="getPrice()" id="category">';

	$sql = "SELECT categoryid,category FROM tblcategory WHERE status = 1";
	$result = $conn->query($sql);
	while($row = $result->fetch_object()){
		$idF = $row->categoryid;
		$categoryF = $row->category;

		if($categoryF==$category){
			echo'<option selected value="'.$idF.'">'.$categoryF.'</option>';
		}else{
			echo'<option value="'.$idF.'">'.$categoryF.'</option>';
		}
	}

					echo'</select>
				</div>
				<div>
					<p>Product Name</p>
					<input value="'.$product.'" required type="text" id="name" name="name">
				</div>
				<div>
					<p>Description *Required 30 characters</p>
					<textarea required id="desc" name="desc">'.$desc.'</textarea>
				</div>
				<div>		
					<p>Select Farm</p>
					<select name="farm" required id="farm"><option disabled selected>Select Farm</option>';
	$sql = "SELECT farmid,farmname FROM tblfarm WHERE status = 1";
	$result = $conn->query($sql);
	while($row = $result->fetch_object()){
		$idFF = $row->farmid;
		$farmFF = $row->farmname;
		if($farm==$farmFF){
			echo'<option selected value="'.$idFF.'">'.$farmFF.'</option>';
		}else{
			echo'<option value="'.$idFF.'">'.$farmFF.'</option>';
		}
	}

					echo'</select>
				</div>
				<div>
					<p>Price / kg</p>
					Low: <span id="low">'.$low.'</span><br>
					Prevailing: <span id="prev">'.$prev.'</span> <br>
					High: <span id="high">'.$high.'</span><br>
					<input value="'.$price.'" type="number" required id="price" step="any" name="price">
				</div>
				<div>
					<p>Image</p>';
if($img=='img/default2.jpg'){
	echo'<h2 style="background:red;color:white;margin:5px;">No Image Yet, add image here.</h2>';
}
					echo'<input type="file" name="img">
				</div>
				<div>
					<input type="submit" value="submit" name="submit">
				</div>
				<div id="error-message2">';
// Submit Changes
if(isset($_POST['submit'])){

$category = $_POST['category'];
$name = $conn->real_escape_string($_POST['name']);
$desc = $conn->real_escape_string($_POST['desc']);
$farm = $_POST['farm'];
$price = $_POST['price'];

$sql = "SELECT low,high FROM tblcategory WHERE categoryid= '$category'";
$result= $conn->query($sql);
$fetch = $result->fetch_object();
$low = $fetch->low;
$high = $fetch->high;

	$error = '';

	if($category=='Select Category'){

		$error .= '<i class="fas fa-exclamation-circle"></i> No category selected <br>';
	}

	if($farm=='Select Farm'){
		$error .= '<i class="fas fa-exclamation-circle"></i> No farm selected <br>';
	}

	if(strlen($name) > 40){

		$error .= '<i class="fas fa-exclamation-circle"></i> Product name is too long <br>';
	}

	if(strlen($desc) < 30){

		$error .= '<i class="fas fa-exclamation-circle"></i> Description must be 30 character or longer <br>';
	}

	if($price < $low){
		$error .= '<i class="fas fa-exclamation-circle"></i> Price can\'t be lower than the Lowest Price <br>';
	}

	if($price > $high){
		$error .= '<i class="fas fa-exclamation-circle"></i> Price can\'t be higher than the Highest Price';
	}

	if(!$_FILES['img']['tmp_name']){
		$filepath= $img;
	}else{

		$filetemp=$_FILES['img']['tmp_name'];
		$filename=$_FILES['img']['name'];
		$filetype=$_FILES['img']['type'];
		$filepath="product/".$filename;
		if($filetype != "image/jpg" && $filetype != "image/png" && $filetype != "image/jpeg"
		&& $filetype != "image/gif") {
		     $error .= '<div id="error-message2"><i class="fas fa-exclamation-circle"></i>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</div>';
		}

		if (filesize($filetemp) > 500000) {
		    $error .= '<div id="error-message2"><i class="fas fa-exclamation-circle"></i>Sorry, your file is too large. <strong>Maximum: 500kb.</strong></div>';
		}
	}


	if(!$error){

		if(!$_FILES['img']['tmp_name']){

		}else{
		move_uploaded_file($filetemp, $filepath);
		$filepath=$conn->real_escape_string($filepath);
		}
		$sql = "UPDATE tblproduct SET categoryid='$category', productname='$name', description='$desc', farmid='$farm', price='$price', img='$filepath' WHERE productid = '$id'";
		$result = $conn->query($sql);
		unset($_SESSION['updateProduct']);
		echo "<meta http-equiv='refresh' content='0'>";
	}else{
		echo $error;
		$_SESSION['updateProduct'] = 1;
	}
}

				echo'</div>
			</form>
		</div>';
		}
	}
?>
			</div>
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
		<?php 
		if(isset($rating)){
			echo 'ratedThis('.$rating.');';
		}
		if(isset($_SESSION['updateProduct'])){
			echo 'showUpdateProductForm();';
		}	
		?>
		
		modal();
		ajaxLogin();

	</script>
</body>
</html>