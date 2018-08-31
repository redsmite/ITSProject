<?php
session_start();
include'functions.php';
require_once'connection.php';
adminpanelAccess();
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
<body onscroll="scrollOpacity()">
	<div class="main-container">
	<!-- Header -->
	<?php
		addheader();
	?>
	<!-- Admin Panel -->
		<div class="other-content">
			<h1 class="center"><i class="fas fa-unlock-alt"></i> Admin Panel</h1>
			<h1 class="center">Hello <?php echo '<a class="white" href="profile.php?id='.$_SESSION['id'].'">'.$_SESSION['name'].'!</a>'?> - <a href="adminsetting.php" class="white"><i class="fas fa-cog"></i> Admin Settings</a></h1>
			<div id="admin-tab">
				<a id="monitoring-tab" onclick="showMonitoringTab()">Monitoring</a>
				<a id="sales-tab" onclick="showSalesTab()">Sales</a>
				<a id="report-tab" onclick="showReportTab()">
<?php
//Number of unread reports
	$sql = "SELECT reportid FROM tblreport WHERE checked=0";
	$result = $conn->query($sql);
	$count = $result->num_rows;
	if(!$count == 0){
		echo '('.$count.')';
	}
?>
				Users</a>
				<a id="announcement-tab" onclick="showAnnouncementTab()">Announcement</a>
			</div>
			<div id="admin-body">
			<div id="monitoring">
				<div class="top-pdf">
					<a href="https://psa.gov.ph/content/psa-media-service-market-prices-selected-commodities-metro-manila" class="get-pdf" target="_blank"><i class="fas fa-file-pdf"></i> Get PDF Here!</a>
				</div>
				<div class="left-monitoring">
					<div class="monitoring-option" onclick="showOrders()"><i class="fas fa-book-open"></i>
<?php
//counting new orders
$sql = "SELECT orderid FROM tblorder WHERE status = 0";
$result = $conn->query($sql);
$ordercount = $result->num_rows;
if($ordercount != 0){
	echo '('.$ordercount.')';
}
?>
					 New Orders</div>
					<div class="monitoring-option" onclick="showTransaction()"><i class="fas fa-clipboard-list"></i>
<?php
//Counting approve orders
$sql = "SELECT orderid FROM tblorder WHERE status = 1";
$result = $conn->query($sql);
$ordercount = $result->num_rows;
if($ordercount != 0){
	echo '('.$ordercount.')';
}
?>
					 Transactions</div>
					 <div class="monitoring-option" onclick="transactionHistory()"><i class="fas fa-book"></i> Transaction History</div>
					<div class="monitoring-option" onclick="showApproveProduct()"><i class="fas fa-clipboard-check"></i>
<?php
$sql = "SELECT productid FROM tblproduct WHERE is_approved = 0";
$result = $conn->query($sql);
$new_products = $result->num_rows;
if($new_products!=0){
	echo '('.$new_products.')';
}
?>
					 Products</div>
					<div class="monitoring-option" onclick="setPrice()"><i class="far fa-money-bill-alt"></i> Price Monitoring</div>
					<div class="monitoring-option" onclick="addNewCategory()"><i class="fas fa-plus"></i> Add / Remove Category</div>
					<div class="monitoring-option" onclick="priceHistory()"><i class="fas fa-book"></i> Change Log</div>
				</div>
				<div class="empty"></div>
				<div class="right-monitoring">
					<div id="order-body">
					<h1>New Orders</h1>
<?php
	$sql = "SELECT cutoff FROM tblcutoff";
	$result = $conn->query($sql);
	$fetch = $result->fetch_object();
	$cutoff = $fetch->cutoff;

	$cutoff2 = strtotime($cutoff);
	$datenow = date('Y-m-d H:i:s');
	$now = strtotime('now');
	$diff = $cutoff2 - $now;
	$diff = gmdate("H:i:s", $diff);
	echo '<h3 id="cutoff-time" value="'.$cutoff.'" now="'.$datenow.'">Cut Off Time in: '.$diff.' </h3>';
