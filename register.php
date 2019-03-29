<?php  include('config.php'); ?>
<?php  include('includes/registration_login.php'); ?>
<?php  include('includes/head_section.php'); ?>
	<title><?= BLOG_NAME ?> | Register </title>
</head>
<body style="background-image: url('<?= BASE_URL ?>/static/images/bg.jpg')">
	<!-- content -->
	<div class="content">

		<form action="register.php" method="POST" class="login-form">
			<h2>Register</h2>
			<?php include(ROOT_PATH . '/includes/errors.php'); ?>
			<input type="text" name="username" placeholder="Username" required>
			<input type="email" name="email" placeholder="Email" required>
			<input name="password" type="password" placeholder="Password" required>
      <input name="passwordConfirmation" type="password" placeholder="Confirm password" required>
			<button type="submit" name="reg_user" class="btn">Register</button>
			<a href="login.php">Log In</a> &bull;
			<a href="<?= HOST_URL . $_SESSION['url'] ?>">Cancel</a>
    </form>

	</div>
	<!-- // content -->
</body>

</html>