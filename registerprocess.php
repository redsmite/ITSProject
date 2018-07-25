<?php
require_once'connection.php';
session_start();

if(isset($_POST['username'])){
	$error='';
	
	if(strlen($_POST['username']) > 20)
	{
	    $error.='<i class="fas fa-exclamation-circle"></i>Username must not exceed 20 characters<br>';
	}

	if(strlen($_POST['password']) < 8)
	{
	    $error.='<i class="fas fa-exclamation-circle"></i>Password must be atleast 8 characters<br>';
	}


	//insert into Database
	$username = mysqli_real_escape_string($conn,$_POST['username']);
	$email= mysqli_real_escape_string($conn,$_POST['email']);
	$password = md5($_POST['password']);
	$retype= md5($_POST['retype']);
	$timestamp = 'NOW()';

	//Check if password is equal
	if($password!=$retype){
		$error.='<i class="fas fa-exclamation-circle"></i>Password doesn\'t match<br>';
	}

	//Check if username is taken
	$sql="SELECT username FROM tbluser WHERE username='$username'";
	$res_u=$conn->query($sql);
	if ($res_u->num_rows != 0) {
		$error.='<i class="fas fa-exclamation-circle"></i>Username is already taken<br>';
	}

	//Check if email is taken		
	$sql2="SELECT email FROM tbluser WHERE email='$email'";
	$res_e =$conn->query($sql2);
	if($res_e->num_rows != 0){
		$error.='<i class="fas fa-exclamation-circle"></i>Email is already taken<br>';
	}

	//Check if no error
	if(!$error){
		$sql3="INSERT INTO tbluser(username,password,datecreated,email,usertypeid,access,is_show_email) VALUES('$username','$password',$timestamp,'$email','1','1','1')";
		if($conn->query($sql3)){

			//Auto Login

			$sql4='SELECT COALESCE(MAX(userid), 0) AS newUserID FROM tbluser';
			$result=$conn->query($sql4);
		
			$row=$result->fetch_object();
			$userlogin=$row->newUserID;
			
			$_SESSION['id']=$userlogin;
			$_SESSION['name']=$username;
			$_SESSION['type']=1;

			$message='Hello '.$username.'! \n Welcome to Bahay Kubo ni Mang Celso. \n Thanks for joining us. \n Please refer for the link for more info \n http://localhost/project/about.php';

			$sendpm="INSERT INTO tblpm (senderid,receiverid,message,pmdate) VALUES('71','$userlogin','$message',$timestamp)";
			$rsendpm=$conn->query($sendpm);

			echo 'success';
		}

	} else {
		echo $error;
	}
}
?>