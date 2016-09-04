<?php
  require_once 'include/global.inc.php';

  if(isset($_POST["submit_form"])){
    $user_name=$_POST["user_name"];
    $password=$_POST["password"];
    $password_confirm=$_POST["password_confirm"];
    $email=$_POST["email"];

    $UT = new userTools();
    $error="";
    $success=true;

    //validate
    //check if user_name or email already exists
    if($UT->check_name_email_exists($user_name,$email,$db_handler)){
      $error.="user name or email already taken. <br>";
      $success=false;
    }
    //check if password match
    if($password!=$password_confirm){
      $error.="passwords do not match. <br>";
      $success=false;
    }
    if($success){
      $user_data=array(
        "user_name"=>$user_name
        ,"password_hashed"=>md5($password)
        ,"email"=>$email
      );

      $new_user=new user($user_data);
      $new_user->save_user_info($db_handler,true);
      echo "<br>You are now registered. The webmaster will activate you if you are a friend.<br>";
    }
  }
?>
<html>
<title>DWBC - Register</title>
<body>

  <?php
    include 'include/header.php';
    echo $error;
  ?>

  <form action="register.php" method="post">
    User name: <input type="text" value="<?php echo $user_name; ?>" name="user_name" /><br>
    Password: <input type="password" value="<?php echo $password; ?>" name="password" /><br>
    Confirm password: <input type="password" value="<?php echo $password_confirm; ?>" name="password_confirm" /><br>
    E-Mail: <input type="text" value="<?php echo $email; ?>" name="email" /><br>
    <input type="submit" value="Register" name="submit_form" />
  </form>

</body>

</html>
