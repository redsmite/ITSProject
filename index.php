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
		<div class="main-search">
			<div class="browse-category">Browse Category</div>
			<form id="main-search-form">
				<div>
					<input type="text" id="main-search" placeholder="Search for Products...">
					<select>
						<option disabled selected>Select Category</option>
						<option>Fruits</option>
						<option>Vegetables</option>
					</select>
					<i class="fas fa-search"></i>
				</div>
			</form>
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
					<h3>Products</h3>
					<div class="product"></div>
					<div class="product"></div>
					<div class="product"></div>
					<div class="product"></div>
					<div class="product"></div>
					<div class="product"></div>
				</div>
				<div class="sidebar">
					<h3>Sidebar</h3>
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
		let search= document.getElementById('main-search');
		search.focus();
		modal();
		ajaxLogin();
	</script>
</body>
</html>