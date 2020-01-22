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
 * Written by michael <michael@adl-crm.uk>, 05/02/19 15:14
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

require_once(BASE_URL . '/classes/database_class.php');
require_once(BASE_URL . '/includes/ADL_PDO_CON.php');

if (isset($ffanalytics) && $ffanalytics == '1') {
    require_once(BASE_URL . '/app/analyticstracking.php');
}

if (isset($fferror) && $fferror == 0) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

$EXECUTE = filter_input(INPUT_GET, 'EXECUTE', FILTER_SANITIZE_NUMBER_INT);
$Body = filter_input(INPUT_POST, 'Body', FILTER_SANITIZE_SPECIAL_CHARS);
$From = filter_input(INPUT_POST, 'From', FILTER_SANITIZE_SPECIAL_CHARS);

$CALLID = preg_replace('~^[0\D]++44|\D++~', '0', $From);


if (isset($EXECUTE)) {
    if ($EXECUTE == '1') {
        if (isset($Body)) {

            $database = new Database();

            $CALLID = preg_replace('~^[0\D]++44|\D++~', '0', $From);

            $database->query("SELECT client_id FROM client_details WHERE phone_number =:CALLID");
            $database->bind(':CALLID', $CALLID);
            $database->execute();
            $data2 = $database->single();

            if (isset($data2['client_id'])) {
                $CID = $data2['client_id'];
            }

            if ($database->rowCount() >= 1) {

                $NEW_MESSAGE = "$CALLID WhatsApp Reply: '$Body'";

                $INSERT = $pdo->prepare("INSERT INTO client_note set client_id=:CID, client_name='ADL Alert', sent_by='ADL', note_type='WhatsApp Reply', message=:CALLERID");
                $INSERT->bindParam(':CID', $CID, PDO::PARAM_INT);
                $INSERT->bindParam(':CALLERID', $NEW_MESSAGE, PDO::PARAM_STR);
                $INSERT->execute();

                $SMS_INSERT = $pdo->prepare("INSERT INTO sms_inbound set sms_inbound_client_id=:CID, sms_inbound_phone=:PHONE, sms_inbound_type='WhatsApp Reply', sms_inbound_msg=:MSG");
                $SMS_INSERT->bindParam(':CID', $CID, PDO::PARAM_INT);
                $SMS_INSERT->bindParam(':MSG', $NEW_MESSAGE, PDO::PARAM_STR);
                $SMS_INSERT->bindParam(':PHONE', $CALLID, PDO::PARAM_STR);
                $SMS_INSERT->execute();

            } else {

                $UNKNOWN_NUMBER = "UNKNOWN NUMBER | $NEW_MESSAGE";

                $SMS_INSERT = $pdo->prepare("INSERT INTO sms_inbound set sms_inbound_client_id=:CID, sms_inbound_phone=:PHONE, sms_inbound_type='WhatsApp Reply', sms_inbound_msg=:MSG");
                $SMS_INSERT->bindParam(':CID', $CID, PDO::PARAM_INT);
                $SMS_INSERT->bindParam(':MSG', $UNKNOWN_NUMBER, PDO::PARAM_STR);
                $SMS_INSERT->bindParam(':PHONE', $CALLID, PDO::PARAM_STR);
                $SMS_INSERT->execute();

            }

        }


    }
}