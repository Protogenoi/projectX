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
 * Written by michael <michael@adl-crm.uk>, 05/02/19 09:56
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
 */

use SendGrid\Mail\Mail;

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

if (isset($ffanalytics) && $ffanalytics == 1) {
    require_once(BASE_URL . '/app/analyticstracking.php');
}

if (isset($fferror) && $fferror == 0) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

$EXECUTE = filter_input(INPUT_GET, 'EXECUTE', FILTER_SANITIZE_NUMBER_INT);

if (isset($EXECUTE) && $EXECUTE == 1) {

    require_once(BASE_URL . '/includes/ADL_PDO_CON.php');
    require_once(BASE_URL . '/class/supportTickets.php');
    require_once(BASE_URL . '/includes/config.php');

    $site = filter_input(INPUT_POST, 'site', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $user = filter_input(INPUT_POST, 'user', FILTER_SANITIZE_SPECIAL_CHARS);
    $pass = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_SPECIAL_CHARS);
    $company = filter_input(INPUT_POST, 'company', FILTER_SANITIZE_STRING);
    $ipAddress = filter_input(INPUT_POST, 'ipAddress', FILTER_SANITIZE_SPECIAL_CHARS);
    $info = filter_input(INPUT_POST, 'info', FILTER_SANITIZE_SPECIAL_CHARS);
    $ticketID = filter_input(INPUT_GET, 'ticketID', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

    $addCredentials = new ADL\supportTickets($pdo);
    $addCredentials->setSite($site);
    $addCredentials->setUser($user);
    $addCredentials->setPass($pass);
    $addCredentials->setCompany($company);
    $addCredentials->setIpAddress($ipAddress);
    $addCredentials->setContent($info);
    $addCredentials->setEncryptionKey($EN_KEY);
    $addCredentials->setId($ticketID);
    $addCredentials->setEmail($email);
    $result = $addCredentials->createCredentials();

    if ($result == 'success') {

        $toastrTitle = 'Success!';
        $toastrMessage = 'Credentials saved and encrypted!';
        $toastrResponse = 1;

        header('Location: ../main.php?toastrResponse=' . $toastrResponse . '&toastrMessage=' . $toastrMessage . '&toastrTitle=' . $toastrTitle);
        die;

    }

}

header('Location: /../../../CRMmain.php?error=1');
die;
