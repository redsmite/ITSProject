<?php
session_start();
include'functions.php';
require_once('connection.php');

if(!isset($_GET['id'])){
	die('This forum doesn\'t exists.');
}

//Get forum information
$forums = $_GET['id'];
if(isset($_SESSION['id'])){
$uid = $_SESSION['id'];
}

$sql = "SELECT username,forumid,title,name,description,t1.datecreated,subscriber FROM tblforum AS t1
LEFT JOIN tbluser
	ON userid = creator
WHERE forumid='$forums'";
$result = $conn->query($sql);	
$fetch = $result->fetch_object();

if($fetch){
$fid = $fetch->forumid;
$title = $fetch->title;
$name = $fetch->name;
$desc = $fetch->description;
$subcount = $fetch->subscriber;
$date = date("M j, Y", strtotime($fetch->datecreated));
$creator = $fetch->username;
}
//forum views

$sql = "UPDATE tblforum SET views=views+1 WHERE forumid='$forums'";
$result= $conn->query($sql);

addSidebar();
addLogin();
setupCookie();
updateStatus();
chattab();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/style.css">
  	<link rel="stylesheet" href="css/fontawesome-all.css">
	<meta charset="UTF-8">
	<title><?php if($fetch){echo $title;}else{ companytitle();}?></title>
</head>
<body>
	<div class="main-container">
	<?php
		addheader();
	?>
	<div class="other-content">
		<div class="forum-grid">
			<div class="main-forum">
				<h1><?php 
				if($fetch){
				echo '<a id="top-title" href="forums.php?id='.$fid.'">'.$name.'</a>';} ?></h1>

				<ul id="forum-list">
<?php
$sql = "SELECT postid FROM tblpost
	LEFT JOIN tblforum
		ON tblpost.forumid = tblforum.forumid
	LEFT JOIN tbluser
		ON userid = starter
	WHERE tblpost.forumid='$forums'
	ORDER BY postid DESC";
$result=$conn->query($sql);

$rows=$result->num_rows;
$page_rows = 25;
$last = ceil($rows/$page_rows);
if($last < 1){
	$last = 1;
}
$pagenum = 1;
if(isset($_GET['pn'])){
	$pagenum = preg_replace('#[^0-9]#', '', $_GET['pn']);
}
if ($pagenum < 1) { 
    $pagenum = 1; 
} else if ($pagenum > $last) { 
    $pagenum = $last; 
}
$limit = 'LIMIT ' .($pagenum - 1) * $page_rows .',' .$page_rows;


$sql = "SELECT postid,tblpost.forumid,name,tblpost.title,tblpost.datecreated,username,imgpath,img,price,comments,score,(((tblpost.views*0.2) + (score*0.8))/((NOW()-tblpost.datecreated)/331536000)) AS trending FROM tblpost
	LEFT JOIN tblforum
		ON tblpost.forumid = tblforum.forumid
	LEFT JOIN tbluser
		ON userid = starter
	WHERE tblpost.forumid='$forums'
	ORDER BY trending DESC $limit";
	$result = $conn->query($sql);

