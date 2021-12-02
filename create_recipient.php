<?php
if(isset($_POST["recipient"])){
   //Start session
   session_start();
   $bankCode = $_SESSION['bank_code'];
   $accountName= $_SESSION['account_name'];
   $accountNumber = $_SESSION['account_number'];
}else{
    header("Location: index.php");
    exit;
}
//Gather the recipients details
$recipient_data = [
    "type" => "nuban",
    "name" => "Transfer Fund",
    "description" => "Staff Salary List",
    "account_number" => $accountNumber,
    "account_name" => $accountName,
    "bank_code" => $bankCode,
    "currency" => "NGN"
];

$ecncode_recipient_data = http_build_query($recipient_data );

//Perform Paystack integration
$curl = curl_init(); //Init curl

//Turn off mandatory ssl
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
//Resolve account endpoint
$url = "https://api.paystack.co/transferrecipient";

//Configure cURL options
curl_setopt_array($curl, array(
        //Set the above endpoint in cURL
        CURLOPT_URL => $url,
        
        //Send data in POST method
        CURLOPT_POST => true,

        //Collect the postfied
        CURLOPT_POSTFIELDS => $ecncode_recipient_data,

        //Make curl return data
        CURLOPT_RETURNTRANSFER => true,

        //Set the CURL headers
        CURLOPT_HTTPHEADER => [
            "accept: application/json",
            "authorization: Bearer sk_test_4f15f9df38e2a07a01ea5055c3bf1fa05e19640f",
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
$recipient= json_decode($response);
echo"<pre>" . $response . "</pre>";
$recipient_code = $recipient->data->recipient_code;
$_SESSION["recipient_code"] = $recipient_code;
?>
 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Fund Recipient in Paystack</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
   <h1>Recipient Created! Click on the "Transfer" button below to start the Transfer.</h1>
   <hr>
   <div class="container">
     <h3>Response:<i><?php echo $recipient->message; ?></i></h3>
     <h3>Purpose:<i><?php echo $recipient->data->name; ?></i></h3>
     <h3>description:<i><?php echo $recipient->data->description; ?></i></h3>
     <h3>Recipient Code:<i><?php echo $recipient->data->recipient_code; ?></i></h3>
     <h3>Account Name:<i><?php echo $recipient->data->details->account_name; ?></i></h3>
     <h3>Account Number:<i><?php echo $recipient->data->details->account_number; ?></i></h3>
     <h3>Bank Name:<i><?php echo $recipient->data->details->bank_name; ?></i></h3>
     <form action="create_transfer.php" method="Post">
        <label>Transfer Reason</labe>
        <input type="text" name="reason" placeholder="Transfer Reason..." required>
         <label>Amount</labe>
        <input type="number" name="amount" placeholder="Amount to transfer..." required>
        <input type="submit" name="transfer" value="Transfer">
     </form>
   </div> 
</body>
</html>
 