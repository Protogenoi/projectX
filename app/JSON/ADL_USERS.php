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

require_once(__DIR__ . '/../../classes/access_user/access_user_class.php');
$page_protect = new Access_user;
$page_protect->access_page(filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS), "", 3);
$hello_name = ($page_protect->user_full_name != "") ? $page_protect->user_full_name : $page_protect->user;

require_once(__DIR__ . '/../../includes/Access_Levels.php');

$EXECUTE = filter_input(INPUT_GET, 'EXECUTE', FILTER_SANITIZE_NUMBER_INT);

if (isset($EXECUTE)) {
    if ($EXECUTE == '1') {
        require_once(__DIR__ . '/../../includes/Access_Levels.php');


        if (in_array($hello_name, $COM_LVL_10_ACCESS, true)) {

            $query = $pdo->prepare("SELECT login AS FULL_NAME from users WHERE active='y' AND extra_info='User' ORDER BY login");
            $query->execute() or die(print_r($query->errorInfo(), true));
            json_encode($results = $query->fetchAll(PDO::FETCH_ASSOC));

        } else {
            $query = $pdo->prepare("SELECT login AS FULL_NAME from users WHERE active='y' AND extra_info='User' ORDER BY login");
            $query->execute() or die(print_r($query->errorInfo(), true));
            json_encode($results = $query->fetchAll(PDO::FETCH_ASSOC));

        }

        echo json_encode($results);

    }

}
