<?php
header("Access-Control-Allow-Origin: *");
//https://github.com/binance-exchange/php-binance-api
require '../../vendor/autoload.php';
$api = new Binance\API();
$exchangeInfo = $api->exchangeInfo();

echo json_encode($exchangeInfo);
?>