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
          ,books.active_book_ind
          ,books.rec_text
          ,books.won_round_id
          ,books.creator_id
        from books
        join users
        on books.creator_id=users.user_id
        where 1=1
        and ".$where_clause.";"
      ;

      $stmt = $db_handler->prepare($sql);
      $stmt->execute();
      $book_list_data = $stmt->fetchall();

      return $book_list_data;
    }

    public function get_candidates($book_category_id,$db_handler){
      $sql="
        select
          book_id
          ,creator_id
          ,title
          ,author
          ,ref_link
          from (
            select
              book_id
              ,creator_id
              ,title
              ,author
              ,ref_link
              ,if(@grp=creator_id,@rk:=@rk+1,@rk:=1) as rk
              ,@grp:=creator_id
            from books
            ,(select @rk:=0,@grp:=0) a
            where book_category_id=:book_category_id
            and (won_round_id=0 or won_round_id is null)
            and active_book_ind=1
            order by creator_id, update_date desc
         ) a
         where rk<=2;
      ";
      $stmt=$db_handler->prepare($sql);
      $stmt->execute(array("book_category_id"=>$book_category_id));
      $book_list_data=$stmt->fetchall();

      return $book_list_data;
    }

    public function get_books_by_round_by_user($round_id,$user_id,$db_handler){
      $sql="select
        b.round_book_map_id
        ,a.book_id
        ,a.title
        ,a.author
        ,a.ref_link
        ,a.rec_text
        ,c.vote_weight
        from books a
        join round_book_mapping b
        on a.book_id=b.book_id
        left join votes c
        on b.round_book_map_id=c.round_book_map_id
        and c.creator_id=:user_id
        where b.round_id=:round_id
        ;
      ";
      $stmt=$db_handler->prepare($sql);
      $stmt->bindParam(":round_id",$round_id, PDO::PARAM_INT);
      $stmt->bindParam(":user_id",$user_id, PDO::PARAM_INT);
      $stmt->execute();
      $book_list_data=$stmt->fetchall();

      return $book_list_data;
    }

    public function get_books_by_round($round_id,$db_handler){
      $sql="
        select
          a.round_book_map_id
          ,b.book_id
          ,b.title
          ,b.author
          ,b.ref_link
          ,a.total_vote
        from round_book_mapping a
        join books b
        on a.book_id=b.book_id
        where a.round_id=:round_id;
      ";
      $stmt=$db_handler->prepare($sql);
      $stmt->bindParam(":round_id",$round_id, PDO::PARAM_INT);
      $stmt->execute();
      $book_list_data=$stmt->fetchall();

      return $book_list_data;

    }
  }
?>
