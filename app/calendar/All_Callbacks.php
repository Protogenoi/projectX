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
$page_protect->access_page(filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS), "", 8);
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

if ($ffcalendar == 0) {

    header('Location: /../../../CRMmain.php?FEATURE=DISABLED');
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

if ($ACCESS_LEVEL < 8) {

    header('Location: /../../../index.php?AccessDenied&USER=' . $hello_name . '&COMPANY=' . $COMPANY_ENTITY);
    die;

}

$ADL_PAGE_TITLE = "Active Callbacks";
require_once(__DIR__ . '/../../app/core/head.php');

?>
<link rel="stylesheet" href="/resources/templates/ADL/Notices.css"/>
<link href='/resources/lib/fullcalendar-3.0.0/fullcalendar.css' rel='stylesheet'/>
<link href='/resources/lib/fullcalendar-3.0.0/fullcalendar.print.css' rel='stylesheet' media='print'/>
<style>
    body {
        text-align: center;
        font-size: 14px;
        font-family: "Lucida Grande", Helvetica, Arial, Verdana, sans-serif;

    }


    #calendar {
        width: 900px;
        margin: 0 auto;
    }
</style>
<script src='/resources/lib/fullcalendar-3.0.0/lib/moment.min.js'></script>
<script src='/resources/lib/fullcalendar-3.0.0/lib/jquery.min.js'></script>
<script src='/resources/lib/fullcalendar-3.0.0/fullcalendar.min.js'></script>

<script>
    $(document).ready(function () {
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();

        var calendar = $('#calendar').fullCalendar({
            editable: true,
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },

            events: "php/events.php",

            // Convert the allDay from string to boolean
            eventRender: function (event, element, view) {
                if (event.allDay === 'true') {
                    event.allDay = true;
                } else {
                    event.allDay = false;
                }
            },
            selectable: true,
            selectHelper: true,
            select: function (start, end, allDay) {
                var title = prompt('Event Title:');
                var url = prompt('Type Event url, if exits:');
                if (title) {
                    var start = $.fullCalendar.moment(start).format();
                    var end = $.fullCalendar.moment(end).format();
                    $.ajax({
                        url: 'add_php/events.php',
                        data: 'title=' + title + '&start=' + start + '&end=' + end + '&url=' + url,
                        type: "POST",
                        success: function (json) {
                            alert('Added Successfully');
                        }
                    });
                    calendar.fullCalendar('renderEvent',
                        {
                            title: title,
                            start: start,
                            end: end,
                            allDay: allDay
                        },
                        true // make the event "stick"
                    );
                }
                calendar.fullCalendar('unselect');
            },

            editable: true,
            eventDrop: function (event, delta) {
                var start = $.fullCalendar.moment(event.start).format();
                var end = $.fullCalendar.moment(event.end).format();
                $.ajax({
                    url: 'update_php/events.php',
                    data: 'title=' + event.title + '&start=' + start + '&end=' + end + '&id=' + event.id,
                    type: "POST",
                    success: function (json) {
                        alert("Updated Successfully");
                    }
                });
            },
            eventResize: function (event) {
                var start = $.fullCalendar.moment(event.start).format();
                var end = $.fullCalendar.moment(event.end).format();
                $.ajax({
                    url: 'update_php/events.php',
                    data: 'title=' + event.title + '&start=' + start + '&end=' + end + '&id=' + event.id,
                    type: "POST",
                    success: function (json) {
                        alert("Updated Successfully");
                    }
                });

            },
//eventClick: function(event) {
//var decision = confirm("Delete event?");
//if (decision) {
//$.ajax({
//type: "POST",
//url: "delete_php/events.php",

//data: "&id=" + event.id
//});
//$('#calendar2').fullCalendar('removeEvents', event.id);

//} else {
//}
//}

        });

    });

</script>

</head>
<body>

<?php require_once(__DIR__ . '/../../includes/navbar.php'); ?>

<div class="container">

    <?php

    $callback = filter_input(INPUT_GET, 'callback', FILTER_SANITIZE_SPECIAL_CHARS);


    if (isset($callback)) {

        $callbackid = filter_input(INPUT_GET, 'callbackid', FILTER_SANITIZE_NUMBER_INT);

        if ($callback == 'complete') {

            echo "<div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa fa-check-circle-o fa-lg\"></i> Success:</strong> Callback $callbackcompletedid completed!</div>";

        }

        if ($callback == 'incomplete') {

            echo "<div class=\"notice notice-warning\" role=\"alert\"><strong><i class=\"fa fa-check fa-lg\"></i> Success:</strong> Callback set to incomplete!</div>";

        }

    }

    ?>

</div>

<div id='calendar'></div>


<div class="container">


    <?php
    if (isset($fflife)) {
        if ($fflife == '1') {

            $query = $pdo->prepare("SELECT CONCAT(callback_time, ' - ', callback_date) AS calltimeid, CONCAT(callback_date, ' - ',callback_time)AS ordersort, client_id, id, client_name, notes, complete from scheduled_callbacks WHERE complete ='N' ORDER BY ordersort DESC");
        }
    }
    ?>

    <table class="table">
        <thead>
        <tr>
            <th colspan='2'><h3><span class="label label-info">Active Call backs</span></h3></th>
        </tr>
        <th>Client</th>
        <th>Callback Time</th>
        <th>Notes</th>
        <th colspan="2">Callback Status</th>
        </thead>

        <?php

        $query->execute();
        if ($query->rowCount() > 0) {
            while ($calllist = $query->fetch(PDO::FETCH_ASSOC)) {

                $callbackid = $calllist['id'];
                $search = $calllist['client_id'];

                echo '<tr>';

                if ($fflife == '1') {

                    echo "<td class='text-left'><a href='/Life/ViewClient.php?search=$search' target='_blank'>" . $calllist['client_name'] . "</a></td>";

                }

                echo "<td class='text-left'>" . $calllist['calltimeid'] . "</td>";
                echo "<td class='text-left'>" . $calllist['notes'] . "</td>";

                if ($fflife == '1') {

                    echo "<td class='text-left'><a href='/php/AddCallback.php?search=$search&callbackid=$callbackid&cb=y' class=\"btn btn-success btn-xs\"><i class='fa fa-check'></i> Complete</a></td>";
                    echo "<td class='text-left'><a href='/php/AddCallback.php?search=$search&callbackid=$callbackid&cb=n' class=\"btn btn-warning btn-xs\"><i class='fa fa-times'></i> In-complete</a></td>";

                }

                echo "</tr>";

            }

        } else {
            echo "<br><br><div class=\"notice notice-warning\" role=\"alert\"><strong>Info!</strong> No call backs found</div>";

        }

        ?>

    </table>


</div>
<script src="/resources/templates/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
</body>
</html>
