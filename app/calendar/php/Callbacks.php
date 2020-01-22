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

require_once(__DIR__ . '/../../../classes/access_user/access_user_class.php');
$page_protect = new Access_user;
$page_protect->access_page(filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS), "", 3);
$hello_name = ($page_protect->user_full_name != "") ? $page_protect->user_full_name : $page_protect->user;

$USER_TRACKING = 0;

require_once(__DIR__ . '/../../../includes/user_tracking.php');

require_once(__DIR__ . '/../../../includes/time.php');

if (isset($FORCE_LOGOUT) && $FORCE_LOGOUT == 1) {
    $page_protect->log_out();
}

require_once(__DIR__ . '/../../../includes/adl_features.php');
require_once(__DIR__ . '/../../../includes/Access_Levels.php');

if ($ffanalytics == '1') {
    require_once(__DIR__ . '/../../../app/analyticstracking.php');
}

if (isset($fferror)) {
    if ($fferror == '1') {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }
}

require_once(__DIR__ . '/../../../classes/database_class.php');
require_once(__DIR__ . '/../../../class/login/login.php');

$CHECK_USER_LOGIN = new UserActions($hello_name, "NoToken");

$CHECK_USER_LOGIN->CheckAccessLevel();

$USER_ACCESS_LEVEL = $CHECK_USER_LOGIN->CheckAccessLevel();

$ACCESS_LEVEL = $USER_ACCESS_LEVEL['ACCESS_LEVEL'];

if ($ACCESS_LEVEL < 3) {

    header('Location: /../../../../index.php?AccessDenied&USER=' . $hello_name . '&COMPANY=' . $COMPANY_ENTITY);
    die;

}

if (isset($fflife)) {
    if ($fflife == '1') {

        $search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_NUMBER_INT);
        $cb = filter_input(INPUT_GET, 'cb', FILTER_SANITIZE_SPECIAL_CHARS);
        $CBK_ID = filter_input(INPUT_GET, 'callbackid', FILTER_SANITIZE_NUMBER_INT);

        if (isset($cb)) {
            if ($cb == 'c') {

                $database = new Database();
                $database->beginTransaction();

                $CBK_DATE = filter_input(INPUT_POST, 'callbackdate', FILTER_SANITIZE_SPECIAL_CHARS);
                $CBK_TIME = filter_input(INPUT_POST, 'callbacktime', FILTER_SANITIZE_SPECIAL_CHARS);
                $CBK_NAME = filter_input(INPUT_POST, 'callbackclient', FILTER_SANITIZE_SPECIAL_CHARS);
                $CBK_NOTES = filter_input(INPUT_POST, 'callbacknotes', FILTER_SANITIZE_SPECIAL_CHARS);
                $CBK_ASSIGN = filter_input(INPUT_POST, 'assign', FILTER_SANITIZE_SPECIAL_CHARS);
                $CBK_REM = filter_input(INPUT_POST, 'callreminder', FILTER_SANITIZE_SPECIAL_CHARS);
                $callremindeed = date("H:i:s", strtotime($CBK_REM, strtotime($CBK_TIME)));

                $database->query("UPDATE scheduled_callbacks set reminder=:reminder, assign=:assign, callback_time=:time, callback_date=:date, client_name =:client, edited_by =:submtter, notes =:note WHERE id=:id");
                $database->bind(':id', $CBK_ID);
                $database->bind(':reminder', $callremindeed);
                $database->bind(':client', $CBK_NAME);
                $database->bind(':assign', $CBK_ASSIGN);
                $database->bind(':time', $CBK_TIME);
                $database->bind(':date', $CBK_DATE);
                $database->bind(':submtter', $CBK_ASSIGN);
                $database->bind(':note', $CBK_NOTES);
                $database->execute();

                if (isset($ffcalendar)) {
                    if ($ffcalendar == '1') {

                        $calendar_start = "$CBK_DATE $CBK_TIME";
                        $calendar_name = " $CBK_TIME - $CBK_NAME ($search) - $CBK_NOTES";

                        $database->query("INSERT INTO evenement set start=:start, end=:end, title=:title, assigned_to=:assign");
                        $database->bind(':assign', $CBK_ASSIGN);
                        $database->bind(':start', $calendar_start);
                        $database->bind(':end', $calendar_start);
                        $database->bind(':title', $calendar_name);
                        $database->execute();

                    }

                }

                $notetypedata = "Callback";
                $messagetime = "Time $CBK_DATE $CBK_TIME | Notes: $CBK_NOTES (Assigned to $CBK_ASSIGN)";

                $database->query("INSERT INTO client_note set client_id=:id, client_name=:recipient, sent_by=:sent, note_type=:note, message=:message");
                $database->bind(':id', $search);
                $database->bind(':sent', $hello_name);
                $database->bind(':recipient', $CBK_NAME);
                $database->bind(':note', $notetypedata);
                $database->bind(':message', $messagetime);
                $database->execute();

                $database->endTransaction();

                if ($database->rowCount() > 0) {

                    header('Location: /app/calendar/calendar.php?callback=complete&callbackid=' . $CBK_ID);
                    die;

                } else {

                    header('Location: /app/calendar/calendar.php?callback=complete&callbackid=' . $CBK_ID);
                    die;


                }

            }
        }

    }
}

header('Location: /../../../../CRMmain.php');
die;
