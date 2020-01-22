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

require_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_SPECIAL_CHARS) . '/app/core/doc_root.php';

require_once(BASE_URL . '/classes/access_user/access_user_class.php');
$page_protect = new Access_user;
$page_protect->access_page(filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS), "", 10);
$hello_name = ($page_protect->user_full_name != "") ? $page_protect->user_full_name : $page_protect->user;

require_once(BASE_URL . '/includes/adl_features.php');
require_once(BASE_URL . '/includes/Access_Levels.php');
require_once(BASE_URL . '/includes/adlfunctions.php');
require_once(BASE_URL . '/classes/database_class.php');
require_once(BASE_URL . '/includes/ADL_PDO_CON.php');

if ($ffanalytics == '1') {
    require_once(BASE_URL . '/app/analyticstracking.php');
}

if (isset($fferror)) {
    if ($fferror == '1') {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }
}
if (!in_array($hello_name, $Level_3_Access, true)) {

    header('Location: /../../../CRMmain.php');
    die;

}
$EXECUTE = filter_input(INPUT_GET, 'EXECUTE', FILTER_SANITIZE_SPECIAL_CHARS);

$DOC_CAT = filter_input(INPUT_POST, 'DOC_CAT', FILTER_SANITIZE_SPECIAL_CHARS);
$DOC_TITLE = filter_input(INPUT_POST, 'DOC_TITLE', FILTER_SANITIZE_SPECIAL_CHARS);

if (isset($EXECUTE)) {
    if ($EXECUTE == '1') {

        $csv_mimetypes = array(
            'text/csv',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'text/plain',
            'application/csv',
            'text/comma-separated-values',
            'application/excel',
            'application/vnd.ms-excel',
            'application/vnd.msexcel',
            'text/anytext',
            'application/octet-stream',
            'application/txt',
            'application/pdf',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'text/xml'
        );

        if (!in_array($_FILES['file']['type'], $csv_mimetypes)) {

            header('Location: ../DocStore.php?UPLOAD=0&Reason=FileType');
            die;

        }
        $uploadtype = "DocStore";
        $date = date("y-m-d-G:i:s");

        $file = $date . $_FILES['file']['name'];
        $file_loc = $_FILES['file']['tmp_name'];
        $file_size = $_FILES['file']['size'];
        $file_type = $_FILES['file']['type'];


        if (!file_exists(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT',
                FILTER_SANITIZE_SPECIAL_CHARS) . "/app/docs/uploads/$COMPANY_ENTITY/$DOC_CAT")) {
            mkdir(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT',
                    FILTER_SANITIZE_SPECIAL_CHARS) . "/app/docs/uploads/$COMPANY_ENTITY/$DOC_CAT", 0777, true);
        }

        $folder = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT',
                FILTER_SANITIZE_SPECIAL_CHARS) . "/app/docs/uploads/$COMPANY_ENTITY/$DOC_CAT/";

        $new_size = $file_size / 1024;
        $new_file_name = strtolower($file);
        $final_file = str_replace("'", "", $new_file_name);
        $LOCATION = "app/docs/uploads/$COMPANY_ENTITY/$DOC_CAT/" . $final_file;

        if (move_uploaded_file($file_loc, $folder . $final_file)) {

            $ALLOWED_CATS = array("Training", "Compliance", "Scripts", "Other");

            if (!in_array($DOC_CAT, $ALLOWED_CATS)) {
                $DOC_CAT = "Other";
            }

            $UPLOAD = $pdo->prepare("INSERT INTO docstore_uploads set docstore_uploads_cat=:CAT, docstore_uploads_title=:TITLE, docstore_uploads_company=:COMPANY, docstore_uploads_uploaded_by=:HELLO, docstore_uploads_location=:LOCATION");
            $UPLOAD->bindParam(':LOCATION', $LOCATION, PDO::PARAM_STR);
            $UPLOAD->bindParam(':HELLO', $hello_name, PDO::PARAM_STR);
            $UPLOAD->bindParam(':TITLE', $DOC_TITLE, PDO::PARAM_STR);
            $UPLOAD->bindParam(':CAT', $DOC_CAT, PDO::PARAM_STR);
            $UPLOAD->bindParam(':COMPANY', $COMPANY_ENTITY, PDO::PARAM_STR);
            $UPLOAD->execute();

            header('Location: ../DocStore.php?UPLOAD=1');
            die;
        }

    }

}
