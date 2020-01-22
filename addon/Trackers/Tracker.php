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

require_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_SPECIAL_CHARS) . '/app/core/doc_root.php';

require_once(BASE_URL . '/classes/access_user/access_user_class.php');
$page_protect = new Access_user;
$page_protect->access_page(filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS), "", 1);
$hello_name = ($page_protect->user_full_name != "") ? $page_protect->user_full_name : $page_protect->user;

$USER_TRACKING = 0;

require_once(BASE_URL . '/includes/user_tracking.php');

require_once(BASE_URL . '/includes/time.php');

if (isset($FORCE_LOGOUT) && $FORCE_LOGOUT == 1) {
    $page_protect->log_out();
}

require_once(BASE_URL . '/includes/adl_features.php');
require_once(BASE_URL . '/includes/Access_Levels.php');
require_once(BASE_URL . '/includes/adlfunctions.php');
require_once(BASE_URL . '/includes/ADL_PDO_CON.php');


if (isset($ffanalytics) && $ffanalytics == 1) {
    require_once(BASE_URL . '/app/analyticstracking.php');
}

if (isset($fferror)) {
    if ($fferror == '1') {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }
}

getRealIpAddr();
$TRACKED_IP = getRealIpAddr();

if (!in_array($hello_name, $anyIPAccess, true)) {

    if ($TRACKED_IP != '81.145.167.66') {
        $page_protect->log_out();
    }
}

require_once(BASE_URL . '/classes/database_class.php');
require_once(BASE_URL . '/class/login/login.php');

$CHECK_USER_LOGIN = new UserActions($hello_name, "NoToken");

$CHECK_USER_LOGIN->SelectToken();
$CHECK_USER_LOGIN->CheckAccessLevel();

$OUT = $CHECK_USER_LOGIN->SelectToken();

if (isset($OUT['TOKEN_SELECT']) && $OUT['TOKEN_SELECT'] != 'NoToken') {

    $TOKEN = $OUT['TOKEN_SELECT'];

}

$USER_ACCESS_LEVEL = $CHECK_USER_LOGIN->CheckAccessLevel();

$ACCESS_LEVEL = $USER_ACCESS_LEVEL['ACCESS_LEVEL'];

if ($ACCESS_LEVEL < 1) {

    header('Location: /../../../index.php?AccessDenied&USER=' . $hello_name . '&COMPANY=' . $COMPANY_ENTITY);
    die;

}

if (isset($fftrackers) && $fftrackers == '0') {
    header('Location: /../../../CRMmain.php?Feature=NotEnabled');
    die;
}

switch ($hello_name):
    case "James":
        $CLOSER_NAME = "James Adams";
        break;
    case "Mike":
        $CLOSER_NAME = "Michael Lloyd";
        break;
    case "Richard";
        $CLOSER_NAME = "Richard Michaels";
        break;
    case "Kyle";
        $CLOSER_NAME = "Kyle Barnett";
        break;
    case "carys";
        $CLOSER_NAME = "Carys Riley";
        break;
    default:
        $CLOSER_NAME = $hello_name;
endswitch;

$QUERY = filter_input(INPUT_GET, 'query', FILTER_SANITIZE_SPECIAL_CHARS);

$Today_DATE = date("d-M-Y");
$Today_DATES = date("l jS \of F Y");
$Today_TIME = date("h:i:s");

