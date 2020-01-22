<?php
/**
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
 *  toastr - https://github.com/CodeSeven/toastr
 */

require_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_SPECIAL_CHARS) . '/app/core/doc_root.php';

require_once(BASE_URL . '/classes/access_user/access_user_class.php');
$page_protect = new Access_user;
$page_protect->access_page(filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS), "", 9);
$hello_name = ($page_protect->user_full_name != "") ? $page_protect->user_full_name : $page_protect->user;

$USER_TRACKING = 0;

require_once(BASE_URL . '/includes/user_tracking.php');
require_once(BASE_URL . '/includes/adl_features.php');
require_once(BASE_URL . '/includes/Access_Levels.php');
require_once(BASE_URL . '/includes/adlfunctions.php');

require_once(BASE_URL . '/includes/ADL_PDO_CON.php');

if (isset($ffanalytics) && $ffanalytics == '1') {
    require_once(BASE_URL . '/app/analyticstracking.php');
}

if (isset($fferror) && $fferror == '1') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

$DeleteLifePolicy = filter_input(INPUT_GET, 'DeleteLifePolicy', FILTER_SANITIZE_SPECIAL_CHARS);
$home = filter_input(INPUT_GET, 'home', FILTER_SANITIZE_SPECIAL_CHARS);

if (isset($home)) {
    $CID = filter_input(INPUT_GET, 'CID', FILTER_SANITIZE_NUMBER_INT);
    $PID = filter_input(INPUT_GET, 'PID', FILTER_SANITIZE_NUMBER_INT);

    $delete = $pdo->prepare("DELETE FROM home_policy WHERE id=:PID AND client_id=:CID LIMIT 1");
    $delete->bindParam(':PID', $PID, PDO::PARAM_INT);
    $delete->bindParam(':CID', $CID, PDO::PARAM_INT);
    $delete->execute();

    if (isset($fferror)) {
        if ($fferror == '0') {
            header('Location: /../../../../addon/Home/ViewClient.php?deletedpolicy=y&CID=' . $CID);
            die;

        }

    }

}

if (isset($DeleteLifePolicy) && $DeleteLifePolicy == '1') {

    $policyID = filter_input(INPUT_POST, 'deletepolicyID', FILTER_SANITIZE_NUMBER_INT);
    $CID = filter_input(INPUT_POST, 'client_id', FILTER_SANITIZE_NUMBER_INT);

    $delete = $pdo->prepare("DELETE FROM client_policy WHERE id=:id limit 1 ");
    $delete->bindParam(':id', $policyID, PDO::PARAM_INT);
    $delete->execute();

    $subject = filter_input(INPUT_POST, 'policy_number', FILTER_SANITIZE_SPECIAL_CHARS);
    $ref = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);

    $notes = "Policy $subject ($policyID)";
    $noteTypeData = "Policy Deleted";

    $query = $pdo->prepare("INSERT INTO client_note set client_id=:clientidholder, client_name=:recipientholder, sent_by=:sentbyholder, note_type=:noteholder, message=:messageholder ");
    $query->bindParam(':clientidholder', $CID, PDO::PARAM_INT);
    $query->bindParam(':sentbyholder', $hello_name, PDO::PARAM_STR, 100);
    $query->bindParam(':recipientholder', $ref, PDO::PARAM_STR, 500);
    $query->bindParam(':noteholder', $noteTypeData, PDO::PARAM_STR, 255);
    $query->bindParam(':messageholder', $notes, PDO::PARAM_STR, 2500);
    $query->execute();

    $toastrTitle = 'Policy Deleted!';
    $toastrMessage = $notes;
    $toastrResponse = 1;

    header('Location: /../../../../app/Client.php?search=' . $CID . '&toastrResponse=' . $toastrResponse . '&toastrMessage=' . $toastrMessage . '&toastrTitle=' . $toastrTitle . '');
    die;

}

header('Location: /../../../../CRMmain.php?error=1');
die;
