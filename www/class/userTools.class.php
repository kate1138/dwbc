<?php
  require_once 'user.class.php';
  class userTools {
    public function user_log_in($user_name,$password,$db_handler){

      $hashed_password=md5($password);

      $stmt = $db_handler->prepare("select user_id,user_name,email,create_date,update_date,active_ind from users where user_name = :user_name and password = :hashed_password;");
      $stmt->execute(array("user_name"=>$user_name,"hashed_password"=>$hashed_password));
      $user_object=$stmt->fetchAll();

      //some code to verify pwd
      if(count($user_object)==1){
        //user logged in, set session variables

        $_SESSION["user_object"]=new user($user_object[0]);
        $_SESSION["user_logged_in"]=true;
        $_SESSION["login_start_time"]=time();
        return true;
      } else {
        return false;
      }
    }

    public function user_log_out(){
      echo "so long!";
      unset($_SESSION["user_id"]);
      unset($_SESSION["user_logged_in"]);
      unset($_SESSION["login_start_time"]);
      session_destroy();
    }

    public function check_name_email_exists($user_name,$email,$db_handler){
      $stmt=$db_handler->prepare("select count(*) cnt from users where user_name = :user_name or email = :email;");
      $stmt->execute(array("user_name"=>$user_name,"email"=>$email));
      $result=$stmt->fetch();
      if($result["cnt"]=0){
        return false;
      } else {
        return true;
      }
    }
  }
?>
