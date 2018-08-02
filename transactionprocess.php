<?php
session_start();
include'functions.php';
require_once'connection.php';
user_access();

if(isset($_POST['placeOrder'])){
	if(!isset($_SESSION['total'])){
		echo'<i class="fas fa-exclamation-circle"></i> Your shopping cart is empty.';
	}else{
		if($_SESSION['total']==0){
			echo'<i class="fas fa-exclamation-circle"></i> Your shopping cart is empty.';
		}else if($_SESSION['total']<500){
			echo'<i class="fas fa-exclamation-circle"></i> Orders should be a minimum of ₱500.00 worth of purchase.';
		}else{
			$total = $_POST['placeOrder'];
			$userid = $_SESSION['id'];
			$address = $_POST['address'];
			$email = $_POST['email'];
			$phone = $_POST['phone'];
			$ordernum = date("Ymdhis");

			$array = $_SESSION['trans'];

			$sql = "INSERT INTO tblorder (userid, ordernumber, billingaddress, email, phone, total, status, datecommit) VALUES ('$userid','$ordernum','$address','$email','$phone','$total',0,NOW())";
			$result = $conn->query($sql);

			$sql='SELECT orderid FROM tblorder ORDER BY orderid DESC LIMIT 1';
			$result = $conn->query($sql);
			$fetch = $result->fetch_object();
			$orderid = $fetch->orderid;

			$values = '';
			foreach ($array as $key => $value) {
				$product = $array[$key]['productid'];
				$weight = $array[$key]['weight'];

				$values .= "('$orderid','$product','$weight'),";
			}
			$values = rtrim($values,',');

			$sql = "INSERT INTO tblordersummary (orderid,productid,weight) VALUES $values";
			$result = $conn->query($sql);

			unset($_SESSION['checkout']);
			echo 'success';
		}
	}
}

if(isset($_POST['approve'])){
	$id = $_POST['approve'];

	$sql = "UPDATE tblorder SET status = 1 WHERE orderid = '$id'";
	$result = $conn->query($sql);
}

if(isset($_POST['reject'])){
	$id = $_POST['reject'];

	$sql = "UPDATE tblorder SET status = 2 WHERE orderid = '$id'";
	$result = $conn->query($sql);
}

?>