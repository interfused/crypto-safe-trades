<?php

class CstUser{
  //database connection and table name
  private $conn;
  private $table_name = 'cst_users';
  
  //object properties
  public $id;
  public $email;
  public $first_name;
  public $last_name;
  public $pw;
  public $pw_reset_key;
  public $date;
  public $cell_phone;
  public $binance_key;
  public $binance_secret;
  public $cryptopia_key;
  
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
                email=:email, first_name=:first_name, last_name=:last_name, pw=:pw, cell_phone=:cell_phone";
 
    
    // prepare query statement
    $stmt = $this->conn->prepare($query);
    
    // sanitize
    $this->email=htmlspecialchars(strip_tags($this->email));
    $this->first_name=htmlspecialchars(strip_tags($this->first_name));
    $this->last_name=htmlspecialchars(strip_tags($this->last_name));
    $this->pw=htmlspecialchars(strip_tags($this->pw));
    $this->cell_phone=htmlspecialchars(strip_tags($this->cell_phone));
    
    // bind values
    $stmt->bindParam(":email", $this->email);
    $stmt->bindParam(":first_name", $this->first_name);
    $stmt->bindParam(":last_name", $this->last_name);
    $stmt->bindParam(":pw", $this->pw);
    $stmt->bindParam(":cell_phone", $this->cell_phone);
    
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
    id, email, first_name, last_name, pw, date, cell_phone, binance_key, binance_secret, cryptopia_key
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
    id, email, first_name, last_name, pw, date, cell_phone, binance_key, binance_secret, cryptopia_key
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
    $this->email = $row['email'];
    $this->first_name = $row['first_name'];
    $this->last_name = $row['last_name'];
    $this->pw = $row['pw'];
    $this->date = $row['date'];
    $this->cell_phone = $row['cell_phone'];
    $this->binance_key = $row['binance_key'];
    $this->binance_secret = $row['binance_secret'];
    $this->cryptopia_key = $row['cryptopia_key'];
    //return $stmt;
  }
  
  
  // update
  function update(){

    // update query
    $query = "UPDATE
                " . $this->table_name . "
            SET
                email =:email,
                first_name =:first_name,
                last_name =:last_name,
                pw =:pw,
                cell_phone =:cell_phone,
                binance_key =:binance_key,
                binance_secret =:binance_secret,
                cryptopia_key =:cryptopia_key
            WHERE
                id =:id";
    
    //prepare
    $stmt = $this->conn->prepare($query);
    
     // sanitize
    $this->email = htmlspecialchars(strip_tags($this->email));
    $this->first_name = htmlspecialchars(strip_tags($this->first_name));
    $this->last_name = htmlspecialchars(strip_tags($this->last_name));
    $this->pw = htmlspecialchars(strip_tags($this->pw));
    $this->cell_phone = htmlspecialchars(strip_tags($this->cell_phone));
    $this->binance_key = htmlspecialchars(strip_tags($this->binance_key));
    $this->binance_secret = htmlspecialchars(strip_tags($this->binance_secret));
    $this->cryptopia_key = htmlspecialchars(strip_tags($this->cryptopia_key));
    
    // bind values
    $stmt->bindParam(":id", $this->id);
    $stmt->bindParam(":email", $this->email);
    $stmt->bindParam(":first_name", $this->first_name);
    $stmt->bindParam(":last_name", $this->last_name);
    $stmt->bindParam(":pw", $this->pw);
    $stmt->bindParam(":cell_phone", $this->cell_phone);
    $stmt->bindParam(":binance_key", $this->binance_key);
    $stmt->bindParam(":binance_secret", $this->binance_secret);
    $stmt->bindParam(":cryptopia_key", $this->cryptopia_key);
    
    //execute
    if($stmt -> execute()){
      return true;
    }
    
    return false;
  }
  
  
  function setPwRecoveryKey(){

    // update query
    $query = "UPDATE
                " . $this->table_name . "
            SET
                pw_reset_key =:pw_reset_key
            WHERE
                email =:email";
    
    //prepare
    $stmt = $this->conn->prepare($query);
    
     // sanitize
    $this->pw = htmlspecialchars(strip_tags($this->pw));
    $this->pw_reset_key = htmlspecialchars(strip_tags($this->pw_reset_key));
    
    // bind values
    $stmt->bindParam(":email", $this->email);
    $stmt->bindParam(":pw_reset_key", $this->pw_reset_key);
    
    //execute
    if($stmt -> execute()){
      return true;
    }
    
    return false;
  }
  
  function clearPwRecoveryKey(){

    // update query
    $query = "UPDATE
                " . $this->table_name . "
            SET
                pw_reset_key =:pw_reset_key
            WHERE
                email =:email";
    
    //prepare
    $stmt = $this->conn->prepare($query);
    
     // sanitize
    $this->email = htmlspecialchars(strip_tags($this->email));
    $this->pw_reset_key = htmlspecialchars(strip_tags($this->pw_reset_key));
    
    // bind values
    $stmt->bindParam(":email", $this->email);
    $stmt->bindParam(":pw_reset_key", $this->pw_reset_key);
    $stmt->bindValue(":pw_reset_key", null, PDO::PARAM_NULL);
    
    //execute
    if($stmt -> execute()){
      return true;
    }
    
    return false;
  }
  
  // update
  function updatePassword(){

    // update query
    $query = "UPDATE
                " . $this->table_name . "
            SET
                pw =:pw
            WHERE
                pw_reset_key =:pw_reset_key";
    
    //prepare
    $stmt = $this->conn->prepare($query);
    
     // sanitize
    $this->pw = htmlspecialchars(strip_tags($this->pw));
    $this->pw_reset_key = htmlspecialchars(strip_tags($this->pw_reset_key));
    
    // bind values
    $stmt->bindParam(":pw", $this->pw);
    $stmt->bindParam(":pw_reset_key", $this->pw_reset_key);
    
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
  
  function get_user_by_column($column = 'email'){
   
    // select all query
    $query = "SELECT * 
    FROM
    " . $this->table_name . " 
    WHERE
      ".$column." = ?
    LIMIT
    0,1";
 
    // prepare query statement
    $stmt = $this->conn->prepare($query);
    
    //bind
    $stmt->bindParam(1, $this->$column);
//    $stmt->bindParam(":email", $this->email);
    
    // execute query
    $stmt->execute();
    
    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
    // set values to object properties
    $this->id = $row['id'];
    $this->email = $row['email'];
    $this->first_name = $row['first_name'];
    $this->last_name = $row['last_name'];
    $this->pw = $row['pw'];
    $this->pw_reset_key = $row['pw_reset_key'];
    $this->date = $row['date'];
    $this->cell_phone = $row['cell_phone'];
    $this->binance_key = $row['binance_key'];
    $this->binance_secret = $row['binance_secret'];
    $this->cryptopia_key = $row['cryptopia_key'];
    //return $stmt;
  }

}
  

?>