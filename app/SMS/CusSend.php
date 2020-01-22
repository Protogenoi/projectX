<?php
/**
 * ------------------------------------------------------------------------
 *                               ADL CRM
 * ------------------------------------------------------------------------
 *
 * Copyright © 2019 ADL CRM All rights reserved.
 *
 * Unauthorised copying of this file, via any medium is strictly prohibited.
 * Unauthorised distribution of this file, via any medium is strictly prohibited.
 * Unauthorised modification of this code is strictly prohibited.
 *
 * Proprietary and confidential
 *
 * Written by michael <michael@adl-crm.uk>, 18/02/19 10:15
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
 *  toastr - https://github.com/CodeSeven/toastr
 *  Twilio - https://github.com/twilio
 *  SendGrid - https://github.com/sendgrid
 */

require_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_SPECIAL_CHARS) . '/app/core/doc_root.php';

require_once(BASE_URL . '/classes/access_user/access_user_class.php');
$page_protect = new Access_user;
$page_protect->access_page(filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS), "", 3);
$hello_name = ($page_protect->user_full_name != "") ? $page_protect->user_full_name : $page_protect->user;

$USER_TRACKING = 0;

require_once(BASE_URL . '/includes/adl_features.php');
require_once(BASE_URL . '/includes/time.php');

if (isset($FORCE_LOGOUT) && $FORCE_LOGOUT == 1) {
    $page_protect->log_out();
}

require_once(BASE_URL . '/includes/user_tracking.php');
require_once(BASE_URL . '/includes/Access_Levels.php');

if (isset($COMPANY_ENTITY)) {
    if ($COMPANY_ENTITY == 'Bluestone Protect') {

        $SMS_URL = 'review.adlcrm.com';
        $SMS_PHONE = '+441792720348';

    } elseif ($COMPANY_ENTITY == 'Project X') {

        $SMS_URL = 'x.adl-crm.uk';
        $SMS_PHONE = '+441792720348';

    }
}

require_once(BASE_URL . '/includes/ADL_PDO_CON.php');

if (isset($ffanalytics) && $ffanalytics == '1') {
    require_once(BASE_URL . '/app/analyticstracking.php');
}

if (isset($fferror) && $fferror == 1) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

$EXECUTE = filter_input(INPUT_GET, 'EXECUTE', FILTER_SANITIZE_NUMBER_INT);

if (isset($EXECUTE) && $EXECUTE == 1) {

    $CID = filter_input(INPUT_POST, 'search', FILTER_SANITIZE_NUMBER_INT);

    $num = filter_input(INPUT_POST, 'phone_number', FILTER_SANITIZE_SPECIAL_CHARS);
    $CLIENT_NAME = filter_input(INPUT_POST, 'FullName', FILTER_SANITIZE_SPECIAL_CHARS);
    $SMS_MESSAGE = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS);

    $SMS_QRY = $pdo->prepare("SELECT twilio_account_sid, AES_DECRYPT(twilio_account_token, UNHEX(:key)) AS twilio_account_token FROM twilio_account");
    $SMS_QRY->bindParam(':key', $EN_KEY, PDO::PARAM_STR);
    $SMS_QRY->execute() or die(print_r($SMS_QRY->errorInfo(), true));
    $SMS_RESULT = $SMS_QRY->fetch(PDO::FETCH_ASSOC);

    $SID = $SMS_RESULT['twilio_account_sid'];
    $TOKEN = $SMS_RESULT['twilio_account_token'];

    $countryCode = "+44";
    $newNumber = preg_replace('/^0?/', '' . $countryCode, $num);

}

require_once(BASE_URL . '/resources/lib/vendor/autoload.php');

use Twilio\Rest\Client;

$client = new Client($SID, $TOKEN);

$client->messages->create(
    "$newNumber",
    array(
        'from' => $SMS_PHONE,
        'body' => "$SMS_MESSAGE",
        'statusCallback' => "https://$SMS_URL/app/SMS/Status.php?EXECUTE=1"
    )
);

if (isset($CID)) {

    $INSERT = $pdo->prepare("INSERT INTO client_note set client_id=:CID, client_name=:HOLD, sent_by=:SENT, note_type='Sent SMS', message=:MESSAGE");
    $INSERT->bindParam(':CID', $CID, PDO::PARAM_INT);
    $INSERT->bindParam(':SENT', $hello_name, PDO::PARAM_STR, 100);
    $INSERT->bindParam(':HOLD', $CLIENT_NAME, PDO::PARAM_STR, 500);
    $INSERT->bindParam(':MESSAGE', $SMS_MESSAGE, PDO::PARAM_STR, 2500);
    $INSERT->execute();

    $toastrTitle = 'SMS Sent!';
    $toastrMessage = "Message sent to $newNumber";
    $toastrResponse = 1;

    header('Location: ../Client.php?toastrResponse=' . $toastrResponse . '&toastrMessage=' . $toastrMessage . '&toastrTitle=' . $toastrTitle . '.&search=' . $CID);
    die;

}

header('Location: /../../../CRMmain.php?error=1');
die;
