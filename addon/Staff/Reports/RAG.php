<?php
/** @noinspection ALL */
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

require_once(BASE_URL . '/classes/access_user/access_user_class.php');
$page_protect = new Access_user;
$page_protect->access_page(filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS), "", 10);
$hello_name = ($page_protect->user_full_name != "") ? $page_protect->user_full_name : $page_protect->user;

$USER_TRACKING = 0;

require_once(BASE_URL . '/includes/user_tracking.php');

require_once(BASE_URL . '/includes/time.php');

if (isset($FORCE_LOGOUT) && $FORCE_LOGOUT == 1) {
    $page_protect->log_out();
}

require_once(BASE_URL . '/includes/adl_features.php');
require_once(BASE_URL . '/includes/Access_Levels.php');
require_once(BASE_URL . '/includes/ADL_PDO_CON.php');

if (isset($ffanalytics) && $ffanalytics == '1') {
    require_once(BASE_URL . '/app/analyticstracking.php');
}

if (isset($fferror) && $fferror == 1) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

$MONTH = filter_input(INPUT_GET, 'MONTH', FILTER_SANITIZE_SPECIAL_CHARS);
$YEAR = filter_input(INPUT_GET, 'YEAR', FILTER_SANITIZE_SPECIAL_CHARS);

$RETURN = filter_input(INPUT_GET, 'RETURN', FILTER_SANITIZE_SPECIAL_CHARS);

if (isset($RETURN)) {
    $DATE = filter_input(INPUT_GET, 'DATE', FILTER_SANITIZE_SPECIAL_CHARS);
} else {
    $DATE = filter_input(INPUT_POST, 'DATE', FILTER_SANITIZE_SPECIAL_CHARS);
}

$ADL_PAGE_TITLE = "RAG";
require_once(BASE_URL . '/app/core/head.php');

?>
<link rel="stylesheet" href="/resources/lib/sweet-alert/sweet-alert.min.css"/>
<link rel="stylesheet" href="/resources/lib/jquery-ui-1.11.4/jquery-ui.min.css">
</head>
<body>

<?php require_once(BASE_URL . '/includes/navbar.php'); ?>

