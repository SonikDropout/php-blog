<?php
    // include utils for form validation
    require_once(ROOT_PATH . '/common_utils.php');
    // variable declaration
    $username = "";
    $email    = "";
    $errors = array();

    // REGISTER USER
    if (isset($_POST['reg_user'])) {
        // receive all input values from the form
        $username = esc($_POST['username']);
        $email = esc($_POST['email']);
        $password = esc($_POST['password']);
        $passwordConfirmation = esc($_POST['passwordConfirmation']);

        // form validation using function from common_utils
        $user_data = compact(explode(' ', 'username email password passwordConfirmation'));
        validateUser($user_data);
        // register user if there are no errors in the form
        if (count($errors) == 0) {
            $password = hash("sha256", $password);//encrypt the password before saving in the database
            $query = $pdo->prepare("INSERT INTO users (username, email, password, created_at, updated_at) 
					  VALUES(?, ?, ?, now(), now())");
            $query->execute(array($username, $email, $password));

            // get id of created user
            $reg_user_id = $pdo->lastInsertId();

            // put logged in user into session array
            $_SESSION['user'] = getUserById($reg_user_id);

            // redirect user accordingly to his role
            redirectAfterLogIn();
        }
    }

    // LOG USER IN
    if (isset($_POST['login_btn'])) {
        $username = esc($_POST['username']);
        $password = esc($_POST['password']);

        if (empty($username)) {
            array_push($errors, "Username required");
        }
        if (empty($password)) {
            array_push($errors, "Password required");
        }
        if (empty($errors)) {
            $password = hash("sha256", $password); // encrypt password
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username=? and password=? LIMIT 1");
            $stmt->execute(array($username, $password));

            // check if right credentials
            if (!$stmt->rowCount()) {
                array_push($errors, 'Wrong credentials');
            } else {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                // put logged in user into session array
                $_SESSION['user'] = $user;

                // redirect user accordingly to his role
                redirectAfterLogIn();
            }
        }
    }
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * *
    * - Escape any special characters in string
    * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    function esc(String $str)
    {
        return htmlentities(trim($str));
    }
    // Get user info from user id
    function getUserById($id)
    {
        global $pdo;
        $sql = "SELECT * FROM users WHERE id=$id";

        $result = $pdo->query($sql);
        $user = $result->fetch(PDO::FETCH_ASSOC);

        // returns user in an array format:
        return $user;
    }
    function redirectAfterLogIn()
    {
        if (in_array($_SESSION['user']['role'], ["Admin", "Author"])) {
            $_SESSION['message'] = "You are now logged in";
            // redirect to admin area
            header('location: ' . BASE_URL . '/admin/dashboard.php');
            exit(0);
        } else if (isset($_SESSION['url'])) {
            $_SESSION['message'] = "You are now logged in";
            // redirect user back to where he was
            header('location: ' . HOST_URL . $_SESSION['url']);
            exit(0);
        } else {
            $_SESSION['message'] = "You are now logged in";
            // redirect to public area
            header('location: index.php');
            exit(0);
        }
    }
