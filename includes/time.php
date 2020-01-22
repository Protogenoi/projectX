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

require_once(__DIR__ . '/../classes/access_user/access_user_class.php');
$page_protect = new Access_user;
$page_protect->access_page(filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS), "", 1);
$hello_name = ($page_protect->user_full_name != "") ? $page_protect->user_full_name : $page_protect->user;

$EXECUTE = filter_input(INPUT_GET, 'EXECUTE', FILTER_SANITIZE_SPECIAL_CHARS);

if (isset($EXECUTE) && $EXECUTE == '1') {

    require_once(__DIR__ . '/../includes/ADL_PDO_CON.php');
    require_once(__DIR__ . '/../includes/Access_Levels.php');

    if (!in_array($hello_name, $TIMELOCK_ACCESS)) {
        $TIMELOCK = date('H');

        if ($TIMELOCK >= '19' || $TIMELOCK < '08') {

            $USER_TRACKING_QRY = $pdo->prepare("INSERT INTO user_tracking
                    SET
                    user_tracking_id_fk=(SELECT id from users where login=:HELLO), user_tracking_url='NAVBAR_Logout', user_tracking_user=:USER
                    ON DUPLICATE KEY UPDATE
                    user_tracking_url='Access_Level_Logout'");
            $USER_TRACKING_QRY->bindParam(':HELLO', $hello_name, PDO::PARAM_STR);
            $USER_TRACKING_QRY->bindParam(':USER', $hello_name, PDO::PARAM_STR);
            $USER_TRACKING_QRY->execute();

            $FORCE_LOGOUT = 1;

        }
    }
}
