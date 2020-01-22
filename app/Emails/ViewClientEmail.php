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
$page_protect->access_page(filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS), "", 3);
$hello_name = ($page_protect->user_full_name != "") ? $page_protect->user_full_name : $page_protect->user;

$USER_TRACKING = 0;

require_once(__DIR__ . '/../../includes/adl_features.php');

require_once(__DIR__ . '/../../includes/time.php');

if (isset($FORCE_LOGOUT) && $FORCE_LOGOUT == 1) {
    $page_protect->log_out();
}

require_once(__DIR__ . '/../../includes/user_tracking.php');
require_once(__DIR__ . '/../../includes/Access_Levels.php');

require_once(__DIR__ . '/../../includes/ADL_PDO_CON.php');

if ($ffanalytics == '1') {
    require_once(__DIR__ . '/../../app/analyticstracking.php');
}

if (isset($fferror)) {
    if ($fferror == '0') {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }
}

require_once(__DIR__ . '/../../resources/lib/PHPMailer_5.2.0/class.phpmailer.php');

$life = filter_input(INPUT_GET, 'life', FILTER_SANITIZE_SPECIAL_CHARS);

$CID = filter_input(INPUT_POST, 'search', FILTER_SANITIZE_NUMBER_INT);
$message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS);
$recipient = filter_input(INPUT_POST, 'recipient', FILTER_SANITIZE_SPECIAL_CHARS);
$subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_SPECIAL_CHARS);

if (isset($life)) {
    if ($life == 'y') {

        $query = $pdo->prepare("select email_signatures.sig, email_accounts.email, email_accounts.emailfrom, email_accounts.emailreply, email_accounts.emailbcc, email_accounts.emailsubject, email_accounts.smtp, email_accounts.smtpport, email_accounts.displayname, AES_DECRYPT(email_accounts.password, UNHEX(:key)) AS password from email_accounts LEFT JOIN email_signatures ON email_accounts.id = email_signatures.email_id where email_accounts.emailaccount='account3'");
        $query->bindParam(':key', $EN_KEY, PDO::PARAM_STR);
        $query->execute() or die(print_r($query->errorInfo(), true));
        $queryr = $query->fetch(PDO::FETCH_ASSOC);

        $emailfromdb = $queryr['emailfrom'];
        $emailbccdb = $queryr['emailbcc'];
        $emailreplydb = $queryr['emailreply'];
        $emailsubjectdb = $queryr['emailsubject'];
        $SMTP_HOST = $queryr['smtp'];
        $SMTP_PORT = $queryr['smtpport'];
        $emaildisplaynamedb = $queryr['displayname'];
        $SMTP_PASS = $queryr['password'];
        $SMTP_USER = $queryr['email'];
        $signat = html_entity_decode($queryr['sig']);

        $target_dir = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_SPECIAL_CHARS) . "/uploads/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

        if ($_FILES["fileToUpload"]["size"] > 700000) {
            $uploadOk = 0;

        }

        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" && $imageFileType != "pdf") {
            $uploadOk = 0;

        }

        $sig = "<br>-- \n
<br>
<br>
<br>
$signat";

        $body = html_entity_decode($message);
        $body .= $sig;

        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->CharSet = 'UTF-8';
        $mail->Host = "$SMTP_HOST";
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "ssl";
        $mail->Port = $SMTP_PORT;
        $mail->Username = "$SMTP_USER";
        $mail->Password = "$SMTP_PASS";

        if (isset($_FILES["fileToUpload"]) &&
            $_FILES["fileToUpload"]["error"] == UPLOAD_ERR_OK) {
            $mail->AddAttachment($_FILES["fileToUpload"]["tmp_name"],
                $_FILES["fileToUpload"]["name"]);
        }

        if (isset($_FILES["fileToUpload2"]) &&
            $_FILES["fileToUpload2"]["error"] == UPLOAD_ERR_OK) {
            $mail->AddAttachment($_FILES["fileToUpload2"]["tmp_name"],
                $_FILES["fileToUpload2"]["name"]);
        }

        if (isset($_FILES["fileToUpload3"]) &&
            $_FILES["fileToUpload3"]["error"] == UPLOAD_ERR_OK) {
            $mail->AddAttachment($_FILES["fileToUpload3"]["tmp_name"],
                $_FILES["fileToUpload3"]["name"]);
        }

        if (isset($_FILES["fileToUpload4"]) &&
            $_FILES["fileToUpload4"]["error"] == UPLOAD_ERR_OK) {
            $mail->AddAttachment($_FILES["fileToUpload4"]["tmp_name"],
                $_FILES["fileToUpload4"]["name"]);
        }

        if (isset($_FILES["fileToUpload5"]) &&
            $_FILES["fileToUpload5"]["error"] == UPLOAD_ERR_OK) {
            $mail->AddAttachment($_FILES["fileToUpload5"]["tmp_name"],
                $_FILES["fileToUpload5"]["name"]);
        }

        if (isset($_FILES["fileToUpload6"]) &&
            $_FILES["fileToUpload6"]["error"] == UPLOAD_ERR_OK) {
            $mail->AddAttachment($_FILES["fileToUpload6"]["tmp_name"],
                $_FILES["fileToUpload6"]["name"]);
        }

        $mail->SetFrom("$emailfromdb", "$emaildisplaynamedb");

        $mail->AddReplyTo("$emailreplydb", "$emaildisplaynamedb");
        $mail->Subject = $subject;
        $mail->IsHTML(true);
        $mail->AltBody = "To view the message, please use an HTML compatible email viewer!";
        $mail->AddAddress($email, $recipient);
        $mail->Body = $body;


        if (!$mail->Send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;

            $NEW_MSG = "Custom email failed ($email - $message)";

            $noteq = $pdo->prepare("INSERT into client_note set client_id=:CID, note_type='Email Failed', client_name=:ref, message=:message, sent_by=:sent");
            $noteq->bindParam(':CID', $CID, PDO::PARAM_STR);
            $noteq->bindParam(':sent', $hello_name, PDO::PARAM_STR);
            $noteq->bindParam(':message', $NEW_MSG, PDO::PARAM_STR);
            $noteq->bindParam(':ref', $recipient, PDO::PARAM_STR);
            $noteq->execute() or die(print_r($noteq->errorInfo(), true));

            header('Location: /../../../app/Client.php?search=' . $CID . '&EMAIL_SENT=0&CLIENT_EMAIL=Send policy number&EMAIL_SENT_TO=' . $email);
            die;

        } else {

            $NEW_MSG = "Custom email sent ($email  - $message)";

            $noteq = $pdo->prepare("INSERT into client_note set client_id=:CID, note_type='Email Sent', client_name=:ref, message=:message, sent_by=:sent");
            $noteq->bindParam(':CID', $CID, PDO::PARAM_STR);
            $noteq->bindParam(':sent', $hello_name, PDO::PARAM_STR);
            $noteq->bindParam(':message', $NEW_MSG, PDO::PARAM_STR);
            $noteq->bindParam(':ref', $recipient, PDO::PARAM_STR);
            $noteq->execute() or die(print_r($noteq->errorInfo(), true));

            header('Location: /../../../app/Client.php?search=' . $CID . '&EMAIL_SENT=1&CLIENT_EMAIL=Custom Email&EMAIL_SENT_TO=' . $email);
            die;

        }

    }
}
?>
