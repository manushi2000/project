<?php

function stkpush($phone, $Amount){
$ch = curl_init('https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials');


$consumerKey = 'SjaUTPLM1PWFi2j2G2AP1Sz4QM406seX';
$consumerSecret = 'nB7EeDQKUqGYyfVo'; 
$credentials = $consumerKey.':'.$consumerSecret;
$credentials = base64_encode($credentials);

curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Basic '.$credentials]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($ch);
curl_close($ch);
// echo $response;


$access_token=json_decode($response)->access_token;
//echo $token;

$Passkey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';
$Timestamp = date('YmdHis');    
$BusinessShortCode = 174379;
$Password = base64_encode($BusinessShortCode.$Passkey.$Timestamp);
$PartyA = $phone; // This is your phone number,  
$AccountReference = 'T4Bfoundation';
$TransactionDesc = 'Test Payment';
$Amount = $Amount;

$initiate_url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

$stkheader = ['Content-Type:application/json','Authorization:Bearer '.$access_token];

  # initiating the transaction
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $initiate_url);
  curl_setopt($curl, CURLOPT_HTTPHEADER, $stkheader);

  $curl_post_data = array(
    //Fill in the request parameters with valid values
    'BusinessShortCode' => $BusinessShortCode,
    'Password' => $Password,
    'Timestamp' => $Timestamp,
    'TransactionType' => 'CustomerPayBillOnline',
    'Amount' => $Amount,
    'PartyA' => $PartyA,
    'PartyB' => $BusinessShortCode,
    'PhoneNumber' => $PartyA,
    'CallBackURL' => 'https://webhook.site/cb518d18-b4b4-45f9-b716-1c8e74ec9fee',
    'AccountReference' => $AccountReference,
    'TransactionDesc' => $TransactionDesc
  );

  $data_string = json_encode($curl_post_data);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
    $curl_response = curl_exec($curl);
//   print_r($curl_response);
}
?>