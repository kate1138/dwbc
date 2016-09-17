<?php
  require_once 'include/global.inc.php';
?>
<html>
  <title>
    Welcome to DWBC
  </title>
  <body>

    <?php

      include 'include/header.php';
      //if user is logged in, display link to logout.php
      //if user is logged out, display link to login.php

    ?>

    <?php if($_SESSION["user_logged_in"]) : ?>
      You can:
      <br><a href="log_book.php">Log a book.</a>
      <br><a href="new_round.php">new round.</a>
      <br><a href="user_settings.php">Update user info.</a>
      <br><a href="logout.php">Log out.</a>
    <?php else : ?>
      You are not logged in.
      <br>Go to <a href="login.php">Log in page</a> or <a href="register.php">Register</a>.<br>
    <?php endif; ?>
    <br>View <a href="book_list.php">book list</a>.

  </body>
</html>