?>
						<div id="order-body-content">
						</div>
					</div>
					<div id="transaction-body">
						<h1>Transactions</h1>
						<div id="transaction-body-content">
						</div>
					</div>
					<div id="transaction-history">
						<h1>Transaction History</h1>
						<div id="transaction-history-content">
						</div>
					</div>
					<div id="product-monitoring">
						<h1>Products to be approved</h1>
						<div class="refresh-button" onclick="showApproveProduct()"><h2>Refresh <i class="fas fa-sync-alt"></i></h2></div>
						<div id="product-monitoring-content">
						</div>
					</div>
					<div id="add-category">
						<h1>Add / Remove Category</h1>
						<div class="edit-form">
							<form id="add-category-form">
								<div>
									<h2>Add Category</h2>
									<input id="category-name" type="text">
								</div>
								<div>
									<button onclick="addCategoryAjax()"><i class="fas fa-plus"></i> Add</button>
								</div>
								<div id="error-message2">
								</div>
							</form>
						</div>
						<div id="fetch-category">
<?php

$sql = "SELECT categoryid,status,category FROM tblcategory";
	echo '
		<table>
		<tr>
		<th>Category</th>
		<th>Show/Hide</th>
		</tr>';
	$result = $conn->query($sql);
	while($row = $result->fetch_object()){
		$id = $row->categoryid;
		$category = $row->category;
		$status = $row->status;

		echo '<tr>
		<th>'.$category.'</th>';
		
		if($status==0){
		echo'<th id="cat-'.$id.'" class="cathover" value='.$id.' onclick="showCat(this)"><span class="banned">Hide</span></th>';
		}else if($status==1){
		echo'<th id="cat-'.$id.'" class="cathover" value='.$id.' onclick="hideCat(this)"><span class="notbanned">Show</span></th>';
		}
		echo'</tr>';
	}
	echo '</table>';
?>
						</div>
					</div>
					<div id="set-price">
						<div class="edit-form">
						<h3 align="center">Set Shipping Fee</h3>
						<form align="center" id="update-fee-form">
<?php
$sql = "SELECT fee FROM tblshippingfee WHERE feeid=1";
$result = $conn->query($sql);
$fetch = $result->fetch_object();
$fee = $fetch->fee;
?>
							₱<input type="number" id="fee" value="<?php echo $fee?>" step="any" min="0">
							<button class="price-button">Update</button>
						</form>
						<h3 align="center">Set Minimum Order</h3>
						<form align="center" id="update-minimum-form">
<?php
$sql = "SELECT minimumorder FROM tblminimumorder";
$result = $conn->query($sql);
$fetch = $result->fetch_object();
$minorder = $fetch->minimumorder;
?>
							₱<input type="number" id="minorder" value="<?php echo $minorder?>" step="any" min="0">
							<button class="price-button">Update</button>
						</form>
						</div>
						<h1>Price Monitoring</h1>
						<div id="error-message3"></div>
						<ul class="price-ul">
<?php
	$sql = "SELECT categoryid, category, low ,high, prevailing FROM tblcategory WHERE status=1";
	$result = $conn->query($sql);
	while($row = $result->fetch_object()){
		$id = $row->categoryid;
		$category = $row->category;
		$low = $row->low;
		$high = $row->high;
		$prevailing = $row->prevailing;

		echo '<li id="li-'.$id.'">
		<h3>'.$category.'</h3> 
			<div class="price-input">
				Low:<input type="number" id="low-'.$id.'" value="'.$low.'">
			</div>
			<div class="price-input">
				High:<input type="number" id="high-'.$id.'" value="'.$high.'">
			</div>
			<div class="price-input">
				Prevailing:<input type="number" id="prev-'.$id.'" value="'.$prevailing.'">
			</div>
			<div class="price-input">
				<button class="price-button" onclick="updatePrice(this)" value="'.$id.'">Update</button>
			</div>
		</li>';
	}
