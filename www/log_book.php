<?php
  require_once 'include/global.inc.php';

  $title="";
  $author="";
  $ref_link="";
  if(isset($_SESSION["user_object"]->user_id)){
    $creator_id=$_SESSION["user_object"]->user_id;
  }
  $msg="";

  if(isset($_POST["submit_book"])){
    $title=$_POST["title"];
    $author=$_POST["author"];
    $ref_link=$_POST["ref_link"];

    $book_data=array(
      "title"=>$title
      ,"author"=>$author
      ,"ref_link"=>$ref_link
      ,"creator_id"=>$creator_id
    );

    $new_book=new book($book_data);
    $new_book->save_book_info($db_handler,true);
    $msg="<br>Book saved!<br>";
  }

?>
<html>
<title>DWBC - new book</title>
<body>
  <?php
    include 'include/header.php';
  ?>
  <?php if($_SESSION["user_logged_in"]) : ?>
    <h2>Submit a new book here</h2>
    <form action="log_book.php" method="post">
      Book title: <input type="text" name="title" required .> * required<br>
      Author: <input type="text" name="author" .><br>
      Reference link: <input type="text" name="ref_link" .><br>
      <input type="submit" value="Submit" name="submit_book" />
    </form>
  <?php else : ?>
    <p>You need to log in to log a book.
      <br><a href="login.php">Log In Page</a>
    </p>
  <?php endif; ?>
  <?php echo $msg; ?>

</body>
</html>
