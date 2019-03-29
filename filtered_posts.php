<?php include('config.php'); ?>
<?php include('includes/public_functions.php'); ?>
<?php include('includes/head_section.php'); ?>
<?php 
	// Get posts under a particular topic
	if (isset($_GET['topic'])) {
		$posts = getPublishedPostsByTopic($_GET['topic']);
		$topic = getTopicNameById($_GET['topic']);
	}
	else if (isset($_GET['tag'])) {
		$posts = getPublishedPostsByTag($_GET['tag']);
		$tag = htmlentities($_GET['tag']);
	}
	else if (isset($_GET['search'])) {
		$posts = getPublishedPostsBySearchQuery($_GET['search']);
		$query = htmlentities($_GET['search']);
	}
	else if (isset($_GET['month']) && isset($_GET['year'])) {
		$month = $_GET['month'];
		$year = $_GET['year'];
		$monthName = date("F", mktime(0, 0, 0, $month, 10));
		$posts = getPublishedPostsByDate($month, $year);
		$date = htmlentities($monthName) . ', ' . htmlentities($year);
	}
?>
	<title><?= BLOG_NAME ?> | <?= $topic ?? $tag ?? $query ?? $date ?> </title>
</head>
<body style="background-image: url('<?= BASE_URL ?>/static/images/bg.jpg')">
	<div class="content">
	<!-- Header -->
	<?php include( ROOT_PATH . '/includes/header.php'); ?>
	<!-- // Header -->

		<!-- Main page content -->
		<div class="main-wrapper">
			<main>

				<h2 class="content-title">
					<?php if(isset($topic)) {
						echo "Articles on $topic";
					} else if (isset($tag)) {
						echo "Articles tagged as $tag";
					} else if (isset($query)) {
						echo "Search result for $query";
					} else if (isset($date)) {
						echo "Articles posted in $date";
					} ?>
				</h2>

				<?php include(ROOT_PATH . '/includes/posts_preview.php') ?>

      </main>

			<!-- sidebar -->
			<?php include(ROOT_PATH . '/includes/sidebar.php') ?>
			<!-- // sidebar -->

		</div>
		<!-- // Main page content -->

		<!-- back to top anchor -->
		<a href="#" class="hidden" id="toTop">&Hat;</a>
		<!-- // back to top anchor -->

		<!-- footer -->
		<?php include(ROOT_PATH . '/includes/footer.php') ?>
		<!-- // footer -->

	</div>
	<!-- // content -->

	<!-- script -->
	<script src="<?= BASE_URL . 'static/js/index.js' ?>"></script>
	<!-- // script -->
</body>
</html>