?>
						</ul>
					</div>
					<div id="history">
						<h1>Changelog</h1>
						<div id="whitepaper">
						</div>
					</div>
				</div>
			</div>
			<div id="admin-reports">
			<ul class="reportlist">
<?php
	$sql = "SELECT reportid FROM tblreport";
	$result=$conn->query($sql);

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

	$sql = "SELECT tblreport.userid,reportid,user1.username AS reported, user2.username AS reporter,reason,tblreport.datecreated,checked FROM tblreport
	LEFT JOIN tbluser AS user1
		ON user1.userid=tblreport.userid
	LEFT JOIN tbluser AS user2
		ON user2.userid = reporter
	ORDER BY reportid DESC $limit";

$textline1 = "<i class='far fa-flag'></i> User Reports (<b>$rows</b>)";
$textline2 = "Page <b>$pagenum</b> of <b>$last</b>";
$paginationCtrls = '';
if($last != 1){
	if ($pagenum > 1) {
        $previous = $pagenum - 1;
		$paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?pn='.$previous.'">Previous</a> &nbsp; &nbsp; ';
		// Render clickable number links that should appear on the left of the target page number
		for($i = $pagenum-4; $i < $pagenum; $i++){
			if($i > 0){
		        $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?pn='.$i.'">'.$i.'</a> &nbsp; ';
			}
	    }
    }
    $paginationCtrls .= ''.$pagenum.' &nbsp; ';
	for($i = $pagenum+1; $i <= $last; $i++){
		$paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?pn='.$i.'">'.$i.'</a> &nbsp; ';
		if($i >= $pagenum+4){
			break;
		}
	}
	    if ($pagenum != $last) {
        $next = $pagenum + 1;
        $paginationCtrls .= ' &nbsp; &nbsp; <a href="'.$_SERVER['PHP_SELF'].'?pn='.$next.'">Next</a> ';
    }
}
 echo'<h2>  '.$textline1.'</h2>
  <p>  '.$textline2.' </p>
  <div id="pagination_controls"> '.$paginationCtrls.'</div>';

	$result = $conn->query($sql);
	while($row=$result->fetch_object()){
		$userid = $row->userid;
		$username= $row->reported;
		$reason = $row->reason;
		$date= time_elapsed_string($row->datecreated);
		$reporter = $row->reporter;
		$checked = $row->checked;
		$id = $row->reportid;

		echo '<li id="'.$id.'" onclick="checkedreport(this)">';

		if($checked==0){
			echo'<p id="rp-'.$id.'" class="newreport">Unread</p>';
		} else {
			echo '<p class="checkreport">Read</p>';
		}

		echo'<p>Reported User: <a href="profile.php?id='.$userid.'"><font color="#00c07f">'.$username.'</font></a></p>
		<p>Reported by: <a href="profile.php?name='.$reporter.'">'.$reporter.'</a></p>';
		
		if($reason==1){
			echo'<p>Reason: Pornographic profile picture.</p>';
		}else if($reason==2){
			echo'<p>Reason: Offensive profile picture.</p>';
		}else if($reason==3){
			echo'<p>Reason: This user harasses me.</p>';
		}else if($reason==4){
			echo'<p>Reason: Spamming.</p>';
		}else if($reason==5){
			echo'<p>Reason: Scammer.</p>';
		}else{
			echo'<p>Reason: '.$reason.'</p>';
		} 
		echo'<p>'.$date.'</p>
		</li>';
	}
