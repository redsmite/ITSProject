<?php
session_start();
require_once'connection.php';
include'functions.php';
user_access();

//Admin login and settings

if(isset($_POST['login'])){
	$name=$conn->real_escape_string($_POST['login']);
	$password=md5($_POST['password']);

	$sql="SELECT name,password FROM tbladmin WHERE name='$name' and password='$password'";
	$result=$conn->query($sql);
	if($result->num_rows==0){
		echo'<div id="error-message"><i class="fas fa-exclamation-circle"></i>Admin login failed.</div>';
	} else {
		$_SESSION['admin']='IchigoParfait';
		echo 'success';
	}
}

if(isset($_POST['changeadmin'])){
	$name = $conn->real_escape_string($_POST['changeadmin']);
	$old = md5($_POST['old']);
	$new = md5($_POST['new']);
	$confirm = md5($_POST['confirm']);

	$sql = "SELECT password FROM tbladmin";
	$result = $conn->query($sql);
	$fetch = $result->fetch_object();
	$password = $fetch->password;
	

	$error = '';
	
	if($old != $password){
		$error .= '<i class="fas fa-exclamation-circle"></i> Old password doesn\'t match<br>';
	}

	if($confirm != $new){
		$error .= '<i class="fas fa-exclamation-circle"></i> New password doesn\'t match<br>';	
	}

	if(!$error){
		$sql = "UPDATE tbladmin SET name='$name',password='$new'";
		$result = $conn->query($sql);
		echo 'success';		
	}else{
		echo $error;
	}
}

//Users and Reports

