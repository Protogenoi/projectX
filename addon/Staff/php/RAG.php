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
$page_protect->access_page(filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS), "", 10);
$hello_name = ($page_protect->user_full_name != "") ? $page_protect->user_full_name : $page_protect->user;

$USER_TRACKING = 0;

require_once(BASE_URL . '/includes/user_tracking.php');

require_once(BASE_URL . '/includes/time.php');

if (isset($FORCE_LOGOUT) && $FORCE_LOGOUT == 1) {
    $page_protect->log_out();
}

require_once(BASE_URL . '/includes/adl_features.php');
require_once(BASE_URL . '/includes/Access_Levels.php');
require_once(BASE_URL . '/includes/ADL_PDO_CON.php');
require_once(BASE_URL . '/classes/database_class.php');

if (isset($fferror)) {
    if ($fferror == '1') {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }
}

if (in_array($hello_name, $Level_10_Access, true)) {

    $EXECUTE = filter_input(INPUT_GET, 'EXECUTE', FILTER_SANITIZE_NUMBER_INT);

    if (isset($EXECUTE)) {

        if ($EXECUTE == '1') {

            $REF = filter_input(INPUT_POST, 'REF', FILTER_SANITIZE_SPECIAL_CHARS);
            $DATE = filter_input(INPUT_GET, 'DATE', FILTER_SANITIZE_SPECIAL_CHARS);
            $YEAR = filter_input(INPUT_GET, 'YEAR', FILTER_SANITIZE_SPECIAL_CHARS);
            $MONTH = filter_input(INPUT_GET, 'MONTH', FILTER_SANITIZE_SPECIAL_CHARS);

            $database = new Database();
            $database->beginTransaction();

            $database->query("INSERT into lead_rag set employee_id=:REF, sales=0, leads=0, month=:month, year=:year, date=:date, updated_by=:hello");
            $database->bind(':REF', $REF);
            $database->bind(':date', $DATE);
            $database->bind(':year', $YEAR);
            $database->bind(':month', $MONTH);
            $database->bind(':hello', $hello_name);
            $database->execute();
            $lastid = $database->lastInsertId();

            $database->query("INSERT INTO employee_register set employee_id=:REF, lead_rag_id=:lead_id, worked = 1");
            $database->bind(':REF', $REF);
            $database->bind(':lead_id', $lastid);
            $database->execute();
            $database->endTransaction();

            header('Location: ../Reports/RAG.php?RETURN=AgentAdded&MONTH=' . $MONTH . '&YEAR=' . $YEAR . '&DATE=' . $DATE . '&REF=' . $REF);
            die;

        }

        if ($EXECUTE == '2') {

            $REF = filter_input(INPUT_GET, 'REF', FILTER_SANITIZE_SPECIAL_CHARS);
            $DATE = filter_input(INPUT_GET, 'DATE', FILTER_SANITIZE_SPECIAL_CHARS);
            $YEAR = filter_input(INPUT_GET, 'YEAR', FILTER_SANITIZE_SPECIAL_CHARS);
            $MONTH = filter_input(INPUT_GET, 'MONTH', FILTER_SANITIZE_SPECIAL_CHARS);

            $SALES = filter_input(INPUT_POST, 'SALES', FILTER_SANITIZE_SPECIAL_CHARS);
            $CANCELS = filter_input(INPUT_POST, 'CANCELS', FILTER_SANITIZE_SPECIAL_CHARS);
            $LEADS = filter_input(INPUT_POST, 'LEADS', FILTER_SANITIZE_SPECIAL_CHARS);
            $HOURS = filter_input(INPUT_POST, 'HOURS', FILTER_SANITIZE_SPECIAL_CHARS);
            $MINUS = filter_input(INPUT_POST, 'MINUS', FILTER_SANITIZE_SPECIAL_CHARS);
            $REGISTER = filter_input(INPUT_POST, 'REGISTER', FILTER_SANITIZE_SPECIAL_CHARS);
            $LEAD_RAG_ID = filter_input(INPUT_GET, 'LEADRAG', FILTER_SANITIZE_SPECIAL_CHARS);
            $campaign = filter_input(INPUT_POST, 'campaign', FILTER_SANITIZE_SPECIAL_CHARS);

            $database = new Database();
            $database->beginTransaction();

            $database->query("UPDATE employee_details SET campaign=:campaign, updated_by=:hello WHERE employee_id=:REF");
            $database->bind(':REF', $REF);
            $database->bind(':campaign', $campaign);
            $database->bind(':hello', $hello_name);
            $database->execute();

            $database->query("UPDATE lead_rag set cancels=:cancels, hours=:hours, minus=:minus, updated_by=:hello WHERE employee_id=:REF AND date=:date");
            $database->bind(':REF', $REF);
            $database->bind(':cancels', $CANCELS);
            $database->bind(':hours', $HOURS);
            $database->bind(':minus', $MINUS);
            $database->bind(':date', $DATE);
            $database->bind(':hello', $hello_name);
            $database->execute();

            if (isset($REGISTER)) {
                if ($REGISTER == '1') {

                    $database->query("UPDATE employee_register set bank='0', worked='1', holiday='0', sick='0', awol='0', authorised='0', training='0' WHERE employee_id=:REF AND lead_rag_id=:lead_id");
                    $database->bind(':REF', $REF);
                    $database->bind(':lead_id', $LEAD_RAG_ID);
                    $database->execute();
                }

                if ($REGISTER == '2') {

                    $database->query("UPDATE employee_register set bank='0', holiday='1', worked='0', sick='0', awol='0', authorised='0', training='0' WHERE employee_id=:REF AND lead_rag_id=:lead_id");
                    $database->bind(':REF', $REF);
                    $database->bind(':lead_id', $LEAD_RAG_ID);
                    $database->execute();
                }
                if ($REGISTER == '3') {

                    $database->query("UPDATE employee_register set bank='0', sick='1', worked='0', holiday='0', awol='0', authorised='0', training='0' WHERE employee_id=:REF AND lead_rag_id=:lead_id");
                    $database->bind(':REF', $REF);
                    $database->bind(':lead_id', $LEAD_RAG_ID);
                    $database->execute();
                }
                if ($REGISTER == '4') {

                    $database->query("UPDATE employee_register set bank='0', awol='1', worked='0', holiday='0', sick='0', authorised='0', training='0' WHERE employee_id=:REF AND lead_rag_id=:lead_id");
                    $database->bind(':REF', $REF);
                    $database->bind(':lead_id', $LEAD_RAG_ID);
                    $database->execute();
                }
                if ($REGISTER == '5') {

                    $database->query("UPDATE employee_register set bank='0', authorised='1', worked='0', holiday='0', sick='0', awol='0', training='0' WHERE employee_id=:REF AND lead_rag_id=:lead_id");
                    $database->bind(':REF', $REF);
                    $database->bind(':lead_id', $LEAD_RAG_ID);
                    $database->execute();
                }
                if ($REGISTER == '6') {

                    $database->query("UPDATE employee_register set bank='0', training='1', worked='0', holiday='0', sick='0', awol='0', authorised='0', WHERE employee_id=:REF AND lead_rag_id=:lead_id");
                    $database->bind(':REF', $REF);
                    $database->bind(':lead_id', $LEAD_RAG_ID);
                    $database->execute();
                }
                if ($REGISTER == '7') {

                    $database->query("UPDATE employee_register set bank='1', training='0', worked='0', holiday='0', sick='0', awol='0', authorised='0', WHERE employee_id=:REF AND lead_rag_id=:lead_id");
                    $database->bind(':REF', $REF);
                    $database->bind(':lead_id', $LEAD_RAG_ID);
                    $database->execute();
                }

            }

            $database->endTransaction();

            header('Location: ../Reports/RAG.php?RETURN=RAGUPDATED&MONTH=' . $MONTH . '&YEAR=' . $YEAR . '&DATE=' . $DATE . '&REF=' . $REF);
            die;

        }

    }

} else {
    header('Location: /../../../../CRMmain');
    die;
}
