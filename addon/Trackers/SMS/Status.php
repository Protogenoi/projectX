<?php
/** @noinspection PhpIncludeInspection */

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
 *  Composer - https://getcomposer.org/doc/
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
 *  Ideal Postcodes - https://ideal-postcodes.co.uk/documentation
 *  Chart.js - https://github.com/chartjs/Chart.js
 */

use ADL\clientNote;

require_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_SPECIAL_CHARS) . '/app/core/doc_root.php';

require_once(BASE_URL . '/includes/adl_features.php');

if (isset($fferror) && $fferror == 1) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

$EXECUTE = filter_input(INPUT_GET, 'EXECUTE', FILTER_SANITIZE_SPECIAL_CHARS);
$MessageStatus = filter_input(INPUT_POST, 'MessageStatus', FILTER_SANITIZE_SPECIAL_CHARS);
$TO = filter_input(INPUT_POST, 'To', FILTER_SANITIZE_SPECIAL_CHARS);

if (isset($EXECUTE) && $EXECUTE == 1) {
    require_once(BASE_URL . '/includes/ADL_PDO_CON.php');
    require_once(BASE_URL . '/classes/database_class.php');
    require_once(BASE_URL . '/class/clientNote.php');
    if (isset($MessageStatus)) {

        if ($MessageStatus == 'delivered') {

            $database = new Database();

            $CALLID = preg_replace('~^[0\D]++44|\D++~', '0', $TO);

            $database->query("SELECT client_id FROM potential_clients WHERE phoneNumber =:CALLID");
            $database->bind(':CALLID', $CALLID);
            $database->execute();
            $data2 = $database->single();

            if (isset($data2['client_id'])) {
                $CID = $data2['client_id'];
            }

            if ($database->rowCount() >= 1) {

                $clientNoteMessage = "SMS has been successfully delivered to $CALLID";
                $clientNoteNoteType = "SMS Delivered";
                $clientNoteAddedBy = 'ADL';
                $clientNoteReference = 'ADL Alert';

                $addTimelineNotes = new ADL\clientNote($CID, $pdo);
                $result = $addTimelineNotes->addPotentialClientNote($clientNoteMessage,
                    $clientNoteNoteType,
                    $clientNoteAddedBy,
                    $clientNoteReference);


                $SMS_INSERT = $pdo->prepare("INSERT INTO potential_sms_inbound set sms_inbound_client_id=:CID, sms_inbound_phone=:PHONE, sms_inbound_type='SMS Delivered', sms_inbound_msg=:MSG");
                $SMS_INSERT->bindParam(':CID', $CID, PDO::PARAM_INT);
                $SMS_INSERT->bindParam(':MSG', $clientNoteMessage, PDO::PARAM_STR);
                $SMS_INSERT->bindParam(':PHONE', $CALLID, PDO::PARAM_STR);
                $SMS_INSERT->execute();

            }

        }

        if ($MessageStatus == 'undelivered') {

            $database = new Database();

            $CALLID = preg_replace('~^[0\D]++44|\D++~', '0', $TO);

            $database->query("SELECT client_id FROM client_details WHERE phone_number =:CALLID");
            $database->bind(':CALLID', $CALLID);
            $database->execute();
            $data2 = $database->single();

            if (isset($data2['client_id'])) {
                $CID = $data2['client_id'];
            }

            if ($database->rowCount() >= 1) {

                $clientNoteMessage = "SMS has failed to be delivered to $CALLID";
                $clientNoteNoteType = "SMS Failed";
                $clientNoteAddedBy = 'ADL';
                $clientNoteReference = 'ADL Alert';

                $addTimelineNotes = new ADL\clientNote($CID, $pdo);
                $result = $addTimelineNotes->addPotentialClientNote($clientNoteMessage,
                    $clientNoteNoteType,
                    $clientNoteAddedBy,
                    $clientNoteReference);

                $SMS_INSERT = $pdo->prepare("INSERT INTO potential_sms_inbound set sms_inbound_client_id=:CID, sms_inbound_phone=:PHONE, sms_inbound_type='SMS Failed', sms_inbound_msg=:MSG");
                $SMS_INSERT->bindParam(':CID', $CID, PDO::PARAM_INT);
                $SMS_INSERT->bindParam(':MSG', $clientNoteMessage, PDO::PARAM_STR);
                $SMS_INSERT->bindParam(':PHONE', $CALLID, PDO::PARAM_STR);
                $SMS_INSERT->execute();

            }

        }

    }

}
