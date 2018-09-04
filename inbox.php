<?php
session_start();
include'functions.php';
include'connection.php';
user_access();
updateStatus();
if(isset($_GET['id'])){
	$id=$_GET['id'];
	$sql ="SELECT username FROM tbluser WHERE userid ='$id'";
	$result = $conn->query($sql);
	$fetch = $result->fetch_object();
	$name = $fetch->username;
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
<body onscroll="scrollOpacity()">
	<div class="main-container">
	<!-- Header -->
	<?php
		addheader();
	?>
	<!-- Main Content -->
		<div class="other-content">
<?php
if($id!=$_SESSION['id']){
	//Send PM
	echo'
	<div class="closethis"><a href="inbox.php?id='.$_SESSION['id'].'"><i class="fas fa-times"></i></a></div>
	<div class="inbox-grid">
			<div class="left-inbox">
				<div class="inboxform-div">
					<form action="#" id="chatform" method="post">
						<input placeholder="Send message to '.$name.'"type="text" autocomplete="off" id="sendmsg" name="message" required>
						<button>Enter</button>
						<input type="hidden" id="hidden" name="hidden" value="'.$_GET["id"].'" />
						<input type="hidden" id="hidden2" name="hidden2" value="'.$_SESSION["id"].'" />
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

			echo'
			<h3>Please don\'t ask for discount or you will get banned.</h3>
			<div class="right-inbox">';
$sql="SELECT senderid,username,imgpath,message,pmdate FROM tblpm
LEFT JOIN tbluser
	ON senderid=userid
WHERE (receiverid='$id' and username='$name') or (senderid='$id' and receiverid='$Rid')
";

$result=$conn->query($sql);
while($row=$result->fetch_object()){
	$Sid = $row->senderid;
	$Sname=$row->username;
	$message=$row->message;
	$imgpath=$row->imgpath;
	$date=$row->pmdate;
	if($imgpath==''){
		$imgpath='img/default.png';
	}

	if($Sid==$_SESSION['id']){
	echo '<div class="chat-me">
	<a class="sender" href="profile.php?id='.$Sid.'">
		<div class="comment-tn">
			<img src="'.$imgpath.'">
		</div>'.$Sname.'</a><span class="inbox-date">'.time_elapsed_string_chat($date).'</span><br>
	<div class="chat-div"> 
		<p class="inbxmsg">'.createlink(nl2br($message)).'</p>
	</div>
	</div>';
	}else{
	echo '<div class="chat-notme">
	<a class="sender" href="profile.php?id='.$Sid.'">
		<div class="comment-tn">
			<img src="'.$imgpath.'">
		</div>'.$Sname.'</a><span class="inbox-date">'.time_elapsed_string_chat($date).'</span><br>
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
	$textline1 = "<i class='fas fa-comments'></i>Conversations (<b>".number_format($rows)."</b>)";
	$textline2 = "<font style='color:white'>Page <b>$pagenum</b> of <b>$last</b>";
	$paginationCtrls = '';
	if($last != 1){
		if ($pagenum > 1) {
        $previous = $pagenum - 1;
		$paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?id='.$_GET['id'].'&pn='.$previous.'">Previous</a> &nbsp; &nbsp; ';
		for($i = $pagenum-4; $i < $pagenum; $i++){
			if($i > 0){
		        $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?id='.$_GET['id'].'&pn='.$i.'">'.$i.'</a> &nbsp; ';
				}
	   		}
    	}
	    $paginationCtrls .= ''.$pagenum.' &nbsp; ';
	    for($i = $pagenum+1; $i <= $last; $i++){
			$paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?id='.$_GET['id'].'&pn='.$i.'">'.$i.'</a> &nbsp; ';
			if($i >= $pagenum+4){
				break;
			}
		}
		if ($pagenum != $last) {
	        $next = $pagenum + 1;
	        $paginationCtrls .= ' &nbsp; &nbsp; <a href="'.$_SERVER['PHP_SELF'].'?id='.$_GET['id'].'&pn='.$next.'">Next</a> ';
	    }
	}
	echo'<h2>  '.$textline1.'</h2>
	<p>  '.$textline2.' </p></font>
	<div id="pagination_controls"> '.$paginationCtrls.'</div>';

	$sql="SELECT senderid,username,imgpath,message,pmdate,checked FROM tblpm
	LEFT JOIN tbluser
		ON senderid=userid
	WHERE pmid IN (SELECT max(pmid) FROM tblpm WHERE receiverid='$id' GROUP BY senderid)
	ORDER BY pmid DESC $limit";
	$result=$conn->query($sql);
	$count=$result->num_rows;
	while($row=$result->fetch_object()){
		$Sid=$row->senderid;
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
				<a class="sender" href="profile.php?id='.$Sid.'">'.$Sname.'</a>	
				<span class="new"> <i class="fab fa-gripfire"></i>new</span>';
		}else{
			
			echo'<div class="inbox-box">
				<a class="sender" href="profile.php?id='.$Sid.'">'.$Sname.'</a>';
		}
		echo'<span class="inbox-date">'.time_elapsed_string($date).'</span>
			<div class="comment-tn">
				<img src="'.$imgpath.'">
			</div>
		<div class="inbox-div"> <p class="inbxmsg">'.createlink(nl2br($message)).'</p></div>
		<a class="reply" href="inbox.php?id='.$Sid.'#main-footer">Show Conversation</a>
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
	$update="UPDATE tblpm SET checked=1 WHERE receiverid='$id' AND checked = 0";
	$R_up=$conn->query($update);
	mysqli_close($conn);
?>