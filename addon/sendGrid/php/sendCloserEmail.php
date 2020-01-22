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
 * Written by michael <michael@adl-crm.uk>, 11/02/19 10:25
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

use SendGrid\Mail\Attachment;
use SendGrid\Mail\Mail;

require_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_SPECIAL_CHARS) . '/app/core/doc_root.php';

require_once(BASE_URL . '/classes/access_user/access_user_class.php');
$page_protect = new Access_user;
$page_protect->access_page(filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS), "", 2);
$hello_name = ($page_protect->user_full_name != "") ? $page_protect->user_full_name : $page_protect->user;

$USER_TRACKING = 0;

require_once(BASE_URL . '/includes/user_tracking.php');
require_once(BASE_URL . '/includes/time.php');

if (isset($FORCE_LOGOUT) && $FORCE_LOGOUT == 1) {
    $page_protect->log_out();
}

require_once(BASE_URL . '/includes/adl_features.php');
require_once(BASE_URL . '/includes/Access_Levels.php');

if (isset($ffanalytics) && $ffanalytics == '1') {
    require_once(BASE_URL . '/app/analyticstracking.php');
}

if (isset($fferror) && $fferror == 0) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

$EXECUTE = filter_input(INPUT_GET, 'EXECUTE', FILTER_SANITIZE_NUMBER_INT);

