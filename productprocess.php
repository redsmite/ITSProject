<?php
session_start();
include'functions.php';
user_access();
require_once'connection.php';

if(isset($_POST['select'])){
	$id = $_POST['select'];

	$sql = "SELECT low,prevailing,high FROM tblcategory WHERE categoryid = '$id'";
	$result = $conn->query($sql);
	$fetch = $result->fetch_object();
	
	$data = '';

	$low = $fetch->low;
	$prev = $fetch->prevailing;
	$high = $fetch->high;

	$data =  $low.'|'.$prev.'|'.$high;

	echo $data;
}

if(isset($_POST['add'])){
	
	$category = $_POST['add'];
	$userid = $_SESSION['id'];
	$name = $conn->real_escape_string($_POST['name']);
	$desc = $conn->real_escape_string($_POST['desc']);
	$farm = $_POST['farm'];
	$price = $_POST['price'];
	$low = $_POST['Alow'];
	$high = $_POST['Ahigh'];

	$error = '';

	if($category=='Select Category'){

		$error .= '<i class="fas fa-exclamation-circle"></i> No category selected <br>';
	}

	if($farm=='Select Farm'){
		$error .= '<i class="fas fa-exclamation-circle"></i> No farm selected <br>';
	}

	if($price < $low){
		$error .= '<i class="fas fa-exclamation-circle"></i> Price can\'t be lower than the Lowest Price <br>';
	}

	if($price > $high){
		$error .= '<i class="fas fa-exclamation-circle"></i> Price can\'t be higher than the Highest Price';
	}

	if(!$error){
		$sql = "INSERT INTO tblproduct (categoryid, productname, description, farmid, userid, dateposted, price) VALUES ('$category','$name','$desc','$farm','$userid',NOW(),'$price')";
		$result = $conn->query($sql);

		echo 'success';
	}else{
		echo $error;
	}
}
?>