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

		echo 'success|';

		$sql = "SELECT productid FROM tblproduct ORDER BY dateposted DESC LIMIT 1";
		$result = $conn->query($sql);
		$fetch = $result->fetch_object();
		$thisid = $fetch->productid;

		echo $thisid;
	}else{
		echo $error;
	}
}

// Review
if(isset($_POST['star'])){
	$userid=$_POST['star'];
	$productid=$_POST['rateproduct'];
	$star=$_POST['rating'];

	$sql = "INSERT INTO tblrating (userid,productid,rating) VALUES ('$userid','$productid','$star')";
	$result = $conn->query($sql);

	$sql = "SELECT AVG(rating) AS average FROM tblrating WHERE productid='$productid'";
	$result = $conn->query($sql);
	$fetch = $result->fetch_object();

	$finalrating = $fetch->average;

	$finalrating = $finalrating*20;

	$sql = "UPDATE tblproduct SET rating='$finalrating' WHERE productid='$productid'";
	$result = $conn->query($sql);
}

if(isset($_POST['updatestar'])){
	$userid=$_POST['updatestar'];
	$productid=$_POST['updaterateproduct'];
	$star=$_POST['updaterating'];

	$sql = "UPDATE tblrating SET rating='$star' WHERE userid='$userid' AND productid = '$productid'";
	$result = $conn->query($sql);
	
	$sql = "SELECT AVG(rating) AS average FROM tblrating WHERE productid='$productid'";
	$result = $conn->query($sql);
	$fetch = $result->fetch_object();

	$finalrating = $fetch->average;

	$finalrating = $finalrating*20;

	$sql = "UPDATE tblproduct SET rating='$finalrating' WHERE productid='$productid'";
	$result = $conn->query($sql);
}

// Cart

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
	unset($_SESSION['trans']);
	unset($_SESSION['total']);
}

if(isset($_POST['remove'])){
	$id = $_POST['remove'];

	$array = explode('|',$_SESSION['cart']);
	unset($array[$id]);
	$array = implode("|",$array);
	$_SESSION['cart'] = $array;
}

if(isset($_POST['weight'])){
	$id = $_POST['listid'];
	$key = $_POST['key'];

	$sql = "SELECT productname FROM tblproduct WHERE productid = '$id'";
	$result = $conn->query($sql);
	$fetch = $result->fetch_object();
	$product = $fetch->productname;

	$weight= $_POST['weight'];
	$price=$_POST['price'];
	$total= $_POST['total'];

//Remove from list
	$array = explode('|',$_SESSION['cart']);
	array_pop($array);
	unset($array[$key]);
	$array = implode("|",$array);
	$_SESSION['cart'] = $array;

//Add to session
	$_SESSION['trans'] .= $product.'|';
	$_SESSION['trans'] .= $price.'|';
	$_SESSION['trans'] .= $weight.'||';

	$_SESSION['total'] = $total;
}

if(isset($_POST['undo'])){
	$key = $_POST['undo'];
	$unitprice = $_POST['undoprice'];
	$_SESSION['total'] = $_SESSION['total'] - $unitprice;
	echo $_SESSION['total'];
	$array = explode('||',$_SESSION['trans']);

	unset($array[$key]);
	$array = implode("||",$array);
	$_SESSION['trans'] = $array;	
}

if(isset($_POST['showcart'])){
	if(!isset($_SESSION['cart'])){

	echo'<p>Shopping Cart is empty...</p>';

	}else{
	
	echo'
	<div class="refresh-button" onclick="showCartPanel()">Refresh <i class="fas fa-sync-alt"></i></div>
	<hr>
	<ul>';
	//Cart - weight defined
	if(isset($_SESSION['trans'])){
		$array = explode('||',$_SESSION['trans']);
		
		array_pop($array);
			
		foreach ($array as $key => $value) {
			
			echo'<li>';
			$list = explode('|',$array[$key]);
			$total = $list[1]*$list[2];
			echo '<b>'.$list[0].'</b><br>
			₱'.$list[1].'/kg x '.$list[2].'kg<br>
			Unit Price: ₱'.number_format($total,2);
			echo'</li>';
		}
	}
	//Cart - weight not defined
	$array = explode('|',$_SESSION['cart']);
	array_pop($array);
	foreach ($array as $key => $value) {
		$sql = "SELECT productname,price FROM tblproduct
		WHERE productid = '$value'";
		$result = $conn->query($sql);
		$row = $result->fetch_object();
		$name = $row->productname;
		$price = $row->price;

		echo '<li id="list-'.$key.'">
		<div class="remove-button" value="'.$key.'" onclick="removeList(this)">
			<i class="fas fa-trash-alt "></i>
		</div>
		<b>'.$name.'</b><br>
		₱'.number_format($price,2).' / kg x
		<input type="number" min="0" class="kg-input" id="input-'.$value.'" step="any">
		<button class="button-control" onclick="addWeight(this)" value='.$key.' id="'.$value.'">Go</button><br>
		<input type="hidden" id="price-'.$value.'" value="'.$price.'"">
		Unit Price: ₱<span id="unit-price'.$value.'"></span>
		</li>';
	}
	echo'</ul>
	<h3> Total: ₱<span id="total">';
	if(isset($_SESSION['total'])){
		echo number_format($_SESSION['total'],2);
	}else{
		echo number_format(0,2);
	}
	echo'</span></h3>
	<div class="add-to-cart"><i class="fas fa-cart-arrow-down"></i></div>
	<div class="red-cart" onclick="deleteCart()"><i class="fas fa-trash-alt"></i></div>';
	}
}
?>