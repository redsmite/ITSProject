<?php
session_start();
include'functions.php';
include'connection.php';

if(isset($_POST['search'])){
	$search=$conn->real_escape_string($_POST['search']);

	$data='';
	$sql="SELECT userid,username,imgpath,datecreated FROM tbluser WHERE username LIKE '%$search%' ORDER BY lastonline  DESC LIMIT 10";
	$result=$conn->query($sql);
	while($row=$result->fetch_object()){
		$id=$row->userid;
		$name=$row->username;
		$img=$row->imgpath;
		if(!$img){
			$img='img/default.png';
		}
		$date = date("M j, Y", strtotime($row->datecreated));

		$data.= $id.'%|%'.$name.'%|%'.$img.'%|%'.$date.'%||%';
	}
	echo $data;
}

if(isset($_POST['search2'])){
	$search = $conn->real_escape_string($_POST['search2']);
	echo '<ul class="drop-ul">';
	$sql = "SELECT productid,productname, img, price FROM tblproduct WHERE productname LIKE '%$search%' AND is_approved = 1 AND is_available = 1 ORDER BY view DESC LIMIT 15";
	$result = $conn->query($sql);
	while($row = $result->fetch_object()){
		$id = $row->productid;
		$name = $row->productname;
		$img = $row->img;
		if(!$img){
			$img='img/default2.jpg';
		}
		$price = $row->price;

		echo '<a href="product.php?id='.$id.'"><li>
		<div class="sch-tn">
			<img src="'.$img.'">
		</div>
		'.$name.'<br>
		₱'.number_format($price,2).' / kg
		</li></a>';
	}
	echo '</ul>';
}

if(isset($_POST['chatsearch'])){
	$search = $conn->real_escape_string($_POST['chatsearch']);
	$id = $_SESSION['id'];

	$sql="SELECT userid,username,imgpath,lastonline FROM tblfriend
	LEFT JOIN tbluser
		ON userid=user1 or userid=user2
	WHERE (user1='$id' or user2='$id') AND accepted=2 AND userid!='$id' AND username LIKE '%$search%'
 	ORDER BY lastonline DESC";
 	$result=$conn->query($sql);

	while($row=$result->fetch_object()){
		$id=$row->userid;
 		$name=$row->username;
 		$img=$row->imgpath;
 		if(!$img){
			$img='img/default.png';
		}
 		$online=$row->lastonline;
 		$time=time();

 		echo '<a href="inbox.php?id='.$id.'"><li>
 		<div class="chat-panel-tn">
 			<img src="'.$img.'">
 		</div>';
 		if($time-strtotime($online)< 300){
			echo'<div class="online"></div>';
		} else{
			echo'<div class="offline"></div>';
		}
 		echo $name
 		.'</li></a>';
 	}
			echo'
				</ul>';
}

if(isset($_POST['mainsearch'])){
	$search = $conn->real_escape_string($_POST['mainsearch']);
	$criteria = $_POST['criteria'];

	echo '<ul>';
	if($criteria == 'Select Category'){
		$sql = "SELECT productid,productname, img, price FROM tblproduct WHERE productname LIKE '%$search%' AND  is_approved = 1 AND is_available = 1 ORDER BY view DESC LIMIT 15";
		$result = $conn->query($sql);
		while($row = $result->fetch_object()){
			$id = $row->productid;
			$name = $row->productname;
			$img = $row->img;
			if(!$img){
				$img='img/default2.jpg';
			}
			$price = $row->price;

			echo '<a href="product.php?id='.$id.'"><li>
			<div class="sch-tn">
				<img src="'.$img.'">
			</div>
			'.$name.'<br>
			₱'.number_format($price,2).' / kg
			</li></a>';
		}
	}else{
		$sql = "SELECT productid,productname, img, price FROM tblproduct WHERE productname LIKE '%$search%' AND categoryid='$criteria' AND is_approved = 1 AND is_available = 1 ORDER BY view DESC LIMIT 15";
		$result = $conn->query($sql);
		while($row = $result->fetch_object()){
			$id = $row->productid;
			$name = $row->productname;
			$img = $row->img;
			if(!$img){
				$img='img/default2.jpg';
			}
			$price = $row->price;

			echo '<a href="product.php?id='.$id.'"><li>
			<div class="sch-tn">
				<img src="'.$img.'">
			</div>
			'.$name.'<br>
			₱'.number_format($price,2).' / kg
			</li></a>';
		}
	}
	echo '</ul>';
}

if(isset($_POST['selectFarm'])){
	$farm = $_POST['selectFarm'];
	$search = $_POST['farmSearch'];
	$limit = $_POST['farmCategory'];
	$order = $_POST['farmOrder'];
	if($order==1){
		$order='ORDER BY price ASC';
	}else if($order==2){
		$order='ORDER BY price DESC';
	}else{
		$order='ORDER BY view,dateposted DESC';
	}

	$string = "WHERE productname LIKE '%$search%' $limit AND is_approved = 1 AND is_available = 1 AND t1.farmid = $farm $order";
	showProduct($string);
}

if(isset($_POST['priceOrder'])){
	$order = $_POST['priceOrder'];
	if($order==1){
		$order='ASC';
	}else{
		$order='DESC';
	}
	$search = $_POST['priceSearch'];
	$limit = $_POST['priceCategory'];
	$farm = $_POST['orderFarm'];
	if($farm=='Select Farm'){
		$farm='';
	}else{
		$farm="AND t1.farmid=$farm";
	}

	$string = "WHERE productname LIKE '%$search%' $limit AND is_approved = 1 AND is_available = 1 $farm ORDER BY price $order";
	showProduct($string);
}
?>