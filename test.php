<?php
	session_start();
	require_once'connection.php';
	include'functions.php';
	$time = 1533888000;

	$time = date('Y-m-d H:i:s',$time);
	echo $time.'<br>';

	$sql = "UPDATE tblcutoff SET cutoff ='$time'";
	$result = $conn->query($sql);

	$sql = "SELECT cutoff FROM tblcutoff";
	$result = $conn->query($sql);
	$fetch = $result->fetch_object();
	$cutoff = $fetch->cutoff;

	$cutoff = strtotime($cutoff);
	$now = strtotime('now');
	// if($now>$cutoff){

	// 	$diff = $now - $cutoff;

	// 	$days = ceil($diff / (60*60*24));

	// 	$new_cutoff = $cutoff + (60*60*24*$days);

	// 	$new_cutoff = date('Y-m-d H:i:s',$new_cutoff);


	// 	$sql = "UPDATE tblcutoff SET cutoff = '$new_cutoff'";
	// 	$result = $conn->query($sql);

	// 	echo $new_cutoff;
	// }

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
</body>
</html>
<?php
	mysqli_close($conn);
?>