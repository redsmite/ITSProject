<?php
session_start();
require_once'connection.php';
include'functions.php';
user_access();

if(isset($_POST['login'])){
	$name=$conn->real_escape_string($_POST['login']);
	$password=$conn->real_escape_string($_POST['password']);

	$sql="SELECT name,password FROM tbladmin WHERE name='$name' and password='$password'";
	$result=$conn->query($sql);
	if($result->num_rows==0){
		echo'<div id="error-message"><i class="fas fa-exclamation-circle"></i>Admin login failed.</div>';
	} else {
		$_SESSION['admin']='IchigoParfait';
		echo 'success';
	}
}

if(isset($_POST['fetch'])){
	$fetch = $_POST['fetch'];

	$data='<div onclick="resetfetch()" class="closethis"><a><i class="fas fa-times"></i></a></div>
	<table>
	<tr>
	<th>Profile</th>
	<th>Ban/Allow</th>
	<th>Remove Photo</th>
	</tr>';

	$sql="SELECT userid,username,access,usertypeid FROM tbluser WHERE username LIKE '%$fetch%' LIMIT 10";
	$result=$conn->query($sql);
	while($row=$result->fetch_object()){
		$id = $row->userid;
		$name = $row->username;
		$access = $row->access;
		$type = $row->usertypeid;

		$data.= '<tr>
		<th><a href="profile.php?name='.$name.'">'.$name.'</a><br></th>
		<th id="user-'.$id.'"><a id="'.$id.'" class="useraccess" value="'.$id.'" onclick="useraccess(this.id)">';

		
		if($access==1){
			if($type==4){
			}else{
				$data.='<span class="notbanned">Allow</span>';
			}
		} else if ($access==0){
			$data.='<span class="banned">Banned</span>';
		}

		$data.='</a></th>
		<th id="photo-'.$id.'"><a value="'.$id.'" onclick="removephoto(this)">Remove Photo</a></th>
		</tr>';
	}
	echo $data;
}

if(isset($_POST['status'])){
	$id = $_POST['status'];

	$sql = "SELECT access FROM tbluser WHERE userid='$id'";
	$result = $conn->query($sql);
	$row = $result->fetch_object();
	$access = $row->access;

	if($access == 1){
		$change = "UPDATE tbluser SET access=0 WHERE userid='$id'";
		$sql2 = $conn->query($change);
		echo'<span class="banned">Banning...</span>';
	} else if ($access == 0){
		$change = "UPDATE tbluser SET access=1 WHERE userid='$id'";
		$sql2 = $conn->query($change);
		echo '<span class="notbanned">Removing ban...</span>';
	}
}

if(isset($_POST['photo'])){
	$id = $_POST['photo'];

	$sql = "UPDATE tbluser SET imgname='',imgtype='',imgpath='' WHERE userid='$id'";
	$result = $conn->query($sql);

	$sql = "INSERT INTO tblnotif(receiverid,notifdate,notiftype) VALUES('$id',NOW(),3)";
	$result = $conn->query($sql);
}

if(isset($_POST['select'])){
	$select = $_POST['select'];
	$reason = $_POST['reason'];
	$name = $_POST['username'];
	$reporter = $_SESSION['id'];

	$sql = "SELECT userid FROM tbluser WHERE username ='$name'";
	$result = $conn->query($sql);
	$row = $result->fetch_object();
	$id = $row->userid;

	if (!$reason){

	$sql = "INSERT INTO tblreport (reason,datecreated,userid,reporter)
	VALUES ('$select',NOW(),'$id','$reporter')";
	$result = $conn->query($sql);
	echo'oke-oke-okay';

	}else{


	$sql = "INSERT INTO tblreport (reason,datecreated,userid,reporter)
	VALUES ('$reason',NOW(),'$id','$reporter')";
	$result = $conn->query($sql);
	echo'oke-oke-okay';
	}
}

