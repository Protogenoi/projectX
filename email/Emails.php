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

require_once(__DIR__ . '/../classes/access_user/access_user_class.php');
$page_protect = new Access_user;
$page_protect->access_page(filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS), "", 3);
$hello_name = ($page_protect->user_full_name != "") ? $page_protect->user_full_name : $page_protect->user;

$USER_TRACKING = 0;

require_once(__DIR__ . '/../includes/user_tracking.php');

require_once(__DIR__ . '/../includes/time.php');

if (isset($FORCE_LOGOUT) && $FORCE_LOGOUT == 1) {
    $page_protect->log_out();
}

require_once(__DIR__ . '/../includes/adl_features.php');
require_once(__DIR__ . '/../includes/Access_Levels.php');


if ($ffanalytics == '1') {
    require_once(__DIR__ . '/../app/analyticstracking.php');
}

if (isset($fferror)) {
    if ($fferror == '1') {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }
}

require_once(__DIR__ . '/../classes/database_class.php');
require_once(__DIR__ . '/../class/login/login.php');
$CHECK_USER_LOGIN = new UserActions($hello_name, "NoToken");
$CHECK_USER_LOGIN->CheckAccessLevel();

$USER_ACCESS_LEVEL = $CHECK_USER_LOGIN->CheckAccessLevel();

$ACCESS_LEVEL = $USER_ACCESS_LEVEL['ACCESS_LEVEL'];

if ($ACCESS_LEVEL < 3) {

    header('Location: /../../index.php?AccessDenied&USER=' . $hello_name . '&COMPANY=' . $COMPANY_ENTITY);
    die;

}
?>
<!DOCTYPE html>
<html lang="en">
<title>ADL | Emails</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="/resources/templates/ADL/main.css" type="text/css"/>
<link rel="stylesheet" href="/resources/templates/bootstrap-3.3.5-dist/css/bootstrap.min.css">
<link rel="stylesheet" href="/resources/templates/bootstrap-3.3.5-dist/css/bootstrap-theme.min.css">
<link href="/img/favicon.ico" rel="icon" type="image/x-icon"/>
<script type="text/javascript" language="javascript"
        src="/resources/templates/fontawesome/svg-with-js/js/fontawesome-all.js"></script>
</head>
<body>

<?php require_once(__DIR__ . '/../includes/navbar.php'); ?>

<div class="container">

    <div class="row">
        <div class="twelve columns">
            <ul class="ca-menu">

                <?php if ($ffkeyfactsemail == '1') { ?>
                    <li>
                        <a href="KeyFactsEmail.php">
                            <span class="ca-icon"><i class="fa fa-envelope"></i></span>
                            <div class="ca-content">
                                <h2 class="ca-main">Closers<br/></h2>
                                <h3 class="ca-sub">Key Facts Email</h3>
                            </div>
                        </a>
                    </li>
                    <?php
                }
                if ($ffgenemail == '1') {
                    ?>
                    <li>
                        <a href="GenericEmail.php">
                            <span class="ca-icon"><i class="fa fa-envelope-o"></i></span>
                            <div class="ca-content">
                                <h2 class="ca-main">Generic<br/></h2>
                                <h3 class="ca-sub">Emails</h3>
                            </div>
                        </a>
                    </li>
                    <?php
                }
                if ($ffintemails == '1') {
                    ?>
                    <li>
                        <a href="InternalEmail.php">
                            <span class="ca-icon"><i class="fa fa-envelope"></i></span>
                            <div class="ca-content">
                                <h2 class="ca-main">Internal<br/>Emails</h2>
                                <h3 class="ca-sub"></h3>
                            </div>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>

<script type="text/javascript" language="javascript" src="/resources/lib/jquery/jquery-3.0.0.min.js"></script>
<script src="/resources/templates/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
</body>
</html>
