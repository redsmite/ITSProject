<?php
session_start();
include'functions.php';
require_once'connection.php';

//add product
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
		$sql = "INSERT INTO tblproduct (categoryid, productname, description, farmid, userid, dateposted, price, rating,is_available) VALUES ('$category','$name','$desc','$farm','$userid',NOW(),'$price','50',1)";
		$result = $conn->query($sql);

		echo 'success|';

		$_SESSION['updateProduct'] = 1;
		$sql = "SELECT productid FROM tblproduct ORDER BY dateposted DESC LIMIT 1";
		$result = $conn->query($sql);
		$fetch = $result->fetch_object();
		$thisid = $fetch->productid;

		echo $thisid;
	}else{
		echo $error;
	}
}

if(isset($_POST['approveProduct'])){
	$id = $_POST['approveProduct'];

	$sql = "UPDATE tblproduct SET is_approved = 1 WHERE productid ='$id'";
	$result = $conn->query($sql);
}

if(isset($_POST['removeProduct'])){
	$id = $_POST['removeProduct'];

	$sql = "UPDATE tblproduct SET is_approved = 0 WHERE productid ='$id'";
	$result = $conn->query($sql);
}

if(isset($_POST['unsetUpdate'])){
	unset($_SESSION['updateProduct']);
}

if(isset($_POST['activateProduct'])){
	$id = $_POST['activateProduct'];

	$sql = "UPDATE tblproduct SET is_available = 1 WHERE productid ='$id'";
	$result = $conn->query($sql);
}


