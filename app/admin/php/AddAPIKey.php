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
require_once(__DIR__ . '/../../../classes/database_class.php');

if (isset($fferror)) {
    if ($fferror == '0') {

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

    }

}

$AddType = filter_input(INPUT_GET, 'AddType', FILTER_SANITIZE_SPECIAL_CHARS);

if (isset($AddType)) {


    if ($AddType == 'PostCode') {

        $PostCodeAPI = filter_input(INPUT_POST, 'PostCodeAPI', FILTER_SANITIZE_SPECIAL_CHARS);

        $database = new Database();

        $database->query("SELECT id from api_keys WHERE type='PostCode'");
        $database->execute();

        if ($database->rowCount() >= 1) {

            $database->query("UPDATE api_keys SET api_key=:key, updated_by=:hello WHERE type='PostCode'");
            $database->bind(':key', $PostCodeAPI);
            $database->bind(':hello', $hello_name);
            $database->execute();

            header('Location: ../Admindash.php?PostCode=y&PostCodeMSG=1');
            die;

        } else {

            $database->query("INSERT INTO api_keys SET api_key=:key, added_by=:hello, type='PostCode'");
            $database->bind(':key', $PostCodeAPI);
            $database->bind(':hello', $hello_name);
            $database->execute();


            header('Location: ../Admindash.php?PostCode=y&PostCodeMSG=2');
            die;

        }

        header('Location: ../Admindash.php?PostCode=y&PostCodeMSG=3');
        die;

    }


}
