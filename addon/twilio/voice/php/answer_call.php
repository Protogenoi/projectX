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
 * Written by michael <michael@adl-crm.uk>, 07/02/19 11:40
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
 *
 */

require_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_SPECIAL_CHARS) . '/app/core/doc_root.php';

if (isset($fferror) && $fferror == 0) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

$EXECUTE = filter_input(INPUT_GET, 'EXECUTE', FILTER_SANITIZE_NUMBER_INT);

if (isset($EXECUTE)) {
    if ($EXECUTE == '1') {

        require_once(BASE_URL . '/includes/ADL_PDO_CON.php');
        require_once(BASE_URL . '/classes/database_class.php');

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

require_once(BASE_URL . '/resources/lib/vendor/autoload.php');

//SHOULD BE TwiML but its looking to my /resources/lib/twilio-php-master/Twilio/TwiML.php' for it
use Twilio\Twiml as TwiML;

// Start our TwiML response
$response = new TwiML;

// Read a message aloud to the caller
$response->say(
    "Thank you for calling! Have a great day.",
    array(
        "voice" => "alice"
    )
);

$response->record();

$response->hangup();

echo $response;