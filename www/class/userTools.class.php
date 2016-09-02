<?php

  class userTools {
    public function user_log_in($user_id,$password){
      $user_pwd_match=true;
      //some code to verify pwd
      if($user_pwd_match){
        $_SESSION["user_id"]=$user_id;
        $_SESSION["user_logged_in"]=true;
        $_SESSION["login_start_time"]=time();
      } else {
        echo "<br>user and password not matched.";
      }
    }

    public function user_log_out(){
      echo "so long!";
      unset($_SESSION["user_id"]);
      unset($_SESSION["user_logged_in"]);
      unset($_SESSION["login_start_time"]);
      session_destroy();
    }
  }
?>
