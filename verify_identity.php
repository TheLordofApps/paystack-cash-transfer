<?php
if(isset($_POST["identify"])){
   //Sanitize form inputs
   $sanitize = filter_var_array($_POST, FILTER_SANITIZE_STRING);

   //Gather the form inputs
   $accountNumber = $sanitize["account_number"];
   $bankCode = $sanitize["bank_code"];
}
if(empty($accountNumber) OR empty($bankCode)){
    die("Go back and fill in all fields!");
}

//Perform Paystack integration
$curl = curl_init(); //Init curl

//Turn off mandatory ssl
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
//Resolve account endpoint
$url = "https://api.paystack.co/bank/resolve?account_number=" . $accountNumber . "&bank_code=" . $bankCode;

//Configure cURL options
curl_setopt_array($curl, array(
        //Set the above endpoint in cURL
        CURLOPT_URL => $url,

        //Make curl return data
        CURLOPT_RETURNTRANSFER => true,

        //Set the CURL headers
        CURLOPT_HTTPHEADER => [
            "accept: application/json",
            "authorization: Bearer sk_test_add_yours_here",
            "cache-control: no-cache"
        ],
    )
);
//Execute the cURL
$response = curl_exec($curl);

//Control  errors
$err = curl_error($curl);

if($err){
    die("Curl returned some errors ".$err);
}

//Covert to object
$identity = json_decode($response);
$status = $identity->status;
$message  = $identity->message;
if($status == "true"){
    $accountNumber = $identity->data->account_number;
    $accountName = $identity->data->account_name;

   $move = '<form action="create_recipient.php" method="post">
              <input type="submit" name="recipient" value="Continue">
            </form>'; 
             $goBack = "";  
}else{
             $accountName = "Unknown Identity";
             $goBack = '<form action="index.php" method="post">
              <input type="submit" value="<<<Go Back">
            </form>'; 
            $move = ""; 
}
//echo "<pre>" . $response."</pre>";
?>
 
 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Beneficiary</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
   <h1>Your idendity has been verified! See your details below:</h1>
   <hr>
   <div class="container">
     <h3>Response:<i><?php echo $message; ?></i></h3>
     <h3>Account Name:<i><?php echo $accountName; ?></i></h3>
     <h3>Account Number:<i><?php echo $accountNumber; ?></i></h3>
     <?php echo $move; ?>
     <?php echo $goBack; ?>
   </div> 
</body>
</html>