<?php
	session_start();
	require_once'connection.php';
	include'functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
  	<meta http-equiv="X-UA-Compatible" content="ie=edge">
  	<link rel="stylesheet" href="css/style.css">
  	<link rel="stylesheet" href="css/fontawesome-all.css">
	<title>Test</title>
</head>
<body>
	<?php
	echo $_SESSION['cart'];
	$percent = 3;

	if($percent>=98){
	echo'
	<div class="star-system">
		<i class="fas fa-star"></i>
		<i class="fas fa-star"></i>
		<i class="fas fa-star"></i>
		<i class="fas fa-star"></i>
		<i class="fas fa-star"></i>
	</div>';
	}else if($percent>=85 & $percent<98){
	echo'
	<div class="star-system">
		<i class="fas fa-star"></i>
		<i class="fas fa-star"></i>
		<i class="fas fa-star"></i>
		<i class="fas fa-star"></i>
		<i class="fas fa-star-half"></i>
	</div>';
	}else if($percent>=75 & $percent<85){
	echo'
	<div class="star-system">
		<i class="fas fa-star"></i>
		<i class="fas fa-star"></i>
		<i class="fas fa-star"></i>
		<i class="fas fa-star"></i>	
		<i class="far fa-star"></i>
	</div>';
	}else if($percent>=65 & $percent<75){
	echo'
	<div class="star-system">
		<i class="fas fa-star"></i>
		<i class="fas fa-star"></i>
		<i class="fas fa-star"></i>
		<i class="fas fa-star-half"></i>
		<i class="far fa-star"></i>
	</div>';
	}else if($percent>=55 & $percent<65){
	echo'
	<div class="star-system">
		<i class="fas fa-star"></i>
		<i class="fas fa-star"></i>
		<i class="fas fa-star"></i>
		<i class="far fa-star"></i>
		<i class="far fa-star"></i>
	</div>';
	}else if($percent>=45 & $percent<55){
	echo'
	<div class="star-system">
		<i class="fas fa-star"></i>
		<i class="fas fa-star"></i>
		<i class="fas fa-star-half"></i>
		<i class="far fa-star"></i>
		<i class="far fa-star"></i>
	</div>';
	}else if($percent>=35 & $percent<45){
	echo'
	<div class="star-system">
		<i class="fas fa-star"></i>
		<i class="fas fa-star"></i>
		<i class="far fa-star"></i>
		<i class="far fa-star"></i>
		<i class="far fa-star"></i>
	</div>';
	}else if($percent>=25 & $percent<35){
	echo'
	<div class="star-system">
		<i class="fas fa-star"></i>
		<i class="fas fa-star-half"></i>
		<i class="far fa-star"></i>
		<i class="far fa-star"></i>
		<i class="far fa-star"></i>
	</div>';
	}else if($percent>=15 & $percent<25){
	echo'
	<div class="star-system">
		<i class="fas fa-star"></i>
		<i class="far fa-star"></i>
		<i class="far fa-star"></i>
		<i class="far fa-star"></i>
		<i class="far fa-star"></i>
	</div>';
	}else if($percent>=4 & $percent<15){
	echo'
	<div class="star-system">
		<i class="fas fa-star-half"></i>
		<i class="far fa-star"></i>
		<i class="far fa-star"></i>
		<i class="far fa-star"></i>
		<i class="far fa-star"></i>
	</div>';
	}else if($percent<4){
	echo'
	<div class="star-system">
		<i class="far fa-star"></i>
		<i class="far fa-star"></i>
		<i class="far fa-star"></i>
		<i class="far fa-star"></i>
		<i class="far fa-star"></i>
	</div>';
	}

	?>
</body>
</html>
<?php
	mysqli_close($conn);
?>