<?php
// Post variables
$post_id = 0;
$isEditingPost = false;
$published = 0;
$title = "";
$tags = "";
$post_slug = "";
$body = "";
$featured_image = "";
$topic_ids = array();

/* - - - - - - - - - -
-  Post functions
- - - - - - - - - - -*/
// get all posts from DB
function getAllPosts()
{
    global $pdo;
    
    // Admin can view all posts
    $sql = "SELECT posts.*, users.username AS author FROM posts INNER JOIN users on posts.user_id = users.id";
    // Author can only view their posts
    if ($_SESSION['user']['role'] == "Author") {
        $user_id = $_SESSION['user']['id'];
        $sql = $sql . " WHERE posts.user_id=$user_id";
    }
    $posts = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

    return $posts;
}
/* - - - - - - - - - -
-  Post actions
- - - - - - - - - - -*/
// if user clicks the create post button
if (isset($_POST['create_post'])) {
    createPost($_POST);
}
// if user clicks the Edit post button
if (isset($_GET['edit-post'])) {
    $isEditingPost = true;
    $post_id = $_GET['edit-post'];
    editPost($post_id);
}
// if user clicks the update post button
if (isset($_POST['update_post'])) {
    updatePost($_POST);
}
// if user clicks the Delete post button
if (isset($_GET['delete-post'])) {
    $post_id = $_GET['delete-post'];
    deletePost($post_id);
}

/* - - - - - - - - - -
-  Post functions
- - - - - - - - - - -*/
function createPost($request_values)
{
    global $pdo, $errors, $title, $tags, $featured_image, $topic_ids, $body, $published, $post_slug;
    setPostData($request_values);

    // validate form
    validatePostData();

    // compress and upload image
    if ($featured_image) {
        $image_destination = $target = "../static/images/" . basename($featured_image);
        compressImage($_FILES['featured_image']['tmp_name'], $image_destination, 70);
    }

    // Ensure that no post is saved twice.
    $post_check_query = $pdo->prepare("SELECT * FROM posts WHERE slug=?");
    $post_check_query->execute(array($post_slug));

    if ($post_check_query->rowCount()) { // if post exists
        array_push($errors, "A post already exists with that title.");
    }
    // create post if there are no errors in the form
    if (count($errors) == 0) {
        $query = $pdo->prepare(
                "INSERT INTO posts
				 (user_id, title, slug, tags, image, body, published, created_at, updated_at)
				 VALUES(:uid, :title, :slug, :tags, :image, :body, :published, now(), now())"
            );
        $success = $query->execute(array(
                'uid' => $_SESSION['user']['id'],
                'title' => $title,
                'slug' => $post_slug,
                'tags' => $tags,
                'image' => $featured_image,
                'body' => $body,
                'published' => $published,
            ));
        if ($success) { // if post created successfully
            $inserted_post_id = $pdo->lastInsertId();
            // create relationship between post and topic
            if (insertPostTopics($inserted_post_id, $topic_ids)) {
                $_SESSION['message'] = "Post created successfully";
                header('location: posts.php');
                exit(0);
            }
        }
    }
}

function setPostData($request_values) {
    global $post_slug, $title, $tags, $featured_image, $topic_ids, $body, $published;
    $title = esc($request_values['title']);
    $tags = esc($request_values['tags']);
    $body = esc($request_values['body']);
    $published = esc($request_values['publish']);
    $topic_ids = $request_values['topic_id'] ?? array();
    if (isset($_FILES['featured_image'])) {
        $featured_image = $_FILES['featured_image']['name'];
    } 
    // create slug: if title is "The Storm Is Over", return "the-storm-is-over" as slug
    $post_slug = makeSlug($title);
}

function validatePostData()
{
    global $errors, $title, $tags, $featured_image, $topic_ids, $body, $published;
    if (empty($title)) {
        array_push($errors, "Post title is required");
    }
    if (empty($tags)) {
        array_push($errors, "Post tags are required");
    }
    if (empty($body)) {
        array_push($errors, "Post body is required");
    }
    if (empty($topic_ids)) {
        array_push($errors, "Post topic is required");
    }
    // Get image name
    if (empty($featured_image)) {
        array_push($errors, "Featured image is required");
    }

    //validate image type
    $valid_ext = array('png','gif','jpg', 'jpeg');
    $file_extension = pathinfo($_FILES['featured_image']['tmp_name'], PATHINFO_EXTENSION);
    $file_extension = strtolower($file_extension);

    if (in_array($file_extension, $valid_ext)) {
        array_push($errors, "Invalid image type. Please upload either jpg, png or gif.");
    }
}

