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
		$sql = "INSERT INTO tblproduct (categoryid, productname, description, farmid, userid, dateposted, price, rating) VALUES ('$category','$name','$desc','$farm','$userid',NOW(),'$price','50')";
		$result = $conn->query($sql);

		echo 'success';
	}else{
		echo $error;
	}
}

if(isset($_POST['cart'])){
	$id = $_POST['cart'];
	$array = explode('|',$_SESSION['cart']);
	array_pop($array);
	if(in_array($id, $array)){

	}else{
		$_SESSION['cart'] .= $id.'|';
	}
}

if(isset($_POST['delete'])){
	unset($_SESSION['cart']);
}

if(isset($_POST['showcart'])){
	if(!isset($_SESSION['cart'])){

	echo'<p>Shopping Cart is empty...</p>';

	}else{
	
	echo'<ul>';
	$array = explode('|',$_SESSION['cart']);
	array_pop($array);
	foreach ($array as $key => $value) {
		$sql = "SELECT productname,price FROM tblproduct
		WHERE productid = '$value'";
		$result = $conn->query($sql);
		$row = $result->fetch_object();
		$name = $row->productname;
		$price = $row->price;

		echo '<li>'.$name.'<br>
		₱'.number_format($price,2).' / kg x
		<input type="number" onkeyup="addWeight(this)" step="any" id="'.$value.'">
		<input type="hidden" id="price-'.$value.'" value="'.$price.'"">
		Unit Price: ₱<span id="unit-price'.$value.'"></span>
		</li>';
	}
	echo'</ul>
	<h3> Total: ₱<span id="total">0</span></h3>
	<div class="add-to-cart" onclick="showCartPanel()"><i class="fas fa-sync-alt"></i></div>
	<div class="red-cart" onclick="deleteCart()"><i class="fas fa-trash-alt"></i></div>';
	}
}
?>