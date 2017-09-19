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
    $req = find_request_by_authority($Authority);

    if ($result->Status == 100) {
        update_payment_request($Authority, $result->RefID);

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
        BallyhooAwards.ir|Payment
    </title>
    <link rel="stylesheet" type="text/css" href="./assets/semantic.min.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" type="text/css" href="assets/fonts/IRANSANS/iransans.css">
</head>
<body>


<div class="ui two column centered grid top-25">
    <div class="column msg"> <?= error_handler($result->Status) ?></div>
    <?php if ($result->Status == 100) { ?>
        <div class="three column centered row">
            <div class="column black result-row text-center" style="font-weight: bold;">کد پیگیری</div>
            <div class="column green result-row text-center"><?= $result->RefID ?></div>
        </div>
        <div class="three column centered row" style="margin-top: 5px;">
            <div class="column black result-row text-center" style="font-weight: bold;">شناسه تراکنش</div>
            <div class="column green result-row latin text-center"><?=$req["iaaId"]?></div>
        </div>
    <?php } ?>
</div>

<div class="row three centered" style="text-align: center;position: relative;top: 35%;">
    <div class="ui container">
        <button class="ui button green iransans" type="button"><a style="color:white;" href="http://ballyhooawards.ir"> بازگشت به سایت</a></button>
    </div>
</div>
</body>
</html>
