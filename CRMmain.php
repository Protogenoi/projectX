<?php
/** @noinspection PhpIncludeInspection */

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
 *  Composer - https://getcomposer.org/doc/
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
 *  Ideal Postcodes - https://ideal-postcodes.co.uk/documentation
 *  Chart.js - https://github.com/chartjs/Chart.js
 */

require_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_SPECIAL_CHARS) . '/app/core/doc_root.php';

require_once(BASE_URL . '/classes/access_user/access_user_class.php');
$page_protect = new Access_user;
$page_protect->access_page(filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS), "", 1);
$hello_name = ($page_protect->user_full_name != "") ? $page_protect->user_full_name : $page_protect->user;

$USER_TRACKING = 0;

require_once(BASE_URL . '/includes/user_tracking.php');

$LOGOUT_ACTION = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_SPECIAL_CHARS);
$FEATURE = filter_input(INPUT_GET, 'FEATURE', FILTER_SANITIZE_SPECIAL_CHARS);

if (isset($LOGOUT_ACTION) && $LOGOUT_ACTION == "log_out") {
    $page_protect->log_out();
}

require_once(BASE_URL . '/includes/time.php');

if (isset($FORCE_LOGOUT) && $FORCE_LOGOUT == 1) {
    $page_protect->log_out();
}

require_once(BASE_URL . '/includes/adl_features.php');
require_once(BASE_URL . '/includes/Access_Levels.php');
require_once(BASE_URL . '/includes/adlfunctions.php');

if (isset($ffanalytics) && $ffanalytics == 1) {
    require_once(BASE_URL . '/app/analyticstracking.php');
}

if (isset($fferror) && $fferror == 1) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

getRealIpAddr();
$TRACKED_IP = getRealIpAddr();

if (!in_array($hello_name, $anyIPAccess, true)) { // ALLOW USER TO CONNECT FROM ANY IP

    if (!in_array($TRACKED_IP,
        $allowedIPAccess)) { //IF THE ABOVE IS FALSE ONLY ALLOW NORNAL USERS TO CONNECT FROM IPs IN ARRAY $allowedIPAccess
        $page_protect->log_out();
    }
}

require_once(BASE_URL . '/classes/database_class.php');
require_once(BASE_URL . '/class/login/login.php');
$CHECK_USER_LOGIN = new UserActions($hello_name, "NoToken");
$CHECK_USER_LOGIN->UpdateToken();
$CHECK_USER_LOGIN->CheckAccessLevel();

$USER_ACCESS_LEVEL = $CHECK_USER_LOGIN->CheckAccessLevel();

$ACCESS_LEVEL = $USER_ACCESS_LEVEL['ACCESS_LEVEL'];

if ($ACCESS_LEVEL <= 0) {

    header('Location: index.php?AccessDenied&USER=' . $hello_name . '&COMPANY=' . $COMPANY_ENTITY);
    die;

}

$ADL_PAGE_TITLE = "Main";
require_once(BASE_URL . '/app/core/head.php');

?>
<script type="text/javascript" src="/resources/lib/jquery/jquery-3.0.0.min.js"></script>
<script type="text/javascript" src="/resources/lib/jquery-ui-1.11.4/jquery-ui.min.js"></script>
<script type="text/javascript" src="/resources/lib/jquery-ui-1.11.4/external/jquery/jquery.js"></script>
<script type="text/javascript" src="/resources/templates/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/resources/lib/toastr/js/toastr.min.js"></script>
</head>
<body>
<?php require_once(BASE_URL . '/includes/navbar.php');

?>

<div class="container">

    <div class="col-xs-12 .col-md-8">

        <div class="row">
            <div class="twelve columns">
                <ul class="ca-menu">

                    <li>
                        <a href="/addon/support/main.php">
                            <span class="ca-icon"><i class="fas fa-list-alt"></i></span>
                            <div class="ca-content">
                                <h2 class="ca-main">To Do<br/></h2>
                                <h3 class="ca-sub"></h3>
                            </div>
                        </a>
                    </li>

                </ul>
            </div>
        </div>
    </div>

