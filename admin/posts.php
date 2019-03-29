<?php  include('../config.php'); ?>
<?php  include(ROOT_PATH . '/admin/includes/admin_functions.php'); ?>
<?php  include(ROOT_PATH . '/admin/includes/post_functions.php'); ?>
<?php include(ROOT_PATH . '/admin/includes/head_section.php'); ?>

<!-- Get all admin posts from DB -->
<?php $posts = getAllPosts(); ?>
	<title>Admin | Manage Posts</title>
</head>
<body>
	<!-- admin navbar -->
	<?php include(ROOT_PATH . '/admin/includes/navbar.php') ?>

	<div class="container content">
		<!-- Left side menu -->
		<?php include(ROOT_PATH . '/admin/includes/menu.php') ?>

		<!-- Display records from DB-->
		<div class="table-div"  style="width: 80%;">
			<!-- Display notification message -->
			<?php include(ROOT_PATH . '/includes/messages.php') ?>

			<?php if (empty($posts)): ?>
				<h1 style="text-align: center; margin-top: 20px;">No posts available for you in database.</h1>
			<?php else: ?>
				<table class="table">
						<thead>
						<th>№</th>
						<th>Title</th>
						<th>Author</th>
						<th>Views</th>
						<!-- Only Admin can publish/unpublish post -->
						<?php if ($_SESSION['user']['role'] == "Admin"): ?>
							<th><small>Published</small></th>
						<?php endif ?>
						<th><small>Edit</small></th>
						<th><small>Delete</small></th>
					</thead>
					<tbody>
					<?php foreach ($posts as $key => $post): ?>
						<tr>
							<td><?= $key + 1; ?></td>
							<td>
								<a 	target="_blank"
								href="<?= BASE_URL . 'single_post.php?post-slug=' . $post['slug'] ?>">
									<?= $post['title']; ?>	
								</a>
							</td>
							<td><?= $post['author']; ?></td>
							<td><?= $post['views']; ?></td>
							
							<!-- Only Admin can publish/unpublish post -->
							<?php if ($_SESSION['user']['role'] == "Admin" ): ?>
								<td>
								<?php if ($post['published'] == true): ?>
									<a class="fa fa-check btn unpublish"
										href="posts.php?unpublish=<?= $post['id'] ?>">
									</a>
								<?php else: ?>
									<a class="fa fa-times btn publish"
										href="posts.php?publish=<?= $post['id'] ?>">
									</a>
								<?php endif ?>
								</td>
							<?php endif ?>

							<td>
								<a class="fa fa-pencil btn edit"
									href="create_post.php?edit-post=<?= $post['id'] ?>">
								</a>
							</td>
							<td>
								<a  class="fa fa-trash btn delete" 
									href="create_post.php?delete-post=<?= $post['id'] ?>">
								</a>
							</td>
						</tr>
					<?php endforeach ?>
					</tbody>
				</table>
			<?php endif ?>
		</div>
		<!-- // Display records from DB -->
	</div>
</body>
</html>