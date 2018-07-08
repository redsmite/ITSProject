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
					<div class="tab-control">
						<a id="tab1" onclick="showallforum()">All</a>
						<?php
						if(isset($_SESSION['id'])){
							echo'<a id="tab2" onclick="showsubforum()">Subscribed</a>';
							}else{
							echo'<a id="tab2" onclick="showlogin()">Subscribed</a>';
							}
						?>
					</div><br>
					<div id="list-container">
						<h2>Trending Posts</h2>
						<ul id="forum-list">
<?php
$sql = "SELECT postid,tblpost.forumid,upvoteid,downvoteid,name,img,tblpost.title,tblpost.datecreated,username,imgpath,price,comments,score,(((tblpost.views*0.2) + (score*0.8))/((NOW()-tblpost.datecreated)/331536000)) AS trending FROM tblpost
	LEFT JOIN tblforum
		ON tblpost.forumid = tblforum.forumid
	LEFT JOIN tbluser
		ON userid = starter
	LEFT JOIN tblupvotepost
		ON postid = tblupvotepost.post
	LEFT JOIN tbldownvotepost
		ON postid = tbldownvotepost.post
	GROUP BY postid
	ORDER BY trending DESC
	LIMIT 25";
	$result = $conn->query($sql);
	while($row=$result->fetch_object()){
		$id = $row->postid;
		$forumid = $row->forumid;
		$forum = $row->name;
		$ptitle = $row->title;
		$name = $row->username;
		$comments = $row->comments;
		$date = $row->datecreated;
		$score = $row->score;
		$upvote = $row->upvoteid;
		$downvote = $row->downvoteid;
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
				'.time_elapsed_string($date).'
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
					<h3>Popular Market</h3>
					<ul id="forum-list">
<?php
//Popular Forums
	$sql = "SELECT forumid,name, (views+subscriber) AS popular FROM tblforum ORDER BY popular DESC LIMIT 15";
	$result = $conn->query($sql);
	while($row=$result->fetch_object()){
		$id = $row->forumid;
		$name = $row->name;

		echo'<li><a href="forums.php?id='.$id.'">'. $name.'</a></li>';
	}
?>
				</ul>
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
		let tab1 = document.getElementById('tab1');
		tab1.classList.add('style-tab');

		modal();
		ajaxLogin();
	</script>
</body>
</html>