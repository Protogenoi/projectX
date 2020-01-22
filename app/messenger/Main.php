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

require_once(__DIR__ . '/../../classes/access_user/access_user_class.php');
$page_protect = new Access_user;
$page_protect->access_page(filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS), "", 3);
$hello_name = ($page_protect->user_full_name != "") ? $page_protect->user_full_name : $page_protect->user;

$USER_TRACKING = 0;

require_once(__DIR__ . '/../../includes/user_tracking.php');

require_once(__DIR__ . '/../../includes/time.php');

if (isset($FORCE_LOGOUT) && $FORCE_LOGOUT == 1) {
    $page_protect->log_out();
}

require_once(__DIR__ . '/../../includes/ADL_PDO_CON.php');
require_once(__DIR__ . '/../../includes/adl_features.php');
require_once(__DIR__ . '/../../includes/Access_Levels.php');

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

require_once(__DIR__ . '/../../classes/database_class.php');
require_once(__DIR__ . '/../../class/login/login.php');

$CHECK_USER_LOGIN = new UserActions($hello_name, "NoToken");

$CHECK_USER_LOGIN->SelectToken();
$CHECK_USER_LOGIN->CheckAccessLevel();

$OUT = $CHECK_USER_LOGIN->SelectToken();

if (isset($OUT['TOKEN_SELECT']) && $OUT['TOKEN_SELECT'] != 'NoToken') {

    $TOKEN = $OUT['TOKEN_SELECT'];

}

$USER_ACCESS_LEVEL = $CHECK_USER_LOGIN->CheckAccessLevel();

$ACCESS_LEVEL = $USER_ACCESS_LEVEL['ACCESS_LEVEL'];

if ($ACCESS_LEVEL < 3) {

    header('Location: ../../../index.php?AccessDenied&USER=' . $hello_name . '&COMPANY=' . $COMPANY_ENTITY);
    die;

}

$RETURN = filter_input(INPUT_GET, 'RETURN', FILTER_SANITIZE_SPECIAL_CHARS);
$EXECUTE = filter_input(INPUT_GET, 'EXECUTE', FILTER_SANITIZE_SPECIAL_CHARS);

$ADL_PAGE_TITLE = "Message Centre";
require_once(__DIR__ . '/../../app/core/head.php');

?>
<link rel="stylesheet" href="/resources/lib/summernote-master/dist/summernote.css">
</head>
<body>

<?php require_once(__DIR__ . '/../../includes/navbar.php'); ?>

