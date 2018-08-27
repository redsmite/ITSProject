<?php
session_start();
include'functions.php';
addSidebar();
addLogin();
setupCookie();
updateStatus();
chattab();
require_once'connection.php';
if(!isset($_GET['id'])){
	die('This page doesn\'t exists.');
}else{
	$id=$_GET['id'];

	$sql="SELECT username FROM tbluser WHERE userid='$id'";
	$result=$conn->query($sql);
	$fetch=$result->fetch_object();
	$name=$fetch->username;
}
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
		<div class="other-content">
			<h1><a class="btp" href="profile.php?id=<?php echo $id ?>">Back to <?php echo $name ?>'s Profile</a></h1>
			<div class="wrap-center">
<?php
// Show friends

$sql="SELECT friendid FROM tblfriend
LEFT JOIN tbluser
	ON userid=user1 or userid=user2
 WHERE (user1='$id' or user2='$id') AND accepted=2 AND userid!='$id'";

$result=$conn->query($sql);
$rows=$result->num_rows;
$page_rows = 8;
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
	$textline1 = $name."'s Friends (<b>".number_format($rows)."</b>)";
	$textline2 = "<font style='color:white'>Page <b>$pagenum</b> of <b>$last</b>";
	$paginationCtrls = '';
	if($last != 1){
		if ($pagenum > 1) {
        $previous = $pagenum - 1;
		$paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?id='.$id.'&pn='.$previous.'">Previous</a> &nbsp; &nbsp; ';
		for($i = $pagenum-4; $i < $pagenum; $i++){
			if($i > 0){
		        $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?id='.$id.'&pn='.$i.'">'.$i.'</a> &nbsp; ';
				}
	   		}
    	}
	    $paginationCtrls .= ''.$pagenum.' &nbsp; ';
	    for($i = $pagenum+1; $i <= $last; $i++){
			$paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?id='.$id.'&pn='.$i.'">'.$i.'</a> &nbsp; ';
			if($i >= $pagenum+4){
				break;
			}
		}
		if ($pagenum != $last) {
	        $next = $pagenum + 1;
	        $paginationCtrls .= ' &nbsp; &nbsp; <a href="'.$_SERVER['PHP_SELF'].'?id='.$id.'&pn='.$next.'">Next</a> ';
	    }
	}
	echo'<h2>  '.$textline1.'</h2>
	<p>  '.$textline2.' </p></font>
	<div id="pagination_controls"> '.$paginationCtrls.'</div>';

$sql="SELECT userid,friendsince,username,imgpath,lastonline FROM tblfriend
LEFT JOIN tbluser
	ON userid=user1 or userid=user2
 WHERE (user1='$id' or user2='$id') AND accepted=2 AND userid!='$id'
 ORDER BY lastonline DESC $limit";

$result=$conn->query($sql);
while($rows=$result->fetch_object()){
$since=date("M j, Y", strtotime($rows->friendsince));
$online=$rows->lastonline;
$time=time();
$userid=$rows->userid;
$username=$rows->username;
$imgpath=$rows->imgpath;
if(!$imgpath){
	$imgpath="img/default.png";
}
	echo'<div class="fr-div">
	<div class="showfr-tn">
			<a href="profile.php?id='.$userid.'"><img src="'.$imgpath.'"></a></div>
		<p><a href="profile.php?id='.$userid.'" class="white">'.$username.'</a></p>
		<p>Friend Since: '.$since.'</p>';
		if($time-strtotime($online)< 300){
			echo'<h5><font color="#98fb98">Online</font></h5>';
		} else{
			echo'<p>Last Online: '.time_elapsed_string($online).'</p>';
		}

		echo'</div>';

}

	mysqli_close($conn);
?>
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