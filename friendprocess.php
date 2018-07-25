<?php
session_start();
include'functions.php';
user_access();

include'connection.php';

if(isset($_POST['fr'])){
	$user1=$_SESSION['id'];
	$user2=$_POST['fr'];

	$sql="SELECT user1,user2 FROM tblfriend WHERE (user1=$user1 and user2=$user2)or(user1=$user2 and user2=$user1)";
	$result=$conn->query($sql);
	if($result->num_rows==0){

	$sql="INSERT INTO tblfriend(user1,user2,accepted) VALUES ('$user1','$user2',1) ";
	$result=$conn->query($sql);

	$sql4='SELECT COALESCE(MAX(friendid), 0) AS newID FROM tblfriend';
	$result=$conn->query($sql4);

	$row=$result->fetch_object();
	$newID=$row->newID;

	$timestamp='NOW()';

	$sql2="INSERT INTO tblnotif(userid,receiverid,notifdate,notiftype,details,details2) VALUES($user1,$user2,$timestamp,'2','$newID','1')";
	$result2=$conn->query($sql2);

	echo json_encode('oke-oke-okay');
	}else{
		echo json_encode('oke-oke-okay');
	}
}

if(isset($_POST['fryes'])){
	$nid=$_POST['fryes'];

	$sql="SELECT userid,receiverid,details FROM tblnotif WHERE notifid='$nid'";
	$result=$conn->query($sql);
	$rows=$result->fetch_object();
	$user1=$rows->userid;
	$user2=$rows->receiverid;
	$fid=$rows->details;

	$sql="UPDATE tblfriend SET accepted=2,friendsince=NOW() WHERE friendid='$fid'";
	$result=$conn->query($sql);

	$sql="UPDATE tblnotif SET details2=2 WHERE notifid='$nid'";
	$result=$conn->query($sql);

	echo json_encode('oke-oke-okay');
}

if(isset($_POST['frno'])){
	$nid=$_POST['frno'];

	$sql="SELECT userid,receiverid,details FROM tblnotif WHERE notifid='$nid'";
	$result=$conn->query($sql);
	$rows=$result->fetch_object();
	$user1=$rows->userid;
	$user2=$rows->receiverid;
	$fid=$rows->details;

	$sql="DELETE from tblfriend WHERE friendid='$fid'";
	$result=$conn->query($sql);

	$sql="UPDATE tblnotif SET details2=3 WHERE notifid='$nid'";
	$result=$conn->query($sql);

	echo json_encode('oke-oke-okay');
}

if(isset($_POST['rmv'])){
	$fid=$_POST['rmv'];

	$sql="DELETE from tblfriend WHERE friendid='$fid'";
	$result=$conn->query($sql);

	echo json_encode('oke-oke-okay');
}
?>