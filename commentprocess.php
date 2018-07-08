<?php
session_start();
include'functions.php';
user_access();

include'connection.php';
if(isset($_POST['comment-submit'])){
	$comment=$conn->real_escape_string($_POST['comment']);
	$id=$conn->real_escape_string($_POST['hidden']);
	$receiver=$conn->real_escape_string($_POST['hidden2']);


	$sql="SELECT userid FROM tbluser WHERE username='$receiver'";
	$result=$conn->query($sql);
	$row=$result->fetch_object();
	$rid=$row->userid;
	$timestamp='NOW()';
	



	$sql2="INSERT INTO tblcomment (userid,receiver,comment,dateposted) VALUES('$id','$rid','$comment',NOW())";
	$result2=$conn->query($sql2) or die(mysqli_error($conn));
	if($rid==$id){

			header("Location:profile.php?name=".$receiver."#profile-comments");
	}else{
		$sql4='SELECT COALESCE(MAX(commentid), 0) AS newUserID FROM tblcomment';
		$result=$conn->query($sql4);
	
		$row=$result->fetch_object();
		$Cid=$row->newUserID;


		$sql3="INSERT INTO tblnotif (userid,receiverid,notifdate,notiftype,details) values('$id','$rid',$timestamp,'1','$Cid')";
		$result3=$conn->query($sql3);

		header("Location:profile.php?name=".$receiver."#profile-comments");
}
}

if(isset($_POST['deletebtn'])){
	$cid=$_POST['hidden3'];
	$receiver=$conn->real_escape_string($_POST['hidden4']);
	$sql="DELETE FROM tblcomment WHERE commentid='$cid'";
	$result=$conn->query($sql);
	header("Location:profile.php?name=".$receiver."#profile-comments");
	;
}

if(isset($_POST['submit'])){
	$id= $_POST['hidden'];
	$name= $_POST['hidden2'];

	$sql="SELECT comment FROM tblcomment WHERE commentid='$id'";
	if($result=$conn->query($sql)){
	$row=$result->fetch_object();
	$comment=$row->comment;
	}else{	
		die('This page doesn\'t exist');
	}

	if($comment==$_POST['comment']){
		header("Location:profile.php?name=".$name."#profile-comments");
	} else{
	
		$comment=$conn->real_escape_string($_POST['comment']);

		$sql2="UPDATE tblcomment SET comment='$comment',modified=NOW() WHERE commentid='$id'";
		$result2=$conn->query($sql2);
		header("Location:profile.php?name=".$name."#profile-comments");
	}
}

if(isset($_POST['back'])){
	$name= $_POST['hidden2'];
	header("Location:profile.php?name=".$name."#profile-comments");
}

?>