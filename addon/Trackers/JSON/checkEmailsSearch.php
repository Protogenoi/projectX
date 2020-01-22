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
 * Written by michael <michael@adl-crm.uk>, 28/02/19 10:17
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
 *  Twilio - https://github.com/twilio
 *  SendGrid - https://github.com/sendgrid
 */

require_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_SPECIAL_CHARS) . '/app/core/doc_root.php';


$USER = filter_input(INPUT_GET, 'USER', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$TOKEN = filter_input(INPUT_GET, 'TOKEN', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

if (isset($USER) && $TOKEN) {

    require_once(BASE_URL . '/classes/database_class.php');
    require_once(BASE_URL . '/class/login/login.php');

    $CHECK_USER_TOKEN = new UserActions($USER, $TOKEN);
    $CHECK_USER_TOKEN->CheckToken();
    $OUT = $CHECK_USER_TOKEN->CheckToken();

    if (isset($OUT['TOKEN_CHECK']) && $OUT['TOKEN_CHECK'] == 'Bad') {
        echo "BAD";
    }

    if (isset($OUT['TOKEN_CHECK']) && $OUT['TOKEN_CHECK'] == 'Good') {

        $hello_name = $USER;

        require_once(BASE_URL . '/includes/Access_Levels.php');
        require_once(BASE_URL . '/includes/ADL_PDO_CON.php');

        $EXECUTE = filter_input(INPUT_GET, 'EXECUTE', FILTER_SANITIZE_NUMBER_INT);

        if (isset($EXECUTE)) {

            if ($EXECUTE == 1) {

                $query = $pdo->prepare("SELECT sentDate, email, message, sentBy FROM closerEmails ORDER BY sentDate DESC");
                $query->execute();
                if ($query->rowCount() > 0) {

                    json_encode($results['aaData'] = $query->fetchAll(PDO::FETCH_ASSOC));

                    echo json_encode($results);

                }
            }

        }

    }
} else {

    header('Location: /../../../CRMmain.php');
    die;

}
