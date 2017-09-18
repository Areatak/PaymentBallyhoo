<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 9/17/17
 * Time: 3:44 PM
 */

function error_handler($status)
{
    $dict = array();
    $dict[100] = "پرداخت با موفقیت انجام شده است.";
    $dict[101] = "پرداخت با موفقیت انجام و قبلا تایید شده است.";
    $dict[33] = "‫مبلغ پرداخت ناصحیح است.";
    $dict[22] = "تراکنش ناموفق";
    $dict[1000] = "تراکنش لغو شد.";
    if ($dict[$status]) {
        return $dict[$status];
    } else {
        return $status;
    }
}