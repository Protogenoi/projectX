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

include($_SERVER['DOCUMENT_ROOT'] . "/classes/access_user/access_user_class.php");
$page_protect = new Access_user;
$page_protect->access_page(filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS), "", 3);
$hello_name = ($page_protect->user_full_name != "") ? $page_protect->user_full_name : $page_protect->user;

include('../../includes/adl_features.php');

if (isset($fferror)) {
    if ($fferror == '1') {

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

    }

}

include('../../includes/ADL_PDO_CON.php');

$life = filter_input(INPUT_GET, 'life', FILTER_SANITIZE_SPECIAL_CHARS);
$legacy = filter_input(INPUT_GET, 'legacy', FILTER_SANITIZE_SPECIAL_CHARS);

$message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS);
$recipient = filter_input(INPUT_POST, 'recipient', FILTER_SANITIZE_SPECIAL_CHARS);
$subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_SPECIAL_CHARS);


$cnquery = $pdo->prepare("select company_name from company_details limit 1");
$cnquery->execute() or die(print_r($query->errorInfo(), true));
$companydetailsq = $cnquery->fetch(PDO::FETCH_ASSOC);
$companynamere = $companydetailsq['company_name'];

if (isset($life)) {
    if ($life == 'y') {

        if (isset($companynamere)) {

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

            $keyfielddata = filter_input(INPUT_POST, 'keyfield', FILTER_SANITIZE_NUMBER_INT);
            $recipientdata = filter_input(INPUT_POST, 'recipient', FILTER_SANITIZE_SPECIAL_CHARS);
            $notetypedata = filter_input(INPUT_POST, 'note_type', FILTER_SANITIZE_SPECIAL_CHARS);
            $messagedata = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS);

            if ($companynamere == "Bluestone Protect") {

                $query = $pdo->prepare("INSERT INTO client_note set client_id=:clientid, client_name=:recipient, sent_by=:sentby, note_type=:note, message=:message ");
                $query->bindParam(':clientid', $keyfielddata, PDO::PARAM_INT);
                $query->bindParam(':sentby', $hello_name, PDO::PARAM_STR, 100);
                $query->bindParam(':recipient', $recipientdata, PDO::PARAM_STR, 500);
                $query->bindParam(':note', $notetypedata, PDO::PARAM_STR, 255);
                $query->bindParam(':message', $messagedata, PDO::PARAM_STR, 2500);
                $query->execute();

                require_once('../../resources/lib/PHPMailer_5.2.0/class.phpmailer.php');

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
                $mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
                $mail->AddAddress($email, $recipient);
                $mail->Body = $body;


                if (!$mail->Send()) {
                    echo "Mailer Error: " . $mail->ErrorInfo;
                } else {
                    echo "<br><br><h1>Message sent!</h1>";
                    echo "<br><br><a href='GenericEmail.php'>Send Another Email</h1></a>";
                }

                if (isset($fferror)) {
                    if ($fferror == '0') {
                        header('Location: ../../Life/ViewClient.php?emailsent&emailto=' . $email . '&search=' . $keyfielddata);
                        die;
                    }
                }
            }


        }

    }
}
?>
