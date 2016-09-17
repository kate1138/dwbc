<?php
  class round{
    public $round_id;
    public $creator_id;
    public $create_date;
    public $update_date;
    public $book_category_id;
    public $round_status;
    public $winner_book_id;

    function __construct($round_info){
      $this->round_id = (isset($user_info['round_id']) ? $user_info['round_id'] : "");
      $this->creator_id = (isset($user_info['creator_id']) ? $user_info['creator_id'] : "");
      $this->create_date = (isset($user_info['create_date']) ? $user_info['create_date'] : "");
      $this->update_date = (isset($user_info['update_date']) ? $user_info['update_date'] : "");
      $this->book_category_id = (isset($user_info['book_category_id']) ? $user_info['book_category_id'] : "");
      $this->round_status = (isset($user_info['round_status']) ? $user_info['round_status'] : "");
      $this->winner_book_id = (isset($user_info['winner_book_id']) ? $user_info['winner_book_id'] : "");
    }

    public function save_round($db_handler,$is_new=false){

      if($is_new){
        $stmt=$db_handler->prepare("
          insert into rounds (creator_id,create_date,update_date,book_category_id,round_status,winner_book_id)
          values(:creator_id,now(),now(),:book_category_id,1,null)
        ");
        $stmt->execute(array("creator_id"=>$this->creator_id,"book_category_id"=>$this->book_category_id));
        $round_id=$db_handler->lastInsertId();
      } else {
        //update
      }
      return $round_id;
    }

    public function set_round_info($db_handler){
      $round_id=$this->round_id;

    }
  }

?>
