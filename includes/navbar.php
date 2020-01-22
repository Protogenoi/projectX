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
 * Written by Michael Owen <michael@adl-crm.uk>, 2019
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

require_once(__DIR__ . '../../includes/ADL_PDO_CON.php');
require_once(__DIR__ . '../../includes/adl_features.php');

if (isset($fferror)) {
    if ($fferror == '1') {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }
}

$LOGOUT_ACTION = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_SPECIAL_CHARS);
$FEATURE = filter_input(INPUT_GET, 'FEATURE', FILTER_SANITIZE_SPECIAL_CHARS);

if (isset($LOGOUT_ACTION) && $LOGOUT_ACTION == "log_out") {
    $page_protect->log_out();
}

if (isset($hello_name)) {

    $cnquery = $pdo->prepare("select company_name from company_details limit 1");
    $cnquery->execute() or die(print_r($query->errorInfo(), true));
    $companydetailsq = $cnquery->fetch(PDO::FETCH_ASSOC);

    $companynamere = $companydetailsq['company_name'];

    ?>
    <style>

        .dropdown:hover .dropdown-menu {
            display: block;
        }
    </style>

    <nav role="navigation" class="navbar navbar-inverse">
        <div class="navbar-header">
            <button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="//x.adl-crm.uk" class="navbar-brand"> ADL</a>
        </div>

        <div id="navbarCollapse" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="/CRMmain.php"><i class="fa fa-home"></i> Home</a></li>
                <li><a href="/addon/support/main.php"><i class="fas fa-list-alt"></i> To Do</a></li>

                <?php
                if (in_array($hello_name, $Level_10_Access, true)) {
                ?>

                <li class='dropdown'>
                    <a data-toggle='dropdown' class='dropdown-toggle' href='#'>Admin <b class='caret'></b></a>
                    <ul role='menu' class='dropdown-menu'>
                        <?php
                        if (isset($ffemployee) && $ffemployee == '1') { ?>
                            <li><a href="/addon/Staff/Main_Menu.php">Staff Database</a></li>
                        <?php }
                        ?>
                        <li class="divider"></li>
                        <li><a href='/app/admin/Admindash.php?admindash=y'>Control Panel</a></li>
                    </ul>
                    <?php } ?>
                </li>
            </ul>


            <div class="LIVERESULTS">

            </div>

            <script>
                function refresh_div() {
                    jQuery.ajax({
                        url: '/app/php/navbar.php',
                        type: 'POST',
                        success: function (results) {
                            jQuery(".LIVERESULTS").html(results);
                        }
                    });
                }

                t = setInterval(refresh_div, 3000);
            </script>


        </div>
    </nav>

    <?php
}
