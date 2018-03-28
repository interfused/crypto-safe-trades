<?php
//https://www.phpflow.com/php/create-php-restful-api-without-rest-framework-dependency/
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");
// include database and object files
require_once '../../config/database.php';
include_once '../../objects/user.php';

$request_method = $_SERVER['REQUEST_METHOD'];

//require userid 
//valid
//password

if($request_method !== 'POST'){
  die('OOPS');
}

//store in DB
$database = new Database();
  $db = $database->getConnection();
  $response = array();
  
  $cstUser = new CstUser($db);
  
  // get posted JSON data.  HEADER "Content-Type" must be "application/json" and send raw json post
  $data = json_decode(file_get_contents("php://input"));
  
  // set product property values

  $cstUser->id = $data->id;
  $cstUser->email = $data->email;
  $cstUser->first_name = $data->first_name;
  $cstUser->last_name = $data->last_name;
  $hashed_password = password_hash($data->pw, PASSWORD_DEFAULT);
  $cstUser->pw = $hashed_password;
  $cstUser->cell_phone = $data->cell_phone;

  //$cstUser->created = date('Y-m-d H:i:s');
  
  // create the product
  if($cstUser->create()){
    $response['status']  = 200;
    $response['message'] = 'User was created.';
  }else{
    $response['status']  = 200;
    $response['message'] = 'Unable to create user.';
  } 
  echo(json_encode($response));

?>