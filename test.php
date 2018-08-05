<?php
	session_start();
	require_once'connection.php';
	include'functions.php';
function getStartAndEndDate($week, $year) {
  $dto = new DateTime();
  $ret['week_start'] = $dto->setISODate($year, $week)->format('Y-m-d');
  $ret['week_end'] = $dto->modify('+6 days')->format('Y-m-d');
  return $ret;
}


echo getStartAndEndDate(31,2018)['week_end'];
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