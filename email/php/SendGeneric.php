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

require_once('../../resources/lib/PHPMailer_5.2.0/class.phpmailer.php');
include('../../includes/ADL_PDO_CON.php');

$query = $pdo->prepare("select email_signatures.sig, email_accounts.email, email_accounts.emailfrom, email_accounts.emailreply, email_accounts.emailbcc, email_accounts.emailsubject, email_accounts.smtp, email_accounts.smtpport, email_accounts.displayname, AES_DECRYPT(email_accounts.password, UNHEX(:key)) AS password from email_accounts LEFT JOIN email_signatures ON email_accounts.id = email_signatures.email_id where email_accounts.emailaccount='account1'");
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

$cnquery = $pdo->prepare("select company_name from company_details limit 1");
$cnquery->execute() or die(print_r($query->errorInfo(), true));
$companydetailsq = $cnquery->fetch(PDO::FETCH_ASSOC);

$companynamere = $companydetailsq['company_name'];

if ($companynamere == 'Bluestone Protect') {

    $target_dir = "../../uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

    if ($_FILES["fileToUpload"]["size"] > 700000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" && $imageFileType != "pdf") {
        $uploadOk = 0;
    }

    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $recipient = filter_input(INPUT_POST, 'recipient', FILTER_SANITIZE_SPECIAL_CHARS);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS);
    $subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_SPECIAL_CHARS);

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
#$mail->SMTPDebug  = 0;

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

    $mail->SetFrom("$emailfromdb", "$emaildisplaynamedb");
    $mail->AddReplyTo("$emailreplydb", "$emaildisplaynamedb");
    $mail->AddBCC("$emailbccdb", "$emaildisplaynamedb");
    $mail->Subject = $subject;
    $mail->IsHTML(true);
    $mail->AltBody = "To view the message, please use an HTML compatible email viewer!";
    $mail->AddAddress($email, $recipient);
    $mail->Body = $body;
    if (!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
        header('Location: ../GenericEmail.php?emailfailed');
        die;
    }

    header('Location: ../GenericEmail.php?emailsent&emailto=' . $email);
    die;

}
?>