function compressImage($source, $destination, $quality)
{
    list($width, $height, $type) = getimagesize($source);

    
    if ($type == 2) {
        $image = imagecreatefromjpeg($source);
    } elseif ($type == 1) {
        $image = imagecreatefromgif($source);
    } elseif ($type == 3) {
        $image = imagecreatefrompng($source);
    }
    
    $new_width = 1920;
    $new_height = $height * $new_width/$width;

    $resizedImg = imagecreatetruecolor( $new_width, $new_height );
    imagecopyresampled( $resizedImg, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
    imagejpeg( $resizedImg, $destination, $quality );
    imagedestroy($image);
    imagedestroy($resizedImg);
}

function compressImagick($source, $destination, $quality) {
    $imagick = new \Imagick($source);
    $height = $imagick->getImageHeight();
    $width = $imagick->getImageWidth();

    $imagick->resizeImage(1920, 0,  \Imagick::FILTER_LANCZOS, 1);

    $imagick->setImageCompression(\Imagick::COMPRESSION_JPEG);
    $imagick->setImageCompressionQuality($quality);
    $imagick->stripImage();
    if($imagick->writeimage($destination)){
    $imagick->destroy();
    return $uniqueName.'-thumb.jpg';
    }
}

function insertPostTopics(int $post_id, array $topic_ids) {
    global $pdo;
    $pdo->query("DELETE FROM post_topic WHERE post_id=$post_id");
    $stmt = $pdo->prepare("INSERT INTO post_topic (post_id, topic_id) VALUES($post_id, ?)");
    foreach($topic_ids as $topic_id) {
        if (!$stmt->execute(array($topic_id))){
            return false;
        }
    }
    return true;
}



/* * * * * * * * * * * * * * * * * * * * *
* - Takes post id as parameter
* - Fetches the post from database
* - sets post fields on form for editing
* * * * * * * * * * * * * * * * * * * * * */
function editPost($post_id)
{
    global $pdo, $tags, $title, $featured_image, $post_slug, $body, $published, $isEditingPost, $topic_ids;
    // get post
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id=?");
    $stmt->execute(array($post_id));
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
    
    //get post topics
    $stmt = $pdo->prepare("SELECT pt.topic_id FROM posts ps JOIN post_topic pt ON ps.id = pt.post_id WHERE pt.post_id=?");
    $stmt->execute(array($post_id));
    while ($topic_id = $stmt->fetchColumn()) {
        array_push($topic_ids, $topic_id);
    }
    // set form values on the form to be updated
    $title = $post['title'];
    $tags = $post['tags'];
    $body = $post['body'];
    $published = $post['published'];
    $featured_image = $post['image'];
    
}

function updatePost($request_values)
{
    global $pdo, $errors, $post_id, $title, $tags, $featured_image, $topic_ids, $body, $published, $post_slug;
    // get id of post currently being edited
    $post_id = esc($request_values['post_id']);
    
    setPostData($request_values);

    validatePostData();


    // compress and upload new image
    if (isset($_FILES['featured_image'])) {
        $image_destination = "../static/images/" . basename($featured_image);
        compressImage($_FILES['featured_image']['tmp_name'], $image_destination, 70);
    }

    // register topic if there are no errors in the form
    if (count($errors) == 0) {
        $stmt = $pdo->prepare(
            "UPDATE posts SET 
            title=:title, 
            tags=:tags, 
            slug=:slug, 
            image=:image, 
            body=:body, 
            published=:published, 
            updated_at=now() 
            WHERE id=:post_id"
        );
        $success = $stmt->execute(array(
            'title' => $title,
            'tags' => $tags,
            'slug' => $post_slug,
            'image' => $featured_image,
            'body' => $body,
            'published' => $published,
            'post_id' => $post_id
        ));
        // attach topic to post on post_topic table
        if ($success) { // if post updated successfully
            // create relationship between post and topic
            if (insertPostTopics($post_id, $topic_ids)) {
                $_SESSION['message'] = "Post updated successfully";
                header('location: posts.php');
                exit(0);
            }
        }
        $_SESSION['message'] = "Post updated successfully";
        header('location: posts.php');
        exit(0);
    }
}
// delete blog post
function deletePost($post_id)
{
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id=?");
    if ($stmt->execute(array($post_id))) {
        $_SESSION['message'] = "Post successfully deleted";
        header("location: posts.php");
        exit(0);
    }
}

if (isset($_GET['publish']) || isset($_GET['unpublish'])) {
    $message = "";
    if (isset($_GET['publish'])) {
        $message = "Post published successfully";
        $post_id = $_GET['publish'];
    } elseif (isset($_GET['unpublish'])) {
        $message = "Post successfully unpublished";
        $post_id = $_GET['unpublish'];
    }
    togglePublishPost($post_id, $message);
}

function togglePublishPost($post_id, $message)
{
    global $pdo;
    $stmt = $pdo->prepare("UPDATE posts SET published=!published WHERE id=?");
    
    if ($stmt->execute(array($post_id))) {
        $_SESSION['message'] = $message;
        header("location: posts.php");
        exit(0);
    }
}
