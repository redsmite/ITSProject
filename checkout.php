<?php
	session_start();
	if(!isset($_SESSION['checkout'])){
		die('Validate your cart first by clicking the checkout button');
	}
	unset($_SESSION['checkout']);
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
<body onscroll="scrollOpacity()">
	<div class="main-container">
	<!-- Header -->
	<?php
		addheader();
	?>
	<div class="other-content">
		<h1>Your Order</h1>
		<ul>
<?php
	$array = $_SESSION['trans'];
	foreach ($array as $key => $value) {
			
			echo'<li id="flist-'.$key.'">
			<div id="undo-'.$key.'" class="remove-button" value="'.$key.'" onclick="undoList(this)">
			<i class="fas fa-trash-alt "></i></div>';
			echo '<a class="black" href="product.php?id='.$array[$key]['productid'].'">'.$array[$key]['product'].'</a><br>';
			echo '₱'.$array[$key]['price'].' / kg x '.$array[$key]['weight'].'kg<br>';
			echo'Unit Price: ₱<span id="flist-unit-price-'.$key.'">'.number_format($array[$key]['unitprice'],2).'</span></li>';
		}
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