<?php
include './funcs.php';
$auth = '000000000000000000000000000053617358';
$q = "UPDATE `request` SET `status` = 1 WHERE `authority` = '".$auth."'";
test($q);
/**
 * Created by PhpStorm.
 * User: root
 * Date: 9/19/17
 * Time: 5:34 PM
 */