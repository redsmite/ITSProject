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
		<h1><i class="fas fa-search"></i>Search</h1>
<?php
//get criteria
if(isset($_GET['criteria'])){
	$crit = $_GET['criteria'];

if($crit==1){

// if criteria is product

if(isset($_GET['search-text'])){
	$search= $conn->real_escape_string($_GET['search-text']);
	echo'<div class="my-products">';
	$sql = "SELECT productid,category, productname, description, farmname, username, dateposted, price, img, rating FROM tblproduct as t1
	LEFT JOIN tblcategory as t2
		ON t1.categoryid = t2.categoryid
	LEFT JOIN tbluser as t3
		ON t1.userid = t3.userid
	LEFT JOIN tblfarm as t4
		ON t1.farmid = t4.farmid
	WHERE productname LIKE '%$search%'  AND is_approved = 1 AND is_available = 1
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
	echo'</div>';
}
}else if($crit==2){

//if criteria is user

if(isset($_GET['search-text'])){
	echo'<ul class="search-ul">';
	$search= $conn->real_escape_string($_GET['search-text']);


	$sql="SELECT userid FROM tbluser WHERE username LIKE '%$search%'";

	$result=$conn->query($sql);

	if($result->num_rows==0){
		echo'No results found';
	}
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

	$sql="SELECT userid,username,imgpath,datecreated FROM tbluser WHERE username LIKE '%$search%' ORDER BY lastonline DESC $limit";
	
	$textline1 = "Result (<b>".number_format($rows)."</b>)";
	$textline2 = "Page <b>$pagenum</b> of <b>$last</b>";
	$paginationCtrls = '';
	if($last != 1){
		if ($pagenum > 1) {
	        $previous = $pagenum - 1;
			$paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?criteria='.$crit.'&search-text='.$search.'&pn='.$previous.'">Previous</a> &nbsp; &nbsp; ';
			for($i = $pagenum-4; $i < $pagenum; $i++){
				if($i > 0){
			        $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?criteria='.$crit.'&search-text='.$search.'&pn='.$i.'">'.$i.'</a> &nbsp; ';
					}
			    }
		    }
		    $paginationCtrls .= ''.$pagenum.' &nbsp; ';
			for($i = $pagenum+1; $i <= $last; $i++){
				$paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?criteria='.$crit.'&search-text='.$search.'&pn='.$i.'">'.$i.'</a> &nbsp; ';
				if($i >= $pagenum+4){
					break;
				}
			}
			    if ($pagenum != $last) {
		        $next = $pagenum + 1;
		        $paginationCtrls .= ' &nbsp; &nbsp; <a href="'.$_SERVER['PHP_SELF'].'?criteria='.$crit.'&search-text='.$search.'&pn='.$next.'">Next</a> ';
		    }
		}
	 echo'<h2>  '.$textline1.'</h2>
	  <p>  '.$textline2.' </p>
	  <div id="pagination_controls"> '.$paginationCtrls.'</div>';



	$result=$conn->query($sql);

	while($row=$result->fetch_object()){
		$id = $row->userid;
		$name = $row->username;
		$img = $row->imgpath;
		$date = date("M j, Y", strtotime($row->datecreated));
		if (!$img){
			$img='img/default.png';
		}

		echo'<li><a href="profile.php?id='.$id.'">
		<div class="sch-tn">
		<img src="'.$img.'">
		</div>
		<p>'.$name.'</a></p>
		<p>Joined: '.$date.'</p>
		<li>';

	}
}
}else if($crit==3){
	if(!isset($_SESSION['id'])){
		die('You\'re not login');
	}
	// Order Tracking
	if(isset($_GET['search-text'])){
	$userid = $_SESSION['id'];
	$search = $_GET['search-text'];

	$sql = "SELECT orderid, ordernumber, t1.userid, username, billingaddress, t1.email, t1.phone, fee, total, status, datecommit FROM tblorder AS t1
	LEFT JOIN tbluser AS t2
		ON t1.userid = t2.userid
	WHERE ordernumber='$search'";
	$result = $conn->query($sql);
	while($row = $result->fetch_object()){
		
		$userid = $row->userid;

		// Check if the user own the order / if user is admin

		if($userid == $_SESSION['id'] or $_SESSION['type']==4){
		
			$orderid = $row->orderid;
			$ordernum = $row->ordernumber;
			$username = $row->username;
			$address = $row->billingaddress;
			$email = $row->email;
			$phone = $row->phone;
			$fee = $row->fee;
			$total = $row->total;
			$status = $row->status;
			if($status==0){
				$status = '<font style="color:orangered;">Reviewing...</font>';
			}else if($status == 1){
				$status = '<font style="color:green;">On delivery...</font>';
			}else if($status == 2){
				$status = '<font style="color:red;">Rejected</font>';
			}else if($status == 3){
				$status = '<font style="color:red;">Cancelled</font>';
			}else if($status == 4){
				$status = '<font style="color:green;">Completed</font>';
			}
			$date = $row->datecommit;

			echo '<div class="orders">
			<p>Order No: '.$ordernum.'</p>
			<p>User: <a class="black" href=profile.php?id='.$userid.'>'.$username.'</a></p>
			<p>Status: <b>'.$status.'</b></p>
			<p>Submitted: '.date('M j, Y g:i A',strtotime($date)).'</p>
			<p>Submitted info:</p> 
			<p class="submitted-info">Billing Address: '.$address.'<br>
			Email: '.$email.'<br>
			Phone: '.$phone.'</p>
			<p>Order Summary</p>
			<div class="order-summary">
			<table>
				<tr>
					<th>Product</th>
					<th>Price</th>
				</tr>';
	// Order Summary
	$sql2 = "SELECT t1.productid,productname, t1.price, weight FROM tblordersummary AS t1
	LEFT JOIN tblproduct AS t2
		ON t1.productid = t2.productid
	WHERE orderid = '$orderid'";
	$result2 = $conn->query($sql2);
	while($row2 = $result2->fetch_object()){
		$productid = $row2->productid;
		$product = $row2->productname;
		$price = $row2->price;
		$weight = $row2->weight;
		$Ptotal = $price*$weight; 
		
		echo'<tr>
			<th><a class="black" href="product.php?id='.$productid.'">'.$product.'</a> (x '.$weight.'kg)
			</th>';
		echo'<th>₱'.number_format($Ptotal,2).'</th>
			</tr>';
	}

			echo'</table></div>
			<p>Subtotal: <b>₱'.number_format($total-$fee,2).'</b></p>
			<p>Shipping Fee: <b>+₱'.number_format($fee,2).'</b></p>
			<p>Total: <b>₱'.number_format($total,2).'</b></p>
			</div>';
		}
	}	
	}
}
}
mysqli_close($conn);
?>
		</ul>
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