if (isset($EXECUTE)) {

    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS);
    $emailTo = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS);
    $clientName = filter_input(INPUT_POST, 'recipient', FILTER_SANITIZE_SPECIAL_CHARS);
    $emailSubject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_SPECIAL_CHARS);
    $insurer = filter_input(INPUT_POST, 'insurer', FILTER_SANITIZE_SPECIAL_CHARS);
    $cbClient = filter_input(INPUT_GET, 'cbClient', FILTER_SANITIZE_SPECIAL_CHARS);
    $emailMessage = html_entity_decode($message);


    require_once(BASE_URL . '/classes/database_class.php');
    require_once(BASE_URL . '/includes/ADL_PDO_CON.php');
    require_once(BASE_URL . '/resources/lib/vendor/autoload.php');
    require_once(BASE_URL . '/includes/config.php');

    if ($EXECUTE == 2) {

        require_once(BASE_URL . '/class/emailTemplates.php');
        echo "<br>MESSAGE EMAIL $emailMessage";
        $getEmailTemplate = new ADL\emailTemplates();
        $getEmailTemplate->setEmail($emailTo);
        $getEmailTemplate->setClientName($clientName);
        $getEmailTemplate->setEmailSubject($message);
        $getEmailTemplate->setCompanyName($COMPANY_ENTITY);
        $getEmailTemplate->setCompanyTel('03333 446 287');
        $getEmailTemplate->setInsurer($insurer);
        $getEmailTemplate->setUser($hello_name);
        $emailMessage = $getEmailTemplate->getEmailMessage();

        $email = new Mail();
        $email->setFrom("info5@renew-life.com", 'Project X Quotation');
        $email->setSubject($message);
        $email->setFooter(true, 'TEXT', '<br>--<br><b>Kind Regards,<br><br>' . $hello_name . '<br><br>Project X</b><center class="wrapper" data-link-color="#1188E6" data-body-style="font-size: 14px; font-family: arial; color: #000000; background-color: #ffffff;">
      <div class="webkit">
        <table cellpadding="0" cellspacing="0" border="0" width="100%" class="wrapper" bgcolor="#ffffff">
          <tr>
            <td valign="top" bgcolor="#ffffff" width="100%">
              <table width="100%" role="content-container" class="outer" align="center" cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td width="100%">
                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                      <tr>
                        <td>
                          <!--[if mso]>
                          <center>
                          <table><tr><td width="600">
                          <![endif]-->
                          <table width="100%" cellpadding="0" cellspacing="0" border="0" style="width: 100%; max-width:600px;" align="center">
                            <tr>
                              <td role="modules-container" style="padding: 0px 0px 0px 0px; color: #000000; text-align: left;" bgcolor="#ffffff" width="100%" align="left">
                                
    <table class="module preheader preheader-hide" role="module" data-type="preheader" border="0" cellpadding="0" cellspacing="0" width="100%"
           style="display: none !important; mso-hide: all; visibility: hidden; opacity: 0; color: transparent; height: 0; width: 0;">
      <tr>
        <td role="module-content">
          <p></p>
        </td>
      </tr>
    </table>
  
    <table class="module"
           role="module"
           data-type="divider"
           border="0"
           cellpadding="0"
           cellspacing="0"
           width="100%"
           style="table-layout: fixed;">
      <tr>
        <td style="padding:0px 0px 0px 0px;"
            role="module-content"
            height="100%"
            valign="top"
            bgcolor="">
          <table border="0"
                 cellpadding="0"
                 cellspacing="0"
                 align="center"
                 width="100%"
                 height="10px"
                 style="line-height:10px; font-size:10px;">
            <tr>
              <td
                style="padding: 0px 0px 10px 0px;"
                bgcolor="#000000"></td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  
    <table class="wrapper" role="module" data-type="image" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed;">
      <tr>
        <td style="font-size:6px;line-height:10px;padding:0px 0px 0px 0px;" valign="top" align="center">
          <img class="max-width" border="0" style="display:block;color:#000000;text-decoration:none;font-family:Helvetica, arial, sans-serif;font-size:16px;" src="https://marketing-image-production.s3.amazonaws.com/uploads/536b3d11df1564b8f742db44c6bdc3da50b0b12db2e818632168a36fa49f61f73bdd76ea0d53067c19d0d04aae3142eba6facf05bf62a98b6a7afa6de769e507.png" alt="" width="216" height="65">
        </td>
      </tr>
    </table>
  
    <table class="module" role="module" data-type="text" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed;">
      <tr>
        <td style="padding:18px 0px 18px 0px;line-height:22px;text-align:inherit;"
            height="100%"
            valign="top"
            bgcolor="">
            <div><span lang="EN-US" style="font-style: normal; font-variant-caps: normal; font-size: 10pt; font-family: Arial, sans-serif; color: rgb(100, 100, 100);">Postal/Registered Address:&nbsp;</span><span lang="EN-US" style="font-style: normal; font-variant-caps: normal; font-size: 10pt; font-family: Arial, sans-serif; color: rgb(131, 190, 40);">6 Riverside Studios, Amethyst Road, Newcastle Business Park, Newcastle Upon Tyne, NE4 7YL.</span><span lang="EN-US" style="font-style: normal; font-variant-caps: normal; font-size: 10pt; font-family: Arial, sans-serif; color: rgb(100, 100, 100);">&nbsp;Project X a Trading Style of Renew Financial Management Limited which is registered as a company in England and Wales, registration number 10602219. Renew Financial Management Limited is authorised and regulated by the Financial Conduct Authority FCA Number 784184</span></div>
        </td>
      </tr>
    </table>
  
    <table class="wrapper" role="module" data-type="image" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed;">
      <tr>
        <td style="font-size:6px;line-height:10px;padding:0px 0px 0px 0px;" valign="top" align="center">
          <img class="max-width" border="0" style="display:block;color:#000000;text-decoration:none;font-family:Helvetica, arial, sans-serif;font-size:16px;max-width:100% !important;width:100%;height:auto !important;" src="https://marketing-image-production.s3.amazonaws.com/uploads/3eb89d179038a4b08c042ab8da304928a2cf230e569c0550cbf46ea68dddba6312cde1e1cbd9ca3bc6059c821dae9b9c1813e91600cde41c478a9d653cfbbce4.png" alt="" width="600">
        </td>
      </tr>
    </table>
                              </td>
                            </tr>
                          </table>
                          <!--[if mso]>
                          </td></tr></table>
                          </center>
                          <![endif]-->
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </div>
    </center>');
        $email->addCategory('Project X Quotation');
        $email->addTo($emailTo, $clientName);
        $email->addContent("text/plain", $emailMessage);
        $email->addContent(
            "text/html", $emailMessage
        );

        $att1 = new Attachment();
        $att1->setContent(file_get_contents($_FILES["fileToUpload"]["tmp_name"], $_FILES["fileToUpload"]["name"]));
        $att1->setType("application/octet-stream");
        $att1->setFilename(basename($_FILES["fileToUpload"]["name"]));
        $att1->setDisposition("attachment");
        $email->addAttachment($att1);


    }

    $sendgrid = new SendGrid($sendGridAPI);

    $NEW_MSG = "$emailTo - $emailMessage";

    try {
        $response = $sendgrid->send($email);
        print $response->statusCode() . "\n";
        print_r($response->headers());
        print $response->body() . "\n";

        $query = $pdo->prepare("INSERT into closerEmails SET sentBy=:sent, email=:email, message=:message");
        $query->bindParam(':sent', $hello_name, PDO::PARAM_STR);
        $query->bindParam(':email', $emailTo, PDO::PARAM_STR);
        $query->bindParam(':message', $emailMessage, PDO::PARAM_STR);
        $query->execute();

    } catch (Exception $e) {
        echo 'Caught exception: ' . $e->getMessage() . "\n";

        $message = 'Email failed';

        $query = $pdo->prepare("INSERT into closerEmails SET sentBy=:sent, email=:email, message=:message");
        $query->bindParam(':sent', $hello_name, PDO::PARAM_STR);
        $query->bindParam(':email', $emailTo, PDO::PARAM_STR);
        $query->bindParam(':message', $NEW_MSG, PDO::PARAM_STR);
        $query->execute();

    }

    $toastrTitle = 'Email sent!';
    $toastrMessage = 'Sent to: ' . $emailTo;
    $toastrResponse = 1;

    if (isset($cbClient) && $cbClient == 1) {

        header('Location: /../../../addon/Trackers/client.php?search=' . $CID . '&toastrResponse=' . $toastrResponse . '&toastrMessage=' . $toastrMessage . '&toastrTitle=' . $toastrTitle);
        die;

    } elseif (isset($cbClient) && $cbClient == 2) {

        header('Location: /../../../addon/Trackers/Tracker.php?query=CloserTrackers&toastrResponse=' . $toastrResponse . '&toastrMessage=' . $toastrMessage . '&toastrTitle=' . $toastrTitle);
        die;

    } else {

        header('Location: /../../../app/Client.php?search=' . $CID . '&toastrResponse=' . $toastrResponse . '&toastrMessage=' . $toastrMessage . '&toastrTitle=' . $toastrTitle);
        die;

    }

} else {

    throw new Exception('Page accessed incorrectly');
}
