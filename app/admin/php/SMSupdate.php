<?php
/*
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
 *
*/

include(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT',
        FILTER_SANITIZE_SPECIAL_CHARS) . "/classes/access_user/access_user_class.php");
$page_protect = new Access_user;
$page_protect->access_page(filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS), "", 10);
$hello_name = ($page_protect->user_full_name != "") ? $page_protect->user_full_name : $page_protect->user;

$USER_TRACKING = 0;

require_once(__DIR__ . '/../../../includes/user_tracking.php');
require_once(__DIR__ . '/../../../includes/adl_features.php');
require_once(__DIR__ . '/../../../includes/Access_Levels.php');
require_once(__DIR__ . '/../../../includes/adlfunctions.php');

require_once(__DIR__ . '/../../../includes/ADL_PDO_CON.php');

if (isset($fferror)) {
    if ($fferror == '1') {

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

    }

}

$updatesms = filter_input(INPUT_GET, 'updatesms', FILTER_SANITIZE_SPECIAL_CHARS);

if ($updatesms == 'y') {

    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS);
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
    $insurer = filter_input(INPUT_POST, 'insurer', FILTER_SANITIZE_SPECIAL_CHARS);
    $COMPANY = filter_input(INPUT_POST, 'company', FILTER_SANITIZE_SPECIAL_CHARS);
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

    $query = $pdo->prepare("UPDATE sms_templates SET title=:titleholder, message=:messageholder, insurer=:insurer, company=:COMPANY WHERE id =:idholder ");
    $query->bindParam(':idholder', $id, PDO::PARAM_INT);
    $query->bindParam(':messageholder', $message, PDO::PARAM_STR, 2500);
    $query->bindParam(':titleholder', $title, PDO::PARAM_STR, 2500);
    $query->bindParam(':COMPANY', $COMPANY, PDO::PARAM_STR, 2500);
    $query->bindParam(':insurer', $insurer, PDO::PARAM_STR, 100);
    $query->execute() or die(print_r($query->errorInfo(), true));
    if (isset($fferror)) {
        if ($fferror == '0') {

            header('Location: ../Admindash.php?SMS=y&SMSupdated=y');
            die;
        }
    }
} else {
    if (isset($fferror)) {
        if ($fferror == '0') {
            header('Location: ../Admindash.php?SMS=y&SMSupdated=fail');
            die;
        }
    }
}