if (in_array($hello_name, $Closer_Access, true) || $hello_name == 'Michael') {


    $CLO_CR = $pdo->prepare("SELECT 
    COUNT(IF(sale = 'SALE',
        1,
        NULL)) AS Sales,
 COUNT(IF(sale IN ('SALE' , 'NoCard',
            'QDE',
            'DEC',
            'QUN',
            'QNQ',
            'DIDNO',
            'QCBK',
            'QQQ',
            'Other',
            'QML'),
        1,
        NULL)) AS Leads
FROM
    closer_trackers

WHERE
date_added > DATE(NOW()) 
AND closer=:closer");
    $CLO_CR->bindParam(':closer', $hello_name, PDO::PARAM_STR);
    $CLO_CR->execute();
    $CLO_CR_RESULT = $CLO_CR->fetch(PDO::FETCH_ASSOC);

    if ($CLO_CR_RESULT['Sales'] == '0') {
        $Formattedrate = "0.0";
    } else {
        $Conversionrate = $CLO_CR_RESULT['Leads'] / $CLO_CR_RESULT['Sales'];
        $SINGLE_CLOSER_RATE = number_format($Conversionrate, 1);
    }

}
$ADL_PAGE_TITLE = "Trackers";
require_once(BASE_URL . '/app/core/head.php');

?>
<link rel="stylesheet" type="text/css" href="/resources/lib/jquery-ui-1.11.4/jquery-ui.min.css"/>
<link rel="stylesheet" type="text/css" href="/resources/lib/clockpicker-gh-pages/dist/jquery-clockpicker.min.css">
<link rel="stylesheet" type="text/css" href="/resources/lib/clockpicker-gh-pages/assets/css/github.min.css">
<link rel="stylesheet" type="text/css" href="/resources/lib/EasyAutocomplete-1.3.3/easy-autocomplete.min.css">
<script type="text/javascript" src="/resources/templates/fontawesome/svg-with-js/js/fontawesome-all.js"></script>

<?php if (in_array($hello_name, $Closer_Access, true) || $hello_name == 'Michael') {
    if (isset($SINGLE_CLOSER_RATE)) {
        if ($SINGLE_CLOSER_RATE >= 6) { ?>
            <style>
                .CLOSE_RATE {
                    background-color: #B00004;
                }
            </style>
        <?php }
        if ($SINGLE_CLOSER_RATE > 4.9 && $SINGLE_CLOSER_RATE < 6) { ?>
            <style>
                .CLOSE_RATE {
                    background-color: #FD7900;
                }
            </style>
        <?php }

        if ($SINGLE_CLOSER_RATE <= 4.9 && $SINGLE_CLOSER_RATE >= 1) { ?>
            <style>
                .CLOSE_RATE {
                    background-color: #16A53F;
                }
            </style>
        <?php }
    }
} ?>

<body <?php if (in_array($hello_name, $Closer_Access, true)) {
    if ($CLO_CR >= '1.5') {
        echo "bgcolor='#B00004'";
    } else {
        echo "bgcolor='#16A53F'";
    }
} ?>>

<?php require_once(BASE_URL . '/includes/navbar.php');

if (isset($QUERY)) {


    if ($QUERY == 'CloserTrackers') {
        $TrackerEdit = filter_input(INPUT_GET, 'TrackerEdit', FILTER_SANITIZE_SPECIAL_CHARS);


        ?>
        <div class="container CLOSE_RATE">

            <div class="col-md-12">
                <div class="STATREFRESH"></div>
                <div class="col-md-4"></div>
                <div class="col-md-4"></div>

                <div class="col-md-4">


                    <script>
                        function refresh_div() {
                            jQuery.ajax({
                                url: 'AJAX/Closer_Stats.php?EXECUTE=1&CLOSER_NAME=<?php echo $CLOSER_NAME;?>',
                                type: 'POST',
                                success: function (results) {
                                    jQuery(".STATREFRESH").html(results);
                                }
                            });
                        }

                        t = setInterval(refresh_div, 10000);
                    </script>

                    <?php

                    if (isset($ffkeyfactsemail) && $ffkeyfactsemail == 1) {

                        $MISS_KF_CHK = $pdo->prepare("SELECT 
    client_details.client_id
FROM
    client_details
    LEFT JOIN client_policy ON client_details.client_id=client_policy.client_id
WHERE
    client_details.email NOT IN (SELECT 
            keyfactsemail_email
        FROM
            keyfactsemail)
        AND
            client_policy.closer=:CLOSER
    GROUP BY client_details.email ORDER BY client_details.submitted_date DESC");
                        $MISS_KF_CHK->bindParam(':CLOSER', $CLOSER_NAME, PDO::PARAM_STR);
                        $MISS_KF_CHK->execute();
                        if ($MISS_KF_CHK->rowCount() > 0) {
                            require_once(__DIR__ . '/models/trackers/MissingKFEmailModel.php');
                            $MissingKFEmail = new MissingKFEmailModal($pdo);
                            $MissingKFEmailList = $MissingKFEmail->getMissingKFEmail($hello_name);
                            require_once(__DIR__ . '/views/trackers/Missing-KFEmail.php');
                        } else { ?>

                            <button class="btn btn-info btn-sm btn-block" data-toggle="modal"
                                    data-target="#KeyFactsModal"><strong>
                                    You have sent all of your
                                    Keyfacts emails!
                                </strong>
                            </button>

                            <?php

                        }
                    }

                    ?>


                </div>

            </div>

            <div class="list-group">
                    <span class="label label-primary" style="font-size: medium"><?php echo $hello_name; ?>
                        Trackers | <?php if (isset($SINGLE_CLOSER_RATE)) {
                            echo "Close Rate = " . $SINGLE_CLOSER_RATE;
                        } else {
                            echo "No leads";
                        } ?></span>
                <br>
                <br>
                <br>
                <div class="col-md-12">
                    <div class="btn-group">
                        <button class="btn btn-primary btn-sm" data-toggle="modal"
                                data-target="#quotationEmail" style="font-weight: bold ">Send Emails!!!
                        </button>
                        <a class="btn btn-info btn-sm" href="/addon/Trackers/checkEmails.php"
                           style="font-weight: bold ">Check Emails!!!
                        </a>
                        <a class="btn btn-default btn-sm" href="/addon/Trackers/closerTrackerHistory.php?EXECUTE=1"
                           style="font-weight: bold " target="_blank">Tracker History
                        </a>
                    </div>

                    <?php

                    require_once(BASE_URL . '/addon/Trackers/views/trackers/quotationEmail-view.php');

                    ?>
                </div>

                <form method="post" action="<?php if (isset($TrackerEdit)) {
                    echo 'php/Tracker.php?query=edit';
                } else {
                    echo 'php/Tracker.php?query=add';
                } ?>">
                    <table id="tracker" class="table table-hover">
                        <thead>
                        <tr>
                            <th>Agent</th>
                            <th>Client</th>
                            <th>Phone</th>
                            <th>Current Premium</th>
                            <th>Our Premium</th>
                            <th>Notes</th>
                            <th>Insurer</th>
                            <th>DISPO</th>
                            <th></th>
                        </tr>
                        </thead>

                        <?php
                        if (isset($TrackerEdit)) {

                            $TRACKER_EDIT = $pdo->prepare("SELECT timeDeadline, dayDeadline, tracker_id, insurer, agent, client, phone, current_premium, our_premium, comments, sale, mtg, lead_up FROM closer_trackers WHERE closer=:closer AND tracker_id=:id");
                            $TRACKER_EDIT->bindParam(':closer', $hello_name, PDO::PARAM_STR);
                            $TRACKER_EDIT->bindParam(':id', $TrackerEdit, PDO::PARAM_INT);
                            $TRACKER_EDIT->execute();
                            if ($TRACKER_EDIT->rowCount() > 0) {
                                $TRACKER_EDIT_result = $TRACKER_EDIT->fetch(PDO::FETCH_ASSOC);

                                $TRK_EDIT_tracker_id = $TRACKER_EDIT_result['tracker_id'];
                                $TRK_EDIT_agent = $TRACKER_EDIT_result['agent'];
                                $TRK_EDIT_client = $TRACKER_EDIT_result['client'];
                                $TRK_EDIT_phone = $TRACKER_EDIT_result['phone'];
                                $TRK_EDIT_current_premium = $TRACKER_EDIT_result['current_premium'];
                                $TRK_EDIT_INSURER = $TRACKER_EDIT_result['insurer'];

                                $TRK_EDIT_our_premium = $TRACKER_EDIT_result['our_premium'];
                                $TRK_EDIT_comments = $TRACKER_EDIT_result['comments'];
                                $TRK_EDIT_sale = $TRACKER_EDIT_result['sale'];


                                $TRK_EDIT_LEAD_UP = $TRACKER_EDIT_result['lead_up'];
                                $TRK_EDIT_MTG = $TRACKER_EDIT_result['mtg'];

                                $cbTime = $TRACKER_EDIT_result['timeDeadline'];
                                $cbDate = $TRACKER_EDIT_result['dayDeadline'];
                                ?>

                                <input type="hidden" value="<?php echo $hello_name; ?>" name="closer">
                                <input type="hidden" value="<?php echo $TRK_EDIT_tracker_id; ?>"
                                       name="tracker_id">
                                <td><input size="12" class="form-control" type="text" name="agent_name"
                                           id="edit_agent_name" value="<?php if (isset($TRK_EDIT_agent)) {
                                        echo $TRK_EDIT_agent;
                                    } ?>"></td>
                                <td><input size="12" class="form-control" type="text" name="client"
                                           value="<?php if (isset($TRK_EDIT_client)) {
                                               echo $TRK_EDIT_client;
                                           } ?>"></td>
                                <td><input size="12" class="form-control" type="text" name="phone"
                                           value="<?php if (isset($TRK_EDIT_phone)) {
                                               echo $TRK_EDIT_phone;
                                           } ?>"></td>
                                <td><input size="7" class="form-control" type="text" name="current_premium"
                                           value="<?php if (isset($TRK_EDIT_current_premium)) {
                                               echo $TRK_EDIT_current_premium;
                                           } ?>"></td>
                                <td><input size="7" class="form-control" type="text" name="our_premium"
                                           value="<?php if (isset($TRK_EDIT_our_premium)) {
                                               echo $TRK_EDIT_our_premium;
                                           } ?>"></td>
                                <td><input type="text" class="form-control" name="comments"
                                           value="<?php if (isset($TRK_EDIT_comments)) {
                                               echo $TRK_EDIT_comments;
                                           } ?>"></td>
                                <td><select name="INSURER" class="form-control" required>
                                        <option value="NA">N/A</option>
                                        <option
                                            value="Aegon" <?php if (isset($TRK_EDIT_INSURER) && $TRK_EDIT_INSURER == "Aegon") {
                                            echo "selected";
                                        } ?> >Aegon
                                        </option>
                                        <option
                                            value="Royal London" <?php if (isset($TRK_EDIT_INSURER) && $TRK_EDIT_INSURER == "Royal London") {
                                            echo "selected";
                                        } ?> >Royal London
                                        </option>
                                        <option
                                            value="LV" <?php if (isset($TRK_EDIT_INSURER) && $TRK_EDIT_INSURER == "LV") {
                                            echo "selected";
                                        } ?> >LV
                                        </option>
                                        <option
                                            value="Aviva" <?php if (isset($TRK_EDIT_INSURER) && $TRK_EDIT_INSURER == "Aviva") {
                                            echo "selected";
                                        } ?> >Aviva
                                        </option>
                                        <option
                                            value="One Family" <?php if (isset($TRK_EDIT_INSURER) && $TRK_EDIT_INSURER == "One Family") {
                                            echo "selected";
                                        } ?> >One Family
                                        </option>
                                        <option
                                            value="National Friendly" <?php if (isset($TRK_EDIT_INSURER) && $TRK_EDIT_INSURER == "National Friendly") {
                                            echo "selected";
                                        } ?> >National Friendly
                                        </option>
                                    </select></td>
                                <td><select name="sale" class="form-control" required>
                                        <option value="">DISPO</option>
                                        <option <?php if (isset($TRK_EDIT_sale)) {
                                            if ($TRK_EDIT_sale == 'SALE') {
                                                echo "selected";
                                            }
                                        } ?> value="SALE">Sale
                                        </option>
                                        <option <?php if (isset($TRK_EDIT_sale)) {
                                            if ($TRK_EDIT_sale == 'QUN') {
                                                echo "selected";
                                            }
                                        } ?> value="QUN">Underwritten
                                        </option>
                                        <option <?php if (isset($TRK_EDIT_sale)) {
                                            if ($TRK_EDIT_sale == 'QQQ') {
                                                echo "selected";
                                            }
                                        } ?> value="QQQ">Quoted
                                        </option>
                                        <option <?php if (isset($TRK_EDIT_sale)) {
                                            if ($TRK_EDIT_sale == 'QNQ') {
                                                echo "selected";
                                            }
                                        } ?> value="QNQ">No Quote
                                        </option>
                                        <option <?php if (isset($TRK_EDIT_sale)) {
                                            if ($TRK_EDIT_sale == 'QML') {
                                                echo "selected";
                                            }
                                        } ?> value="QML">Quote Mortgage Lead
                                        </option>
                                        <option <?php if (isset($TRK_EDIT_sale)) {
                                            if ($TRK_EDIT_sale == 'QDE') {
                                                echo "selected";
                                            }
                                        } ?> value="QDE">Decline
                                        </option>
                                        <option <?php if (isset($TRK_EDIT_sale)) {
                                            if ($TRK_EDIT_sale == 'QCBK') {
                                                echo "selected";
                                            }
                                        } ?> value="QCBK">Quoted Callback
                                        </option>
                                        <option <?php if (isset($TRK_EDIT_sale)) {
                                            if ($TRK_EDIT_sale == 'NoCard') {
                                                echo "selected";
                                            }
                                        } ?> value="NoCard">No Card
                                        </option>
                                        <option <?php if (isset($TRK_EDIT_sale)) {
                                            if ($TRK_EDIT_sale == 'DIDNO') {
                                                echo "selected";
                                            }
                                        } ?> value="DIDNO">Quote Not Beaten
                                        </option>
                                        <option <?php if (isset($TRK_EDIT_sale)) {
                                            if ($TRK_EDIT_sale == 'DETRA') {
                                                echo "selected";
                                            }
                                        } ?> value="DETRA">Declined but passed to upsale
                                        </option>
                                        <option <?php if (isset($TRK_EDIT_sale)) {
                                            if ($TRK_EDIT_sale == 'Hangup on XFER') {
                                                echo "selected";
                                            }
                                        } ?> value="Hangup on XFER">Hangup on XFER
                                        </option>
                                        <option <?php if (isset($TRK_EDIT_sale)) {
                                            if ($TRK_EDIT_sale == 'Thought we were an insurer') {
                                                echo "selected";
                                            }
                                        } ?> value="Thought we were an insurer">Thought we were an insurer
                                        </option>
                                        <option <?php if (isset($TRK_EDIT_sale)) {
                                            if ($TRK_EDIT_sale == 'Other') {
                                                echo "selected";
                                            }
                                        } ?> value="Other">Other
                                        </option>
                                    </select>
                                </td>

                                <td>
                                    <button type="submit" class="btn btn-warning btn-sm"><i
                                            class="fa fa-save"></i> UPDATE
                                    </button>
                                </td>
                                <td><a href="?query=CloserTrackers" class="btn btn-danger btn-sm"><i
                                            class="fa fa-ban"></i> CANCEL</a></td>

                                <tr>
                                    <th>Callback Time</th>
                                    <th>Callback Date</th>
                                    <th>Callback</th>
                                </tr>

                                <td>
                                    <div class='input-group date clockpicker'><input
                                            type='text'
                                            class="form-control"
                                            id="cbTime"
                                            name="cbTime"
                                            placeholder="24 Hour Format"
                                            value="<?php if (isset($cbTime)) {
                                                echo $cbTime;
                                            } ?>"
                                        /> <span class="input-group-addon">
                                                                            <span
                                                                                class="glyphicon glyphicon-time"></span>
                                                                        </span></div>
                                </td>
                                <td><input type="text" id="cbDate" name="cbDate" class="form-control"
                                           value="<?php if (isset($cbDate)) {
                                               echo $cbDate;
                                           } ?>"></td>
                                <td><select id="sendNotification"
                                            name="sendNotification"
                                            class="form-control">
                                        <option value="No" selected>Delete call back</option>
                                        <option value="Yes" <?php if (isset($cbDate)) {
                                            echo "selected";
                                        } ?>>Yes
                                        <option value="Complete">Call back complete
                                        </option>
                                    </select></td>


                                <?php
                            }
                        } else {
                            ?>
                            <input type="hidden" value="<?php echo $hello_name; ?>" name="closer">
                            <td><select class="form-control" name="agent_name" id="agent_name">
                                    <option value="">Select Agent...</option>


                                </select></td>
                            <td><input size="12" class="form-control" type="text" name="client"></td>
                            <td><input size="12" class="form-control" type="text" name="phone" pattern=".{11}|.{11,11}"
                                       maxlength="11" title="Enter a valid phone number 11 digits" required></td>
                            <td><input size="8" class="form-control" type="text" name="current_premium"></td>
                            <td><input size="8" class="form-control" type="text" name="our_premium"></td>
                            <td><input type="text" class="form-control" name="comments"></td>
                            <td><select name="INSURER" class="form-control" required>
                                    <option value="NA">N/A</option>
                                    <option value="Aegon">Aegon</option>
                                    <option value="HSBC">HSBC</option>
                                    <option value="Royal London">Royal London</option>
                                    <option value="LV">LV</option>
                                    <option value="Aviva">Aviva</option>
                                    <option value="One Family">One Family</option>
                                    <option value="National Friendly">National Friendly</option>
                                </select>
                            </td>
                            <td><select name="sale" class="form-control" required>
                                    <option value="">DISPO</option>
                                    <option value="SALE">Sale</option>
                                    <option value="QUN">Underwritten</option>
                                    <option value="QQQ">Quoted</option>
                                    <option value="QNQ">No Quote</option>
                                    <option value="QML">Quote Mortgage Lead</option>
                                    <option value="QDE">Decline</option>
                                    <option value="QCBK">Quoted Callback</option>
                                    <option value="NoCard">No Card</option>
                                    <option value="DIDNO">Quote Not Beaten</option>
                                    <option value="DETRA">Declined but passed to upsale</option>
                                    <option value="Hangup on XFER">Hangup on XFER</option>
                                    <option value="Thought we were an insurer">Thought we were an insurer
                                    </option>
                                    <option value="Other">Other</option>
                                </select></td>
                            <td>
                                <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-save"></i>
                                    SAVE
                                </button>
                            </td>

                            <tr>
                                <th>Callback Time</th>
                                <th>Callback Date</th>
                                <th>Callback</th>
                            </tr>

                            <td>
                                <div class='input-group date clockpicker'><input
                                        type='text'
                                        class="form-control"
                                        id="cbTime"
                                        name="cbTime"
                                        placeholder="24 Hour Format"
                                    /> <span class="input-group-addon">
                                                                            <span
                                                                                class="glyphicon glyphicon-time"></span>
                                                                        </span></div>
                            </td>
                            <td><input type="text" id="cbDate" name="cbDate" class="form-control"></td>
                            <td><select id="sendNotification"
                                        name="sendNotification"
                                        class="form-control">
                                    <option value="No" selected>No</option>
                                    <option value="Yes">Yes
                                    </option>
                                </select></td>

                        <?php } ?>

                    </table>
                </form>
                <?php
                $TRACKER = $pdo->prepare("SELECT insurer, mtg, lead_up, date_updated, tracker_id, agent, closer, client, phone, current_premium, our_premium, comments, sale, date_updated FROM closer_trackers WHERE closer=:closer AND date_added >= CURDATE() ORDER BY date_added");
                $TRACKER->bindParam(':closer', $hello_name, PDO::PARAM_STR);
                $TRACKER->execute();
                if ($TRACKER->rowCount() > 0) {
                    ?>

                    <table id="tracker" class="table table-hover">
                        <thead>
                        <tr>
                            <th>Row</th>
                            <th>Agent</th>
                            <th>Client</th>
                            <th>Phone</th>
                            <th>Current Premium</th>
                            <th>Our Premium</th>
                            <th>Comments</th>
                            <th>Insurer</th>
                            <th>DISPO</th>
                            <?php if (in_array($hello_name, $Manager_Access)) { ?>
                                <th></th>
                            <?php } ?>
                        </tr>
                        </thead>


                        <?php
                        $i = 0;
                        while ($TRACKERresult = $TRACKER->fetch(PDO::FETCH_ASSOC)) {

                            $i++;

                            $TRK_tracker_id = $TRACKERresult['tracker_id'];
                            $TRK_agent = $TRACKERresult['agent'];
                            $TRK_closer = $TRACKERresult['closer'];
                            $TRK_client = $TRACKERresult['client'];
                            $TRK_phone = $TRACKERresult['phone'];
                            $TRK_current_premium = $TRACKERresult['current_premium'];
                            $TRK_our_premium = $TRACKERresult['our_premium'];
                            $TRK_comments = $TRACKERresult['comments'];
                            $TRK_sale = $TRACKERresult['sale'];
                            $TRK_LEAD_UP = $TRACKERresult['lead_up'];
                            $TRK_MTG = $TRACKERresult['mtg'];
                            $TRK_INSURER = $TRACKERresult['insurer'];
                            ?>

                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $TRK_agent; ?></td>
                                <td><?php echo $TRK_client; ?></td>
                                <td><?php echo $TRK_phone; ?></td>
                                <td><?php echo $TRK_current_premium; ?></td>
                                <td><?php echo $TRK_our_premium; ?></td>
                                <td><?php echo $TRK_comments; ?></td>
                                <td><?php if (isset($TRK_INSURER)) {
                                        echo $TRK_INSURER;
                                    } ?></td>
                                <td><?php if (isset($TRK_sale)) {
                                        echo $TRK_sale;
                                    } ?></td>

                                <?php if (in_array($hello_name, $Manager_Access)) { ?>
                                    <td>
                                        <a href='Tracker.php?query=CloserTrackers&TrackerEdit=<?php echo $TRK_tracker_id; ?>'
                                           class='btn btn-info btn-xs'><i class='fa fa-edit'></i> EDIT</a>
                                    </td>
                                <?php } ?>
                            </tr>
                        <?php }
                        ?>
                    </table>

                <?php }
                ?>

            </div>
        </div>

    <?php }
} ?>

