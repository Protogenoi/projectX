<?php
/** @noinspection PhpIncludeInspection */

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
 * Written by michael <michael@adl-crm.uk>, 18/02/19 10:15
 *
 * ADL CRM makes use of the following third party open sourced software/tools:
 *  Composer - https://getcomposer.org/doc/
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
 *  Ideal Postcodes - https://ideal-postcodes.co.uk/documentation
 *  Chart.js - https://github.com/chartjs/Chart.js
 */

require_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_SPECIAL_CHARS) . '/app/core/doc_root.php';

require_once(BASE_URL . '/classes/access_user/access_user_class.php');
$page_protect = new Access_user;
$page_protect->access_page(filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS), "", 2);
$hello_name = ($page_protect->user_full_name != "") ? $page_protect->user_full_name : $page_protect->user;

$EXECUTE = filter_input(INPUT_GET, 'EXECUTE', FILTER_SANITIZE_NUMBER_INT);


require_once(BASE_URL . '/resources/lib/PHPMailer_5.2.0/class.phpmailer.php');
require_once(BASE_URL . '/includes/ADL_PDO_CON.php');
require_once(BASE_URL . '/includes/Access_Levels.php');
require_once(BASE_URL . '/includes/config.php');

$query = $pdo->prepare("select email_signatures.sig, email_accounts.email, email_accounts.emailfrom, email_accounts.emailreply, email_accounts.emailbcc, email_accounts.emailsubject, email_accounts.smtp, email_accounts.smtpport, email_accounts.displayname, AES_DECRYPT(email_accounts.password, UNHEX(:key)) AS password from email_accounts LEFT JOIN email_signatures ON email_accounts.id = email_signatures.email_id where email_accounts.emailaccount='account4'");
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

if ($COMPANY_ENTITY == 'Project X') {

    $target_dir = "../../uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

// Check file size
    if ($_FILES["fileToUpload"]["size"] > 700000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }
// Allow certain file formats
    if (isset($imageFileType)) {
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" && $imageFileType != "pdf") {
            echo "Sorry, only JPG, JPEG, PNG, PDF & GIF files are allowed.";
            $uploadOk = 0;
        }
    }

    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $recipient = filter_input(INPUT_POST, 'recipient', FILTER_SANITIZE_STRING);
    $subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);

    $subject = $subject . " | " . $hello_name;

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
    $mail->SMTPDebug = 0;

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
    $mail->AddBCC("$emailbccdb", "$emaildisplaynamedb");
    $mail->Subject = $subject;
    $mail->IsHTML(true);

    $mail->AltBody = "To view the message, please use an HTML compatible email viewer!";

    $address = $email;
    $mail->AddAddress($address, $recipient);
    $mail->Body = $body;

    if (!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
        // header('Location: ../InternalEmail.php?emailfailed'); die;
    } else {
        header('Location: ../InternalEmail.php?emailsent&emailto=' . $email);
        die;
    }

}
