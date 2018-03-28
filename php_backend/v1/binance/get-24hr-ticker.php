<?php
header("Access-Control-Allow-Origin: *");
// Turn off all error reporting
error_reporting(0);
//https://github.com/binance-exchange/php-binance-api
require '../../vendor/autoload.php';
$api = new Binance\API();
$exchangeInfo = $api->prevDay();

echo json_encode($exchangeInfo);
?>