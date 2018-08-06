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
			$fee = $_POST['fee'];
			$address = $_POST['address'];
			$email = $_POST['email'];
			$phone = $_POST['phone'];
			$ordernum = date("Ymdhis").$_SESSION['id'];

			$array = $_SESSION['trans'];

			$sql = "INSERT INTO tblorder (userid, ordernumber, billingaddress, email, phone, fee, total, status, datecommit) VALUES ('$userid','$ordernum','$address','$email','$phone','$fee','$total',0,NOW())";
			$result = $conn->query($sql);

			$sql='SELECT orderid FROM tblorder ORDER BY orderid DESC LIMIT 1';
			$result = $conn->query($sql);
			$fetch = $result->fetch_object();
			$orderid = $fetch->orderid;

			$values = '';
			foreach ($array as $key => $value) {
				$product = $array[$key]['productid'];
				$weight = $array[$key]['weight'];
				$price= $array[$key]['price'];

				$values .= "('$orderid','$product','$weight',$price),";
			}
			$values = rtrim($values,',');

			$sql = "INSERT INTO tblordersummary (orderid,productid,weight,price) VALUES $values";
			$result = $conn->query($sql);

			$sql = "INSERT INTO tblnotif (userid, receiverid, notifdate, notiftype, details) VALUES ('$userid', 1, NOW(), 7,'$ordernum')";
			$result = $conn->query($sql);

			unset($_SESSION['checkout']);
			echo 'success';
		}
	}
}

function orderMonitoring($where,$condition){
	$conn = new mysqli('localhost','root','','itsproject');
	$sql = "SELECT orderid, ordernumber, t1.userid, username, billingaddress, t1.email, t1.phone, fee, total, status, datecommit FROM tblorder AS t1
	LEFT JOIN tbluser AS t2
		ON t1.userid = t2.userid
	$where
	ORDER BY datecommit DESC";
	$result = $conn->query($sql);
	while($row = $result->fetch_object()){
		$orderid = $row->orderid;
		$ordernum = $row->ordernumber;
		$userid = $row->userid;
		$username = $row->username;
		$address = $row->billingaddress;
		$email = $row->email;
		$phone = $row->phone;
		$fee = $row->fee;
		$total = $row->total;
		$status = $row->status;
		if($status==0){
			$Sstatus = '<font style="color:orangered;">Reviewing...</font>';
		}else if($status == 1){
			$Sstatus = '<font style="color:green;">On delivery...</font>';
		}else if($status == 2){
			$Sstatus = '<font style="color:red;">Rejected</font>';
		}else if($status == 3){
			$Sstatus = '<font style="color:red;">Cancelled</font>';
		}else if($status == 4){
			$Sstatus = '<font style="color:green;">Completed</font>';
		}
		$date = $row->datecommit;

		echo '<div class="orders">
		<p>Order No: '.$ordernum.'</p>
		<p>User: <a class="black" href=profile.php?id='.$userid.'>'.$username.'</a></p>
		<p>Status: <b>'.$Sstatus.'</b></p>
		<p>Submitted: '.date('M j, Y g:i A',strtotime($date)).'</p>
		<p>Submitted info:</p> 
		<p class="submitted-info">Billing Address: '.$address.'<br>
		Email: '.$email.'<br>
		Phone: '.$phone.'</p>
		<p>Order Summary</p>
		<div class="order-summary">
		<table>
			<tr>
				<th>Product</th>
				<th>Price</th>
			</tr>';
	// Order Summary
	$sql2 = "SELECT t1.productid, productname, t1.price, weight FROM tblordersummary AS t1
	LEFT JOIN tblproduct AS t2
	ON t1.productid = t2.productid
	WHERE orderid = '$orderid'";
	$result2 = $conn->query($sql2);
	while($row2 = $result2->fetch_object()){
	$productid = $row2->productid;
	$product = $row2->productname;
	$price = $row2->price;
	$weight = $row2->weight;
	$Ptotal = $price*$weight; 

	echo'<tr>
		<th><a class="black" href="product.php?id='.$productid.'">'.$product.'</a> (x '.$weight.'kg)
		</th>';
	echo'<th>₱'.number_format($Ptotal,2).'</th>
		</tr>';
	}

		echo'</table></div>
		<p>Subtotal: <b>₱'.number_format($total-$fee,2).'</b></p>
		<p>Shipping Fee: <b>+₱'.number_format($fee,2).'</b></p>
		<p>Total: <b>₱'.number_format($total,2).'</b></p>';
		if($condition==0){
		echo'<div id="order-approve-'.$orderid.'" class="add-product-button">
			<div onclick="approveOrder(this)" receiver="'.$userid.'" number="'.$ordernum.'" value="'.$orderid.'">
				<i class="far fa-thumbs-up"></i> Approve
			</div>
		</div>
		<div id="order-reject-'.$orderid.'" class="add-product-button">
			<div onclick="rejectOrder(this)" receiver="'.$userid.'" number="'.$ordernum.'" value="'.$orderid.'">
				<i class="far fa-thumbs-down"></i> Reject
			</div>
		</div>';
		}
		if($condition==1){
		echo'<div id="order-complete-'.$orderid.'" class="add-product-button">
			<div onclick="completeOrder(this)" receiver="'.$userid.'" number="'.$ordernum.'" value="'.$orderid.'">
				<i class="fas fa-check-circle"></i> Complete
			</div>
		</div>
		<div id="order-cancel-'.$orderid.'" class="add-product-button">
			<div onclick="cancelOrder(this)" receiver="'.$userid.'" number="'.$ordernum.'" value="'.$orderid.'">
				<i class="fas fa-ban"></i> Cancel
			</div>
		</div>';
		}

		echo'</div>';
	}
}

