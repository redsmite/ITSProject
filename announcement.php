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
	<div class="other-content">
<?php
$sql="SELECT announceid,title,content,t1.datecreated,username,comments FROM tblannouncement AS t1
LEFT JOIN tbluser
	ON userid = author
ORDER BY announceid DESC
LIMIT 1";
$result= $conn->query($sql);
$row=$result->fetch_object();

$id = $row->announceid;
$title = $row->title;
$content = $row->content;
$date = date('D, F j Y g:i A',strtotime($row->datecreated));
$author = $row->username;
$comments = $row->comments;

echo '<h2 id="announcement-title">'.$title.'</h2>
<p>Posted on: '.$date.' by: <a href="profile.php?name='.$author.'">'.$author.'</a></p>
<div class="announce-content">'.nl2br($content);

echo'</div>
<br><hr>
<p>Comments ('.$comments.')</p>';

?>
<form action="#" method="post">
	<textarea id="announcement-text" required name="comment"></textarea>
	<br>
	<input type="submit" value="submit" id="comment-submit" name="submit">
</form>
<hr><br>
<?php
	if(isset($_POST['submit'])){
		$comment = $conn->real_escape_string($_POST['comment']);
		$userid = $_SESSION['id'];
		$announceid = $id;
		
		$sql = "INSERT INTO tblcommentann (announceid,comment,userid,dateposted) VALUES('$announceid','$comment','$userid',NOW())";
		$result = $conn->query($sql);

		$sql = "UPDATE tblannouncement SET comments = comments+1 WHERE announceid = '$announceid'";
		$result = $conn->query($sql);	
	}

	$sql = "SELECT comment,t1.userid,username,imgpath,dateposted FROM tblcommentann AS t1
		LEFT JOIN tbluser AS t2
			ON t1.userid = t2.userid
		WHERE announceid = '$id'
		ORDER BY dateposted DESC";
	$result = $conn->query($sql);
	while($row = $result->fetch_object()){
		$comment = $row->comment;
		$user = $row->username;
		$userid = $row->userid;
		$img = $row->imgpath;
		$date = $row->dateposted;

		echo'<div class="comment-box">
	<div class="comment-header">
	<a class="cm-user" href="profile.php?name='.$user.'">
	<div class="comment-tn">
	<img src="'.$img.'">
	</div>
	'.$user.'</a>
	<small>'.time_elapsed_string($date).'</small>
	</div>
	<div class="comment-body">
	<div class="com-container"><p class="comment-cm">'.nl2br($comment).'</p></div>
	</div>
	</div>';
	}
?>
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