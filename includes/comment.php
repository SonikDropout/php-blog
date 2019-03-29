<?php
// STORE COMMENT TO DB
if (isset($_POST['comment_submit'])) {
  $body = esc($_POST['comment']);
  $post_id = $_POST['post_id'];
  $user_id = $_SESSION['user']['id'];
  $slug = $_GET['post-slug'];

  if (empty($body)) {
      array_push($errors, "Are you actually going to comment or not?");
  }
  else if (strlen($body) > 300) {
      array_push($errors, "Wow! Consider joining our authors team if you like writing such detailed comments");
  }

  if (count($errors) == 0) {
    $stmt = $pdo->prepare("INSERT INTO comments (user_id, post_id, body, created_at) 
    VALUES($user_id, ?, ?, now())");
    if ($stmt->execute(array($post_id, $body))) {
      header('location: ' . BASE_URL . '/single_post.php?post-slug=' . $slug);
      exit(0);
    } else {
      array_push($erros, 'Failed to store comment');
      header('location: ' . BASE_URL . '/single_post.php?post-slug=' . $slug);
      exit(0);
    }
  }
}