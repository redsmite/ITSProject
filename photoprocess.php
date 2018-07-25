<?php
session_start();
include'functions.php';
user_access();
require_once'connection.php';


$id=$_SESSION['id'];

if(isset($_POST['remove'])){
	$remove = $_POST['remove'];


	$sql = "UPDATE tbluser SET imgname='',imgtype='',imgpath='' WHERE userid='$id'";  
	$conn->query($sql);
	echo $_SESSION['id'];	

}

if(isset($_POST['img'])){
	$file= $_POST['img'];
}


?>