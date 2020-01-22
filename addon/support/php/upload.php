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

    $ticketID = filter_input(INPUT_GET, 'ticketID', FILTER_SANITIZE_NUMBER_INT);
    $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING);
    $category = 'Cat';

    if (isset($_FILES['file'])) {

        $countfiles = count($_FILES['file']['name']);

        for ($i = 0; $i < $countfiles; $i++) {

            $rand = rand(1, 25);
            $DATE = date("his");
            $file = $ticketID . "-" . $DATE . $rand . "-" . $_FILES['file']['name'][$i];
            $file_loc = $_FILES['file']['tmp_name'][$i];
            $file_size = $_FILES['file']['size'][$i];
            $fileType = $_FILES['file']['type'][$i];

            if (!file_exists(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT',
                    FILTER_SANITIZE_SPECIAL_CHARS) . "/addon/support/uploads/$ticketID")) {
                mkdir(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT',
                        FILTER_SANITIZE_SPECIAL_CHARS) . "/addon/support/uploads/$ticketID", 0777, true);
            }

            $folder = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT',
                    FILTER_SANITIZE_SPECIAL_CHARS) . "/addon/support/uploads/$ticketID/";

            $new_size = $file_size / 1024;
            $new_file_name = strtolower($file);

            $final_file = str_replace("'", "", $new_file_name);

            if (move_uploaded_file($file_loc, $folder . $final_file)) {

                $location = $folder . "" . $final_file;

                $uploadSupportFiles = new ADL\supportTickets($pdo);
                $uploadSupportFiles->setAddedBy($hello_name);
                $uploadSupportFiles->setFileName($final_file);
                $uploadSupportFiles->setFileType($fileType);
                $uploadSupportFiles->setCategory($category);
                $uploadSupportFiles->setFileLocation($location);
                $uploadSupportFiles->setId($ticketID);
                $result = $uploadSupportFiles->uploadSupportTicketFiles();

            }

        }


        if (isset($result) && $result != 'error') {

            $toastrTitle = 'File Uploaded!';
            $toastrMessage = 'Success';
            $toastrResponse = 1;

            header('Location: /../../../../addon/support/main.php?&toastrResponse=' . $toastrResponse . '&toastrMessage=' . $toastrMessage . '&toastrTitle=' . $toastrTitle);
            die;

        } else {

            $toastrTitle = 'File Not Uploaded!';
            $toastrMessage = 'Error!';
            $toastrResponse = 2;

            header('Location: /../../../../addon/support/main.php?toastrResponse=' . $toastrResponse . '&toastrMessage=' . $toastrMessage . '&toastrTitle=' . $toastrTitle);
            die;

        }


    }

}

$toastrTitle = 'File Not Uploaded!';
$toastrMessage = "Error";
$toastrResponse = 2;

header('Location: /../../../../addon/support/main.php?toastrResponse=' . $toastrResponse . '&toastrMessage=' . $toastrMessage . '&toastrTitle=' . $toastrTitle);
die;

