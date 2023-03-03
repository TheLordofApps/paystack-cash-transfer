<?php
 $curl = curl_init();
 curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

 $url = "https://api.paystack.co/transfer/enable_otp/TRF_1xmadeav5c4wx11";
 curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_HTTPHEADER => [
    "accept: application/json",
    "authorization: Bearer XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX",
    "cache-control: no-cache"
  ],
));

$response = curl_exec($curl);
$err = curl_error($curl);

if($err){
    // there was an error contacting the Paystack API
  die('Curl returned error: ' . $err);
}

$verify_transfer = json_decode($response);

echo "<pre>".$response."</pre>";
//echo $verify_transfer->message."<br>";

/*

*/
  ?>
 