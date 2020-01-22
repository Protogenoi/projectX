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
require_once(__DIR__ . '/../../../classes/database_class.php');
require_once(__DIR__ . '/../../../includes/ADL_PDO_CON.php');
require_once(__DIR__ . '/../../../includes/ADL_MYSQLI_CON.php');

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

$add = filter_input(INPUT_GET, 'add', FILTER_SANITIZE_SPECIAL_CHARS);


if (isset($add)) {


    $googleapi = filter_input(INPUT_POST, 'googleapi', FILTER_SANITIZE_SPECIAL_CHARS);
    $googletrackingid = filter_input(INPUT_POST, 'googletrackingid', FILTER_SANITIZE_SPECIAL_CHARS);

    $dupcheck = "Select id from google_dev";

    $duperaw = $conn->query($dupcheck);

    if ($duperaw->num_rows >= 1) {
        while ($row = $duperaw->fetch_assoc()) {

            $dupeclientid = $row['id'];
        }

        $query = $pdo->prepare("UPDATE google_dev set tracking_id=:trackingidhold, api=:apihold, updated_by=:userholder WHERE id =:iddupe");

        $query->bindParam(':iddupe', $dupeclientid, PDO::PARAM_INT);
        $query->bindParam(':trackingidhold', $googletrackingid, PDO::PARAM_STR, 500);
        $query->bindParam(':apihold', $googleapi, PDO::PARAM_STR, 500);
        $query->bindParam(':userholder', $hello_name, PDO::PARAM_STR, 500);
        $query->execute() or die(print_r($query->errorInfo(), true));

        if (isset($fferror)) {
            if ($fferror == '0') {
                header('Location: ../Admindash.php?Google=y&google=updated');
                die;
            }
        }
    }

    $query = $pdo->prepare("INSERT INTO google_dev set tracking_id=:trackingidhold, api=:apihold, added_by=:userholder");

    $query->bindParam(':trackingidhold', $googletrackingid, PDO::PARAM_STR, 500);
    $query->bindParam(':apihold', $googleapi, PDO::PARAM_STR, 500);
    $query->bindParam(':userholder', $hello_name, PDO::PARAM_STR, 500);
    $query->execute() or die(print_r($query->errorInfo(), true));


    if (isset($fferror)) {
        if ($fferror == '0') {
            header('Location: ../Admindash.php?Google=y&google=y');
            die;
        }
    }
} else {
    if (isset($fferror)) {
        if ($fferror == '0') {
            header('Location: ../Admindash.php?Google=y&google=failed');
            die;
        }
    }
}
