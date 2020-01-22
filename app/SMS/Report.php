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

require_once(__DIR__ . '/../../includes/adl_features.php');
require_once(__DIR__ . '/../../includes/Access_Levels.php');
require_once(__DIR__ . '/../../includes/adlfunctions.php');

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

if (!in_array($hello_name, $Level_3_Access, true)) {

    header('Location: /../../../CRMmain.php');
    die;
}

if (isset($ffsms) && $ffsms == 0) {

    header('Location: /../../../CRMmain.php');
    die;
}
$ADL_PAGE_TITLE = "SMS Reports";
require_once(__DIR__ . '/../../app/core/head.php');

?>

</head>
<body>

<?php
require_once(__DIR__ . '/../../includes/navbar.php');

$SEARCH_BY = filter_input(INPUT_GET, 'SEARCH_BY', FILTER_SANITIZE_SPECIAL_CHARS);
?>

<div class="container">

    <?php
    if ($ffsms == '1') {
        ?>

        <form class="form-vertical" method="GET" action="Report.php">
            <fieldset>

                <legend>SMS Response Check<i> (click row to view)</i></legend>


                <div class="col-md-2">
                    <div class="form-group">
                        <select id="SEARCH_BY" name="SEARCH_BY" class="form-control" onchange="this.form.submit()">
                            <option value="Sent" <?php
                            if (isset($SEARCH_BY)) {
                                if ($SEARCH_BY == 'Sent') {
                                    echo "selected";
                                } else {
                                    echo "selected";
                                }
                            }
                            ?> >Sent
                            </option>
                            <option value="Failed" <?php
                            if (isset($SEARCH_BY)) {
                                if ($SEARCH_BY == 'Failed') {
                                    echo "selected";
                                }
                            }
                            ?> >Failed
                            </option>
                            <option value="Responses" <?php
                            if (isset($SEARCH_BY)) {
                                if ($SEARCH_BY == 'Responses') {
                                    echo "selected";
                                }
                            }
                            ?> >Responses
                            </option>
                            <option value="WhatsApp Reply" <?php
                            if (isset($SEARCH_BY)) {
                                if ($SEARCH_BY == 'WhatsApp Reply') {
                                    echo "selected";
                                }
                            }
                            ?> >WhatsApp Reply
                            </option>
                            <option value="WhatsApp Sent" <?php
                            if (isset($SEARCH_BY)) {
                                if ($SEARCH_BY == 'WhatsApp Sent') {
                                    echo "selected";
                                }
                            }
                            ?> >WhatsApp Sent
                            </option>
                        </select>
                    </div>
                </div>


            </fieldset>
        </form>
        <?php

        if (isset($SEARCH_BY)) {
            if ($SEARCH_BY == 'Sent' || $SEARCH_BY == 'Failed') { ?>
                <div class="col-md-2"><a class="btn btn-default btn-sm"
                                         href="Update.php?EXECUTE=2&TYPE=<?php if (isset($SEARCH_BY)) {
                                             if ($SEARCH_BY == 'Sent') {
                                                 echo "SMS Delivered";
                                             } elseif ($SEARCH_BY == 'Failed') {
                                                 echo "SMS Failed";
                                             }
                                         } ?>"><i class="fa fa-check-circle"></i> Check all</a></div>

            <?php }
        }

        if (isset($SEARCH_BY)) {
            try {


                if ($SEARCH_BY == 'Sent') {

                    $SEARCH = $pdo->prepare("SELECT 
    sms_inbound_id, sms_inbound_client_id, sms_inbound_phone, sms_inbound_msg, sms_inbound_date, sms_inbound_type
FROM
    sms_inbound
WHERE
    sms_inbound_type = 'SMS Delivered'");
                }

                if ($SEARCH_BY == 'Failed') {

                    $SEARCH = $pdo->prepare("SELECT 
    sms_inbound_id, sms_inbound_client_id, sms_inbound_phone, sms_inbound_msg, sms_inbound_date, sms_inbound_type
FROM
    sms_inbound
WHERE
        sms_inbound_type = 'SMS Failed'");
                }

                if ($SEARCH_BY == 'Responses') {

                    $SEARCH = $pdo->prepare("SELECT 
    sms_inbound_id, sms_inbound_client_id, sms_inbound_phone, sms_inbound_msg, sms_inbound_date, sms_inbound_type
FROM
    sms_inbound
WHERE
    sms_inbound_type = 'Client SMS Reply'");
                }

                if ($SEARCH_BY == 'WhatsApp Reply') {

                    $SEARCH = $pdo->prepare("SELECT 
    sms_inbound_id, sms_inbound_client_id, sms_inbound_phone, sms_inbound_msg, sms_inbound_date, sms_inbound_type
FROM
    sms_inbound
WHERE
    sms_inbound_type='WhatsApp Reply'");
                }

                if ($SEARCH_BY == 'WhatsApp Sent') {

                    $SEARCH = $pdo->prepare("SELECT 
    sms_inbound_id, sms_inbound_client_id, sms_inbound_phone, sms_inbound_msg, sms_inbound_date, sms_inbound_type
FROM
    sms_inbound
WHERE
    sms_inbound_type='WhatsApp Sent'");
                }
                ?>

                <table id="clients" class="table table-striped table-hover" width="auto" cellspacing="0">
                <thead>
                <tr>
                    <th>Date</th>
                    <th>SMS Response</th>
                    <th>SMS Message</th>
                    <th></th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>Date</th>
                    <th>SMS Response</th>
                    <th>SMS Message</th>
                    <th></th>
                </tr>
                </tfoot>

                <?php
                $SEARCH->execute();
                if ($SEARCH->rowCount() > 0) {
                    while ($result = $SEARCH->fetch(PDO::FETCH_ASSOC)) {


                        echo "<tr class='clickable-row' data-href='../Client.php?search=" . $result['sms_inbound_client_id'] . "#menu4'><td>" . $result['sms_inbound_date'] . "</td>";
                        echo "<td>" . $result['sms_inbound_type'] . "</td>";
                        echo "<td>" . $result['sms_inbound_msg'] . "</td>";
                        echo "<td><a href='Update.php?EXECUTE=1&NID=" . $result['sms_inbound_id'] . "&TYPE=" . $result['sms_inbound_type'] . "&PHONE=" . $result['sms_inbound_phone'] . "&CID=" . $result['sms_inbound_client_id'] . "'><i class='fa fa-check-circle'></i></a></td>";
                        echo "</tr>";
                    }
                } else {

                    echo "<div class='notice notice-info' role='alert' id='HIDEDUPEPOL'><strong><i class='fa fa-exclamation-triangle fa-lg'></i> Info:</strong> No messages found for $SEARCH_BY<a href='#' class='close' data-dismiss='alert' aria-label='close' id='CLICKTOHIDEDUPEPOL'>&times;</a></div>";
                }
            } catch (PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();
            }
            ?>
            </table>
            <?php
        }
    }
    ?>

</div>

<script type="text/javascript" language="javascript" src="/resources/lib/jquery/jquery-3.0.0.min.js"></script>
<script type="text/javascript" language="javascript" src="/resources/lib/jquery-ui-1.11.4/jquery-ui.min.js"></script>
<script type="text/javascript" language="javascript"
        src="/resources/lib/jquery-ui-1.11.4/external/jquery/jquery.js"></script>
<script src="/resources/templates/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#LOADING').modal('show');
    });
    $(window).load(function () {
        $('#LOADING').modal('hide');
    });
</script>
<script>
    jQuery(document).ready(function ($) {
        $(".clickable-row").click(function () {
            window.location = $(this).data("href");
        });
    });
</script>
<div class="modal modal-static fade" id="LOADING" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center">
                    <center><i class="fa fa-spinner fa-pulse fa-5x fa-lg"></i></center>
                    <br>
                    <h3>Searching SMS database... </h3>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
