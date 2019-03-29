<?php  
	include('../config.php');
	include(ROOT_PATH . '/admin/includes/admin_functions.php');
	$numberOfUsers = getTotalNumberOfRows('users');
	$numberOfPosts = getTotalNumberOfRows('posts');
	$numberOfComments = getTotalNumberOfRows('comments');
	include(ROOT_PATH . '/admin/includes/head_section.php');
?>
	<title>Admin | Dashboard</title>
</head>
<body>
	
	<?php include(ROOT_PATH . '/admin/includes/navbar.php'); ?>

	<div class="container dashboard">
		<h1>Welcome</h1>
		<!-- Display notification message -->
		<?php include(ROOT_PATH . '/includes/messages.php') ?>
		<div class="stats">
			<a href="users.php" class="first">
				<span><?= $numberOfUsers ?></span> <br>
				<span>Registered users</span>
			</a>
			<a href="posts.php">
				<span><?= $numberOfPosts ?></span> <br>
				<span>Published posts</span>
			</a>
			<a href="comments.php">
				<span><?= $numberOfComments ?></span> <br>
				<span>Published comments</span>
			</a>
		</div>
		<br><br><br>
		<div class="buttons">
			<?php if($_SESSION['user']['role'] === 'Admin'): ?>
				<a href="users.php">Add Users</a>
			<?php endif; ?>
			<a href="create_post.php">Add Post</a>
		</div>
	</div>
</body>
</html>