//Orders in admin panel

if(isset($_POST['showNewOrders'])){
	$string = "WHERE status = 0";
	orderMonitoring($string,false);
}

if(isset($_POST['showApproveOrders'])){
	$string = "WHERE status = 1";
	orderMonitoring($string,true);
}


if(isset($_POST['approve'])){
	$id = $_POST['approve'];
	$number = $_POST['approveNum'];
	$receiver = $_POST['approveRec'];

	$sql = "UPDATE tblorder SET status = 1 WHERE orderid = '$id'";
	$result = $conn->query($sql);

	$sql = "INSERT INTO tblnotif (receiverid, notifdate, notiftype, details) VALUES ('$receiver', NOW(),4,'$number')";
	$result = $conn->query($sql);
}

if(isset($_POST['reject'])){
	$id = $_POST['reject'];
	$number = $_POST['rejectNum'];
	$receiver = $_POST['rejectRec'];

	$sql = "UPDATE tblorder SET status = 2 WHERE orderid = '$id'";
	$result = $conn->query($sql);
	$sql = "INSERT INTO tblnotif (receiverid, notifdate, notiftype, details) VALUES ('$receiver', NOW(), 5,'$number')";
	$result = $conn->query($sql);
}

if(isset($_POST['cancel'])){
	$id = $_POST['cancel'];
	$number = $_POST['cancelNum'];
	$receiver = $_POST['cancelRec'];

	$sql = "UPDATE tblorder SET status = 3 WHERE orderid = '$id'";
	$result = $conn->query($sql);
	$sql = "INSERT INTO tblnotif (receiverid, notifdate, notiftype, details) VALUES ('$receiver', NOW(), 6,'$number')";
	$result = $conn->query($sql);
}

if(isset($_POST['complete'])){
	$id = $_POST['complete'];
	$number = $_POST['completeNum'];
	$receiver = $_POST['completeRec'];

	$sql = "UPDATE tblorder SET status = 4 WHERE orderid = '$id'";
	$result = $conn->query($sql);

	$sql = "SELECT productid, weight, price FROM tblordersummary
	WHERE orderid='$id'";
	$result = $conn->query($sql);
	$values = '';
	while($row = $result->fetch_object()){
		$product = $row->productid;
		$weight = $row->weight;
		$price = $row->price;
		$sales = $weight*$price;

		$values .= "('$product','$weight','$sales',NOW()),";
	}
	$values = rtrim($values,',');
	$sql = "INSERT INTO tblsales (productid, weight, sales, datecommit) VALUES $values";
	$result = $conn->query($sql);
}

?>