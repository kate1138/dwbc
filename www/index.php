<?php
  require_once 'include/global.inc.php';
?>
<html>
  <title>
    Welcome to DWBC
  </title>
  <body>

    <?php
      //if user is logged in, display link to logout.php
      //if user is logged out, display link to login.php

      if($_SESSION["user_logged_in"]){
        echo "<br>Hello, ".$_SESSION["user_object"]->user_name.". To log out: <a href=\"logout.php\">logout page</a>";
      } else {
        echo "<br>Welcome, please log in -> <a href=\"login.php\">login page</a> or <a href=\"register.php\">register</a>";
      }

    ?>



  </body>
</html>
