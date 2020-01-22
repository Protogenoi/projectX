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

require_once(__DIR__ . '/../../../includes/ADL_PDO_CON.php');

if (isset($fferror)) {
    if ($fferror == '1') {

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

    }

}

$AssignTasks = filter_input(INPUT_GET, 'AssignTasks', FILTER_SANITIZE_SPECIAL_CHARS);

if (isset($AssignTasks)) {
    if ($AssignTasks == '1') {

        $taskname = filter_input(INPUT_POST, 'tasknames', FILTER_SANITIZE_SPECIAL_CHARS);
        $taskuser = filter_input(INPUT_POST, 'taskuser', FILTER_SANITIZE_SPECIAL_CHARS);

        $dupe = $pdo->prepare("SELECT Task FROM Set_Client_Tasks WHERE Task=:task");

        $dupe->bindParam(':task', $taskname, PDO::PARAM_STR);
        $dupe->execute() or die(print_r($dupe->errorInfo(), true));
        if ($dupe->rowCount() <= 0) {

            $insert = $pdo->prepare("INSERT INTO Set_Client_Tasks set Assigned=:assign, Task=:task");

            $insert->bindParam(':task', $taskname, PDO::PARAM_STR);
            $insert->bindParam(':assign', $taskuser, PDO::PARAM_STR);
            $insert->execute() or die(print_r($insert->errorInfo(), true));
            if ($insert->rowCount() >= 1) {

                if (isset($fferror)) {
                    if ($fferror == '0') {
                        header('Location: ../Admindash.php?TaskAssigned=y&AssignTasks=y&TaskAssignedTo=' . $taskuser . '&TASKUPDATED=' . $taskname);
                        die;
                    }
                }

            }

        } elseif ($dupe->rowCount() >= 1) {

            $update = $pdo->prepare("UPDATE Set_Client_Tasks set Assigned=:assign WHERE Task=:task");
            $update->bindParam(':task', $taskname, PDO::PARAM_STR);
            $update->bindParam(':assign', $taskuser, PDO::PARAM_STR);
            $update->execute() or die(print_r($update->errorInfo(), true));

            if (isset($fferror)) {
                if ($fferror == '0') {
                    header('Location: ../Admindash.php?TaskAssigned=y&AssignTasks=y&TaskAssignedTo=' . $taskuser . '&TASKUPDATED=' . $taskname);
                    die;
                }
            }


        }


    }

} else {

    if (isset($fferror)) {
        if ($fferror == '0') {
            header('Location: ../Admindash.php?TaskAssigned=failed&AssignTasks=y');
            die;
        }
    }


}
