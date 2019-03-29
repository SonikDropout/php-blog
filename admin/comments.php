<?php  include('../config.php');
  include(ROOT_PATH . '/admin/includes/admin_functions.php');
  include(ROOT_PATH . '/admin/includes/post_functions.php');
  include(ROOT_PATH . '/admin/includes/head_section.php');

  $comments = getAllComments();
?>
	<title>Admin | Manage Comments</title>
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

			<?php if (empty($comments)): ?>
				<h1 style="text-align: center; margin-top: 20px;">No comments in the database.</h1>
			<?php else: ?>
				<table class="table">
						<thead>
						<th>№</th>
						<th>Author</th>
						<th>Post</th>
						<th>Content</th>
						<th><small>Delete</small></th>
					</thead>
					<tbody>
					<?php foreach ($comments as $key => $comment): ?>
						<tr>
							<td><?= $key + 1; ?></td>
							<td><?= $comment['author']; ?></td>
							<td>
								<a 	target="_blank"
								href="<?= BASE_URL . 'single_post.php?post-slug=' . $comment['post_slug'] ?>">
									<?= $comment['post_title']; ?>	
								</a>
							</td>
							<td><?= substr($comment['body'], 0, 30) . (strlen($comment['body']) > 30 ? '...' : ''); ?></td>
							
							<td>
								<a  class="fa fa-trash btn delete" title="delete comment"
									href="create_post.php?delete-comment=<?= $comment['id'] ?>">
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