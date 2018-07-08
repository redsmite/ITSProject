<?php
session_start();
require_once'connection.php';

if(isset($_POST['username'])){
	//insert into Database
	$username = $conn->real_escape_string($_POST['username']);
	$password = md5($_POST['password']);
	$remember = $_POST['remember'];

	$sql = "SELECT userid,username,email,password,usertypeid,access FROM tbluser WHERE (username='$username' and password='$password') or (email='$username' and password='$password')";

		if($result=$conn->query($sql)){
		
			if ($result->num_rows != 0) {

				$row=$result->fetch_object();

				if($row->access==1){

					$id = $row->userid;
					$name = $row->username;
					$type = $row->usertypeid;
					
					$_SESSION['id']=$id;
					$_SESSION['name']=$name;
					$_SESSION['type']=$type;

					if($remember==1){
						setcookie('id', $id, time()+60*60*24*365,'/');
						setcookie('name', $name, time()+60*60*24*365,'/');
						setcookie('type', $type, time()+60*60*24*365,'/');
					}

					$sessions = array(0,$id,$name,$type);

					echo json_encode($sessions);

				}else if($row->access==0) {
					$sessions =array(1,'<i class="fas fa-exclamation-circle"></i>Sorry, your account is banned.');
					echo json_encode($sessions);
				}
		
			}else{
				$sessions =array(1,'<i class="fas fa-exclamation-circle"></i>Invalid username / email or password.');
				echo json_encode($sessions);
			}
		}
}