<div class="container">

    <?php if (isset($RETURN)) {
        if ($RETURN == 'WEEKSTATS') {

            $dateFrom = filter_input(INPUT_POST, 'dateFrom', FILTER_SANITIZE_SPECIAL_CHARS);
            $dateTo = filter_input(INPUT_POST, 'dateTo', FILTER_SANITIZE_SPECIAL_CHARS);
            if (empty($dateFrom)) {
                $dateFrom = filter_input(INPUT_GET, 'dateFrom', FILTER_SANITIZE_SPECIAL_CHARS);
                $dateTo = filter_input(INPUT_GET, 'dateTo', FILTER_SANITIZE_SPECIAL_CHARS);

            }

            ?>

            <div class='notice notice-default' role='alert'>
                <h1><strong>
                        <div style="text-align: center;"><?php if (isset($dateFrom)) {
                                echo "RAG Week Stats search: $dateFrom - $dateTo";
                            } ?></div>
                    </strong></h1>
            </div>
            <br>
            <div class="row fixed-toolbar">
                <div class="col-xs-5">
                    <a href="RAG.php" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back</a>
                </div>
                <div class="col-xs-7">
                    <div class="text-right">
                        <a class="btn btn-primary"
                           href='/addon/Staff/Export/Export.php?EXECUTE=1&dateFrom=<?php echo "$dateFrom&dateTo=$dateTo"; ?>'><i
                                class="fa fa-download"></i> Export</a>
                        <a class="btn btn-warning"
                           href='?RETURN=AVERAGESTATS&dateFrom=<?php echo "$dateFrom&dateTo=$dateTo"; ?>'><i
                                class="fa fa-chart-line "></i> Average Stats</a>
                        <a class="btn btn-info"
                           href='?RETURN=REGISTERSTATS&dateFrom=<?php echo $dateFrom; ?>&dateTo=<?php echo $dateTo; ?>'><i
                                class="fa fa-calendar-alt"></i> Employee Register</a>
                    </div>
                </div>
            </div>
            <br>

            <?php

            $RAG_WEEK_QRY = $pdo->prepare("SELECT SUM(lead_rag.cancels) AS cancels, campaign, CONCAT(employee_details.firstname, ' ', employee_details.lastname) AS NAME, SUM(lead_rag.sales) AS sales, SUM(lead_rag.hours) AS hours, SUM(lead_rag.minus) AS minus, SUM(lead_rag.leads) AS leads FROM lead_rag JOIN employee_details ON employee_details.employee_id = lead_rag.employee_id WHERE substr(lead_rag.date,5) between :dateFrom AND :dateTo GROUP BY NAME");
            $RAG_WEEK_QRY->bindParam(':dateFrom', $dateFrom, PDO::PARAM_STR);
            $RAG_WEEK_QRY->bindParam(':dateTo', $dateTo, PDO::PARAM_STR);
            $RAG_WEEK_QRY->execute();
            if ($RAG_WEEK_QRY->rowCount() > 0) { ?>

                <div class="row">
                <table class="table">
                <tr>
                    <th>Employee</th>
                    <th>TOTAL SALES</th>
                    <th>TOTAL LEADS</th>
                    <th>TOTAL CR</th>
                    <th>TOTAL CANCELS</th>
                    <th>TOTAL Hours</th>
                    <th>TOTAL Minus</th>
                    <th>Camp</th>
                </tr>
                <?php

                while ($result = $RAG_WEEK_QRY->fetch(PDO::FETCH_ASSOC)) {

                    $SALES = $result['sales'];
                    $LEADS = $result['leads'];
                    $HOURS = $result['hours'];
                    $MINUS = $result['minus'];
                    $NAME = $result['NAME'];
                    $CANCELS = $result['cancels'];
                    $campaign = $result['campaign'];

                    ?>

                    <tr>
                        <td><input type="text" class="form-control" readonly value="<?php echo $NAME; ?>"
                                   name="EMPLOYEE"></td>
                        <td><input type="text" class="form-control" readonly value="<?php echo $SALES; ?>" name="SALES">
                        </td>
                        <td><input type="text" class="form-control" readonly value="<?php echo $LEADS; ?>" name="LEADS">
                        </td>
                        <td><input type="text" class="form-control" readonly
                                   value="<?php if (isset($SALES) && isset($LEADS)) {
                                       if ($LEADS > 0) {
                                           $var = $SALES / $LEADS;
                                           echo number_format((float)$var, 2, '.', '');
                                       }
                                   } ?>" name="CR"></td>
                        <td><input type="text" class="form-control" readonly value="<?php echo $CANCELS; ?>"
                                   name="CANCELS"></td>
                        <td><input type="text" class="form-control" readonly value="<?php echo $HOURS; ?>" name="HOURS">
                        </td>
                        <td><input type="text" class="form-control" readonly value="<?php echo $MINUS; ?>" name="MINUS">
                        </td>
                        <td><input type="text" class="form-control" readonly value="<?php echo $campaign; ?>"
                                   name="campaign"></td>
                    </tr>

                <?php } ?> </table><?php } ?>

            </div>
            <?php
        }

        if ($RETURN == 'AVERAGESTATS') {

            $dateFrom = filter_input(INPUT_GET, 'dateFrom', FILTER_SANITIZE_SPECIAL_CHARS);
            $dateTo = filter_input(INPUT_GET, 'dateTo', FILTER_SANITIZE_SPECIAL_CHARS);

            ?>

            <div class='notice notice-default' role='alert'>
                <h1><strong>
                        <div style="text-align: center;"><?php if (isset($dateFrom)) {
                                echo "RAG Average stats search: $dateFrom - $dateTo";
                            } ?></div>
                    </strong></h1>
            </div>
            <br>
            <div class="row fixed-toolbar">
                <div class="col-xs-5">
                    <a href="RAG.php" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back</a>
                </div>
                <div class="col-xs-7">
                    <div class="text-right">
                        <a class="btn btn-warning"
                           href='?RETURN=WEEKSTATS&dateFrom=<?php echo $dateFrom; ?>&dateTo=<?php echo $dateTo; ?>'><i
                                class="fa fa-chart-line"></i> Week Stats</a>
                        <a class="btn btn-info"
                           href='?RETURN=REGISTERSTATS&dateFrom=<?php echo $dateFrom; ?>&dateTo=<?php echo $dateTo; ?>'><i
                                class="fa fa-calendar-alt"></i> Employee Register</a>
                    </div>
                </div>
            </div>
            <br>
            <?php

            $RAG_WEEK_QRY = $pdo->prepare("SELECT AVG(lead_rag.cancels) AS cancels, campaign, CONCAT(employee_details.firstname, ' ', employee_details.lastname) AS NAME, AVG(lead_rag.sales) AS sales, AVG(lead_rag.hours) AS hours, AVG(lead_rag.minus) AS minus, AVG(lead_rag.leads) AS leads FROM lead_rag JOIN employee_details ON employee_details.employee_id = lead_rag.employee_id WHERE substr(lead_rag.date,5) between :dateFrom AND :dateTo GROUP BY lead_rag.employee_id");
            $RAG_WEEK_QRY->bindParam(':dateFrom', $dateFrom, PDO::PARAM_STR);
            $RAG_WEEK_QRY->bindParam(':dateTo', $dateTo, PDO::PARAM_STR);
            $RAG_WEEK_QRY->execute();
            if ($RAG_WEEK_QRY->rowCount() > 0) { ?>

                <div class="row">
                <table class="table">
                <tr>
                    <th>Employee</th>
                    <th>AVG SALES</th>
                    <th>AVG LEADS</th>
                    <th>AVG CR</th>
                    <th>AVG CANCELS</th>
                    <th>AVG Hours</th>
                    <th>AVG Minus</th>
                    <th>Camp</th>
                </tr>
                <?php

                while ($result = $RAG_WEEK_QRY->fetch(PDO::FETCH_ASSOC)) {

                    $SALES = $result['sales'];
                    $LEADS = $result['leads'];
                    $HOURS = $result['hours'];
                    $MINUS = $result['minus'];
                    $NAME = $result['NAME'];
                    $CANCELS = $result['cancels'];
                    $campaign = $result['$campaign'];

                    ?>

                    <tr>
                        <td><input type="text" class="form-control" readonly value="<?php echo $NAME; ?>"
                                   name="EMPLOYEE"></td>
                        <td><input type="text" class="form-control" readonly value="<?php echo $SALES; ?>" name="SALES">
                        </td>
                        <td><input type="text" class="form-control" readonly value="<?php echo $LEADS; ?>" name="LEADS">
                        </td>
                        <td><input type="text" class="form-control" readonly
                                   value="<?php if (isset($SALES) && isset($LEADS)) {
                                       if ($LEADS > 0) {
                                           $var = $SALES / $LEADS;
                                           echo number_format((float)$var, 2, '.', '');
                                       }
                                   } ?>" name="CR"></td>
                        <td><input type="text" class="form-control" readonly value="<?php echo $CANCELS; ?>"
                                   name="CANCELS"></td>
                        <td><input type="text" class="form-control" readonly value="<?php echo $HOURS; ?>" name="HOURS">
                        </td>
                        <td><input type="text" class="form-control" readonly value="<?php echo $MINUS; ?>" name="MINUS">
                        </td>
                        <td><input type="text" class="form-control" readonly value="<?php echo $campaign; ?>"
                                   name="campaign"></td>
                    </tr>

                <?php } ?> </table><?php } ?>

            </div>
            <?php
        }

        if ($RETURN == 'REGISTERSTATS') {

            $dateFrom = filter_input(INPUT_GET, 'dateFrom', FILTER_SANITIZE_SPECIAL_CHARS);
            $dateTo = filter_input(INPUT_GET, 'dateTo', FILTER_SANITIZE_SPECIAL_CHARS);

            ?>

            <div class='notice notice-default' role='alert'>
                <h1><strong>
                        <div style="text-align: center;"><?php if (isset($dateFrom)) {
                                echo "RAG Employee Register stats search: $dateFrom - $dateTo";
                            } ?></div>
                    </strong></h1>
            </div>
            <br>
            <div class="row fixed-toolbar">
                <div class="col-xs-5">
                    <a href="RAG.php" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back</a>
                </div>
                <div class="col-xs-7">
                    <div class="text-right">
                        <a class="btn btn-warning"
                           href='?RETURN=WEEKSTATS&dateFrom=<?php echo $dateFrom; ?>&dateTo=<?php echo $dateTo; ?>'><i
                                class="fa fa-chart-line"></i> Week Stats</a>
                        <a class="btn btn-warning"
                           href='?RETURN=AVERAGESTATS&dateFrom=<?php echo "$dateFrom&dateTo=$dateTo"; ?>'><i
                                class="fa fa-chart-line "></i> Average Stats</a>
                    </div>
                </div>
            </div>
            <br>
            <?php

            $RAG_WEEK_QRY = $pdo->prepare("SELECT CONCAT(employee_details.firstname, ' ', employee_details.lastname) AS NAME, SUM(worked) as worked, SUM(holiday) AS holiday, SUM(sick) as sick, SUM(awol) as awol, SUM(training) as training, SUM(authorised) as authorised FROM lead_rag JOIN employee_details ON employee_details.employee_id = lead_rag.employee_id JOIN employee_register ON employee_register.lead_rag_id = lead_rag.id WHERE substr(lead_rag.date,5) between :dateFrom AND :dateTo GROUP BY lead_rag.employee_id");
            $RAG_WEEK_QRY->bindParam(':dateFrom', $dateFrom, PDO::PARAM_STR);
            $RAG_WEEK_QRY->bindParam(':dateTo', $dateTo, PDO::PARAM_STR);
            $RAG_WEEK_QRY->execute();
            if ($RAG_WEEK_QRY->rowCount() > 0) { ?>

                <div class="row">
                <table class="table">
                <tr>
                    <th>Employee</th>
                    <th>Days Worked</th>
                    <th>Holidays</th>
                    <th>Sickness</th>
                    <th>AWOL</th>
                    <th>Days Training</th>
                    <th>Authorised Leave</th>
                </tr>
                <?php

                while ($result = $RAG_WEEK_QRY->fetch(PDO::FETCH_ASSOC)) {

                    $WORKED = $result['worked'];
                    $HOLIDAY = $result['holiday'];
                    $SICK = $result['sick'];
                    $AWOL = $result['awol'];
                    $TRAINING = $result['training'];
                    $AUTHORISED = $result['authorised'];
                    $NAME = $result['NAME'];

                    ?>

                    <tr>
                        <td><input type="text" class="form-control" readonly value="<?php echo $NAME; ?>"
                                   name="EMPLOYEE"></td>
                        <td><input type="text" class="form-control" readonly value="<?php echo $WORKED; ?>"
                                   name="WORKED"></td>
                        <td><input type="text" class="form-control" readonly value="<?php echo $HOLIDAY; ?>"
                                   name="HOLIDAYS"></td>
                        <td><input type="text" class="form-control" readonly value="<?php echo $SICK; ?>" name="SICK">
                        </td>
                        <td><input type="text" class="form-control" readonly value="<?php echo $AWOL; ?>" name="AWOL">
                        </td>
                        <td><input type="text" class="form-control" readonly value="<?php echo $TRAINING; ?>"
                                   name="TRAINING"></td>
                        <td><input type="text" class="form-control" readonly value="<?php echo $AUTHORISED; ?>"
                                   name="Authorised"></td>
                    </tr>

                <?php } ?> </table><?php } ?>

            </div>
            <?php
        }


    }
    ?>
    <?php if (empty($MONTH) && $RETURN != 'WEEKSTATS' && $RETURN != 'AVERAGESTATS' && $RETURN != 'REGISTERSTATS') { ?>

        <div class="row">
            <div class="col-sm-6">
                <th><a href="?MONTH=JAN&YEAR=<?php echo date('Y'); ?>" class="btn btn-default btn-lg"><i
                            class="fa fa-calendar-alt"></i><br> JAN <br>2019</a></th>
                <th><a href="?MONTH=FEB&YEAR=<?php echo date('Y'); ?>" class="btn btn-warning btn-lg"><i
                            class="fa fa-calendar-alt"></i><br> FEB <br>2019</a></th>
                <th><a href="?MONTH=MAR&YEAR=<?php echo date('Y'); ?>" class="btn btn-default btn-lg"><i
                            class="fa fa-calendar-alt"></i><br> MAR <br>2019</a></th>
            </div>
            <div class="col-sm-6">
                <th><a href="?MONTH=JUL&YEAR=<?php echo date('Y'); ?>" class="btn btn-default btn-lg"><i
                            class="fa fa-calendar-alt"></i><br> JUL <br>2019</a></th>
                <th><a href="?MONTH=AUG&YEAR=<?php echo date('Y'); ?>" class="btn btn-warning btn-lg"><i
                            class="fa fa-calendar-alt"></i><br> AUG <br>2019</a></th>
                <th><a href="?MONTH=SEP&YEAR=<?php echo date('Y'); ?>" class="btn btn-default btn-lg"><i
                            class="fa fa-calendar-alt"></i><br> SEP <br>2019</a></th>
            </div>
            <div class="col-sm-6">

            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <th><a href="?MONTH=APR&YEAR=<?php echo date('Y'); ?>" class="btn btn-default btn-lg"><i
                            class="fa fa-calendar-alt"></i><br> APR <br>2019</a></th>
                <th><a href="?MONTH=MAY&YEAR=<?php echo date('Y'); ?>" class="btn btn-warning btn-lg"><i
                            class="fa fa-calendar-alt"></i><br> MAY <br>2019</a></th>
                <th><a href="?MONTH=JUN&YEAR=<?php echo date('Y'); ?>" class="btn btn-default btn-lg"><i
                            class="fa fa-calendar-alt"></i><br> JUN <br>2019</a></th>
            </div>
            <div class="col-sm-6">
                <th><a href="?MONTH=OCT&YEAR=<?php echo date('Y'); ?>" class="btn btn-default btn-lg"><i
                            class="fa fa-calendar-alt"></i><br> OCT <br>2019</a></th>
                <th><a href="?MONTH=NOV&YEAR=<?php echo date('Y'); ?>" class="btn btn-warning btn-lg"><i
                            class="fa fa-calendar-alt"></i><br> NOV <br>2019</a></th>
                <th><a href="?MONTH=DEC&YEAR=<?php echo date('Y'); ?>" class="btn btn-default btn-lg"><i
                            class="fa fa-calendar-alt"></i><br> DEC <br>2019</a></th>
            </div>
            <div class="col-sm-6">

            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">

            </div>
            <div class="col-sm-6">
            </div>
            <div class="col-sm-6">
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
            </div>
            <div class="col-sm-6">
            </div>
            <div class="col-sm-6">
            </div>
        </div>
    <?php }
    if (isset($MONTH) && empty($DATE)) { ?>

        <div class='notice notice-default' role='alert'>
            <h1><strong>
                    <div style="text-align: center;"><?php if (isset($MONTH)) {
                            echo "$MONTH - $YEAR";
                        } ?></div>
                </strong></h1>
        </div>

        <?php

        switch ($MONTH) {
            case"JAN":
                $CONVERT_MONTH = '01';
                break;
            case"FEB":
                $CONVERT_MONTH = '02';
                break;
            case"MAR":
                $CONVERT_MONTH = '03';
                break;
            case"APR":
                $CONVERT_MONTH = '04';
                break;
            case"MAY":
                $CONVERT_MONTH = '05';
                break;
            case"JUN":
                $CONVERT_MONTH = '06';
                break;
            case"JUL":
                $CONVERT_MONTH = '07';
                break;
            case"AUG":
                $CONVERT_MONTH = '08';
                break;
            case"SEP":
                $CONVERT_MONTH = '09';
                break;
            case"OCT":
                $CONVERT_MONTH = '10';
                break;
            case"NOV":
                $CONVERT_MONTH = '11';
                break;
            case"DEC":
                $CONVERT_MONTH = '12';
                break;
            default:
                $CONVERT_MONTH = '1';
        }

        $list = array();

        for ($d = 1; $d <= 31; $d++) {
            $time = mktime(12, 0, 0, $CONVERT_MONTH, $d, $YEAR);
            if (date('m', $time) == $CONVERT_MONTH) {
                $list[] = date('D d-m-y', $time);
            }
        }
        ?>
        <form class="form" method="POST" action="RAG.php<?php echo "?MONTH=$MONTH&YEAR=$YEAR"; ?>">
            <div class="col-md-4">
                <select class="form-control" name="DATE" onchange="this.form.submit()" required>
                    <?php

                    foreach ($list as $item) { ?>
                        <option <?php if (isset($DATE)) {
                            if ($DATE == $item) {
                                echo "selected";
                            }
                        } ?> value="<?php echo $item; ?>"><?php echo $item; ?></option>
                    <?php }

                    ?>
                </select>
            </div>

        </form>

    <?php }
    if (isset($DATE)) { ?>

        <div class='notice notice-default' role='alert'>
            <h1><strong>
                    <div style="text-align: center;"><?php if (isset($MONTH)) {
                            echo "$MONTH - $DATE";
                        } ?></div>
                </strong></h1>
        </div>
        <br>
        <?php include('../php/Notifications.php'); ?>
        <br>
        <div class="row fixed-toolbar">
            <div class="col-xs-5">
                <a href="RAG.php" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back</a>
            </div>
            <div class="col-xs-7">
                <div class="text-right">
                    <a class="btn btn-primary" data-toggle="modal" data-target="#myModal" data-backdrop="static"
                       data-keyboard="false"><i class="fa fa-user-plus"></i> Add Agent</a>
                    <a class="btn btn-warning" data-toggle="modal" data-target="#myModal2" data-backdrop="static"
                       data-keyboard="false" href="#"><i class="fa fa-chart-line "></i> Week Stats</a>
                </div>
            </div>
        </div>

        <br>
        <?php

        switch ($MONTH) {
            case"JAN":
                $CONVERT_MONTH = '01';
                break;
            case"FEB":
                $CONVERT_MONTH = '02';
                break;
            case"MAR":
                $CONVERT_MONTH = '03';
                break;
            case"APR":
                $CONVERT_MONTH = '04';
                break;
            case"MAY":
                $CONVERT_MONTH = '05';
                break;
            case"JUN":
                $CONVERT_MONTH = '06';
                break;
            case"JUL":
                $CONVERT_MONTH = '07';
                break;
            case"AUG":
                $CONVERT_MONTH = '08';
                break;
            case"SEP":
                $CONVERT_MONTH = '09';
                break;
            case"OCT":
                $CONVERT_MONTH = '10';
                break;
            case"NOV":
                $CONVERT_MONTH = '11';
                break;
            case"DEC":
                $CONVERT_MONTH = '12';
                break;
            default:
                $CONVERT_MONTH = '01';
        }

        $list = array();


        for ($d = 1; $d <= 31; $d++) {
            $time = mktime(12, 0, 0, $CONVERT_MONTH, $d, $YEAR);
            if (date('m', $time) == $CONVERT_MONTH) {
                $list[] = date('D d-m-y', $time);
            }
        }
        ?>
        <form class="form" method="POST" action="RAG.php<?php echo "?MONTH=$MONTH&YEAR=$YEAR"; ?>">
            <div class="col-md-4">
                <select class="form-control" name="DATE" onchange="this.form.submit()" required>
                    <?php

                    foreach ($list as $item) { ?>
                        <option <?php if (isset($DATE)) {
                            if ($DATE == $item) {
                                echo "selected";
                            }
                        } ?> value="<?php echo $item; ?>"><?php echo $item; ?></option>
                    <?php }

                    ?>
                </select>
            </div>


        </form>

        <?php

        $RAG_QRY = $pdo->prepare("select campaign, lead_rag.cancels, employee_register.worked, employee_register.bank, employee_register.holiday, employee_register.sick, employee_register.awol, employee_register.authorised, employee_register.training, lead_rag.id, CONCAT(employee_details.firstname, ' ', employee_details.lastname) AS NAME, employee_details.employee_id, lead_rag.sales, lead_rag.leads, lead_rag.hours, lead_rag.minus, lead_rag.updated_by, lead_rag.updated_date from lead_rag JOIN employee_details on lead_rag.employee_id = employee_details.employee_id JOIN employee_register on employee_register.lead_rag_id = lead_rag.id WHERE lead_rag.date=:DATE ORDER BY NAME");
        $RAG_QRY->bindParam(':DATE', $DATE, PDO::PARAM_STR);
        $RAG_QRY->execute();
        if ($RAG_QRY->rowCount() > 0) { ?>

            <div class="row">
            <table class="table table-condensed">
                <tr>
                    <th>Employee</th>
                    <th>SALES</th>
                    <th>LEADS</th>
                    <th>CR</th>
                    <th>Cancels</th>
                    <th>Hours</th>
                    <th>Minus 25</th>
                    <th>Register</th>
                    <th>Camp</th>
                    <th></th>
                </tr>
                <?php

                while ($result = $RAG_QRY->fetch(PDO::FETCH_ASSOC)) {

                    $SALES = $result['sales'];
                    $LEADS = $result['leads'];
                    $HOURS = $result['hours'];
                    $MINUS = $result['minus'];
                    $UPDATED_BY = $result['updated_by'];
                    $UPDATED_DATE = $result['updated_date'];
                    $REF = $result['employee_id'];
                    $NAME = $result['NAME'];
                    $RAGID = $result['id'];
                    $CANCELS = $result['cancels'];
                    $campaign = $result['campaign'];

                    $WORKED = $result['worked'];
                    $HOLIDAY = $result['holiday'];
                    $TRAINING = $result['training'];
                    $AWOL = $result['awol'];
                    $SICK = $result['sick'];
                    $AUTHORISED = $result['authorised'];

                    ?>
                    <form method="POST"
                          action="/addon/Staff/php/RAG.php?EXECUTE=2<?php echo "&REF=$REF&MONTH=$MONTH&YEAR=$YEAR&DATE=$DATE&LEADRAG=$RAGID"; ?>">
                        <tr>
                            <td><input type="text" class="form-control" readonly value="<?php echo $NAME; ?>"
                                       name="EMPLOYEE"></td>
                            <td><input type="text" class="form-control" readonly value="<?php echo $SALES; ?>"
                                       name="SALES">
                            </td>
                            <td><input type="text" class="form-control" readonly value="<?php echo $LEADS; ?>"
                                       name="LEADS">
                            </td>
                            <td><input type="text" class="form-control" readonly
                                       value="<?php if (isset($SALES) && isset($LEADS)) {
                                           if ($LEADS > 0) {
                                               $var = $SALES / $LEADS;
                                               echo number_format((float)$var, 2, '.', '');
                                           }
                                       } ?>" name="CR"></td>
                            <td><input type="text" class="form-control" value="<?php echo $CANCELS; ?>" name="CANCELS">
                            </td>
                            <td><input type="text" class="form-control" value="<?php echo $HOURS; ?>" name="HOURS"></td>
                            <td><input type="text" class="form-control" value="<?php echo $MINUS; ?>" name="MINUS"></td>
                            <td><select name="REGISTER" class="form-control">
                                    <option <?php if (isset($WORKED)) {
                                        if ($WORKED > '0') {
                                            echo "selected";
                                        }
                                    } ?> value="1">Worked
                                    </option>
                                    <option <?php if (isset($HOLIDAY)) {
                                        if ($HOLIDAY > '0') {
                                            echo "selected";
                                        }
                                    } ?> value="2">Holidays
                                    </option>
                                    <option <?php if (isset($SICK)) {
                                        if ($SICK > '0') {
                                            echo "selected";
                                        }
                                    } ?> value="3">Sickness
                                    </option>
                                    <option <?php if (isset($AWOL)) {
                                        if ($AWOL > '0') {
                                            echo "selected";
                                        }
                                    } ?> value="4">AWOL
                                    </option>
                                    <option <?php if (isset($AUTHORISED)) {
                                        if ($AUTHORISED > '0') {
                                            echo "selected";
                                        }
                                    } ?> value="5">Authorised Leave
                                    </option>
                                    <option <?php if (isset($TRAINING)) {
                                        if ($TRAINING > '0') {
                                            echo "selected";
                                        }
                                    } ?> value="6">Days Training
                                    </option>
                                    <option <?php if (isset($BANK)) {
                                        if ($BANK > '0') {
                                            echo "selected";
                                        }
                                    } ?> value="7">Bank Holiday
                                    </option>
                                </select></td>
                            <td>
                                <select name="campaign" class="form-control">
                                    <option <?php if (isset($campaign)) {
                                        if ($campaign == 1300) {
                                            echo "selected";
                                        }
                                    } ?> value="1300">1300
                                    </option>
                                    <option <?php if (isset($campaign)) {
                                        if ($campaign == 1700) {
                                            echo "selected";
                                        }
                                    } ?> value="1700">1700
                                    </option>
                                    <option <?php if (isset($campaign)) {
                                        if ($campaign == 9996) {
                                            echo "selected";
                                        }
                                    } ?> value="9996">9996
                                    </option>
                                </select>
                            </td>
                            <td>
                                <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-check-circle"></i>
                                </button>
                            </td>
                        </tr>
                    </form>

                <?php } ?>

            </table>

        <?php } ?>

        </div>

    <?php } ?>

