<?php
  require_once 'include/global.inc.php';
?>
<html>
<head>DWBC - Log In</head>
<body>
  just trying to call the login func.<br><br>
  <?php
    if(isset($_POST["submit_login"])){
      $user_name=$_POST["user_name"];
      $password=$_POST["password"];

      if($UT->user_log_in($user_name,$password,$db_handler)){
        echo "<br>Welcome, you have successfully logged in.";
      } else {
        echo "<br>Failed to log in, wrong user/password combination.";
      }
    }

  ?>
  <br><a href="index.php">Home</a>

  <form action="login.php" method="post">
    User Name: <input type="text" name="user_name" value="<?php echo $user_name ?>"/><br>
    Password: <input type="password" name="password" value="<?php echo $password ?>"/><br>
    <input type="submit" value="Login" name="submit_login" />
  </form>

</body>

</html>
