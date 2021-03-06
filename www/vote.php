<?php
  require_once 'include/global.inc.php';

  $vT=new voteTools;
  $BT=new bookTools;

  $msg="";
  $disable_submit="";

  $active_round_id=$vT->get_active_round($db_handler);

  if($active_round_id){

    if($_SESSION["user_logged_in"]){
      $book_list_data=$BT->get_books_by_round_by_user($active_round_id,$_SESSION["user_object"]->user_id,$db_handler);
      if(isset($_POST["submit_vote"])){
        //check if total is 1
        $total_weight=0;
        foreach($_POST["vote"]["weight"] as $i => $weight){
          if($weight>1 or $weight<0){
            $msg.="Failed to vote: votes must be between 0 and 1.";
            break;
          }
          $total_weight+=$weight;
        }
        if($total_weight==1.0){
          //success, save to DB
          $vT->save_vote($_SESSION["user_object"]->user_id,$_POST["vote"],$db_handler);
          $msg.="Success: votes saved.";
        } else {
          $msg.="Failed to vote: total vote must be 1.";
        }
      }
    } else {
      $msg="you are not logged in.";
      $disable_submit="disabled";
      $book_list_data=$BT->get_books_by_round_by_user($active_round_id,0,$db_handler);
    }
  } else {
    $msg="no active round to vote.";
    $disable_submit="disabled";
  }

?>

<html>

<head>
  <title>Vote</title>
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
    .Rec {
      width: 15%;
    }
  </style>
  <?php
    include 'include/header.php';
  ?>
</head>
<body>


  <h2>Vote Book List</h2>
<form method="post" action="vote.php">
  <table>
    <tr>
      <th>Title</th><th>Author</th><th>Reference Link</th><th class="Rec">Recommendation</th><th>Vote Weight</th></tr>
      <?php
        foreach($book_list_data as $book){
          echo "<tr>
            <td>".$book["title"]."</td>
            <td>".$book["author"]."</td>
            <td><a href=\"".$book["ref_link"]."\" target=\"_blank\">[link]</a></td>
            <td class=\"Rec\">".$book["rec_text"]."</td>
            <td>Vote: <input type=\"number\" name=\"vote[weight][]\" value=".$book["vote_weight"]." min=\"0\" max=\"1\" step=\"0.1\">
            <input type=\"hidden\" name=\"vote[round_book_map_id][]\" value=\"".$book["round_book_map_id"]."\" >
            </td>
          </tr>";
        }
      ?>

  </table>
  <input type="submit" value="vote" name="submit_vote" <?php echo $disable_submit; ?>>
</form>
<?php echo $msg; ?>

  <br>


</body>
</html>
