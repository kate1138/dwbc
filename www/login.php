<?php
  require_once 'include/global.inc.php';
?>
<html>
<title>DWBC - Log In</title>
<body>

  <?php

    $user_name="";
    $password="";
    $msg="";

    if(isset($_POST["submit_login"])){
      $user_name=$_POST["user_name"];
      $password=$_POST["password"];

      if($UT->user_log_in($user_name,$password,$db_handler)){
        $msg= "Welcome ".$_SESSION["user_object"]->user_name.", you have successfully logged in.";
      } else {
        $msg= "Failed to log in, wrong user/password combination, or user not activated.";
      }
    }
    include 'include/header.php';

  ?>
  <br><a href="index.php">Home</a>

  <form action="login.php" method="post">
    User Name: <input type="text" name="user_name" value="<?php echo $user_name ?>"/><br>
    Password: <input type="password" name="password" value=""/><br>
    <input type="submit" value="Login" name="submit_login" />
  </form>

  <?php echo $msg; ?>

</body>

</html>
