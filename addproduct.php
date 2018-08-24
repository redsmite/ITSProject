<?php
	session_start();
	include'functions.php';
	require_once'connection.php';
	addSidebar();
	addLogin();
	setupCookie();
	updateStatus();
	user_access();
	seller_access();
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
		<h1><i class="fas fa-plus-circle"></i> Add Product</h1>
		<div class="edit-form">
			<form method="post" id="add-product-form">
				<h1>Add Product</h1>
				<div>		
					<p>Select Category</p>
					<select name="category" required onchange="getPrice()" id="category">
						<option disabled selected>Select Category</option>
<?php
	$sql = "SELECT categoryid,category FROM tblcategory WHERE status = 1";
	$result = $conn->query($sql);
	while($row = $result->fetch_object()){
		$id = $row->categoryid;
		$category = $row->category;

		echo'<option value="'.$id.'">'.$category.'</option>';
	}
?>
					</select>
				</div>
				<div>
					<p>Product Name</p>
					<input required type="text" id="name" name="name">
				</div>
				<div>
					<p>Description *Required 30 characters</p>
					<textarea required id="desc" name="desc"></textarea>
				</div>
				<div>		
					<p>Select Farm</p>
					<select name="farm" required id="farm"><option disabled selected>Select Farm</option>
<?php
	$sql = "SELECT farmid,farmname FROM tblfarm WHERE status = 1";
	$result = $conn->query($sql);
	while($row = $result->fetch_object()){
		$id = $row->farmid;
		$farm = $row->farmname;

		echo'<option value="'.$id.'">'.$farm.'</option>';
	}
?>
					</select>
				</div>
				<div>
					<p>Price / kg</p>
					Low: <span id="low"></span><br>
					Prevailing: <span id="prev"></span> <br>
					High: <span id="high"></span><br>
					<input type="number" step="any" required id="price" name="price">
				</div>
				<div>
					<button>Submit</button>
				</div>
				<input type="hidden" id="userid" value="<?php $_SESSION['id'] ?>">
				<div id="error-message2"></div>
			</form>
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
		addProductAjax();
	</script>
</body>
</html>