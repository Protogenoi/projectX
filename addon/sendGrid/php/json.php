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
 * Written by michael <michael@adl-crm.uk>, 12/02/19 12:51
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
 *
 */

$EXECUTE = filter_input(INPUT_GET, 'EXECUTE', FILTER_SANITIZE_NUMBER_INT);

if (isset($EXECUTE) && $EXECUTE == 1) {

    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        throw new Exception('Request method must be POST!');
    }

    $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

    if (strcasecmp($contentType, 'application/json;charset=utf-8') != 0) {
        $content = trim(file_get_contents("php://input"));
        $decoded = json_decode($content, true);
    } elseif (strcasecmp($contentType, 'application/json; charset=utf-8') != 0) {
        $content = trim(file_get_contents("php://input"));
        $decoded = json_decode($content, true);
    } elseif (strcasecmp($contentType, 'application/json;') != 0) {
        $content = trim(file_get_contents("php://input"));
        $decoded = json_decode($content, true);
    } else {
        error_log(print_r($_SERVER["CONTENT_TYPE"], true));
        error_log(print_r($decoded, true));
        throw new Exception('Content type must be: application/json;');
    }

    $content = trim(file_get_contents("php://input"));

    $decoded = json_decode($content, true);

    if (!is_array($decoded)) {
        error_log(print_r($decoded, true));
        throw new Exception('Received content contained invalid JSON!');
    } else {

        require_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT',
                FILTER_SANITIZE_SPECIAL_CHARS) . '/app/core/doc_root.php';

        require_once(BASE_URL . '/classes/database_class.php');
        require_once(BASE_URL . '/includes/ADL_PDO_CON.php');

        foreach ($decoded as $decodedJSON) {

            $database = new Database();
            $database->query("SELECT client_id FROM client_details WHERE email =:EMAIL");
            $database->bind(':EMAIL', $decodedJSON['email']);
            $database->execute();
            $data2 = $database->single();

            if ($database->rowCount() >= 1) {

                $TYPE = "Response: " . $decodedJSON['event'];
                $CID = $data2['client_id'];
                $NEW_MESSAGE = 'Email: ' . implode(',', $decodedJSON['category']);

                $INSERT = $pdo->prepare("INSERT INTO client_note set client_id=:CID, client_name='ADL Alert', sent_by='SendGrid', note_type=:TYPE, message=:MSG");
                $INSERT->bindParam(':CID', $CID, PDO::PARAM_INT);
                $INSERT->bindParam(':MSG', $NEW_MESSAGE, PDO::PARAM_STR);
                $INSERT->bindParam(':TYPE', $TYPE, PDO::PARAM_STR);
                $INSERT->execute();

                //   throw new Exception('CLIENT FOUND FOR:' . $decodedJSON['email']);

            } else {

                $database = new Database();
                $database->query("SELECT client_id FROM potential_clients WHERE email =:EMAIL");
                $database->bind(':EMAIL', $decodedJSON['email']);
                $database->execute();
                $data2 = $database->single();
                if ($database->rowCount() >= 1) {

                    $TYPE = "Response: " . $decodedJSON['event'];
                    $CID = $data2['client_id'];
                    $NEW_MESSAGE = 'Email: ' . implode(',', $decodedJSON['category']);

                    $INSERT = $pdo->prepare("INSERT INTO potentialClientNote set client_id=:CID, client_name='ADL Alert', sent_by='SendGrid', note_type=:TYPE, message=:MSG");
                    $INSERT->bindParam(':CID', $CID, PDO::PARAM_INT);
                    $INSERT->bindParam(':MSG', $NEW_MESSAGE, PDO::PARAM_STR);
                    $INSERT->bindParam(':TYPE', $TYPE, PDO::PARAM_STR);
                    $INSERT->execute();
                } else {

                    $database = new Database();
                    $database->query("SELECT email FROM closerEmails WHERE email =:EMAIL");
                    $database->bind(':EMAIL', $decodedJSON['email']);
                    $database->execute();
                    $data2 = $database->single();
                    if ($database->rowCount() >= 1) {

                        $email = $data2['email'];

                        $TYPE = "Response: " . $decodedJSON['event'];
                        $NEW_MESSAGE = "$TYPE: " . implode(',',
                                $decodedJSON['category']);
                        $sentBy = 'SendGrid';

                        $query = $pdo->prepare("INSERT into closerEmails SET sentBy=:sent, email=:email, message=:message");
                        $query->bindParam(':sent', $sentBy, PDO::PARAM_STR);
                        $query->bindParam(':email', $decodedJSON['email'], PDO::PARAM_STR);
                        $query->bindParam(':message', $NEW_MESSAGE, PDO::PARAM_STR);
                        $query->execute();
                    }
                }

            }

            /*            if (empty($CID)) {
                            throw new Exception('CLIENT NOT FOUND FOR:' . $decodedJSON['email']);
                        } elseif (!empty($email)) {
                            throw new Exception('EMAIL NOT FOUND FOR:' . $decodedJSON['email']);
                        }*/


        }

    }

} else {
    throw new Exception('EXECUTE GET NOT SET');
}
