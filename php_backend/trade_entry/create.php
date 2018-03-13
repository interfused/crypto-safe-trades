<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// get database connection
include_once '../config/database.php';
 
// instantiate product object
include_once '../objects/trade-entry.php';
 
$database = new Database();
$db = $database->getConnection();
 
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
    echo '{';
        echo '"message": "Trade entry was created."';
    echo '}';
}
 
// if unable to create the product, tell the user
else{
    echo '{';
        echo '"message": "Unable to create trade entry."';
    echo '}';
}
?>