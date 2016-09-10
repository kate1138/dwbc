<?php
  require_once 'include/global.inc.php';
  $user_name="";
  $password="";
  $password_confirm="";
  $email="";
  $error="";

  $msg="";

  if(isset($_POST["submit_form"])){ // fields not need to update can be left blank
    $user_name = $_POST["user_name"]!=="" ? $_POST["user_name"] : $_SESSION["user_object"]->user_name;
    $password = $_POST["password"]!=="" ? md5($_POST["password"]) : $_SESSION["user_object"]->password_hashed;
    $password_confirm = $_POST["password_confirm"]!=="" ? md5($_POST["password_confirm"]) : $_SESSION["user_object"]->password_hashed;
    $email = $_POST["email"]!=="" ? $_POST["email"] : $_SESSION["user_object"]->email;

    $UT = new userTools();
    $error="";
    $success=true;

    //validate form
    //if set to change, check if user_name or email already exists
    //nobody should be using email or user name that is a hash value... if so, then this would fail
    if($user_name!=$_SESSION["user_object"]->user_name){
      if($UT->check_name_email_exists($user_name,md5($email),$db_handler)){
        $error.="user name already taken. <br>";
        $success=false;
      }
    }

    if($email!=$_SESSION["user_object"]->email){
      if($UT->check_name_email_exists(md5($user_name),$email,$db_handler)){
        $error.="email already taken. <br>";
        $success=false;
      }
    }

    //check if password match
    if($password!=$password_confirm){
      $error.="passwords do not match. <br>";
      $success=false;
    }

    if($success){
      $user_data=array(
        "user_id"=>$_SESSION["user_object"]->user_id
        ,"user_name"=>$user_name
        ,"password_hashed"=>$password
        ,"email"=>$email
      );

      $user_update=new user($user_data);
      $user_update->save_user_info($db_handler,false);

      //update the user object in this session
      $_SESSION["user_object"]->set_user_info($db_handler);

      $msg="updated!";
    }
  }
?>
<html>
  <title>
    Welcome to DWBC
  </title>
  <body>

    <?php

      include 'include/header.php';
      echo $error;

    ?>
    <?php if($_SESSION["user_logged_in"]) : ?>
      <br>
      * Fields not needed can be left blank.
      <br>
      <form action="user_settings.php" method="post">
        User name: <input type="text" name="user_name" /><br>
        Password: <input type="password" name="password" /><br>
        Confirm password: <input type="password" name="password_confirm" /><br>
        E-Mail: <input type="text" name="email" /><br>
        <input type="submit" value="update" name="submit_form" />
      </form>
    <?php else : ?>
      <br>You are not logged in!
      <br>please log in -> <a href="login.php">login page</a> or <a href="register.php">register</a>
    <?php endif; ?>
    <?php echo $msg; ?>

  </body>
</html>
