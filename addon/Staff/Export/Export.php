<?php
/*
 * ------------------------------------------------------------------------
 *                               ADL CRM
 * ------------------------------------------------------------------------
 *
 * Copyright © 2017 ADL CRM All rights reserved.
 *
 * Unauthorised copying of this file, via any medium is strictly prohibited.
 * Unauthorised distribution of this file, via any medium is strictly prohibited.
 * Unauthorised modification of this code is strictly prohibited.
 *
 * Proprietary and confidential
 *
 * Written by Michael Owen <michael@adl-crm.uk>, 2017
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
 *
*/

require_once(__DIR__ . '/../../../classes/access_user/access_user_class.php');
$page_protect = new Access_user;
$page_protect->access_page(filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS), "", 10);
$hello_name = ($page_protect->user_full_name != "") ? $page_protect->user_full_name : $page_protect->user;

$USER_TRACKING = 0;

require_once(__DIR__ . '/../../../includes/user_tracking.php');

require_once(__DIR__ . '/../../../includes/time.php');

if (isset($FORCE_LOGOUT) && $FORCE_LOGOUT == 1) {
    $page_protect->log_out();
}

require_once(__DIR__ . '/../../../includes/adl_features.php');
require_once(__DIR__ . '/../../../includes/Access_Levels.php');

require_once(__DIR__ . '/../../../classes/database_class.php');
require_once(__DIR__ . '/../../../includes/ADL_PDO_CON.php');

if (isset($fferror)) {
    if ($fferror == '1') {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }
}

if ($ffanalytics == '1') {
    require_once(__DIR__ . '/../../../app/analyticstracking.php');
}

$EXECUTE = filter_input(INPUT_GET, 'EXECUTE', FILTER_SANITIZE_NUMBER_INT);

if (isset($EXECUTE)) {

    $START_DATE = filter_input(INPUT_GET, 'START_DATE', FILTER_SANITIZE_SPECIAL_CHARS);
    $END_DATE = filter_input(INPUT_GET, 'END_DATE', FILTER_SANITIZE_SPECIAL_CHARS);

    if ($EXECUTE = '1') {

        $file = "exportRAG";
        $filename = $file . "_" . date("Y-m-d_H-i", time());
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename . '.csv');

        $output = "Employee Name, Total Sales,Total Leads, Total Cancels, Total Hours, Total Minus\n";
        $query = $pdo->prepare("SELECT SUM(lead_rag.cancels) AS cancels, CONCAT(employee_details.firstname, ' ', employee_details.lastname) AS NAME, SUM(lead_rag.sales) AS sales, SUM(lead_rag.hours) AS hours, SUM(lead_rag.minus) AS minus, SUM(lead_rag.leads) AS leads FROM lead_rag JOIN employee_details ON employee_details.employee_id = lead_rag.employee_id WHERE substr(lead_rag.date,5) between :START_DATE AND :END_DATE GROUP BY lead_rag.employee_id");
        $query->bindParam(':START_DATE', $START_DATE, PDO::PARAM_STR);
        $query->bindParam(':END_DATE', $END_DATE, PDO::PARAM_STR);
        $query->execute();

        $list = $query->fetchAll();
        foreach ($list as $rs) {
            $output .= $rs['NAME'] . "," . $rs['sales'] . "," . $rs['leads'] . "," . $rs['cancels'] . "," . $rs['hours'] . "," . $rs['minus'] . "\n";

        }
        echo $output;
        exit;

    }
}
