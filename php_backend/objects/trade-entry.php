<?php

class TradeEntry{
  //database connection and table name
  private $conn;
  private $table_name = 'cst_entries';
  
  //object properties
  public $id;
  public $user_id;
  public $trade_id;
  public $exchange_id;
  public $trade_pair;
  public $date;
  public $qty;
  public $price;
  public $fully_closed;
  
  // constructor with $db as database connection
  public function __construct($db){
    $this->conn = $db;
  }
  
  // create
  function create(){
    // insert query
    $query = "INSERT INTO
                " . $this->table_name . "
            SET
                user_id=:user_id, trade_id=:trade_id, exchange_id=:exchange_id, trade_pair=:trade_pair, qty=:qty, price=:price";
 
    
    // prepare query statement
    $stmt = $this->conn->prepare($query);
    
    // sanitize
    $this->user_id=htmlspecialchars(strip_tags($this->user_id));
    $this->trade_id=htmlspecialchars(strip_tags($this->trade_id));
    $this->exchange_id=htmlspecialchars(strip_tags($this->exchange_id));
    $this->trade_pair=htmlspecialchars(strip_tags($this->trade_pair));
    $this->qty=htmlspecialchars(strip_tags($this->qty));
    $this->price=htmlspecialchars(strip_tags($this->price));
    
    // bind values
    $stmt->bindParam(":user_id", $this->user_id);
    $stmt->bindParam(":trade_id", $this->trade_id);
    $stmt->bindParam(":exchange_id", $this->exchange_id);
    $stmt->bindParam(":trade_pair", $this->trade_pair);
    $stmt->bindParam(":qty", $this->qty);
    $stmt->bindParam(":price", $this->price);
    
    // execute query
    if($stmt->execute()){
      return true;
    }
    
    return false;
  }
  
  // read trade entries
  function read(){
   
    // select all query
    $query = "SELECT
    id, user_id, trade_id, exchange_id, trade_pair, date, qty, price, fully_closed
    FROM
    " . $this->table_name . " 
    ORDER BY
    date DESC";
    
    // prepare query statement
    $stmt = $this->conn->prepare($query);
    
    // execute query
    $stmt->execute();
    
    return $stmt;
  }
  
  //read single
  function readOne(){
   
    // select all query
    $query = "SELECT
    id, user_id, trade_id, exchange_id, trade_pair, date, qty, price, fully_closed
    FROM
    " . $this->table_name . " 
    WHERE
      id = ?
    LIMIT
    0,1";
 
    
    // prepare query statement
    $stmt = $this->conn->prepare($query);
    
    //bind
    $stmt->bindParam(1, $this->id);
    
    // execute query
    $stmt->execute();
    
    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
    // set values to object properties
    $this->id = $row['id'];
    $this->user_id = $row['user_id'];
    $this->trade_id = $row['trade_id'];
    $this->exchange_id = $row['exchange_id'];
    $this->trade_pair = $row['trade_pair'];
    $this->date = $row['date'];
    $this->qty = $row['qty'];
    $this->price = $row['price'];
    $this->fully_closed = $row['fully_closed'];
    //return $stmt;
  }
  
  
  // update
  function update(){

    // update query
    $query = "UPDATE
                " . $this->table_name . "
            SET
                user_id =:user_id,
                trade_id =:trade_id,
                exchange_id =:exchange_id,
                trade_pair =:trade_pair,
                qty =:qty,
                price =:price,
                fully_closed =:fully_closed
            WHERE
                id =:id";
    
    //prepare
    $stmt = $this->conn->prepare($query);
    
     // sanitize
    $this->user_id = htmlspecialchars(strip_tags($this->user_id));
    $this->trade_id = htmlspecialchars(strip_tags($this->trade_id));
    $this->exchange_id = htmlspecialchars(strip_tags($this->exchange_id));
    $this->trade_pair = htmlspecialchars(strip_tags($this->trade_pair));
    $this->qty = htmlspecialchars(strip_tags($this->qty));
    $this->price = htmlspecialchars(strip_tags($this->price));
    $this->fully_closed = htmlspecialchars(strip_tags($this->fully_closed));
    
    // bind values
    $stmt->bindParam(":id", $this->id);
    $stmt->bindParam(":user_id", $this->user_id);
    $stmt->bindParam(":trade_id", $this->trade_id);
    $stmt->bindParam(":exchange_id", $this->exchange_id);
    $stmt->bindParam(":trade_pair", $this->trade_pair);
    $stmt->bindParam(":qty", $this->qty);
    $stmt->bindParam(":price", $this->price);
    $stmt->bindParam(":fully_closed", $this->fully_closed);
    
    //execute
    if($stmt -> execute()){
      return true;
    }
    
    return false;
  }
  
  
  //DELETE
  function delete(){
   // delete query
    $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
 
    // prepare query
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $this->id=htmlspecialchars(strip_tags($this->id));
 
    // bind id of record to delete
    $stmt->bindParam(1, $this->id);
 
    // execute query
    if($stmt->execute()){
        return true;
    }
 
    return false; 
  }

}
  

?>