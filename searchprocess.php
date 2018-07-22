<?php
session_start();
include'functions.php';
include'connection.php';

if(isset($_POST['search'])){
	$search=$_POST['search'];

	$data='';
	$sql="SELECT username,imgpath,datecreated FROM tbluser WHERE username LIKE '%$search%' ORDER BY lastonline  DESC LIMIT 10";
	$result=$conn->query($sql);
	while($row=$result->fetch_object()){

		$name=$row->username;
		$img=$row->imgpath;
		if(!$img){
			$img='img/default.png';
		}
		$date = date("M j, Y", strtotime($row->datecreated));

		$data.= $name.'|'.$img.'|'.$date.'||';
	}
	echo $data;
}

if(isset($_POST['chatsearch'])){
	$search = $_POST['chatsearch'];
	$id = $_SESSION['id'];

	$sql="SELECT username,imgpath,lastonline FROM tblfriend
	LEFT JOIN tbluser
		ON userid=user1 or userid=user2
	WHERE (user1='$id' or user2='$id') AND accepted=2 AND userid!='$id' AND username LIKE '%$search%'
 	ORDER BY lastonline DESC";
 	$result=$conn->query($sql);

	while($row=$result->fetch_object()){
 		$name=$row->username;
 		$img=$row->imgpath;
 		$online=$row->lastonline;
 		$time=time();

 		echo '<a href="inbox.php?name='.$name.'"><li>
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
		$sql = "SELECT productid,productname, img, price FROM tblproduct WHERE productname LIKE '%$search%'";
		$result = $conn->query($sql);
		while($row = $result->fetch_object()){
			$id = $row->productid;
			$name = $row->productname;
			$img = $row->img;
			$price = $row->price;

			echo '<a href="product.php?id='.$id.'"><li>
			<div class="sch-tn">
				<img src="'.$img.'">
			</div>
			'.$name.'<br>
			₱'.$price.'
			</li></a>';
		}
	}else{
		$sql = "SELECT productid,productname, img, price FROM tblproduct WHERE productname LIKE '%$search%' AND categoryid='$criteria'";
		$result = $conn->query($sql);
		while($row = $result->fetch_object()){
			$id = $row->productid;
			$name = $row->productname;
			$img = $row->img;
			$price = $row->price;

			echo '<a href="product.php?id='.$id.'"><li>
			<div class="sch-tn">
				<img src="'.$img.'">
			</div>
			'.$name.'<br>
			₱'.$price.'
			</li></a>';
		}
	}
	echo '</ul>';
}
?>