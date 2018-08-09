<?php
date_default_timezone_set('Asia/Manila');

// Random Character Generator
function randomString($length)
{
    return bin2hex(random_bytes($length));
}

function companytitle(){
	if(isset($_SESSION['id'])){
		$id = $_SESSION['id'];
		$conn = new mysqli('localhost','root','','itsproject');
		$sql = "SELECT notifid FROM tblnotif WHERE receiverid = '$id' AND checked = 0";
		$result = $conn->query($sql);
		$count = $result->num_rows;

		$sql = "SELECT pmid FROM tblpm WHERE receiverid = '$id' AND checked = 0";
		$result = $conn->query($sql);
		$count2 = $result->num_rows;
		$total = $count+$count2;

		if($total==0){
			echo'Bahay Kubo';
		}else{
			echo 'Bahay Kubo ('.$total.')';
		}
	}else{
		echo'Bahay Kubo';
	}
}

function updateStatus(){
	if(isset($_SESSION['id'])){
		$conn = new mysqli('localhost','root','','itsproject');
		$id=$_SESSION['id'];
		$sql="UPDATE tbluser SET lastonline=NOW() WHERE userid=$id";
		$result=$conn->query($sql);
		mysqli_close($conn);
	}
}

function addheader(){
	echo'<header id="main-header">
			<div class="grid-header">
				<div class="box1">
					<a href="index.php">
						<div class="logo">
							<img src="img/logo.jpg">
						</div>
						<div id="header-text">
						<h1 id="first-text">Bahay Kubo</h1><br>
							<h2 id="second-text"> ni Mang Celso</h2>
						</div>
					</a>	
				</div>
				<div class="box2">';
					
						search_function();
					
				echo'</div>
				<div class="modal2">
				</div>
				<div id="search-dropdown">

				</div>
			</div>
		</header>
	<!-- Sub Header -->
		<div class="subheader">
			<div class="subgrid">
				<div class="svg">
					<p class="open-slide" onclick="openSlideMenu()">
						<i class="fas fa-bars"></i>	
					</p>
				</div>
				<div class="nav">
					<a id="Home" href="index.php"><i class="fas fa-home"></i> HOME</a>
				</div>
				<div class="profile-grid">';
					
						session_button();
					
				echo'</div>
			</div>
		</div>
	';
}

function addfooter(){
	echo'<footer id="main-footer">
			<div class="container">
				<a target="_blank" title="Like us on Facebook" href="https://www.facebook.com/BahayKuboniMangCelso/?ref=page_internal"><i class="fab fa-facebook-square"></i></a>
				<a target="_blank" title="Follow us on Twitter" href="https://twitter.com/"><i class="fab fa-twitter"></i></a>
				<a target="_blank" title="Follow us on Instagram" href="https://www.instagram.com/?hl=ens"><i class="fab fa-instagram"></i></a>
				<p><a href="about.php">About Us</a>
				<a href="contact.php">Contact Us</a>
				</p>
				<p>Copyright &copy; '.date('Y').' - <span id="company">Bahay Kubo ni Mang Celso</span></p>
			</div>
		</footer>';
}

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second'
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function time_elapsed_string_chat($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute'
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : '< 1 minute ago';
}

function createlink($string){
$url = '~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i'; 
$string = preg_replace($url, '<a href="$0" target="_blank" title="$0">$0</a>', $string);
return $string;
}

//Sidebar
function addSidebar(){
	echo'<div id="sidebarmodal" onclick="closeSlideMenu()">
		</div>';
	if(isset($_SESSION["id"])){
		echo '
		<div id="sidebarmodal">
		</div>
		<div class="side-nav" id="side-menu">
			<ul>
				<li><p href="#" class="btn-close" onclick="closeSlideMenu()">&times;</p></li>';
				
				//If site admin
				if($_SESSION['type']==4){
					echo'<li><a title="Admin Panel" href="admin.php"><i class="fas fa-unlock-alt"></i></a></li>';
				}

				if($_SESSION['type']== 3 OR $_SESSION['type']==4){
					echo'<li><a title="Add Product" href="addproduct.php"><i class="fas fa-plus-circle"></i></a></li>';
				}
				echo'
				<li>
				<a title="About us" href="about.php"><i class="fas fa-info-circle"></i></a></li>
				<li>
				<a title="Change your profile picture" href="insertphoto.php"><i class="fas fa-camera"></i></i></a></li>
				<li><a title="Edit your personal info" href="editinfo.php"><i class="fas fa-pen-square"></i></a></li>
				<li><a title="Change your account settings" href="accountsetting.php"><i class="fas fa-cog"></i></a></li>
				<li><a title="Order Tracking" href="ordertracking.php"><i class="fas fa-shoe-prints"></i></a></li>
				<li><a title="Logout" href="logout.php"><i class="fas fa-power-off"></i></a></li>
			</ul>
		</div>';
	}else{
	echo'
		<div class="side-nav" id="side-menu">
			<ul>
				<li><p href="#" class="btn-close" onclick="closeSlideMenu()">&times;</p></li>
				<li>
				<a title="About us" href="about.php"><i class="fas fa-info-circle"></i></a></li>
				<li>
				<li><a target="_blank" title="Like us on Facebook" href="https://www.facebook.com/BahayKuboniMangCelso/?ref=page_internal"><i class="fab fa-facebook-square"></i></a></li>
				<li><a target="_blank" title="Follow us on Twitter" href="https://twitter.com/"><i class="fab fa-twitter"></i></a></li>
				<li><a target="_blank" title="Follow us on Instagram" href="https://www.instagram.com/?hl=ens"><i class="fab fa-instagram"></i></a></li>
			</ul>
		</div>';
	}
}

