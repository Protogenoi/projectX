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

if ($ffcalendar == '0') {

    header('Location: /CRMmain.php');
    die;
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

$CHECK_USER_LOGIN->CheckAccessLevel();

$USER_ACCESS_LEVEL = $CHECK_USER_LOGIN->CheckAccessLevel();

$ACCESS_LEVEL = $USER_ACCESS_LEVEL['ACCESS_LEVEL'];

if ($ACCESS_LEVEL < 3) {

    header('Location: /../../../index.php?AccessDenied&USER=' . $hello_name . '&COMPANY=' . $COMPANY_ENTITY);
    die;

}

$ADL_PAGE_TITLE = "Calendar";
require_once(__DIR__ . '/../../app/core/head.php');

?>
<link rel="stylesheet" href="/resources/templates/ADL/Notices.css"/>
<link href='/resources/lib/fullcalendar-3.0.0/fullcalendar.css' rel='stylesheet'/>
<link href='/resources/lib/fullcalendar-3.0.0/fullcalendar.print.css' rel='stylesheet' media='print'/>
<link rel="stylesheet" href="/resources/lib/summernote-master/dist/summernote.css">
<link rel="stylesheet" href="/resources/lib/jquery-ui-1.11.4/jquery-ui.min.css">
<link rel="stylesheet" type="text/css" href="/resources/lib/clockpicker-gh-pages/dist/jquery-clockpicker.min.css">
<link rel="stylesheet" type="text/css" href="/resources/lib/clockpicker-gh-pages/assets/css/github.min.css">
<style>
    .clockpicker-popover {
        z-index: 999999;
    }

    #calendar {
        width: 700px;
        margin: 0 auto;
    }

    .ui-datepicker {
        z-index: 1151 !important;
    }
</style>

<script src='/resources/lib/fullcalendar-3.0.0/lib/moment.min.js'></script>
<script src='/resources/lib/fullcalendar-3.0.0/lib/jquery.min.js'></script>
<script src='/resources/lib/fullcalendar-3.0.0/fullcalendar.min.js'></script>
</head>
<body>

<?php require_once(__DIR__ . '/../../includes/navbar.php'); ?>

<div class="container">

    <?php

    $callback = filter_input(INPUT_GET, 'callback', FILTER_SANITIZE_SPECIAL_CHARS);


    if (isset($callback)) {
        $callbackid = filter_input(INPUT_GET, 'callbackid', FILTER_SANITIZE_NUMBER_INT);
        if ($callback == 'complete') {
            echo "<div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa fa-check-circle-o fa-lg\"></i> Success:</strong> Callback completed!</div>";

        }

        if ($callback == 'incomplete') {
            echo "<div class=\"notice notice-warning\" role=\"alert\"><strong><i class=\"fa fa-check fa-lg\"></i> Success:</strong> Callback set to incomplete!</div>";

        }

    }

    ?>

