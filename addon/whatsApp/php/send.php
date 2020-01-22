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
 * Written by michael <michael@adl-crm.uk>, 05/02/19 14:54
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
 *
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

require_once(BASE_URL . '/includes/ADL_PDO_CON.php');
require_once(BASE_URL . '/classes/database_class.php');

if (isset($ffanalytics) && $ffanalytics == '1') {
    require_once(BASE_URL . '/app/analyticstracking.php');
}

if (isset($fferror) && $fferror == 0) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

$EXECUTE = filter_input(INPUT_GET, 'EXECUTE', FILTER_SANITIZE_NUMBER_INT);
$CID = filter_input(INPUT_GET, 'CID', FILTER_SANITIZE_NUMBER_INT);

$whatsAppMessage = filter_input(INPUT_POST, 'whatsAppMessage', FILTER_SANITIZE_SPECIAL_CHARS);

if (isset($COMPANY_ENTITY)) {
    if ($COMPANY_ENTITY == 'Project X') {

        $SMS_URL = 'x.adl-crm.uk';
        $whatsAppNumber = '14155238886';

    }
}

if (isset($EXECUTE)) {
    if ($EXECUTE == '1') {

        $query = $pdo->prepare("SELECT CONCAT(first_name, ' ', last_name) AS NAME, phone_number FROM client_details WHERE client_id=:CID");
        $query->bindParam(':CID', $CID, PDO::PARAM_INT);
        $query->execute() or die(print_r($INSERT->errorInfo(), true));
        $result = $query->fetch(PDO::FETCH_ASSOC);

        $phoneNumber = $result['phone_number'];
        $name = $result['NAME'];

        $SMS_QRY = $pdo->prepare("SELECT twilio_account_sid, AES_DECRYPT(twilio_account_token, UNHEX(:key)) AS twilio_account_token FROM twilio_account");
        $SMS_QRY->bindParam(':key', $EN_KEY, PDO::PARAM_STR, 500);
        $SMS_QRY->execute() or die(print_r($INSERT->errorInfo(), true));
        $SMS_RESULT = $SMS_QRY->fetch(PDO::FETCH_ASSOC);

        $SID = $SMS_RESULT['twilio_account_sid'];
        $TOKEN = $SMS_RESULT['twilio_account_token'];

        $countryCode = "+44";

        $newNumber = preg_replace('/^0?/', '' . $countryCode, $phoneNumber);

    }
}

require_once(BASE_URL . '/resources/lib/twilio-php-master/Twilio/autoload.php');

use Twilio\Rest\Client;

$sid = $SID;
$token = $TOKEN;
$twilio = new Client($sid, $token);

$message = $twilio->messages
    ->create("whatsapp:$newNumber",
        array(
            "body" => "$whatsAppMessage",
            "from" => "whatsapp:+$whatsAppNumber",
            'StatusCallbackUrl' => "https://renew-life.adl-crm/addon/whatsApp/php/callback.php?EXECUTE=1"
        )
    );

print($message->sid);

if (isset($CID)) {

    $NOTE_OPTION = "WhatsApp Sent";

    $INSERT = $pdo->prepare("INSERT INTO client_note set client_id=:CID, client_name=:HOLDER, sent_by=:SENT, note_type=:REF, message=:MESSAGE ");
    $INSERT->bindParam(':CID', $CID, PDO::PARAM_INT);
    $INSERT->bindParam(':SENT', $hello_name, PDO::PARAM_STR, 100);
    $INSERT->bindParam(':HOLDER', $name, PDO::PARAM_STR, 500);
    $INSERT->bindParam(':REF', $NOTE_OPTION, PDO::PARAM_STR, 2500);
    $INSERT->bindParam(':MESSAGE', $whatsAppMessage, PDO::PARAM_STR, 2500);
    $INSERT->execute();

    $toastrTitle = 'WhatsApp Sent!';
    $toastrMessage = "Message sent to $newNumber";
    $toastrResponse = 1;

    header('Location: /../../../../../app/Client.php?toastrResponse=' . $toastrResponse . '&toastrMessage=' . $toastrMessage . '&toastrTitle=' . $toastrTitle . '.&search=' . $CID);
    die;

}
header('Location: /../../../../../CRMmain.php?error=1');
die;
