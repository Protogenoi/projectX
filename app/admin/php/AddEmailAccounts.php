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

require_once(__DIR__ . '/../../../includes/ADL_PDO_CON.php');
require_once(__DIR__ . '/../../../includes/ADL_MYSQLI_CON.php');

if (isset($fferror)) {
    if ($fferror == '0') {

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

    }

}

$emailaccount = filter_input(INPUT_POST, 'emailaccount', FILTER_SANITIZE_SPECIAL_CHARS);
$displayname = filter_input(INPUT_POST, 'displayname', FILTER_SANITIZE_SPECIAL_CHARS);
$emailtype = filter_input(INPUT_POST, 'emailtype', FILTER_SANITIZE_SPECIAL_CHARS);
$pop = filter_input(INPUT_POST, 'pop', FILTER_SANITIZE_SPECIAL_CHARS);
$popport = filter_input(INPUT_POST, 'popport', FILTER_SANITIZE_SPECIAL_CHARS);
$imap = filter_input(INPUT_POST, 'imap', FILTER_SANITIZE_SPECIAL_CHARS);
$imapport = filter_input(INPUT_POST, 'imapport', FILTER_SANITIZE_SPECIAL_CHARS);
$smtp = filter_input(INPUT_POST, 'smtp', FILTER_SANITIZE_SPECIAL_CHARS);
$smtpport = filter_input(INPUT_POST, 'smtpport', FILTER_SANITIZE_SPECIAL_CHARS);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);

$emailfrom = filter_input(INPUT_POST, 'emailfrom', FILTER_SANITIZE_SPECIAL_CHARS);
$emailreply = filter_input(INPUT_POST, 'emailreply', FILTER_SANITIZE_SPECIAL_CHARS);
$emailbcc = filter_input(INPUT_POST, 'emailbcc', FILTER_SANITIZE_SPECIAL_CHARS);
$emailsubject = filter_input(INPUT_POST, 'emailsubject', FILTER_SANITIZE_SPECIAL_CHARS);

