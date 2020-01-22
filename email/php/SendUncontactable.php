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
$page_protect->access_page(filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS), "", 3);
$hello_name = ($page_protect->user_full_name != "") ? $page_protect->user_full_name : $page_protect->user;

include('../../includes/Access_Levels.php');

if (!in_array($hello_name, $Level_3_Access, true)) {

    header('Location: ../../CRMmain.php?AccessDenied');
    die;

}

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

if (isset($companynamere)) {

    $EXECUTE = filter_input(INPUT_GET, 'EXECUTE', FILTER_SANITIZE_NUMBER_INT);
    $search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_NUMBER_INT);
    $policy = filter_input(INPUT_GET, 'policy', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS);
    $recipient = filter_input(INPUT_GET, 'recipient', FILTER_SANITIZE_SPECIAL_CHARS);
    $INSURER = filter_input(INPUT_GET, 'insurer', FILTER_SANITIZE_SPECIAL_CHARS);

    if ($companynamere == 'Bluestone Protect') {

        if (isset($hello_name)) {

            switch ($hello_name) {
                case "Michael":
                    $hello_name_full = "Michael Owen";
                    break;
                case "Jakob":
                    $hello_name_full = "Jakob Lloyd";
                    break;
                case "leighton":
                    $hello_name_full = "Leighton Morris";
                    break;
                case "Roxy":
                    $hello_name_full = "Roxanne Studholme";
                    break;
                case "Nicola":
                    $hello_name_full = "Nicola Griffiths";
                    break;
                case "Rhibayliss":
                    $hello_name_full = "Rhiannon Bayliss";
                    break;
                case "Amelia":
                    $hello_name_full = "Amelia Pike";
                    break;
                case "Abbiek":
                    $hello_name_full = "Abbie Kenyon";
                    break;
                case "carys":
                    $hello_name_full = "Carys Riley";
                    break;
                case "Matt":
                    $hello_name_full = "Matthew Jones";
                    break;
                case "Tina":
                    $hello_name_full = "Tina Dennis";
                    break;
                case "Nick":
                    $hello_name_full = "Nick Dennis";
                    break;
                case "Amy":
                    $hello_name_full = "Amy Clayfield";
                    break;
                case "Georgia":
                    $hello_name_full = "Georgia Davies";
                    break;
                case "Mike":
                    $hello_name_full = "Michael Lloyd";
                    break;
                default:
                    $hello_name_full = $hello_name;

            }

        }

        if (isset($EXECUTE)) {
            if ($EXECUTE == '1') {

                $subject = "The Review Bureau - Direct Debit";
                $sig = "<br>-- \n
<br>
<br>
<br>
$signat";

                $body = "<p>Dear $recipient,</p>
          <p>           
There is an issue with your $INSURER direct debit <strong>$policy</strong>. </p>

          <p>
We have tried contacting you on numerous occasions but have been unsuccessful, It is very important we speak to you.
          </p>
          <p>Please contact us on 0845 095 0041 or email us back with a preferred contact time and number for us to call you. Office hours are between Monday to Friday 10:00 - 18:30.</p>
          Many thanks,<br>
$hello_name_full<br>The Review Bureau
          </p>";

            }

            if ($EXECUTE == '2') {

                $subject = "The Review Bureau Life Insurance Application";
                $sig = "<br>-- \n
<br>
<br>
<br>
$signat";

                $body = "<p>Dear $recipient,</p>
          <p>           
There is an issue with your $INSURER life insurance application. </p>

          <p>
We have tried contacting you on numerous occasions but have been unsuccessful, It is very important we speak to you.
          </p>
          <p>Please contact us on 0845 095 0041 or email us back with a preferred contact time and number for us to call you. Office hours are between Monday to Friday 10:00 - 18:30.</p>
          Many thanks,<br>
$hello_name_full<br>The Review Bureau
          </p>";

            }

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
            $mail->SetFrom("$emailfromdb", "$emaildisplaynamedb");
            $mail->AddReplyTo("$emailreplydb", "$emaildisplaynamedb");
            $mail->Subject = $subject;
            $mail->IsHTML(true);
            $mail->AltBody = "To view the message, please use an HTML compatible email viewer!";
            $address = $email;
            $mail->AddAddress($address, $recipient);
            $mail->Body = $body;

            if (!$mail->Send()) {
                echo "Mailer Error: " . $mail->ErrorInfo;
                header('Location: ../../Life/ViewClient.php?emailfailed&search=' . $search);
                die;
            } else {

                $notetype = "Email Sent";
                $message = "Uncontactable email sent ($email)";
                $ref = "$recipient";


                $noteq = $pdo->prepare("INSERT into client_note set client_id=:id, note_type=:type, client_name=:ref, message=:message, sent_by=:sent");
                $noteq->bindParam(':id', $search, PDO::PARAM_STR);
                $noteq->bindParam(':sent', $hello_name, PDO::PARAM_STR);
                $noteq->bindParam(':type', $notetype, PDO::PARAM_STR);
                $noteq->bindParam(':message', $message, PDO::PARAM_STR);
                $noteq->bindParam(':ref', $ref, PDO::PARAM_STR);
                $noteq->execute() or die(print_r($noteq->errorInfo(), true));


                header('Location: ../../Life/ViewClient.php?emailsent&search=' . $search . '&emailto=' . $email);
                die;

            }
        }
    }

}
?>
