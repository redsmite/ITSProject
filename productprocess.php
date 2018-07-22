<?php
session_start();
include'functions.php';
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

	if(strlen($name) > 50){

		$error .= '<i class="fas fa-exclamation-circle"></i> Product name is too long <br>';
	}

	if(strlen($desc) < 30){

		$error .= '<i class="fas fa-exclamation-circle"></i> Description must be 30 character or longer <br>';
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

if(isset($_POST['cart'])){
	$id = $_POST['cart'];

	$_SESSION['cart'] .= $id.'|';

}

if(isset($_POST['showcart'])){
	if(!isset($_SESSION['cart'])){

	echo'<p>Shopping Cart is empty...</p>';

	}else{
	
	echo'<ul>';
	$array = explode('|',$_SESSION['cart']);
	array_pop($array);
	$total=0;
	foreach ($array as $key => $value) {
		$sql = "SELECT productname,price FROM tblproduct
		WHERE productid = '$value'";
		$result = $conn->query($sql);
		$row = $result->fetch_object();
		$name = $row->productname;
		$price = $row->price;

		$total = $total + $price;
		echo '<li>'.$name.' ₱'.$price.'</li>';
	}
	echo'</ul>
	<h3> Total: ₱' .$total .'</h3>';
	
	}
}
?>