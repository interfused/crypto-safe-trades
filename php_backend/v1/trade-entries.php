<?php
//https://www.phpflow.com/php/create-php-restful-api-without-rest-framework-dependency/
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");
// include database and object files
include_once '../config/database.php';
include_once '../objects/trade-entry.php';

$request_method = $_SERVER['REQUEST_METHOD'];

//GET REQUEST METHOD
switch($request_method)
{
  case 'OPTIONS':
  break;
  case 'GET':
    // read
    if(!empty($_GET['id'])){
      $id=intval($_GET['id']);
      get_trade_entry_single($id);
    }else{
      get_trade_entries();
    }
  break;
  
  case 'POST':
    //create
    create_trade_entry();
  break;
  
  case 'PUT':
    //update
    if(!empty($_GET['id'])){
      $id=intval($_GET['id']);
      update_trade_entry($id);
    }else{
      echo ( json_encode(
        array(
          'status' => 204,
          'message' => 'no id provided'
        ) 
      ));
    }
  break;
  
  case 'DELETE':
    //delete
    if(!empty($_GET['id'])){
      $id=intval($_GET['id']);
      delete_trade_entry($id);
    }else{
      echo ( json_encode(
        array(
          'status' => 204,
          'message' => 'no id provided'
        ) 
      )); 
    }
  break;
  
  default:
      // Invalid Request Method
  header("HTTP/1.0 405 Method Not Allowed");
  break;
}


// -------- CREATE -------- 
function create_trade_entry(){

  $database = new Database();
  $db = $database->getConnection();
  $response = array();
  
  $tradeEntry = new TradeEntry($db);
  
  // get posted JSON data.  HEADER "Content-Type" must be "application/json" and send raw json post
  $data = json_decode(file_get_contents("php://input"));
  
  // set product property values

  $tradeEntry->user_id = $data->user_id;
  $tradeEntry->trade_id = $data->trade_id;
  $tradeEntry->exchange_id = $data->exchange_id;
  $tradeEntry->trade_pair = $data->trade_pair;
  $tradeEntry->qty = $data->qty;
  $tradeEntry->price = $data->price;

  //$tradeEntry->created = date('Y-m-d H:i:s');
  
  // create the product
  if($tradeEntry->create()){
    $response['status']  = 200;
    $response['message'] = 'Trade entry was created.';
  }else{
    $response['status']  = 200;
    $response['message'] = 'Unable to create trade entry.';
  } 
  echo(json_encode($response));
}

// -------- READ ALL --------
function get_trade_entries(){
  // instantiate database and product object
  $database = new Database();
  $db = $database->getConnection();

  // initialize object
  $tradeEntry = new TradeEntry($db);

// query products
  $stmt = $tradeEntry->read();
  $num = $stmt->rowCount();

// check if more than 0 record found
  if($num>0){
  // products array
    $entries_arr = array();
    $entries_arr['status'] = 200;
    $entries_arr['message'] = 'Successfully received trades.';
    $entries_arr['data']['records'] = array();
    
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
      extract($row);
      
      $trade_item = array(
        'id' => $id,
        'user_id' => $user_id,
        'trade_id' => $trade_id,
        'exchange_id' => $exchange_id,
        'trade_pair' => $trade_pair,
        'date' => $date,
        'qty' => $qty,
        'price' => $price,
        'fully_closed' => $fully_closed
      );
      
      array_push($entries_arr['data']['records'], $trade_item);
    }
    
    echo json_encode($entries_arr);
    
  }else{
    echo json_encode(
      array(
        'status' => 200,
        'message' => 'No trade entries found.'
      )
    );
  }
}


// -------- READ SINGLE --------
function get_trade_entry_single($id = 0){
  // get database connection
  $database = new Database();
  $db = $database->getConnection();
  
// prepare product object
  $tradeEntry = new TradeEntry($db);
  
// set ID property of product to be edited
  $tradeEntry->id = ($id != 0) ? $id : die();

// read the details of product to be edited
  $tradeEntry->readOne();
  
  if( is_null($TradeEntry->id) ){
    $response = array(
      'status' => 404,
      'message' => 'record not found'
    );
    
  }else{
    $response = array(
      'status' => 200,
      'id' =>  $tradeEntry->id,
      'user_id' => $tradeEntry->user_id,
      'trade_id' => $tradeEntry->trade_id,
      'exchange_id' => $tradeEntry->exchange_id,
      'trade_pair' => $tradeEntry->trade_pair,
      'date' => $tradeEntry->date,
      'qty' => $tradeEntry->qty,
      'price' => $tradeEntry->price,
      'fully_closed' => $tradeEntry->fully_closed,    
    );  
  }
  
  echo(json_encode($response));
}

// -------- UPDATE --------
function update_trade_entry($id){
  // get database connection
  $database = new Database();
  $db = $database->getConnection();
  $response = array();
  
  // prepare tradeEntry object
  $tradeEntry = new TradeEntry($db);
  
  // get id of tradeEntry to be edited
  $data = json_decode(file_get_contents("php://input"));
  
  // set ID property of tradeEntry to be edited
  $tradeEntry->id = $id;
  
  // set tradeEntry property values
  $tradeEntry->user_id = $data->user_id;
  $tradeEntry->trade_id = $data->trade_id;
  $tradeEntry->exchange_id = $data->exchange_id;
  $tradeEntry->trade_pair = $data->trade_pair;
  $tradeEntry->qty = $data->qty;
  $tradeEntry->price = $data->price;
  $tradeEntry->fully_closed = $data->fully_closed;
  
  // update the tradeEntry
  if($tradeEntry->update()){
    $response['status']  = 200;
    $response['message'] = 'Trade entry was updated.';
  }else{
    $response['status']  = 204;
    $response['message'] = 'Unable to update trade entry.';
  }
  
  echo(json_encode($response));
}

// -------- DELETE --------
function delete_trade_entry($id){
  // get database connection
  $database = new Database();
  $db = $database->getConnection();
  $response = array();
  
  // prepare trade-entry object
  $tradeEntry = new TradeEntry($db);
  
  // get trade-entry id
  $data = json_decode(file_get_contents("php://input"));
  
  // set trade-entry id to be deleted
  $tradeEntry->id = $id;
  
  // delete the trade-entry
  if($tradeEntry->delete()){
    $response['status']  = 200;
    $response['message'] = 'Trade entry was deleted.';
  }else{
    $response['status']  = 204;
    $response['message'] = 'Unable to delete trade entry.';
  }
  echo json_encode($response);
}
?>