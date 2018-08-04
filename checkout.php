<?php
	session_start();
	if(!isset($_SESSION['checkout'])){
		die('Validate your cart first by clicking the checkout button');
	}
	include'functions.php';
	require_once'connection.php';
	user_access();
	addSidebar();
	addLogin();
	setupCookie();
	updateStatus();
	chattab();
	$userid = $_SESSION['id'];
	$sql = "SELECT phone,location,email FROM tbluser WHERE userid = '$userid'";
	$result = $conn->query($sql);
	$fetch = $result->fetch_object();
	$phone = $fetch->phone;
	$location = $fetch->location;
	$email = $fetch->email;

	$sql = "SELECT title, fee FROM tblfee WHERE feeid = 1";
	$result = $conn->query($sql);
	$fetch = $result->fetch_object();
	$title = $fetch->title;
	$fee = $fetch->fee;
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
	<div class="other-content">
		<div class="checkout-grid">
		<div class="checkout-left">
			<h1>Order Summary</h1>
				<table>
					<tr>
						<th>Product</th>
						<th>Price</th>
					</tr>
<?php
	if(!isset($_SESSION['trans'])){
		echo'<tr><th colspan="2">Shopping Cart is empty.</th></tr></table>';
	}else{
	$array = $_SESSION['trans'];
	foreach ($array as $key => $value) {
			echo'<tr>
				<th>'.$array[$key]['product'].' (x '.$array[$key]['weight'].')
				</th>';
			echo'<th>₱'.number_format($array[$key]['unitprice'],2).'</th>
				</tr>';
		}
		echo'</table>
		<div class="checkout-final">
		<p>Subtotal: ₱';
		if(isset($_SESSION['total'])){
			echo number_format($_SESSION['total'],2);
			$subtotal = $_SESSION['total'];
		}else{
			echo number_format(0,2);
			$subtotal = 0;
		}
		echo '</p>
		<p>+ '.$title.': '.$fee.'</p>
		<hr>';
		$checkoutFinal = $subtotal+$fee;
		echo'<p><b>Total: ₱'.number_format($checkoutFinal,2).'</b></p>
		</div>';
	}
?>
			</div>
			<div class="checkout-right">
				<h1>Checkout Policy</h1>
				<ol>
					<li>1. Minimum of <b>₱500.00</b> worth of purchase.</li>
					<li>2. Delivery is limited only in <b>Metro Manila</b></li>
					<li>3. There will be <strong>no cancellation</strong> when the goods starts to deliver.</li>
				</ol>
			</div>
			<div class="payment-option">
				<h1>Payment</h1>
				<h3>Cash On Delivery</h3>
				<div>
				<form id="place-order-form">
					<p>Enter Billing Address (Manila Only)</p>
					<textarea id="address" cols="30" rows="10" required><?php echo $location?></textarea>
					</div>
					<div>
					<p>Enter Email Address</p>
					<input type="email" id="email" value="<?php echo $email?>" required>
					</div>
					<div>
					<p>Enter Phone Number</p>
					<input type="number" id="phone" value="<?php echo $phone?>" required>
					</div>
					<button class="place-order" 
					id="place-order"
					final="
					<?php
					if(!isset($checkoutFinal)){
						echo 0;
					}else{
						echo $checkoutFinal;
					} 
					?>"

					fee="
					<?php
					if(!isset($fee)){
						echo 0;
					}else{
						echo $fee;
					} 
					?>">
					<i class="fas fa-truck"></i> Place Order
					</button>
					<div id="error-message5"></div>
				</form>
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
		placeOrder();
	</script>
</body>
</html>