<?php
  require_once 'book.class.php';
  class bookTools{
    public function get_book_list($where_clause,$db_handler){
      $sql="select
          books.book_id
          ,users.user_name
          ,books.create_date
          ,books.update_date
          ,case books.book_category_id when 1 then 'fiction' when 2 then 'non-fiction' end book_category_desc
          ,books.won_round_id
          ,books.title
          ,books.author
          ,books.ref_link
        from books
        join users
        on books.creator_id=users.user_id
        where books.active_book_ind=1
        and ".$where_clause.";"
      ;

      $stmt = $db_handler->prepare($sql);
      $stmt->execute();
      $book_list_data = $stmt->fetchall();

      return $book_list_data;
    }
  }
?>