?>
			</ul>
		</div>

			<div id="get-users-div">
				<h1>Get User</h1>
				<input type="text" onkeyup="fetchUser()" id="get-user">
			</div>
			<div id="fetch">
				<div onclick="resetfetch()" class="closethis"><a><i class="fas fa-times"></i></a></div>
			</div>
			<div id="announcement-div">
				<h3>Announcement</h3>
				<div class="edit-form">
					<form id="announce-form">
						<div>
							<p>Title:</p>
							<input required type="text" placeholder="Enter title..." id="announce-title">
						</div>
						<div>
							<p>Content:</p>
							<textarea required placeholder="Enter Content..." id="announce-content"></textarea>
						</div>
						<div>
							<input type="submit" value="submit">
						</div>
						<input type="hidden" id="announce-author" value="<?php echo $_SESSION['id']?>">
					</form>
				</div>
				<h3>Send message to all users</h3>
				<div class="edit-form">
				<form id='sendtoallform'>
					<center>
					<div>
						<textarea id="sendtoallmessage"  required placeholder="Enter message..."></textarea>
					</div>
					<div>
						<input type="submit" value="submit">
					</div>
					</center>
				</form>
				</div>
			</div>
			<div id="sales">
<div id="sales-left">
	<div class="monitoring-option" onclick="showDailyTab()">
		<i class="fas fa-chart-bar"></i> Daily Report
	</div>
	<div class="monitoring-option" onclick="showWeeklyTab()">
		<i class="fas fa-chart-bar"></i> Weekly Report
	</div>
	<div class="monitoring-option" onclick="showMonthlyTab()">
		<i class="fas fa-chart-bar"></i> Monthly Report
	</div>
	<div class="monitoring-option" onclick="showYearlyTab()">
		<i class="fas fa-chart-bar"></i> Yearly Report
	</div>
	<div class="monitoring-option" onclick="showFarmTab()">
		<i class="fas fa-location-arrow"></i> Farms
	</div>
</div>
<div class="empty"></div>

<div id="sales-body">
	<div id="farms">
		<h1><i class="fas fa-location-arrow"></i> Farms</h1>
		<div class="edit-form">
			<form id="add-farm-form">
				<h2><i class="fas fa-plus"></i> Add Farm</h2>
				<div>
					<p>Farm Name</p>
					<input type="text" id="farm-name">
				</div>
				<div>
					<p>Address</p>
					<textarea id="farm-address"></textarea>
				</div>
				<div>
					<br>
					<button>Submit</button>
				</div>
			</form>
		</div>
		<div id="fetch-category">
			<h1>Farms</h1>
			<table>
				<tr>
					<th>Farm</th>
					<th>Address</th>
					<th>Status</th>
					<th>Update</th>
				</tr>
<?php
	$sql = "SELECT farmid,farmname,address,status FROM tblfarm";
	$result = $conn->query($sql);
	while($row=$result->fetch_object()){
		$id = $row->farmid;
		$name = $row->farmname;
		$address = $row->address;
		$status = $row->status;

		echo '<tr>
		<th>'.$name.'</th>
		<th>'.$address.'</th>';
		if($status==1){
			echo '<th><font style="color:green">Active</font></th>';
		}else{
			echo '<th><font style="color:red">Inactive</font></th>';
		}
		echo'<th><a href="updatefarm.php?id='.$id.'">Update</a></th>
		</tr>';
	}
?>
			</table>
		</div>
	</div>
	<div id="daily-report">
	</div>
	<div id="weekly-report">
		<h1><i class="fas fa-chart-bar"></i> Weekly Report</h1>
	</div>
	<div id="monthly-report">
	</div>
	<div id="yearly-report">
	</div>
</div>
			</div>
			</div>
			<!-- End of Admin Body Panel-->
		</div>
	<!-- Footer -->
		<?php
			addfooter();
		?>
	<!-- End of Container -->
	</div>
	<script src="js/main.js"></script>
	<script>
		cutoffCountdown();
		updateFee();
		updateMinimum();
		sendAllUser();
		showMonitoringTab();
		sendAnnounce();
		addFarm();
	</script>
</body>
</html>