<div class="container">
    <div class='notice notice-info' role='alert'><strong><i class='fa fa-question-circle'></i> Info:</strong> Send
        private messages to your colleagues! Click send message, select the recipient(s) and enter your message (Emoji
        support) and send!
    </div>
    <?php
    if (isset($RETURN)) {
        if ($RETURN == 'MSGADDED') {
            echo "<div class='notice notice-success' role='alert'><strong><i class='fa fa-share-square'></i> Success:</strong> Message sent!</div>";

        }
        if ($RETURN == 'MSGUPDATED') {
            echo "<div class='notice notice-success' role='alert'><strong><i class='far fa-check-circle'></i> Success:</strong> Message marked as read!</div>";

        }
    }
    ?>

    <div class="col-xs-12 .col-md-8">

        <div class="row">
            <div class="twelve columns">
                <ul class="ca-menu">

                    <?php if (in_array($hello_name, $Level_1_Access, true)) { ?>
                        <li>
                            <a data-toggle="modal" data-target="#myModal">
                                <span class="ca-icon"><i class="fa fa-share-square"></i></span>
                                <div class="ca-content">
                                    <h2 class="ca-main">Send<br/> Message</h2>
                                    <h3 class="ca-sub"></h3>
                                </div>
                            </a>
                        </li>

                        <li>
                            <a href="Main.php?EXECUTE=2">
                                <span class="ca-icon"><i class="fa fa-comments"></i></span>
                                <div class="ca-content">
                                    <h2 class="ca-main">Check<br/> Inbox</h2>
                                    <h3 class="ca-sub"></h3>
                                </div>
                            </a>
                        </li>

                        <li>
                            <a href="Main.php?EXECUTE=1">
                                <span class="ca-icon"><i class="fa fa-inbox"></i></span>
                                <div class="ca-content">
                                    <h2 class="ca-main">Check<br/> Sent</h2>
                                    <h3 class="ca-sub"></h3>
                                </div>
                            </a>
                        </li>
                    <?php } ?>

            </div>
        </div>
    </div>

    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Send a message</h4>
                </div>
                <div class="modal-body">
                    <p>


                    <form action="php/msg.php?EXECUTE=1" method="POST">

                        <div class='notice notice-info' role='alert'><strong><i class='fa fa-question-circle'></i> Info:</strong>
                            Select who to send your message to or hold 'ctrl/command and click' to send to a group of
                            people.
                        </div>

                        <div class="form-group">
                            <select class="form-control" name="MSG_TO[]" id="MSG_TO" multiple="yes" required="yes">
                                <option value="">Select Agent...</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="MSG">Message:</label>
                            <textarea id="notes" name="MSG" id="message" class="summernote" id="contents"
                                      title="Contents" maxlength="2000" required></textarea>
                        </div>


                        <button type="submit" class="btn btn-success"><i class="fas fa-share-square"></i> Send</button>
                    </form>

                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

    <?php

    if (isset($EXECUTE)) {
        if ($EXECUTE == '1') {

            $query = $pdo->prepare("SELECT
            messenger_sent_by,
            messenger_msg,
            messenger_date,
            messenger_status,
            messenger_company
            FROM
    messenger
    WHERE messenger_sent_by=:HELLO");
            $query->bindParam(':HELLO', $hello_name, PDO::PARAM_STR);
            $query->execute();
            if ($query->rowCount() > 0) { ?>

                <table class="table table-condensed">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Sender</th>
                        <th>Company</th>
                        <th>Message</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>Date</th>
                        <th>Sender</th>
                        <th>Company</th>
                        <th>Message</th>
                        <th>Status</th>
                    </tr>
                    </tfoot>

                    <?php
                    while ($result = $query->fetch(PDO::FETCH_ASSOC)) {

                        $NOTE = html_entity_decode($result['messenger_msg']);


                        echo "<tr>
                           <td>" . $result['messenger_date'] . "</td>";
                        echo "<td>" . $result['messenger_sent_by'] . "</td>";
                        echo "<td>" . $result['messenger_company'] . "</td>";
                        echo "<td>$NOTE</td>";
                        echo "<td>" . $result['messenger_status'] . "</td>";
                        echo "</tr>";
                    }
                    ?> </table>  <?php }


        }
        if ($EXECUTE == '2') {

            $query = $pdo->prepare("SELECT
            messenger_sent_by,
            messenger_msg,
            messenger_date,
            messenger_id,
            messenger_company
            FROM
    messenger
    WHERE
    messenger_status='Unread'
    AND messenger_to=:HELLO");
            $query->bindParam(':HELLO', $hello_name, PDO::PARAM_STR);
            $query->execute();
            if ($query->rowCount() > 0) { ?>

                <table class="table table-condensed">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Sender</th>
                        <?php if (in_array($hello_name, $COM_LVL_10_ACCESS, true)) { ?>
                            <th>Company</th>
                        <?php } ?>
                        <th>Message</th>
                        <th>Dismiss</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>Date</th>
                        <th>Sender</th>
                        <?php if (in_array($hello_name, $COM_LVL_10_ACCESS, true)) { ?>
                            <th>Company</th>
                        <?php } ?>
                        <th>Message</th>
                        <th>Dismiss</th>
                    </tr>
                    </tfoot>

                    <?php
                    while ($result = $query->fetch(PDO::FETCH_ASSOC)) {

                        $NOTE = html_entity_decode($result['messenger_msg']);


                        echo "<form method='POST' action='php/msg.php?EXECUTE=2&MID=" . $result['messenger_id'] . "''><tr>
                           <td>" . $result['messenger_date'] . "</td>";
                        echo "<td>" . $result['messenger_sent_by'] . "</td>"; ?>
                        <?php

                        if (in_array($hello_name, $COM_LVL_10_ACCESS, true)) { ?>

                            <td>
                                <select class="form-control" name='COMPANY_ENTITY'>
                                    <option value='<?php echo $COMPANY_ENTITY; ?>'><?php echo $COMPANY_ENTITY; ?></option>
                                </select>
                            </td>


                        <?php }

                        echo "<td>$NOTE</td>";
                        echo "<td><button type='submit' class='btn btn-success'><i class='far fa-check-circle'></i></button></td>";
                        echo "</tr>";
                    }
                    ?> </table>  <?php }
        }

    } else {
        $query = $pdo->prepare("SELECT
            messenger_sent_by,
            messenger_msg,
            messenger_date,
            messenger_id,
            messenger_company
            FROM
    messenger
    WHERE
    messenger_status='Unread'
    AND messenger_to=:HELLO");
        $query->bindParam(':HELLO', $hello_name, PDO::PARAM_STR);
        $query->execute();
        if ($query->rowCount() > 0) { ?>

            <table class="table table-condensed">
                <thead>
                <tr>
                    <th>Date</th>
                    <th>Sender</th>
                    <?php if (in_array($hello_name, $COM_LVL_10_ACCESS, true)) { ?>
                        <th>Company</th>
                    <?php } ?>
                    <th>Message</th>
                    <th>Dismiss</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>Date</th>
                    <th>Sender</th>
                    <?php if (in_array($hello_name, $COM_LVL_10_ACCESS, true)) { ?>
                        <th>Company</th>
                    <?php } ?>
                    <th>Message</th>
                    <th>Dismiss</th>
                </tr>
                </tfoot>

                <?php

                while ($result = $query->fetch(PDO::FETCH_ASSOC)) {
                    $NOTE = html_entity_decode($result['messenger_msg']);


                    echo "<form method='POST' id='MSG_FORM' name='MSG_FORM' action'><tr>
                           <td>" . $result['messenger_date'] . "</td>";
                    echo "<td>" . $result['messenger_sent_by'] . "</td>"; ?>
                    <?php

                    if (in_array($hello_name, $COM_LVL_10_ACCESS, true)) { ?>

                        <td>
                            <select class="form-control" name='COMPANY_ENTITY'>
                                <option value='<?php echo $COMPANY_ENTITY; ?>'><?php echo $COMPANY_ENTITY; ?></option>
                            </select>
                        </td>


                    <?php }

                    echo "<td>$NOTE</td>";
                    echo "<td><a href='php/msg.php?EXECUTE=2&MID=" . $result['messenger_id'] . "&SENDER=" . $result['messenger_date'] . "' class='btn btn-success'><i class='fa fa-check-circle'></i></a></td>";
                    echo "</tr>";
                    ?>


                <?php }
                ?> </table>  <?php }
    } ?>


</div>
<script type="text/javascript" language="javascript" src="/resources/lib/jquery/jquery-3.0.0.min.js"></script>
<script type="text/javascript" language="javascript" src="/resources/lib/jquery-ui-1.11.4/jquery-ui.min.js"></script>
<script type="text/javascript" language="javascript"
        src="/resources/lib/jquery-ui-1.11.4/external/jquery/jquery.js"></script>
<script type="text/javascript" language="javascript"
        src="/resources/templates/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
<script type="text/JavaScript">
    var $select = $('#MSG_TO');
    $.getJSON('/../../app/JSON/ADL_USERS.php?EXECUTE=1', function (data) {
        $select.html('MSG_TO');
        $.each(data, function (key, val) {
            $select.append('<option value="' + val.FULL_NAME + '">' + val.FULL_NAME + '</option>');
        })
    });
</script>
<script type="text/javascript" src="/resources/lib/summernote-master/dist/summernote.js"></script>

<script type="text/javascript">
    $(function () {
        $('.summernote').summernote({
            height: 200
        });


    });
</script>
</body>
</html>
