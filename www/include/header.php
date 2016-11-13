<?php
  require_once 'global.inc.php';

  echo "<h1>DWBC</h1>";

  echo "<a href=\"index.php\">Home</a>  |  ";
  echo "<a href=\"book_list.php\">Book List</a>  |  ";

  $vT=new voteTools;
  $current_round_id=$vT->get_latest_round($db_handler);

  echo "<a href=\"round.php?round_id=".$current_round_id."\">Last Voting Info</a>  |  ";

  if ($_SESSION["user_logged_in"]){
    echo "<a href=\"log_book.php\">Log a Book</a>  |  ";
    echo "<a href=\"vote.php\">Vote</a>  |  ";
    echo "<a href=\"new_round.php\">Initiate Voting</a>  |  ";
    echo "<a href=\"logout.php\">Log Out</a>  |  ";
    echo "Welcome, <a href=\"user_settings.php\">".$_SESSION["user_object"]->user_name ."</a>!";
  } else {
    echo "<a href=\"login.php\">Log In</a>  |  ";
    echo "<a href=\"register.php\">Register</a>  |  ";
    echo "Welcome, Guest!";
  }
  echo "<hr>";
?>
