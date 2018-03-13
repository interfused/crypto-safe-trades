<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/trade-entry.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare tradeEntry object
$tradeEntry = new TradeEntry($db);
 
// get id of tradeEntry to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of tradeEntry to be edited
$tradeEntry->id = $data->id;
 
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
    echo '{';
        echo '"message": "Trade entry was updated."';
    echo '}';
}
 
// if unable to update the tradeEntry, tell the user
else{
    echo '{';
        echo '"message": "Unable to update trade entry."';
    echo '}';
}
?>