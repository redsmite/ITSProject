<?php
	session_start();
	include'functions.php';
	updateStatus();
	//Get Profile Info
	require_once'connection.php';

	if(!isset($_GET['name'])){
		die('<div id="thanks-message"><p>This page doesn\'t exist.</p></div>');
	}

	if(isset($_GET['name'])){
		$name = $_GET['name'];
		$sql="SELECT userid,username,firstname,middlename,lastname,birthday,datecreated,email,website,location,usertypeid,imgpath,bio,is_show_email,gender,lastonline,profileviews FROM tbluser WHERE username='$name'";
		
		$result=$conn->query($sql);

		if($result->num_rows == 0){
			die('<div id="thanks-message"><p>This username doesn\'t exist.</p></div>');
		}

		$rows=$result->fetch_object();
		
		$id=$rows->userid;
		$user=$rows->username;
		$firstname=$rows->firstname;
		$lastname=$rows->lastname;
		$datecreated=date("M j, Y", strtotime($rows->datecreated));
		$email=$rows->email;
		$usertype=$rows->usertypeid;
		$email_access=$rows->is_show_email;
		$online=$rows->lastonline;
		$time=time();
		$middlename=$rows->middlename;
		if($rows->birthday==0){

			$birthday='';

		}else{

			$birthday=date("M j, Y", strtotime($rows->birthday));
		}
		$website=$rows->website;
		$location=$rows->location;
		$image=$rows->imgpath;
		$bio=$rows->bio;
		$gender=$rows->gender;
		$views=$rows->profileviews;
	}
	//Add to Profile View
	if(!isset($_SESSION['id'])){
		$sql="UPDATE tbluser SET profileviews=profileviews+1 WHERE userid='$id'";
		$result=$conn->query($sql);
	}

	if(isset($_SESSION['id'])){
		if($id!=$_SESSION['id']){
			$sql="UPDATE tbluser SET profileviews=profileviews+1 WHERE userid='$id'";
			$result=$conn->query($sql);
		}
	}

	addSidebar();
	addLogin();
	setupCookie();
	reportuser();
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
			<div class="user-grid">
				<div class="left-grid">
					<div class="profile-pic-wrap">
						<?php
							if($image){
								$image= '<img src="'.$image.'"/>';
							}else if(!$image){
								$image='<img src="img/default.png" />';
							}
							echo $image;
						?>
					</div>
					<div class="user-header">
						<?php
							echo'<h1>			
							'.$user.'					
							</h1>
							<h3>Joined: '.$datecreated.'</h3>';
							if($time-strtotime($online)< 300){
								echo'<h5><font color="green"><div class="online"></div>Online</font></h5>';
							} else{
								echo'<h3><div class="offline"></div>Last Online: '.time_elapsed_string($online).'</h3>';
							}

							if($usertype==1){
								//Nothing
							}else if ($usertype==2){
								echo'<p id="user-bot">Bot</p>';
							}else if ($usertype==3){
								echo'<p id="user-dbadmin">DB Admin</p>';
							}else if ($usertype==4){
								echo'<p id="user-siteadmin">Site Admin</p>';
							}
						?>
					</div>
					<div class="friends">
						<h1>Friends</h1>
						<a href="profilefriends.php?name=<?php echo $name; ?>"><p id="showallfr">Show all friends</p></a>
<?php
// Show friends
$sql="SELECT user1,user2,username,imgpath FROM tblfriend
LEFT JOIN tbluser
	ON userid=user1 or userid=user2
 WHERE (user1='$id' or user2='$id') AND accepted=2 AND userid!='$id'
 ORDER BY lastonline DESC LIMIT 12";
$result=$conn->query($sql);
while($rows=$result->fetch_object()){
$user1=$rows->user1;
$user2=$rows->user2;
$username=$rows->username;
$imgpath=$rows->imgpath;

	echo'<div class="friends-tn">
			<a title="'.$username.'" href="profile.php?name='.$username.'"><img src="'.$imgpath.'"></a>
		</div>';


}