</div>

<div id="padAlerts" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="label label-primary">Callback alerts</span></h4>
            </div>
            <div class="modal-body">
                <?php

                $nowTime = date("H:i:s");

                $query = $pdo->prepare("SELECT client_id, addedBy, updatedBy, customer, comments, status, timeDeadline,  timeReminder, dayDeadline FROM pad_stats WHERE dayDeadline = CURDATE() AND timeReminder <= :timeReminder OR dayDeadline <= CURDATE()");
                $query->bindParam(':timeReminder', $nowTime, PDO::PARAM_STR);
                $query->execute();
                if ($query->rowCount() >= 1) {
                ?>
                <script type="text/javascript">
                    $(window).load(function () {
                        $('#padAlerts').modal('show');
                    });
                </script>

                <table class="table">
                    <thead>
                    <tr>
                        <th>Client</th>
                        <th>Day</th>
                        <th>Time</th>
                        <th>Comments</th>
                        <th>Status</th>
                        <th>Added by</th>
                        <th>Updated by</th>
                    </tr>
                    </thead>

                    <?php
                    while ($padResults = $query->fetch(PDO::FETCH_ASSOC)) {

                        $client_id = $padResults['client_id'];

                        ?>

                        <tr>
                            <td>
                                <a href="app/Client.php?search=<?php echo $client_id; ?>"><?php echo $padResults['customer']; ?></a>
                            </td>
                            <td><?php echo $padResults['dayDeadline']; ?></td>
                            <td><?php echo $padResults['timeDeadline']; ?></td>
                            <td><?php echo html_entity_decode($padResults['comments']); ?></td>
                            <td><?php echo $padResults['status']; ?></td>
                            <td><?php echo $padResults['addedBy']; ?></td>
                            <td><?php echo $padResults['updatedBy']; ?></td>
                        </tr>

                        <?php
                    }
                    }

                    $query = $pdo->prepare("SELECT client_id_fk, addedBy, updatedBy, customer, notes, status, timeDeadline,  timeReminder, dayDeadline FROM ews_stats WHERE dayDeadline = CURDATE() AND timeReminder <= :timeReminder OR dayDeadline <= CURDATE()");
                    $query->bindParam(':timeReminder', $nowTime, PDO::PARAM_STR);
                    $query->execute();
                    if ($query->rowCount() >= 1) {
                    ?>
                    <script type="text/javascript">
                        $(window).load(function () {
                            $('#padAlerts').modal('show');
                        });
                    </script>

                    <table class="table">
                        <thead>
                        <tr>
                            <th>Client</th>
                            <th>Day</th>
                            <th>Time</th>
                            <th>Comments</th>
                            <th>Status</th>
                            <th>Added by</th>
                            <th>Updated by</th>
                        </tr>
                        </thead>

                        <?php
                        while ($padResults = $query->fetch(PDO::FETCH_ASSOC)) {

                            $client_id = $padResults['client_id_fk'];

                            ?>

                            <tr>
                                <td>
                                    <a href="app/Client.php?search=<?php echo $client_id; ?>"><?php echo $padResults['customer']; ?></a>
                                </td>
                                <td><?php echo $padResults['dayDeadline']; ?></td>
                                <td><?php echo $padResults['timeDeadline']; ?></td>
                                <td><?php echo html_entity_decode($padResults['notes']); ?></td>
                                <td><?php echo $padResults['status']; ?></td>
                                <td><?php echo $padResults['addedBy']; ?></td>
                                <td><?php echo $padResults['updatedBy']; ?></td>
                            </tr>

                            <?php
                        }
                        }

                        $query = $pdo->prepare("SELECT client_id, clientName, closer ,comments, sale, timeDeadline, dayDeadline, updatedBy FROM closer_trackers JOIN potential_clients on potential_clients.phoneNumber = closer_trackers.phone WHERE dayDeadline <= CURDATE()");
                        $query->execute();
                        if ($query->rowCount() >= 1) {
                        ?>
                        <script type="text/javascript">
                            $(window).load(function () {
                                $('#padAlerts').modal('show');
                            });
                        </script>

                        <table class="table">
                            <thead>
                            <tr>
                                <th>Client</th>
                                <th>Day</th>
                                <th>Time</th>
                                <th>Comments</th>
                                <th>Status</th>
                                <th>Added by</th>
                                <th>Updated by</th>
                            </tr>
                            </thead>

                            <?php
                            while ($padResults = $query->fetch(PDO::FETCH_ASSOC)) {

                                $client_id = $padResults['client_id'];

                                ?>

                                <tr>
                                    <td>
                                        <a href="/addon/Trackers/client.php?search=<?php echo $client_id; ?>"><?php echo $padResults['clientName']; ?></a>
                                    </td>
                                    <td><?php echo $padResults['dayDeadline']; ?></td>
                                    <td><?php echo $padResults['timeDeadline']; ?></td>
                                    <td><?php echo html_entity_decode($padResults['comments']); ?></td>
                                    <td><?php echo $padResults['sale']; ?></td>
                                    <td><?php echo $padResults['closer']; ?></td>
                                    <td><?php echo $padResults['updatedBy']; ?></td>
                                </tr>

                                <?php
                            }
                            }
                            ?>

                        </table>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal"><i
                        class='far fa-window-close'></i>
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<?php

