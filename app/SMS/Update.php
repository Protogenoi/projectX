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

require_once(BASE_URL . '/includes/user_tracking.php');

require_once(BASE_URL . '/includes/time.php');

if (isset($FORCE_LOGOUT) && $FORCE_LOGOUT == 1) {
    $page_protect->log_out();
}

require_once(BASE_URL . '/includes/adl_features.php');
require_once(BASE_URL . '/includes/Access_Levels.php');
require_once(BASE_URL . '/includes/adlfunctions.php');

if (isset($ffanalytics) && $ffanalytics == '1') {
    require_once(BASE_URL . '/app/analyticstracking.php');
}

if (isset($fferror) && $fferror == 1) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

if (!in_array($hello_name, $Level_3_Access, true)) {

    header('Location: /../../../CRMmain.php');
    die;
}

if (isset($ffsms) && $ffsms == 0) {

    header('Location: /../../../CRMmain.php');
    die;
}

$EXECUTE = filter_input(INPUT_GET, 'EXECUTE', FILTER_SANITIZE_SPECIAL_CHARS);
$NID = filter_input(INPUT_GET, 'NID', FILTER_SANITIZE_SPECIAL_CHARS);
$PHONE = filter_input(INPUT_GET, 'PHONE', FILTER_SANITIZE_SPECIAL_CHARS);
$CID = filter_input(INPUT_GET, 'CID', FILTER_SANITIZE_SPECIAL_CHARS);
$TYPE = filter_input(INPUT_GET, 'TYPE', FILTER_SANITIZE_SPECIAL_CHARS);

if (isset($EXECUTE)) {
    if ($EXECUTE == '1') {
        if (isset($NID)) {

            if ($TYPE == 'WhatsApp Reply' || $TYPE == 'WhatsApp Sent') {
                $noteType = 'WhatsApp Update';
            } else {
                $noteType = 'SMS Update';
            }

            $MESSAGE = "Viewed $TYPE for $PHONE";

            $INSERT = $pdo->prepare("INSERT INTO client_note set client_id=:CID, client_name=:PHONE, sent_by=:hello, note_type=:TYPE, message=:MSG");
            $INSERT->bindParam(':CID', $CID, PDO::PARAM_INT);
            $INSERT->bindParam(':hello', $hello_name, PDO::PARAM_STR);
            $INSERT->bindParam(':MSG', $MESSAGE, PDO::PARAM_STR);
            $INSERT->bindParam(':PHONE', $PHONE, PDO::PARAM_STR);
            $INSERT->bindParam(':TYPE', $noteType, PDO::PARAM_STR);
            $INSERT->execute();

            $SMS_DELETE = $pdo->prepare("DELETE FROM sms_inbound WHERE sms_inbound_id=:NID LIMIT 1");
            $SMS_DELETE->bindParam(':NID', $NID, PDO::PARAM_INT);
            $SMS_DELETE->execute();

            header('Location: ../Client.php?search=' . $CID);
            die;

        }
    }
    if ($EXECUTE == '2') {

        if (isset($TYPE)) {
            if ($TYPE == 'SMS Failed') {
                $SMS_REDIRECT = 'Failed';

            } elseif ($TYPE == 'SMS Delivered') {
                $SMS_REDIRECT = 'Sent';
            }
        }

        $i = 0;

        $SELECT_SMS = $pdo->prepare("SELECT sms_inbound_id, sms_inbound_client_id, sms_inbound_phone, sms_inbound_msg, sms_inbound_type FROM sms_inbound WHERE sms_inbound_type = :TYPE");
        $SELECT_SMS->bindParam(':TYPE', $TYPE, PDO::PARAM_STR);
        $SELECT_SMS->execute();
        if ($SELECT_SMS->rowCount() >= 1) {
            while ($result = $SELECT_SMS->fetch(PDO::FETCH_ASSOC)) {

                $SMS_ID = $result['sms_inbound_id'];
                $CID = $result['sms_inbound_client_id'];
                $PHONE = $result['sms_inbound_phone'];
                $MESSAGE = $result['sms_inbound_msg'];
                $TYPE = $result['sms_inbound_type'];

                $CHK_MATCH = $pdo->prepare("SELECT client_id FROM client_details where client_id =:CID AND phone_number=:PHONE");
                $CHK_MATCH->bindParam(':CID', $CID, PDO::PARAM_INT);
                $CHK_MATCH->bindParam(':PHONE', $PHONE, PDO::PARAM_STR);
                $CHK_MATCH->execute();
                $row = $CHK_MATCH->fetch(PDO::FETCH_ASSOC);
                if ($CHK_MATCH->rowCount() >= 1) {

                    $i++;

                    $MESSAGE = "Viewed $TYPE for $PHONE";

                    $INSERT = $pdo->prepare("INSERT INTO client_note set client_id=:CID, client_name=:PHONE, sent_by=:hello, note_type='SMS Update', message=:MSG");
                    $INSERT->bindParam(':CID', $CID, PDO::PARAM_INT);
                    $INSERT->bindParam(':hello', $hello_name, PDO::PARAM_STR);
                    $INSERT->bindParam(':MSG', $MESSAGE, PDO::PARAM_STR);
                    $INSERT->bindParam(':PHONE', $PHONE, PDO::PARAM_STR);
                    $INSERT->execute();

                    $SMS_DELETE = $pdo->prepare("DELETE FROM sms_inbound WHERE sms_inbound_id=:NID LIMIT 1");
                    $SMS_DELETE->bindParam(':NID', $SMS_ID, PDO::PARAM_INT);
                    $SMS_DELETE->execute();

                }

            }
        }

        header('Location: Report.php?SEARCH_BY=' . $SMS_REDIRECT . '&UPDATED=' . $i);
        die;

    }

}
