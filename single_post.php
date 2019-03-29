
<?php
	require_once('config.php');
	require_once(ROOT_PATH . '/includes/public_functions.php');
	require_once(ROOT_PATH . '/includes/registration_login.php');
	require_once(ROOT_PATH . '/includes/comment.php');
	
	if (isset($_GET['post-slug'])) {
		$post = getPost($_GET['post-slug']);
		incrementPostViews($_GET['post-slug']);
	}

	require_once(ROOT_PATH . '/includes/head_section.php');
?>
	<title><?= BLOG_NAME ?>  | <?= $post['title'] ?> </title>
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
				<?php if(!empty($post)): ?>
				<article class="post">
          <img src="<?= BASE_URL . '/static/images/' . $post['image']; ?>" alt="" class="post-image">
          <h2><?= $post['title'] ?></h2>
          <h4 class="post-info">
            posted on <?= date("F j, Y ", strtotime($post["created_at"])); ?>
            by <a href="mailto: <?= $post['authorEmail'] ?>"><?= $post['authorName'] ?></a>
          </h4>
          <div class="post-body">
           <?= html_entity_decode($post['body']); ?>
          </div>
				</article>
				
				<!-- comment section -->
        <section class="comments">
					<?php if(isset($_SESSION['user'])): ?>
						<form action="single_post.php?post-slug=<?= $post['slug'] ?>" method="POST" class="comment-form">
							<h3>leave a comment:</h3>
							<?php include(ROOT_PATH . '/includes/errors.php'); ?>
							<input type="hidden" name="post_id" value="<?= $post['id'] ?>" />
							<textarea name="comment" id="" rows="8" palceholder="Leave comment"></textarea>
							<button class="btn" type="submit" name="comment_submit">Submit</button>
						</form>
					<?php else: ?>
						<p class="comment-message">
							<a href="login.php">Log In</a> |
							<a href="register.php">Register</a> to comment on the article
						</p>
					<?php endif; ?>

					<?php foreach($post['comments'] as $comment): ?>
						<div class="comment">
							<h5><?= $comment['author'] ?></h5>
							<p><?= $comment['body'] ?></p>
						</div>
					<?php endforeach; ?>

        </section>
				<!-- // comment section -->
				<?php else: ?>
					<h2 style="text-align: center">No post found</h2>
				<?php endif ?>
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