if (isset($ffcallbacks) && $ffcallbacks == 1) {
    ?>

    <div id="pappoint" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Appointment and callback reminders</h4>
                </div>
                <div class="modal-body">
                    <?php
                    if (isset($fflife) && $fflife == 1) {

                        $set_time = date("G:i", strtotime('-30 minutes'));
                        $set_time_to = date("G:i", strtotime('+20 minutes'));

                        $query = $pdo->prepare("SELECT client_id, callback_time AS calltimeid, reminder, client_name, notes from scheduled_callbacks WHERE callback_date = CURDATE() AND reminder <= :timeto AND reminder >= :time AND complete='N' and assign =:hello");
                        $query->bindParam(':hello', $hello_name, PDO::PARAM_STR, 12);
                        $query->bindParam(':time', $set_time, PDO::PARAM_STR);
                        $query->bindParam(':timeto', $set_time_to, PDO::PARAM_STR);
                        echo "<table class=\"table\">";

                        echo "  <thead>
        <tr>
        <th><h3><span class=\"label label-primary\">Call back Reminders</span></h3></th>
        </tr>
        <tr>
        <th>Client</th>
        <th>Call back</th>
        <th>Reminder</th>
        <th>Notes</th>
        <th>Options</th>
        </tr>
        </thead>";

                        $query->execute();
                        if ($query->rowCount() >= 1) {
                            ?>
                            <script type="text/javascript">
                                $(window).load(function () {
                                    $('#pappoint').modal('show');
                                });
                            </script>
                            <?php
                            while ($calllist = $query->fetch(PDO::FETCH_ASSOC)) {
                                $NOTES_MESSAGE = html_entity_decode($calllist['notes']);
                                $client_id = $calllist['client_id'];

                                echo '<tr>';
                                echo "<td>" . $calllist['client_name'] . "</td>";
                                echo "<td>" . $calllist['calltimeid'] . "</td>";
                                echo "<td>" . $calllist['reminder'] . "</td>";
                                echo "<td>$NOTES_MESSAGE</td>";
                                echo "<form method='GET' action='Life/ViewClient.php'> <input type='hidden' value='$client_id' name='search'>";
                                echo "<td><button type=\"submit\" class=\"btn btn-default btn-xs\"><i class='fa fa-folder-open'></i> </button></td></form>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<br><br><div class=\"notice notice-warning\" role=\"alert\"><strong>Info!</strong> No call backs for today found</div>";
                        }
                        echo "</table>";


                        $query = $pdo->prepare("SELECT client_id, callback_time AS calltimeid, reminder, client_name, notes from scheduled_callbacks WHERE callback_date = CURDATE() AND complete='N' and assign =:hello");
                        $query->bindParam(':hello', $hello_name, PDO::PARAM_STR, 12);
                        echo "<table class=\"table\">";

                        echo "  <thead>
        <tr>
        <th><h3><span class=\"label label-primary\">Todays Call backs</span></h3></th>
        </tr>
        <tr>
        <th>Client</th>
        <th>Call back</th>
        <th>Reminder</th>
        <th>Notes</th>
        <th>Options</th>
        </tr>
        </thead>";

                        $query->execute();
                        if ($query->rowCount() >= 1) {

                            while ($calllist = $query->fetch(PDO::FETCH_ASSOC)) {
                                $NOTES_MESSAGE = html_entity_decode($calllist['notes']);
                                $client_id = $calllist['client_id'];

                                echo '<tr>';
                                echo "<td>" . $calllist['client_name'] . "</td>";
                                echo "<td>" . $calllist['calltimeid'] . "</td>";
                                echo "<td>" . $calllist['reminder'] . "</td>";
                                echo "<td>$NOTES_MESSAGE</td>";
                                echo "<form method='GET' action='Life/ViewClient.php'> <input type='hidden' value='$client_id' name='search'>";
                                echo "<td><button type=\"submit\" class=\"btn btn-default btn-xs\"><i class='fa fa-folder-open'></i> </button></td></form>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<br><br><div class=\"notice notice-warning\" role=\"alert\"><strong>Info!</strong> No call backs for today found</div>";
                        }
                        echo "</table>";
                    }

                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal"><i
                            class='fa fa-close'></i> Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php

}

require_once(BASE_URL . '/app/Holidays.php');

if (isset($hello_name)) {
    if ($XMASDate == '25th of December') {
        //if ($XMASDate == 'December') {
        $SANTA_TIME = date("H");

        ?>
        <audio autoplay>
            <source src="/app/sounds/<?php echo $XMAS_ARRAY[$RAND_XMAS_ARRAY[0]]; ?>" type="audio/mpeg">
        </audio>
        <?php

    }

    if ($HALLOWEEN == '31st of October' || $HALLOWEEN == '30th of October') { ?>
        <!--    <style>
                body {
                    background-image: url("https://x.adl-crm.uk/img/552086.jpg");
                }
            </style>-->

        <audio autoplay>
            <source src="/app/sounds/halloween/<?php echo $RAND_HALLOWEEN_ARRAY; ?>" type="audio/mpeg">
        </audio>
    <?php }
}

if (isset($matrix) && $matrix == 1) { ?>
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        canvas {
            display: block;
        }

        body {
            background: black;
        }
    </style>
    <script>
        const c = document.getElementById('c');
        const cxt = c.getContext("2d");

        c.width = window.innerWidth;
        c.height = window.innerHeight;

        let chinese = "田由甲申甴电甶男甸甹町画甼甽甾甿畀畁畂畃畄畅畆畇畈畉畊畋界畍畎畏畐畑";
        chinese = chinese.split("");

        const font_size = 10;
        const columns = c.width / font_size;

        const drops = [];

        for (let x = 0; x < columns; x++) {
            drops[x] = 1;
        }

        function draw() {
            cxt.fillStyle = "rgba(0,0,0,0.05)";
            cxt.fillRect(0, 0, c.width, c.height);

            cxt.fillStyle = "#0F0";
            cxt.font = font_size + 'px arial';


            for (let i = 0; i < drops.length; i++) {
                const text = chinese[Math.floor(Math.random() * chinese.length)];
                cxt.fillText(text, i * font_size, drops[i] * font_size);

                if (drops[i] * font_size > c.height && Math.random() > 0.975)
                    drops[i] = 0;
                drops[i]++;
            }

        }

        setInterval(draw, 33);
    </script>
<?php } ?>
</body>
</html>
