<?php
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
 * Written by michael <michael@adl-crm.uk>, 28/06/19 16:50
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

    $page_protect->log_out();
    die;

}

$EXECUTE = filter_input(INPUT_GET, 'EXECUTE', FILTER_SANITIZE_NUMBER_INT);

if (isset($EXECUTE) && $EXECUTE == 1) {

    $agentName = filter_input(INPUT_POST, 'agentName', FILTER_SANITIZE_SPECIAL_CHARS);
    $dateFrom = filter_input(INPUT_POST, 'dateFrom', FILTER_SANITIZE_SPECIAL_CHARS);
    $dateTo = filter_input(INPUT_POST, 'dateTo', FILTER_SANITIZE_SPECIAL_CHARS);

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


    endforeach;

    $chart .= "{ y: 'Ross', a: $rossSale, b: $rossXfer},";
    $chart .= "{ y: 'Jade', a: $jadeSale, b: $jadeXfer},";
    $chart .= "{ y: 'Dan', a: $danSale, b: $danXfer},";
    $chart .= "{ y: 'Rich', a: $richSale, b: $richXfer},";
    $chart .= "{ y: 'James', a: $jamesSale, b: $jamesXfer},";
    $chart .= "{ y: 'Kyle', a: $kyleSale, b: $kyleXfer},";
    $chart .= "{ y: 'Carys', a: $carysSale, b: $carysXfer},";
    $chart .= "{ y: 'Mike', a: $mikeSale, b: $mikeXfer},";

}

$ADL_PAGE_TITLE = "Lead Conversions";
require_once(BASE_URL . '/app/core/head.php'); ?>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
</head>
<body>

<?php require_once(BASE_URL . '/includes/navbar.php'); ?>

<div class="container">
    <div class="col-sm-12 text-center">
        <label class="label label-success"><?php echo $agentName; ?></label>
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
                pointFillColors: ['#ffffff'],
                pointStrokeColors: ['black'],
            };

        config.element = 'bar-chart';
        Morris.Bar(config);
    </script>
</body>
</html>