// Login Pop Out
function addLogin(){
	echo'<div id="simpleModal" class="modal">
			<div class="modal-content">
				<div class="modal-header">
					<span id="closeBtn">&times;</span>	
					<h5>Login Form</h5>
				</div>
				<div class="modal-body">
					<form action="loginprocess.php" method="post" id="log-form" >
						<center><label for="">Username/Email:</label>
						<input type="text" required name="username" id="log-user" placeholder="Enter Username...">
						<br>
						<label for="">Password:</label>
						<input type="password" required name="password" id="log-pass" placeholder="Enter Password...">
						<br>
						<label for="">Remember Me?</label>
						<input type="checkbox" id="log-remember" name="remember">
						<br>
						<input type="submit" class="modal-button" value="Login"></center>
					</form>
					<div id="error-message"></div>	
				</div>
				<div class="modal-footer">
					<a href="register.php">Doesn\'t have an account?</a>
				</div>
			</div>
		</div>
	';
}

function session_button(){
	$conn = mysqli_connect ("localhost", "root", "", "itsproject");

// Shopping Cart
	echo '<div id="top-total" onclick="showCartPanel()">';
	if(isset($_SESSION['total'])){
		echo '₱'.number_format($_SESSION['total'],2);
	}
	echo'</div>
	<div id="cart-panel"">
		<h1 onclick="hideCartPanel()" class="close-heading">Shopping Cart <i style="float:right; padding-right:10px" class="fas fa-times"></i></h1>
		<div id="shopping-cart-content"></div>
	</div>
	<i class="fas fa-shopping-cart button" onclick="showCartPanel()"></i>';

	if(isset($_SESSION['id'])){

	$id=$_SESSION['id'];
		$sql="SELECT imgpath FROM tbluser WHERE userid=".$id."";
		$result=$conn->query($sql);
		$row=$result->fetch_object();
		$tn_image=$row->imgpath;
		if($tn_image==''){
			$tn_image='img/default.png';
		}
//PM Count
$sql="SELECT pmid FROM tblpm WHERE receiverid='$id' AND checked=0 GROUP BY senderid";
$result=$conn->query($sql);
$count=$result->num_rows;
		echo'<a class="button" title="Check your messages" href="inbox.php?id='.$_SESSION["id"].'"><i class="far fa-envelope"></i><span id="pmnum">'.$count.'</span></a>';

//Notification Count

$sql="SELECT notifid FROM tblnotif
	WHERE receiverid='$id' and checked=0
	";
$result=$conn->query($sql);
$count=$result->num_rows;

		echo'<a class="button" id="notifbtn" title="Check your notifications" onclick="showNotif()""><i class="far fa-bell"></i><span id="notifnum">'.$count.'</span></a>
		<a class="button" href=profile.php?id='.$_SESSION['id'].'>
		'.$_SESSION["name"].'\'s Profile<div class="top-tn"><img src="'.$tn_image.'""></div></a>';
		echo'<div id="notifdrop">
		Notifications
		<ul>';

//Notification Drop down
$sql="SELECT notifid,tblnotif.userid,username,imgpath,receiverid,notifdate,notiftype,checked,details,details2 FROM tblnotif
	LEFT JOIN tbluser
		ON tblnotif.userid=tbluser.userid
	WHERE receiverid='$id'
	ORDER BY notifid DESC
	LIMIT 10";

$result=$conn->query($sql);
$count=$result->num_rows;
if($count==0){
	echo'<li>No notifications yet...</li>';
}else{

while($rows=$result->fetch_object())
{
$nid=$rows->notifid;
$uid=$rows->userid;
$uname=$rows->username;
$rid=$rows->receiverid;
$date=time_elapsed_string($rows->notifdate);
$type=$rows->notiftype;

$imgpath=$rows->imgpath;
$uname=$rows->username;
$details=$rows->details;
$details2=$rows->details2;
if(!$imgpath){
	$imgpath='img/default.png';
}

if($type==1){


	echo'<li><div class="comment-tn">
				<img src="'.$imgpath.'">
			</div> <a class="n1" href="profile.php?id='.$uid.'">'.$uname.'</a> <a class="n2" href="profile.php?id='.$_SESSION['id'].'#comment'.$details.'"> has commented on your profile '.$date.'</a></li>';
} else if($type==2){
	if ($details2==1){
	echo'<li><div class="comment-tn">
				<img src="'.$imgpath.'">
			</div>
			 <a class="n1" href="profile.php?id='.$uid.'">'.$uname.'</a> has sent a friend request '.$date.'<br>
		<div id="fr-'.$nid.'"><a class="fr-yes" onclick="friendyes(this)" value="'.$nid.'">Yes</a> <a class="fr-no" onclick="friendno(this)" value="'.$nid.'">No</a>
		</div>
		</li>';
	} else if($details2==2){
		echo'<li><div class="comment-tn">
				<img src="'.$imgpath.'">
			</div>
			 <a class="n1" href="profile.php?id='.$uid.'">'.$uname.'</a> has sent a friend request '.$date.'<br>
		<div id="fr-'.$nid.'">
			Request Accepted
		</div>
		</li>';
	} else if($details2==3){
		echo'<li><div class="comment-tn">
				<img src="'.$imgpath.'">
			</div>
			 <a class="n1" href="profile.php?id='.$uid.'">'.$uname.'</a> has sent a friend request '.$date.'<br>
		<div id="fr-'.$nid.'">
			Request Denied
		</div>
		</li>';
	}
}else if ($type==3){
	echo'<li><i class="fas fa-ban banned"></i> Sorry, your profile picture has been removed.<br>
	Please read the rules and guidelines.</li>';
}else if ($type==4){
	echo'<li> Your order has been <span style="color:green;"><b>approved</b></span><br>'.$date.'.<br>
	Order No. <a class="black" href="search.php?criteria=3&search-text='.$details.'"><b>'.$details.'</a></b></li>';
}else if ($type==5){
	echo'<li> Your order has been <span style="color:red;"><b>rejected</b></span><br>'.$date.'.<br>
	Order No. <a class="black" href="search.php?criteria=3&search-text='.$details.'"><b>'.$details.'</a></b></li>';
}else if ($type==6){
	echo'<li> Your order has been <span style="color:red;"><b>cancelled</b></span><br>'.$date.'.<br>
	Order No. <a class="black" href="search.php?criteria=3&search-text='.$details.'"><b>'.$details.'</a></b></li>';
}else if ($type==7){
	echo'<li> <div class="comment-tn">
				<img src="'.$imgpath.'">
			</div>
			 <a class="n1" href="profile.php?id='.$uid.'">'.$uname.'</a> has place an order '.$date.'.<br>
	Order No. <a class="black" href="search.php?criteria=3&search-text='.$details.'"><b>'.$details.'</b></a></li>';
}
}
}

		echo'<center><a id="seeallnotif" href="notification.php">See all notifications</a></center>
		</ul>
		</div>';
	}else{
		echo'<div id="notifnum"></div><div id="pmnum"></div>
		<a href ="register.php" class="button"><i class="fas fa-pencil-alt"></i></i>Sign Up</a>
		<a id="modalBtn" class="button"><i class="fas fa-sign-in-alt"></i>Login</a>';
	}
	echo'<div id="modal3" onclick="hideNotif()">
	</div>';
}

