<?php
    require_once('config.php');
    require_once(ROOT_PATH . '/includes/public_functions.php');
    require_once(ROOT_PATH . '/includes/registration_login.php');
    require_once(ROOT_PATH . '/includes/head_section.php');

    $posts = getPublishedPostsForPage(); ?>
	<title><?= BLOG_NAME ?>  | Home </title>
</head>
<body style="background-image: url('<?= BASE_URL ?>/static/images/bg.jpg')">
	<!-- container - wraps whole page -->
	<div class="content">
		<!-- header -->
		<?php include(ROOT_PATH . '/includes/header.php') ?>
		<!-- // header -->

		<!-- Main page content -->
		<div class="main-wrapper">
			<main>

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
	<!-- // container -->

	<!-- script -->
	<script src="<?= BASE_URL . 'static/js/index.js' ?>"></script>
</body>
</html>