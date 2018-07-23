<?php
	session_start();
	include'functions.php';
	include'connection.php';
	updateStatus();
	addSidebar();
	setupCookie();
	addLogin();
	chattab();

// select admin
	$sql = "SELECT username FROM tbluser WHERE usertypeid = 4";
	$result = $conn->query($sql);
	$fetch = $result->fetch_object();
	$admin = $fetch->username;

// select farm
	$sql = "SELECT farmname FROM tblfarm WHERE status = 1";
	$result = $conn->query($sql);

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
	<!-- Main Content -->
		<div class="other-content">
			<h1><i class="fas fa-info-circle"></i> About</h1>
			<div class="container">
				<div class="content-box">
					<h2><span id="highlight-text">What</span> is BahayKubo ni Mang Celso?</h2>
					<div class="blogpic1">
						<img src="img/logo.jpg">
					</div>
					<p>Home delivery of fruits and vegetables as well as other groceries is a new concept to most.
					Those of us who do our own groceries like the control of picking out our produce and making sure what we pay for is exactly what we want.
					</p>
					<p>
					But if we could choose, wouldn’t we rather spend the extra time?if any?with family and attend to more important matters, than spend the hour on a supermarket run?</p>
					<p>We deliver only the freshest harvest at your doorstep every day straight from the different farms we represent located in 
<?php
// Display farm location
	$data=array();
	while($row= $result->fetch_object()){
		$farm = $row->farmname;

		array_push($data, $farm);
	}
	$arrlength = count($data);
	for($i = 0; $i < $arrlength; $i++) {
		if ($arrlength==1){
			echo '<strong>'.$data[$x].'</strong>.';
		}else{

			if($i != $arrlength-1){
		    	echo '<strong>'.$data[$i].'</strong>, ';
			}else{
				echo 'and <strong>'.$data[$i].'</strong>.';
			}
		}
	}
?>
					</p>
					<div class="blogpic2">
						<img src="img/farm.jpg">
					</div>
					<p>
					We synchronize with the harvest schedules of our farms and ensure that the produce you receive is within hours of its harvest. We know where our products come from and vouch for their integrity. We also screen new partners and continually add to our roster for a more consistent availability.
					</p>
					<p>
					With fresh market delivery at your convenience, you can now cut down trips to the grocery and spend those extra hours with family or that much desired Me time.
					</p>
					<p>Source: greengrocermanila</p>
					<hr>
					<center><h1 id="how-to-order"><span id="highlight-text" class="">How</span> to Order?</h1></center>
					<p>Ordering here is pretty easy, all you need to do is click an item, add to the cart and checkout and choose a payment option for our great services. If these steps are unclear, please refer to the following steps.</p>
					<p>1. Search for the product you want.</p>
					<p>There are plenty of ways to <strong>Search</strong> for the items you want like searching for keywords or browse categories.</p>
					<p>2. Click the add-to-cart button</p>
					<p> Click that green button with a <strong>Shopping Cart Icon</strong>. The item will be added on your shopping cart.</p>
					<p>3. Checkout your order.</p>
					<p> Click for the icon located at the top-right corner of the screen. The shopping cart will pop-out at the right of the screen. Choose the <strong>Weight</strong> of the product in kilograms. Click <strong>Checkout</strong> then 
<?php
	// Show Login Modal
	if(isset($_SESSION['id'])){
		echo '<strong><a class="black cathover">login</a></strong>';
	}else{
		echo'<strong onclick="showLogin()"><a class="black cathover">login</a></strong>';
	}
?>
					, or <strong><a class="black" href="register.php" target="_blank">register</a></strong> if you doesn't have an account yet.</p>
					<p>4. Choose payment options</p>
					<p>You can choose for either <strong>Cash-on-delivery</strong> or <strong>Bank</strong>.</p>
					<p>5. Wait for the delivery</p>
					<p>Wait for at least one day for the delivery. We don't deliver at places <strong>outside of Manila</strong> unfortunately.</p>
					<hr>
					<center><h1>Feedbacks</h1></center>
					<p>Please go <a class="black" href="contact.php" target="_blank">here</a> for feedbacks, or message the admin <a class="black" href="inbox.php?name=<?php echo $admin ?>" target="_blank">here</a> (you need to login / create an account).</p>
					<hr>
					<center><h1>Developers</h1></center>
					<p>Carabeo, Kym</p>
					<p>Cerigo, Kimberly</p>
					<p>Paguio, Kevin</p>
					<p>Prado, Felimer</p>
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
		modal();
		ajaxLogin();
	</script>
</body>
</html>