<?php
session_start();
include'functions.php';
user_access();

include'connection.php';
if(isset($_POST['comment-submit'])){
	$comment=$conn->real_escape_string($_POST['comment']);
	$id=$conn->real_escape_string($_POST['hidden']);
	$rid=$conn->real_escape_string($_POST['hidden2']);
	$timestamp='NOW()';
	



	$sql2="INSERT INTO tblcomment (userid,receiver,comment,dateposted) VALUES('$id','$rid','$comment',NOW())";
	$result2=$conn->query($sql2) or die(mysqli_error($conn));
	if($rid==$id){

			header("Location:profile.php?id=".$receiver."#profile-comments");
	}else{
		$sql4='SELECT COALESCE(MAX(commentid), 0) AS newUserID FROM tblcomment';
		$result=$conn->query($sql4);
	
		$row=$result->fetch_object();
		$Cid=$row->newUserID;


		$sql3="INSERT INTO tblnotif (userid,receiverid,notifdate,notiftype,details) values('$id','$rid',$timestamp,'1','$Cid')";
		$result3=$conn->query($sql3);

		header("Location:profile.php?id=".$rid."#profile-comments");
}
}

if(isset($_POST['deletebtn'])){
	$cid=$_POST['hidden3'];
	$receiver=$_POST['hidden4'];
	$sql="DELETE FROM tblcomment WHERE commentid='$cid'";
	$result=$conn->query($sql);
	header("Location:profile.php?id=".$receiver."#profile-comments");
	;
}

if(isset($_POST['submit'])){
	$id= $_POST['hidden'];
	$profile= $_POST['hidden2'];

	$sql="SELECT comment FROM tblcomment WHERE commentid='$id'";
	if($result=$conn->query($sql)){
	$row=$result->fetch_object();
	$comment=$row->comment;
	}else{	
		die('This page doesn\'t exist');
	}

	if($comment==$_POST['comment']){
		header("Location:profile.php?id=".$profile."#profile-comments");
	} else{
	
		$comment=$conn->real_escape_string($_POST['comment']);

		$sql2="UPDATE tblcomment SET comment='$comment',modified=NOW() WHERE commentid='$id'";
		$result2=$conn->query($sql2);
		header("Location:profile.php?id=".$profile."#profile-comments");
	}
}

if(isset($_POST['back'])){
	$id= $_POST['hidden2'];
	header("Location:profile.php?id=".$id."#profile-comments");
}

if(isset($_POST['announce'])){
	$comment = $conn->real_escape_string($_POST['announce']);
	$userid = $_POST['Auserid'];
	$announceid = $_POST['announceid'];

	
	$sql = "INSERT INTO tblcommentann (announceid,comment,userid,dateposted) VALUES('$announceid','$comment','$userid',NOW())";
	$result = $conn->query($sql);

// Redisplay

$sql = "SELECT commentannid,comment,t1.userid,username,imgpath,dateposted FROM tblcommentann AS t1
LEFT JOIN tbluser AS t2
	ON t1.userid = t2.userid
WHERE announceid = '$announceid'
ORDER BY dateposted DESC";
$result = $conn->query($sql);
while($row = $result->fetch_object()){
	$thisid = $row->commentannid;
	$comment = $row->comment;
	$user = $row->username;
	$userid = $row->userid;
	$img = $row->imgpath;
	if(!$img){
		$img="img/default.png";
	}
	$date = $row->dateposted;
	echo'<div class="comment-box">
<div class="comment-header">
<a class="cm-user" href="profile.php?id='.$userid.'">
<div class="comment-tn">
<img src="'.$img.'">
</div>
'.$user.'</a>
<small>'.time_elapsed_string($date).'</small>
</div>
<div class="comment-body">
<div class="com-container"><p class="comment-cm">'.nl2br($comment).'</p></div>
</div>';

if($_SESSION['id']==$userid){
	echo'<p class="button-control" id="'.$announceid.'"value="'.$thisid.'" onclick="deleteComment(this)">Delete</p>';
}

echo'</div>';
	}

}


if(isset($_POST['delete'])){
	$id = $_POST['delete'];
	$id2 = $_POST['id2'];


	$sql = "DELETE FROM tblcommentann WHERE commentannid = '$id'";
	$result= $conn->query($sql);


// Redisplay

$sql = "SELECT commentannid,comment,t1.userid,username,imgpath,dateposted FROM tblcommentann AS t1
LEFT JOIN tbluser AS t2
	ON t1.userid = t2.userid
WHERE announceid = '$id2'
ORDER BY dateposted DESC";
$result = $conn->query($sql);
while($row = $result->fetch_object()){
	$thisid = $row->commentannid;
	$comment = $row->comment;
	$user = $row->username;
	$userid = $row->userid;
	$img = $row->imgpath;
	if(!$img){
		$img="img/default.png";
	}
	$date = $row->dateposted;
	echo'<div class="comment-box">
<div class="comment-header">
<a class="cm-user" href="profile.php?id='.$userid.'">
<div class="comment-tn">
<img src="'.$img.'">
</div>
'.$user.'</a>
<small>'.time_elapsed_string($date).'</small>
</div>
<div class="comment-body">
<div class="com-container"><p class="comment-cm">'.nl2br($comment).'</p></div>
</div>';

if($_SESSION['id']==$userid){
	echo'<p class="button-control" id="'.$id2.'" value="'.$thisid.'" onclick="deleteComment(this)">Delete</p>';
}

echo'</div>';
	}

}

?>