if(isset($_POST['deactivateProduct'])){
	$id = $_POST['deactivateProduct'];

	$sql = "UPDATE tblproduct SET is_available = 0 WHERE productid ='$id'";
	$result = $conn->query($sql);
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


if(isset($_POST['review'])){
	$user = $_POST['reviewuser'];
	$product = $_POST['reviewproduct'];
	$review = $conn->real_escape_string($_POST['review']);

	$sql = "INSERT INTO tblreviews (review, productid, userid, dateposted) VALUES ('$review','$product','$user',NOW())";
	$result = $conn->query($sql);
}

if(isset($_POST['deleteReview'])){
	$id = $_POST['deleteReview'];

	$sql = "DELETE FROM tblreviews WHERE reviewid = '$id'";
	$result = $conn->query($sql);
}

if(isset($_POST['likeReview'])){
	$id = $_POST['likeReview'];
	$userid = $_SESSION['id'];

	$sql = "INSERT INTO tbllikes (reviewid, userid) VALUES ('$id','$userid')";
	$result = $conn->query($sql);

	$sql = "UPDATE tblreviews SET likes = likes+1 WHERE reviewid ='$id'";
	$result = $conn->query($sql);
}

if(isset($_POST['undoLike'])){
	$id = $_POST['undoLike'];
	$userid = $_SESSION['id'];

	$sql = "DELETE FROM tbllikes WHERE reviewid='$id' AND userid='$userid'";
	$result = $conn->query($sql);

	$sql = "UPDATE tblreviews SET likes = likes-1 WHERE reviewid ='$id'";
	$result = $conn->query($sql);
}

// Cart

if(isset($_POST['cart'])){
	$id = $_POST['cart'];
	echo $id;
	if(!isset($_SESSION['cart'])){
		$_SESSION['cart'] = array();
	}

	if(in_array($id, $_SESSION['cart'])){

	}else{
		array_push($_SESSION['cart'], $id);
	}
}

if(isset($_POST['delete'])){
	unset($_SESSION['cart']);
	unset($_SESSION['trans']);
	unset($_SESSION['total']);
}

if(isset($_POST['remove'])){
	$id = $_POST['remove'];

	if(isset($_SESSION['cart'])){
		$array = $_SESSION['cart'];
		unset($array[$id]);
		$_SESSION['cart'] = $array;
	}
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
	$unitprice = $_POST['weight']*$_POST['price'];
	$total= $_POST['total'];

	//Remove from list
	$array = $_SESSION['cart'];
	unset($array[$key]);
	$_SESSION['cart'] = $array;

	//Add to session
	if(!isset($_SESSION['trans'])){
		$_SESSION['trans'] = array();
	}

	if(!$_SESSION['trans']){

		$new_array = array(
		'productid'=> $id,
		'product'=> $product,
		'price'=> $price,
		'weight'=> $weight,
		'unitprice'=>$unitprice
		);

		array_push($_SESSION['trans'], $new_array);	

	}else{
		foreach ($_SESSION['trans'] as $key => $value) {
			if($_SESSION['trans'][$key]['productid']==$id){

				$_SESSION['trans'][$key]['weight'] += $weight;
				$_SESSION['trans'][$key]['unitprice'] += $unitprice;
				$_SESSION['notexist']=1;
			}
		}
		if(!isset($_SESSION['notexist'])){
			$new_array = array(
			'productid'=> $id,
			'product'=> $product,
			'price'=> $price,
			'weight'=> $weight,
			'unitprice'=>$unitprice
			);

			array_push($_SESSION['trans'], $new_array);
				
		}else{
			unset($_SESSION['notexist']);
		}
	}
	$_SESSION['total'] = $total;
}

if(isset($_POST['undo'])){
	$key = $_POST['undo'];
	$total = $_POST['undoTotal'];

	$_SESSION['total']=$total;
	unset($_SESSION['trans'][$key]);
}

if(isset($_POST['showcart'])){
	if(!isset($_SESSION['cart'])){

	echo'<p>Shopping Cart is empty...</p>';

	}else{
	
	echo'<ul>';
	//Cart - weight defined
	if(isset($_SESSION['trans'])){
		$array = $_SESSION['trans'];
			
		foreach ($array as $key => $value) {
			
			echo'<li id="flist-'.$key.'">
			<div id="undo-'.$key.'" class="remove-button" value="'.$key.'" onclick="undoList(this)">
			<i class="fas fa-trash-alt "></i></div>';
			echo '<a class="black" href="product.php?id='.$array[$key]['productid'].'">'.$array[$key]['product'].'</a><br>';
			echo '₱'.$array[$key]['price'].' / kg x '.$array[$key]['weight'].'kg<br>';
			echo'Unit Price: ₱<span id="flist-unit-price-'.$key.'">'.number_format($array[$key]['unitprice'],2).'</span></li>';
		}
	}
	//Cart - weight not defined
	$array = $_SESSION['cart'];
	foreach ($array as $key => $value) {
		$sql = "SELECT productname,price FROM tblproduct
		WHERE productid = '$value'";
		$result = $conn->query($sql);
		$row = $result->fetch_object();
		$name = $row->productname;
		$price = $row->price;

		echo '<li id="list-'.$key.'">
		<div id="remove-'.$value.'" class="remove-button" value="'.$key.'" onclick="removeList(this)">
			<i class="fas fa-trash-alt "></i>
		</div>
		<a class="black" href="product.php?id='.$value.'">'.$name.'</a><br>
		₱'.number_format($price,2).' / kg x
		<input type="number" min="0" class="kg-input" id="input-'.$value.'" step="any">
		<button class="button-control" onclick="addWeight(this)" value='.$key.' id="'.$value.'">Go</button><br>
		<input type="hidden" id="price-'.$value.'" value="'.$price.'"">
		</li>';
	}
	echo'</ul>
	<h3> Subtotal: ₱<span id="total">';
	if(isset($_SESSION['total'])){
		echo number_format($_SESSION['total'],2);
	}else{
		echo number_format(0,2);
	}
	echo'</span></h3>
	<div class="add-to-cart"'; 
		if(isset($_SESSION['id'])){
			echo'value="1"';
		}else{
			echo'value="0"';
		}
	echo'onclick="checkoutCart(this)"><i class="fas fa-cart-arrow-down"></i></div>
	<div class="red-cart" onclick="deleteCart()"><i class="fas fa-trash-alt"></i></div>
	<div id="error-message5"></div>';
	}
}

if(isset($_POST['checkout'])){
	$_SESSION['checkout']=1;
}
?>