<?php
  require_once 'include/global.inc.php';
?>

<html>
  <title>
    DWBC - book list
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
      $BT = new bookTools;

      $book_list_data=$BT->get_book_list("1=1",$db_handler);
    ?>
    <h2>Book List</h2>

    <table>
      <tr>
        <th>Title</th><th>Author</th><th>Category</th><th>Added by</th><th>Active</th><th>Won</th><th>Ref</th><th>Edit</th></tr>
        <?php
          foreach($book_list_data as $book){
            if($book["won_round_id"]>0){
              $winner="<a href=\"round.php?round_id=".$book["won_round_id"]."\">".$book["won_round_id"]."</a>";
            } else {
              $winner="";
            }
            if($book["creator_id"]==$_SESSION["user_object"]->user_id){
              $edit="<a href=\"book_edit.php?book_id=".$book["book_id"]."\">[edit]</a>";
            } else {
              $edit="";
            }
            echo "<tr>
              <td>".$book["title"]."</td>
              <td>".$book["author"]."</td>
              <td>".$book["book_category_desc"]."</td>
              <td>".$book["user_name"]."</td>
              <td>".$book["active_book_ind"]."</td>
              <td>".$winner."</td>
              <td><a href=\"".$book["ref_link"]."\" target=\"_blank\">[link]</a></td>
              <td>".$edit."</td>
            </tr>";
          }
        ?>

    </table>

  </body>
</html>
