<?php
session_start();
require_once'connection.php';
include'functions.php';

if(isset($_POST['title'])){
	$title = $conn->real_escape_string($_POST['title']);
	$name = $conn->real_escape_string($_POST['name']);
	$desc = $conn->real_escape_string($_POST['desc']);
	$id = $_SESSION['id'];

	$error="";


	if (preg_match('/[^A-Z]/i',$name)){
		$error.='<p><i class="fas fa-exclamation-circle"></i>Market name must not contain special characters or spaces</p>';
	}

	if(strlen($name) > 25)
	{
	    $error.='<i class="fas fa-exclamation-circle"></i>Market name length is too long.<br>';
	}

// Test if market name exists
	$sql= "SELECT forumid FROM tblforum WHERE name = '$name'";
	$result = $conn->query($sql);
	$exist = $result->num_rows;
	
	if($exist!=0){
		$error.='<p><i class="fas fa-exclamation-circle"></i>Market name already exists';
	}

	if(!$error){

	$sql = "INSERT INTO tblforum (title,name,datecreated,description,creator) VALUES('$title','$name',NOW(),'$desc','$id')";
	$result = $conn->query($sql);

	$sql="SELECT COALESCE(MAX(forumid), 0) AS newForumID FROM tblforum";
	$result=$conn->query($sql);
	$row=$result->fetch_object();
	$forumid=$row->newForumID;

	echo $forumid;
	}else{
		echo $error;
	}
}

if(isset($_POST['newpost'])){
	$title = $conn->real_escape_string($_POST['newpost']);
	$text = $conn->real_escape_string($_POST['text']);
	$forum = $_POST['forum'];
	$user = $_POST['user'];
	$price = $_POST['price'];

	$error="";

	if(strlen($text) < 30)
	{
	    $error.='<i class="fas fa-exclamation-circle"></i>Text must be atleast 30 characters<br>';
	}
	if($price<0){
		$error.='<i class="fas fa-exclamation-circle"></i>You cannot set price to negative<br>';
	}


	if(!$error){

	$sql = "INSERT INTO tblpost(forumid,title,datecreated,description,starter,price) VALUES ('$forum','$title',NOW(),'$text','$user','$price')";
	$result = $conn->query($sql);
	echo 'success';

	}else{
		echo $error;
	}
}

if(isset($_POST['sub'])){
	$id = $_SESSION['id'];
	$sub = $_POST['sub'];

	$sql = "SELECT subid FROM tblsubscribe WHERE subscriber='$id' AND forum='$sub'";

	$result= $conn->query($sql);
	if($result->num_rows==0){

	$sql = "INSERT INTO tblsubscribe(subscriber,forum) VALUES('$id','$sub')";
	$result= $conn->query($sql);
	
	$sql = "UPDATE tblforum SET subscriber=subscriber+1 WHERE forumid='$sub'";
	$result= $conn->query($sql);

	$sql = "SELECT subscriber FROM tblforum WHERE forumid='$sub'";
	$result=$conn->query($sql);
	$row= $result->fetch_object();
	$subcount = $row->subscriber;
	echo $subcount.' Subscribers';

	} else {
	$sql = "DELETE FROM tblsubscribe WHERE subscriber='$id' AND forum='$sub' ";
	$result= $conn->query($sql);

	$sql = "UPDATE tblforum SET subscriber=subscriber-1 WHERE forumid='$sub'";
	$result= $conn->query($sql);
	$sql = "SELECT subscriber FROM tblforum WHERE forumid='$sub'";
	$result=$conn->query($sql);
	$row= $result->fetch_object();
	$subcount = $row->subscriber;
	echo $subcount.' Subscribers';
	}

}

if(isset($_POST['upvote'])){
	$upvote = $_POST['upvote'];
	$id = $_SESSION['id'];

	$sql = "SELECT upvoteid FROM tblupvotepost WHERE user='$id' and post='$upvote'";
	$result = $conn->query($sql);
	$count = $result->num_rows;
	if($count){
	
	$sql = "DELETE FROM tblupvotepost WHERE user='$id' and post='$upvote'";
	$result = $conn->query($sql);

	$sql = "UPDATE tblpost SET score=score-1 WHERE postid='$upvote'";
	$result = $conn->query($sql);

	$sql = "SELECT score FROM tblpost WHERE postid='$upvote'";
	$result= $conn->query($sql);
	$row = $result->fetch_object();
	$score = $row->score;
	if($score<0){
		$score=0;
	}
	echo $score;

	}else{
	
	$sql = "INSERT INTO tblupvotepost (user,post) VALUES ('$id','$upvote')";
	$result = $conn->query($sql);

	$sql = "SELECT downvoteid FROM tbldownvotepost WHERE user ='$id' AND post ='$upvote'";
	$result = $conn->query($sql);
	$downvoted = $result->num_rows;

	if($downvoted){
	$sql = "DELETE FROM tbldownvotepost WHERE user ='$id' AND post ='$upvote' ";
	$result = $conn->query($sql);

	$sql = "UPDATE tblpost SET score=score+2 WHERE postid='$upvote'";
	$result = $conn->query($sql);
	}else{
	$sql = "UPDATE tblpost SET score=score+1 WHERE postid='$upvote'";
	$result = $conn->query($sql);

	}
	$sql = "SELECT score FROM tblpost WHERE postid='$upvote'";
	$result= $conn->query($sql);
	$row = $result->fetch_object();
	$score = $row->score;
	if($score<0){
		$score=0;
	}
	echo $score;
	}
}

