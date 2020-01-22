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

$USER = filter_input(INPUT_GET, 'USER', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$TOKEN = filter_input(INPUT_GET, 'TOKEN', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

if (isset($USER) && $TOKEN) {

    require_once(__DIR__ . '/../../classes/database_class.php');
    require_once(__DIR__ . '/../../class/login/login.php');

    $CHECK_USER_TOKEN = new UserActions($USER, $TOKEN);
    $CHECK_USER_TOKEN->CheckToken();
    $OUT = $CHECK_USER_TOKEN->CheckToken();

    if (isset($OUT['TOKEN_CHECK']) && $OUT['TOKEN_CHECK'] == 'Bad') {
        echo "BAD";
    }

    if (isset($OUT['TOKEN_CHECK']) && $OUT['TOKEN_CHECK'] == 'Good') {

        $hello_name = $USER;
        require_once(__DIR__ . '/../../includes/Access_Levels.php');


        $EXECUTE = filter_input(INPUT_GET, 'EXECUTE', FILTER_SANITIZE_NUMBER_INT);

        if (isset($EXECUTE)) {
            require_once(__DIR__ . '/../../includes/ADL_PDO_CON.php');
            if ($EXECUTE == '1') {

                $query = $pdo->prepare("SELECT 
    client_details.company,
    client_details.phone_number,
    client_details.submitted_date,
    client_details.client_id,
    CONCAT(client_details.title, ' ', client_details.first_name, ' ', client_details.last_name) AS Name,
    CONCAT(client_details.title2,
            ' ',
            client_details.first_name2,
            ' ',
            client_details.last_name2) AS Name2,
    client_details.post_code
FROM
    client_note
    JOIN
    client_details
    ON client_details.client_id=client_note.client_id
    WHERE client_note.client_name='Compliant'
ORDER BY client_details.client_id DESC");
                $query->execute() or die(print_r($query->errorInfo(), true));
                json_encode($results['aaData'] = $query->fetchAll(PDO::FETCH_ASSOC));

                echo json_encode($results);

            }

        }

    }

} else {

    header('Location: /../../../CRMmain.php');
    die;

}