</div>
<br>
<div class="container">
    <div class="col-md-12">
        <div class="col-md-8">

            <script>
                $(document).ready(function () {

                    $('#calendar').fullCalendar({
                        header: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'listDay,listWeek,month'
                        },

                        views: {
                            listDay: {buttonText: 'list day'},
                            listWeek: {buttonText: 'list week'}
                        },

                        defaultView: 'month',
                        defaultDate: '<?php echo date("Y-m-d"); ?>',
                        navLinks: true, // can click day/week names to navigate views
                        editable: true,
                        eventLimit: true, // allow "more" link when too many events
                        events: '/app/calendar/JSON/GetEvents.php?EXECUTE=1'
                    });

                });
            </script>

            <div id='calendar'></div>

        </div>

        <div class="col-md-4">

            <?php
            if (isset($fflife)) {
                if ($fflife == '1') {

                    $query = $pdo->prepare("SELECT CONCAT(callback_time, ' - ', callback_date) AS calltimeid, callback_date, callback_time, reminder, CONCAT(callback_date, ' - ',callback_time)AS ordersort, client_id, id, client_name, notes, complete from scheduled_callbacks WHERE assign=:hello ORDER BY ordersort ASC");
                    $query->bindParam(':hello', $hello_name, PDO::PARAM_STR, 12);
                }
            }

            $query->execute();
            if ($query->rowCount() > 0) {
                $i = 0;
                ?>

                <table class="table">
                    <thead>
                    <tr>
                        <th colspan='2'><h3><span class="label label-info">Call backs</span></h3></th>
                    </tr>
                    <th>Client</th>
                    <th>Callback</th>
                    <th></th>
                    </thead>

                    <?php
                    while ($calllist = $query->fetch(PDO::FETCH_ASSOC)) {
                        $i++;
                        $callbackid = $calllist['id'];
                        $search = $calllist['client_id'];
                        $NAME = $calllist['client_name'];
                        $TIME = $calllist['calltimeid'];
                        $NOTES = html_entity_decode($calllist['notes']);
                        $REMINDER = $calllist['reminder'];
                        $CB_DATE = $calllist['callback_date'];
                        $CB_TIME = $calllist['callback_time'];

                        echo '<tr>';

                        if ($fflife == '1') {

                            echo "<td class='text-left'><a href='/Life/ViewClient.php?search=$search'>" . $calllist['client_name'] . "</a></td>";

                        }


                        echo "<td class='text-left'>" . $calllist['calltimeid'] . "</td>";

                        if ($fflife == '1') {

                            echo "<td class='text-left'><a data-toggle='modal' data-target='#myModal$i' class=\"btn btn-info btn-xs\"><i class='fa fa-cogs'></i> Options</a></td>";
                        }

                        echo "</tr>"; ?>

                        <div id='myModal<?php echo $i; ?>' class='modal fade' role='dialog'>
                            <div class='modal-dialog modal-lg'>
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                        <button type='button' class='close' data-dismiss='modal'>&times;</button>
                                        <h4 class='modal-title'><?php echo "$NAME ($TIME | Reminder at $REMINDER)"; ?></h4>
                                    </div>
                                    <div class='modal-body'>

                                        <form class="form-horizontal"
                                              action='php/Callbacks.php?search=<?php echo "$search&callbackid=$callbackid&cb=c"; ?>'
                                              method='POST'>
                                            <fieldset>

                                                <div class='container'>
                                                    <div class='row'>
                                                        <div class='col-md-4'>
                                                            <div class='form-group'>
                                                                <select id='getcallback_client' name='callbackclient'
                                                                        class='form-control'>
                                                                    <option value='<?php echo $NAME; ?>'><?php echo $NAME; ?></option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class='row'>
                                                        <div class='col-md-4'>
                                                            <div class='form-group'>
                                                                <select id='assign' name='assign' class='form-control'>
                                                                    <option value='<?php echo $hello_name; ?>'><?php echo $hello_name; ?></option>

                                                                    <?php

                                                                    try {

                                                                        $calluser = $pdo->prepare("SELECT login, real_name from users where extra_info ='User'");

                                                                        $calluser->execute() or die(print_r($calluser->errorInfo(),
                                                                            true));
                                                                        if ($calluser->rowCount() > 0) {
                                                                            while ($row = $calluser->fetch(PDO::FETCH_ASSOC)) {


                                                                                ?>

                                                                                <option value='<?php echo $row['login']; ?>'><?php echo $row['real_name']; ?></option>

                                                                                <?php

                                                                            }

                                                                        }
                                                                    } catch (PDOException $e) {
                                                                        echo 'Connection failed: ' . $e->getMessage();

                                                                    }
                                                                    ?>

                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class='col-md-4'>
                                                            <div class="form-group">
                                                                <div class='input-group date' id='datetimepicker1'>
                                                                    <input type='text' class="form-control"
                                                                           id="callback_date" name="callbackdate"
                                                                           placeholder="YYYY-MM-DD"
                                                                           value="<?php if (isset($CB_DATE)) {
                                                                               echo $CB_DATE;
                                                                           } ?>" required/>
                                                                    <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class='col-md-4'>
                                                            <div class="form-group">
                                                                <div class='input-group date clockpicker'>
                                                                    <input type='text' class="form-control"
                                                                           id="clockpicker" name="callbacktime"
                                                                           placeholder="24 Hour Format"
                                                                           value="<?php if (isset($CB_TIME)) {
                                                                               echo $CB_TIME;
                                                                           } ?>" required/>
                                                                    <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-time"></span>
                                            </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class='col-md-4'>
                                                            <div class="form-group">
                                                                <select id="callreminder" name="callreminder"
                                                                        class="form-control" required>
                                                                    <option value="">Reminder</option>
                                                                    <option value="-5 minutes">5mins</option>
                                                                    <option value="-10 minutes">10mins</option>
                                                                    <option value="-15 minutes">15mins</option>
                                                                    <option value="-20 minutes">20mins</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class='col-md-8'>
                                                            <div class="form-group">
                                                                <textarea class="form-control summernote" id="textarea"
                                                                          name="callbacknotes"
                                                                          placeholder="Call back notes"><?php if (isset($NOTES)) {
                                                                        echo $NOTES;
                                                                    } ?></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="btn-group">
                                                    <button class="btn btn-primary"><i
                                                                class='fa  fa-check-circle-o'></i> Save
                                                    </button>
                                                    <a href='/app/calendar/php/AddCallback.php?search=<?php echo "$search&callbackid=$callbackid&cb=y"; ?>'
                                                       class="btn btn-success"><i class='fa fa-check'></i> Complete</a>
                                                    <a href='/app/calendar/php/AddCallback.php?search=<?php echo "$search&callbackid=$callbackid&cb=n"; ?>'
                                                       class="btn btn-warning"><i class='fa fa-times'></i>
                                                        In-complete</a>
                                                </div>
                                            </fieldset>
                                        </form>

                                    </div>
                                    <div class='modal-footer'>
                                        <button type='button' class='btn btn-default' data-dismiss='modal'>Close
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    <?php } ?>
                </table>

            <?php } else {
                echo "<br><br><div class=\"notice notice-warning\" role=\"alert\"><strong>Info!</strong> No call backs found</div>";

            }

            ?>

        </div>
    </div>
</div>

<script type="text/javascript" language="javascript" src="/resources/lib/jquery-ui-1.11.4/jquery-ui.min.js"></script>
<script type="text/javascript" src="/resources/lib/clockpicker-gh-pages/dist/jquery-clockpicker.min.js"></script>
<script type="text/javascript">
    $('.clockpicker').clockpicker();
    $('.clockpicker').clockpicker()
        .find('input').change(function () {
    });

</script>
<script type="text/javascript" src="/resources/lib/clockpicker-gh-pages/assets/js/highlight.min.js"></script>
<script>
    $(function () {
        $("#callback_date").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true
        });
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
<script src="/resources/templates/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
</body>
</html>
