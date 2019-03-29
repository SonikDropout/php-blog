<?php
$_SESSION['url'] = $_SERVER['REQUEST_URI']; ?>
<header>
  <div class="logo">
    <h1><a href="index.php"><?= BLOG_NAME ?></a></h1>
  </div>
  <button class="toggle">&#9776;</button>
  <nav class="navbar">
    <a href="index.php" class="active">Home</a>
    <a href="about.php">About</a>
    <a href="contact.php">Contact</a>
    <span class="auth-links">
      <?php if(isset($_SESSION['user'])): ?>
        <b><?= $_SESSION['user']['username'] ?></b> |
        <a href="logout.php" class="auth"><i class="fa fa-sign-out" aria-hidden="true"></i> Log Out</a>
      <?php else: ?>
        <a href="login.php" class="auth"><i class="fa fa-sign-in" aria-hidden="true"></i> Log In</a> |
        <a href="register.php" class="auth"><i class="fa fa-user"></i> Register</a>
      <?php endif; ?>
    </span>
  </nav>
</header>