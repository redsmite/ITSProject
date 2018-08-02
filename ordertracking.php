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
		<h3>Order Tracking</h3>
<?php
	$userid = $_SESSION['id'];
	$sql = "SELECT orderid, ordernumber, billingaddress, email, phone, total, status, datecommit FROM tblorder WHERE userid = '$userid' ORDER BY datecommit DESC";
	$result = $conn->query($sql);
	while($row = $result->fetch_object()){
		$orderid = $row->orderid;
		$ordernum = $row->ordernumber;
		$address = $row->billingaddress;
		$email = $row->email;
		$phone = $row->phone;
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
$sql2 = "SELECT t1.productid,productname, price, weight FROM tblordersummary AS t1
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
		<th><a class="black" href="product.php?id='.$productid.'">'.$product.'</a> (x '.$weight.')
		</th>';
	echo'<th>₱'.number_format($Ptotal,2).'</th>
		</tr>';
}

		echo'</table></div>
		<p>Subtotal: <b>₱'.number_format($total-60,2).'</b></p>
		<p>Shipping Fee: <b>+₱60.00</b></p>
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