<?php
session_start();
require_once'connection.php';
include'functions.php';
user_access();

//encapsulate sending message
function sendmessage($sender,$name,$message){

	$conn = new mysqli('localhost','root','','itsproject');

	$sql="SELECT userid FROM tbluser WHERE username='$name'";
	$result=$conn->query($sql);
	$row=$result->fetch_object();
	$Rid=$row->userid;

	$receiver=$conn->real_escape_string($Rid);
	$message=$conn->real_escape_string($message);
	$timestamp='NOW()';
	
	$sql="INSERT INTO tblpm (senderid,receiverid,message,pmdate) VALUES('$sender','$receiver','$message',$timestamp)";
	$result=$conn->query($sql);



	//Reset Inbox

$Rquery="SELECT userid FROM tbluser WHERE username='$name'";
$result=$conn->query($Rquery);
$row=$result->fetch_object();
$Rid=$row->userid;

$sql="SELECT username,imgpath,message,pmdate FROM tblpm
LEFT JOIN tbluser
	ON senderid=userid
WHERE (receiverid='$sender' and username='$name') or (senderid='$sender' and receiverid='$Rid')
";

$data='';
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
	$data.= '<div class="chat-me">
	<a class="sender" href="profile.php?name='.$Sname.'">
		<div class="comment-tn">
			<img src="'.$imgpath.'">
		</div>'.$Sname.'</a><span class="inbox-date">'.time_elapsed_string($date).'</span><br>
	<div class="chat-div"> 
		<p class="inbxmsg">'.createlink(nl2br($message)).'</p>
	</div>
	</div>';
	}else{
	$data.= '<div class="chat-notme">
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
		$data.='</div>
	</div>';


	echo $data;
}

//User sends message

if(isset($_POST['message'])){

	$sender=$_SESSION['id'];
	$name=$_POST['name'];
	$message=$_POST['message'];

	sendmessage($sender,$name,$message);	
}

//TuturuBot Replies

if(isset($_POST['hellobot'])){

	$name=$_SESSION['name'];
	$message=$_POST['hellobot'];

	sendmessage(71,$name,$message);
	
}

if(isset($_POST['song'])){
	$name=$_SESSION['name'];
	$message='(♪ Jazz music playing...)';

	sendmessage(71,$name,$message);
}

if(isset($_POST['time'])){
	$name=$_SESSION['name'];
	$message='The time is ' . date("h:i:s A") . ' '. $name. '-san';

	sendmessage(71,$name,$message);
}

if(isset($_POST['thanks'])){
	$name=$_SESSION['name'];
	$message='You\'re welcome '. $name. '-san!';

	sendmessage(71,$name,$message);
}

if(isset($_POST['chat'])){
	$name=$_SESSION['name'];
	$message=$_POST['chat'];

	sendmessage(71,$name,$message);
}

if(isset($_POST['bye'])){
	$name=$_SESSION['name'];
	$message='Goodbye '. $name. '-san see you later!';

	sendmessage(71,$name,$message);
}


// send to all users a message through bot

if(isset($_POST['sendall'])){
	$message=$conn->real_escape_string($_POST['sendall']);
	$timestamp = 'NOW()';

	$sql = "SELECT userid FROM tbluser";
	$result=$conn->query($sql);
	$count = $result->num_rows;

	for ($i = 0; $i <= $count; $i++) {
		$send="INSERT INTO tblpm (senderid,receiverid,message,pmdate) VALUES('71','$i','$message',$timestamp)";
		$result=$conn->query($send);
	}
	echo 'oke-oke-okay';
}


//reset div every time

if(isset($_POST['load'])){

$id=$_SESSION['id'];
$name=$_POST['name'];

$Rquery="SELECT userid FROM tbluser WHERE username='$name'";
$result=$conn->query($Rquery);
$row=$result->fetch_object();
$Rid=$row->userid;

$sql="SELECT username,imgpath,message,pmdate FROM tblpm
LEFT JOIN tbluser
	ON senderid=userid
WHERE (receiverid='$id' and username='$name') or (senderid='$id' and receiverid='$Rid')
";

$data='';
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
	$data.= '<div class="chat-me">
	<a class="sender" href="profile.php?name='.$Sname.'">
		<div class="comment-tn">
			<img src="'.$imgpath.'">
		</div>'.$Sname.'</a><span class="inbox-date">'.time_elapsed_string($date).'</span><br>
	<div class="chat-div"> 
		<p class="inbxmsg">'.createlink(nl2br($message)).'</p>
	</div>
	</div>';
	}else{
	$data.= '<div class="chat-notme">
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
		$data.='</div>
	</div>';


	echo $data;
}



?>