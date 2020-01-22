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
require_once(__DIR__ . '/../../../classes/database_class.php');

if (isset($fferror)) {
    if ($fferror == '1') {

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }
}

$EXECUTE = filter_input(INPUT_GET, 'EXECUTE', FILTER_SANITIZE_SPECIAL_CHARS);


if (isset($EXECUTE)) {
    if ($EXECUTE == '1') {

        $PRO_ID = filter_input(INPUT_POST, 'PRO_ID', FILTER_SANITIZE_SPECIAL_CHARS);
        $PRO_PERCENT = filter_input(INPUT_POST, 'PRO_PERCENT', FILTER_SANITIZE_SPECIAL_CHARS);
        $PRO_COMPANY = filter_input(INPUT_POST, 'PRO_COMPANY', FILTER_SANITIZE_SPECIAL_CHARS);
        $PRO_ACTIVE = filter_input(INPUT_POST, 'PRO_ACTIVE', FILTER_SANITIZE_SPECIAL_CHARS);

        $database = new Database();
        $database->query("Select insurance_company_id from insurance_company WHERE insurance_company_id=:insurance_company_id");
        $database->bind(':insurance_company_id', $PRO_ID);
        $database->execute();

        if ($database->rowCount() >= 1) {


            $database->query("UPDATE insurance_company set insurance_company_name=:name, insurance_company_active=:active, insurance_company_added_by=:added_by, insurance_company_percent=:percent WHERE insurance_company_id=:id");
            $database->bind(':id', $PRO_ID);
            $database->bind(':percent', $PRO_PERCENT);
            $database->bind(':name', $PRO_COMPANY);
            $database->bind(':active', $PRO_ACTIVE);
            $database->bind(':added_by', $hello_name);
            $database->execute();

            if (isset($fferror)) {
                if ($fferror == '0') {
                    header('Location: ../Admindash.php?RETURN=UPDATED&provider=y');
                    die;
                }
            }
        } else {

            $INSERT = $pdo->prepare("INSERT INTO insurance_company set insurance_company_name=:insurance_company_name, insurance_company_active=:insurance_company_active, insurance_company_added_by=:insurance_company_added_by, insurance_company_percent=:insurance_company_percent");
            $INSERT->bindParam(':insurance_company_percent', $PRO_PERCENT, PDO::PARAM_STR, 500);
            $INSERT->bindParam(':insurance_company_name', $PRO_COMPANY, PDO::PARAM_STR, 500);
            $INSERT->bindParam(':insurance_company_active', $PRO_ACTIVE, PDO::PARAM_INT);
            $INSERT->bindParam(':insurance_company_added_by', $hello_name, PDO::PARAM_STR, 500);
            $INSERT->execute() or die(print_r($INSERT->errorInfo(), true));

            if (isset($fferror)) {
                if ($fferror == '0') {
                    header('Location: ../Admindash.php?RETURN=UPDATED&provider=y');
                    die;
                }
            }

        }


    }
} else {
    if (isset($fferror)) {
        if ($fferror == '0') {
            header('Location: ../Admindash.php?RETURN=FAIL&provider=y');
            die;
        }
    }

}