function search_function(){
	echo'
		<form action="search.php" method="get">
			<i class="fas fa-search"></i>
			<label>Search</label>
			<select name="criteria" id="criteria">
				<option value="1">Product</option>';
				if(isset($_SESSION['id'])){
					echo'<option value="3">Order No</option>';
				}
			echo'<option value="2">User</option>
			</select>
			<input type="text" onkeyup="searchdropdown()" required name="search-text" placeholder="Search..." id="search-text" autocomplete="off">
		</form>
	';
}

function chattab(){
	if(isset($_SESSION['id'])){
	$id=$_SESSION['id'];

	$conn = new mysqli('localhost','root','','itsproject');
	

	$sql="SELECT userid,username,imgpath,lastonline FROM tblfriend
	LEFT JOIN tbluser
		ON userid=user1 or userid=user2
	WHERE (user1='$id' or user2='$id') AND accepted=2 AND userid!='$id'
 	ORDER BY lastonline DESC";
 	$result=$conn->query($sql);

		echo'
		<div id="chat-tab" onclick="showChatPanel()">
			<div class="online"></div>
			<span>Chat</span>
		</div>
		<div id="chat-modal" onclick="hideChatPanel()">
		</div>
		<div id="chat-panel">
			<div id="chat-panel-body"><h3 onclick="hideChatPanel()" class="close-heading">Chat</h3>
				<ul>';
	while($row=$result->fetch_object()){
		$id=$row->userid;
 		$name=$row->username;
 		$img=$row->imgpath;
 		if(!$img){
 			$img="img/default.png";
 		}
 		$online=$row->lastonline;
 		$time=time();

 		echo '<a href="inbox.php?id='.$id.'"><li>
 		<div class="chat-panel-tn">
 			<img src="'.$img.'">
 		</div>';
 		if($time-strtotime($online)< 300){
			echo'<div class="online"></div>';
		} else{
			echo'<div class="offline"></div>';
		}
 		echo $name
 		.'</li></a>';
 	}
			echo'
				</ul>
			</div>
			<div id="chat-bottom">
				<form id="chat-search-form">
					<i class="fas fa-search"></i><input type="text" id="chat-search" onkeyup="searchChat()" autocomplete="off" placeholder="Search friend...">
				</form>
			</div>
		</div>';
	}
}

