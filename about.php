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
	$sql = "SELECT userid FROM tbluser WHERE usertypeid = 4";
	$result = $conn->query($sql);
	$fetch = $result->fetch_object();
	$admin = $fetch->userid;

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
<body onscroll="scrollOpacity()">
	<div class="main-container">
	<!-- Header -->
	<?php
		addheader();
	?>
	<!-- Main Content -->
	<div class="about-page">
		<div class="about-header">
			<h1>BAHAY KUBO NI MANG CELSO</h1>
			<p>About</p>
		</div>
		<section class="about-section-grid">
			<div class="company-desc">
				<h1>Bahay Kubo ni Mang Celso</h1>
				<p>The prospective company is called Bahay Kubo ni Mang Celso, owned by Miss Lynn Gutierrez. It is located at Babu Pangulo, Porac, Pampanga. The company specializes in retail for agricultural products. The company is run by Miss Gutierrez, who performs all tasks within the company, from administration to advertising, to day-to-day operations.  The company offers some special and interesting services. Farmer for a Day Experience, Pick and Pay, and the popular Farm-to-Table Restaurant.</p>
			</div>
			<div class="mission">
				<h2>Mission</h2>
				<p>Mission is to spark an interest to our youth regarding the rewards of farming. That it will not just enrich your soul, it will enrich your bank accounts too. Let’s destroy the cruel image of a poor farmer. Instead let’s replace that with a farmer that can stand along our country’s finest. </p>
			</div>
			<div class="vision">
				<h2>Vision</h2>
				<p>Vision is to contribute to a heathier Philippines. Not just for its people, for its economy as well. Let us bring back our country’s golden age. Wherein our produce is the envy of many. Turning farming in to a career not just a devotion. </p>
			</div>
		</section>
		<section class="farm-section">
			<h2>Farm Location and Produce</h2>
<?php
$sql = "SELECT farmid, farmname, address FROM tblfarm WHERE status=1";
$result = $conn->query($sql);
while($row = $result->fetch_object()){
$farmid = $row->farmid;
$farmname = $row->farmname;
$desc = $row->address;
echo '<div class="farm-div"><h1>'.$farmname.'</h1><p>'.$desc.'</p>
	<a href="searchfarm.php?id='.$farmid.'"><div class="see-more">See More</div></a>
</div>';
}
?>
		</section>
		<section class="parallax-img">
			<h1>How to Order?</h1>
		</section>
		<section class="how-to-order">
			<h1 class="how-to">How to Order</h1>
			<p>Ordering here is pretty easy, all you need to do is click an item, add to the cart and checkout and enter your billing information like email, phone and address. If these steps are unclear, please refer to the following steps.</p>
			<p class="how-to"><i class="fas fa-search"></i> Search for the product you want.</p>
			<p>There are plenty of ways to <strong>Search</strong> for the items you want like searching for keywords, browse categories, browse farms and you can sort by price.</p>
			<p class="how-to"><i class="fas fa-shopping-cart"></i> Click the add-to-cart button</p>
			<p> Click that green button with a <strong>Shopping Cart Icon</strong>. The item will be added on your shopping cart.</p>
			<p class="how-to"><i class="fas fa-cart-arrow-down"></i> Checkout your order.</p>
			<p> Click for the icon located at the top-right corner of the screen. The shopping cart will pop-out at the right of the screen. Choose the <strong>Unit</strong> for the product in kilograms. Click the green <strong>Shopping Cart Button</strong> then 
<?php
// Show Login Modal
if(isset($_SESSION['id'])){
echo '<strong><a class="black cathover">login</a></strong>';
}else{
echo'<strong onclick="showLogin()"><a class="black cathover">login</a></strong>';
}
?>
			, or <strong><a class="black" href="register.php" target="_blank">register</a></strong> if you doesn't have an account yet.</p>
			<p class="how-to"><i class="fas fa-clipboard"></i> Enter your payment info</p>
			<p><strong>Cash-on-delivery</strong> - enter your billing address, email.</p>
			<p class="how-to"><i class="far fa-clock"></i> Wait for the delivery</p>
			<p>Wait for at least one to four days for the delivery. We don't deliver at places <strong>outside of Manila</strong>. You <strong>cannot cancel</strong> your order after every next 4pm.</p>
		</section>
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