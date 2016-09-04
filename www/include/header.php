<?php
  require_once 'global.inc.php';

  echo "<h1>DWBC</h1>";

  echo "<a href=\"/dwbc/www/index.php\">Home</a>  |  ";

  if ($_SESSION["user_logged_in"]){
    echo "Welcome, ".$_SESSION["user_object"]->user_name ."!";
  } else {
    echo "Welcome, Guest!";
  }
  echo "<hr>";
?>
