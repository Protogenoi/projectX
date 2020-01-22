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

require_once(__DIR__ . '/../../../classes/access_user/access_user_class.php');
$page_protect = new Access_user;
$page_protect->access_page(filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS), "", 10);
$hello_name = ($page_protect->user_full_name != "") ? $page_protect->user_full_name : $page_protect->user;

$USER_TRACKING = 0;

require_once(__DIR__ . '/../../../includes/user_tracking.php');

require_once(__DIR__ . '/../../../includes/time.php');

if (isset($FORCE_LOGOUT) && $FORCE_LOGOUT == 1) {
    $page_protect->log_out();
}

require_once(__DIR__ . '/../../../includes/adl_features.php');
require_once(__DIR__ . '/../../../includes/Access_Levels.php');

if (isset($fferror)) {
    if ($fferror == '1') {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }
}

if ($ffanalytics == '1') {
    require_once(__DIR__ . '/../../../app/analyticstracking.php');
}

$REF = filter_input(INPUT_GET, 'REF', FILTER_SANITIZE_SPECIAL_CHARS);
?>
<!DOCTYPE html>
<html lang="en">
<title>Calendar</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href='/resources/lib/fullcalendar-3.0.0/fullcalendar.css' rel='stylesheet'/>
<link href='/resources/lib/fullcalendar-3.0.0/fullcalendar.print.css' rel='stylesheet' media='print'/>
<link rel="stylesheet" href="/resources/templates/bootstrap-3.3.5-dist/cosmo/bootstrap.min.css">
<link rel="stylesheet" href="/resources/templates/bootstrap-3.3.5-dist/cosmo/bootstrap.css">
<link rel="stylesheet" href="/resources/templates/ADL/Notices.css"/>
<link href="/img/favicon.ico" rel="icon" type="image/x-icon"/>
<style>
    #calendar {
        max-width: 900px;
        margin: 0 auto;
    }
</style>
<script type="text/javascript" language="javascript"
        src="/resources/templates/fontawesome/svg-with-js/js/fontawesome-all.js"></script>
<script src='/resources/lib/fullcalendar-3.0.0/lib/moment.min.js'></script>
<script src='/resources/lib/fullcalendar-3.0.0/lib/jquery.min.js'></script>
<script src='/resources/lib/fullcalendar-3.0.0/fullcalendar.min.js'></script>
</head>
<body>

<?php require_once(__DIR__ . '/../../../includes/navbar.php'); ?>

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
            events: '/addon/Staff/Holidays/JSON/GetEvents.php?EXECUTE=1'
        });

    });

</script>

<div class="col-md-4"><h1>Holidays</h1></div>
<div class="col-md-12">
    <div id='calendar'></div>
</div>

<script src="/resources/templates/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
</body>
</html>
