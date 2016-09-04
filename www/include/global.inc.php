<?php
  //define constants
  define( 'ROOT_DIR', '/Applications/MAMP/htdocs/dwbc/www/' );

  define( 'DB_HOST', 'localhost' );
  define( 'DB_USER', 'root' );
  define( 'DB_PASS', 'root' );
  define( 'DB_NAME', 'phptest' );

  $db_handler = new PDO ("mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8"
    ,DB_USER
    ,DB_PASS
    ,[PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
     ,PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
     ]
  );

  require_once ROOT_DIR.'class/user.class.php';
  require_once ROOT_DIR.'class/userTools.class.php';

  session_start();

  //new user tool object
  $UT = new userTools;

  //if logged in, create a new current user object with current user info
  //placeholder

?>
