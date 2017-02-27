<?php
  require_once 'include/global.inc.php';
  $msg="";
  $display_form=true;
  $display_vote_result=false;
  $disable_submit="";
  $vT=new voteTools;
  $BT=new bookTools;
  $vote_cnt=0;
  $round_create_date="";
  $round_status_desc="";
  $book_category_desc="";

  if(isset($_GET["round_id"])){

    $book_list_data=$BT->get_books_by_round_by_user($_GET["round_id"],0,$db_handler);
    $round_info=$vT->get_round_info($_GET["round_id"],$db_handler);

    $vote_cnt=$round_info["vote_cnt"];
    $round_status=$round_info["round_status"];
    $round_create_date=$round_info["create_date"];
    $book_category_desc=$round_info["book_category_desc"];

    if($round_status==1){ //round is active
      $round_status_desc="Voting is open.";
      if($_SESSION["user_logged_in"]){
        if(isset($_POST["submit_conclude_round"])){
          $won_book_id=$vT->conclude_round($_GET["round_id"],$db_handler);

          if($won_book_id==-1){
            $msg.="Cannot conclude voting, there is not a unique winner. Please update votes.";
          } else {
            $bk=new book(array("book_id"=>$won_book_id));
            $bk->set_book_info($db_handler);
            $msg.="This round of voting is concluded. Winner book is <i>".$bk->title."</i>.";
            $display_form=false;
            $disable_submit="disabled";
          }
        }
      } else {
        $msg.="You are not logged in.";
        $display_form=false;
        $disable_submit="disabled";
      }
    } else { //round is inactive
      $display_vote_result=true;
      $book_list_data=$BT->get_books_by_round($_GET["round_id"],$db_handler);
      $display_form=false;
      $disable_submit="disabled";
      $won_book_id=$round_info["winner_book_id"];
      $bk=new book(array("book_id"=>$won_book_id));
      $bk->set_book_info($db_handler);
      $msg.="This round of voting is concluded. Winner book is <i>".$bk->title."</i>.";
    }

  } else {
    $msg.="no round selected.";
    $display_form=false;
    $disable_submit="disabled";
  }

?>

<html>
<head>
  <title>DWBC</title>
  <style>
    table {
      width: 90%;
      border-collapse: collapse;
    }
    td, th {
      border: 1px solid #dddddd;
      text-align: left;
      padding: 8px;
    }
    tr:nth-child(even) {
      background-color: #dddddd;
    }
    th.Recommendation {
      width: 15%
    }
  </style>
  <?php
    include 'include/header.php';
  ?>
</head>
<body>
  <p>
    <?php echo $round_status_desc; echo " Book type is ".$book_category_desc.".";?>
    <br>
    Voting started: <?php echo $round_create_date; ?>
    <br>
    Votes received: <?php echo $vote_cnt; ?>
  </p>
  <h2>Candidates:</h2>
  <form method="post" action="round.php?round_id=<?php echo $_GET["round_id"] ?>" method="post">
    <input type="submit" value="conclude this round of votes" name="submit_conclude_round" <?php echo $disable_submit; ?>>
  </form>
  <table>
    <tr>
      <th>Title</th><th>Author</th><th>Reference Link</th><th>Votes</th></tr>
      <?php
        foreach($book_list_data as $book){
          if($display_vote_result){
            $votes=$book["total_vote"];
          } else {
            $votes="show when vote is concluded";
          }
          echo "<tr>
            <td>".$book["title"]."</td>
            <td>".$book["author"]."</td>
            <td><a href=\"".$book["ref_link"]."\" target=\"_blank\">[link]</a></td>
            <td>".$votes."</td>
          </tr>";
        }
      ?>

  </table>

  <?php echo $msg; ?>
</body>
</html>
