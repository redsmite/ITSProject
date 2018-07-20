<?php
session_start();
include'functions.php';
include'connection.php';
user_access();
updateStatus();
if(isset($_GET['name'])){
	$name=$_GET['name'];	
}else{
	die('This page doesn\'t exist.');
}
addSidebar();
setupCookie();
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
<?php
if($name!=$_SESSION['name']){
	//Send PM
	echo'
	<audio id="myAudio">
		<source src="audio/tuturumayushiidesuring.mp3" type="audio/mpeg">
	</audio>
	<audio id="mySong">
		<source src="audio/lounge.mp3" type="audio/mpeg">
	</audio>
	<div class="closethis"><a href="inbox.php?name='.$_SESSION['name'].'"><i class="fas fa-times"></i></a></div>
	<div class="inbox-grid">
			<div class="left-inbox">
				<div class="inboxform-div">
					<form action="#" id="chatform" method="post">
						<div>
						<input type="hidden" id="hidden" name="hidden" value="'.$_GET["name"].'" />
						<input type="hidden" id="hidden2" name="hidden2" value="'.$_SESSION["id"].'" />
							<input placeholder="Send message to '.$_GET["name"].'"type="text" autocomplete="off" id="sendmsg" name="message" required>Enter
						</div>
					</form>
				</div>
			</div>';

//Show Conversation
$id=$_SESSION['id'];

$Rquery="SELECT userid,username,imgpath FROM tbluser WHERE username='$name'";
$result=$conn->query($Rquery);
$row=$result->fetch_object();
$Rid=$row->userid;
$Ruser=$row->username;
$Rimage=$row->imgpath;
if($Rimage==''){
	$Rimage='img/default.png';
}

			echo'<div class="right-inbox"
	background-attachment: fixed;">';

$sql="SELECT username,imgpath,message,pmdate FROM tblpm
LEFT JOIN tbluser
	ON senderid=userid
WHERE (receiverid='$id' and username='$name') or (senderid='$id' and receiverid='$Rid')
";

$result=$conn->query($sql);
while($row=$result->fetch_object()){
	$Sname=$row->username;
	$message=$row->message;
	$imgpath=$row->imgpath;
	$date=$row->pmdate;
	if($imgpath==''){
		$imgpath='img/default.png';
	}

	if($Sname==$_SESSION['name']){
	echo '<div class="chat-me">
	<a class="sender" href="profile.php?name='.$Sname.'">
		<div class="comment-tn">
			<img src="'.$imgpath.'">
		</div>'.$Sname.'</a><span class="inbox-date">'.time_elapsed_string($date).'</span><br>
	<div class="chat-div"> 
		<p class="inbxmsg">'.createlink(nl2br($message)).'</p>
	</div>
	</div>';
	}else{
	echo '<div class="chat-notme">
	<a class="sender" href="profile.php?name='.$Sname.'">
		<div class="comment-tn">
			<img src="'.$imgpath.'">
		</div>'.$Sname.'</a><span class="inbox-date">'.time_elapsed_string($date).'</span><br>
	<div class="chat-div"> 
		<p class="inbxmsg">'.createlink(nl2br($message)).'</p>
	</div>
	</div>';
	}

}
		echo'</div>
	</div>';

	//Javascript
	echo'<script src="js/main.js"></script>
	<script>
		ajaxinbox();
		loadInboxInterval();
		document.getElementById("sendmsg").focus();
		var messageBody = document.querySelector(".right-inbox");
		messageBody.scrollTop = messageBody.scrollHeight - messageBody.clientHeight;
	</script>';
} else{
	$id=$_SESSION['id'];
	$sql="SELECT pmid FROM tblpm WHERE receiverid='$id' 
	GROUP BY senderid";
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
	$textline1 = "<i class='fas fa-comments'></i>Conversations (<b>$rows</b>)";
	$textline2 = "Page <b>$pagenum</b> of <b>$last</b>";
	$paginationCtrls = '';
	if($last != 1){
		if ($pagenum > 1) {
        $previous = $pagenum - 1;
		$paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?name='.$_GET['name'].'&pn='.$previous.'">Previous</a> &nbsp; &nbsp; ';
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

	$sql="SELECT username,imgpath,message,pmdate,checked FROM tblpm
	LEFT JOIN tbluser
		ON senderid=userid
	WHERE pmid IN (SELECT max(pmid) FROM tblpm WHERE receiverid='$id' GROUP BY senderid)
	ORDER BY pmid DESC $limit";
	$result=$conn->query($sql);
	$count=$result->num_rows;
	while($row=$result->fetch_object()){
		$Sname=$row->username;
		$message=$row->message;
		$imgpath=$row->imgpath;
		$date=$row->pmdate;
		$checked=$row->checked;
		if($imgpath==''){
			$imgpath='img/default.png';
		}

		if($checked==0){
			echo '<div class="inbox-new">
				<a class="sender" href="profile.php?name='.$Sname.'">'.$Sname.'</a>	
				<span class="new"> <i class="fab fa-gripfire"></i>new</span>';
		}else{
			
			echo'<div class="inbox-box">
				<a class="sender" href="profile.php?name='.$Sname.'">'.$Sname.'</a>';
		}
		echo'<span class="inbox-date">'.time_elapsed_string($date).'</span>
			<div class="comment-tn">
				<img src="'.$imgpath.'">
			</div>
		<div class="inbox-div"> <p class="inbxmsg">'.createlink(nl2br($message)).'</p></div>
		<a class="reply" href="inbox.php?name='.$Sname.'#main-footer">Show Conversation</a>
		</div>

		<script src="js/main.js"></script>';
	}
}
		?>
	</div>
	<!-- Footer -->
		<?php
			addfooter();
		?>
	<!-- End of Container -->
	</div>
</body>
</html>
<?php
	$id=$_SESSION['id'];
	$update="UPDATE tblpm SET checked=1 WHERE receiverid='$id'";
	$R_up=$conn->query($update);
	mysqli_close($conn);
?>