function reportuser(){
	if(isset($_SESSION['id'])){
	echo'
	<div id="modal4" onclick="hidereport()">
	</div>
	<div id="reportdiv">
		<div id="reportheader">
			<span>Report this user</span>
			<a onclick="hidereport()"><i class="far fa-window-close"></i></a>
		</div>
		<div id="reportbody">
			<form id="reportform">
				<p>Select reason:</p>
				<select id="select-reason">
					<option value="1">Pornographic profile picture</option>
					<option value="2">Offensive profile picture</option>
					<option value="3">This user is harassing me</option>
					<option value="4">Spamming</option>
					<option value="5">Scammer</option>
				</select>
				<p>Other reasons:</p>
				<textarea id="report-reasons" placeholder="State other reasons..."></textarea>
				<br>
				<input type="hidden" id="report-userid" value="'.$_GET['id'].'">
				<input type="submit">
			</form>
		</div>
	</div>
	';
	}
}

function starsystem($percent){
	echo'<div class="star-system" title="'.$percent.'% rating">';

	if($percent>=98){
	echo'
		<i class="fas fa-star"></i>
		<i class="fas fa-star"></i>
		<i class="fas fa-star"></i>
		<i class="fas fa-star"></i>
		<i class="fas fa-star"></i>
	';
	}else if($percent>=85 & $percent<98){
	echo'
		<i class="fas fa-star"></i>
		<i class="fas fa-star"></i>
		<i class="fas fa-star"></i>
		<i class="fas fa-star"></i>
		<i class="fas fa-star-half"></i>
	';
	}else if($percent>=75 & $percent<85){
	echo'
		<i class="fas fa-star"></i>
		<i class="fas fa-star"></i>
		<i class="fas fa-star"></i>
		<i class="fas fa-star"></i>	
		<i class="far fa-star"></i>
	';
	}else if($percent>=65 & $percent<75){
	echo'
		<i class="fas fa-star"></i>
		<i class="fas fa-star"></i>
		<i class="fas fa-star"></i>
		<i class="fas fa-star-half"></i>
		<i class="far fa-star"></i>
	';
	}else if($percent>=55 & $percent<65){
	echo'
		<i class="fas fa-star"></i>
		<i class="fas fa-star"></i>
		<i class="fas fa-star"></i>
		<i class="far fa-star"></i>
		<i class="far fa-star"></i>
	';
	}else if($percent>=45 & $percent<55){
	echo'
		<i class="fas fa-star"></i>
		<i class="fas fa-star"></i>
		<i class="fas fa-star-half"></i>
		<i class="far fa-star"></i>
		<i class="far fa-star"></i>
	';
	}else if($percent>=35 & $percent<45){
	echo'
		<i class="fas fa-star"></i>
		<i class="fas fa-star"></i>
		<i class="far fa-star"></i>
		<i class="far fa-star"></i>
		<i class="far fa-star"></i>
	';
	}else if($percent>=25 & $percent<35){
	echo'
		<i class="fas fa-star"></i>
		<i class="fas fa-star-half"></i>
		<i class="far fa-star"></i>
		<i class="far fa-star"></i>
		<i class="far fa-star"></i>
	';
	}else if($percent>=15 & $percent<25){
	echo'
		<i class="fas fa-star"></i>
		<i class="far fa-star"></i>
		<i class="far fa-star"></i>
		<i class="far fa-star"></i>
		<i class="far fa-star"></i>
	';
	}else if($percent>=4 & $percent<15){
	echo'
		<i class="fas fa-star-half"></i>
		<i class="far fa-star"></i>
		<i class="far fa-star"></i>
		<i class="far fa-star"></i>
		<i class="far fa-star"></i>
	';
	}else if($percent<4){
	echo'
		<i class="far fa-star"></i>
		<i class="far fa-star"></i>
		<i class="far fa-star"></i>
		<i class="far fa-star"></i>
		<i class="far fa-star"></i>
	';
	}
	echo'</div>';
}

