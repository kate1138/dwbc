<?php
  class voteTools{
    public function get_active_round($db_handler){
      $sql="select round_id from rounds where round_status=1;";
      $stmt=$db_handler->prepare($sql);
      $stmt->execute();
      $results=$stmt->fetch();

      return $results["round_id"];
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

    public function save_vote($creator_id,$vote,$db_handler){
      $sql="
        insert into votes (
          round_book_map_id
          ,creator_id
          ,create_date
          ,update_date
          ,vote_weight
        ) values (
          :round_book_map_id
          ,:creator_id
          ,now()
          ,now()
          ,:vote_weight
        ) on duplicate key update
          update_date=now()
          ,vote_weight=:vote_weight
      ";
      $round_book_map_id=-1;
      $vote_weight=-1;
      $stmt=$db_handler->prepare($sql);
      $stmt->bindParam(":round_book_map_id",$round_book_map_id, PDO::PARAM_INT);
      $stmt->bindParam(":creator_id",$creator_id, PDO::PARAM_INT);
      $stmt->bindParam(":vote_weight",$vote_weight);

      for ($i=0; $i<count($vote["round_book_map_id"]); $i++){
        $round_book_map_id=$vote["round_book_map_id"][$i];
        $vote_weight=$vote["weight"][$i];
        $stmt->execute();
      }
    }

    public function get_round_info($round_id,$db_handler){
      $sql="
        select a.round_status
        ,a.create_date
        ,case when a.book_category_id=1 then 'fiction' else 'non-fiction' end book_category_desc
        ,a.winner_book_id
        ,sum(coalesce(c.vote_weight)) vote_cnt
        from rounds a
        left join round_book_mapping b
        on a.round_id=b.round_id
        left join votes c
        on b.round_book_map_id=c.round_book_map_id
        where a.round_id=:round_id
        group by 1,2,3,4
      ";

      $stmt=$db_handler->prepare($sql);
      $stmt->bindParam(":round_id",$round_id, PDO::PARAM_INT);
      $stmt->execute();
      $result=$stmt->fetch();

      return $result;
    }

    public function conclude_round($round_id,$db_handler){
      //calculate votes
      $sql["calc_vote"]="
        select a.round_book_map_id,sum(vote_weight) total_vote
        from votes a
        join round_book_mapping b
        on a.round_book_map_id=b.round_book_map_id
        where b.round_id=:round_id
        group by a.round_book_map_id
        ;
      ";
      $stmt=$db_handler->prepare($sql["calc_vote"]);
      $stmt->bindParam(":round_id",$round_id);
      $stmt->execute();
      $result=$stmt->fetchall();

      $votes=array();

      foreach ($result as $index => $vote_result){
        $votes[$vote_result["round_book_map_id"]]=$vote_result["total_vote"];
      }

      echo '<pre>';
      var_dump($votes);
      var_dump(array_keys($votes, max($votes)));
      var_dump(count(array_keys($votes, max($votes))));
      var_dump(array_keys($votes, max($votes))[0]);
      echo '</pre>';

      if(count(array_keys($votes, max($votes)))==1){
        //updates
        $won_round_book_map_id=array_keys($votes, max($votes))[0];
        $sql["get_book_id"]="select book_id from round_book_mapping where round_book_map_id=:round_book_map_id;";
        $stmt=$db_handler->prepare($sql["get_book_id"]);
        $stmt->bindParam(":round_book_map_id",$won_round_book_map_id,PDO::PARAM_INT);
        $stmt->execute();
        $won_book_id=$stmt->fetch()["book_id"];

        $db_handler->beginTransaction();
        try {
          //update round table, set to concluded, set won book_id
          $sql["update_round"]="
            update rounds
            set winner_book_id=:won_book_id
            ,round_status=2
            ,update_date=now()
            where round_id=:round_id
            ;
          ";
          $stmt=$db_handler->prepare($sql["update_round"]);
          $stmt->bindParam(":round_id",$round_id,PDO::PARAM_INT);
          $stmt->bindParam(":won_book_id",$won_book_id,PDO::PARAM_INT);
          $stmt->execute();


          //update book table, set won round id
          $sql["update_book"]="
            update books
            set won_round_id=:round_id
            ,update_date=now()
            where book_id=:book_id
            ;
          ";
          $stmt=$db_handler->prepare($sql["update_book"]);
          $stmt->bindParam(":round_id",$round_id,PDO::PARAM_INT);
          $stmt->bindParam(":book_id",$won_book_id,PDO::PARAM_INT);
          $stmt->execute();


          //update round_book_mapping table, udpate total votes
          $sql["update_mapping"]="
            update round_book_mapping
            set total_vote=:total_vote
            where round_book_map_id=:round_book_map_id
            ;
          ";
          $stmt=$db_handler->prepare($sql["update_mapping"]);
          foreach($votes as $round_book_map_id => $total_vote){
            $stmt->bindParam(":round_book_map_id",$round_book_map_id,PDO::PARAM_INT);
            $stmt->bindParam(":total_vote",$total_vote,PDO::PARAM_STR);
            $stmt->execute();

          }

          $db_handler->commit();
        } catch (PDOException $e) {
          //
          $db_handler->rollBack();
          $msg.="there is an error updating tables.";
        }

        return $won_book_id;
      } else {
        return -1;
      }



      //$db_handler->beginTransaction();
      //try {
      //  //calculate vote, update final votes into round book mapping table
      //  $stmt=$db_handler->prepare($sql["calc_vote"]);
      //  $stmt->bindParam(":round_id",$round_id, PDO::PARAM_INT);
      //  $stmt->execute();
      //  //get books and votes
      //  $stmt=$db_handler->prepare($sql["get_vote"]);
      //  $stmt->bindParam(":round_id",$round_id, PDO::PARAM_INT);
      //  $stmt->execute();
      //  $book_list_data=$stmt->fetchAll();
      //  //find winner book id
      //

      //} catch (PDOException $e) {
      //  $db_handler->rollBack();


      //}
    }

  }

?>
