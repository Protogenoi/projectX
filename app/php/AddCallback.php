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

require_once(__DIR__ . '/../../includes/adl_features.php');

require_once(__DIR__ . '/../../includes/time.php');

if (isset($FORCE_LOGOUT) && $FORCE_LOGOUT == 1) {
    $page_protect->log_out();
}

require_once(__DIR__ . '/../../includes/user_tracking.php');
require_once(__DIR__ . '/../../includes/Access_Levels.php');

require_once(__DIR__ . '/../../includes/ADL_PDO_CON.php');
require_once(__DIR__ . '/../../classes/database_class.php');

if ($ffanalytics == '1') {
    require_once(__DIR__ . '/../../app/analyticstracking.php');
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

        $EXECUTE = filter_input(INPUT_GET, 'EXECUTE', FILTER_SANITIZE_SPECIAL_CHARS);

        if (isset($EXECUTE)) {

            $CBK_ID = filter_input(INPUT_GET, 'CBK_ID', FILTER_SANITIZE_NUMBER_INT);

            if ($EXECUTE == '1') {
                $query = $pdo->prepare("UPDATE scheduled_callbacks set complete='y' where id = :CALL_IDyes");
                $query->bindParam(':CALL_IDyes', $CBK_ID, PDO::PARAM_INT);
                $query->execute();

                header('Location: /app/calendar/calendar.php?callback=complete&CALL_ID' . $CBK_ID);
                die;

            }

            if ($EXECUTE == 'n') {
                $query = $pdo->prepare("UPDATE scheduled_callbacks set complete='n' where id = :CALL_IDno");
                $query->bindParam(':CALL_IDno', $CBK_ID, PDO::PARAM_INT);
                $query->execute();

                header('Location: /app/calendar/calendar.php?callback=incomplete');
                die;

            }

            if ($EXECUTE == 'yV') {

                $search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_NUMBER_INT);

                $query = $pdo->prepare("UPDATE scheduled_callbacks set complete='y' where id = :CALL_IDyes");
                $query->bindParam(':CALL_IDyes', $CBK_ID, PDO::PARAM_INT);
                $query->execute();

                header('Location: /Client.php?Addcallback=complete&CALL_ID' . $CBK_ID . '&search=' . $search);
                die;

            }

            if ($EXECUTE == 'nV') {

                $search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_NUMBER_INT);

                $query = $pdo->prepare("UPDATE scheduled_callbacks set complete='n' where id = :CALL_IDno");
                $query->bindParam(':CALL_IDno', $CBK_ID, PDO::PARAM_INT);
                $query->execute();

                header('Location: /Client.php?Addcallback=incomplete&search=' . $search);
                die;

            }

        }

    }
}

$search = filter_input(INPUT_POST, 'search', FILTER_SANITIZE_NUMBER_INT);
$callbacktype = filter_input(INPUT_POST, 'callbacktype', FILTER_SANITIZE_SPECIAL_CHARS);

$EXECUTE = filter_input(INPUT_GET, 'CB', FILTER_SANITIZE_SPECIAL_CHARS);
$callsub = filter_input(INPUT_POST, 'callsub', FILTER_SANITIZE_NUMBER_INT);

if (isset($callsub)) {

    $database = new Database();
    $database->beginTransaction();

    $getcallback_date = filter_input(INPUT_POST, 'callbackdate', FILTER_SANITIZE_SPECIAL_CHARS);
    $getcallback_time = filter_input(INPUT_POST, 'callbacktime', FILTER_SANITIZE_SPECIAL_CHARS);
    $getcallback_client = filter_input(INPUT_POST, 'callbackclient', FILTER_SANITIZE_SPECIAL_CHARS);
    $getcallback_notes = filter_input(INPUT_POST, 'callbacknotes', FILTER_SANITIZE_SPECIAL_CHARS);
    $assign = filter_input(INPUT_POST, 'assign', FILTER_SANITIZE_SPECIAL_CHARS);
    $callreminder = filter_input(INPUT_POST, 'callreminder', FILTER_SANITIZE_SPECIAL_CHARS);
    $search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_NUMBER_INT);
    $callremindeed = date("H:i:s", strtotime($callreminder, strtotime($getcallback_time)));

    $database->query("INSERT INTO scheduled_callbacks set reminder=:reminder, assign=:assign, callback_time=:callback_time, callback_date=:callback_date, client_id = :searchplaceholder, client_name =:clientnameplaceholder, submitted_by =:submtterplaceholder, notes =:callbacknotesvar");
    $database->bind(':searchplaceholder', $search);
    $database->bind(':reminder', $callremindeed);
    $database->bind(':clientnameplaceholder', $getcallback_client);
    $database->bind(':assign', $assign);
    $database->bind(':callback_time', $getcallback_time);
    $database->bind(':callback_date', $getcallback_date);
    $database->bind(':submtterplaceholder', $assign);
    $database->bind(':callbacknotesvar', $getcallback_notes);
    $database->execute();

    if (isset($ffcalendar)) {
        if ($ffcalendar == '1') {

            $calendar_start = "$getcallback_date $getcallback_time";
            $calendar_name = " $getcallback_time - $getcallback_client ($search) - $getcallback_notes";

            $database->query("INSERT INTO evenement set start=:start, end=:end, title=:title, assigned_to=:assign");
            $database->bind(':assign', $assign);
            $database->bind(':start', $calendar_start);
            $database->bind(':end', $calendar_start);
            $database->bind(':title', $calendar_name);
            $database->execute();

        }

    }

    $notetypedata = "Callback";
    $messagetime = "Time $getcallback_date $getcallback_time | Notes: $getcallback_notes (Assigned to $assign)";

    $database->query("INSERT INTO client_note set client_id=:clientidholder, client_name=:recipientholder, sent_by=:sentbyholder, note_type=:noteholder, message=:messageholder ");
    $database->bind(':clientidholder', $search);
    $database->bind(':sentbyholder', $hello_name);
    $database->bind(':recipientholder', $getcallback_client);
    $database->bind(':noteholder', $notetypedata);
    $database->bind(':messageholder', $messagetime);
    $database->execute();

    $database->endTransaction();

    if ($database->rowCount() > 0) {

        header('Location: ../Client.php?CallbackSet=1&search=' . $search . '&CallbackTime=' . $getcallback_time . '&CallbackDate=' . $getcallback_date);
        die;

    } else {

        header('Location: ../Client.php?CallbackSet=0&search=' . $search);
        die;


    }

}

header('Location: /../../../CRMmain.php');
die;
