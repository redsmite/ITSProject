<?php
	session_start();
	include'functions.php';
	updateStatus();
	addSidebar();
	setupCookie();
	addLogin();
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
			<h1><i class="fas fa-info-circle"></i> About</h1>
			<div class="container">
				<div class="content-box">
					<h2><span id="highlight-text">What</span> is CropRotation?</h2>
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsa, sunt aliquam ipsum officia molestiae cum amet natus totam. At soluta, molestiae dicta! Illum dicta temporibus nobis architecto assumenda, perferendis, aperiam hic excepturi suscipit, quaerat sit enim perspiciatis amet accusamus nam, eveniet labore dignissimos! Veritatis, consequuntur, dolorem! Velit magnam laboriosam in, veritatis labore? Voluptatum cupiditate saepe excepturi fugit officia nam, ipsum doloribus officiis omnis nihil eligendi vitae sit velit assumenda quas tempore commodi nesciunt quidem eos. Architecto ad, provident quo. Obcaecati molestiae similique repellendus voluptatem necessitatibus. Exercitationem dolorum, quam recusandae repellendus iste laudantium laboriosam autem. Corporis et sequi optio veniam deleniti totam, eaque ipsum tenetur voluptatem, id vel mollitia laudantium dolores, ut saepe. Quos, quo porro eum accusamus omnis. Nam facilis corporis est reiciendis accusamus cum officia repellat perferendis adipisci consequatur, iste voluptatibus, aspernatur fugiat eum alias aut iure ducimus earum ipsum ipsa esse velit eaque delectus laboriosam. Ducimus modi eos ut reprehenderit, consequatur consequuntur iusto. Consectetur laudantium accusantium vel porro voluptates, voluptatum voluptatibus eos excepturi aperiam tempora, possimus iure quos itaque tempore culpa illo unde a libero veniam ipsa dicta in esse. Ab ad temporibus cumque rerum natus voluptatum, sunt quod eligendi quam, magnam? Sed, quo! Esse a ab accusamus! </p>
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
		modal();
		ajaxLogin();
	</script>
</body>
</html>