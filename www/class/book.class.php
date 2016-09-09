<?php

  class book {

    public $book_id;
    public $creator_id;
    public $create_date;
    public $update_date;
    public $active_book_ind;
    public $won_round_id;

    public $title;
    public $author;
    public $ref_link;

    function __construct($book_info){
      $this->book_id = (isset($book_info['book_id']) ? $book_info['book_id'] : "");
      $this->creator_id = (isset($book_info['creator_id']) ? $book_info['creator_id'] : "");
      $this->create_date = (isset($book_info['create_date']) ? $book_info['create_date'] : "");
      $this->update_date = (isset($book_info['update_date']) ? $book_info['update_date'] : "");
      $this->active_book_ind = (isset($book_info['active_book_ind']) ? $book_info['active_book_ind'] : "");
      $this->won_round_id = (isset($book_info['won_round_id']) ? $book_info['won_round_id'] : "");

      $this->title = (isset($book_info['title']) ? $book_info['title'] : "");
      $this->creator_id = (isset($book_info['creator_id']) ? $book_info['creator_id'] : "");
      $this->ref_link = (isset($book_info['ref_link']) ? $book_info['ref_link'] : "");
    }

    public function save_book_info($db_handler,$is_new_book=false){

      if($is_new_book){
        if(!isset($this->creator_id)){throw new exception("invalid user!");}
        $book_data=array(
          "title"=>$this->title
          ,"author"=>$this->author
          ,"ref_link"=>$this->ref_link
          ,"creator_id"=>$this->creator_id
        );
        $stmt=$db_handler->prepare("
          insert into books (creator_id,create_date,update_date,active_book_ind,title,author,ref_link)
          values(:creator_id,now(),now(),1,:title,:author,:ref_link);
        ");
        $stmt->execute($book_data);
      } else {
        //update
      }
    }


  }

?>
