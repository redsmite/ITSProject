<?php
	session_start();
	include'functions.php';
	require_once'connection.php';
	addSidebar();
	addLogin();
	setupCookie();
	updateStatus();
	chattab();
	if(isset($_SESSION['id'])){
		$uid = $_SESSION['id'];
	}
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
	<!-- Showcase -->
		<div class="showcase">
			<div class="container">
				<div class="showcase-content">
					<div class="showcase-container">
						<h1>Welcome to BahayKubo</h1>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Culpa eveniet omnis ut qui, aspernatur neque unde velit officia molestias nostrum?</p><br>
						<p><a href="about.php">Read More</a></p>
					</div>
				</div>
			</div>
		</div>
	<!-- Content -->
		<div class="main-content">
			<div class="main-content-grid">
				<div class="announcement">
					<h2><i class="fas fa-bullhorn"></i> Announcement</h2>
<?php
$sql="SELECT title,content,t1.datecreated,username FROM tblannouncement AS t1
LEFT JOIN tbluser
	ON userid = author
ORDER BY announceid DESC
LIMIT 1";
$result= $conn->query($sql);
while($row=$result->fetch_object()){
	$title = $row->title;
	$content = $row->content;
	$date = date('D, F j Y g:i A',strtotime($row->datecreated));
	$author = $row->username;

	echo '<h2>'.$title.'</h2>
	<p>Posted on: '.$date.' by: <a href="profile.php?name='.$author.'">'.$author.'</a></p>
	<div class="announce-content">'.nl2br($content).'</div>';
}
?>
				</div>
				<div class="advertisement">
					<div class="advertisement-inner">
						<img src="img/neiman_marcus.gif" alt="advertisement">
					</div>
				</div>
				<div class="content-body">
				</div>
				<div class="sidebar">
					<h3>Newest Users</h3>
<?php
//newest users
	$sql="SELECT username,imgpath,datecreated FROM tbluser ORDER BY userid DESC LIMIT 10";
	$result=$conn->query($sql);
	while($row=$result->fetch_object()){

		$name=$row->username;
		$img=$row->imgpath;
		if(!$img){
			$img='img/default.png';
		}
		$date = date("M j, Y", strtotime($row->datecreated));
		$time = date("g:i:s A", strtotime($row->datecreated));

		echo'
		<div>
		<ul class="drop-ul"><li><a href="profile.php?name='.$name.'"><div class="drop-tn"><img src="'.$img.'"></div><p>'.$name.'</a></p><small>Joined: '.$date.'</small><br>
			<small>'.$time.'</small>
		<li></ul>
		</div>';
	}

?>
				</div>
				<div class="sidebar2">
					<h3>Browse Category</h3>
				</div>
				<div class="sidebar3">
					<h3>Popular Users</h3>
<?php
//Popular Users
	$count=1;
	$sql="SELECT username,imgpath,datecreated FROM tbluser ORDER BY profileviews DESC LIMIT 10";
	$result=$conn->query($sql);
	while($row=$result->fetch_object()){

		$name=$row->username;
		$img=$row->imgpath;
		if(!$img){
			$img='img/default.png';
		}
		$date = date("M j, Y", strtotime($row->datecreated));
		$time = date("g:i:s A", strtotime($row->datecreated));

		echo'
		<div>
		<ul class="drop-ul"><li><a href="profile.php?name='.$name.'"><div class="drop-tn"><img src="'.$img.'"></div><p>';
		if ($count==1){
			echo'<i class="fas fa-trophy gold"></i>';
		}else if ($count==2){
			echo'<i class="fas fa-trophy silver"></i>';
		}else if ($count==3){
			echo'<i class="fas fa-trophy bronze"></i>';
		}else{
			echo '#'.$count.' ';
		}
		echo ' '.$name.'</a></p><small>Joined: '.$date.'</small><br>
			<small>'.$time.'</small>
		<li></ul>
		</div>';
		$count++;
	}

?>		
				</div>
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
		modal();
		ajaxLogin();
	</script>
</body>
</html>