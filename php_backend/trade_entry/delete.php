<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
 
// include database and object file
include_once '../config/database.php';
include_once '../objects/trade-entry.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare trade-entry object
$tradeEntry = new TradeEntry($db);
 
// get trade-entry id
$data = json_decode(file_get_contents("php://input"));
 
// set trade-entry id to be deleted
$tradeEntry->id = $data->id;
 
// delete the trade-entry
if($tradeEntry->delete()){
    $fbArr = array(
      'message' => 'Trade entry was deleted.'
    );
}
 
// if unable to delete the trade-entry
else{
    
$fbArr = array(
      'message' => 'Unable to delete trade entry.'
    );
}

    echo json_encode($fbArr);
?>