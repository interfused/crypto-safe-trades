<?php
//https://www.phpflow.com/php/create-php-restful-api-without-rest-framework-dependency/
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");
// include database and object files
require_once '../../config/database.php';
require_once '../../objects/user.php';

$request_method = $_SERVER['REQUEST_METHOD'];

//require userid 
//valid
//password

if($request_method !== 'POST'){
  die('OOPS');
  //store in DB
}

$database = new Database();
$db = $database->getConnection();
$response = array();
$cstUser = new CstUser($db);

$data = json_decode(file_get_contents('php://input'));


$cstUser->pw_reset_key = $data->pw_reset_key;
$cstUser->get_user_by_column('pw_reset_key');

$hash = password_hash($data->pw, PASSWORD_DEFAULT);   
$cstUser->pw = $hash;


if( $cstUser->updatePassword() ){
  //clear previous key
  $cstUser->pw_reset_key = null ;
  if( $cstUser->clearPwRecoveryKey() ){
      $response['status']  = 200;
      $response['message'] = 'Successfully updated password.';  
    }else{
      $response['status']  = 200;
      $response['message'] = 'Could not update password.';     
    }
  
}

  
//display response
echo json_encode($response);


?>