?>
					</div>
					<div class="dashboard">
						<?php
						if(isset($_SESSION['id'])){
							if($_SESSION['name']==$_GET['name']){
								echo'<ul>
									<li><a href="inbox.php?name='.$_GET["name"].'"><i class="fas fa-envelope"></i> Check Inbox</a></li>
									<li><a href="insertphoto.php"><i class="fas fa-camera"></i> Change Profile Picture</a></li>
									<li><a href="editinfo.php"><i class="fas fa-pen-square"></i> Edit Personal Info</a></li>
									<li><a href="accountsetting.php"><i class="fas fa-cog"></i> Account Settings</a></li>
									</ul>';
							}else{
								echo'<ul>
								<li>
								<a href="inbox.php?name='.$_GET["name"].'"><i class="fas fa-comments"></i> Chat with '.$_GET["name"].'</a>
								</li>
								<li><a id="report" onclick="showreport()"><i class="far fa-flag"></i> Report this User</a>
								</li>';
// Test if user is friend or not
$thisid=$_SESSION['id'];								
$test="SELECT user1,user2 FROM tblfriend WHERE 
(user1='$id' and user2='$thisid') or (user1='$thisid' and user2='$id')";
$testR=$conn->query($test);
$rows=$testR->fetch_object();
if($testR->num_rows!=0){
	$test="SELECT friendid,accepted,friendsince FROM tblfriend WHERE 
	(user1='$id' and user2='$thisid') or (user1='$thisid' and user2='$id')";
	$testR=$conn->query($test);
	$rows=$testR->fetch_object();
	$fid=$rows->friendid;
	$accepted=$rows->accepted;
	$friendsince=$rows->friendsince;
	if($accepted==1){
	echo'<li><p><i class="fas fa-user-plus"></i> Pending request...</p></li>
	</ul>';
	} else if ($accepted==2){
		echo'<li><a id="rmv-fr" value="'.$fid.'" onclick="friendremove()"><i class="fas fa-ban"></i> Remove Friend</a></li>
		</ul>';
	} else if ($accepted==3 && $friendsince==''){
		echo'<li><a id="fr-btn" value="'.$name.'" onclick="friendprocess()"><i class="fas fa-user-plus"></i> Add as friend</a></li>
		</ul>';
	}
}else{
	echo'<li><a id="fr-btn" value="'.$name.'" onclick="friendprocess()"><i class="fas fa-user-plus"></i> Add as friend</a></li>
	</ul>';
}
							}
						}
						echo'<table id="profilestats">';
						// Comment Count
						$sql="SELECT commentid FROM tblcomment WHERE userid = '$id'";
						$result=$conn->query($sql);
						$count = $result->num_rows;
						echo'<tr>
						<th><i class="far fa-comment-dots"></i> Comments:</th>
						<th>'.$count.'</th>
						</tr>';
						//Profile Views
						if(isset($_SESSION['id'])){
							if($id==$_SESSION['id']){
							echo'<tr>
							<th><i class="far fa-eye"></i> Profile Views:</th>
							<th>'.$views.'</th>
							</tr>';
							}
						}
						echo'</table>';
						?>
					</div>
				</div>
				<div class="right-grid">
					<div class="user-info">
						<?php
							echo'<h1><i class="fas fa-info-circle"></i>'.$user.'\'s Personal Info</h1>
							<ul>';
							if($middlename==''){
								echo'<li>Name: '.$firstname.' '.$lastname.'</li>';
							}else{
								echo'<li>Name: '.$firstname.' "'.$middlename.'" '.$lastname.'</li>';
							}
							
							if($gender==1){
								echo'<li>Gender: Male </li>';
							} else if ($gender==2){
								echo'<li>Gender: Female </li>';
							} else if($gender==3){
								echo'<li>Gender: Non-binary </li>';
							}else{
								echo'<li>Gender: </li>';
							}
							
							if($email_access==0){
								echo'<li>Email:</li>';
							}else{
								echo'<li>Email: '.$email.'</li>';
							}
							echo'<li>Birthday: '.$birthday.'</li>
							<li>Website: '.createlink(nl2br($website)).'</li>
							<li>Location: '.nl2br($location).'</li>
							</ul>';
						?>
						<div class="biography">
							<h1><i class="fas fa-info-circle"></i>About me</h1>
							<p>
							<?php
								echo nl2br($bio);
							?>
							</p>
						</div>
					</div>
					</div>
					<div class="comment-grid">
					<div id="profile-comments">
						<h1><i class="fas fa-comments"></i>Comments</h1>
						<p align="right"><a id="allcom" href="profilecomments.php?name=<?php echo $name ?>">Show All Comments</a></p>
						<?php
						if(isset($_SESSION['id'])){
							echo'<div>
							<form action="commentprocess.php" method="post" id="postcomment">
								<textarea name="comment" required id="comment"></textarea>
								<input type="hidden" id="hidden" name="hidden" value="'.$_SESSION["id"].'" />
								<input type="hidden" id="hidden2" name="hidden2" value="'.$_GET["name"].'" />
								<input type="submit" id="comment-submit" name="comment-submit" value="submit">
								</form>
							</div>';
							}
							?>
								<?php
									$sql2="SELECT userid FROM tbluser WHERE username='$name'";
									$result2=$conn->query($sql2);
									$row=$result2->fetch_object();
									$rid=$row->userid;

									$sql3="SELECT commentid,tblcomment.userid,username,comment,dateposted,imgpath,modified FROM tblcomment
									LEFT JOIN tbluser
										ON tblcomment.userid = tbluser.userid
									WHERE receiver='$rid'
									ORDER BY commentid DESC
									LIMIT 15";

									$result3=$conn->query($sql3);
									while($rows2=$result3->fetch_object()){
										$Cid=$rows2->commentid;
										$Cuid=$rows2->userid;
										$Cuser=$rows2->username;
										$Ccomment=$rows2->comment;
										$dateposted=$rows2->dateposted;
										$Cimg=$rows2->imgpath;
										$modified=$rows2->modified;
										if($Cimg==''){
											$Cimg='img/default.png';
										}
										if($modified==0){
											$modified='';
										}else{
											$modified='<i>Modified: '.time_elapsed_string($modified).'</i>';
										}

										echo'<div id="comment'.$Cid.'" class="comment-box">
										<div class="comment-header">
										<a class="cm-user" href="profile.php?name='.$Cuser.'">
										<div class="comment-tn">
										<img src="'.$Cimg.'">
										</div>
										'.$Cuser.'</a>
										<small>'.time_elapsed_string($dateposted).'</small>
										</div>
										<div class="comment-body">
										<div class="com-container"><p class="comment-cm">'.createlink(nl2br($Ccomment)).'</p>
										</div>
											<p class="modified">'.$modified.'</p>';
						//Delete / Edit Comment
						if(!isset($_SESSION['name'])|| !isset($_SESSION['id']))
						{

						}else if($name==$_SESSION['name']||$Cuid==$_SESSION['id']){
							echo'
							<form align="right" action="commentprocess.php" method="post">
							<input type="hidden" name="hidden4" value="'.$_GET["name"].'" />
								<input type="hidden" name="hidden3" value="'.$Cid.'">'; 
								if($Cuid==$_SESSION['id']){
									echo'<a class="profile-edit" href="editcomment.php?id='.$Cid.'&name='.$name.'&this='.$Cuid.'">edit</a>';
								}
							echo'	<input type="submit" value="delete" name="deletebtn">   

							</form>';
				
						}				

						
										

										echo'
										</div>
										</div>';
									}
									mysqli_close($conn);	
								?>
					</div>			
				</div>
				</div>
		</div>
		<!-- Escaping Report User function -->
		<div id="reportform"></div>
	<!-- Footer -->
		<?php
			addfooter();
		?>
	<!-- End of Container -->
	</div>
	<script src="js/main.js"></script>
	<script>
		reportuser();
		modal();
		ajaxLogin();
	</script>
</body>
</html>