if(isset($_POST['fetch'])){
	$fetch = $_POST['fetch'];

	$data='<div onclick="resetfetch()" class="closethis"><a><i class="fas fa-times"></i></a></div>
	<table>
	<tr>
	<th>Profile</th>
	<th>Ban/Allow</th>
	<th>Remove Photo</th>
	<th>Change User Type</th>
	</tr>';

	$sql="SELECT userid,username,access,usertypeid FROM tbluser WHERE username LIKE '%$fetch%' LIMIT 10";
	$result=$conn->query($sql);
	while($row=$result->fetch_object()){
		$id = $row->userid;
		$name = $row->username;
		$access = $row->access;
		$type = $row->usertypeid;

		$data.= '<tr>
		<th><a href="profile.php?id='.$id.'">'.$name.'</a><br></th>
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
		<th id="photo-'.$id.'"><a value="'.$id.'" onclick="removephoto(this)">Remove Photo</a></th>';

		if($type==1){
			$data.='<th id="type-'.$id.'" class="cathover" value="'.$id.'" onclick="settoSeller(this)"><u>User</u></th>';
		}else if ($type==2){
			$data.='<th>Bot</th>';
		}else if ($type==3){
			$data.='<th id="type-'.$id.'" class="cathover" value="'.$id.'" onclick="settoUser(this)"><u>Seller</u></th>';
		}else if ($type==4){
			$data.='<th>Admin</th>';
		}

			$data.='</tr>';
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

if(isset($_POST['seller'])){
	$id = $_POST['seller'];

	$sql = "UPDATE tbluser SET usertypeid = 3 WHERE userid = '$id'";
	$result = $conn->query($sql);

	echo 'oke-oke-okay';
}

if(isset($_POST['notseller'])){
	$id = $_POST['notseller'];

	$sql = "UPDATE tbluser SET usertypeid = 1 WHERE userid = '$id'";
	$result = $conn->query($sql);

	$sql ="UPDATE tblproduct SET is_available = 0 WHERE userid = '$id'";
	$result = $conn->query($sql);

	echo 'oke-oke-okay';
}

if(isset($_POST['select'])){
	$select = $_POST['select'];
	$reason = $_POST['reason'];
	$id = $_POST['userid'];
	$reporter = $_SESSION['id'];

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

//Announcement

if(isset($_POST['title'])){
	$title = $conn->real_escape_string($_POST['title']);
	$content = $conn->real_escape_string($_POST['content']);
	$author = $_POST['author'];

	$sql = "INSERT INTO tblannouncement (title,content,author,datecreated) VALUES ('$title','$content','$author',NOW())";
	$result = $conn->query($sql);
	echo $sql;

}

// Price Monitoring
if(isset($_POST['updateFee'])){
	$fee = $_POST['updateFee'];

	$sql = "UPDATE tblfee SET fee = '$fee' WHERE feeid=1";
	$result = $conn->query($sql);

	$log = 'Update price of Shipping fee';

	$sql = "INSERT INTO tblchangelog (log,datecreated) VALUES ('$log',NOW())";
	$result= $conn->query($sql);
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

	$sql="SELECT log,datecreated FROM tblchangelog ORDER BY logid DESC LIMIT 100";
	$result=$conn->query($sql);
	while($row=$result->fetch_object()){
		$log = $row->log;
		$date = date('D, F j Y g:i A',strtotime($row->datecreated));

		echo'<p>'.$date.': '.$log.'</p>';
	}
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

	// Price Validation
	$sql = "SELECT category,low,high,prevailing FROM tblcategory WHERE categoryid='$id'";
	$result= $conn->query($sql);
	$fetch = $result->fetch_object();
	$category=$fetch->category;
	$Clow = $fetch->low;
	$Chigh = $fetch->high;
	$Cprevailing = $fetch->prevailing;

	$error='';

	if($low==$Clow and $Chigh == $high and $prev == $Cprevailing){
		$error.=' No changes are made<br>';

	}

	if($low>$prev){
		$error.= 'Prevailing price can\'t be lower than low price<br>';
	}

	if($prev>$high){
		$error.= 'Prevailing price can\'t be higher than high price<br>';
	}

	if($high<$low){
		$error.= 'Low price can\'t be higher than high price<br>';
	}

	if ($low<0 or $high<0 or $prev<0){
		$error.=' Can\'t set price to negative<br>';
	}

	if(!$error){

		$sql = "UPDATE tblcategory SET low='$low',high='$high',prevailing='$prev' WHERE categoryid='$id'";
		$result = $conn->query($sql);

		$sql2="SELECT productid,price FROM tblproduct WHERE categoryid='$id'";
		$result2=$conn->query($sql2);
		while($row2=$result2->fetch_object()){
			$id = $row2->productid;
			$price = $row2->price;
			if($price < $low){
				$sql = "UPDATE tblproduct SET price = '$low' WHERE productid = '$id'";
				$result = $conn->query($sql);
			}
			if($price>$high){
				$sql = "UPDATE tblproduct SET price = '$high' WHERE productid = '$id'";
				$result = $conn->query($sql);
			}
		}

		$log = 'Update price of '.$category;

		$sql = "INSERT INTO tblchangelog (log,datecreated) VALUES ('$log',NOW())";
		$result= $conn->query($sql);

		echo 'success';
	}else{
		echo $error;
	}
}

// Products and Transactions

if(isset($_POST['showApprove'])){

	$sql = "SELECT productid, productname FROM tblproduct WHERE is_approved = 0 ORDER BY productid DESC";
	$result = $conn->query($sql);
	while($row = $result->fetch_object()){
		$productid = $row->productid;
		$product = $row->productname;
		echo '<p><a class="black" target="_blank" href="product.php?id='.$productid.'">'.$product.'</a></p>';
	}
}

//Sales and Farm

if(isset($_POST['farm'])){
	$farm = $conn->real_escape_string($_POST['farm']);
	$address = $conn->real_escape_string($_POST['address']);

	$sql = "SELECT farmid FROM tblfarm WHERE farmname = '$farm'";
	$result = $conn->query($sql);
	$count = $result->num_rows;
	if($count != 0){
		echo'Farm name is already taken';
	}else{
		$sql = "INSERT INTO tblfarm (farmname, address,status) VALUES('$farm','$address',1)";
		$result = $conn->query($sql);
		echo 'success';
	}
}

if(isset($_POST['updatefarm'])){
	$name = $conn->real_escape_string($_POST['updatefarm']);
	$address = $conn->real_escape_string($_POST['address']);
	$status = $_POST['fstatus'];
	$id = $_POST['id'];

	$sql = "UPDATE tblfarm SET farmname='$name', address='$address', status='$status' WHERE farmid = '$id'";
	$result = $conn->query($sql);
	echo 'success';
}

//Sales Report Function
function salesReport($where,$format,$weekly){
	$conn = new mysqli('localhost','root','','itsproject');
	echo'<table class="sales-table">
	<tr><th colspan="3">';
	
	if($weekly==0){
		echo date($format);
	}else{
		for($i=6;$i>=0;$i--){
			if($i==6){
			$date = strtotime("-$i day");
			echo date(' M j Y', $date).' - ';
			}
			if($i==0){
			$date = strtotime("-$i day");
			echo date(' M j Y', $date);
			}
		}
	}

	echo'</th></tr>
	<tr><th>Product</th><th>Unit</th><th>Sales</th></tr>';

	$sql = "SELECT t1.productid, productname, weight, sales FROM tblsales AS t1
	LEFT JOIN tblproduct AS t2
		ON t1.productid = t2.productid
	$where
	GROUP BY t1.productid
	ORDER BY sales DESC";
	$result = $conn->query($sql);
	while($row=$result->fetch_object()){
		$productid = $row->productid;
		$product = $row->productname;
		$weight = $row->weight;
		$sales = $row->sales;
		echo'<tr>
		<th><a class="black" href="product.php?id='.$productid.'">'.$product.'</a></th>
		<th>'.$weight.'kg</th>
		<th>₱'.number_format($sales,2).'</th>
		</tr>';
	}
	echo'</table>';
	$sql = "SELECT SUM(sales) AS total FROM tblsales $where";
	$result = $conn->query($sql);
	$fetch = $result->fetch_object();
	echo '<h1>Total: ₱'.number_format($fetch->total,2).'</h1>';
}

if(isset($_POST['daily'])){
	echo '<h1><i class="fas fa-chart-bar"></i> Daily Report</h1>';

	$string = 'WHERE day(CURRENT_DATE) = day(datecommit)';
	$format = "M j, Y";
	salesReport($string,$format,false);
}

if(isset($_POST['weekly'])){
	echo '<h1><i class="fas fa-chart-bar"></i> Weekly Report</h1>';
	$string = "WHERE DATE_SUB(datecommit, INTERVAL 7 DAY)";
	$format = "M j, Y";
	salesReport($string,$format,true);

}

if(isset($_POST['monthly'])){
	echo '<h1><i class="fas fa-chart-bar"></i> Monthly Report</h1>';

	$string='WHERE month(CURRENT_DATE) = month(datecommit) AND year(CURRENT_DATE) = year(datecommit)';
	$format = "F Y";
	salesReport($string,$format,false);
}

if(isset($_POST['yearly'])){
	echo '<h1><i class="fas fa-chart-bar"></i> Yearly Report</h1>';

	$string='WHERE month(CURRENT_DATE) = month(datecommit) AND year(CURRENT_DATE) = year(datecommit)';
	$format = "Y";
	salesReport($string,$format,false);
}

?>