<?php
session_start();
include'functions.php';
require_once'connection.php';
user_access();

if(isset($_POST['placeOrder'])){
$sql = "SELECT minimumorder FROM tblminimumorder";
$result = $conn->query($sql);
$fetch = $result->fetch_object();
$minorder = $fetch->minimumorder;
					
	if(!isset($_SESSION['total'])){
		echo'<i class="fas fa-exclamation-circle"></i> Your shopping cart is empty.';
	}else{
		if($_SESSION['total']==0){
			echo'<i class="fas fa-exclamation-circle"></i> Your shopping cart is empty.';
		}else if($_SESSION['total']<$minorder){
			echo'<i class="fas fa-exclamation-circle"></i> Orders should be a minimum of ₱'.$minorder.' worth of purchase.';
		}else{
			$sql = "SELECT cutoff FROM tblcutoff";
			$result = $conn->query($sql);
			$fetch = $result->fetch_object();
			$cutoff = $fetch->cutoff;

			$total = $_POST['placeOrder'];
			$userid = $_SESSION['id'];
			$fee = $_POST['fee'];
			$address = $_POST['address'];
			$email = $_POST['email'];
			$phone = $_POST['phone'];
			$ordernum = date("Ymdhis").$_SESSION['id'];

			$array = $_SESSION['trans'];

			$sql = "INSERT INTO tblorder (userid, ordernumber, billingaddress, email, phone, fee, total, status, datecommit,cutoff) VALUES ('$userid','$ordernum','$address','$email','$phone','$fee','$total',0,NOW(),'$cutoff')";
			$result = $conn->query($sql);

			$sql='SELECT orderid FROM tblorder ORDER BY orderid DESC LIMIT 1';
			$result = $conn->query($sql);
			$fetch = $result->fetch_object();
			$orderid = $fetch->orderid;

			$values = '';

			$seller_array = array();
			array_push($seller_array, 1);
			foreach ($array as $key => $value) {
				$product = $array[$key]['productid'];
				$weight = $array[$key]['weight'];
				$price= $array[$key]['price'];

				$values .= "('$orderid','$product','$weight',$price),";

				$seller = $array[$key]['seller'];
				if(in_array($seller, $seller_array)){

				}else{
					array_push($seller_array, $seller);
				}
			}
			$values = rtrim($values,',');

			$sql = "INSERT INTO tblordersummary (orderid,productid,weight,price) VALUES $values";
			$result = $conn->query($sql);

			//Send Notification to admin and Sellers
			$values = '';

			foreach ($seller_array as $key => $value) {
				$values .= "('$userid', $value, NOW(), 7,'$ordernum'),";
			}
			$values = rtrim($values,',');
			$sql = "INSERT INTO tblnotif (userid, receiverid, notifdate, notiftype, details) VALUES $values";
			$result = $conn->query($sql);

			unset($_SESSION['checkout']);
			echo 'success';
		}
	}
}

function orderMonitoring($where,$condition){
	$conn = new mysqli('localhost','root','','itsproject');
	$sql = "SELECT orderid, ordernumber, t1.userid, username, billingaddress, t1.email, t1.phone, fee, total, status, datecommit,cutoff FROM tblorder AS t1
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
			$Sstatus = '<font style="color:orangered;">Pending...</font>';
		}else if($status == 1){
			$Sstatus = '<font style="color:green;">Approved</font>';
		}else if($status == 2){
			$Sstatus = '<font style="color:red;">Rejected</font>';
		}else if($status == 3){
			$Sstatus = '<font style="color:red;">Cancelled</font>';
		}else if($status == 4){
			$Sstatus = '<font style="color:green;">Completed</font>';
		}
		$date = $row->datecommit;
		$cutoff = $row->cutoff;
		$now = strtotime('now');
		$cutofftime = strtotime($cutoff);

		echo '<div class="orders">';
		if($now < $cutofftime){
			echo '<p>Cut off time: <font style="color:green;">Active</font></p>';
		}else{
			echo '<p>Cut off time: <font style="color:red;">Expired</font></p>';
		}
		echo'<p>Order No: '.$ordernum.'</p>
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
	echo'<th><span class="left">₱</span><span class="right">'.number_format($Ptotal,2).'</span></th>
		</tr>';
	}

		echo'</table></div>
		<div class="checkout-final">
		<p>Subtotal: <b>₱'.number_format($total-$fee,2).'</b></p>
		<p>Shipping Fee: <b>+₱'.number_format($fee,2).'</b></p>
		<p>Total: <b>₱'.number_format($total,2).'</b></p>
		</div>';
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

