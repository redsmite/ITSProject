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
			echo'<i class="fas fa-exclamation-circle"></i> Orders should be a minimum of ₱500.00 worth of purchase..';
		}else{
			$total = $_POST['placeOrder'];
			echo 'success';
		}
	}

}

?>