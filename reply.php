<?php
session_start();
include'functions.php';
require_once('connection.php');

if(!isset($_GET['id'])){
	die('This forum doesn\'t exists.');
}

if(!isset($_GET['thread'])){
	die('This post doesn\'t exists.');
}

//Get forum information
if(isset($_SESSION['id'])){
	$uid=$_SESSION['id'];
}
$forums = $_GET['id'];
$thread = $_GET['thread'];

$sql = "SELECT forumid,title,name,description,t1.datecreated,subscriber,username FROM tblforum AS t1
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

$sql = "UPDATE tblforum SET views=views+1 WHERE forumid='$thread'";
$result= $conn->query($sql);

//Post views

$sql = "UPDATE tblpost SET views=views+1 WHERE postid='$forums'";
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
				<h1><?php if($fetch){echo '<a id="top-title" href="forums.php?id='.$fid.'">'.$name.'</a>';} ?></h1>
				<ul id="forum-list">
<?php
	$sql = "SELECT postid,tblpost.forumid,name,imgpath,tblpost.title,tblpost.datecreated,tblpost.description,tblpost.score ,username,img,price,comments FROM tblpost
 	LEFT JOIN tblforum
 		ON tblpost.forumid = tblforum.forumid
 	LEFT JOIN tbluser
 		ON userid = starter
 	WHERE tblforum.forumid = '$forums' AND postid='$thread'";
 	$result = $conn->query($sql);
 	$row=$result->fetch_object();
 		if($row){
 		$id = $row->postid;
 		$forumid = $row->forumid;
 		$forum = $row->name;
 		$ptitle = $row->title;
 		$name = $row->username;
 		$pdesc = $row->description;
 		$score = $row->score;
 		$comments = $row->comments;
 		$pdate = $row->datecreated;
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

		//check upvote / downvote
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
				'.time_elapsed_string($date).'
			</div>

		</div>
		<div class="com">
			<p>'.$comments.' Comments</p>
		</div>
		</div>
		</div>
		</li>';
 	}
?>
			</ul>
			<div class="add-to-cart"><i class="fas fa-shopping-cart"></i> Add to Cart</div>
			<div class="text-content">
				<?php echo nl2br($pdesc) ?>
			</div>
			<div class="showcase-item">
				<img src="<?php echo $pimg?>">
			</div>
			<div class="reply-form">
				<form id="reply-submit">
					<div>
						<textarea id="reply-text"></textarea>
					</div>
					<div>
						<input type="submit" value="Submit">
					</div>
				</form>
			</div>
			</div>
			<div class="forum-sidebar">
				<?php
					forumcontrols();
				?>
				<div class="forum-panel">
					<p>This Post was submitted on:<br>
					<b><?php if($fetch){echo date("M j, Y", strtotime($pdate));}?></b></p>
					<?php
	// Getting post score
	if($fetch){
	$sql = "SELECT upvoteid FROM tblupvotepost WHERE post='$id'";
	$result = $conn->query($sql);
	$upvotecount = $result->num_rows;

	$sql = "SELECT downvoteid FROM tbldownvotepost WHERE post='$id'";
	$result = $conn->query($sql);
	$downvotecount = $result->num_rows;

	if ($score == 0 or $score == 1 or $score== -1){
		echo '<h3>'.$score.' Point</h3>';
	} else{
		echo '<h3>'.$score.' Points</h3>';	
	}
	$total = $upvotecount + $downvotecount;
	if ($total==0){
		$upvotecount = 1;
		$total = 2;
	}
	$percent = round($upvotecount/$total * 100);
	echo'<center>';
	starsystem($percent);
	echo'</center>
	('.$percent.'% Liked)';
	}
?>
					</p>
					<hr>
					<h2 id="forum-name"><?php if($fetch){echo $title;}?></h2>
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