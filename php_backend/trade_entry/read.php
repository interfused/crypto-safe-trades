<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/database.php';
include_once '../objects/trade-entry.php';

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
    $entries_arr["records"] = array();
 
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
 
        $trade_item = array(
            "id" => $id,
            "user_id" => $user_id,
            "trade_id" => $trade_id,
            "exchange_id" => $exchange_id,
            "trade_pair" => $trade_pair,
            "date" => $date,
            "qty" => $qty,
            "price" => $price,
            "fully_closed" => $fully_closed
        );
 
        array_push($entries_arr["records"], $trade_item);
    }
 
    echo json_encode($entries_arr);
    
}else{
  echo json_encode(
    array("message" => "No trade entries found.")
  );
}
?>