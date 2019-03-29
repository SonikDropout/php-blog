<?php
// SEND MESSAGE TO BLOG AUTHOR
if (isset($_POST['contact_submit'])) {
  $message = esc($_POST['message']);
  $email = esc($_POST['email']);
  $name = esc($_POST['name']);
  if (isset($_POST['subject'])) {
    $subject = esc($_POST['subject']);
  }

  
  if (empty($message)) {
    array_push($errors, "Please, enter you message");
  } else if (strlen($message) > 2000) {
    array_push($errors, "Wow! Consider joining our authors team if you like writing such detailed messages");
  }

  
  $name_exp = "/^[A-Za-z .'-]+$/";
  
  if (empty($name)) {
    array_push($errors, "Please, enter you name");
  } else if (!preg_match($name_exp,$name)) {
    array_push($errors, "The name you entered does not appear to be valid.");
  }
  
  if (empty($email)) {
    array_push($errors, "Please, enter you email");
  } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    array_push($errors, "The email address you entered does not appear to be valid.");
  }
  
  if (count($errors) == 0) {
    $to = 'alex_efim@inbox.ru';
    $bad = array("content-type","bcc:","to:","cc:","href");
    $message = str_replace($bad, '', $message);
    $subject = str_replace($bad, '', $subject);
    $headers = "From: $email \r\n";
    $headers .= "Reply-to: $email \r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    if (mail($to, $subject, $message, $headers, "-fvalidfromaddress@mydomain.com")) {
      $_SESSION['message'] = "Your message has been successfully sent";
      header('location: ' . BASE_URL . '/contact.php');
      exit(0);
    } else {
      $_SESSION['message'] = "Failed to send the message";
      header('location: ' . BASE_URL . '/contact.php');
      exit(0);
    }
  }
}