if(isset($_POST['check'])){
	$id = $_POST['check'];

	$sql = "UPDATE tblreport SET checked=1 WHERE reportid = '$id'";
	$result = $conn->query($sql);

	echo 'oke-oke-okay';
}

if(isset($_POST['title'])){
	$title = $conn->real_escape_string($_POST['title']);
	$content = $conn->real_escape_string($_POST['content']);
	$author = $_POST['author'];

	$sql = "INSERT INTO tblannouncement (title,content,author,datecreated) VALUES ('$title','$content','$author',NOW())";
	$result = $conn->query($sql);
	echo $sql;

}

if(isset($_POST['addCat'])){
	$category = $conn->real_escape_string($_POST['addCat']);

	$sql="SELECT categoryid FROM tblcategory WHERE category='$category'";
	$result=$conn->query($sql);
	if($result->num_rows==0){

	$sql="INSERT INTO tblcategory (category,status) VALUES('$category',1)";
	$result=$conn->query($sql);
	echo 'success';

	$log = 'Add '.$category.' category to the database';

	$sql="INSERT INTO tblchangelog (log,datecreated) VALUES ('$log',NOW())";
	$result= $conn->query($sql);

	}else{
		echo 'Already exist';
	}
}

if(isset($_POST['log'])){

	$sql="SELECT log,datecreated FROM tblchangelog ORDER BY logid DESC";
	$result=$conn->query($sql);
	while($row=$result->fetch_object()){
		$log = $row->log;
		$date = date('D, F j Y g:i A',strtotime($row->datecreated));

		echo'<p>'.$date.': '.$log.'</p>';
	}
}

if(isset($_POST['fetchb'])){
	$category = $conn->real_escape_string($_POST['fetchb']);
	$sql = "SELECT categoryid,status,category FROM tblcategory WHERE category LIKE '%$category%' LIMIT 10";
	echo '<div onclick="resetthis()" class="closethis"><a><i class="fas fa-times"></i></a></div>
		<table>
		<tr>
		<th>Category</th>
		<th>Show/Hide</th>
		</tr>';
	$result = $conn->query($sql);
	while($row = $result->fetch_object()){
		$id = $row->categoryid;
		$category = $row->category;
		$status = $row->status;

		echo '<tr>
		<th>'.$category.'</th>';
		
		if($status==0){
		echo'<th id="cat-'.$id.'" class="cathover" value='.$id.' onclick="showCat(this)"><span class="banned">Hide</span></th>';
		}else if($status==1){
		echo'<th id="cat-'.$id.'" class="cathover" value='.$id.' onclick="hideCat(this)"><span class="notbanned">Show</span></th>';
		}
		echo'</tr>';
	}
	echo '</table>';
}

if(isset($_POST['showcat'])){
	$id = $_POST['showcat'];


	$sql = "UPDATE tblcategory SET status=1 WHERE categoryid='$id'";
	$result = $conn->query($sql);
	echo 'oke-oke-okay';
}


if(isset($_POST['hidecat'])){
	$id = $_POST['hidecat'];

	$sql = "UPDATE tblcategory SET status=0 WHERE categoryid='$id'";
	$result = $conn->query($sql);
	echo 'oke-oke-okay';
}

if(isset($_POST['low'])){
	$id= $_POST['pid'];
	$low = $_POST['low'];
	$high = $_POST['high'];
	$prev = $_POST['prev'];

	if($low<0 or $high<0 or $prev<0){
		echo' Can\'t set price to negative';
	}else{

		$sql = "UPDATE tblcategory SET low='$low',high='$high',prevailing='$prev' WHERE categoryid='$id'";
		$result = $conn->query($sql);

		$sql = "SELECT category FROM tblcategory WHERE categoryid='$id'";
		$result= $conn->query($sql);
		$fetch = $result->fetch_object();
		$category=$fetch->category;

		$log = 'Update price of '.$category;

		$sql = "INSERT INTO tblchangelog (log,datecreated) VALUES ('$log',NOW())";
		$result= $conn->query($sql);

		echo 'success';
	}
}
?>