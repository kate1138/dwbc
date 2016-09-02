<?php
class DB {
  /* db class. Need these functions:
   * DBinsert($dataset, $table)
   * DBupdate($dataset, $table, $where)
   * DBselect($table, $where)
   */

  protected $db_host="localhost";
  protected $db_name="phptest";
  protected $db_user="root";
  protected $db_pass="root";
  protected $charset="utf8";

  public function DBSelect($table, $where){
    $dsn = "mysql:host=$this->db_host;dbname=$this->db_name;charset=$this->charset";

    try {
      $dbh = new PDO($dsn,$this->db_user,$this->db_pass);
      $stmt = $dbh->query("select * from $table where $where ;");
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      $dataset = $stmt->fetch();
    } catch(PDOException $e) {
      echo $e->getMessage();
    }

    return $dataset;
  }
}



?>
