<header class="header">
	<div class="logo">
		<a href="<?= BASE_URL .'/admin/dashboard.php' ?>">
			<h1><?= BLOG_NAME ?> - Admin</h1>
		</a>
	</div>
	<div class="user-info">
		<span><?= $_SESSION['user']['username'] ?></span> &nbsp; &nbsp; <a href="<?= BASE_URL . '/logout.php'; ?>" class="logout-btn">logout</a>
	</div>
</header>