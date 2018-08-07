<?php
	session_start();
	include'functions.php';
	require_once'connection.php';
	addSidebar();
	addLogin();
	setupCookie();
	updateStatus();
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
<body onscroll="scrollOpacity()">
	<div class="main-container">
	<!-- Header -->
	<?php
		addheader();
	?>
	<!-- Main Search -->
		<div class="main-search">
			<div id="browse-category" onclick="showCategory()">Browse Category
			</div>
			<div id="category-modal" onclick="hideCategory()"></div>
			<div id="category-slide">
				<h2 class="close-heading" onclick="hideCategory()">All Categories <i style="float:right; padding-right:10px" class="fas fa-times"></i></h2>
<?php
	$sql = "SELECT categoryid,category FROM tblcategory WHERE status =1 ORDER BY category";
	$result = $conn->query($sql);
	while($row = $result->fetch_object()){
		$category = $row->category;
		$id = $row->categoryid;

		echo '<p value="'.$id.'" onclick="browseCategory(this)">'.$category.'</p>';
	}
?>
			</div>
			<form action="searchproduct.php" method="get" id="main-search-form">
				<div>
					<input type="text" name="search" id="main-search" autocomplete="off" placeholder="Search for Products..." onkeyup="searchProduct()">
					<select  id="main-select" name="select">
						<option disabled selected>Select Category</option>
<?php
// Select Category
	$sql = "SELECT categoryid,category FROM tblcategory WHERE status =1 ORDER BY category";
	$result = $conn->query($sql);
	while($row = $result->fetch_object()){
		$category = $row->category;
		$id = $row->categoryid;

		echo '<option value="'.$id.'">'.$category.'</option>';
	}
?>
					</select>
					<i class="fas fa-search"></i>
				</div>
			</form>
		</div>
<!-- Main-Search Modal -->
<div id="main-search-panel">
	
</div>
<div id="main-search-modal" onclick="hideSearchPanel()"></div>
	<!-- Content -->
		<div class="main-content">
			<div class="main-content-grid">
				<div class="announcement">
<?php
$sql="SELECT announceid,title,content,t1.datecreated,author,username FROM tblannouncement AS t1
LEFT JOIN tbluser
	ON userid = author
ORDER BY announceid DESC
LIMIT 1";
$result= $conn->query($sql);
$row=$result->fetch_object();
	$announceid = $row->announceid;
	$title = $row->title;
	$content = $row->content;
	$date = date('D, F j Y g:i A',strtotime($row->datecreated));
	$userid = $row->author;
	$author = $row->username;

	echo '<h2 id="announcement-title">'.$title.'</h2>
	<p>Posted on: '.$date.' by: <a href="profile.php?id='.$userid.'">'.$author.'</a></p>
	<div class="announce-content">'.substr(nl2br($content), 0, 450);
	if(strlen($content) > 450 ){
		echo'...';
	}

	echo'<br>
		<a class="center" id="announcement-comment" href="announcement.php">Read More</a>';
$sql = "SELECT commentannid FROM tblcommentann WHERE announceid = '$announceid'";
$result= $conn->query($sql);
$comments = $result->num_rows;
echo'<p>Comments ('.number_format($comments).')</p>
		</div>
		';
?>
				</div>
				<div class="advertisement">
					<a href="about.php">
					<div class="advertisement-inner">
						<img src="img/logo.jpg" alt="advertisement">
					</div>
					</a>
				</div>
				<div class="farm-select-div">
					<h3><i class="fas fa-leaf"></i> Farms</h3>
					<ul>
<?php
$sql = "SELECT farmid, farmname FROM tblfarm WHERE status=1";
$result = $conn->query($sql);
while($row = $result->fetch_object()){
	$farmid = $row->farmid;
	$farmname = $row->farmname;
	echo '<li><a href="searchfarm.php?id='.$farmid.'">'.$farmname.'</a></li>';
}
?>
					</ul>
				</div>
				<div class="content-body">
					<h2><i class="fas fa-leaf"></i> Freshly Picked<br>
					<small>What's New</small></h2>
<?php
$string = 'WHERE is_approved = 1 AND is_available = 1
	ORDER BY dateposted DESC
	LIMIT 16';
showProduct($string);
?>
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