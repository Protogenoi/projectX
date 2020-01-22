<?php
/**
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


//NEW NOTIFICATIONS //

$trackerAlertCheck = $pdo->prepare("SELECT comments, sale, timeDeadline, dayDeadline, phone FROM closer_trackers WHERE phone=:phone AND dayDeadline <= CURDATE()");
$trackerAlertCheck->bindParam(':phone', $newClientResponse['phoneNumber'], PDO::PARAM_INT);
$trackerAlertCheck->execute();
if ($trackerAlertCheck->rowCount() > 0) {
    while ($result = $trackerAlertCheck->fetch(PDO::FETCH_ASSOC)) { ?>

        <div class="notice notice-danger" role="alert"><strong><i class="fas fa-exclamation-triangle fa-lg"></i> Closer
                Tracker Alert <?php echo $result['sale']; ?>:</strong> Deadline today | Call
            at <?php echo $result['timeDeadline']; ?>
            <a class="btn btn-xs btn-success"
               href="/addon/Trackers/php/trackerAlert.php?EXECUTE=2&CID=<?php echo $search; ?>&phone=<?php echo $result['phone']; ?>&status=<?php echo $result['sale']; ?>&dayDeadline=<?php echo $result['dayDeadline']; ?>&timeDeadline=<?php echo $result['timeDeadline']; ?>">Completed</a><a
                class="btn btn-xs btn-warning"
                href="/addon/Trackers/php/trackerAlert.php?EXECUTE=1&CID=<?php echo $search; ?>&phone=<?php echo $result['phone']; ?>&status=<?php echo $result['sale']; ?>&dayDeadline=<?php echo $result['dayDeadline']; ?>&timeDeadline=<?php echo $result['timeDeadline']; ?>">Dismiss</a>
        </div>

    <?php }
}

if (isset($status)) { ?>
    <div class="notice notice-info" role="alert"><strong><i class="fas fa-exclamation-triangle fa-lg"></i>Client
            status: <?php echo $status; ?></div>
<?php }
