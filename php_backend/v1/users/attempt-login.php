<?php
//https://www.phpflow.com/php/create-php-restful-api-without-rest-framework-dependency/
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");
// include database and object files
require_once '../../config/database.php';
include_once '../../objects/user.php';

$requestMethod = $_SERVER['REQUEST_METHOD'];

//require userid 
//valid
//password

if($requestMethod !== 'POST'){
  die('OOPS');
}

$database = new Database();
$db = $database->getConnection();
$response = array();

$cstUser = new CstUser($db);

// get posted JSON data.  HEADER "Content-Type" must be "application/json" and send raw json post
$data = json_decode(file_get_contents("php://input"));

// set user property values
$cstUser->email = $data->email;

//get the user by email
$cstUser->get_user_by_column('email');

if(is_null($cstUser->id)){
  $response['status']  = 200;
  $response['message'] = 'User not found.';
}else{
  //check password  
  if( password_verify($data->pw, $cstUser->pw ) ){
    $response['status']  = 200;
    $response['message'] = 'Successful login.';
  }else{
    $response['status']  = 200;
    $response['message'] = 'Invalid password.';
  }
}

echo(json_encode($response));
?>