if ($emailaccount == "account1") {

    $dupcheck = "Select id from email_accounts where emailaccount='account1'";

    $duperaw = $conn->query($dupcheck);

    if ($duperaw->num_rows >= 1) {
        while ($row = $duperaw->fetch_assoc()) {

            $dupeclientid = $row['id'];
        }

        $query = $pdo->prepare("UPDATE email_accounts set emailfrom=:emailfrom, emailreply=:emailreply, emailsubject=:emailsubject, emailbcc=:emailbcc, displayname=:displayname, emailtype=:emailtype, pop=:pop, popport=:popport, imap=:imap, imapport=:imapport, smtp=:smtp, smtpport=:smtpport, email=:email, password=AES_ENCRYPT(:password, UNHEX(:key)), added_by=:hello where emailaccount='account1'");
        $query->bindParam(':key', $EN_KEY, PDO::PARAM_STR);
        $query->bindParam(':displayname', $displayname, PDO::PARAM_STR, 500);
        $query->bindParam(':emailtype', $emailtype, PDO::PARAM_STR, 500);
        $query->bindParam(':pop', $pop, PDO::PARAM_STR, 500);
        $query->bindParam(':popport', $popport, PDO::PARAM_STR, 500);
        $query->bindParam(':imap', $imap, PDO::PARAM_STR, 500);
        $query->bindParam(':imapport', $imapport, PDO::PARAM_STR, 500);
        $query->bindParam(':smtp', $smtp, PDO::PARAM_STR, 500);
        $query->bindParam(':smtpport', $smtpport, PDO::PARAM_STR, 500);
        $query->bindParam(':email', $email, PDO::PARAM_STR, 500);
        $query->bindParam(':password', $password, PDO::PARAM_STR, 2500);
        $query->bindParam(':hello', $hello_name, PDO::PARAM_STR, 500);
        $query->bindParam(':emailfrom', $emailfrom, PDO::PARAM_STR, 500);
        $query->bindParam(':emailreply', $emailreply, PDO::PARAM_STR, 500);
        $query->bindParam(':emailbcc', $emailbcc, PDO::PARAM_STR, 500);
        $query->bindParam(':emailsubject', $emailsubject, PDO::PARAM_STR, 500);
        $query->execute() or die(print_r($query->errorInfo(), true));

        if (isset($fferror)) {
            if ($fferror == '0') {

                header('Location: ../Admindash.php?emailaccount=account1&Emails=y');
                die;
            }
        }
    } else {

        $query = $pdo->prepare("INSERT INTO email_accounts set emailfrom=:emailfrom, emailreply=:emailreply, emailsubject=:emailsubject, emailbcc=:emailbcc, emailaccount=:emailaccount, displayname=:displayname, emailtype=:emailtype, pop=:pop, popport=:popport, imap=:imap, imapport=:imapport, smtp=:smtp, smtpport=:smtpport, email=:email, password=AES_ENCRYPT(:password, UNHEX(:key)), added_by=:hello");
        $query->bindParam(':key', $EN_KEY, PDO::PARAM_STR);
        $query->bindParam(':emailaccount', $emailaccount, PDO::PARAM_STR, 500);
        $query->bindParam(':displayname', $displayname, PDO::PARAM_STR, 500);
        $query->bindParam(':emailtype', $emailtype, PDO::PARAM_STR, 500);
        $query->bindParam(':pop', $pop, PDO::PARAM_STR, 500);
        $query->bindParam(':popport', $popport, PDO::PARAM_STR, 500);
        $query->bindParam(':imap', $imap, PDO::PARAM_STR, 500);
        $query->bindParam(':imapport', $imapport, PDO::PARAM_STR, 500);
        $query->bindParam(':smtp', $smtp, PDO::PARAM_STR, 500);
        $query->bindParam(':smtpport', $smtpport, PDO::PARAM_STR, 500);
        $query->bindParam(':email', $email, PDO::PARAM_STR, 500);
        $query->bindParam(':password', $password, PDO::PARAM_STR, 2500);
        $query->bindParam(':hello', $hello_name, PDO::PARAM_STR, 500);
        $query->bindParam(':emailfrom', $emailfrom, PDO::PARAM_STR, 500);
        $query->bindParam(':emailreply', $emailreply, PDO::PARAM_STR, 500);
        $query->bindParam(':emailbcc', $emailbcc, PDO::PARAM_STR, 500);
        $query->bindParam(':emailsubject', $emailsubject, PDO::PARAM_STR, 500);


        $query->execute() or die(print_r($query->errorInfo(), true));

        if (isset($fferror)) {
            if ($fferror == '0') {

                header('Location: ../Admindash.php?emailaccount=account1&Emails=y');
                die;
            }
        }
    }

}

