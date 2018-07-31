<?php
session_start();
require_once'connection.php';

if(isset($_POST['username'])){
	$error=array();
	$username=$_SESSION['name'];
	$time=$_POST['time'];
	$lastupdate=strtotime($_POST['update']);
	$diff=time()-$lastupdate;
	$month=60*60*24*28;
	if($diff<$month){
		array_push($error,'<i class="fas fa-exclamation-circle"></i>You have recently changed your username');
		echo json_encode($error);
	}else{

		$sql="SELECT username FROM tbluser WHERE username='$username'";
		$result=$conn->query($sql);
		$count= $result->num_rows;
		if ($count == 0) {
			array_push($error,'<i class="fas fa-exclamation-circle"></i>Username is already taken');
		}


		if(strlen($_POST['username']) > 20)
		{
		    array_push($error,'<i class="fas fa-exclamation-circle"></i>Username must not be longer than 20 characters.');
		}

		if($username==$_POST['username']){
			array_push($error,'<i class="fas fa-exclamation-circle"></i> No changes made.');	
		}
		if(!$error){
			$editname=$_POST['username'];
			$id=$_SESSION['id'];
			$sql="UPDATE tbluser SET username='$editname', lastupdate='$time' WHERE userid='$id'";
			if($conn->query($sql)){

			$_SESSION['name']=$editname;

			echo json_encode('success');
			}
		}else{
			echo json_encode($error);
		}
	}
}

if(isset($_POST['email'])){
	$error=array();

	$new=$_POST['email'];
	$username=$_SESSION['name'];

	$sql="SELECT email FROM tbluser WHERE username='$username'";
	$result=$conn->query($sql);
	$row=$result->fetch_object();
	$old=$row->email;

	$sql2="SELECT email FROM tbluser WHERE email='$new'";
	$result2=$conn->query($sql2);
	$rows=$result2->fetch_object();
	if ($result2->num_rows != 0 && $old !=$new) {
		array_push($error,'<i class="fas fa-exclamation-circle"></i>Email is already taken');
	}

	if($old==$new){
		array_push($error,'<i class="fas fa-exclamation-circle"></i> No changes made.');	
	}

	if(!$error){
		$id=$_SESSION['id'];
		$sql="UPDATE tbluser SET email='$new' WHERE userid='$id'";
		if($conn->query($sql)){
			echo json_encode('success');
		}
	}else{
		echo json_encode($error);
	}
}



if(isset($_POST['oldpass'])){
	$error=array();

	$id=$_SESSION['id'];
	$old=md5($_POST['oldpass']);
	$new=md5($_POST['newpass']);
	$new2=md5($_POST['retype']);
	$true=$_POST['truepass'];
	if($true!=$old){
		 array_push($error,'<i class="fas fa-exclamation-circle"></i> Verification failed: Old password doesn\'t match.');
		 echo json_encode($error);
	}else{
		if($new!=$new2){
			array_push($error,'<i class="fas fa-check"></i> Password verification success.');
			array_push($error,'<i class="fas fa-exclamation-circle"></i>Password doesn\'t match.');
		}

		if($old==$new){
			array_push($error,'<i class="fas fa-exclamation-circle"></i> No changes are made.');	
		}

		if(!$error){
			$sql="UPDATE tbluser SET password='$new' WHERE userid='$id'";
			$conn->query($sql) or die($conn->error);

			echo json_encode('success');
		}else{
			 echo json_encode($error);
		}
	}
}
if(isset($_POST['session'])){
	$id=$_SESSION['id'];
	echo json_encode($id);
}

if(isset($_POST['checked'])){
	$id=$_SESSION['id'];
	$sql="UPDATE tblnotif SET checked=1 WHERE receiverid='$id' AND checked = 0";
	$result=$conn->query($sql);

	echo json_encode('oke-oke-okay');
}

?>