if(isset($_POST['userCancel'])){
	$id = $_POST['userCancel'];
	
	$sql = "SELECT datecommit,cutoff FROM tblorder WHERE orderid = '$id'";
	$result = $conn->query($sql);
	$fetch = $result->fetch_object();
	
	$date = $fetch->datecommit;
	$cutoff = $fetch->cutoff;
	$datetime = strtotime($date);
	$cutofftime = strtotime($cutoff);

	if($datetime < $cutofftime){
		$sql = "UPDATE tblorder SET status = 3 WHERE orderid = '$id'";
		$result = $conn->query($sql);
		echo 'success';
	}else{
		echo 'This order has reached its cut off time. Cancel request denied';
	}
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
	// Shipping revenues
	$sql = "SELECT fee,datecommit FROM tblorder WHERE orderid=$id";
	$result = $conn->query($sql);
	$row = $result->fetch_object();
	$fee = $row->fee;
	$date = $row->datecommit;
	$sql = "INSERT INTO tblshippingrevenue (srevenue,datecommit) VALUES ('$fee','$date')";
	$result = $conn->query($sql);
}

// Cut off

if(isset($_POST['setCutoff'])){
	$sql = "SELECT cutoff FROM tblcutoff";
	$result = $conn->query($sql);
	$fetch = $result->fetch_object();
	$cutoff = $fetch->cutoff;

	$cutoff = strtotime($cutoff);
	$now = strtotime('now');
	if($now>$cutoff){

		$diff = $now - $cutoff;

		$days = ceil($diff / (60*60*24));

		$new_cutoff = $cutoff + (60*60*24*$days);

		$new_cutoff = date('Y-m-d H:i:s',$new_cutoff);


		$sql = "UPDATE tblcutoff SET cutoff = '$new_cutoff'";
		$result = $conn->query($sql);

		echo $new_cutoff;
	}
}

if(isset($_POST['selectStatus'])){
	$status = $_POST['selectStatus'];
	$userid = $_SESSION['id'];
	$sql = "SELECT orderid, ordernumber, billingaddress, email, phone, fee, total, status, datecommit, cutoff FROM tblorder WHERE userid = '$userid' AND status = $status ORDER BY datecommit DESC";
	$result = $conn->query($sql);
	while($row = $result->fetch_object()){
		$orderid = $row->orderid;
		$ordernum = $row->ordernumber;
		$address = $row->billingaddress;
		$email = $row->email;
		$phone = $row->phone;
		$fee = $row->fee;
		$total = $row->total;
		$status = $row->status; 
		if($status==0){
			$status = '<font style="color:orangered;">Pending...</font>';
		}else if($status == 1){
			$status = '<font style="color:green;">Approved</font>';
		}else if($status == 2){
			$status = '<font style="color:red;">Rejected</font>';
		}else if($status == 3){
			$status = '<font style="color:red;">Cancelled</font>';
		}else if($status == 4){
			$status = '<font style="color:green;">Completed</font>';
		}
		$date = $row->datecommit;
		$orderCutoff = $row->cutoff;

		echo '<div class="orders">';

		//Cancel Order
		$datetime = strtotime($date);
		$ordercutofftime = strtotime($orderCutoff);
		$warningtime = $ordercutofftime - (60*60);
		$now = strtotime('now');
		if($warningtime < $now AND $ordercutofftime > $now){
			echo '<h3>The cut off time is about to expire. This your last chance to cancel this order.</h3>';
		}
		if($datetime < $ordercutofftime AND $status == '<font style="color:orangered;">Pending...</font>' AND $now < $ordercutofftime){
			echo'<div id="cancelOrder" onclick="cancelThisOrder()" value="'.$orderid.'"><h3><i class="far fa-clock"></i><br> Cancel</h3></div>';
		}

		echo'<p>Order No: '.$ordernum.'</p>
		<p>Status: <b>'.$status.'</b></p>
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
	echo'<th><span class="left">₱</span><span class="right">'.number_format($Ptotal,2).'</span></th>
		</tr>';
}

		echo'</table></div>
		<div class="checkout-final">
		<p>Subtotal: <b>₱'.number_format($total-$fee,2).'</b></p>
		<p>Shipping Fee: <b>+₱'.number_format($fee,2).'</b></p>
		<p>Total: <b>₱'.number_format($total,2).'</b></p>
		</div></div>';
	}
}

?>