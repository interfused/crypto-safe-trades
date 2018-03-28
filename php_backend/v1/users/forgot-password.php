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

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function sendResetEmail($cstUser){
  ob_start();
  $greetingName = $cstUser->first_name? $cstUser->first_name : 'Friend';
  ?>
  <p>Dear <?php echo $greetingName;?>,</p>
  <p>Here is your authorization code: <?php echo $cstUser->pw_reset_key;?></p>
  <p>You will need it in order to reset your password</p>
    
  
  <?php
  $mailBody = ob_get_clean();
  $headers = "From: " . strip_tags('noreply@cryptosafetrades.com') . "\r\n";
  //$headers .= "Reply-To: ". strip_tags('noreply@cryptosafetrades.com') . "\r\n";
  //$headers .= "CC: susan@example.com\r\n";
  $headers .= "MIME-Version: 1.0\r\n";
  $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
  
  if( mail($cstUser->email, 'Your password reset instructions for Crypto Safe Trades',$mailBody,$headers) ){
    return true;
  }else{
    return false;
  }
  
}

//

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
  
  //$salt = "498#2D83B631%3800EBD!801600D*7E3CC13";
  $salt = generateRandomString(36);
  //
  $cstUser->pw_reset_key = hash('sha512', $salt.$cstUser->email) ;
  
  if( sendResetEmail( $cstUser ) ){
    //store key
    
    if( $cstUser->setPwRecoveryKey() ){
      $response['status']  = 200;
      $response['message'] = 'Email sent with instructions.';  
    }else{
      $response['status']  = 200;
      $response['message'] = 'Email could not be sent.';     
    }
      
  }
  
}

echo(json_encode($response));
?>