if ($emailaccount == "account2") {

    $dupcheck = "Select id from email_accounts where emailaccount='account2'";

    $duperaw = $conn->query($dupcheck);

    if ($duperaw->num_rows >= 1) {
        while ($row = $duperaw->fetch_assoc()) {

            $dupeclientid = $row['id'];
        }

        $query = $pdo->prepare("UPDATE email_accounts set emailfrom=:emailfrom, emailreply=:emailreply, emailsubject=:emailsubject, emailbcc=:emailbcc, displayname=:displayname, emailtype=:emailtype, pop=:pop, popport=:popport, imap=:imap, imapport=:imapport, smtp=:smtp, smtpport=:smtpport, email=:email, password=AES_ENCRYPT(:password, UNHEX(:key)), added_by=:hello where emailaccount='account2'");
        $query->bindParam(':key', $EN_KEY, PDO::PARAM_STR);
        $query->bindParam(':displayname', $displayname, PDO::PARAM_STR, 500);
        $query->bindParam(':emailtype', $emailtype, PDO::PARAM_STR, 500);
        $query->bindParam(':pop', $pop, PDO::PARAM_STR, 500);
        $query->bindParam(':popport', $popport, PDO::PARAM_STR, 500);
        $query->bindParam(':imap', $imap, PDO::PARAM_STR, 500);
        $query->bindParam(':imapport', $imapport, PDO::PARAM_STR, 500);
        $query->bindParam(':smtp', $smtp, PDO::PARAM_STR, 500);
        $query->bindParam(':smtpport', $smtpport, PDO::PARAM_STR, 500);
        $query->bindParam(':email', $email, PDO::PARAM_STR, 500);
        $query->bindParam(':password', $password, PDO::PARAM_STR, 2500);
        $query->bindParam(':hello', $hello_name, PDO::PARAM_STR, 500);
        $query->bindParam(':emailfrom', $emailfrom, PDO::PARAM_STR, 500);
        $query->bindParam(':emailreply', $emailreply, PDO::PARAM_STR, 500);
        $query->bindParam(':emailbcc', $emailbcc, PDO::PARAM_STR, 500);
        $query->bindParam(':emailsubject', $emailsubject, PDO::PARAM_STR, 500);
        $query->execute() or die(print_r($query->errorInfo(), true));

        if (isset($fferror)) {
            if ($fferror == '0') {

                header('Location: ../Admindash.php?emailaccount=account2&Emails=y');
                die;
            }
        }
    } else {
        $query = $pdo->prepare("INSERT INTO email_accounts set emailfrom=:emailfrom, emailreply=:emailreply, emailsubject=:emailsubject, emailbcc=:emailbcc, emailaccount=:emailaccount, displayname=:displayname, emailtype=:emailtype, pop=:pop, popport=:popport, imap=:imap, imapport=:imapport, smtp=:smtp, smtpport=:smtpport, email=:email, password=AES_ENCRYPT(:password, UNHEX(:key)), added_by=:hello");
        $query->bindParam(':key', $EN_KEY, PDO::PARAM_STR);
        $query->bindParam(':emailaccount', $emailaccount, PDO::PARAM_STR, 500);
        $query->bindParam(':displayname', $displayname, PDO::PARAM_STR, 500);
        $query->bindParam(':emailtype', $emailtype, PDO::PARAM_STR, 500);
        $query->bindParam(':pop', $pop, PDO::PARAM_STR, 500);
        $query->bindParam(':popport', $popport, PDO::PARAM_STR, 500);
        $query->bindParam(':imap', $imap, PDO::PARAM_STR, 500);
        $query->bindParam(':imapport', $imapport, PDO::PARAM_STR, 500);
        $query->bindParam(':smtp', $smtp, PDO::PARAM_STR, 500);
        $query->bindParam(':smtpport', $smtpport, PDO::PARAM_STR, 500);
        $query->bindParam(':email', $email, PDO::PARAM_STR, 500);
        $query->bindParam(':password', $password, PDO::PARAM_STR, 2500);
        $query->bindParam(':hello', $hello_name, PDO::PARAM_STR, 500);
        $query->bindParam(':emailfrom', $emailfrom, PDO::PARAM_STR, 500);
        $query->bindParam(':emailreply', $emailreply, PDO::PARAM_STR, 500);
        $query->bindParam(':emailbcc', $emailbcc, PDO::PARAM_STR, 500);
        $query->bindParam(':emailsubject', $emailsubject, PDO::PARAM_STR, 500);
        $query->execute() or die(print_r($query->errorInfo(), true));

        if (isset($fferror)) {
            if ($fferror == '0') {
                header('Location: ../Admindash.php?emailaccount=account2&Emails=y');
                die;
            }
        }
    }

}

