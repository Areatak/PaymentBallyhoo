<?php
include './funcs.php';
include './error.php';
$config = parse_ini_file('./config.ini');


$MerchantID = $config['merchantId'];  //Required
$Amount = $config['amount']; //Amount will be based on Toman
$Authority = $_GET['Authority'];

if ($_GET['Status'] == 'OK') {
    // URL also can be ir.zarinpal.com or de.zarinpal.com
    $client = new SoapClient('https://www.zarinpal.com/pg/services/WebGate/wsdl', ['encoding' => 'UTF-8']);

    $result = $client->PaymentVerification([
        'MerchantID' => $MerchantID,
        'Authority' => $Authority,
        'Amount' => $Amount,
    ]);
    if ($result->Status == 100) {
        update_payment_request($Authority, $result->RefID);

        $req = find_request_by_authority($Authority);
        sold($req['iaaId']);
        echo 'Transation success. RefID:' . $result->RefID;
        echo error_handler($result->Status);
    } else {
        echo 'Transation failed. Status:' . error_handler($result->Status);
    }
} else {
    echo 'Transaction canceled by user';
}
