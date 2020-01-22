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

require_once(__DIR__ . '/../../classes/access_user/access_user_class.php');
$page_protect = new Access_user;
$page_protect->access_page(filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS), "", 1);
$hello_name = ($page_protect->user_full_name != "") ? $page_protect->user_full_name : $page_protect->user;

require_once(__DIR__ . '/../../resources/lib/PHPMailer_5.2.0/class.phpmailer.php');
require_once(__DIR__ . '/../../includes/adl_features.php');
require_once(__DIR__ . '/../../includes/Access_Levels.php');
require_once(__DIR__ . '/../../includes/adlfunctions.php');
require_once(__DIR__ . '/../../includes/ADL_PDO_CON.php');

if ($ffanalytics == '1') {
    require_once(__DIR__ . '/../../app/analyticstracking.php');
}

if (isset($fferror)) {
    if ($fferror == '1') {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }
}

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


$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$recipient = filter_input(INPUT_POST, 'recipient', FILTER_SANITIZE_SPECIAL_CHARS);

if (empty($email)) {
    header('Location: ../../email/KeyFactsEmail.php?RETURN=NO_EMAIL_ADDRESS');
    die;
}

if ($COMPANY_ENTITY == 'Project X') {
    $EMAIL_IDD = "idd@firstprioritygroup.co.uk";

    $target_dir = "../../uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

    if ($_FILES["fileToUpload"]["size"] > 700000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" && $imageFileType != "pdf") {
        echo "Sorry, only JPG, JPEG, PNG, PDF & GIF files are allowed.";
        $uploadOk = 0;
    }

    $message = "<img src='cid:KeyFacts'>";
    $sig = "<br>-- \n
<br>
<br>
<br>
$signat";

    $body = $message;
    $body .= $sig;
    $mail = new PHPMailer();
    $mail->IsSMTP();
#$mail->SMTPDebug  = 2;
    $mail->CharSet = 'UTF-8';
    $mail->Host = "$SMTP_HOST";
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = "ssl";
    $mail->Port = $SMTP_PORT;
    $mail->Username = "$SMTP_USER";
    $mail->Password = "$SMTP_PASS";

    $mail->AddEmbeddedImage('../../img/Key Facts - Project X.png', 'KeyFacts');
    $mail->AddEmbeddedImage('../../img/fpg_logo.png', 'logo');

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
    $mail->AddCC($EMAIL_IDD, $emaildisplaynamedb);

    $mail->Subject = "$emailsubjectdb";
    $mail->IsHTML(true);
    $mail->AltBody = "To view the message, please use an HTML compatible email viewer!";
    $mail->AddAddress($email, $recipient);

    $mail->Body = $body;

    if (!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;

        header('Location: ../../email/KeyFactsEmail.php?RETURN=MAILER_ERROR');
        die;

    } else {

        $INSERT = $pdo->prepare("INSERT INTO keyfactsemail set keyfactsemail_email=:email, keyfactsemail_added_by=:hello");
        $INSERT->bindParam(':email', $email, PDO::PARAM_STR);
        $INSERT->bindParam(':hello', $hello_name, PDO::PARAM_STR);
        $INSERT->execute() or die(print_r($INSERT->errorInfo(), true));

        header('Location: ../../email/KeyFactsEmail.php?emailsent&emailto=' . $email);
        die;

    }

}

header('Location: ../../email/KeyFactsEmail.php?emailfailed');
die;
?>