if ($emailaccount == "account3") {

    $dupcheck = "Select id from email_accounts where emailaccount='account3'";

    $duperaw = $conn->query($dupcheck);

    if ($duperaw->num_rows >= 1) {
        while ($row = $duperaw->fetch_assoc()) {

            $dupeclientid = $row['id'];
        }

        $query = $pdo->prepare("UPDATE email_accounts set emailfrom=:emailfrom, emailreply=:emailreply, emailsubject=:emailsubject, emailbcc=:emailbcc, displayname=:displayname, emailtype=:emailtype, pop=:pop, popport=:popport, imap=:imap, imapport=:imapport, smtp=:smtp, smtpport=:smtpport, email=:email, password=AES_ENCRYPT(:password, UNHEX(:key)), added_by=:hello where emailaccount='account3'");
        $query->bindParam(':key', $EN_KEY, PDO::PARAM_STR);
        $query->bindParam(':displayname', $displayname, PDO::PARAM_STR, 500);
        $query->bindParam(':emailtype', $emailtype, PDO::PARAM_STR, 500);
        $query->bindParam(':pop', $pop, PDO::PARAM_STR, 500);
        $query->bindParam(':popport', $popport, PDO::PARAM_STR, 500);
        $query->bindParam(':imap', $imap, PDO::PARAM_STR, 500);
        $query->bindParam(':imapport', $imapport, PDO::PARAM_STR, 500);
        $query->bindParam(':smtp', $smtp, PDO::PARAM_STR, 500);
        $query->bindParam(':smtpport', $smtpport, PDO::PARAM_STR, 500);
        $query->bindParam(':email', $email, PDO::PARAM_STR, 500);
        $query->bindParam(':password', $password, PDO::PARAM_STR, 2500);
        $query->bindParam(':hello', $hello_name, PDO::PARAM_STR, 500);
        $query->bindParam(':emailfrom', $emailfrom, PDO::PARAM_STR, 500);
        $query->bindParam(':emailreply', $emailreply, PDO::PARAM_STR, 500);
        $query->bindParam(':emailbcc', $emailbcc, PDO::PARAM_STR, 500);
        $query->bindParam(':emailsubject', $emailsubject, PDO::PARAM_STR, 500);
        $query->execute() or die(print_r($query->errorInfo(), true));

        if (isset($fferror)) {
            if ($fferror == '0') {
                header('Location: ../Admindash.php?emailaccount=account3&Emails=y');
                die;
            }
        }
    } else {

        $query = $pdo->prepare("INSERT INTO email_accounts set emailfrom=:emailfrom, emailreply=:emailreply, emailsubject=:emailsubject, emailbcc=:emailbcc, emailaccount=:emailaccount, displayname=:displayname, emailtype=:emailtype, pop=:pop, popport=:popport, imap=:imap, imapport=:imapport, smtp=:smtp, smtpport=:smtpport, email=:email, password=AES_ENCRYPT(:password, UNHEX(:key)), added_by=:hello");
        $query->bindParam(':key', $EN_KEY, PDO::PARAM_STR);
        $query->bindParam(':emailaccount', $emailaccount, PDO::PARAM_STR, 500);
        $query->bindParam(':displayname', $displayname, PDO::PARAM_STR, 500);
        $query->bindParam(':emailtype', $emailtype, PDO::PARAM_STR, 500);
        $query->bindParam(':pop', $pop, PDO::PARAM_STR, 500);
        $query->bindParam(':popport', $popport, PDO::PARAM_STR, 500);
        $query->bindParam(':imap', $imap, PDO::PARAM_STR, 500);
        $query->bindParam(':imapport', $imapport, PDO::PARAM_STR, 500);
        $query->bindParam(':smtp', $smtp, PDO::PARAM_STR, 500);
        $query->bindParam(':smtpport', $smtpport, PDO::PARAM_STR, 500);
        $query->bindParam(':email', $email, PDO::PARAM_STR, 500);
        $query->bindParam(':password', $password, PDO::PARAM_STR, 2500);
        $query->bindParam(':hello', $hello_name, PDO::PARAM_STR, 500);
        $query->bindParam(':emailfrom', $emailfrom, PDO::PARAM_STR, 500);
        $query->bindParam(':emailreply', $emailreply, PDO::PARAM_STR, 500);
        $query->bindParam(':emailbcc', $emailbcc, PDO::PARAM_STR, 500);
        $query->bindParam(':emailsubject', $emailsubject, PDO::PARAM_STR, 500);
        $query->execute() or die(print_r($query->errorInfo(), true));


        if (isset($fferror)) {
            if ($fferror == '0') {
                header('Location: ../Admindash.php?emailaccount=account3&Emails=y');
                die;
            }
        }
    }

}

