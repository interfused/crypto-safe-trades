<?php
//https://www.phpflow.com/php/create-php-restful-api-without-rest-framework-dependency/
header("Access-Control-Allow-Origin: *");
// include database and object files
include_once '../config/database.php';
include_once '../objects/trade-exit.php';

$request_method=$_SERVER['REQUEST_METHOD'];

//GET REQUEST METHOD
switch($request_method)
{
  case 'GET':
    // read all for parent ids
    if(!empty($_GET['entry_id'])){
      $entry_id = ($_GET['entry_id']);
      
      if( !empty($_GET['id']) ){        
        $id = intval($_GET['id']);
        get_trade_exit_single($entry_id, $id);

      }else{

        get_trade_exits($entry_id);  
      }
      
    }else{
      echo ( json_encode(
        array(
          'status' => 404,
          'message' => 'no parent id provided'
        ) 
      ));
    }
  break;
  
  case 'POST':
    //create
    create_trade_exit();
  break;
  
  case 'PUT':
    //update
    if(!empty($_GET['entry_id']) && !empty($_GET['id'])  ){
      $id = intval($_GET['id']);
      update_trade_exit($id);
    }else{
      echo ( json_encode(
        array(
          'status' => 404,
          'message' => 'no id provided'
        ) 
      ));
    }
  break;
  
  case 'DELETE':
    //delete
    if(!empty($_GET['entry_id']) && !empty($_GET['id']) ){
      $id = intval($_GET['id']);
      delete_trade_exit($id);
    }else{
      echo ( json_encode(
        array(
          'status' => 404,
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
function create_trade_exit(){
  $database = new Database();
  $db = $database->getConnection();
  $response = array();
  
  $tradeExit = new TradeExit($db);
  
  // get posted JSON data.  HEADER "Content-Type" must be "application/json" and send raw json post
  $data = json_decode(file_get_contents("php://input"));
  
  // set product property values

  $tradeExit->user_id = $data->user_id;
  $tradeExit->entry_id = $data->entry_id;
  $tradeExit->trade_id = $data->trade_id;
  $tradeExit->exchange_id = $data->exchange_id;
  $tradeExit->trade_pair = $data->trade_pair;
  $tradeExit->qty = $data->qty;
  $tradeExit->price = $data->price;
  $tradeExit->executed = $data->executed;

  //$tradeExit->created = date('Y-m-d H:i:s');
  
  // create the product
  if($tradeExit->create()){
    $response['status'] = 200; 
    $response['message'] = 'Trade exit was created.';
  }else{
    $response['status'] = 204;
    $response['message'] = 'Unable to create trade exit.';
  } 
  echo(json_encode($response));
}


// -------- READ ALL --------
function get_trade_exits($entry_id){
  // instantiate database and product object
  $database = new Database();
  $db = $database->getConnection();
  
  
  // initialize object
  $tradeExit = new TradeExit($db);
  
  // set ID property of product to be edited
  $tradeExit->entry_id = ($entry_id != '0') ? $entry_id : die();
  
  
// query products
  $stmt = $tradeExit->read();
  $num = $stmt->rowCount();

// check if more than 0 record found
  if($num>0){
  // products array
    $exits_arr = array();
    $exits_arr['data']['records'] = array();
    
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
        'entry_id' => $entry_id,
        'trade_id' => $trade_id,
        'exchange_id' => $exchange_id,
        'trade_pair' => $trade_pair,
        'date' => $date,
        'qty' => $qty,
        'price' => $price,
        'executed' => $executed
      );
      
      array_push($exits_arr['data']['records'], $trade_item);
    }
    
    echo json_encode($exits_arr);
    
  }else{
    echo json_encode(
      array(
        'status' => 204,
        'message' => 'No trade entries found.'
      )
    );
  }
}

// -------- READ SINGLE --------
function get_trade_exit_single($entry_id='fakeID', $id = 0){
  // get database connection
  $database = new Database();
  $db = $database->getConnection();
  
// prepare product object
  $tradeExit = new TradeExit($db);
  
// set ID property of product to be edited
  $tradeExit->entry_id = ($entry_id != 'fakeID') ? $entry_id : die();
  $tradeExit->id = (intval($id) != 0) ? intval($id) : die();

// read the details of product to be edited
  $tradeExit->readOne();
        
  if( empty($tradeExit->id) ){
    $response = array(
      'status' => 204,
      'message' => 'No record found for '.$entry_id
    );
    
  }else{
    $response = array(
      'status' => 200,
      'id' =>  $tradeExit->id,
      'user_id' => $tradeExit->user_id,
      'entry_id' => $tradeExit->entry_id,
      'trade_id' => $tradeExit->trade_id,
      'exchange_id' => $tradeExit->exchange_id,
      'trade_pair' => $tradeExit->trade_pair,
      'date' => $tradeExit->date,
      'qty' => $tradeExit->qty,
      'price' => $tradeExit->price,
      'executed' => $tradeExit->executed,    
    );  
  }
  
  echo(json_encode($response));
}

// -------- UPDATE --------
function update_trade_exit($id){
  // get database connection
  $database = new Database();
  $db = $database->getConnection();
  $response = array();
  
  // prepare tradeExit object
  $tradeExit = new TradeExit($db);
  
  // get id of tradeExit to be edited
  $data = json_decode(file_get_contents("php://input"));
  
  // set ID property of tradeExit to be edited
  $tradeExit->id = $id;
  
  // set tradeExit property values
  $tradeExit->user_id = $data->user_id;
  $tradeExit->entry_id = $data->entry_id;
  $tradeExit->trade_id = $data->trade_id;
  $tradeExit->exchange_id = $data->exchange_id;
  $tradeExit->trade_pair = $data->trade_pair;
  $tradeExit->qty = $data->qty;
  $tradeExit->price = $data->price;
  $tradeExit->executed = $data->executed;
  
  // update the tradeExit
  if($tradeExit->update()){
    $response['status'] = 200;
    $response['message'] = 'Trade exit was updated.';
  }else{
    $response['status'] = 204;
    $response['message'] = 'Unable to update trade exit.';
  }
  
  echo(json_encode($response));
}

// -------- DELETE --------
function delete_trade_exit($id){
  // get database connection
  $database = new Database();
  $db = $database->getConnection();
  $response = array();
  
  // prepare trade-exit object
  $tradeExit = new TradeExit($db);
  
  // get trade-exit id
  $data = json_decode(file_get_contents("php://input"));
  
  // set trade-exit id to be deleted
  $tradeExit->id = $id;
  
  // delete the trade-exit
  if($tradeExit->delete()){
    $response['status'] = 200;
    $response['message'] = 'Trade exit was deleted.';
  }else{
    $response['status'] = 204;
    $response['message'] = 'Unable to delete trade exit.';
  }
  echo json_encode($response);
}
?>