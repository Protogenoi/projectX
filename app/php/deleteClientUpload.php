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

use ADL\uploads;

require_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_SPECIAL_CHARS) . '/app/core/doc_root.php';

require_once(BASE_URL . '/classes/access_user/access_user_class.php');
$page_protect = new Access_user;
$page_protect->access_page(filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS), "", 9);
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

if (isset($fferror) && $fferror == 1) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

getRealIpAddr();
$TRACKED_IP = getRealIpAddr();

if (!in_array($hello_name, $anyIPAccess, true)) { // ALLOW USER TO CONNECT FROM ANY IP

    if (!in_array($TRACKED_IP,
        $allowedIPAccess)) { //IF THE ABOVE IS FALSE ONLY ALLOW NORNAL USERS TO CONNECT FROM IPs IN ARRAY $allowedIPAccess
        $page_protect->log_out();
    }
}

require_once(BASE_URL . '/classes/database_class.php');
require_once(BASE_URL . '/class/login/login.php');
$CHECK_USER_LOGIN = new UserActions($hello_name, "NoToken");
$CHECK_USER_LOGIN->UpdateToken();
$CHECK_USER_LOGIN->CheckAccessLevel();

$USER_ACCESS_LEVEL = $CHECK_USER_LOGIN->CheckAccessLevel();

$ACCESS_LEVEL = $USER_ACCESS_LEVEL['ACCESS_LEVEL'];

if ($ACCESS_LEVEL < 9) {

    header('Location: index.php?AccessDenied&USER=' . $hello_name . '&COMPANY=' . $COMPANY_ENTITY);
    die;

}

$EXECUTE = filter_input(INPUT_GET, 'EXECUTE', FILTER_SANITIZE_NUMBER_INT);

if (isset($EXECUTE) && $EXECUTE == 1) {

    require_once(BASE_URL . '/includes/ADL_PDO_CON.php');
    require_once(BASE_URL . '/class/uploads.php');

    $CID = filter_input(INPUT_POST, 'CID', FILTER_SANITIZE_NUMBER_INT);
    $UID = filter_input(INPUT_POST, 'UID', FILTER_SANITIZE_NUMBER_INT);
    $fileName = filter_input(INPUT_POST, 'fileName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $deleteClientUpload = new uploads($pdo);
    $deleteClientUpload->setCID($CID);
    $deleteClientUpload->setUID($UID);
    $deleteClientUpload->setHello($hello_name);
    $deleteClientUpload->setFileName($fileName);
    $deleteUploadResponse = $deleteClientUpload->deleteClientUploadByID();

    if ($deleteUploadResponse == 'success') {

        $toastrTitle = 'File deleted!';
        $toastrMessage = $fileName;
        $toastrResponse = 1;

        header('Location: /../../../../app/Client.php?search=' . $CID . '&toastrResponse=' . $toastrResponse . '&toastrMessage=' . $toastrMessage . '&toastrTitle=' . $toastrTitle . '');
        die;

    } else {

        $toastrTitle = 'Error!';
        $toastrMessage = "File not deleted";
        $toastrResponse = 2;

        header('Location: /../../../../app/Client.php?search=' . $CID . '&toastrResponse=' . $toastrResponse . '&toastrMessage=' . $toastrMessage . '&toastrTitle=' . $toastrTitle . '');
        die;
    }

}

$toastrTitle = 'Error!';
$toastrMessage = "Error";
$toastrResponse = 2;

header('Location: /../../../../CRMmain.php?toastrResponse=' . $toastrResponse . '&toastrMessage=' . $toastrMessage . '&toastrTitle=' . $toastrTitle . '');
die;
