<?php
//Session start
session_start();
if(!isset($_SESSION['transfer_code'])){
    header("Location: create_transfer.php");
    exit;
}else{
    $transfer_code =  $_SESSION['transfer_code'];
}

$otp = "";
$otp_error = "";

if(isset($_POST["enter_otp"])){
  //sanitize form inputs
$sanitize = filter_var_array($_POST, FILTER_SANITIZE_STRING);

//Gather form inputs
  $otp = $sanitize["otp"];

if(empty($otp)){
   $otp_error = "Please, enter the OTP sent to your business phone or email";
}

//Grab unto the endpoint
  $url = "https://api.paystack.co/transfer/finalize_transfer";

//Gather the customer detail in an array
  $transfer_otp = [
    "transfer_code" => $transfer_code,
    "otp" => $otp
];

//Generates a URL-encoded query string from the associative (or indexed) array above.
  $encode_transfer_otp = http_build_query($transfer_otp);

  //open connection
  $ch = curl_init();

  //Turn off mandatory SSL checker
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  
  //set the url
  curl_setopt($ch,CURLOPT_URL, $url);

   //enable  data to be sent in POST method
  curl_setopt($ch,CURLOPT_POST, true);

    //Collect the posted data 
  curl_setopt($ch, CURLOPT_POSTFIELDS,   $encode_transfer_otp);

  //Set the headers from the endpoint 
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
 //enter your test secret key in front of the word Bearer
 //sk_live_f3898e4b79175676c2ba74591617ebcccaa568d1 live
 //sk_test_37aa4cd5a1d8cb11dee6828b2f865f0c7d6614d1 test
    "Authorization: Bearer XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX",
    "Cache-Control: no-cache"
  ));
  
  //So that curl_exec returns the contents of the cURL; rather than echoing it
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
  
  //execute post
  $result = curl_exec($ch);
  $errors = curl_error($ch);
  if($errors){
    die("Curl returned an error: " . $errors);
  }
$transfer = json_decode($result);
$message = $transfer->message;

//echo"<pre>" . $result . "</pre>";

}else{
  $message ="";
}

?>

<!DOCTYPE html>
<html lang="en-us">
<head>
    <title>Create customer in paystack</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Enter the OTP sent to your business phone or email below:</h1>
    <hr/>
    <div class="container">
    <span class="error"><?php echo $message; ?></span>
      <form action="" method="post">
            <label>Enter OTP: </label>
            <span class="error"><?php echo $otp_error; ?></span>
            <input type="text" name="otp" placeholder="Enter OTP...">
            <input type="submit" name="enter_otp" value="Confirm Transfer">
        </form>
    </div>
</body>
</html>