<?php
include './funcs.php';
$config = parse_ini_file('./config.ini');


create_request_table();

$iaaId = $_GET['id'];
$txInfo = get_tx_info($iaaId);


$MerchantID = $config['merchantId'];;  //Required
$Amount = $config['amount']; //Amount will be based on Toman  - Required
$Description = $txInfo->desc == null ? 'توضیحات' : $txInfo->desc;  // Required
$Email = ''; // Optional
$Mobile = ''; // Optional
$CallbackURL = $config['paymentAddress'].'/verify.php';  // Required


// URL also can be ir.zarinpal.com or de.zarinpal.com
$client = new SoapClient('https://www.zarinpal.com/pg/services/WebGate/wsdl', ['encoding' => 'UTF-8']);

$result = $client->PaymentRequest([
    'MerchantID' => $MerchantID,
    'Amount' => $Amount,
    'Description' => $Description,
    'Email' => $Email,
    'Mobile' => $Mobile,
    'CallbackURL' => $CallbackURL,
]);


//Redirect to URL You can do it also by creating a form
if ($result->Status == 100) {
    insert_payment_request($iaaId, $result->Authority);
    header('Location: https://www.zarinpal.com/pg/StartPay/' . $result->Authority);
} else {
    echo 'ERR: ' . $result->Status;
}
