<?php 
// Utils for form validation
require_once(ROOT_PATH . '/common_utils.php');
// Admin user variables
$admin_id = 0;
$isEditingUser = false;
$username = "";
$role = "";
$email = "";
// general variables
$errors = [];

/* - - - - - - - - - - - -
-  Admin dashboard utils
- - - - - - - - - - - - - */
function getTotalNumberOfRows($table_name) {
	global $pdo;
	$sql = "SELECT COUNT($table_name.id) FROM $table_name";
	$result = $pdo->query($sql);
	$numberOfRows = $result->fetchColumn();
	return $numberOfRows;
}

/* - - - - - - - - - - 
-  Admin users actions
- - - - - - - - - - -*/
// if user clicks the create admin button
if (isset($_POST['create_admin'])) {
	createAdmin($_POST);
}
// if user clicks the Edit admin button
if (isset($_GET['edit-admin'])) {
	$isEditingUser = true;
	$admin_id = $_GET['edit-admin'];
	editAdmin($admin_id);
}
// if user clicks the update admin button
if (isset($_POST['update_admin'])) {
	updateAdmin($_POST);
}
// if user clicks the Delete admin button
if (isset($_GET['delete-admin'])) {
	$admin_id = $_GET['delete-admin'];
	deleteAdmin($admin_id);
}

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * 
* - Escape any special characters in string
* * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
function esc(String $str) {
  return htmlentities(trim($str));
}
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * 
* - Returns all admin users and their corresponding roles
* * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
function getAdminUsers(){
	global $pdo, $roles;
	$sql = "SELECT * FROM users WHERE role IS NOT NULL";
	$result = $pdo->query($sql);
	$users = $result->fetchAll(PDO::FETCH_ASSOC);

	return $users;
}
// Receives a string like 'Some Sample String'
// and returns 'some-sample-string'
function makeSlug(String $string){
	$string = strtolower($string);
	$slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
	return $slug;
}
/* - - - - - - - - - - - -
-  Admin users functions
- - - - - - - - - - - - -*/
/* * * * * * * * * * * * * * * * * * * * * * *
* - Receives new admin data from form
* - Create new admin user
* - Returns all admin users with their roles 
* * * * * * * * * * * * * * * * * * * * * * */
function createAdmin($request_values){
	global $pdo, $errors, $role, $username, $email;
	$username = esc($request_values['username']);
	$email = esc($request_values['email']);
	$password = esc($request_values['password']);
	$passwordConfirmation = esc($request_values['passwordConfirmation']);
	    var_dump('Set password: ' . ($password ? $password: 'empty'));

	if(isset($request_values['role'])){
		$role = esc($request_values['role']);
	}
	// form validation using function from common_utils
	$user_data = compact(explode(' ', 'username email password passwordConfirmation'));
	validateUser($user_data, $errors);
	// don't forget that role is not validated by validateUser function
	if (empty($role)) { array_push($errors, "Role is required for admin users");}
	
	// prevent user duplication with function from common_utils
	checkIfUserAlreadyExists($username, $email);
	
	// register user if there are no errors in the form
	if (count($errors) == 0) {
		$password = hash("sha256", $password);//encrypt the password before saving in the database
		$stmt = $pdo->prepare("INSERT INTO users (username, email, role, password, created_at, updated_at) 
				  VALUES(?, ?, ?, ?, now(), now())");
		$stmt->execute(array($username, $email, $role, $password));

		$_SESSION['message'] = "Admin user created successfully";
		header('location: users.php');
		exit(0);
	}
}
/* * * * * * * * * * * * * * * * * * * * *
* - Takes admin id as parameter
* - Fetches the admin from database
* - sets admin fields on form for editing
* * * * * * * * * * * * * * * * * * * * * */
function editAdmin($admin_id)
{
	global $pdo, $username, $role, $isEditingUser, $admin_id, $email;

	$sql = "SELECT * FROM users WHERE id=$admin_id LIMIT 1";
	$result = $pdo->query($sql);
	$admin = $result->fetch(PDO::FETCH_ASSOC);

	// set form values ($username and $email) on the form to be updated
	$username = $admin['username'];
	$email = $admin['email'];
	$role = $admin['role'];
}

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * 
* - Receives admin request from form and updates in database
* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
function updateAdmin($request_values){
	global $pdo, $errors, $role, $username, $isEditingUser, $admin_id, $email;
	
	// get id of the admin to be updated
	$admin_id = $request_values['admin_id'];
	// set edit state to false
	$isEditingUser = false;

	$username = esc($request_values['username']);
	$email = esc($request_values['email']);
	$password = esc($request_values['password']);
	$passwordConfirmation = esc($request_values['passwordConfirmation']);
	if(isset($request_values['role'])){
		$role = $request_values['role'];
	}

	// form validation using function from common_utils
	$user_data = compact(explode(' ', 'username email password passwordConfirmation'));
	validateUser($user_data);
	// don't forget that role is not validated by validateUser function
	if (empty($role)) { array_push($errors, "Role is required for admin users");}

	// update user if there are no errors in the form
	if (count($errors) == 0) {
		//encrypt the password (security purposes)
		$password = hash("sha256", $password);

		$stmt = $pdo->prepare("UPDATE users SET username=?, email=?, password=?, role=?, updated_at=now() WHERE id=?");
		$stmt->execute(array($username, $email, $password, $role, $admin_id));

		$_SESSION['message'] = "Admin user updated successfully";
		header('location: users.php');
		exit(0);
	}
}
// delete admin user 
function deleteAdmin($admin_id) {
	global $pdo;
	$stmt = $pdo->prepare("DELETE FROM users WHERE id=?");
	if ($stmt->execute(array($admin_id))) {
		$_SESSION['message'] = "User successfully deleted";
		header("location: users.php");
		exit(0);
	}
}


// Topics variables
$topic_id = 0;
$isEditingTopic = false;
$topic_name = "";

/* - - - - - - - - - - 
-  Topic actions
- - - - - - - - - - -*/
// if user clicks the create topic button
if (isset($_POST['create_topic'])) { createTopic($_POST); }
// if user clicks the Edit topic button
if (isset($_GET['edit-topic'])) {
	$isEditingTopic = true;
	$topic_id = $_GET['edit-topic'];
	editTopic($topic_id);
}
// if user clicks the update topic button
if (isset($_POST['update_topic'])) {
	updateTopic($_POST);
}
// if user clicks the Delete topic button
if (isset($_GET['delete-topic'])) {
	$topic_id = $_GET['delete-topic'];
	deleteTopic($topic_id);
}

/* - - - - - - - - - - 
-  Topics functions
- - - - - - - - - - -*/
function getAllTopics() {
	global $pdo;
	$sql = "SELECT * FROM topics";
	$result = $pdo->query($sql);
	$topics = $result->fetchAll(PDO::FETCH_ASSOC);
	return $topics;
}
function createTopic($request_values){
	global $pdo, $errors, $topic_name;
	$topic_name = esc($request_values['topic_name']);
	// create slug: if topic is "Life Advice", return "life-advice" as slug
	$topic_slug = makeSlug($topic_name);
	// validate form
	if (empty($topic_name)) { 
		array_push($errors, "Topic name required"); 
	}
	// Ensure that no topic is saved twice. 
	$topic_check_query = $pdo->prepare("SELECT * FROM topics WHERE slug=? LIMIT 1");
	$topic_check_query->execute(array($topic_slug));
	if ($topic_check_query->fetch()) { // if topic exists
		array_push($errors, "Topic already exists");
	}
	// register topic if there are no errors in the form
	if (count($errors) == 0) {
		$stmt = $pdo->prepare("INSERT INTO topics (name, slug) 
					VALUES(?, ?)");
		$stmt->execute(array($topic_name, $topic_slug));

		$_SESSION['message'] = "Topic created successfully";
		header('location: topics.php');
		exit(0);
	}
}
/* * * * * * * * * * * * * * * * * * * * *
* - Takes topic id as parameter
* - Fetches the topic from database
* - sets topic fields on form for editing
* * * * * * * * * * * * * * * * * * * * * */
function editTopic($topic_id) {
	global $pdo, $topic_name, $isEditingTopic, $topic_id;
	$stmt = $pdo->prepare("SELECT * FROM topics WHERE id=? LIMIT 1");
	$stmt->execute(array($topic_id));
	$topic = $stmt->fetch(PDO::FETCH_ASSOC);
	// set form values ($topic_name) on the form to be updated
	$topic_name = $topic['name'];
}
function updateTopic($request_values) {
	global $pdo, $errors, $topic_name, $topic_id;
	$topic_name = esc($request_values['topic_name']);
	$topic_id = esc($request_values['topic_id']);
	// create slug: if topic is "Life Advice", return "life-advice" as slug
	$topic_slug = makeSlug($topic_name);
	// validate form
	if (empty($topic_name)) { 
		array_push($errors, "Topic name required"); 
	}
	// register topic if there are no errors in the form
	if (count($errors) == 0) {
		$stmt = $pdo->prepare("UPDATE topics SET name=?, slug=? WHERE id=?");
		$stmt->execute(array($topic_name, $topic_slug, $topic_id));

		$_SESSION['message'] = "Topic updated successfully";
		header('location: topics.php');
		exit(0);
	}
}
function deleteTopic($topic_id) {
	global $pdo;
	$stmt = $pdo->prepare("DELETE FROM topics WHERE id=?");
	if ($stmt->execute(array($topic_id))) {
		$_SESSION['message'] = "Topic successfully deleted";
		header("location: topics.php");
		exit(0);
	}
}
/* - - - - - - - - - - 
-  Topic actions
- - - - - - - - - - -*/
// if user clicks the Delete comment button
if (isset($_GET['delete-comment'])) {
	$comment_id = $_GET['delete-comment'];
	deleteComment($comment_id);
}
/* - - - - - - - - - - 
-  Comments functions
- - - - - - - - - - -*/
function getAllComments() {
	global $pdo;
	$sql = "SELECT comments.*,
		users.username AS author,
		posts.title AS post_title,
		posts.slug AS post_slug
		FROM comments JOIN users ON users.id = comments.user_id
		JOIN posts ON posts.id = comments.post_id";
	$result = $pdo->query($sql);
	$topics = $result->fetchAll(PDO::FETCH_ASSOC);
	return $topics;
}
function deleteComment($comment_id) {
	global $pdo;
	$stmt = $pdo->prepare("DELETE FROM comments WHERE id=?");
	if ($stmt->execute(array($comment_id))) {
		$_SESSION['message'] = "Comment successfully deleted";
		header("location: comments.php");
		exit(0);
	}
}
?>