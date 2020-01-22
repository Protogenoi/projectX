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

require_once(__DIR__ . '/../../../includes/ADL_PDO_CON.php');

if (isset($fferror)) {
    if ($fferror == '0') {

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

    }

}

$emailsignature = filter_input(INPUT_GET, 'emailsignature', FILTER_SANITIZE_SPECIAL_CHARS);

if (isset($emailsignature)) {

    $emailid = filter_input(INPUT_POST, 'emailid', FILTER_SANITIZE_SPECIAL_CHARS);
    $sig = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS);

    $dupeck = $pdo->prepare("Select email_id from email_signatures WHERE email_id=:email");
    $dupeck->bindParam(':email', $emailid, PDO::PARAM_INT);
    $dupeck->execute();
    $row = $dupeck->fetch(PDO::FETCH_ASSOC);
    if ($count = $dupeck->rowCount() >= 1) {
        $dupechecked = $row['email_id'];

        $query = $pdo->prepare("UPDATE email_signatures set sig=:sighold, added_by=:uphold where email_id=:emailidhold");
        $query->bindParam(':emailidhold', $emailid, PDO::PARAM_INT);
        $query->bindParam(':sighold', $sig, PDO::PARAM_STR, 5000);
        $query->bindParam(':uphold', $hello_name, PDO::PARAM_STR, 500);
        $query->execute() or die(print_r($query->errorInfo(), true));


        header('Location: ../Admindash.php?Emails=y&signature=updated');
        die;

    }

    $query = $pdo->prepare("INSERT INTO email_signatures set email_id=:emailidhold, sig=:sighold, added_by=:uphold");
    $query->bindParam(':emailidhold', $emailid, PDO::PARAM_INT);
    $query->bindParam(':sighold', $sig, PDO::PARAM_STR, 5000);
    $query->bindParam(':uphold', $hello_name, PDO::PARAM_STR, 500);
    $query->execute() or die(print_r($query->errorInfo(), true));

    header('Location: ../Admindash.php?Emails=y&signature=y');
    die;

} else {

    header('Location: ../Admindash.php?Emails=y&signature=failed');
    die;

}
