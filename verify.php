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
//        echo 'Transation success. RefID:' . $result->RefID;
//        echo error_handler($result->Status);
    } else {
//        echo 'Transation failed. Status:' . error_handler($result->Status);
    }
} else {
    $result->Status = 1000;
//    echo 'Transaction canceled by user';
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>
        Ballyhoo|Payment
    </title>
    <link rel="stylesheet" type="text/css" href="./assets/semantic.min.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" type="text/css" href="assets/fonts/IRANSANS/iransans.css">
</head>
<body>
<div class="column three centered msg" style="text-align: center;">
    <div class="ui container">
        <div class="column centered" style="padding: 40px;">
            <?= error_handler($result->Status) ?>
        </div>
    </div>
</div>
<div class="column three centered msg" style="text-align: center;">
    <div class="ui container">
        <button class="ui button green iransans" type="submit">بازگشت به سایت</button>
    </div>
</div>
</body>
</html>
