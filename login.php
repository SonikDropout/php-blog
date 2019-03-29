<?php  include('config.php'); ?>
<?php  include('includes/registration_login.php'); ?>
<?php  include('includes/head_section.php'); ?>
	<title><?= BLOG_NAME ?>  | Log in </title>
</head>
<body style="background-image: url('<?= BASE_URL ?>/static/images/bg.jpg')">
	<!-- content -->
	<div class="content">

		<form action="login.php" method="POST" class="login-form">
			<h2>Log in</h2>
			<?php include(ROOT_PATH . '/includes/errors.php'); ?>
			<input type="text" name="username" placeholder="Username" required>
			<input type="password" name="password" placeholder="Password" required>
			<button type="submit" name="login_btn" class="btn">Log in</button>
			<a href="register.php">Register</a> &bull;
			<a href="<?= HOST_URL . $_SESSION['url'] ?>">Cancel</a>
    </form>

	</div>
	<!-- // content -->
</body>

</html>