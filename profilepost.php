<?php
session_start();
include'functions.php';
addSidebar();
addLogin();
setupCookie();
updateStatus();
chattab();
require_once'connection.php';
$name=$_GET['name'];
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
			<h1><a class="btp" href="profile.php?name=<?php echo $name ?>">Back to <?php echo $name ?>'s Profile</a></h1>

<ul id="forum-list">
<?php
if(isset($_SESSION['id'])){
	$uid = $_SESSION['id'];
}

$sql="SELECT userid FROM tbluser WHERE username='$name'";
$result=$conn->query($sql);
$row=$result->fetch_object();
$rid=$row->userid;

$sql = "SELECT postid FROM tblpost
	LEFT JOIN tblforum
		ON tblpost.forumid = tblforum.forumid
	LEFT JOIN tbluser
		ON userid = starter
	WHERE starter='$rid'
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


$sql = "SELECT postid,tblpost.forumid,name,imgpath,tblpost.title,tblpost.datecreated,username,img,price,comments,score FROM tblpost
	LEFT JOIN tblforum
		ON tblpost.forumid = tblforum.forumid
	LEFT JOIN tbluser
		ON userid = starter
	WHERE starter='$rid'
	ORDER BY postid DESC
	$limit";
	$result = $conn->query($sql);

$textline1 = "<i class='fas fa-comments'></i> Post by ".$_GET['name']." (<b>$rows</b>)";
$textline2 = "Page <b>$pagenum</b> of <b>$last</b>";
$paginationCtrls = '';
if($last != 1){
	if ($pagenum > 1) {
        $previous = $pagenum - 1;
		$paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?name='.$_GET['name'].'&pn='.$previous.'">Previous</a> &nbsp; &nbsp; ';
		// Render clickable number links that should appear on the left of the target page number
		for($i = $pagenum-4; $i < $pagenum; $i++){
			if($i > 0){
		        $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?name='.$_GET['name'].'&pn='.$i.'">'.$i.'</a> &nbsp; ';
			}
	    }
    }
    $paginationCtrls .= ''.$pagenum.' &nbsp; ';
	for($i = $pagenum+1; $i <= $last; $i++){
		$paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?name='.$_GET['name'].'&pn='.$i.'">'.$i.'</a> &nbsp; ';
		if($i >= $pagenum+4){
			break;
		}
	}
	    if ($pagenum != $last) {
        $next = $pagenum + 1;
        $paginationCtrls .= ' &nbsp; &nbsp; <a href="'.$_SERVER['PHP_SELF'].'?name='.$_GET['name'].'&pn='.$next.'">Next</a> ';
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
		$date = $row->datecreated;
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