<?php

  class user {

    public $user_id;
    public $user_name;
    public $password_hashed;
    public $email;
    public $create_date;
    public $update_date;
    public $active_ind;

    function __construct($user_info){
      $this->user_id = (isset($user_info['user_id']) ? $user_info['user_id'] : "");
      $this->user_name = (isset($user_info['user_name']) ? $user_info['user_name'] : "");
      $this->password_hashed = (isset($user_info['password_hashed']) ? $user_info['password_hashed'] : "");
      $this->email = (isset($user_info['email']) ? $user_info['email'] : "");
      $this->create_date = (isset($user_info['create_date']) ? $user_info['create_date'] : "");
      $this->update_date = (isset($user_info['update_date']) ? $user_info['update_date'] : "");
      $this->active_ind = (isset($user_info['active_ind']) ? $user_info['active_ind'] : 0);
    }

    
  }

?>
