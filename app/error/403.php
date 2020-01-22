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
$access_denied = new Access_user;

$ADL_PAGE_TITLE = "403 Restricted Access";
require_once(__DIR__ . '/../../app/core/head.php');

?>
</head>
<body>

<?php require_once(__DIR__ . '/../../includes/navbar.php'); ?>

<div class="container">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">403
                <small>Restricted Access</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="/CRMmain.php">Home</a>
                </li>
                <li class="active">403</li>
            </ol>
        </div>
    </div>

    <div class="row">

        <div class="col-lg-12">
            <div class="jumbotron">
                <h1><span class="glyphicon glyphicon-lock"></span> 403</h1>

                <p>Access to the page requested is restricted, higher user access level is required. Here are some
                    helpful links to get you back on track:</p>

            </div>
        </div>
    </div>

    <script type="text/javascript" language="javascript" src="/resources/lib/jquery/jquery-3.0.0.min.js"></script>
    <script src="/resources/templates/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
</body>
</html>