</div>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Agent to RAG</h4>
            </div>
            <div class="modal-body">
                <div class="panel">
                    <div class="panel-body">
                        <form
                            class="form"
                            action="../php/RAG.php?EXECUTE=1&DATE=<?php echo $DATE; ?>&MONTH=<?php echo $MONTH; ?>&YEAR=<?php echo $YEAR; ?>"
                            method="POST"
                            id="addform">

                            <div class="tab-content">
                                <div id="Modal1" class="tab-pane fade in active">

                                    <div class="col-lg-12 col-md-12">
                                        <div class="row">

                                            <?php

                                            $ADD_RAG_QRY = $pdo->prepare("select CONCAT(firstname, ' ', lastname) AS NAME, employee_id, position from employee_details WHERE position ='Life Lead Gen' AND employed ='1' AND employee_id NOT IN(select employee_id from lead_rag WHERE date =:DATE)");
                                            $ADD_RAG_QRY->bindParam(':DATE', $DATE, PDO::PARAM_STR);
                                            $ADD_RAG_QRY->execute();
                                            if ($ADD_RAG_QRY->rowCount() > 0) { ?>

                                                <div class="col-sm-8">
                                                    <div class="form-group">
                                                        <label class="control-label">Agent</label>
                                                        <select name="REF" class="form-control">

                                                            <?php

                                                            while ($result = $ADD_RAG_QRY->fetch(PDO::FETCH_ASSOC)) {

                                                                $EMPLOYEE_ID = $result['employee_id'];
                                                                $NAME = $result['NAME']; ?>

                                                                <option value="<?php if (isset($EMPLOYEE_ID)) {
                                                                    echo $EMPLOYEE_ID;
                                                                } ?>"><?php if (isset($NAME)) {
                                                                        echo $NAME;
                                                                    } ?></option>

                                                            <?php } ?>        </select>
                                                    </div>
                                                </div>

                                                <div class="col-sm-8">
                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-success"><i
                                                                class="fa fa-check-circle"></i> Add Agent!
                                                        </button>
                                                    </div>
                                                </div>

                                            <?php } ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal-footer">

                <script>
                    document.querySelector('#addform').addEventListener('submit', function (e) {
                        const form = this;
                        e.preventDefault();
                        swal({
                                title: "Add agent to RAG?",
                                text: "Confirm to add agent!",
                                type: "warning",
                                showCancelButton: true,
                                confirmButtonColor: '#DD6B55',
                                confirmButtonText: 'Yes, I am sure!',
                                cancelButtonText: "No, cancel it!",
                                closeOnConfirm: false,
                                closeOnCancel: false
                            },
                            function (isConfirm) {
                                if (isConfirm) {
                                    swal({
                                        title: 'Agent Added!',
                                        text: 'Agent has been added to the RAG!',
                                        type: 'success'
                                    }, function () {
                                        form.submit();
                                    });

                                } else {
                                    swal("Cancelled", "No changes were made", "error");
                                }
                            });
                    });

                </script>

                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i>
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal2" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">RAG Week Stats</h4>
            </div>
            <div class="modal-body">

                <form class="form" action="RAG.php?RETURN=WEEKSTATS" method="POST" id="searchform">

                    <div class="col-lg-12 col-md-12">
                        <div class="row">

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label">Date From</label>
                                    <input type="text" name="dateFrom" id="dateFrom"
                                           class="form-control" value="<?php if (isset($dateFrom)) {
                                        echo $dateFrom;
                                    } ?>">
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label">Date To</label>
                                    <input type="text" name="dateTo" id="dateTo"
                                           class="form-control" value="<?php if (isset($dateTo)) {
                                        echo $dateTo;
                                    } ?>">
                                </div>
                            </div>

                        </div>
                    </div>
                    <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> Search!
                    </button>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i>
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    document.querySelector('#searchform').addEventListener('submit', function (e) {
        const form = this;
        e.preventDefault();
        swal({
                title: "Search RAG for these dates?",
                text: "Yes search!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, I am sure!',
                cancelButtonText: "No, cancel it!",
                closeOnConfirm: false,
                closeOnCancel: false
            },
            function (isConfirm) {
                if (isConfirm) {
                    swal({
                        title: 'Data range searched!',
                        text: 'RAG populated!',
                        type: 'success'
                    }, function () {
                        form.submit();
                    });

                } else {
                    swal("Cancelled", "No changes were made", "error");
                }
            });
    });