function showProduct($where){
	$conn = new mysqli('localhost','root','','itsproject');

	$sql = "SELECT productid,category, productname, description, farmname, username, dateposted, price, img, rating FROM tblproduct as t1
	LEFT JOIN tblcategory as t2
		ON t1.categoryid = t2.categoryid
	LEFT JOIN tbluser as t3
		ON t1.userid = t3.userid
	LEFT JOIN tblfarm as t4
		ON t1.farmid = t4.farmid
	$where";
	$result = $conn->query($sql);
	while($row = $result->fetch_object()){
		$id = $row->productid;
		$category = $row->category;
		$product = $row->productname;
		$desc = $row->description;
		$farm = $row->farmname;
		$user = $row->username;
		$date = date('F j, Y',strtotime($row->dateposted));
		$price = $row->price;
		$img = $row->img;
		if(!$img){
			$img='img/default2.jpg';
		}
		$rating = $row->rating;

		echo'
		<div class="product">
		<a href="product.php?id='.$id.'">
		<div class="product-img-wrap">
			<img src="'.$img.'" alt="Product Image">
		</div>
		<p class="product-title">'.$product.'</p>
		</a>
		<div class="product-content">
		<a href="product.php?id='.$id.'">
		<p>';

		starsystem($rating);

		echo'
		</p>

		<p class="product-category">'.$category.'</p>
		<p class="product-farm">'.$farm.'</p>
		<p class="product-desc">Description: '.substr($desc,0,30).' ...</p>
		</a>
		<p class="product-price">₱'.number_format($price,2).' / kg</p>
		<div class="add-to-cart" value="'.$id.'" onclick="addThistoCart(this)"><i class="fas fa-shopping-cart"></i> Add to Cart</div>
		</div>
		</div>';
	}
}

function setupCookie(){
	if(isset($_COOKIE['id'])){
		$_SESSION['id'] = $_COOKIE['id'];
		$_SESSION['name']= $_COOKIE['name'];
		$_SESSION['type']= $_COOKIE['type'];
	}
}

function destroyCookie(){
	if (isset($_COOKIE['id'])) {
	    
	    unset($_COOKIE['id']);
	    unset($_COOKIE['name']);
	    unset($_COOKIE['type']);

	    setcookie('id', '', time() - 3600, '/');
	    setcookie('name', '', time() - 3600, '/');
	    setcookie('type', '', time() - 3600, '/');
	}
}

function user_access(){
	if(!isset($_SESSION['id'])){
		die('You\'re not login.');
	}
}


function user_nonAccess(){
	if(isset($_SESSION['id'])){
		header('location:index.php');
	}
}

function adminAccess(){
	if(isset($_SESSION['id'])){
		if($_SESSION['type']!= 4){
			header('Location: index.php');
		}
	}
}

function adminpanelAccess(){
	if(isset($_SESSION['id'])){
		if($_SESSION['type']==4){

			if(isset($_SESSION['admin'])){
				if($_SESSION['admin']!='IchigoParfait'){
					header('Location: admin.php');
				}
			}
		}else{
			die('You\'re not allowed to access this page');	
		}
	}else{
		die('You\'re not allowed to access this page');
	}
}

function admingoback(){
	if(isset($_SESSION['admin'])){
		if($_SESSION['admin']=='IchigoParfait'){
			header('Location: adminpanel.php');
		}
	}
}

function seller_Access(){
	if(isset($_SESSION['id'])){
		if($_SESSION['type']!=3 AND $_SESSION['type']!=4){
			header('Location: index.php');
		}
	}	
}