if(isset($_POST['downvote'])){
	$downvote = $_POST['downvote'];
	$id = $_SESSION['id'];

	$sql = "SELECT downvoteid FROM tbldownvotepost WHERE user='$id' and post='$downvote'";
	$result = $conn->query($sql);
	$count = $result->num_rows;
	if($count){
		$sql = "DELETE FROM tbldownvotepost WHERE user ='$id' AND post ='$downvote' ";
		$result = $conn->query($sql);

		$sql = "UPDATE tblpost SET score=score+1 WHERE postid='$downvote'";
		$result = $conn->query($sql);

		$sql = "SELECT score FROM tblpost WHERE postid='$downvote'";
		$result= $conn->query($sql);
		$row = $result->fetch_object();
		$score = $row->score;
		if($score<0){
			$score=0;
		}
		echo $score;
	}else{

	$sql = "SELECT upvoteid FROM tblupvotepost WHERE user='$id' and post='$downvote'";
	$result = $conn->query($sql);
	$upvoted = $result->num_rows;

		if(!$upvoted){
		
		$sql = "INSERT INTO tbldownvotepost (user,post) VALUES ('$id','$downvote')";
		$result = $conn->query($sql);
		$sql = "UPDATE tblpost SET score=score-1 WHERE postid='$downvote'";
		$result = $conn->query($sql);


		}else{

		$sql = "DELETE FROM tblupvotepost WHERE user='$id' and post='$downvote'";
		$result = $conn->query($sql);
		
		$sql = "INSERT INTO tbldownvotepost (user,post) VALUES ('$id','$downvote')";
		$result = $conn->query($sql);
		$sql = "UPDATE tblpost SET score=score-2 WHERE postid='$downvote'";
		$result = $conn->query($sql);
		}
		$sql = "SELECT score FROM tblpost WHERE postid='$downvote'";
		$result= $conn->query($sql);
		$row = $result->fetch_object();
		$score = $row->score;

		if($score<0){
 			$score=0;
 		}

		echo $score;
	}
}

if(isset($_POST['taball'])){
	if(isset($_SESSION['id'])){
	$uid = $_SESSION['id'];
	}

	echo '<h2>Trending Posts</h2>
	<ul id="forum-list">';

	$sql = "SELECT postid,tblpost.forumid,upvoteid,downvoteid,name,imgpath,tblpost.title,tblpost.datecreated,username,img,price,comments,score,(((tblpost.views*0.2) + (score*0.8))/((NOW()-tblpost.datecreated)/331536000)) AS trending FROM tblpost
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
	echo '<ul>';
}

if(isset($_POST['tabsub'])){
	$uid = $_SESSION['id'];

	echo '<h2>Subscribed Market Posts</h2>
	<ul id="forum-list">';

	$sql = "SELECT postid,tblpost.forumid,upvoteid,downvoteid,name,imgpath,tblpost.title,tblpost.datecreated,username,img,price,comments,score,(((tblpost.views*0.2) + (score*0.8))/((NOW()-tblpost.datecreated)/331536000)) AS trending FROM tblpost
	LEFT JOIN tblforum
		ON tblpost.forumid = tblforum.forumid
	LEFT JOIN tbluser
		ON userid = starter
	LEFT JOIN tblupvotepost
		ON postid = tblupvotepost.post
	LEFT JOIN tbldownvotepost
		ON postid = tbldownvotepost.post
	WHERE tblforum.forumid IN(SELECT forum FROM tblsubscribe WHERE subscriber='$uid')
	GROUP BY postid
	ORDER BY trending DESC
	LIMIT 25";
	$result = $conn->query($sql);
	$count = $result->num_rows;
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
	if($count==0){
		echo'You aren\'t subscribed to any forums yet...';
	}
	echo '<ul>';
}

?>