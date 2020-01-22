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
 * Written by michael <michael@adl-crm.uk>, 18/02/19 10:15
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

$HTTP_PROTOCOL_CHK = ((!empty(filter_input(INPUT_SERVER, 'HTTPS',
            FILTER_SANITIZE_SPECIAL_CHARS)) && filter_input(INPUT_SERVER, 'HTTPS',
            FILTER_SANITIZE_SPECIAL_CHARS) != 'off') || filter_input(INPUT_SERVER, 'SERVER_PORT',
        FILTER_SANITIZE_NUMBER_INT) == 443) ? "https://" : "http://";
$USER_TRACKING_GRAB_URL = $HTTP_PROTOCOL_CHK . filter_input(INPUT_SERVER, 'HTTP_HOST',
        FILTER_SANITIZE_SPECIAL_CHARS) . filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_SPECIAL_CHARS);

require_once(BASE_URL . '/includes/ADL_PDO_CON.php');
require_once(BASE_URL . '/includes/Access_Levels.php');

$SMS_QRY = $pdo->prepare("SELECT twilio_account_sid, AES_DECRYPT(twilio_account_token, UNHEX(:key)) AS twilio_account_token FROM twilio_account");
$SMS_QRY->bindParam(':key', $EN_KEY, PDO::PARAM_STR, 500);
$SMS_QRY->execute() or die(print_r($SMS_QRY->errorInfo(), true));
$SMS_RESULT = $SMS_QRY->fetch(PDO::FETCH_ASSOC);

$SID = $SMS_RESULT['twilio_account_sid'];
$SMS_TOKEN = $SMS_RESULT['twilio_account_token'];

use Twilio\Rest\Client;

require_once(BASE_URL . '/resources/lib/vendor/autoload.php');

function getRealIpAddr()
{
    if (!empty(filter_input(INPUT_SERVER, 'HTTP_CLIENT_IP', FILTER_SANITIZE_SPECIAL_CHARS))) {
        $ip = filter_input(INPUT_SERVER, 'HTTP_CLIENT_IP', FILTER_SANITIZE_SPECIAL_CHARS);
    } elseif (!empty(filter_input(INPUT_SERVER, 'HTTP_X_FORWARDED_FOR', FILTER_SANITIZE_SPECIAL_CHARS))) {
        $ip = filter_input(INPUT_SERVER, 'HTTP_X_FORWARDED_FOR', FILTER_SANITIZE_SPECIAL_CHARS);
    } else {
        $ip = filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_SANITIZE_SPECIAL_CHARS);
    }
    return $ip;
}

getRealIpAddr();
$TRACKED_IP = getRealIpAddr();

if (!in_array($TRACKED_IP, $allowedIPAccess, true)) {

    require_once(BASE_URL . '/classes/database_class.php');
    $database = new Database();
    $database->beginTransaction();

    $database->query("SELECT user_tracking_user FROM user_tracking WHERE user_tracking_user=:HELLO AND DATE(user_tracking_date)>=CURDATE() AND INET6_NTOA(user_tracking_ip)=:IP");
    $database->bind(':HELLO', $hello_name);
    $database->bind(':IP', $TRACKED_IP);
    $database->execute();
    $row = $database->single();

    $database->endTransaction();

    if ($database->rowCount() <= 0) {

        $client = new Client($SID, $SMS_TOKEN);

        $MOB_ARRAY = array("07495704872", "07917886451");
        $MOB_MSG = "ADL $hello_name accessed from IP $TRACKED_IP!";
        foreach ($MOB_ARRAY as $MESS_TO) {

            $client->messages->create(
                $MESS_TO,
                array(
                    'from' => '+441792720348',
                    'body' => "$MOB_MSG"
                )
            );

        }

    }

}

$USER_TRACKING_QRY = $pdo->prepare("INSERT INTO user_tracking
                    SET
                    user_tracking_id_fk=(SELECT id from users where login=:HELLO), user_tracking_url=:URL, user_tracking_user=:USER, user_tracking_ip=INET6_ATON(:IP)
                    ON DUPLICATE KEY UPDATE
                    user_tracking_url=:URL2,
                    user_tracking_ip=INET6_ATON(:IP2)");
$USER_TRACKING_QRY->bindParam(':HELLO', $hello_name, PDO::PARAM_STR);
$USER_TRACKING_QRY->bindParam(':USER', $hello_name, PDO::PARAM_STR);
$USER_TRACKING_QRY->bindParam(':URL', $USER_TRACKING_GRAB_URL, PDO::PARAM_STR);
$USER_TRACKING_QRY->bindParam(':URL2', $USER_TRACKING_GRAB_URL, PDO::PARAM_STR);
$USER_TRACKING_QRY->bindParam(':IP', $TRACKED_IP, PDO::PARAM_STR);
$USER_TRACKING_QRY->bindParam(':IP2', $TRACKED_IP, PDO::PARAM_STR);
$USER_TRACKING_QRY->execute();

$USER_HISTORY_TRK = $pdo->prepare("INSERT INTO tracking_history SET tracking_history_id_fk=(SELECT id from users where login=:HELLO), tracking_history_url=:URL, tracking_history_user=:USER, tracking_history_ip=INET6_ATON(:IP)");
$USER_HISTORY_TRK->bindParam(':HELLO', $hello_name, PDO::PARAM_STR);
$USER_HISTORY_TRK->bindParam(':USER', $hello_name, PDO::PARAM_STR);
$USER_HISTORY_TRK->bindParam(':URL', $USER_TRACKING_GRAB_URL, PDO::PARAM_STR);
$USER_HISTORY_TRK->bindParam(':IP', $TRACKED_IP, PDO::PARAM_STR);
$USER_HISTORY_TRK->execute();

if ($USER_TRACKING == '1') {

    $USER_TRACKING_CHK = $pdo->prepare("SELECT user_tracking_user, user_tracking_url FROM user_tracking WHERE user_tracking_url like :URL AND user_tracking_user!=:HELLO AND DATE(user_tracking_date)=CURDATE()");
    $USER_TRACKING_CHK->bindParam(':URL', $tracking_search, PDO::PARAM_STR);
    $USER_TRACKING_CHK->bindParam(':HELLO', $hello_name, PDO::PARAM_STR);
    $USER_TRACKING_CHK->execute();
    while ($USER_TRACKING_RESULT = $USER_TRACKING_CHK->fetch(PDO::FETCH_ASSOC)) {

        if ($USER_TRACKING_RESULT['user_tracking_user'] != $hello_name) {
            ?>
            <div class='notice notice-info' role='alert'><strong>
                    <center><h2>
                            <i class="fa fa-user-secret"></i> <?php echo $USER_TRACKING_RESULT['user_tracking_user']; ?>
                            is also viewing this page.</h2></center>
                </strong></div>

        <?php }
    }
}
