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

require_once(__DIR__ . '/../../../classes/access_user/access_user_class.php');
$page_protect = new Access_user;
$page_protect->access_page(filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS), "", 2);
$hello_name = ($page_protect->user_full_name != "") ? $page_protect->user_full_name : $page_protect->user;

require_once(__DIR__ . '/../../../includes/adl_features.php');
require_once(__DIR__ . '/../../../includes/Access_Levels.php');
require_once(__DIR__ . '/../../../includes/adlfunctions.php');
require_once(__DIR__ . '/../../../classes/database_class.php');
require_once(__DIR__ . '/../../../includes/ADL_PDO_CON.php');

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
if (!in_array($hello_name, $Level_3_Access, true)) {

    header('Location: /../../../CRMmain.php');
    die;

}
$EXECUTE = filter_input(INPUT_GET, 'EXECUTE', FILTER_SANITIZE_SPECIAL_CHARS);

if (isset($EXECUTE)) {
    if ($EXECUTE == '1') {

        $MSG = filter_input(INPUT_POST, 'MSG', FILTER_SANITIZE_SPECIAL_CHARS);
        $TO = filter_input(INPUT_POST, 'MSG_TO', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);

        foreach ($TO as $SELECTED_TO) {

            $query = $pdo->prepare("SELECT employee_id FROM employee_details WHERE company=:COMPANY AND CONCAT(firstname, ' ', lastname)=:NAME");
            $query->bindParam(':NAME', $TO, PDO::PARAM_STR);
            $query->bindParam(':COMPANY', $COMPANY_ENTITY, PDO::PARAM_STR);
            $query->execute();
            $data1 = $query->fetch(PDO::FETCH_ASSOC);

            $ID_FK = $data1['employee_id'];

            if (isset($ID_FK)) {

                $MESSAGE = "$MSG | From $hello_name";

                $database = new Database();
                $database->query("INSERT INTO employee_timeline set note_type='ADL Message', message=:change, added_by=:hello, employee_id=:REF");
                $database->bind(':REF', $ID_FK);
                $database->bind(':hello', $hello_name);
                $database->bind(':change', $MESSAGE);
                $database->execute();

            }

            $database = new Database();
            $database->query("INSERT INTO messenger SET messenger_to=:TO, messenger_msg=:MSG, messenger_sent_by=:HELLO, messenger_company=:COMPANY");
            $database->bind(':COMPANY', $COMPANY_ENTITY);
            $database->bind(':MSG', $MSG);
            $database->bind(':TO', $SELECTED_TO);
            $database->bind(':HELLO', $hello_name);
            $database->execute();

        }

        header('Location: ../Main.php?RETURN=MSGADDED');


    }
    if ($EXECUTE == '2') {


        $query = $pdo->prepare("SELECT employee_id FROM employee_details WHERE company=:COMPANY AND CONCAT(firstname, ' ', lastname)=:NAME");
        $query->bindParam(':NAME', $TO, PDO::PARAM_STR);
        $query->bindParam(':COMPANY', $COMPANY_ENTITY, PDO::PARAM_STR);
        $query->execute();
        $data1 = $query->fetch(PDO::FETCH_ASSOC);

        $ID_FK = $data1['employee_id'];

        if (isset($ID_FK)) {

            $MESSAGE = "$MSG | From $hello_name";

            $database = new Database();
            $database->query("INSERT INTO employee_timeline set note_type='Read Receipt for ADL Message', message=:change, added_by=:hello, employee_id=:REF");
            $database->bind(':REF', $ID_FK);
            $database->bind(':hello', $hello_name);
            $database->bind(':change', $MESSAGE);
            $database->execute();

        }

        $MID = filter_input(INPUT_GET, 'MID', FILTER_SANITIZE_SPECIAL_CHARS);
        $database = new Database();
        $database->query("UPDATE messenger SET messenger_status='Read' WHERE messenger_company=:COMPANY AND messenger_id=:MID");
        $database->bind(':COMPANY', $COMPANY_ENTITY);
        $database->bind(':MID', $MID);
        $database->execute();

        header('Location: ../Main.php?RETURN=MSGUPDATED');


    }
}
