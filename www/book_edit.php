<?php
  require_once 'include/global.inc.php';

  $msg="";
  $display_form=true;

  if(isset($_GET["book_id"])){
    $book_id=$_GET["book_id"];
    $book_update=new book(array("book_id"=>$book_id));
    $book_update->set_book_info($db_handler);

    if($book_update->active_book_ind){
      $active_checked="checked";
      $inactive_checked="";
    } else {
      $active_checked="";
      $inactive_checked="checked";
    }
    if($book_update->book_category_id==1){
      $fiction_selected="selected";
      $nonfiction_selected="";
    } else {
      $fiction_selected="";
      $nonfiction_selected="selected";
    }

    if($_SESSION["user_logged_in"]){

      if($_SESSION["user_object"]->user_id == $book_update->creator_id){
        if(isset($_POST["submit_book_edit"])){
          //update DB
          $book_update->book_category_id=$_POST["book_category_id"];
          $book_update->active_book_ind=$_POST["active_book_ind"];
          $book_update->title=$_POST["title"];
          $book_update->author=$_POST["author"];
          $book_update->ref_link=$_POST["ref_link"];

          $book_update->save_book_info($db_handler);
          $book_update->set_book_info($db_handler);

          $msg.="book <i>".$book_update->title."</i> updated";
        }
      } else {
        $display_form=false;
        $msg.="you are not owner of this book.";
      }

    } else {
      $display_form=false;
      $msg.="You are not logged in.";
    }

    //var_dump($book_id);
  } else {
    $display_form=false;
    $msg.="No book selected.";
  }
?>

<html>
  <title>
    DWBC - book edit page
  </title>
  <head>
    <?php
      include 'include/header.php';
    ?>
  </head>
  <body>
    <h2>Edit a book</h2>
    <?php if($display_form) : ?>
      * all fields are updated, please don't leave any blank if not intended.<br><br>
      <form action="book_edit.php?book_id=<?php echo $book_id ?>" method="post">
        <input type="hidden" name="book_id" value="<?php echo $book_id ?>">
        Book title: <input type="text" name="title" value="<?php echo $book_update->title; ?>" required .><br>
        Author: <input type="text" name="author" value="<?php echo $book_update->author; ?>" .><br>
        Reference link: <input type="text" name="ref_link" value="<?php echo $book_update->ref_link; ?>".><br>
        Book category: <select name="book_category_id">
          <option value="1">Fiction</option>
          <option value="2" selected>Non-Fiction</option>
        </select><br>
        Active for vote:
          <input type="radio" name="active_book_ind" value="1" <?php echo $active_checked; ?>> Yes
          <input type="radio" name="active_book_ind" value="0" <?php echo $inactive_checked; ?>> No<br>
        <input type="submit" value="Submit" name="submit_book_edit" />
      </form>
   <?php endif; ?>
   <?php echo $msg; ?>
  </body>
</html>
