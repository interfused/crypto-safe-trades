<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/trade-entry.php';

// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare product object
$tradeEntry = new TradeEntry($db);
 
// set ID property of product to be edited
$tradeEntry->id = isset($_GET['id']) ? $_GET['id'] : die();

// read the details of product to be edited
$tradeEntry->readOne();
 
// create array
$tradeEntry_arr = array(
    "id" =>  $tradeEntry->id,
    "user_id" => $tradeEntry->user_id,
    "trade_id" => $tradeEntry->trade_id,
    "exchange_id" => $tradeEntry->exchange_id,
    "trade_pair" => $tradeEntry->trade_pair,
    "date" => $tradeEntry->date,
    "qty" => $tradeEntry->qty,
    "price" => $tradeEntry->price,
    "fully_closed" => $tradeEntry->fully_closed,    
);
 
// make it json format
print_r(json_encode($tradeEntry_arr));

?>