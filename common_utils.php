<?php
function validateUser($user_data)
{
    global $errors;
    // extract username, email, password etc...
    extract($user_data);

    if (empty($username)) {
        array_push($errors, "Uhmm...We gonna need the username");
    }
    if (empty($email)) {
        array_push($errors, "Oops.. Email is missing");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($errors, "Oops.. Invalid email address");
    }
    if (empty($password)) {
        array_push($errors, "uh-oh you forgot the password");
    } elseif (strlen($password) < 8) {
        array_push($errors, "Ouch.. Your password is too damn short");
    }
    if ($password != $passwordConfirmation) {
        array_push($errors, "The two passwords do not match");
    }
    // Ensure that no user is registered twice.
    // the email and usernames should be unique
}
function checkIfUserAlreadyExists($username, $email)
{
    global $pdo, $errors;
    $user_check_query = $pdo->prepare("SELECT * FROM users WHERE username=? OR email=?");
    $user_check_query->execute(array($username, $email));
    $user = $user_check_query->fetch(PDO::FETCH_ASSOC);
    if ($user) { // if user exists
        if ($user['username'] === $username) {
            array_push($errors, "Username already exists");
        }

        if ($user['email'] === $email) {
            array_push($errors, "Email already exists");
        }
    }
}
