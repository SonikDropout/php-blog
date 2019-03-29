<?php  include('../config.php'); ?>
<?php  include(ROOT_PATH . '/admin/includes/admin_functions.php'); ?>
<?php  include(ROOT_PATH . '/admin/includes/post_functions.php'); ?>
<?php include(ROOT_PATH . '/admin/includes/head_section.php'); ?>
<!-- Get all topics -->

<?php $topics = getAllTopics();	?>
	<title>Admin | Create Post</title>
</head>
<body>
	<!-- admin navbar -->
	<?php include(ROOT_PATH . '/admin/includes/navbar.php') ?>

	<div class="container content">
		<!-- Left side menu -->
		<?php include(ROOT_PATH . '/admin/includes/menu.php') ?>

		<!-- Middle form - to create and edit  -->
		<div class="action create-post-div">
			<h1 class="page-title">Create/Edit Post</h1>
			<form method="post" enctype="multipart/form-data" action="<?= HOST_URL . $_SERVER['REQUEST_URI'] ?>" >
				<!-- validation errors for the form -->
				<?php include(ROOT_PATH . '/includes/errors.php') ?>

				<!-- if editing post, the id is required to identify that post -->
				<?php if ($isEditingPost === true): ?>
					<input type="hidden" name="post_id" value="<?= $post_id; ?>">
				<?php endif ?>

				<input type="text" name="title" value="<?= $title; ?>" placeholder="Title">
				<input type="text" name="tags" value="<?= $tags; ?>" placeholder="Comma separated tags">
				<label style="float: left; margin: 5px auto 5px;">Featured image</label>
				<input type="file" name="featured_image" accept=".png, .jpg, .gif">
				<textarea name="body" id="body" cols="30" rows="10"><?= $body; ?></textarea>
				<fieldset name="topic_id[]">
				<legend>Topics</legend>
					<?php foreach ($topics as $topic): $checked = in_array($topic['id'], $topic_ids); ?>
						<label>
							&nbsp;<?= $topic['name']; ?>
							<input type="checkbox" value="<?= $topic['id'] ?>" name="topic_id[]" <?= $checked ? 'checked' : '' ?>>
						</label>
					<?php endforeach ?>
				</fieldset>
				
				<!-- Only admin users can view publish input field -->
				<?php if ($_SESSION['user']['role'] == "Admin"): ?>
					<!-- display checkbox according to whether post has been published or not -->
					<fieldset>
						<legend>Publish</legend>
						<label>
							&nbsp;<input type="radio" value="1" name="publish" <?= $published ? 'checked': '' ?>>
							Yes
						</label>
						<label>
							&nbsp;<input type="radio" value="0" name="publish" <?= $published ? '': 'checked' ?>>
							No
						</label>
					</fieldset>
				<?php endif ?>
				
				<!-- if editing post, display the update button instead of create button -->
				<?php if ($isEditingPost === true): ?> 
					<button type="submit" class="btn" name="update_post">Update Post</button>
				<?php else: ?>
					<button type="submit" class="btn" name="create_post">Save Post</button>
				<?php endif ?>

			</form>
		</div>
		<!-- // Middle form - to create and edit -->
	</div>
</body>
</html>

<script>
	CKEDITOR.replace('body');
</script>