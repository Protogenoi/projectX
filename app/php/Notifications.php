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

if (isset($ffsms) && $ffsms == '1') {

    $database->query("SELECT 
    sms_inbound_id, sms_inbound_client_id, sms_inbound_phone, sms_inbound_msg, sms_inbound_date, sms_inbound_type
FROM
    sms_inbound
WHERE
        sms_inbound_type = 'SMS Failed' AND sms_inbound_phone=:PHONE");
    $database->bind(':PHONE', $newClientResponse['phone_number']);
    $database->execute();
    $database->single();

    if ($database->rowCount() > 0) { ?>

        <div class="notice notice-danger" role="alert" id="HIDELGKEY"><strong><i
                    class="fas fa-exclamation-triangle"></i>
                Info:</strong> <?php echo $newClientResponse["phone_number"]; ?> has a failed SMS delivery response!
            The
            number may no longer be active, if the client cannot be contacted via phone either.
            <a href='#' class='close' data-dismiss='alert' aria-label='close' id='CLICKTOHIDELGKEY'>&times;</a>
        </div>

        <?php


    }

    $CHECKSMS_FAILED = "SMS has failed to be delivered to $newClientResponse[phone_number]";

    $database->query("SELECT 
    note_id
FROM
    client_note
WHERE
        note_type='SMS Failed' AND message=:PHONE");
    $database->bind(':PHONE', $CHECKSMS_FAILED);
    $database->execute();
    $database->single();

    if ($database->rowCount() > 0) { ?>

        <div class="notice notice-danger" role="alert" id="HIDELGKEY"><strong><i
                    class="fas fa-exclamation-triangle"></i>
                Info:</strong> <?php echo $newClientResponse["phone_number"]; ?> has a failed SMS delivery response!
            The
            number may no longer be active, if the client cannot be contacted via phone either.
            <a href='#' class='close' data-dismiss='alert' aria-label='close' id='CLICKTOHIDELGKEY'>&times;</a>
        </div>

        <?php $NUMBER_BAD = '1';

    }

    if ($COMPANY_ENTITY != 'Project X') {

        if (isset($LANG_POL) && $LANG_POL == 1
            || isset($HAS_NEW_VIT_POL) && $HAS_NEW_VIT_POL == 1
            || isset($HAS_VIT_POL) && $HAS_VIT_POL == 1
            || isset($HAS_LV_POL) && $HAS_LV_POL == 1
            || isset($HAS_AVI_POL) && $HAS_AVI_POL == 1
            || isset($HAS_ZURICH_POL) && $HAS_ZURICH_POL == 1
            || isset($HAS_SCOTTISH_WIDOWS_POL) && $HAS_SCOTTISH_WIDOWS_POL == 1
            || isset($HAS_RL_POL) && $HAS_RL_POL == 1
            || isset($HAS_AEG_POL) && $HAS_AEG_POL == 1
            || isset($HAS_WOL_POL) && $HAS_WOL_POL == 1) {

            $database->query("SELECT 
    note_id
FROM
    client_note
WHERE
    note_type = 'Sent SMS: Welcome'
        AND client_id = :CID
        OR note_type = 'Sent SMS'
        AND message = 'Welcome'
OR
        note_type = 'SMS notice dismissed'
        AND message = 'Welcome message dismissed'
        AND client_id =:CID2");
            $database->bind(':CID', $search);
            $database->bind(':CID2', $search);
            $database->execute();
            $database->single();

            if ($database->rowCount() <= 0) { ?>

                <div class="notice notice-warning" role="alert" id="HIDELGKEY"><strong><i
                            class="fas fa-mobile-alt"></i> Alert:</strong> No Welcome SMS has been sent to this
                    client! <a class="btn btn-xs btn-warning"
                               href="/addon/Life/SMS/dismiss_notification.php?EXECUTE=1&CID=<?php echo $search; ?>">Dismiss</a>
                    <a href='#' class='close' data-dismiss='alert' aria-label='close'
                       id='CLICKTOHIDELGKEY'>&times;</a></div>

            <?php }


        }

    }

}

if (isset($ffews) && $ffews == 1) {

    if (isset($LANG_POL) && $LANG_POL == 1
        || isset($HAS_NEW_VIT_POL) && $HAS_NEW_VIT_POL == 1
        || isset($HAS_VIT_POL) && $HAS_VIT_POL == 1
        || isset($HAS_LV_POL) && $HAS_LV_POL == 1
        || isset($HAS_AVI_POL) && $HAS_AVI_POL == 1
        || isset($HAS_ZURICH_POL) && $HAS_ZURICH_POL == 1
        || isset($HAS_SCOTTISH_WIDOWS_POL) && $HAS_SCOTTISH_WIDOWS_POL == 1
        || isset($HAS_RL_POL) && $HAS_RL_POL == 1
        || isset($HAS_AEG_POL) && $HAS_AEG_POL == 1
        || isset($HAS_WOL_POL) && $HAS_WOL_POL == 1) {

        $EWS_NEW = $pdo->prepare("SELECT 
        adl_ews_ref,
        adl_ews_orig_status
    FROM
        adl_ews
    WHERE
        adl_ews_status = 'NEW'
    AND 
        adl_ews_client_id=:CID");
        $EWS_NEW->bindParam(':CID', $search, PDO::PARAM_INT);
        $EWS_NEW->execute();
        if ($EWS_NEW->rowCount() > 0) {
            while ($result = $EWS_NEW->fetch(PDO::FETCH_ASSOC)) { ?>

                <div class="notice notice-danger" role="alert" id="HIDELGKEY"><strong><i
                            class="fas fa-exclamation-triangle"></i>
                        Alert:</strong> <?php echo $result['adl_ews_ref']; ?> policy is on EWS marked
                    as <?php echo $result['adl_ews_orig_status']; ?> <a href='#' class='close' data-dismiss='alert'
                                                                        aria-label='close'
                                                                        id='CLICKTOHIDELGKEY'>&times;</a>
                </div>


            <?php }

        }

    }

}


if ($ffkeyfactsemail == '1') {

    $database->query("select keyfactsemail_email from keyfactsemail where keyfactsemail_email=:email");
    $database->bind(':email', $clientonemail);
    $database->execute();
    $database->single();
    if ($database->rowCount() <= 0) {

        echo "<div class='notice notice-danger' role='alert' id='HIDECLOSERKF'><strong><i class='far fa-envelope  fa-lg'></i> Alert:</strong> Keyfacts Email not sent <i>(Send from Files & Uploads tab)</i>!"
            . "<a href='#' class='close' data-dismiss='alert' aria-label='close' id='CLICKTOHIDECLOSERKF'>&times;</a></div>";

    }

}

$database->query("select uploadtype from tbl_uploads where uploadtype='Closer Call Recording' and file like :search");
$database->bind(':search', $likesearch);
$database->execute();
$database->single();
if ($database->rowCount() <= 0) {

    echo "<div class=\"notice notice-danger\" role=\"alert\" id='HIDEDEALSHEET'><strong><i class=\"fas fa-exclamation-triangle fa-lg\"></i> Alert:</strong> Closer call recording not uploaded!"
        . "<a href='#' class='close' data-dismiss='alert' aria-label='close' id='CLICKTOHIDEDEALSHEET'>&times;</a></div>";

}

$database->query("select uploadtype from tbl_uploads where uploadtype='Agent Call Recording' and file like :search");
$database->bind(':search', $likesearch);
$database->execute();
$database->single();
if ($database->rowCount() <= 0) {

    echo "<div class=\"notice notice-danger\" role=\"alert\" id='HIDEDEALSHEET'><strong><i class=\"fas fa-exclamation-triangle fa-lg\"></i> Alert:</strong> Agent call recording not uploaded!"
        . "<a href='#' class='close' data-dismiss='alert' aria-label='close' id='CLICKTOHIDEDEALSHEET'>&times;</a></div>";

}

if (!isset($dealsheet_id)) {
    $database->query("select uploadtype from tbl_uploads where uploadtype='Dealsheet' and file like :search");
    $database->bind(':search', $likesearch);
    $database->execute();
    $database->single();
    if ($database->rowCount() <= 0) {

        echo "<div class=\"notice notice-warning\" role=\"alert\" id='HIDEDEALSHEET'><strong><i class=\"fa fa-upload fa-lg\"></i> Alert:</strong> Dealsheet not uploaded!"
            . "<a href='#' class='close' data-dismiss='alert' aria-label='close' id='CLICKTOHIDEDEALSHEET'>&times;</a></div>";

    }
}

$dupepolicy = filter_input(INPUT_GET, 'dupepolicy', FILTER_SANITIZE_SPECIAL_CHARS);

if (isset($dupepolicy)) {
    if (!empty($dupepolicy)) {
        $origpolicy = filter_input(INPUT_GET, 'origpolicy', FILTER_SANITIZE_SPECIAL_CHARS);

        echo "<div class='notice notice-danger' role='alert' id='HIDEDUPEPOL'><strong><i class='fas fa-exclamation-triangle fa-lg'></i> Warning:</strong> Duplicate $origpolicy number found! Policy number changed to $dupepolicy<br><br><strong><i class='fas fa-exclamation-triangle fa-lg'></i> $hello_name:</strong> If you are replacing an old policy change old policy to $origpolicy OLD and remove DUPE from the newer updated policy.<a href='#' class='close' data-dismiss='alert' aria-label='close' id='CLICKTOHIDEDUPEPOL'>&times;</a></div>";

    }
}

$Callback = filter_input(INPUT_GET, 'Callback', FILTER_SANITIZE_SPECIAL_CHARS);
if (isset($Callback)) {
    if ($Callback == 'y') {
        print("<div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa fa-check fa-calendar\"></i> Success:</strong> Callback Set!</div>");

    }
    if ($Callback == 'fail') {
        print("<div class=\"notice notice-danger\" role=\"alert\"><strong><i class=\"fas fa-exclamation-triangle fa-lg\"></i> Error:</strong> No changes were made!</div>");

    }

}

$policydetailsadded = filter_input(INPUT_GET, 'policydetailsadded', FILTER_SANITIZE_SPECIAL_CHARS);
if (isset($policydetailsadded)) {
    if ($policydetailsadded == 'failed') {
        print("<div class=\"notice notice-danger\" role=\"alert\"><strong><i class=\"fas fa-exclamation-triangle fa-lg\"></i> Error:</strong> No changes were made!</div>");


    }

}

$taskedited = filter_input(INPUT_GET, 'taskedited', FILTER_SANITIZE_SPECIAL_CHARS);
if (isset($taskedited)) {
    if ($taskedited == 'y') {
        print("<div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa fa-pencil-alt fa-lg\"></i> Success:</strong> Task notes updated!</div>");

    }
    if ($taskedited == 'n') {
        print("<div class=\"notice notice-danger\" role=\"alert\"><strong><i class=\"fas fa-exclamation-triangle fa-lg\"></i> Error:</strong> Task notes NOT updated!</div>");

    }

}

$policyedited = filter_input(INPUT_GET, 'policyedited', FILTER_SANITIZE_SPECIAL_CHARS);
if (isset($policyedited)) {
    if ($policyedited == 'y') {
        print("<div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa fa-pencil-alt fa-lg\"></i> Success:</strong> Policy details updated!</div>");

    }
    if ($policyedited == 'n') {
        print("<div class=\"notice notice-danger\" role=\"alert\"><strong><i class=\"fas fa-exclamation-triangle fa-lg\"></i> Error:</strong> Policy details updated!</div>");

    }

}

$checklistupdated = filter_input(INPUT_GET, 'checklistupdated', FILTER_SANITIZE_SPECIAL_CHARS);
if (isset($checklistupdated)) {
    if ($checklistupdatedd == 'y') {
        print("<div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa fa-check fa-lg\"></i> Success:</strong> Checklist updated!</div>");

    }
    if ($checklistupdatedd == 'n') {
        print("<div class=\"notice notice-danger\" role=\"alert\"><strong><i class=\"fas fa-exclamation-triangle fa-lg\"></i> Error:</strong> Checklist not updated!</div>");

    }

}

$Addcallback = filter_input(INPUT_GET, 'Addcallback', FILTER_SANITIZE_SPECIAL_CHARS);


if (isset($Addcallback)) {

    $callbackcompletedid = filter_input(INPUT_GET, 'callbackid', FILTER_SANITIZE_NUMBER_INT);

    if ($Addcallback == 'complete') {

        echo "<div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa fa-check-circle-o fa-lg\"></i> Success:</strong> Callback $callbackcompletedid completed!</div>";

    }

    if ($Addcallback == 'incomplete') {

        echo "<div class=\"notice notice-warning\" role=\"alert\"><strong><i class=\"fa fa-check fa-lg\"></i> Success:</strong> Callback set to incomplete!</div>";

    }

}

$taskcompleted = filter_input(INPUT_GET, 'taskcompleted', FILTER_SANITIZE_SPECIAL_CHARS);
if (isset($taskcompleted)) {
    print("<div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa fa-pencil-alt fa-lg\"></i> Success:</strong> Task completed!</div>");

}

$emailsent = filter_input(INPUT_GET, 'emailsent', FILTER_SANITIZE_SPECIAL_CHARS);

if (isset($emailsent)) {

    $emailtype = filter_input(INPUT_GET, 'emailtype', FILTER_SANITIZE_SPECIAL_CHARS);
    $emailto = filter_input(INPUT_GET, 'emailto', FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($emailtype)) {
        if ($emailtype = "CloserKeyFacts") {
            echo "<div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa fa-envelope fa-lg\"></i> Success:</strong> Closer KeyFacts Email sent to <b>$emailto</b> !</div>";
        }
    } else {
        $emailaddress = filter_input(INPUT_GET, 'emailto', FILTER_SANITIZE_EMAIL);
        print("<div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa fa-envelope fa-lg\"></i> Success:</strong> Email sent to <b>$emailaddress</b> !</div>");
    }
}

$workflow = filter_input(INPUT_GET, 'workflow', FILTER_SANITIZE_SPECIAL_CHARS);
if (isset($workflow)) {
    $stepcom = filter_input(INPUT_GET, 'workflow', FILTER_SANITIZE_SPECIAL_CHARS);
    print("<div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fas fa-exclamation-circle fa-lg\"></i> Success:</strong>  $stepcom updated</div>");

}

$CallbackSet = filter_input(INPUT_GET, 'CallbackSet', FILTER_SANITIZE_NUMBER_INT);
if (isset($CallbackSet)) {
    if ($CallbackSet == '1') {

        $CallbackTime = filter_input(INPUT_GET, 'CallbackTime', FILTER_SANITIZE_SPECIAL_CHARS);
        $CallbackDate = filter_input(INPUT_GET, 'CallbackDate', FILTER_SANITIZE_SPECIAL_CHARS);

        echo "<div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fas fa-exclamation-circle fa-lg\"></i> Callback set for $CallbackTime $CallbackDate</strong></div>";


    }

    if ($CallbackSet == '0') {

        echo "<div class=\"notice notice-warning\" role=\"alert\"><strong><i class=\"fas fa-exclamation-triangle fa-lg\"></i> No call back changes made</strong></div>";

    }
}

//NEW NOTIFICATIONS //

$padAlertCheck = $pdo->prepare("SELECT comments, status, policyNumber, timeDeadline,  timeReminder, dayDeadline FROM pad_stats WHERE client_id=:CID AND dayDeadline <= CURDATE()");
$padAlertCheck->bindParam(':CID', $search, PDO::PARAM_INT);
$padAlertCheck->execute();
if ($padAlertCheck->rowCount() > 0) {
    while ($result = $padAlertCheck->fetch(PDO::FETCH_ASSOC)) { ?>

        <div class="notice notice-danger" role="alert"><strong><i class="fas fa-exclamation-triangle fa-lg"></i> Pad
                Alert <?php echo $result['status']; ?>:</strong> Deadline today | Call
            at <?php echo $result['timeDeadline']; ?>
            <a class="btn btn-xs btn-success"
               href="/addon/Life/PAD/php/padAlert.php?EXECUTE=2&CID=<?php echo $search; ?>&policyNumber=<?php echo $result['policyNumber']; ?>&status=<?php echo $result['status']; ?>&dayDeadline=<?php echo $result['dayDeadline']; ?>&timeDeadline=<?php echo $result['timeDeadline']; ?>">Completed</a><a
                class="btn btn-xs btn-warning"
                href="/addon/Life/PAD/php/padAlert.php?EXECUTE=1&CID=<?php echo $search; ?>&policyNumber=<?php echo $result['policyNumber']; ?>&status=<?php echo $result['status']; ?>&dayDeadline=<?php echo $result['dayDeadline']; ?>&timeDeadline=<?php echo $result['timeDeadline']; ?>">Dismiss</a>
        </div>

    <?php }
}

$CLIENT_TASK = filter_input(INPUT_GET, 'CLIENT_TASK', FILTER_SANITIZE_SPECIAL_CHARS);

if (isset($CLIENT_TASK)) {
    if ($CLIENT_TASK == 'CYD') {
        echo "<div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa fa-check\"></i> Success:</strong> CYD Task Updated!</div>";

    }
    if ($CLIENT_TASK == '5 day') {
        echo "<div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa fa-check\"></i> Success:</strong> 5 Day Task Updated!</div>";

    }
    if ($CLIENT_TASK == '24 48') {
        echo "<div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa fa-check\"></i> Success:</strong> 24-48 Day Task Updated!</div>";

    }
    if ($CLIENT_TASK == '18 day') {
        echo "<div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa fa-check\"></i> Success:</strong> 18 Day Task Updated!</div>";

    }

}

$CLIENT_EWS = filter_input(INPUT_GET, 'CLIENT_EWS', FILTER_SANITIZE_SPECIAL_CHARS);

if (isset($CLIENT_EWS)) {
    $CLIENT_POLICY_POL_NUM = filter_input(INPUT_GET, 'CLIENT_POLICY_POL_NUM', FILTER_SANITIZE_NUMBER_INT);
    if ($CLIENT_EWS == '1') {
        echo "<div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa fa-check\"></i> Success:</strong> EWS Updated for policy $CLIENT_POLICY_POL_NUM!</div>";

    }

}

$EMAIL_SENT = filter_input(INPUT_GET, 'EMAIL_SENT', FILTER_SANITIZE_SPECIAL_CHARS);

if (isset($EMAIL_SENT)) {
    $CLIENT_EMAIL = filter_input(INPUT_GET, 'CLIENT_EMAIL', FILTER_SANITIZE_SPECIAL_CHARS);
    $EMAIL_SENT_TO = filter_input(INPUT_GET, 'EMAIL_SENT_TO', FILTER_SANITIZE_SPECIAL_CHARS);
    if ($EMAIL_SENT == 1) {
        echo "<div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa fa-envelope fa-lg\"></i> Email:</strong> $CLIENT_EMAIL sent to <b>$EMAIL_SENT_TO</b>!</div>";
    }
    if ($EMAIL_SENT == 0) {
        echo "<div class=\"notice notice-danger\" role=\"alert\"><strong><i class=\"fa fa-envelope fa-lg\"></i> Email:</strong> $CLIENT_EMAIL failed to <b>$EMAIL_SENT_TO</b>!</div>";
    }
}

$CLIENT_SMS = filter_input(INPUT_GET, 'CLIENT_SMS', FILTER_SANITIZE_SPECIAL_CHARS);
if (isset($CLIENT_SMS)) {
    if ($CLIENT_SMS == 2) {
        print("<div class=\"notice notice-warning\" role=\"alert\"><strong><i class=\"fa fa-envelope fa-lg\"></i> Success:</strong> SMS dismissed!</div>");
    }
}

$TSK_QRY = $pdo->prepare("select task from Client_Tasks WHERE client_id=:CID and complete ='0' and deadline <= CURDATE()");
$TSK_QRY->bindParam(':CID', $search, PDO::PARAM_INT);
$TSK_QRY->execute();
if ($TSK_QRY->rowCount() > 0) {
    while ($result = $TSK_QRY->fetch(PDO::FETCH_ASSOC)) { ?>


        <div class="notice notice-default" role="alert" id='HIDELGKEY'><strong><i class="fa fa-tasks fa-lg"></i>
                Tasks To Do:</strong> <?php
            foreach ($result as $value) {
                echo "$value ";
            }
            ?> deadline expired<a href='#' class='close' data-dismiss='alert' aria-label='close'
                                  id='CLICKTOHIDELGKEY'>&times;</a></div>

    <?php }
}

$NEW_TSK_QRY = $pdo->prepare("SELECT life_tasks_task from life_tasks WHERE life_tasks_client_id=:CID and life_tasks_complete ='0' and life_tasks_deadline <= CURDATE()");
$NEW_TSK_QRY->bindParam(':CID', $search, PDO::PARAM_INT);
$NEW_TSK_QRY->execute();
if ($NEW_TSK_QRY->rowCount() > 0) {
    while ($result = $NEW_TSK_QRY->fetch(PDO::FETCH_ASSOC)) { ?>


        <div class="notice notice-default" role="alert" id='HIDELGKEY'><strong><i class="fa fa-tasks fa-lg"></i>
                Tasks To Do:</strong> <?php
            foreach ($result as $value) {
                echo "$value ";
            }
            ?> deadline expired<a href='#' class='close' data-dismiss='alert' aria-label='close'
                                  id='CLICKTOHIDELGKEY'>&times;</a></div>

    <?php }
}

$WORKFLOW_DEADLINE_NOTICE = $pdo->prepare("SELECT 
    adl_workflows_name
FROM
    adl_workflows
WHERE
    adl_workflows_client_id_fk = :CID
        AND adl_workflows_complete = '0'
        AND adl_workflows_deadline <= CURDATE()");
$WORKFLOW_DEADLINE_NOTICE->bindParam(':CID', $search, PDO::PARAM_INT);
$WORKFLOW_DEADLINE_NOTICE->execute();
if ($WORKFLOW_DEADLINE_NOTICE->rowCount() > 0) {
    while ($result = $WORKFLOW_DEADLINE_NOTICE->fetch(PDO::FETCH_ASSOC)) { ?>


        <div class="notice notice-danger" role="alert" id='HIDELGKEY'><strong><i class="fa fa-tasks fa-lg"></i>
                Workflow tasks To Do:</strong> <?php
            foreach ($result as $value) {
                echo "$value ";
            }
            ?> deadline expired<a href='#' class='close' data-dismiss='alert' aria-label='close'
                                  id='CLICKTOHIDELGKEY'>&times;</a></div>

    <?php }
}

$WORKFLOW_EXPIRES_NOTICE = $pdo->prepare("SELECT 
    adl_workflows_name
FROM
    adl_workflows
WHERE
    adl_workflows_client_id_fk = :CID
        AND adl_workflows_complete = '0'
        AND adl_workflows_reminder = CURDATE()");
$WORKFLOW_EXPIRES_NOTICE->bindParam(':CID', $search, PDO::PARAM_INT);
$WORKFLOW_EXPIRES_NOTICE->execute();
if ($WORKFLOW_EXPIRES_NOTICE->rowCount() > 0) {
    while ($result = $WORKFLOW_EXPIRES_NOTICE->fetch(PDO::FETCH_ASSOC)) { ?>


        <div class="notice notice-warning" role="alert" id='HIDELGKEY'><strong><i class="fa fa-tasks fa-lg"></i>
                Workflow tasks To Do:</strong> <?php
            foreach ($result as $value) {
                echo "$value ";
            }
            ?> reminder<a href='#' class='close' data-dismiss='alert' aria-label='close'
                          id='CLICKTOHIDELGKEY'>&times;</a>
        </div>

    <?php }
}

if (isset($HAS_ZURICH_POL) && $HAS_ZURICH_POL == 1) {

    $database->query("select uploadtype from tbl_uploads where uploadtype='Zurichkeyfacts' and file like :search");
    $database->bind(':search', $likesearch);
    $database->execute();
    $database->single();

    if ($database->rowCount() <= 0) {

        echo "<div class=\"notice notice-warning\" role=\"alert\" id='HIDELGKEY'><strong><i class=\"fa fa-upload fa-lg\"></i> Alert:</strong> Zurich Keyfacts not uploaded!"
            . "<a href='#' class='close' data-dismiss='alert' aria-label='close' id='CLICKTOHIDELGKEY'>&times;</a></div>";

    }

    $database->query("select uploadtype from tbl_uploads where uploadtype='Zurichpolicy' and file like :search");
    $database->bind(':search', $likesearch);
    $database->execute();
    $database->single();
    if ($database->rowCount() <= 0) {

        echo "<div class=\"notice notice-warning\" role=\"alert\" id='HIDELGAPP'><strong><i class=\"fa fa-upload fa-lg\"></i> Alert:</strong> Zurich App not uploaded!"
            . "<a href='#' class='close' data-dismiss='alert' aria-label='close' id='CLICKTOHIDELGAPP'>&times;</a></div>";

    }

    if (isset($ffaudits) && $ffaudits == 1) {

        if (empty($HAS_ZURICH_CLOSER_AUDIT_CHECK)) {
            echo "<div class='notice notice-info' role='alert' id='HIDECLOSER'><strong><i class='fa fa-headphones fa-lg'></i> Alert:</strong> No Zurich Closer audit!<a href='#' class='close' data-dismiss='alert' aria-label='close' id='CLICKTOHIDECLOSER'>&times;</a></div>";
        }

        if (empty($HAS_ZURICH_LEAD_AUDIT)) {
            echo "<div class='notice notice-info' role='alert' id='HIDECLOSER'><strong><i class='fa fa-headphones fa-lg'></i> Alert:</strong> No Zurich Lead audit!<a href='#' class='close' data-dismiss='alert' aria-label='close' id='CLICKTOHIDECLOSER'>&times;</a></div>";
        }

    }

}

if (isset($HAS_RL_POL) && $HAS_RL_POL == 1 || $WHICH_COMPANY == 'Royal London') {

    if (isset($enabled)) {

        $ddMandataReminder = $pdo->prepare("SELECT DATE(sale_date) AS sub_date, policy_number FROM client_policy WHERE client_id=:CID AND insurer='Royal London' LIMIT 1");
        $ddMandataReminder->bindParam(':CID', $search, PDO::PARAM_INT);
        $ddMandataReminder->execute();
        if ($ddMandataReminder->rowCount() > 0) {
            while ($result = $ddMandataReminder->fetch(PDO::FETCH_ASSOC)) {

                $ddMandatePolicyNumber = $result['policy_number'];
                $ddMandateSubDate = $result['sub_date'];
                $ddMandateInsurer = 'Royal London';

                ?>

                <div class="notice notice-warning" role="alert" id="HIDELGKEY"><strong><i
                            class="fas fa-exclamation-triangle"></i> Alert:
                    </strong><?php echo $ddMandateInsurer . " policy: " . $ddMandatePolicyNumber; ?> direct debit
                    mandate SMS reminder. Payment date <?php echo $ddMandateSubDate; ?> <a href='#' class='close'
                                                                                           data-dismiss='alert'
                                                                                           aria-label='close'
                                                                                           id='CLICKTOHIDELGKEY'>&times;</a>
                </div>


            <?php }
        }

    }

    $database->query("select uploadtype from tbl_uploads where uploadtype='RLkeyfacts' and file like :search");
    $database->bind(':search', $likesearch);
    $database->execute();
    $database->single();

    if ($database->rowCount() <= 0) {

        echo "<div class=\"notice notice-warning\" role=\"alert\" id='HIDELGKEY'><strong><i class=\"fa fa-upload fa-lg\"></i> Alert:</strong> Royal London Keyfacts not uploaded!"
            . "<a href='#' class='close' data-dismiss='alert' aria-label='close' id='CLICKTOHIDELGKEY'>&times;</a></div>";

    }

    $database->query("select uploadtype from tbl_uploads where uploadtype='RLpolicy' and file like :search");
    $database->bind(':search', $likesearch);
    $database->execute();
    $database->single();
    if ($database->rowCount() <= 0) {

        echo "<div class=\"notice notice-warning\" role=\"alert\" id='HIDELGAPP'><strong><i class=\"fa fa-upload fa-lg\"></i> Alert:</strong> Royal London App not uploaded!"
            . "<a href='#' class='close' data-dismiss='alert' aria-label='close' id='CLICKTOHIDELGAPP'>&times;</a></div>";

    }

    if (isset($ffaudits) && $ffaudits == 1) {

        if (empty($HAS_RL_CLOSER_AUDIT_CHECK)) {
            echo "<div class='notice notice-info' role='alert' id='HIDECLOSER'><strong><i class='fa fa-headphones fa-lg'></i> Alert:</strong> No Royal London Closer audit!<a href='#' class='close' data-dismiss='alert' aria-label='close' id='CLICKTOHIDECLOSER'>&times;</a></div>";
        }

        if (empty($HAS_RL_LEAD_AUDIT)) {
            echo "<div class='notice notice-info' role='alert' id='HIDECLOSER'><strong><i class='fa fa-headphones fa-lg'></i> Alert:</strong> No Royal London Lead audit!<a href='#' class='close' data-dismiss='alert' aria-label='close' id='CLICKTOHIDECLOSER'>&times;</a></div>";
        }

    }

}

if (isset($HAS_SCOTTISH_WIDOWS_POL) && $HAS_SCOTTISH_WIDOWS_POL == 1) {

    $database->query("select uploadtype from tbl_uploads where uploadtype='SWkeyfacts' and file like :search");
    $database->bind(':search', $likesearch);
    $database->execute();
    $database->single();

    if ($database->rowCount() <= 0) {

        echo "<div class=\"notice notice-warning\" role=\"alert\" id='HIDELGKEY'><strong><i class=\"fa fa-upload fa-lg\"></i> Alert:</strong> Scottish Widows Keyfacts not uploaded!"
            . "<a href='#' class='close' data-dismiss='alert' aria-label='close' id='CLICKTOHIDELGKEY'>&times;</a></div>";

    }

    $database->query("select uploadtype from tbl_uploads where uploadtype='SWpolicy' and file like :search");
    $database->bind(':search', $likesearch);
    $database->execute();
    $database->single();
    if ($database->rowCount() <= 0) {

        echo "<div class=\"notice notice-warning\" role=\"alert\" id='HIDELGAPP'><strong><i class=\"fa fa-upload fa-lg\"></i> Alert:</strong> Scottish Widows App not uploaded!"
            . "<a href='#' class='close' data-dismiss='alert' aria-label='close' id='CLICKTOHIDELGAPP'>&times;</a></div>";

    }
}

if (isset($HAS_AEG_POL) && $HAS_AEG_POL == 1 || $WHICH_COMPANY == 'Aegon') {

    $ddMandataReminder = $pdo->prepare("SELECT DATE(adl_policy_sub_date) AS sub_date, adl_policy_ref as policy_number FROM adl_policy WHERE adl_policy_client_id_fk=:CID AND adl_policy_insurer='Aegon' AND adl_policy_status = 'Live' LIMIT 1");
    $ddMandataReminder->bindParam(':CID', $search, PDO::PARAM_INT);
    $ddMandataReminder->execute();
    if ($ddMandataReminder->rowCount() > 0) {
        while ($result = $ddMandataReminder->fetch(PDO::FETCH_ASSOC)) {

            $ddMandatePolicyNumber = $result['policy_number'];
            $ddMandateSubDate = $result['sub_date'];
            $ddMandateInsurer = 'Aegon';

            $EXPIRES = new DateTime($ddMandateSubDate);
            $EXPIRES_DAY = $EXPIRES->modify('+30 weekday');
            if (date('Y-m-d') < $EXPIRES_DAY->format('Y-m-d')) {

                $AEGON_DD_DATE_FROM = new DateTime($ddMandateSubDate);
                $AEGON_DD_DATE_FROM->modify('+7 weekday');

                $AEGON_DD_DATE_TO = new DateTime($ddMandateSubDate);
                $AEGON_DD_DATE_TO->modify('+10 weekday');

                ?>

                <div class="notice notice-warning" role="alert" id="HIDELGKEY"><strong><i
                            class="fas fa-exclamation-triangle"></i> Alert:
                    </strong><?php echo $ddMandateInsurer . " policy: $ddMandatePolicyNumber - Live date: $ddMandateSubDate"; ?>
                    | Direct debit
                    mandate first payment date
                    between <?php echo $AEGON_DD_DATE_FROM->format('jS F') . " - " . $AEGON_DD_DATE_TO->format('jS F Y'); ?>
                    <a href='#' class='close'
                       data-dismiss='alert'
                       aria-label='close'
                       id='CLICKTOHIDELGKEY'>&times;</a>
                </div>


            <?php }
        }

    }

    $database->query("select uploadtype from tbl_uploads where uploadtype='Aegonkeyfacts' and file like :search");
    $database->bind(':search', $likesearch);
    $database->execute();
    $database->single();

    if ($database->rowCount() <= 0) {

        echo "<div class=\"notice notice-warning\" role=\"alert\" id='HIDELGKEY'><strong><i class=\"fa fa-upload fa-lg\"></i> Alert:</strong> Aegon Keyfacts not uploaded!"
            . "<a href='#' class='close' data-dismiss='alert' aria-label='close' id='CLICKTOHIDELGKEY'>&times;</a></div>";

    }

    $database->query("select uploadtype from tbl_uploads where uploadtype='Aegonpolicy' and file like :search");
    $database->bind(':search', $likesearch);
    $database->execute();
    $database->single();
    if ($database->rowCount() <= 0) {

        echo "<div class=\"notice notice-warning\" role=\"alert\" id='HIDELGAPP'><strong><i class=\"fa fa-upload fa-lg\"></i> Alert:</strong> Aegon App not uploaded!"
            . "<a href='#' class='close' data-dismiss='alert' aria-label='close' id='CLICKTOHIDELGAPP'>&times;</a></div>";

    }

    if (isset($ffaudits) && $ffaudits == 1) {

        if (empty($HAS_AEG_CLOSER_AUDIT_CHECK)) {
            echo "<div class='notice notice-info' role='alert' id='HIDECLOSER'><strong><i class='fa fa-headphones fa-lg'></i> Alert:</strong> No Aegon Closer audit!<a href='#' class='close' data-dismiss='alert' aria-label='close' id='CLICKTOHIDECLOSER'>&times;</a></div>";
        }

        if (empty($HAS_AEG_LEAD_AUDIT)) {
            echo "<div class='notice notice-info' role='alert' id='HIDECLOSER'><strong><i class='fa fa-headphones fa-lg'></i> Alert:</strong> No Aegon Lead audit!<a href='#' class='close' data-dismiss='alert' aria-label='close' id='CLICKTOHIDECLOSER'>&times;</a></div>";
        }

    }

}

if (isset($HAS_VIT_POL) && $HAS_VIT_POL == 1 || isset($HAS_NEW_VIT_POL) && $HAS_NEW_VIT_POL == 1) {

    $database->query("select uploadtype from tbl_uploads where uploadtype='Vitalitykeyfacts' and file like :search");
    $database->bind(':search', $likesearch);
    $database->execute();
    $database->single();

    if ($database->rowCount() <= 0) {

        echo "<div class=\"notice notice-warning\" role=\"alert\" id='HIDELGKEY'><strong><i class=\"fa fa-upload fa-lg\"></i> Alert:</strong> Vitality Keyfacts not uploaded!"
            . "<a href='#' class='close' data-dismiss='alert' aria-label='close' id='CLICKTOHIDELGKEY'>&times;</a></div>";

    }

    $database->query("select uploadtype from tbl_uploads where uploadtype='Vitalitypolicy' and file like :search");
    $database->bind(':search', $likesearch);
    $database->execute();
    $database->single();
    if ($database->rowCount() <= 0) {

        echo "<div class=\"notice notice-warning\" role=\"alert\" id='HIDELGAPP'><strong><i class=\"fa fa-upload fa-lg\"></i> Alert:</strong> Vitality App not uploaded!"
            . "<a href='#' class='close' data-dismiss='alert' aria-label='close' id='CLICKTOHIDELGAPP'>&times;</a></div>";

    }

    if (isset($ffaudits) && $ffaudits == 1) {

        if (empty($HAS_VIT_CLOSER_AUDIT_CHECK)) {
            echo "<div class='notice notice-info' role='alert' id='HIDECLOSER'><strong><i class='fa fa-headphones fa-lg'></i> Alert:</strong> No Vitality Closer audit!<a href='#' class='close' data-dismiss='alert' aria-label='close' id='CLICKTOHIDECLOSER'>&times;</a></div>";
        }

        if (empty($HAS_VIT_LEAD_AUDIT)) {
            echo "<div class='notice notice-info' role='alert' id='HIDECLOSER'><strong><i class='fa fa-headphones fa-lg'></i> Alert:</strong> No Vitality Lead audit!<a href='#' class='close' data-dismiss='alert' aria-label='close' id='CLICKTOHIDECLOSER'>&times;</a></div>";
        }

    }

}

if (isset($HAS_EXETER_POL) && $HAS_EXETER_POL == 1 || $WHICH_COMPANY == 'The Exeter') {

    $database->query("select uploadtype from tbl_uploads where uploadtype='EXETERkeyfacts' and file like :search");
    $database->bind(':search', $likesearch);
    $database->execute();
    $database->single();

    if ($database->rowCount() <= 0) {

        echo "<div class=\"notice notice-warning\" role=\"alert\" id='HIDELGKEY'><strong><i class=\"fa fa-upload fa-lg\"></i> Alert:</strong> The Exeter Keyfacts not uploaded!"
            . "<a href='#' class='close' data-dismiss='alert' aria-label='close' id='CLICKTOHIDELGKEY'>&times;</a></div>";

    }

    $database->query("select uploadtype from tbl_uploads where uploadtype='EXETERkeyfacts' and file like :search");
    $database->bind(':search', $likesearch);
    $database->execute();
    $database->single();
    if ($database->rowCount() <= 0) {

        echo "<div class=\"notice notice-warning\" role=\"alert\" id='HIDELGAPP'><strong><i class=\"fa fa-upload fa-lg\"></i> Alert:</strong> The Exeter App not uploaded!"
            . "<a href='#' class='close' data-dismiss='alert' aria-label='close' id='CLICKTOHIDELGAPP'>&times;</a></div>";

    }


}

if (isset($HAS_HSBC_POL) && $HAS_HSBC_POL == 1 || $WHICH_COMPANY == 'HSBC') {

    $database->query("select uploadtype from tbl_uploads where uploadtype='HSBCkeyfacts' and file like :search");
    $database->bind(':search', $likesearch);
    $database->execute();
    $database->single();

    if ($database->rowCount() <= 0) {

        echo "<div class=\"notice notice-warning\" role=\"alert\" id='HIDELGKEY'><strong><i class=\"fa fa-upload fa-lg\"></i> Alert:</strong> HSBC Keyfacts not uploaded!"
            . "<a href='#' class='close' data-dismiss='alert' aria-label='close' id='CLICKTOHIDELGKEY'>&times;</a></div>";

    }

    $database->query("select uploadtype from tbl_uploads where uploadtype='HSBCkeyfacts' and file like :search");
    $database->bind(':search', $likesearch);
    $database->execute();
    $database->single();
    if ($database->rowCount() <= 0) {

        echo "<div class=\"notice notice-warning\" role=\"alert\" id='HIDELGAPP'><strong><i class=\"fa fa-upload fa-lg\"></i> Alert:</strong> HSBC App not uploaded!"
            . "<a href='#' class='close' data-dismiss='alert' aria-label='close' id='CLICKTOHIDELGAPP'>&times;</a></div>";

    }


}

if (isset($HAS_LV_POL) && $HAS_LV_POL == 1 || $WHICH_COMPANY == 'LV') {

    $ddMandataReminder = $pdo->prepare("SELECT DATE(sale_date) AS sub_date, policy_number FROM client_policy WHERE client_id=:CID AND insurer='LV' AND policystatus = 'Live' LIMIT 1");
    $ddMandataReminder->bindParam(':CID', $search, PDO::PARAM_INT);
    $ddMandataReminder->execute();
    if ($ddMandataReminder->rowCount() > 0) {
        while ($result = $ddMandataReminder->fetch(PDO::FETCH_ASSOC)) {

            $ddMandatePolicyNumber = $result['policy_number'];
            $ddMandateSubDate = $result['sub_date'];
            $ddMandateInsurer = 'LV';

            $EXPIRES = new DateTime($ddMandateSubDate);
            $EXPIRES_DAY = $EXPIRES->modify('+30 weekday');
            if (date('Y-m-d') < $EXPIRES_DAY->format('Y-m-d')) {

                $SUB_DATE_DAY = new DateTime($ddMandateSubDate);
                $DAY = $SUB_DATE_DAY->format('D');


                $LV_DD_DATE = new DateTime($ddMandateSubDate);

                if ($ddMandateInsurer === 'LV') {

                    switch ($DAY):

                        case 'Mon':
                            $LV_DD_DATE->modify('+14 weekday');
                            break;
                        case 'Tue':
                            $LV_DD_DATE->modify('+13 weekday');
                            break;
                        case 'Wed':
                            $LV_DD_DATE->modify('+13 weekday');
                            break;
                        case 'Thu':
                            $LV_DD_DATE->modify('+12 weekday');
                            break;
                        case 'Fri':
                            $LV_DD_DATE->modify('+13 weekday');
                            break;
                        case 'Sat':
                            $LV_DD_DATE->modify('+13 weekday');
                            break;
                        case 'Sun':
                            $LV_DD_DATE->modify('+13 weekday');
                            break;

                    endswitch;

                }

                ?>

                <div class="notice notice-warning" role="alert" id="HIDELGKEY"><strong><i
                            class="fas fa-exclamation-triangle"></i> Alert:
                    </strong><?php echo $ddMandateInsurer . " policy: $ddMandatePolicyNumber - Live date: $ddMandateSubDate"; ?>
                    | Direct debit
                    mandate first payment date <?php echo $LV_DD_DATE->format('l jS F Y'); ?> <a href='#'
                                                                                                 class='close'
                                                                                                 data-dismiss='alert'
                                                                                                 aria-label='close'
                                                                                                 id='CLICKTOHIDELGKEY'>&times;</a>
                </div>


            <?php }
        }

    }

    $database->query("select uploadtype from tbl_uploads where uploadtype='LVkeyfacts' and file like :search");
    $database->bind(':search', $likesearch);
    $database->execute();
    $database->single();

    if ($database->rowCount() <= 0) {

        echo "<div class=\"notice notice-warning\" role=\"alert\" id='HIDELGKEY'><strong><i class=\"fa fa-upload fa-lg\"></i> Alert:</strong> LV Keyfacts not uploaded!"
            . "<a href='#' class='close' data-dismiss='alert' aria-label='close' id='CLICKTOHIDELGKEY'>&times;</a></div>";

    }

    $database->query("select uploadtype from tbl_uploads where uploadtype='LVkeyfacts' and file like :search");
    $database->bind(':search', $likesearch);
    $database->execute();
    $database->single();
    if ($database->rowCount() <= 0) {

        echo "<div class=\"notice notice-warning\" role=\"alert\" id='HIDELGAPP'><strong><i class=\"fa fa-upload fa-lg\"></i> Alert:</strong> LV App not uploaded!"
            . "<a href='#' class='close' data-dismiss='alert' aria-label='close' id='CLICKTOHIDELGAPP'>&times;</a></div>";

    }

    if (isset($ffaudits) && $ffaudits == 1) {

        if (empty($HAS_LV_CLOSER_AUDIT_CHECK)) {
            echo "<div class='notice notice-info' role='alert' id='HIDECLOSER'><strong><i class='fa fa-headphones fa-lg'></i> Alert:</strong> No LV Closer audit!<a href='#' class='close' data-dismiss='alert' aria-label='close' id='CLICKTOHIDECLOSER'>&times;</a></div>";
        }
        if (empty($HAS_LV_LEAD_AUDIT)) {
            echo "<div class='notice notice-info' role='alert' id='HIDECLOSER'><strong><i class='fa fa-headphones fa-lg'></i> Alert:</strong> No LV Lead audit!<a href='#' class='close' data-dismiss='alert' aria-label='close' id='CLICKTOHIDECLOSER'>&times;</a></div>";
        }

    }

}


if (isset($HAS_WOL_POL) && $HAS_WOL_POL == 1 || $WHICH_COMPANY == 'One Family') {

    $ddMandataReminder = $pdo->prepare("SELECT DATE(sale_date) AS sub_date, policy_number FROM client_policy WHERE client_id=:CID AND insurer='One Family' AND policystatus = 'Live' LIMIT 1");
    $ddMandataReminder->bindParam(':CID', $search, PDO::PARAM_INT);
    $ddMandataReminder->execute();
    if ($ddMandataReminder->rowCount() > 0) {
        while ($result = $ddMandataReminder->fetch(PDO::FETCH_ASSOC)) {

            $ddMandatePolicyNumber = $result['policy_number'];
            $ddMandateSubDate = $result['sub_date'];
            $ddMandateInsurer = 'One Family';

            $EXPIRES = new DateTime($ddMandateSubDate);
            $EXPIRES_DAY = $EXPIRES->modify('+30 weekday');
            if (date('Y-m-d') < $EXPIRES_DAY->format('Y-m-d')) {

                $OF_DD_DATE = new DateTime($ddMandateSubDate);
                $OF_DD_DATE->modify('+10 weekday');

                ?>

                <div class="notice notice-warning" role="alert" id="HIDELGKEY"><strong><i
                            class="fas fa-exclamation-triangle"></i> Alert:
                    </strong><?php echo $ddMandateInsurer . " policy: $ddMandatePolicyNumber - Live date: $ddMandateSubDate"; ?>
                    | Direct debit
                    mandate first payment date <?php echo $OF_DD_DATE->format('l jS F Y'); ?> <a href='#'
                                                                                                 class='close'
                                                                                                 data-dismiss='alert'
                                                                                                 aria-label='close'
                                                                                                 id='CLICKTOHIDELGKEY'>&times;</a>
                </div>


            <?php }
        }

    }

    $database->query("select uploadtype from tbl_uploads where uploadtype='WOLkeyfacts' and file like :search");
    $database->bind(':search', $likesearch);
    $database->execute();
    $database->single();

    if ($database->rowCount() <= 0) {

        echo "<div class=\"notice notice-warning\" role=\"alert\" id='HIDELGKEY'><strong><i class=\"fa fa-upload fa-lg\"></i> Alert:</strong> One Family Keyfacts not uploaded!"
            . "<a href='#' class='close' data-dismiss='alert' aria-label='close' id='CLICKTOHIDELGKEY'>&times;</a></div>";

    }

    $database->query("select uploadtype from tbl_uploads where uploadtype='WOLpolicy' and file like :search");
    $database->bind(':search', $likesearch);
    $database->execute();
    $database->single();
    if ($database->rowCount() <= 0) {

        echo "<div class=\"notice notice-warning\" role=\"alert\" id='HIDELGAPP'><strong><i class=\"fa fa-upload fa-lg\"></i> Alert:</strong> One Family App not uploaded!"
            . "<a href='#' class='close' data-dismiss='alert' aria-label='close' id='CLICKTOHIDELGAPP'>&times;</a></div>";

    }

    if (isset($ffaudits) && $ffaudits == 1) {

        if (empty($HAS_WOL_CLOSE_AUDIT)) {
            echo "<div class='notice notice-info' role='alert' id='HIDECLOSER'><strong><i class='fa fa-headphones fa-lg'></i> Alert:</strong> No One Family Closer audit!<a href='#' class='close' data-dismiss='alert' aria-label='close' id='CLICKTOHIDECLOSER'>&times;</a></div>";
        }
        if (empty($HAS_WOL_LEAD_AUDIT)) {
            echo "<div class='notice notice-info' role='alert' id='HIDECLOSER'><strong><i class='fa fa-headphones fa-lg'></i> Alert:</strong> No One Family Lead audit!<a href='#' class='close' data-dismiss='alert' aria-label='close' id='CLICKTOHIDECLOSER'>&times;</a></div>";
        }

    }

}

if (isset($HAS_AVI_POL) && $HAS_AVI_POL == '1') {

    $database->query("select uploadtype from tbl_uploads where uploadtype='Avivakeyfacts' and file like :search");
    $database->bind(':search', $likesearch);
    $database->execute();
    $database->single();

    if ($database->rowCount() <= 0) {

        echo "<div class=\"notice notice-warning\" role=\"alert\" id='HIDELGKEY'><strong><i class=\"fa fa-upload fa-lg\"></i> Alert:</strong> Aviva Keyfacts not uploaded!"
            . "<a href='#' class='close' data-dismiss='alert' aria-label='close' id='CLICKTOHIDELGKEY'>&times;</a></div>";

    }

    $database->query("SELECT uploadtype from tbl_uploads WHERE uploadtype='Avivapolicy' AND file LIKE :search");
    $database->bind(':search', $likesearch);
    $database->execute();
    $database->single();
    if ($database->rowCount() <= 0) {

        echo "<div class=\"notice notice-warning\" role=\"alert\" id='HIDELGAPP'><strong><i class=\"fa fa-upload fa-lg\"></i> Alert:</strong> Aviva App not uploaded!"
            . "<a href='#' class='close' data-dismiss='alert' aria-label='close' id='CLICKTOHIDELGAPP'>&times;</a></div>";

    }

    if (isset($ffaudits) && $ffaudits == 1) {

        if (empty($HAS_AVI_CLOSE_AUDIT)) {
            echo "<div class='notice notice-info' role='alert' id='HIDECLOSER'><strong><i class='fa fa-headphones fa-lg'></i> Alert:</strong> No Aviva Closer audit!<a href='#' class='close' data-dismiss='alert' aria-label='close' id='CLICKTOHIDECLOSER'>&times;</a></div>";
        }
        if (empty($HAS_AVI_LEAD_AUDIT)) {
            echo "<div class='notice notice-info' role='alert' id='HIDECLOSER'><strong><i class='fa fa-headphones fa-lg'></i> Alert:</strong> No Aviva Lead audit!<a href='#' class='close' data-dismiss='alert' aria-label='close' id='CLICKTOHIDECLOSER'>&times;</a></div>";
        }

    }

}

$WORKFLOW_GET_VAR = filter_input(INPUT_GET, 'WORKFLOW', FILTER_SANITIZE_SPECIAL_CHARS);
if (isset($WORKFLOW_GET_VAR)) {
    $CLIENT_TASK_GET_VAR = filter_input(INPUT_GET, 'CLIENT_TASK', FILTER_SANITIZE_SPECIAL_CHARS);
    if ($WORKFLOW_GET_VAR == "UPDATED") {
        echo "<div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa fa-tasks fa-lg\"></i> Workflow:</strong> $CLIENT_TASK_GET_VAR task updated!</div>";

    }
}

$clientnotesadded = filter_input(INPUT_GET, 'clientnotesadded', FILTER_SANITIZE_SPECIAL_CHARS);
if (isset($clientnotesadded)) {
    print("<div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa fa-pencil-alt fa-lg\"></i> Success:</strong> Client notes added!</div>");
}

$TaskSelect = filter_input(INPUT_GET, 'TaskSelect', FILTER_SANITIZE_SPECIAL_CHARS);

if (isset($TaskSelect)) {

    if ($TaskSelect == '5 day') {

        echo "<div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa fa-check\"></i> Success:</strong> 5 Day Task Updated!</div>";
    }

    if ($TaskSelect == '18 day') {

        echo "<div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa fa-check\"></i> Success:</strong> 18 Day Task Updated!</div>";
    }
}
