<?php
session_start();
include'functions.php';
include'connection.php';
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
<body>
	<div class="main-container">
	<!-- Header -->
	<?php
		addheader();
	?>
	<!-- Main Content -->
	<div class="other-content">
		<h1><i class="fas fa-search"></i>Search</h1>
		<ul class="search-ul">
<?php
//get criteria
if(isset($_GET['criteria'])){
	$crit = $_GET['criteria'];

if($crit==1){

//if criteria is forum

if(isset($_GET['search-text'])){
	$search= $_GET['search-text'];

	$sql = "SELECT forumid FROM tblforum WHERE name LIKE '%$search%'";
	$result=$conn->query($sql);

	if($result->num_rows==0){
		echo'No results found';
	}
	$rows=$result->num_rows;
	$page_rows = 10;
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

	$sql = "SELECT forumid,name,(views+subscriber) AS popular FROM tblforum WHERE name LIKE '%$search%' ORDER BY popular DESC $limit";
	
	$textline1 = "Result (<b>$rows</b>)";
	$textline2 = "Page <b>$pagenum</b> of <b>$last</b>";
	$paginationCtrls = '';
	if($last != 1){
		if ($pagenum > 1) {
	        $previous = $pagenum - 1;
			$paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?criteria='.$crit.'&search-text='.$search.'&pn='.$previous.'">Previous</a> &nbsp; &nbsp; ';
			for($i = $pagenum-4; $i < $pagenum; $i++){
				if($i > 0){
			        $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?criteria='.$crit.'&search-text='.$search.'&pn='.$i.'">'.$i.'</a> &nbsp; ';
					}
			    }
		    }
		    $paginationCtrls .= ''.$pagenum.' &nbsp; ';
			for($i = $pagenum+1; $i <= $last; $i++){
				$paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?criteria='.$crit.'&search-text='.$search.'&pn='.$i.'">'.$i.'</a> &nbsp; ';
				if($i >= $pagenum+4){
					break;
				}
			}
			    if ($pagenum != $last) {
		        $next = $pagenum + 1;
		        $paginationCtrls .= ' &nbsp; &nbsp; <a href="'.$_SERVER['PHP_SELF'].'?criteria='.$crit.'&search-text='.$search.'&pn='.$next.'">Next</a> ';
		    }
		}
	 echo'<h2>  '.$textline1.'</h2>
	  <p>  '.$textline2.' </p>
	  <div id="pagination_controls"> '.$paginationCtrls.'</div>';

	$result = $conn->query($sql);
	while($row = $result->fetch_object()){
		$id = $row->forumid;
		$name = $row->name;

		echo'<ul class="drop-ul2"><li><a href="forums.php?id='.$id.'">'.$name.'</li></ul>';
	}
}

}else if($crit==2){

//if criteria is user

if(isset($_GET['search-text'])){
	$search= $_GET['search-text'];


	$sql="SELECT userid FROM tbluser WHERE username LIKE '%$search%'";

	$result=$conn->query($sql);

	if($result->num_rows==0){
		echo'No results found';
	}
	$rows=$result->num_rows;
	$page_rows = 10;
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

	$sql="SELECT username,imgpath,datecreated FROM tbluser WHERE username LIKE '%$search%' ORDER BY lastonline DESC $limit";
	
	$textline1 = "Result (<b>$rows</b>)";
	$textline2 = "Page <b>$pagenum</b> of <b>$last</b>";
	$paginationCtrls = '';
	if($last != 1){
		if ($pagenum > 1) {
	        $previous = $pagenum - 1;
			$paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?criteria='.$crit.'&search-text='.$search.'&pn='.$previous.'">Previous</a> &nbsp; &nbsp; ';
			for($i = $pagenum-4; $i < $pagenum; $i++){
				if($i > 0){
			        $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?criteria='.$crit.'&search-text='.$search.'&pn='.$i.'">'.$i.'</a> &nbsp; ';
					}
			    }
		    }
		    $paginationCtrls .= ''.$pagenum.' &nbsp; ';
			for($i = $pagenum+1; $i <= $last; $i++){
				$paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?criteria='.$crit.'&search-text='.$search.'&pn='.$i.'">'.$i.'</a> &nbsp; ';
				if($i >= $pagenum+4){
					break;
				}
			}
			    if ($pagenum != $last) {
		        $next = $pagenum + 1;
		        $paginationCtrls .= ' &nbsp; &nbsp; <a href="'.$_SERVER['PHP_SELF'].'?criteria='.$crit.'&search-text='.$search.'&pn='.$next.'">Next</a> ';
		    }
		}
	 echo'<h2>  '.$textline1.'</h2>
	  <p>  '.$textline2.' </p>
	  <div id="pagination_controls"> '.$paginationCtrls.'</div>';



	$result=$conn->query($sql);

	while($row=$result->fetch_object()){
		$name = $row->username;
		$img = $row->imgpath;
		$date = date("M j, Y", strtotime($row->datecreated));
		if (!$img){
			$img='img/default.png';
		}

		echo'<li><a href="profile.php?name='.$name.'">
		<div class="sch-tn">
		<img src="'.$img.'">
		</div>
		<p>'.$name.'</a></p>
		<p>Joined: '.$date.'</p>
		<li>';

	}
}
}else if($crit==3){

//if criteria is product
if(isset($_SESSION['id'])){
$uid = $_SESSION['id'];
}

if(isset($_GET['search-text'])){
	$search= $_GET['search-text'];

	$sql = "SELECT postid FROM tblpost WHERE title LIKE '%$search%'";
	$result=$conn->query($sql);

	if($result->num_rows==0){
		echo'No results found';
	}
	$rows=$result->num_rows;
	$page_rows = 10;
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
	$sql = "SELECT postid,tblpost.forumid,upvoteid,downvoteid,name,img,tblpost.title,tblpost.datecreated,username,imgpath,price,comments,score,(((tblpost.views*0.2) + (score*0.8))/((NOW()-tblpost.datecreated)/331536000)) AS trending FROM tblpost
	LEFT JOIN tblforum
		ON tblpost.forumid = tblforum.forumid
	LEFT JOIN tbluser
		ON userid = starter
	LEFT JOIN tblupvotepost
		ON postid = tblupvotepost.post
	LEFT JOIN tbldownvotepost
		ON postid = tbldownvotepost.post
	WHERE tblpost.title LIKE '%$search%'
	GROUP BY postid
	ORDER BY trending DESC $limit";
	
	$textline1 = "Result (<b>$rows</b>)";
	$textline2 = "Page <b>$pagenum</b> of <b>$last</b>";
	$paginationCtrls = '';
	if($last != 1){
		if ($pagenum > 1) {
	        $previous = $pagenum - 1;
			$paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?criteria='.$crit.'&search-text='.$search.'&pn='.$previous.'">Previous</a> &nbsp; &nbsp; ';
			for($i = $pagenum-4; $i < $pagenum; $i++){
				if($i > 0){
			        $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?criteria='.$crit.'&search-text='.$search.'&pn='.$i.'">'.$i.'</a> &nbsp; ';
					}
			    }
		    }
		    $paginationCtrls .= ''.$pagenum.' &nbsp; ';
			for($i = $pagenum+1; $i <= $last; $i++){
				$paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?criteria='.$crit.'&search-text='.$search.'&pn='.$i.'">'.$i.'</a> &nbsp; ';
				if($i >= $pagenum+4){
					break;
				}
			}
			    if ($pagenum != $last) {
		        $next = $pagenum + 1;
		        $paginationCtrls .= ' &nbsp; &nbsp; <a href="'.$_SERVER['PHP_SELF'].'?criteria='.$crit.'&search-text='.$search.'&pn='.$next.'">Next</a> ';
		    }
		}
	 echo'<h2>  '.$textline1.'</h2>
	  <p>  '.$textline2.' </p>
	  <div id="pagination_controls"> '.$paginationCtrls.'</div>';

	$result = $conn->query($sql);

	echo'<ul id="forum-list">';

	while($row = $result->fetch_object()){
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
	echo '</ul>';
}
}
}
mysqli_close($conn);
?>
		</ul>
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
