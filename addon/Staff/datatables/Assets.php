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

$USER = filter_input(INPUT_GET, 'USER', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$TOKEN = filter_input(INPUT_GET, 'TOKEN', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

if (isset($USER) && $TOKEN) {

    require_once(__DIR__ . '/../../../classes/database_class.php');
    require_once(__DIR__ . '/../../../class/login/login.php');

    $CHECK_USER_LOGIN = new UserActions($USER, $TOKEN);
    $CHECK_USER_LOGIN->CheckToken();
    $OUT = $CHECK_USER_LOGIN->CheckToken();

    $USER_ACCESS_LEVEL = $CHECK_USER_LOGIN->CheckAccessLevel();

    $ACCESS_LEVEL = $USER_ACCESS_LEVEL['ACCESS_LEVEL'];

    if ($ACCESS_LEVEL < 10) {

        header('Location: /../../../../index.php?AccessDenied&USER=' . $hello_name . '&COMPANY=' . $COMPANY_ENTITY);
        die;

    }

    if (isset($OUT['TOKEN_CHECK']) && $OUT['TOKEN_CHECK'] == 'Bad') {
        echo "BAD";
    }

    if (isset($OUT['TOKEN_CHECK']) && $OUT['TOKEN_CHECK'] == 'Good') {

        $EXECUTE = filter_input(INPUT_GET, 'EXECUTE', FILTER_SANITIZE_NUMBER_INT);

        $USER = filter_input(INPUT_GET, 'USER', FILTER_SANITIZE_NUMBER_INT);
        $TOKEN = filter_input(INPUT_GET, 'TOKEN', FILTER_SANITIZE_NUMBER_INT);

        if (isset($EXECUTE)) {
            if ($EXECUTE == '1') {

                $query = $pdo->prepare("SELECT updated_date, asset_name, manufactorer, device, fault, fault_reason, inv_id FROM inventory ORDER BY updated_date DESC");
                $query->execute() or die(print_r($query->errorInfo(), true));
                json_encode($results['aaData'] = $query->fetchAll(PDO::FETCH_ASSOC));
                echo json_encode($results);

            }

            if ($EXECUTE == '2') {


                $query = $pdo->prepare("select int_computers.inv_id, int_computers.mac, int_computers.ram, int_computers.os, int_computers.hostname, inventory.asset_name, inventory.fault, inventory.updated_date, inventory.manufactorer FROM int_computers JOIN inventory on inventory.inv_id = int_computers.inv_id ORDER BY inventory.updated_date DESC");
                $query->execute() or die(print_r($query->errorInfo(), true));
                json_encode($results['aaData'] = $query->fetchAll(PDO::FETCH_ASSOC));
                echo json_encode($results);

            }

            if ($EXECUTE == '3') {


                $query = $pdo->prepare("select int_keyboards.inv_id, int_keyboards.connection_type, inventory.asset_name, inventory.fault, inventory.updated_date, inventory.manufactorer FROM int_keyboards JOIN inventory on inventory.inv_id = int_keyboards.inv_id ORDER BY inventory.updated_date DESC");
                $query->execute() or die(print_r($query->errorInfo(), true));
                json_encode($results['aaData'] = $query->fetchAll(PDO::FETCH_ASSOC));
                echo json_encode($results);

            }

            if ($EXECUTE == '4') {


                $query = $pdo->prepare("select int_mice.inv_id, int_mice.connection_type, inventory.asset_name, inventory.fault, inventory.updated_date, inventory.manufactorer FROM int_mice JOIN inventory on inventory.inv_id = int_mice.inv_id ORDER BY inventory.updated_date DESC");
                $query->execute() or die(print_r($query->errorInfo(), true));
                json_encode($results['aaData'] = $query->fetchAll(PDO::FETCH_ASSOC));
                echo json_encode($results);

            }

            if ($EXECUTE == '5') {


                $query = $pdo->prepare("select int_headsets.assigned, inventory.inv_id, inventory.asset_name, inventory.fault, inventory.updated_date, inventory.manufactorer FROM int_headsets JOIN inventory on inventory.inv_id = int_headsets.inv_id ORDER BY inventory.updated_date DESC");
                $query->execute() or die(print_r($query->errorInfo(), true));
                json_encode($results['aaData'] = $query->fetchAll(PDO::FETCH_ASSOC));
                echo json_encode($results);

            }

            if ($EXECUTE == '6') {


                $query = $pdo->prepare("select int_phones.inv_id, int_phones.mac, inventory.asset_name, inventory.fault, inventory.updated_date, inventory.manufactorer FROM int_phones JOIN inventory on inventory.inv_id = int_phones.inv_id ORDER BY inventory.updated_date DESC");
                $query->execute() or die(print_r($query->errorInfo(), true));
                json_encode($results['aaData'] = $query->fetchAll(PDO::FETCH_ASSOC));
                echo json_encode($results);

            }


            if ($EXECUTE == '7') {


                $query = $pdo->prepare("select int_network.inv_id, int_network.mac, int_network.ip, int_network.hostname, inventory.asset_name, inventory.fault, inventory.updated_date, inventory.manufactorer FROM int_network JOIN inventory on inventory.inv_id = int_network.inv_id ORDER BY inventory.updated_date DESC");
                $query->execute() or die(print_r($query->errorInfo(), true));
                json_encode($results['aaData'] = $query->fetchAll(PDO::FETCH_ASSOC));
                echo json_encode($results);

            }

            if ($EXECUTE == '8') {


                $query = $pdo->prepare("select int_printers.inv_id, int_printers.mac, int_printers.ip, int_printers.hostname, inventory.asset_name, inventory.fault, inventory.updated_date, inventory.manufactorer FROM int_printers JOIN inventory on inventory.inv_id = int_printers.inv_id ORDER BY inventory.updated_date DESC");
                $query->execute() or die(print_r($query->errorInfo(), true));
                json_encode($results['aaData'] = $query->fetchAll(PDO::FETCH_ASSOC));
                echo json_encode($results);

            }

            if ($EXECUTE == '9') {


                $query = $pdo->prepare("select inventory.inv_id, inventory.asset_name, inventory.fault, inventory.updated_date, inventory.manufactorer FROM int_monitors JOIN inventory on inventory.inv_id = int_monitors.inv_id ORDER BY inventory.updated_date DESC");
                $query->execute() or die(print_r($query->errorInfo(), true));
                json_encode($results['aaData'] = $query->fetchAll(PDO::FETCH_ASSOC));
                echo json_encode($results);

            }

        }

    }

}

?>
