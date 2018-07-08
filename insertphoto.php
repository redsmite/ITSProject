<?php
	session_start();
	include'functions.php';
	user_access();
	updateStatus();
	//Get Profile Info
	require_once'connection.php';
	
	if(isset($_SESSION['id'])){
		$id = $_SESSION['id'];
		$sql="SELECT imgpath FROM tbluser WHERE userid='$id'";
		
		$result=$conn->query($sql);

		if($result->num_rows == 0){
			die('<div id="thanks-message"><p>This username doesn\'t exist.</p></div>');
		}

		$rows=$result->fetch_object();
		
		$image=$rows->imgpath;
	}
	//End of Get Profile Info
	addSidebar();
	addLogin();
	setupCookie();
	chattab();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
 	<meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">
  	<link rel="stylesheet" href="css/fontawesome-all.css">
	<title><?php companytitle()?></title>
</head>
<body>
	<div class="main-container">
	<!-- Header -->
	<?php
		addheader();
	?>
	<!-- Main Content -->
		<div class="other-content">
			<h1><i class="fas fa-camera"></i> Profile Picture</h1>
			<div class="edit-form">
				<center>
							<p>*Please don't post nudes or offensive pictures</p> 
					<div class="profile-pic-wrap">
						<?php
							if($image){
								$fimage= '<img src="'.$image.'"/>';
							}else if(!$image){
								$fimage='<img src="img/default.png" />';
							}
							echo $fimage;
						?>
					</div>

					<form id="profile-pic-form" method="POST" enctype="multipart/form-data">

						<div>Select Picture:
							<br>
							<input type="file" name="img" value="Choose Image" name="img"/>
						</div>
						<div>
							<button type="submit" name="submit">
								<i class="fas fa-upload"></i> Upload
							</button><br><br> 

							<button type="
							submit" name="remove">
								<i class="fas fa-trash-alt"></i> Remove
							</button>
						</div>
<?php

$id=$_SESSION['id'];

if(isset($_POST['remove'])){
		$sql = "UPDATE tbluser SET imgname='',imgtype='',imgpath='' WHERE userid='$id'";  
		$conn->query($sql);
		echo("<script>window.location.href = 'profile.php?name=".$_SESSION['name']."';</script>");	
}

//upload photo
if(isset($_POST['submit'])){
	$error='';
	
	if(!$_FILES['img']['tmp_name']){
		echo'<div id="error-message2"><i class="fas fa-exclamation-circle"></i>File is empty. Select an image to upload.</div>';
	}else{

	$filetemp=$_FILES['img']['tmp_name'];
	$filename=$_FILES['img']['name'];
	$filetype=$_FILES['img']['type'];
	$filepath="upload/".$filename;
	if($filetype != "image/jpg" && $filetype != "image/png" && $filetype != "image/jpeg"
	&& $filetype != "image/gif") {
	     echo'<div id="error-message2"><i class="fas fa-exclamation-circle"></i>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</div>';
	 	$error=1;
	}

	if (filesize($filetemp) > 500000) {
	    echo'<div id="error-message2"><i class="fas fa-exclamation-circle"></i>Sorry, your file is too large. <strong>Maximum: 500kb.</strong></div>';
	    $error=1;
	}


	if($error==''){
		move_uploaded_file($filetemp, $filepath);
		$filename=$conn->real_escape_string($filename);
		$filetype=$conn->real_escape_string($filetype);
		$filepath=$conn->real_escape_string($filepath);
		$sql="UPDATE tbluser SET imgname='$filename',imgtype='$filetype',imgpath='$filepath' WHERE userid='$id'";
		$result=$conn->query($sql) or die($conn->error());

		if($result){
			echo("<script>window.location.href = 'profile.php?name=".$_SESSION['name']."';</script>");
		}
	}
	}
}
?>
						<div id="error-message2">
							</div>
					</form>
				</center>
			</div>
		</div>
	<!-- Footer -->
		<?php
			addfooter();
		?>
	<!-- End of Container -->
	</div>
	<script src="js/main.js"></script>
	<script>
	</script>
</body>
</html>