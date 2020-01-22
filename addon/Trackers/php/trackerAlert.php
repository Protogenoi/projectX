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
 * Written by michael <michael@adl-crm.uk>, 2019-01-24 12:26
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

require_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_SPECIAL_CHARS) . '/app/core/doc_root.php';

require_once(BASE_URL . '/classes/access_user/access_user_class.php');
$page_protect = new Access_user;
$page_protect->access_page(filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS), "", 2);
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

if (isset($ffanalytics) && $ffanalytics == 1) {
    require_once(BASE_URL . '/app/analyticstracking.php');
}

if (isset($fferror)) {
    if ($fferror == '1') {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }
}

require_once(BASE_URL . '/classes/database_class.php');
require_once(BASE_URL . '/class/login/login.php');
$CHECK_USER_LOGIN = new UserActions($hello_name, "NoToken");
$CHECK_USER_LOGIN->CheckAccessLevel();

$USER_ACCESS_LEVEL = $CHECK_USER_LOGIN->CheckAccessLevel();

$ACCESS_LEVEL = $USER_ACCESS_LEVEL['ACCESS_LEVEL'];

if ($ACCESS_LEVEL < 2) {

    $page_protect->log_out();

}

$EXECUTE = filter_input(INPUT_GET, 'EXECUTE', FILTER_SANITIZE_SPECIAL_CHARS);
$CID = filter_input(INPUT_GET, 'CID', FILTER_SANITIZE_SPECIAL_CHARS);
$phone = filter_input(INPUT_GET, 'phone', FILTER_SANITIZE_SPECIAL_CHARS);
$status = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_SPECIAL_CHARS);
$timeDeadline = filter_input(INPUT_GET, 'timeDeadline', FILTER_SANITIZE_SPECIAL_CHARS);
$dayDeadline = filter_input(INPUT_GET, 'dayDeadline', FILTER_SANITIZE_SPECIAL_CHARS);

if (isset($EXECUTE)) {

    if ($EXECUTE == 1) {


        $NOTE_OPTION = "Closer Tracker alert dismissed";

    }

    if ($EXECUTE == 2) {

        $NOTE_OPTION = "Closer Tracker alert complete";

    }

    $message = "$status - Deadline: $dayDeadline at $timeDeadline";
    $CLIENT_NAME = 'ADL Alert';

    $INSERT = $pdo->prepare("INSERT INTO potentialClientNote set client_id=:CID, client_name=:HOLDER, sent_by=:SENT, note_type=:REF, message=:MESSAGE ");
    $INSERT->bindParam(':CID', $CID, PDO::PARAM_INT);
    $INSERT->bindParam(':SENT', $hello_name, PDO::PARAM_STR, 100);
    $INSERT->bindParam(':HOLDER', $CLIENT_NAME, PDO::PARAM_STR, 500);
    $INSERT->bindParam(':REF', $NOTE_OPTION, PDO::PARAM_STR, 2500);
    $INSERT->bindParam(':MESSAGE', $message, PDO::PARAM_STR, 2500);
    $INSERT->execute();

    $update = $pdo->prepare("UPDATE closer_trackers SET timeDeadline=NULL, dayDeadline=NULL WHERE phone=:phone");
    $update->bindParam(':phone', $phone, PDO::PARAM_INT);
    $update->execute();

    header('Location: /../../../../addon/Trackers/client.php?search=' . $CID . '&trackerPadResponse=3&padStatus=' . $status . '&trackerPadMessage=' . $NOTE_OPTION);
    die;


}

header('Location: /../../../../addon/Trackers/client.php?search=' . $CID . '&trackerPadResponse=2');
die;
