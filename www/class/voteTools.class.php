<?php
  class voteTools{
    public function check_active_round_exists($db_handler){
      $sql="select count(*) cnt from rounds where round_status=1;";
      $stmt=$db_handler->prepare($sql);
      $stmt->execute();
      $cnt=$stmt->fetch();
      if($cnt["cnt"]==0){
        return false;
      } else {
        return true;
      }
    }

    public function create_new_round($book_list_data,$creator_id,$book_category_id,$db_handler){
      //insert record in round table
      //insert records in round-book mapping table
      //the above in the same transaction
      $sql["round"]="
        insert into rounds (creator_id,create_date,update_date,book_category_id,round_status,winner_book_id)
        values(:creator_id,now(),now(),:book_category_id,1,null);
      ";
      $sql["round_book_map"]="
        insert into round_book_mapping (book_id,round_id,creator_id,create_date,update_date)
        values(:book_id,:round_id,:creator_id,now(),now());
      ";

      $book_id=-1;
      $round_id=-1;

      $db_handler->beginTransaction();
      try {
        //insert rounds table and get new round id
        $stmt=$db_handler->prepare($sql["round"]);
        $stmt->bindValue(":creator_id",$creator_id, PDO::PARAM_INT);
        $stmt->bindValue(":book_category_id",$book_category_id, PDO::PARAM_INT);
        $stmt->execute();
        $round_id=$db_handler->lastInsertId();

        //insert round book mapping table
        $stmt=$db_handler->prepare($sql["round_book_map"]);
        $stmt->bindParam(":book_id",$book_id, PDO::PARAM_INT);
        $stmt->bindParam(":round_id",$round_id, PDO::PARAM_INT);
        $stmt->bindParam(":creator_id",$creator_id, PDO::PARAM_INT);
        foreach($book_list_data as $book){
          $book_id=$book["book_id"];
          $stmt->execute();
        }

        $db_handler->commit();
      } catch (PDOException $e) {
        $db_handler->rollBack();
        $round_id=-1; //negative round_id treated as a failure
      }
      return $round_id;
    }

  }

?>
