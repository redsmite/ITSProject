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
	$userid = $_SESSION['id'];
	$sql="SELECT orderid FROM tblorder WHERE userid = '$userid'";
	$result=$conn->query($sql);

	$rows=$result->num_rows;
	$page_rows = 1;
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

	$sql = "SELECT orderid, ordernumber, billingaddress, email, phone, fee, total, status, datecommit FROM tblorder WHERE userid = '$userid' ORDER BY datecommit DESC $limit";

	$textline1 = "Order Tracking (<b>".number_format($rows)."</b>)";
$textline2 = "Page <b>$pagenum</b> of <b>$last</b>";
$paginationCtrls = '';
if($last != 1){
	if ($pagenum > 1) {
        $previous = $pagenum - 1;
		$paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?pn='.$previous.'">Previous</a> &nbsp; &nbsp; ';
		// Render clickable number links that should appear on the left of the target page number
		for($i = $pagenum-4; $i < $pagenum; $i++){
			if($i > 0){
		        $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?pn='.$i.'">'.$i.'</a> &nbsp; ';
			}
	    }
    }
    $paginationCtrls .= ''.$pagenum.' &nbsp; ';
	for($i = $pagenum+1; $i <= $last; $i++){
		$paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?pn='.$i.'">'.$i.'</a> &nbsp; ';
		if($i >= $pagenum+4){
			break;
		}
	}
	    if ($pagenum != $last) {
        $next = $pagenum + 1;
        $paginationCtrls .= ' &nbsp; &nbsp; <a href="'.$_SERVER['PHP_SELF'].'?pn='.$next.'">Next</a> ';
    }
}
 echo'<h2>  '.$textline1.'</h2>
  <p>  '.$textline2.' </p>
  <div id="pagination_controls"> '.$paginationCtrls.'</div>';


	$result = $conn->query($sql);
	while($row = $result->fetch_object()){
		$orderid = $row->orderid;
		$ordernum = $row->ordernumber;
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
$sql2 = "SELECT t1.productid, productname, t1.price, weight FROM tblordersummary AS t1
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
		modal();
		ajaxLogin();
	</script>
</body>
</html>