$textline1 = "<i class='fas fa-comments'></i> Post (<b>$rows</b>)";
$textline2 = "Page <b>$pagenum</b> of <b>$last</b>";
$paginationCtrls = '';
if($last != 1){
	if ($pagenum > 1) {
        $previous = $pagenum - 1;
		$paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?id='.$forums.'&pn='.$previous.'">Previous</a> &nbsp; &nbsp; ';
		// Render clickable number links that should appear on the left of the target page number
		for($i = $pagenum-4; $i < $pagenum; $i++){
			if($i > 0){
		        $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?id='.$forums.'&pn='.$i.'">'.$i.'</a> &nbsp; ';
			}
	    }
    }
    $paginationCtrls .= ''.$pagenum.' &nbsp; ';
	for($i = $pagenum+1; $i <= $last; $i++){
		$paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?id='.$forums.'&pn='.$i.'">'.$i.'</a> &nbsp; ';
		if($i >= $pagenum+4){
			break;
		}
	}
	    if ($pagenum != $last) {
        $next = $pagenum + 1;
        $paginationCtrls .= ' &nbsp; &nbsp; <a href="'.$_SERVER['PHP_SELF'].'?id='.$forums.'&pn='.$next.'">Next</a> ';
    }
}
 echo'<h2>  '.$textline1.'</h2>
  <p>  '.$textline2.' </p>
  <div id="pagination_controls"> '.$paginationCtrls.'</div>';



	while($row=$result->fetch_object()){
		$id = $row->postid;
		$forumid = $row->forumid;
		$forum = $row->name;
		$ptitle = $row->title;
		$name = $row->username;
		$comments = $row->comments;
		$datep = $row->datecreated;
		$score = $row->score;
		$flair= $row->imgpath;
		if(!$flair){
			$flair='img/default.png';
		}
		$pimg=$row->img;
		if(!$pimg){
			$pimg='img/noimage.png';
		}
		$price = $row->price;

		echo '<li value="'.$id.'">
 		<div class="forum-post-grid">';
//login'd user can only vote
 		if(isset($_SESSION['id'])){
 		//check upvote/downvote
		$sql2 = "SELECT upvoteid FROM tblupvotepost WHERE user='$uid' and post='$id'";
		$result2 = $conn->query($sql2);
		$upvote = $result2->num_rows;

		$sql3 = "SELECT downvoteid FROM tbldownvotepost WHERE user='$uid' and post='$id'";
		$result3 = $conn->query($sql3);
		$downvote = $result3->num_rows;
 		
 		echo'
 		<div class="vote">';
 			
 			if(!$upvote){
 			echo'<div id="up-'.$id.'" style="color:gray;" value="'.$id.'" onclick="upvotepost(this)" class="upvote"><i class="far fa-thumbs-up"></i></div>';
 			}else{
 			echo'<div id="up-'.$id.'" style="color:orangered;" value="'.$id.'" onclick="upvotepost(this)" class="upvote"><i class="far fa-thumbs-up"></i></div>';
 			}
			
 			if($score<0){
 				$score=0;
 			}
			echo'
			<div id="score-'.$id.'">'.$score.'</div>';
			

			if(!$downvote){
			echo'
			<div id="down-'.$id.'" style="color:gray;" value="'.$id.'" onclick="downvotepost(this)" class="downvote"><i class="far fa-thumbs-down"></i>
			</div>';
			}else{
			echo'
			<div id="down-'.$id.'" style="color:blue;" value="'.$id.'" onclick="downvotepost(this)" class="downvote"><i class="far fa-thumbs-down"></i>
			</div>';
			}

			echo'
		</div>';

		}else{
			echo'
		<div class="vote">
 			<div class="upvote" style="color:gray;" onclick="showlogin()">
			<i class="far fa-thumbs-up"></i>
			</div>';
			
 			if($score<0){
 				$score=0;
 			}
			echo'<div>'.$score.'</div>
			<div class="downvote" style="color:gray;" onclick="showlogin()">
			<i class="far fa-thumbs-down"></i>
		</div>
		</div>';
		}
		echo '<div class="post-image">
			<img src='.$pimg.'>
		</div>';
		echo'<div class="post-right">
		<p class="main-forum-title"><a href="reply.php?id='.$forumid.'&thread='.$id.'">'.$ptitle.'</a></p>';
		
		$sql3 = "SELECT upvoteid FROM tblupvotepost WHERE post='$id'";
			$result3 = $conn->query($sql3);
			$upvotecount = $result3->num_rows;

			$sql4 = "SELECT downvoteid FROM tbldownvotepost WHERE post='$id'";
			$result4 = $conn->query($sql4);
			$downvotecount = $result4->num_rows;
			
			$total = $upvotecount + $downvotecount;
			if ($total==0){
				$upvotecount = 1;
				$total = 2;
			}
			$percent = round($upvotecount/$total * 100);

			starsystem($percent);
		echo'<div class="price">PHP: '.number_format($price,2).'</div>';

		echo'<div class="second-line">

			<div class="from">
				From: <a href="forums.php?id='.$forumid.'">
				'.$forum.'</a> By: 
			</div>

			<div class="by">
				<a href="profile.php?name='.$name.'">
				<p><div class="flair">
					<img src="'.$flair.'">
				</div>'.$name.'</a> 
			</div>

			<div class="when">
				'.time_elapsed_string($datep).'
			</div>

		</div>
		<div class="com">
			<p>(<a href="reply.php?id='.$forumid.'&thread='.$id.'">'.$comments.' Comments</a>)</p>
		</div>
		</div>
		</div>
		</li>';
	}
?>
			</ul>
			</div>
			<div class="forum-sidebar">
				<?php
					forumcontrols();
				?>
				<div class="forum-panel">
					<h2 id="forum-name"><?php if($fetch){ echo $title;}?></h2>
					<div class="" id="forum-date"><?php if($fetch){echo 'Created: '.$date;} ?></div>
					<p id="description"><?php if($fetch){echo nl2br($desc);} ?></p>
					<p id="subscriber-count"><?php if($fetch){echo $subcount;} ?> Subscribers</p>
					<p id="users-count">1 Users here now</p>
					<?php
						if($fetch){

							echo'Creator: <a class="creator" href=
							profile.php?='.$creator.'">'.$creator.'</a>';
							if(isset($_SESSION['id'])){
								if($creator==$_SESSION['name']){
									echo'<br><a class="forum-panel-button" href="forumpanel.php?id='.$forums.'">Forum Panel</a>';
								}
							}
						}
					?>
				</div>
			</div>
		</div>
	</div>
	<?php
		addfooter();
	?>
	</div>
	<?php if(!isset($_SESSION['id'])){echo'<div id="create-forum-form"></div><div id="create-post-form"></div>';}?>
	<script src="js/main.js"></script>
	<script>
		newForumForm();
		newPostForm();
		modal();
		ajaxLogin();
	</script>
</body>
</html>