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
$page_protect->access_page(filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS), "", 3);
$hello_name = ($page_protect->user_full_name != "") ? $page_protect->user_full_name : $page_protect->user;

$USER_TRACKING = 0;

require_once(__DIR__ . '/../../../includes/adl_features.php');

require_once(__DIR__ . '/../../../includes/time.php');

if (isset($FORCE_LOGOUT) && $FORCE_LOGOUT == 1) {
    $page_protect->log_out();
}

require_once(__DIR__ . '/../../../includes/user_tracking.php');
require_once(__DIR__ . '/../../../includes/Access_Levels.php');

require_once(__DIR__ . '/../../../includes/ADL_PDO_CON.php');

if ($ffanalytics == '1') {
    require_once(__DIR__ . '/../../../app/analyticstracking.php');
}

if (isset($fferror)) {
    if ($fferror == '0') {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }
}

if (isset($fflife)) {
    if ($fflife == '1') {

        $cb = filter_input(INPUT_GET, 'cb', FILTER_SANITIZE_SPECIAL_CHARS);
        if (isset($cb)) {
            $callbackcompletedyn = filter_input(INPUT_GET, 'cb', FILTER_SANITIZE_SPECIAL_CHARS);
            $callbackcompletedid = filter_input(INPUT_GET, 'callbackid', FILTER_SANITIZE_NUMBER_INT);
            if ($callbackcompletedyn == 'y') {
                $query = $pdo->prepare("UPDATE scheduled_callbacks set complete='y' where id = :callbackidyes");
                $query->bindParam(':callbackidyes', $callbackcompletedid, PDO::PARAM_INT);
                $query->execute();

                header('Location: /app/calendar/calendar.php?callback=complete&callbackid' . $callbackcompletedid);
                die;

            }

            if ($callbackcompletedyn == 'n') {
                $query = $pdo->prepare("UPDATE scheduled_callbacks set complete='n' where id = :callbackidno");
                $query->bindParam(':callbackidno', $callbackcompletedid, PDO::PARAM_INT);
                $query->execute();

                header('Location: /app/calendar/calendar.php?callback=incomplete');
                die;

            }

        }

    }
}

header('Location: /../../../../CRMmain.php');
die;
