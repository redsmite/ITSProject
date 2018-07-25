<?php
	session_start();
	include'functions.php';
	addSidebar();
	setupCookie();
	user_access();
	updateStatus();
	//Get Profile Info
	require_once'connection.php';
	
	if(isset($_SESSION['id'])){
		$id = $_SESSION['id'];
		$sql="SELECT userid,firstname,middlename,lastname,birthday,website,location,usertypeid,bio,is_show_email,gender FROM tbluser WHERE userid='$id'";
		
		$result=$conn->query($sql);

		if($result->num_rows == 0){
			die('<div id="thanks-message"><p>This username doesn\'t exist.</p></div>');
		}

		$rows=$result->fetch_object();
		
		$id=$rows->userid;
		$firstname=$rows->firstname;
		$lastname=$rows->lastname;
		$usertype=$rows->usertypeid;
		$email_access=$rows->is_show_email;
		$gender=$rows->gender;
		$middlename=$rows->middlename;
		$birthday=$rows->birthday;
		$website=$rows->website;
		$location=$rows->location;
		$bio=$rows->bio;
	}
	mysqli_close($conn);
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
			<h1><i class="fas fa-pen-square"></i> Personal Info</h1>
			<div class="edit-form">
				<center>
				<form action="editinfoprocess.php"id="edit-form" method="post">
					<div>
						<label for="">First Name</label><br>
						<input type="text"  <?php echo 'value="'.$firstname.'"'?> id="edit-first" name="edit-first"placeholder="Enter First Name...">
					</div>
					<div>
						<label for="">Last Name</label><br>
						<input type="text" <?php echo 'value="'.$lastname.'"'?> id="edit-last" name="edit-last" placeholder="Enter Last Name...">
					</div>
					<div>
						<label for="">Nickname</label><br>
						<input type="text" <?php echo 'value="'.$middlename.'"'?> id="edit-middle" name="edit-middle"placeholder="Enter Nickname...">
					</div>
					<div>
						<label for="">Birthday</label><br>
						<input type="date" <?php echo 'value="'.$birthday.'"'?> id="edit-birthday" name="edit-birthday">
					</div>
					<div>
						<label for="">Website</label><br>
						<textarea id="edit-website" name="edit-website"><?php echo $website?></textarea>
					</div>
					<div>
						<label for="">Location</label><br>
						<textarea id="edit-location" name="edit-location"><?php echo $location?></textarea>
						<br><br>
					</div>
					<div id="gender-box">

						<?php
						if($gender==1){
							echo'<label for="">Gender</label><br><input type="radio" checked required name="gender" id="gender" value="1"> Male
							<input type="radio" name="gender" id="gender" value="2"> Female
							<input type="radio" name="gender" id="gender" value="3"> Non-Binary
							<input type="radio" name="gender" id="gender" value="4"> Don\'t';
						}else if ($gender==2){
							echo'<label for="">Gender</label><br><input type="radio" name="gender" id="gender" value="1"> Male
								<input type="radio"  checked name="gender" id="gender" value="2"> Female
								<input type="radio" name="gender" id="gender" value="3"> Non-Binary
								<input type="radio" name="gender" id="gender" value="4"> Don\'t Show';
						}else if($gender==3){
							echo'<label for="">Gender</label><br><input type="radio" name="gender" id="gender" value="1"> Male
								<input type="radio" name="gender" id="gender" value="2"> Female
								<input type="radio" name="gender" id="gender" checked value="3"> Non-Binary
								<input type="radio" name="gender" id="gender" value="4"> Don\'t Show';
						}else{
							echo'<label for="">Gender</label><br><input type="radio" name="gender" id="gender" value="1"> Male
								<input type="radio" name="gender" id="gender" value="2"> Female
								<input type="radio" name="gender" id="gender" value="3"> Non-Binary
								<input type="radio" name="gender" id="gender" checked value="4"> Don\'t Show';
						}
						?>
					</div>
					<br>
					<div>
						<label for="">About me</label><br>
						<textarea id="edit-bio" name="edit-bio" placeholder="Tell us about yourself..."><?php echo $bio?></textarea>
					</div>
					<div>

						<label for="">Show email?</label>
						<?php
							if($email_access==1){
								echo'<input type="checkbox" id="privacy" checked value="1" name="privacy">';
							}else{
								echo'<input type="checkbox" value="1" id="privacy"name="privacy">';
							}
						?>
						
					</div>
						<br><br>
					<div>
						<button type="submit" name="edit-button" id="edit-button">Submit</button>
					</div>
				</form>
				</center>
			</div>
		</div>
	<!-- Footer -->
		<<?php
			addfooter();
		?>
	<!-- End of Container -->
	</div>
	<script src="js/main.js"></script>
</body>
</html>