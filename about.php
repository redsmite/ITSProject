<?php
	session_start();
	include'functions.php';
	updateStatus();
	addSidebar();
	setupCookie();
	addLogin();
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
	<!-- Main Content -->
		<div class="other-content">
			<h1><i class="fas fa-info-circle"></i> About</h1>
			<div class="container">
				<div class="content-box">
					<h2><span id="highlight-text">What</span> is BahayKubo?</h2>
					<p>Home delivery of fruits and vegetables as well as other groceries is a new concept to most.
					Those of us who do our own groceries like the control of picking out our produce and making sure what we pay for is exactly what we want.
					</p>
					<p>
					But if we could choose, wouldn’t we rather spend the extra time?if any?with family and attend to more important matters, than spend the hour on a supermarket run?</p>
					<p>We deliver only the freshest harvest at your doorstep every Wednesday and Saturday, straight from the different farms we represent located in [Not Yet Defined].
					</p>
					<p>
					We synchronize with the harvest schedules of our farms and ensure that the produce you receive is within hours of its harvest. We know where our products come from and vouch for their integrity. We also screen new partners and continually add to our roster for a more consistent availability.
					</p>
					<p>
					With fresh market delivery at your convenience, you can now cut down trips to the grocery and spend those extra hours with family or that much desired Me time.
					</p>
					<p>Source: greengrocermanila</p>
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