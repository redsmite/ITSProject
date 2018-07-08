<?php
session_start();
include'functions.php';
include'connection.php';
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
<body>
	<div class="main-container">
	<!-- Header -->
	<?php
		addheader();
	?>
	<!-- Main Content -->
	<div class="other-content">
		<h1><i class="fas fa-search"></i>Search</h1>
		<ul class="search-ul">
<?php
//get criteria
if(isset($_GET['criteria'])){
	$crit = $_GET['criteria'];

if($crit==1){

}else if($crit==2){

//if criteria is user

if(isset($_GET['search-text'])){
	$search= $_GET['search-text'];


	$sql="SELECT userid FROM tbluser WHERE username LIKE '%$search%'";

	$result=$conn->query($sql);

	if($result->num_rows==0){
		echo'No results found';
	}
	$rows=$result->num_rows;
	$page_rows = 10;
	$last = ceil($rows/$page_rows);
	if($last < 1){
		$last = 1;
	}
	$pagenum = 1;
	if(isset($_GET['pn'])){
		$pagenum = preg_replace('#[^0-9]#', '', $_GET['pn']);
	}
	if ($pagenum < 1) { 
	    $pagenum = 1; 
	} else if ($pagenum > $last) { 
	    $pagenum = $last; 
	}
	$limit = 'LIMIT ' .($pagenum - 1) * $page_rows .',' .$page_rows;

	$sql="SELECT username,imgpath,datecreated FROM tbluser WHERE username LIKE '%$search%' ORDER BY lastonline DESC $limit";
	
	$textline1 = "Result (<b>$rows</b>)";
	$textline2 = "Page <b>$pagenum</b> of <b>$last</b>";
	$paginationCtrls = '';
	if($last != 1){
		if ($pagenum > 1) {
	        $previous = $pagenum - 1;
			$paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?criteria='.$crit.'&search-text='.$search.'&pn='.$previous.'">Previous</a> &nbsp; &nbsp; ';
			for($i = $pagenum-4; $i < $pagenum; $i++){
				if($i > 0){
			        $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?criteria='.$crit.'&search-text='.$search.'&pn='.$i.'">'.$i.'</a> &nbsp; ';
					}
			    }
		    }
		    $paginationCtrls .= ''.$pagenum.' &nbsp; ';
			for($i = $pagenum+1; $i <= $last; $i++){
				$paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?criteria='.$crit.'&search-text='.$search.'&pn='.$i.'">'.$i.'</a> &nbsp; ';
				if($i >= $pagenum+4){
					break;
				}
			}
			    if ($pagenum != $last) {
		        $next = $pagenum + 1;
		        $paginationCtrls .= ' &nbsp; &nbsp; <a href="'.$_SERVER['PHP_SELF'].'?criteria='.$crit.'&search-text='.$search.'&pn='.$next.'">Next</a> ';
		    }
		}
	 echo'<h2>  '.$textline1.'</h2>
	  <p>  '.$textline2.' </p>
	  <div id="pagination_controls"> '.$paginationCtrls.'</div>';



	$result=$conn->query($sql);

	while($row=$result->fetch_object()){
		$name = $row->username;
		$img = $row->imgpath;
		$date = date("M j, Y", strtotime($row->datecreated));
		if (!$img){
			$img='img/default.png';
		}

		echo'<li><a href="profile.php?name='.$name.'">
		<div class="sch-tn">
		<img src="'.$img.'">
		</div>
		<p>'.$name.'</a></p>
		<p>Joined: '.$date.'</p>
		<li>';

	}
}
}else if($crit==3){

}
mysqli_close($conn);
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
