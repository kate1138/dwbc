<?php
  require_once 'include/global.inc.php';

  $vT=new voteTools;
  $BT=new bookTools;

  //conditionally allow create new round
  $create_new_round="";

  $round_id=-2;
  $round_create_success=false;
  $round_create_display_error=false;

  $msg="";

  $fiction_selected="selected";
  $nonfiction_selected="";

  if(isset($_POST["book_category_id"])){
    if($_POST["book_category_id"]==1){
      $fiction_selected="selected";
      $nonfiction_selected="";
    } else {
      $fiction_selected="";
      $nonfiction_selected="selected";
    }
  }

  if($_SESSION["user_logged_in"]){
    $active_round_id=$vT->get_active_round($db_handler);
    //$active_round_exists=$vT->check_active_round_exists($db_handler);

    if($active_round_id){
      $create_new_round="disabled";
      $msg.="* cannot create new round. There is already an active round.";
    } else { //do create round here
      if(isset($_POST["submit_round"])){
        $book_list_data=$BT->get_candidates($_POST["book_category_id"],$db_handler);
        $round_id=$vT->create_new_round($book_list_data,$_SESSION["user_object"]->user_id,$_POST["book_category_id"],$db_handler);
        if($round_id<0){
          $round_create_display_error=true;
        } else {
          $round_create_success=true;
          $create_new_round="disabled";
        }
      }
    }
  } else {
    $create_new_round="disabled";
    $msg.="* you are not logged in.";
  }

  //preview candidates - handling form submission
  if(isset($_POST["submit_preview"])){
    $book_list_data=$BT->get_candidates($_POST["book_category_id"],$db_handler);
  }

?>
<html>

  <title>DWBC new round
  </title>
  <head>
    <style>
      table {
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
    </style>
  </head>
  <body>
    <?php
      include 'include/header.php';
    ?>
    <h2>Initiate a new round</h2>

    <form action="new_round.php" method="post">
      Book category:
      <select name="book_category_id">
        <option value="1" <?php echo $fiction_selected; ?>>Fiction</option>
        <option value="2" <?php echo $nonfiction_selected; ?>>Non-Fiction</option>
      </select><br>
      <input type="submit" value="preview candidates" name="submit_preview" />
      <input type="submit" value="new round" name="submit_round" <?php echo $create_new_round; ?> />
      <?php echo $msg; ?>
    </from>

    <?php if(isset($_POST["submit_preview"])) : ?>
      <h2>Candidate Book List</h2>

      <table>
        <tr>
          <th>Title</th><th>Author</th><th>Reference Link</th><th>Edit</th></tr>
          <?php
            foreach($book_list_data as $book){
              echo "<tr>
                <td>".$book["title"]."</td>
                <td>".$book["author"]."</td>
                <td><a href=\"".$book["ref_link"]."\" target=\"_blank\">[link]</a></td>
                <td><a href=\"book_edit.php?book_id=".$book["book_id"]."\">[edit]</a></td>
              </tr>";
            }
          ?>

      </table>
    <?php endif; ?>

    <?php if($round_create_success) : ?>
      <p>New round initiated. Go to <a href="round.php?round_id=<?php echo $round_id; ?>">New Round</a>.</p>
    <?php endif; ?>
    <?php if($round_create_display_error) : ?>
      <p>There was some error creating the round.</p>
    <?php endif; ?>


    <br>

  </body>

</html>
