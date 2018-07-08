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

if(isset($_POST['search2'])){
	$search = $conn->real_escape_string($_POST['search2']);

	$data='';

	$sql = "SELECT forumid,name,(views+subscriber) AS popular FROM tblforum WHERE name LIKE '%$search%' ORDER BY popular DESC LIMIT 10";
	$result = $conn->query($sql);
	while($row = $result->fetch_object()){
		$id = $row->forumid;
		$name = $row->name;

		$data.= '<ul class="drop-ul2">
		<a href="forums.php?id='.$id.'"><li>
		<div class="drop-tn">
			<img src="img/marketicon.png">
		</div>
		'.$name.'</li></a></ul>';
	}
	echo $data;
}

if(isset($_POST['search3'])){
	$search = $conn->real_escape_string($_POST['search3']);

	$data='';

	$sql = "SELECT postid,forumid,title,img,(((views*0.2) + (score*0.8))/((NOW()-datecreated)/331536000)) AS trending FROM tblpost WHERE title LIKE '%$search%' ORDER BY trending DESC LIMIT 10";
	$result = $conn->query($sql);
	while($row = $result->fetch_object()){
		$id = $row->postid;
		$forumid = $row->forumid;
		$title = $row->title;
		$img = $row->img;
		if(!$img){
			$img='img/noimage.png';
		}

		$data.= '<ul class="drop-ul2">
		<a href="reply.php?id='.$forumid.'&thread='.$id.'"><li>
		<div class="drop-tn">
			<img src="'.$img.'">
		</div>
		'.$title.'</li></a></ul>';
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
?>