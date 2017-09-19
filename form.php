<!DOCTYPE html>
<html>
<head>
    <title>
        Ballyhoo|Payment
    </title>
    <link rel="stylesheet" type="text/css" href="./assets/semantic.min.css">
<!--    <link rel="stylesheet" type="text/css" href="./assets/semantic.min.js">-->
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" type="text/css" href="assets/fonts/IRANSANS/iransans.css">
</head>
<body>

<?php
include './funcs.php';
$config = parse_ini_file('./config.ini');

$iaaId = $_GET['id'];
$txInfo = get_tx_info($iaaId);
$name = $txInfo->user->name . ' ' . $txInfo->user->lastName;
$desc = $txInfo->desc;
$amount = $config['amount'];
?>

<div class="ui two column centered grid main-container">
    <div class="column three centered" style="text-align: center;">
        <div class="ui container">
            <?php if ($txInfo) { ?>
                <div class="column centered padding-25 border-smooth form-title">
                    اطلاعات پرداخت
                </div>
                <form class="ui form payment-form border-smooth" method="post" action="request.php?id=<?=$iaaId?>">
                    <div class="field">
                        <label class="input-label"><i class="user icon"></i>نام و نام خانوادگی</label>
                        <input disabled=disabled class="iransans" type="text" name="name" value="<?= $name ?>"
                               placeholder="نام و نام خانوادگی">
                    </div>
                    <div class="field">
                        <label class="input-label"><i class="money icon"></i>مبلغ (ریال)</label>
                        <input disabled=disabled value="<?= $amount ?>" class="iransans" type="text" name="amount"
                               placeholder="مبلغ">
                    </div>
                    <div class="field">
                        <label class="input-label"><i class="align right icon"></i>توضیحات</label>
                        <input class="iransans" type="text" name="desc" value="<?= $desc ?>" placeholder="توضیحات">
                    </div>
                    <button class="ui button black iransans fluid submit-btn" type="submit">پرداخت</button>
                </form>
            <?php } else { ?>
                <div class="column centered msg">
                    تراکنش یافت نشد.
                </div>
            <?php } ?>
        </div>
    </div>
</div>


</body>
</html>
