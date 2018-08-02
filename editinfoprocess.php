<?php
session_start();
include'functions.php';
user_access();
require_once'connection.php';
if(isset($_POST['edit-button'])){
	$username=$_SESSION['name'];
	$firstname= $conn->real_escape_string($_POST['edit-first']);
	$middlename= $conn->real_escape_string($_POST['edit-middle']);
	$lastname= $conn->real_escape_string($_POST['edit-last']);
	$birthday= $conn->real_escape_string($_POST['edit-birthday']);
	$phone= $conn->real_escape_string($_POST['edit-phone']);
	$location= $conn->real_escape_string($_POST['edit-location']);
	$bio= $conn->real_escape_string($_POST['edit-bio']);
	$privacy=$_POST['privacy'];
	$gender=$_POST['gender'];

	$sql="UPDATE tbluser SET firstname='$firstname', middlename='$middlename',lastname='$lastname',birthday='$birthday',phone='$phone',location='$location',bio='$bio',is_show_email='$privacy',gender='$gender' WHERE username='$username'";
	if($result =$conn->query($sql)){
		header("Location:profile.php?id=".$_SESSION['id']."");
	}
}

?>