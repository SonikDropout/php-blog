<?php 
/* * * * * * * * * * * * * * *
* Returns all published posts
* * * * * * * * * * * * * * */
function getPublishedPostsForPage($pageNumber = 0) {
	// use global $pdo object in function
	global $pdo;

	$postsPerPage = 5;

	$sql = "SELECT posts.*, 
	users.username AS authorName, 
	users.email AS authorEmail, 
	COUNT(comments.id) AS commentsCount
	FROM posts JOIN users 
	ON posts.user_id = users.id 
	LEFT JOIN comments
	ON posts.id = comments.post_id 
	WHERE posts.published = true 
	GROUP BY posts.id 
	LIMIT ?, $postsPerPage";

	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($postsPerPage * $pageNumber));

	// fetch all posts as an associative array called $posts
	$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

	for ($i = 0; $i < count($posts); $i++) {
		$posts[$i]['topics'] = getPostTopics($posts[$i]['id']); 
	}
	return $posts;
}
/* * * * * * * * * * * * * * *
* Receives a post id and
* Returns topic of the post
* * * * * * * * * * * * * * */
function getPostTopics($post_id){
	global $pdo;
	$sql = "SELECT topics.id, topics.name 
    FROM topics JOIN post_topic
    ON post_topic.topic_id=topics.id
    WHERE post_topic.post_id=$post_id";
  $result = $pdo->query($sql);
	$topics = $result->fetchAll(PDO::FETCH_ASSOC);
	return $topics;
}
/* * * * * * * * * * * * * * * *
* Returns all posts under a topic
* * * * * * * * * * * * * * * * */
function getPublishedPostsByTopic($topic_id) {
	$sql = "SELECT posts.*, 
		users.username AS authorName, 
		users.email AS authorEmail,
		COUNT(comments.id) AS commentsCount
		FROM posts JOIN users ON posts.user_id = users.id 
		JOIN post_topic ON posts.id = post_topic.post_id 
		JOIN topics ON post_topic.topic_id = topics.id
		LEFT JOIN comments ON comments.post_id = posts.id
		WHERE posts.published = true AND post_topic.topic_id=:param
		GROUP BY posts.id";

	$posts = getPostsByQuery($sql, $topic_id);

	return $posts;
}
/* * * * * * * * * * * * * * * *
* Returns all posts under a tag
* * * * * * * * * * * * * * * * */
function getPublishedPostsByTag($tag) {
	$param = "%$tag%";
	$sql = "SELECT posts.*, 
		users.username AS authorName, 
		users.email AS authorEmail,
		COUNT(comments.id) AS commentsCount
		FROM posts JOIN users ON posts.user_id = users.id 
		LEFT JOIN comments ON comments.post_id = posts.id
		WHERE posts.published = true AND posts.tags LIKE :param
		GROUP BY posts.id";

	$posts = getPostsByQuery($sql, $param);

	return $posts;
}
/* * * * * * * * * * * * * * * * * * * * * *
* Returns all posts under a search query
* * * * * * * * * * * * * * * * * * * * * */
function getPublishedPostsBySearchQuery($query) {
	$param = '%' . strtolower(trim($query)) . '%';
	$sql = "SELECT posts.*, 
		users.username AS authorName, 
		users.email AS authorEmail,
		COUNT(comments.id) AS commentsCount
		FROM posts JOIN users ON posts.user_id = users.id 
		LEFT JOIN comments ON comments.post_id = posts.id
		WHERE posts.published = true 
		AND LCASE(posts.body) LIKE :param
		GROUP BY posts.id";

	$posts = getPostsByQuery($sql, $param);

	return $posts;
}
/* * * * * * * * * * * * * * * * * * * * * *
* Returns all published posts by date
* * * * * * * * * * * * * * * * * * * * * */
function getPublishedPostsByDate($month, $year) {
	global $pdo;

	//set from and to dates
	$from = date('Y-m-01 00:00:00', strtotime("$year-$month"));
	$to = date('Y-m-31 23:59:59', strtotime("$year-$month"));

	$sql = "SELECT posts.*, 
		users.username AS authorName, 
		users.email AS authorEmail,
		COUNT(comments.id) AS commentsCount
		FROM posts JOIN users ON posts.user_id = users.id 
		LEFT JOIN comments ON comments.post_id = posts.id
		WHERE posts.published = true 
		AND posts.created_at >= :from
		AND posts.created_at <= :to
		GROUP BY posts.id";
	
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array(
			':from' => $from,
			':to' => $to
	));

	$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

	for ($i = 0; $i < count($posts); $i++) {
		$posts[$i]['topics'] = getPostTopics($posts[$i]['id']); 
	}

	return $posts;
}
/* * * * * * * * * * * * * * * * * * * * * *
* Returns all posts with applied filter
* * * * * * * * * * * * * * * * * * * * * */
function getPostsByQuery($sql, $param) {
	global $pdo;

	$stmt = $pdo->prepare($sql);
	$stmt->execute(array(':param' => $param));
	// fetch all posts as an associative array called $posts
	$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

	for ($i = 0; $i < count($posts); $i++) {
		$posts[$i]['topics'] = getPostTopics($posts[$i]['id']); 
	}
	return $posts;
}
/* * * * * * * * * * * * * * * *
* Returns topic name by topic id
* * * * * * * * * * * * * * * * */
function getTopicNameById($id)
{
	global $pdo;
	$sql = "SELECT name FROM topics WHERE id=$id";
  $result = $pdo->query($sql);
	$topic = $result->fetchColumn();
	return $topic;
}
/* * * * * * * * * * * * * * *
* Returns a single post
* * * * * * * * * * * * * * */
function getPost($slug){
	global $pdo;

	$sql = "SELECT posts.*, 
		users.username AS authorName, 
		users.email AS authorEmail
		FROM posts JOIN users 
		ON users.id = posts.user_id
	  WHERE posts.slug=? AND posts.published=true";
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($slug));
	// fetch query results as associative array.
	$post = $stmt->fetch(PDO::FETCH_ASSOC);
	if ($post) {
		// get the comments which belong to this post
		$post['comments'] = getPostComments($post['id']);
	}
	return $post;
}
/* * * * * * * * * * * * * * *
* Returns post comments
* * * * * * * * * * * * * * */
function getPostComments($post_id){
	global $pdo;

	$sql = "SELECT comments.body,
	  users.username AS author
    FROM posts JOIN comments ON posts.id = comments.post_id
		JOIN users ON users.id = comments.user_id
    WHERE posts.id=$post_id";
  $result = $pdo->query($sql);
	$comments = $result->fetchAll(PDO::FETCH_ASSOC);
	return $comments;
}
/* * * * * * * * * * * *
*  Returns all topics
* * * * * * * * * * * * */
function getAllTopics()
{
	global $pdo;
	$sql = "SELECT * FROM topics";
  $result = $pdo->query($sql);
  $topics = $result->fetchAll(PDO::FETCH_ASSOC);
	return $topics;
}
/* * * * * * * * * * * *
*  Returns recent posts
* * * * * * * * * * * * */
function getRecentPosts()
{
	global $pdo;
	$sql = "SELECT title, slug FROM posts ORDER BY id DESC LIMIT 5";
  $result = $pdo->query($sql);
  $topics = $result->fetchAll(PDO::FETCH_ASSOC);
	return $topics;
}
/* * * * * * * * * * * *
*  Returns archives
* * * * * * * * * * * * */
function getArchives()
{
	global $pdo;
	$sql = "SELECT MONTH(created_at) as month, 
		YEAR(created_at) as year 
		FROM posts 
		GROUP BY MONTH(created_at), 
		YEAR(created_at) 
		ORDER BY created_at DESC";
  $result = $pdo->query($sql);
  $archives = $result->fetchAll(PDO::FETCH_ASSOC);
	return $archives;
}
/* * * * * * * * * * * *
*   Views counter
* * * * * * * * * * * * */
function incrementPostViews($post_slug)
{
	global $pdo;
	$sql = "UPDATE posts SET views = views + 1 WHERE slug = ?";
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($post_slug));
}
// more functions to come here ...
?>