</div>
<script type="text/javascript" src="/resources/lib/clockpicker-gh-pages/assets/js/jquery.min.js"></script>
<script type="text/javascript"
        src="/resources/lib/clockpicker-gh-pages/dist/jquery-clockpicker.min.js"></script>
<script type="text/javascript">
    $('.clockpicker').clockpicker({
        placement: 'bottom',
        align: 'left',
        donetext: 'Done'
    });
</script>
<script type="text/javascript"
        src="/resources/lib/clockpicker-gh-pages/assets/js/highlight.min.js"></script>
<script type="text/javascript" src="/resources/lib/jquery/jquery-3.0.0.min.js"></script>
<script type="text/javascript" src="/resources/lib/jquery-ui-1.11.4/jquery-ui.min.js"></script>
<script src="/resources/templates/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
<script src="/resources/lib/EasyAutocomplete-1.3.3/jquery.easy-autocomplete.min.js"></script>
<script>
    $(function () {
        $("#cbDate").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true
        });
    });
</script>
<script type="text/JavaScript">
    var $select = $('#agent_name');
    $.getJSON('/app/JSON/Agents.php?EXECUTE=1&USER=<?php echo $hello_name; ?>&TOKEN=<?php echo $TOKEN; ?>', function (data) {
        $select.html('agent_name');
        $.each(data, function (key, val) {
            $select.append('<option value="' + val.full_name + '">' + val.full_name + '</option>');
        })
    });
</script>
<script>var options = {
        url: "/app/JSON/Agents.php?EXECUTE=1&USER=<?php echo $hello_name; ?>&TOKEN=<?php echo $TOKEN; ?>",
        getValue: "full_name",
        list: {
            match: {
                enabled: true
            }
        }
    };
    $("#edit_agent_name").easyAutocomplete(options);
</script>

<?php require_once(BASE_URL . '/app/php/toastr.php'); ?>

<div class="closerTrackerLiveResults">

</div>
<script>
    function refresh_div() {
        jQuery.ajax({
            url: '/addon/Trackers/php/closerTrackersLiveResults.php?closer=<?php echo $hello_name; ?>',
            type: 'POST',
            success: function (results) {
                jQuery(".closerTrackerLiveResults").html(results);
            }
        });
    }

    t = setInterval(refresh_div, 10000);
</script>
</body>
</html>
