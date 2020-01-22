<?php
/*
 * ------------------------------------------------------------------------
 *                               ADL CRM
 * ------------------------------------------------------------------------
 *
 * Copyright © 2018 ADL CRM All rights reserved.
 *
 * Unauthorised copying of this file, via any medium is strictly prohibited.
 * Unauthorised distribution of this file, via any medium is strictly prohibited.
 * Unauthorised modification of this code is strictly prohibited.
 *
 * Proprietary and confidential
 *
 * Written by Michael Owen <michael@adl-crm.uk>, 2018
 *
 * ADL CRM makes use of the following third party open sourced software/tools:
 *  DataTables - https://github.com/DataTables/DataTables
 *  EasyAutocomplete - https://github.com/pawelczak/EasyAutocomplete
 *  PHPMailer - https://github.com/PHPMailer/PHPMailer
 *  ClockPicker - https://github.com/weareoutman/clockpicker
 *  fpdf17 - http://www.fpdf.org
 *  summernote - https://github.com/summernote/summernote
 *  Font Awesome - https://github.com/FortAwesome/Font-Awesome
 *  Bootstrap - https://github.com/twbs/bootstrap
 *  jQuery UI - https://github.com/jquery/jquery-ui
 *  Google Dev Tools - https://developers.google.com
 *  Twitter API - https://developer.twitter.com
 *  Webshim - https://github.com/aFarkas/webshim/releases/latest
 *
*/

include(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT',
        FILTER_SANITIZE_SPECIAL_CHARS) . "/classes/access_user/access_user_class.php");
$page_protect = new Access_user;
$page_protect->access_page(filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS), "", 10);
$hello_name = ($page_protect->user_full_name != "") ? $page_protect->user_full_name : $page_protect->user;

$USER_TRACKING = 0;

require_once(__DIR__ . '/../../../includes/user_tracking.php');
require_once(__DIR__ . '/../../../includes/adl_features.php');
require_once(__DIR__ . '/../../../includes/Access_Levels.php');
require_once(__DIR__ . '/../../../includes/adlfunctions.php');

require_once(__DIR__ . '/../../../includes/ADL_PDO_CON.php');

if (isset($fferror)) {
    if ($fferror == '0') {

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

    }

}

$addsms = filter_input(INPUT_GET, 'addsms', FILTER_SANITIZE_SPECIAL_CHARS);

if (isset($addsms)) {

    $provider = filter_input(INPUT_GET, 'provider', FILTER_SANITIZE_SPECIAL_CHARS);
    $smsusername = filter_input(INPUT_POST, 'smsusername', FILTER_SANITIZE_SPECIAL_CHARS);
    $smspassword = filter_input(INPUT_POST, 'smspassword', FILTER_SANITIZE_SPECIAL_CHARS);

    if (isset($provider)) {

        if ($provider == 'Twilio') {
            $SID = filter_input(INPUT_POST, 'SID', FILTER_SANITIZE_SPECIAL_CHARS);
            $TOKEN = filter_input(INPUT_POST, 'TOKEN', FILTER_SANITIZE_SPECIAL_CHARS);

            $query = $pdo->prepare("INSERT INTO twilio_account set twilio_account_updated_by=:hello, twilio_account_sid=:SID, twilio_account_token=AES_ENCRYPT(:TOKEN, UNHEX(:key))");
            $query->bindParam(':key', $EN_KEY, PDO::PARAM_STR, 500);
            $query->bindParam(':SID', $SID, PDO::PARAM_STR, 100);
            $query->bindParam(':TOKEN', $TOKEN, PDO::PARAM_STR, 500);
            $query->bindParam(':hello', $hello_name, PDO::PARAM_STR, 100);
            $query->execute() or die(print_r($query->errorInfo(), true));

            if (isset($fferror)) {
                if ($fferror == '0') {
                    header('Location: ../Admindash.php?smsaccount=y&SMS=y');
                    die;

                }

            }

        }

    }

}

$newsmsmessagevar = filter_input(INPUT_GET, 'newsmsmessage', FILTER_SANITIZE_SPECIAL_CHARS);

if (isset($newsmsmessagevar)) {

    $smsmessagevar = filter_input(INPUT_POST, 'smsmessage', FILTER_SANITIZE_SPECIAL_CHARS);
    $smstitle = filter_input(INPUT_POST, 'smstitle', FILTER_SANITIZE_SPECIAL_CHARS);
    $insurer = filter_input(INPUT_POST, 'insurer', FILTER_SANITIZE_SPECIAL_CHARS);
    $COMPANY = filter_input(INPUT_POST, 'COMPANY', FILTER_SANITIZE_SPECIAL_CHARS);

    $query = $pdo->prepare("INSERT INTO sms_templates set title=:title, insurer=:insurer, message=:message, company=:COMPANY");
    $query->bindParam(':insurer', $insurer, PDO::PARAM_STR, 500);
    $query->bindParam(':title', $smstitle, PDO::PARAM_STR, 500);
    $query->bindParam(':COMPANY', $COMPANY, PDO::PARAM_STR, 500);
    $query->bindParam(':message', $smsmessagevar, PDO::PARAM_STR, 500);

    $query->execute() or die(print_r($query->errorInfo(), true));
    if (isset($fferror)) {
        if ($fferror == '0') {
            header('Location: ../Admindash.php?smsaccount=messadded&SMS=y');
            die;
        }
    }
} else {
    if (isset($fferror)) {
        if ($fferror == '0') {
            header('Location: ../Admindash.php?smsaccount=failed&SMS=y');
            die;

        }

    }

}