if ($emailaccount == "account4") {

    $dupcheck = "Select id from email_accounts where emailaccount='account4'";

    $duperaw = $conn->query($dupcheck);

    if ($duperaw->num_rows >= 1) {
        while ($row = $duperaw->fetch_assoc()) {

            $dupeclientid = $row['id'];
        }

        $query = $pdo->prepare("UPDATE email_accounts set emailfrom=:emailfrom, emailreply=:emailreply, emailsubject=:emailsubject, emailbcc=:emailbcc, displayname=:displayname, emailtype=:emailtype, pop=:pop, popport=:popport, imap=:imap, imapport=:imapport, smtp=:smtp, smtpport=:smtpport, email=:email, password=AES_ENCRYPT(:password, UNHEX(:key)), added_by=:hello where emailaccount='account4'");
        $query->bindParam(':key', $EN_KEY, PDO::PARAM_STR);
        $query->bindParam(':displayname', $displayname, PDO::PARAM_STR, 500);
        $query->bindParam(':emailtype', $emailtype, PDO::PARAM_STR, 500);
        $query->bindParam(':pop', $pop, PDO::PARAM_STR, 500);
        $query->bindParam(':popport', $popport, PDO::PARAM_STR, 500);
        $query->bindParam(':imap', $imap, PDO::PARAM_STR, 500);
        $query->bindParam(':imapport', $imapport, PDO::PARAM_STR, 500);
        $query->bindParam(':smtp', $smtp, PDO::PARAM_STR, 500);
        $query->bindParam(':smtpport', $smtpport, PDO::PARAM_STR, 500);
        $query->bindParam(':email', $email, PDO::PARAM_STR, 500);
        $query->bindParam(':password', $password, PDO::PARAM_STR, 2500);
        $query->bindParam(':hello', $hello_name, PDO::PARAM_STR, 500);
        $query->bindParam(':emailfrom', $emailfrom, PDO::PARAM_STR, 500);
        $query->bindParam(':emailreply', $emailreply, PDO::PARAM_STR, 500);
        $query->bindParam(':emailbcc', $emailbcc, PDO::PARAM_STR, 500);
        $query->bindParam(':emailsubject', $emailsubject, PDO::PARAM_STR, 500);
        $query->execute() or die(print_r($query->errorInfo(), true));

        if (isset($fferror)) {
            if ($fferror == '0') {
                header('Location: ../Admindash.php?emailaccount=account4&Emails=y');
                die;
            }
        }
    } else {

        $query = $pdo->prepare("INSERT INTO email_accounts set emailfrom=:emailfrom, emailreply=:emailreply, emailsubject=:emailsubject, emailbcc=:emailbcc, emailaccount=:emailaccount, displayname=:displayname, emailtype=:emailtype, pop=:pop, popport=:popport, imap=:imap, imapport=:imapport, smtp=:smtp, smtpport=:smtpport, email=:email, password=AES_ENCRYPT(:password, UNHEX(:key)), added_by=:hello");
        $query->bindParam(':key', $EN_KEY, PDO::PARAM_STR);
        $query->bindParam(':emailaccount', $emailaccount, PDO::PARAM_STR, 500);
        $query->bindParam(':displayname', $displayname, PDO::PARAM_STR, 500);
        $query->bindParam(':emailtype', $emailtype, PDO::PARAM_STR, 500);
        $query->bindParam(':pop', $pop, PDO::PARAM_STR, 500);
        $query->bindParam(':popport', $popport, PDO::PARAM_STR, 500);
        $query->bindParam(':imap', $imap, PDO::PARAM_STR, 500);
        $query->bindParam(':imapport', $imapport, PDO::PARAM_STR, 500);
        $query->bindParam(':smtp', $smtp, PDO::PARAM_STR, 500);
        $query->bindParam(':smtpport', $smtpport, PDO::PARAM_STR, 500);
        $query->bindParam(':email', $email, PDO::PARAM_STR, 500);
        $query->bindParam(':password', $password, PDO::PARAM_STR, 2500);
        $query->bindParam(':hello', $hello_name, PDO::PARAM_STR, 500);
        $query->bindParam(':emailfrom', $emailfrom, PDO::PARAM_STR, 500);
        $query->bindParam(':emailreply', $emailreply, PDO::PARAM_STR, 500);
        $query->bindParam(':emailbcc', $emailbcc, PDO::PARAM_STR, 500);
        $query->bindParam(':emailsubject', $emailsubject, PDO::PARAM_STR, 500);
        $query->execute() or die(print_r($query->errorInfo(), true));

        if (isset($fferror)) {
            if ($fferror == '0') {
                header('Location: ../Admindash.php?emailaccount=account4&Emails=y');
                die;
            }
        }
    }
}


if (isset($fferror)) {
    if ($fferror == '0') {
        header('Location: ../Admindash.php?emailaccount=failed&Emails=y');
        die;

    }
}
