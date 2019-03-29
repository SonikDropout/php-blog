<?php
    require_once('config.php');
    require_once(ROOT_PATH . '/includes/public_functions.php');
    require_once(ROOT_PATH . '/includes/registration_login.php');
	  require_once(ROOT_PATH . '/includes/contact.php');
    require_once(ROOT_PATH . '/includes/head_section.php');

?>
	<title><?= BLOG_NAME ?>  | Contact </title>
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

        <article class="contact">
          <h2>Contact creator of this blog</h2>
  				<form action="contact.php" method="post" class="contact-form">
      			<?php include(ROOT_PATH . '/includes/errors.php'); ?>
            <input type="text" name="name" placeholder="Your name" required />
            <input type="email" name="email" placeholder="Your email" required />
            <input type="text" name="subject" placeholder="Subject" />
            <textarea rows="6" name="message" placeholder="Your message" required></textarea>
            <button type="submit" name="contact_submit" class="btn">Send message</button>
          </form>
        </article>

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

	<!-- main script -->
  <script src="<?= BASE_URL . 'static/js/index.js' ?>"></script>
  <!-- message alert from contact form -->
	<?php if (isset($_SESSION['message'])) : ?>
      <script>
          window.onload = function() {alert('<?= $_SESSION['message'] ?>')}
			</script>
	<?php unset($_SESSION['message']); endif ?>
</body>
</html>