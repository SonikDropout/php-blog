<?php foreach ($posts as $post):
  $post_body_preview = substr($post['body'], 0, strpos($post['body'], '&lt;/p&gt;') + 10);
?>
  <article class="post-card">
    <div class="post-card-image">
      <img src="<?= BASE_URL . '/static/images/' . $post['image']; ?>" alt="<?= $post['image'] ?>">
    </div>
    <div class="post-card-body">
      <h2><a href="single_post.php?post-slug=<?= $post['slug']; ?>"><?= $post['title'] ?></a></h2>
      <h4 class="post-card-info">
        by <a href="mailto: <?= $post['authorEmail'] ?>"><?= $post['authorName'] ?></a>
        in 
        <?php foreach ($post['topics'] as $topic): ?>
          <a href="<?= BASE_URL . 'filtered_posts.php?topic=' . $topic['id'] ?>"><?= $topic['name'] ?></a>
        <?php endforeach; ?>
      </h4>
      <?= html_entity_decode($post_body_preview) ?>
      <ul class="post-card-tags">
        <i class="fa fa-tag"></i>
        <?php foreach(explode(',', $post['tags']) as $tag): ?>
          <li><a href="filtered_posts.php?tag=<?= $tag ?>"><?= $tag ?></a></li>
        <?php endforeach; ?>
      </ul>
      <div class="post-card-meta">
        <span title="post date"><?= date("F j, Y ", strtotime($post["created_at"])); ?></span>
        <span title="views"><i class="fa fa-eye"></i> <?= $post['views'] ?></span>
        <span title="comments"><i class="fa fa-comment-o"></i> <?= $post['commentsCount'] ?></span> &nbsp;
      </div>
    </div>
  </article>
<?php endforeach; ?>