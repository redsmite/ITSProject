<?php
session_start();
include'functions.php';
addSidebar();
addLogin();
setupCookie();
updateStatus();
chattab();
require_once'connection.php';
$name=$_GET['name'];
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
			<h1><a class="btp" href="profile.php?name=<?php echo $name ?>">Back to <?php echo $name ?>'s Profile</a></h1>
<?php

$sql2="SELECT userid FROM tbluser WHERE username='$name'";
$result2=$conn->query($sql2);
$row=$result2->fetch_object();
$rid=$row->userid;

$sql="SELECT commentid FROM tblcomment WHERE receiver='$rid'";
$result=$conn->query($sql);

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



$sql3="SELECT commentid,tblcomment.userid,username,comment,dateposted,imgpath,modified FROM tblcomment
LEFT JOIN tbluser
	ON tblcomment.userid = tbluser.userid
WHERE receiver='$rid'
ORDER BY commentid DESC $limit";

$textline1 = "<i class='fas fa-comments'></i>Comments (<b>".number_format($rows)."</b>)";
$textline2 = "Page <b>$pagenum</b> of <b>$last</b>";
$paginationCtrls = '';
if($last != 1){
	if ($pagenum > 1) {
        $previous = $pagenum - 1;
		$paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?name='.$_GET['name'].'&pn='.$previous.'">Previous</a> &nbsp; &nbsp; ';
		// Render clickable number links that should appear on the left of the target page number
		for($i = $pagenum-4; $i < $pagenum; $i++){
			if($i > 0){
		        $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?name='.$_GET['name'].'&pn='.$i.'">'.$i.'</a> &nbsp; ';
			}
	    }
    }
    $paginationCtrls .= ''.$pagenum.' &nbsp; ';
	for($i = $pagenum+1; $i <= $last; $i++){
		$paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?name='.$_GET['name'].'&pn='.$i.'">'.$i.'</a> &nbsp; ';
		if($i >= $pagenum+4){
			break;
		}
	}
	    if ($pagenum != $last) {
        $next = $pagenum + 1;
        $paginationCtrls .= ' &nbsp; &nbsp; <a href="'.$_SERVER['PHP_SELF'].'?name='.$_GET['name'].'&pn='.$next.'">Next</a> ';
    }
}
 echo'<h2>  '.$textline1.'</h2>
  <p>  '.$textline2.' </p>
  <div id="pagination_controls"> '.$paginationCtrls.'</div>';

$result3=$conn->query($sql3);
while($rows2=$result3->fetch_object()){
	$Cid=$rows2->commentid;
	$Cuid=$rows2->userid;
	$Cuser=$rows2->username;
	$Ccomment=$rows2->comment;
	$dateposted=$rows2->dateposted;
	$Cimg=$rows2->imgpath;
	$modified=$rows2->modified;
	if($Cimg==''){
		$Cimg='img/default.png';
	}
	if($modified==0){
		$modified='';
	}else{
		$modified='<i>Modified: '.time_elapsed_string($modified).'</i>';
	}

	echo'<div class="comment-box">
	<div class="comment-header">
	<a class="cm-user" href="profile.php?name='.$Cuser.'">
	<div class="comment-tn">
	<img src="'.$Cimg.'">
	</div>
	'.$Cuser.'</a>
	<small>'.time_elapsed_string($dateposted).'</small>
	</div>
	<div class="comment-body">
	<div class="com-container"><p class="comment-cm">'.nl2br($Ccomment).'</p></div>
		<p class="modified">'.$modified.'</p>';
//Delete / Edit Comment
if(!isset($_SESSION['name'])|| !isset($_SESSION['id'])){

}else if($name==$_SESSION['name']||$Cuid==$_SESSION['id']){
echo'
<form align="right" action="commentprocess.php" method="post">
<input type="hidden" name="hidden4" value="'.$_GET["name"].'" />
<input type="hidden" name="hidden3" value="'.$Cid.'">'; 
if($Cuid==$_SESSION['id']){
	echo'<a class="profile-edit" href="editcomment.php?id='.$Cid.'&name='.$name.'&this='.$Cuid.'">edit</a>';
}
echo'	<input type="submit" value="delete" class="comment-delete" name="deletebtn">   

</form>';

}				

		echo'
		</div>
		</div>';
	}
	mysqli_close($conn);
?>
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