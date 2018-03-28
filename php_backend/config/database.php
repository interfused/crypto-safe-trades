<?php
require('credentials.php');

class Database {
 // specify your own database credentials
  /*
  private $host = "localhost";
  private $db_name = "fusecre8_cst";
  private $username = "fusecre8";
  private $password = "Tr3xf1st!!";
  */
  public $conn;
  
 // get the database connection
  public function getConnection(){
   
    $this->conn = null;
    
    try{
      $this->conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
      $this->conn->exec("set names utf8");
    }catch(PDOException $exception){
      echo "Connection error: " . $exception->getMessage();
    }
    
    return $this->conn;
  }
} 
?>