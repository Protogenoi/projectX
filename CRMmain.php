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

</body>
</html>
