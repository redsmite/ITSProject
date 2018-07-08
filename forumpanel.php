<?php
session_start();
include'connection.php';
include'functions.php';
user_access();
updateStatus();
chattab();

if(!isset($_GET['id'])){
	die('This forum doesn\'t exists.');
}

//Get forum information
$forums = $_GET['id'];
if(isset($_SESSION['id'])){
$uid = $_SESSION['id'];
}

$sql = "SELECT name,title,description,userid FROM tblforum AS t1
LEFT JOIN tbluser
	ON userid = creator
WHERE forumid='$forums'";
$result = $conn->query($sql);	
$fetch = $result->fetch_object();
if($fetch){
$name = $fetch->name;
$title = $fetch->title;
$desc = $fetch->description;
$creator = $fetch->userid;

if($creator!=$uid){
	die('You cannot access this page.');
}
}else{
	die('This page doesn\'t exists.');
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
<body>
	<div class="main-container">
	<!-- Header -->
	<?php
		addheader();
	?>
	<!-- Edit Form -->
		<div class="other-content">
			<h1><a class="btp" href="forums.php?id=<?php echo $forums?>">Back to <?php echo $name ?></a></h1>
			<div class="edit-form">
				<form action="#" method="post">
					<div>
						<h4>You cannot change your forum name.</h4><br>
						<p>Title</p>
						<small><i>*This will appear on the browser toolbar.</i></small>
						<input type="text" required value="<?php echo $title ?>" name="forum-title">
					</div>
					<div>
						<p>Description</p>
						<small><i>*Brief description about the forum or set some rules here.</i></small>
						<textarea name="forum-desc" id="forum-desc" required><?php echo $desc ?></textarea>
					</div>
					<div>
						<input type="submit" value="submit" name="forum-submit">
<?php
	if(isset($_POST['forum-submit'])){
		$title = $_POST['forum-title'];
		$desc = $_POST['forum-desc'];

		$sql = "UPDATE tblforum SET title='$title',description='$desc' WHERE forumid='$forums'";
		$result = $conn->query($sql);


		echo'<script>window.location.href = "forums.php?id='.$forums.'"</script>';
	}
?>
					</div>
				</form>
			</div>
		</div>
	<!-- Footer -->
		<?php
			addfooter();
		?>
	<!-- End of Container -->
	</div>
	<script src="js/main.js"></script>
</body>
</html>