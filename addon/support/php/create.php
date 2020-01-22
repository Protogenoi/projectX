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

    $task = filter_input(INPUT_POST, 'task', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS);
    $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING);
    $sendText = filter_input(INPUT_POST, 'sendText', FILTER_SANITIZE_SPECIAL_CHARS);
    $assign = filter_input(INPUT_POST, 'assign', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

    $createSupportTicket = new ADL\supportTickets($pdo);
    $createSupportTicket->setAddedBy($hello_name);
    $createSupportTicket->setTask($task);
    $createSupportTicket->setContent($content);
    $createSupportTicket->setCategory($category);
    $createSupportTicket->setAssigned($assign);
    $result = $createSupportTicket->createTicket();

    if (isset($sendText) && $sendText != 'No') {

        require_once(BASE_URL . '/resources/lib/vendor/autoload.php');

        if ($sendText == 'Michael') {
            $emailTo = 'michael@firstprioritygroup.co.uk';
            $clientName = 'Michael';

        } elseif ($sendText == 'Nick') {
            $emailTo = 'nick@firstprioritygroup.co.uk';
            $clientName = 'Nick';
        } elseif ($sendText == 'Matt') {
            $emailTo = 'matt@firstprioritygroup.co.uk';
            $clientName = 'Matt';
        }

        $emailMessage = html_entity_decode($content);
        $message = 'ADL Task:' . $task;

        $email = new Mail();
        $email->setFrom("no-reply@adl-crm.uk", 'ADL Support Ticket');
        $email->setSubject($message);
        $email->setFooter(true, 'TEXT', '');
        $email->addCategory('ADL Support Ticket');
        $email->addTo($emailTo, $clientName);
        $email->addContent("text/plain", $emailMessage);
        $email->addContent(
            "text/html", $emailMessage
        );


        $sendgrid = new SendGrid($sendGridAPI);

        $NEW_MSG = "$emailTo - $emailMessage";

        $response = $sendgrid->send($email);
        print $response->statusCode() . "\n";
        print_r($response->headers());
        print $response->body() . "\n";

    }

    if ($result == 'success') {

        $toastrTitle = 'Ticket submitted!';
        $toastrMessage = 'Ticket saved';
        $toastrResponse = 1;

        header('Location: ../main.php?toastrResponse=' . $toastrResponse . '&toastrMessage=' . $toastrMessage . '&toastrTitle=' . $toastrTitle . '#menu4');
        die;

    }

}

header('Location: /../../../CRMmain.php?error=1');
die;
