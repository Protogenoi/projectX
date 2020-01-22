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
$page_protect->access_page(filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS), "", 1);
$hello_name = ($page_protect->user_full_name != "") ? $page_protect->user_full_name : $page_protect->user;

require_once(BASE_URL . '/includes/adl_features.php');
require_once(BASE_URL . '/includes/Access_Levels.php');
require_once(BASE_URL . '/includes/adlfunctions.php');
require_once(BASE_URL . '/includes/ADL_PDO_CON.php');

if (isset($ffanalytics) && $ffanalytics == '1') {
    require_once(BASE_URL . '/app/analyticstracking.php');
}

if (isset($fferror) && $fferror == 1) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
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

    $page_protect->log_out();
    die;

}


if (isset($fftrackers) && $fftrackers == 0) {
    $page_protect->log_out();
    die;
}

if (!in_array($hello_name, $Manager_Access, true)) {
    $page_protect->log_out();
}

$EXECUTE = filter_input(INPUT_GET, 'EXECUTE', FILTER_SANITIZE_SPECIAL_CHARS);
$datefrom = filter_input(INPUT_POST, 'DATES', FILTER_SANITIZE_SPECIAL_CHARS);
$dateTo = filter_input(INPUT_POST, 'dateTo', FILTER_SANITIZE_SPECIAL_CHARS);
$CLOSER = filter_input(INPUT_POST, 'CLOSER', FILTER_SANITIZE_SPECIAL_CHARS);
$TID = filter_input(INPUT_GET, 'TID', FILTER_SANITIZE_NUMBER_INT);

$Today_DATE = date("d-M-Y");
$Today_DATES = date("l jS \of F Y");
$Today_TIME = date("h:i:s");

