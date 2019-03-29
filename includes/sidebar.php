<?php
$recentPosts = getRecentPosts();
$topics = getAllTopics();
$archives = getArchives();
?>
<aside>
  <form action="filtered_posts.php" autocomplete="off" class="search-form">
    <input type="text" name="search" placeholder="Search" required />
    <button type="submit" name="search_submit" class="btn"><i class="fa fa-search"></i></button>
  </form>
  <div class="sidebar-card">
    <h3>Recent posts</h3>
    <ul>
      <?php foreach($recentPosts as $post): ?>
        <li><a href="single_post.php?post-slug=<?= $post['slug'] ?>"><?= $post['title'] ?></a></li>
      <?php endforeach; ?>
    </ul>
  </div>
  <div class="sidebar-card">
    <h3>Topics</h3>
    <ul>
     <?php foreach($topics as $topic): ?>
        <li><a href="filtered_posts.php?topic=<?= $topic['id'] ?>"><?= $topic['name'] ?></a></li>
      <?php endforeach; ?>
    </ul>
  </div>
  <div class="sidebar-card">
    <h3>Archives</h3>
    <ul>
     <?php foreach($archives as $archive): 
      $dateObj   = DateTime::createFromFormat('!m', $archive['month']);
      $monthName = $dateObj->format('F');?>
        <li><a href="filtered_posts.php?month=<?= $archive['month'] ?>&year=<?= $archive['year'] ?>"><?= $monthName . ', ' . $archive['year'] ?></a></li>
      <?php endforeach; ?>
    </ul>
  </div>
</aside>