</script>

<div class="modal fade" id="myModal3" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">RAG Average Stats</h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <ul class="nav nav-pills nav-justified">
                        <li class="active"><a data-toggle="pill" href="#Modal1">Average Stats</a></li>
                    </ul>
                </div>

                <div class="panel">
                    <div class="panel-body">
                        <div class="tab-content">
                            <div id="Modal1" class="tab-pane fade in active">

                                <div class="col-lg-12 col-md-12">
                                    <div class="row">
                                        <table class="table">
                                            <tr>
                                                <td><input type="text" class="form-control" readonly
                                                           value="<?php if (isset($NAME)) {
                                                               echo $NAME;
                                                           } ?>" name="EMPLOYEE"></td>
                                                <td><input type="text" class="form-control" readonly
                                                           value="<?php if (isset($SALES)) {
                                                               echo $SALES;
                                                           } ?>" name="SALES"></td>
                                                <td><input type="text" class="form-control" readonly
                                                           value="<?php if (isset($LEADS)) {
                                                               echo $LEADS;
                                                           } ?>" name="LEADS"></td>
                                                <td><input type="text" class="form-control" readonly
                                                           value="<?php if (isset($LEADS) && isset($SALES)) {
                                                               if ($LEADS > 0) {
                                                                   $var = $SALES / $LEADS;
                                                                   echo number_format((float)$var, 2, '.', '');
                                                               }
                                                           } ?>" name="CR"></td>
                                                <td><input type="text" class="form-control"
                                                           value="<?php if (isset($HOURS)) {
                                                               echo $HOURS;
                                                           } ?>" name="HOURS"></td>
                                                <td><input type="text" class="form-control"
                                                           value="<?php if (isset($MINUS)) {
                                                               echo $MINUS;
                                                           } ?>" name="MINUS"></td>
                                                <td>
                                                    <button type="submit" class="btn btn-success btn-md"><i
                                                            class="fa fa-check-circle"></i> Update
                                                    </button>
                                                </td>
                                            </tr>

                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i>
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="/resources/lib/jquery/jquery-3.0.0.min.js"></script>
<script type="text/javascript" src="/resources/lib/jquery-ui-1.11.4/external/jquery/jquery.js"></script>
<script src="/resources/lib/jquery-ui-1.11.4/jquery-ui.min.js"></script>
<script src="/resources/templates/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
<script src="/resources/lib/sweet-alert/sweet-alert.min.js"></script>
<script>
    $(function () {
        $("#dateFrom").datepicker({
            dateFormat: 'dd-mm-y',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:-0"
        });
    });
    $(function () {
        $("#dateTo").datepicker({
            dateFormat: 'dd-mm-y',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:-0"
        });
    });
</script>
</body>
</html>