if (isset($EXECUTE) && $EXECUTE == 1 && empty($datefrom)) {

    $chart = '';

    $abbieRXfer = 0;
    $abbieRSale = 0;

    $ashWXfer = 0;
    $ashWSale = 0;

    $brandPXfer = 0;
    $brandPSale = 0;

    $conWXfer = 0;
    $conWSale = 0;

    $georgeMXfer = 0;
    $georgeMSale = 0;

    $katMXfer = 0;
    $katMSale = 0;

    $laurenAXfer = 0;
    $laurenASale = 0;

    $leeDXfer = 0;
    $leeDSale = 0;

    $leeSXfer = 0;
    $leeSSale = 0;

    $mikeHXfer = 0;
    $mikeHSale = 0;

    $natRXfer = 0;
    $natRSale = 0;

    $rachelJXfer = 0;
    $rachelJSale = 0;

    $rickyDXfer = 0;
    $rickyDSale = 0;

    $stavXfer = 0;
    $stavSale = 0;

    $stepLXfer = 0;
    $stepLSale = 0;

    $stepHXfer = 0;
    $stepHSale = 0;

    $loisTXfer = 0;
    $loisTSale = 0;

    $sophieJXfer = 0;
    $sophieJSale = 0;

    $ffionEXfer = 0;
    $ffionESale = 0;

    $sophieHXfer = 0;
    $sophieHSale = 0;

    $adamAXfer = 0;
    $adamASale = 0;

    $query = $pdo->prepare("SELECT 
    closer,
    agent,
    count(tracker_id) AS sale
FROM
    closer_trackers
WHERE
    DATE(date_added) >= CURDATE()
        AND sale = 'SALE'
        GROUP BY agent
ORDER BY agent DESC");
    $query->execute();
    $row = $query->fetchall(PDO::FETCH_ASSOC);
    $totalSales = 0;
    foreach ($row as $item):

        if (isset($item['agent'])) {
            if ($item['agent'] == 'Abbie Rowden') {

                $abbieRSale += $item['sale'];

            }
            if ($item['agent'] == 'Ashleigh Woodgate') {

                $ashWSale += $item['sale'];

            }
            if ($item['agent'] == 'Sophie Jones') {

                $sophieJSale += $item['sale'];

            }
            if ($item['agent'] == 'Brandon Preece') {

                $brandPSale += $item['sale'];

            }
            if ($item['agent'] == 'Connor Williams') {

                $conWSale += $item['sale'];

            }
            if ($item['agent'] == 'George Matthews') {

                $georgeMSale += $item['sale'];

            }
            if ($item['agent'] == 'Katrina Mort') {

                $katMSale += $item['sale'];

            }
            if ($item['agent'] == 'Lauren Ace') {

                $laurenASale += $item['sale'];

            }
            if ($item['agent'] == 'Lee McDonaugh') {

                $leeDSale += $item['sale'];

            }
            if ($item['agent'] == 'Lee Stafford') {

                $leeSSale += $item['sale'];

            }
            if ($item['agent'] == 'Michael Hodge') {

                $mikeHSale += $item['sale'];

            }
            if ($item['agent'] == 'Nathan Roberts') {

                $natRSale += $item['sale'];

            }
            if ($item['agent'] == 'Rachel Jones') {

                $rachelJSale += $item['sale'];

            }
            if ($item['agent'] == 'Ricky Derrick') {

                $rickyDSale += $item['sale'];

            }
            if ($item['agent'] == 'Stavros') {

                $stavSale += $item['sale'];

            }
            if ($item['agent'] == 'Stephan Leyson') {

                $stepLSale += $item['sale'];

            }
            if ($item['agent'] == 'Stephan Howard') {

                $stepHSale += $item['sale'];

            }
            if ($item['agent'] == 'Lois Taylor') {

                $loisTSale += $item['sale'];

            }

            if ($item['agent'] == 'Ffion Edwards') {

                $ffionESale += $item['sale'];

            }

            if ($item['agent'] == 'Sophie Harvard') {

                $sophieHSale += $item['sale'];

            }

            if ($item['agent'] == 'Adam Arrigan') {

                $adamASale += $item['sale'];

            }
        }

        $totalSales += $item['sale'];
    endforeach;

    $query = $pdo->prepare("SELECT 
    closer,
    agent,
    count(tracker_id) AS noSale
FROM
    closer_trackers
WHERE
    DATE(date_added) >= CURDATE()
        GROUP BY agent
ORDER BY agent DESC");
    $query->execute();
    $row = $query->fetchall(PDO::FETCH_ASSOC);
    $totalXfers = 0;
    foreach ($row as $item):

        if (isset($item['agent'])) {
            if ($item['agent'] == 'Abbie Rowden') {

                $abbieRXfer += $item['noSale'];

            }
            if ($item['agent'] == 'Ashleigh Woodgate') {

                $ashWXfer += $item['noSale'];

            }
            if ($item['agent'] == 'Sophie Jones') {

                $sophieJXfer += $item['noSale'];

            }
            if ($item['agent'] == 'Brandon Preece') {
                $brandPXfer += $item['noSale'];

            }
            if ($item['agent'] == 'Connor Williams') {
                $conWXfer += $item['noSale'];

            }
            if ($item['agent'] == 'George Matthews') {
                $georgeMXfer += $item['noSale'];

            }
            if ($item['agent'] == 'Katrina Mort') {
                $katMXfer += $item['noSale'];

            }
            if ($item['agent'] == 'Lauren Ace') {
                $laurenAXfer += $item['noSale'];

            }
            if ($item['agent'] == 'Lee McDonaugh') {
                $leeDXfer += $item['noSale'];

            }
            if ($item['agent'] == 'Lee Stafford') {
                $leeSXfer += $item['noSale'];

            }
            if ($item['agent'] == 'Michael Hodge') {
                $mikeHXfer += $item['noSale'];

            }
            if ($item['agent'] == 'Nathan Roberts') {
                $natRXfer += $item['noSale'];

            }
            if ($item['agent'] == 'Rachel Jones') {
                $rachelJXfer += $item['noSale'];

            }
            if ($item['agent'] == 'Ricky Derrick') {
                $rickyDXfer += $item['noSale'];

            }
            if ($item['agent'] == 'Stavros') {
                $stavXfer += $item['noSale'];

            }
            if ($item['agent'] == 'Stephan Leyson') {
                $stepLXfer += $item['noSale'];

            }
            if ($item['agent'] == 'Stephan Howard') {
                $stepHXfer += $item['noSale'];

            }
            if ($item['agent'] == 'Lois Taylor') {
                $loisTXfer += $item['noSale'];

            }

            if ($item['agent'] == 'Ffion Edwards') {
                $ffionEXfer += $item['noSale'];

            }

            if ($item['agent'] == 'Sophie Harvard') {
                $sophieHXfer += $item['noSale'];

            }

            if ($item['agent'] == 'Adam Arrigan') {
                $adamAXfer += $item['noSale'];

            }
        }

        $totalXfers += $item['noSale'];


    endforeach;

    if ($abbieRXfer > 0) {
        $chart .= "{ y: 'Abbie', a: $abbieRSale, b: $abbieRXfer},";
    }
    if ($ashWXfer > 0) {
        $chart .= "{ y: 'Ash', a: $ashWSale, b: $ashWXfer},";
    }
    if ($sophieJXfer > 0) {
        $chart .= "{ y: 'Sophie', a: $sophieJSale, b: $sophieJXfer},";
    }
    if ($brandPXfer > 0) {
        $chart .= "{ y: 'Brandon', a: $brandPSale, b: $brandPXfer},";
    }
    if ($conWXfer > 0) {
        $chart .= "{ y: 'Connor', a: $conWSale, b: $conWXfer},";
    }
    if ($georgeMXfer > 0) {
        $chart .= "{ y: 'George', a: $georgeMSale, b: $georgeMXfer},";
    }
    if ($katMXfer > 0) {
        $chart .= "{ y: 'Katrina', a: $katMSale, b: $katMXfer},";
    }
    if ($laurenAXfer > 0) {
        $chart .= "{ y: 'Lauren', a: $laurenASale, b: $laurenAXfer},";
    }
    if ($leeDXfer > 0) {
        $chart .= "{ y: 'Lee D', a: $leeDSale, b: $leeDXfer},";
    }
    if ($leeSXfer > 0) {
        $chart .= "{ y: 'Lee S', a: $leeSSale, b: $leeSXfer},";
    }
    if ($mikeHXfer > 0) {
        $chart .= "{ y: 'Mike', a: $mikeHSale, b: $mikeHXfer},";
    }
    if ($natRXfer > 0) {
        $chart .= "{ y: 'Nathan', a: $natRSale, b: $natRXfer},";
    }
    if ($rachelJXfer > 0) {
        $chart .= "{ y: 'Rachel', a: $rachelJSale, b: $rachelJXfer},";
    }
    if ($rickyDXfer > 0) {
        $chart .= "{ y: 'Ricky', a: $rickyDSale, b: $rickyDXfer},";
    }
    if ($stavXfer > 0) {
        $chart .= "{ y: 'Stavros', a: $stavSale, b: $stavXfer},";
    }
    if ($stepLXfer > 0) {
        $chart .= "{ y: 'Step L', a: $stepLSale, b: $stepLXfer},";
    }
    if ($stepHXfer > 0) {
        $chart .= "{ y: 'Step H', a: $stepHSale, b: $stepHXfer},";
    }
    if ($loisTXfer > 0) {
        $chart .= "{ y: 'Step H', a: $loisTSale, b: $loisTXfer},";
    }
    if ($ffionEXfer > 0) {
        $chart .= "{ y: 'Ffion', a: $ffionESale, b: $ffionEXfer},";
    }
    if ($sophieHXfer > 0) {
        $chart .= "{ y: 'Sophie H', a: $sophieHSale, b: $sophieHXfer},";
    }
    if ($adamAXfer > 0) {
        $chart .= "{ y: 'Adam', a: $adamASale, b: $adamAXfer},";
    }

} else {

    $chart = '';

    $rossXfer = 0;
    $rossSale = 0;

    $danXfer = 0;
    $danSale = 0;

    $richXfer = 0;
    $richSale = 0;

    $jadeXfer = 0;
    $jadeSale = 0;

    $jamesXfer = 0;
    $jamesSale = 0;

    $kyleXfer = 0;
    $kyleSale = 0;

    $davidXfer = 0;
    $davidSale = 0;

    $carysXfer = 0;
    $carysSale = 0;

    $mikeXfer = 0;
    $mikeSale = 0;

    $dateFrom = $datefrom;

    $query = $pdo->prepare("SELECT 
    closer,
    agent,
    count(tracker_id) AS sale
FROM
    closer_trackers
WHERE
    DATE(date_added) BETWEEN :dateFrom AND  :dateTo
        AND agent =:agent
        AND sale = 'SALE'
        GROUP BY closer
ORDER BY closer DESC");
    $query->bindParam(':agent', $CLOSER, PDO::PARAM_STR);
    $query->bindParam(':dateFrom', $dateFrom, PDO::PARAM_STR);
    $query->bindParam(':dateTo', $dateTo, PDO::PARAM_STR);
    $query->execute();
    $row = $query->fetchall(PDO::FETCH_ASSOC);
    $totalSales = 0;
    foreach ($row as $item):

        if (isset($item['closer'])) {
            if ($item['closer'] == 'Ross') {

                $rossSale += $item['sale'];

            }
            if ($item['closer'] == 'Dan') {

                $danSale += $item['sale'];

            }
            if ($item['closer'] == 'Richard') {

                $richSale += $item['sale'];

            }
            if ($item['closer'] == 'Jade') {

                $jadeSale += $item['sale'];

            }
            if ($item['closer'] == 'James') {

                $jamesSale += $item['sale'];

            }
            if ($item['closer'] == 'Kyle') {

                $kyleSale += $item['sale'];

            }
            if ($item['closer'] == 'David') {

                $davidSale += $item['sale'];

            }
            if ($item['closer'] == 'Carys') {

                $carysSale += $item['sale'];

            }
            if ($item['closer'] == 'Mike') {

                $mikeSale += $item['sale'];

            }
        }

        $totalSales += $item['sale'];

    endforeach;

    $query = $pdo->prepare("SELECT 
    closer,
    agent,
    count(tracker_id) AS noSale
FROM
    closer_trackers
WHERE
    DATE(date_added) BETWEEN :dateFrom AND  :dateTo
        AND agent =:agent
        GROUP BY closer
ORDER BY closer DESC");
    $query->bindParam(':agent', $CLOSER, PDO::PARAM_STR);
    $query->bindParam(':dateFrom', $dateFrom, PDO::PARAM_STR);
    $query->bindParam(':dateTo', $dateTo, PDO::PARAM_STR);
    $query->execute();
    $row = $query->fetchall(PDO::FETCH_ASSOC);
    $totalXfers = 0;
    foreach ($row as $item):

        if (isset($item['closer'])) {
            if ($item['closer'] == 'Ross') {

                $rossXfer += $item['noSale'];

            }
            if ($item['closer'] == 'Jade') {

                $jadeXfer += $item['noSale'];

            }
            if ($item['closer'] == 'Dan') {

                $danXfer += $item['noSale'];

            }
            if ($item['closer'] == 'Richard') {

                $richXfer += $item['noSale'];

            }
            if ($item['closer'] == 'James') {

                $jamesXfer += $item['noSale'];

            }
            if ($item['closer'] == 'Kyle') {

                $kyleXfer += $item['noSale'];

            }
            if ($item['closer'] == 'David') {

                $davidXfer += $item['noSale'];

            }
            if ($item['closer'] == 'Carys') {

                $carysXfer += $item['noSale'];

            }
            if ($item['closer'] == 'Mike') {

                $mikeXfer += $item['noSale'];

            }
        }

        $totalXfers += $item['noSale'];


    endforeach;

    if ($rossXfer > 0) {
        $chart .= "{ y: 'Ross', a: $rossSale, b: $rossXfer},";
    }
    if ($jadeXfer > 0) {
        $chart .= "{ y: 'Jade', a: $jadeSale, b: $jadeXfer},";
    }
    if ($danXfer > 0) {
        $chart .= "{ y: 'Dan', a: $danSale, b: $danXfer},";
    }
    if ($richXfer > 0) {
        $chart .= "{ y: 'Rich', a: $richSale, b: $richXfer},";
    }
    if ($jamesXfer > 0) {
        $chart .= "{ y: 'James', a: $jamesSale, b: $jamesXfer},";
    }
    if ($kyleXfer > 0) {
        $chart .= "{ y: 'Kyle', a: $kyleSale, b: $kyleXfer},";
    }
    if ($carysXfer > 0) {
        $chart .= "{ y: 'Carys', a: $carysSale, b: $carysXfer},";
    }
    if ($mikeXfer > 0) {
        $chart .= "{ y: 'Mike', a: $mikeSale, b: $mikeXfer},";
    }
}

$leadCR = 0;
if (isset($totalSales) && $totalSales > 0) {
    $leadCR = $totalXfers / $totalSales;
    $leadCR = number_format($leadCR, 1);
}

$ADL_PAGE_TITLE = "Agent Trackers";
require_once(BASE_URL . '/app/core/head.php');

?>
<link rel="stylesheet" type="text/css" href="/resources/templates/ADL/Notices.css">
<link rel="stylesheet" type="text/css" href="/resources/lib/sweet-alert/sweet-alert.min.css"/>
<link rel="stylesheet" type="text/css" href="/resources/lib/jquery-ui-1.11.4/jquery-ui.min.css"/>
<link rel="stylesheet" href="/resources/lib/EasyAutocomplete-1.3.3/easy-autocomplete.min.css">
<link rel="stylesheet" href="/resources/lib/morrisCharts/morris.css">
<script src="/resources/lib/morrisCharts/jquery.min.js"></script>
<script src="/resources/lib/morrisCharts/raphael-min.js"></script>
<script src="/resources/lib/morrisCharts/morris.min.js"></script>
<body>

<?php require_once(BASE_URL . '/includes/navbar.php'); ?>

<?php
if (isset($EXECUTE)) {
    if ($EXECUTE == '1') { ?>

        <div class="container-fluid">

            <div class="col-md-12">

                <div class="col-md-4">
                </div>
                <div class="col-md-4"></div>

                <div class="col-md-4">

                    <?php echo "<h3>$Today_DATES</h3>"; ?>
                    <?php echo "<h4>$Today_TIME</h4>"; ?>

                </div>

            </div>
            <div class="col-sm-12 text-center">
                <h2>
                    <label class="label label-primary"><?php if (isset($EXECUTE) && $EXECUTE == 1 && isset($datefrom)) {
                            echo $CLOSER . " $totalXfers/$totalSales ($leadCR)";
                        } else {
                            echo "Today's trackers $totalXfers/$totalSales ($leadCR)";
                        } ?></label>
                </h2>
                <div id="bar-chart"></div>
            </div>

            <script>
                var data = [<?php echo $chart; ?>],
                    config = {
                        data: data,
                        xkey: 'y',
                        ykeys: ['a', 'b'],
                        labels: ['Sales', 'Transfers'],
                        fillOpacity: 0.6,
                        hideHover: 'auto',
                        behaveLikeLine: true,
                        resize: true,
                    };

                config.element = 'bar-chart';
                Morris.Bar(config);
            </script>

            <div class="list-group">
                <span class="label label-primary">Agent Trackers</span>
                <br><br>
                <form action="?EXECUTE=1" method="post">

                    <div class="col-md-12">
                        <div class="col-md-4">
                            <select class="form-control" name="CLOSER" id="CLOSER">
                                <option value="">Select...</option>


                            </select>
                        </div>

                        <div class="col-sm-4">
                            <input type="text" id="DATES" name="DATES" value="<?php if (isset($datefrom)) {
                                echo "$datefrom";
                            } else {
                                echo date("Y-m-d");
                            } ?>" class="form-control">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="dateTo" name="dateTo" value="<?php if (isset($dateTo)) {
                                echo "$dateTo";
                            } else {
                                echo date("Y-m-d");
                            } ?>" class="form-control">
                        </div>

                        <div class="col-md-8">
                            <div class="btn-group">
                                <button type="submit" class="btn btn-success btn-sm"><i
                                        class="fa fa-calendar-alt"></i> Set Dates
                                </button>
                                <?php if (isset($dateFrom)) { ?>
                                    <a
                                        class="btn btn-default btn-sm"
                                        href="Export/Closers.php?EXECUTE=1&DATE=<?php if (isset($dateFrom)) {
                                            echo "$dateFrom";
                                        } else {
                                            echo date("Y-m-d");
                                        } ?>&DATE_TO=<?php if (isset($DATE_TO)) {
                                            echo "$DATE_TO";
                                        } else {
                                            echo date("Y-m-d");
                                        } ?>"
                                    ><i class="fa fa-file-excel"></i> Export</a>
                                    <?php if ($hello_name == 'Michael') { ?>
                                        <a
                                            class="btn btn-default btn-sm"
                                            href="Export/agentStats.php?EXECUTE=1&dateFrom=<?php if (isset($dateFrom)) {
                                                echo "$dateFrom&dateTo=$dateTo";
                                            } ?> &agent=<?php if (isset($CLOSER)) {
                                                echo "$CLOSER";
                                            } ?>"
                                        ><i class="fa fa-file-excel"></i> <?php echo $CLOSER; ?> Stats</a>
                                        <a
                                            class="btn btn-default btn-sm"
                                            href="Export/agentStats.php?EXECUTE=2&dateFrom=<?php if (isset($dateFrom)) {
                                                echo "$dateFrom&dateTo=$dateTo";
                                            } ?>"
                                        ><i class="fa fa-file-excel"></i> All Agent Stats</a>
                                    <?php } ?>
                                    <a class="btn btn-danger btn-sm" href="?EXECUTE=1"><i class="fa fa-recycle"></i>
                                        RESET</a>
                                <?php } ?>
                                <a class="btn btn-info btn-sm" href="Closers.php?EXECUTE=1"><i
                                        class="fa fa-check-circle"></i> Closer Trackers</a>
                            </div>
                        </div>


                    </div>

                </form>
            </div>
            <div class="STATREFRESH"></div>

            <script>
                function refresh_div() {
                    jQuery.ajax({
                        url: 'AJAX/Stats.php?EXECUTE=1',
                        type: 'POST',
                        success: function (results) {
                            jQuery(".STATREFRESH").html(results);
                        }
                    });
                }

                t = setInterval(refresh_div, 1000);
            </script>
        </div>
        <div class="container-fluid">

            <?php

            if (isset($TID) && is_numeric($TID)) {

                require_once(__DIR__ . '/models/trackers/CLOSER/editCloserTracker-model.php');
                $editTracker = new editTrackerModel($pdo);
                $editTrackerList = $editTracker->getEditTrackerModel($TID);
                require_once(__DIR__ . '/views/trackers/AGENT/editAgentTracker-view.php');

            }

            if (isset($datefrom)) {
                $AGT_CHK = $pdo->prepare("SELECT tracker_id from closer_trackers WHERE DATE(date_updated) BETWEEN :date AND :dateTo AND agent=:agent");
                $AGT_CHK->bindParam(':date', $datefrom, PDO::PARAM_STR);
                $AGT_CHK->bindParam(':dateTo', $dateTo, PDO::PARAM_STR);
                $AGT_CHK->bindParam(':agent', $CLOSER, PDO::PARAM_STR);
                $AGT_CHK->execute();
                if ($AGT_CHK->rowCount() > 0) {

                    require_once(__DIR__ . '/models/trackers/AGENT/AgentPAD.php');
                    $AgentPad = new AgentPadModal($pdo);
                    $AgentPadList = $AgentPad->getAgentPad($datefrom, $dateTo, $CLOSER);
                    require_once(__DIR__ . '/views/trackers/AGENT/Agent-PAD.php');
                }
            }
            if (!isset($datefrom)) {
                $AGT_CHK = $pdo->prepare("SELECT tracker_id from closer_trackers WHERE date_updated >=CURDATE()");
                $AGT_CHK->execute();
                if ($AGT_CHK->rowCount() > 0) {

                    require_once(__DIR__ . '/models/trackers/AGENT/AgentALLPAD.php');
                    $AgentPad = new AgentALLPadModal($pdo);
                    $AgentPadList = $AgentPad->getAgentALLPad();
                    require_once(__DIR__ . '/views/trackers/AGENT/Agent-PAD.php');
                }
            }
            ?>

        </div>


    <?php }
}
?>

<script type="text/javascript" src="/resources/lib/jquery/jquery-3.0.0.min.js"></script>
<script type="text/javascript" src="/resources/lib/jquery-ui-1.11.4/jquery-ui.min.js"></script>
<script src="/resources/templates/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
<script src="/resources/lib/EasyAutocomplete-1.3.3/jquery.easy-autocomplete.min.js"></script>
<script>
    $(function () {
        $("#DATES").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:-0"
        });
    });
    $(function () {
        $("#dateTo").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:-0"
        });
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

    $("#provider-json").easyAutocomplete(options);</script>
<script type="text/JavaScript">
    var $select = $('#CLOSER');
    $.getJSON('/app/JSON/Agents.php?EXECUTE=1&USER=<?php echo $hello_name; ?>&TOKEN=<?php echo $TOKEN; ?>', function (data) {
        $select.html('CLOSER');
        $.each(data, function (key, val) {
            $select.append('<option value="' + val.full_name + '">' + val.full_name + '</option>');
        })
    });
</script>
</body>
</html>
