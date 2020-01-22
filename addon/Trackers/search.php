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
$page_protect->access_page(filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS), "", 2);
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

$EXECUTE = filter_input(INPUT_GET, 'EXECUTE', FILTER_SANITIZE_SPECIAL_CHARS);

require_once(BASE_URL . '/classes/database_class.php');
require_once(BASE_URL . '/class/login/login.php');

$CHECK_USER_LOGIN = new UserActions($hello_name, "NoToken");
$CHECK_USER_LOGIN->SelectToken();
$OUT = $CHECK_USER_LOGIN->SelectToken();

if (isset($OUT['TOKEN_SELECT']) && $OUT['TOKEN_SELECT'] != 'NoToken') {

    $TOKEN = $OUT['TOKEN_SELECT'];

}

$CHECK_USER_LOGIN->CheckAccessLevel();
$USER_ACCESS_LEVEL = $CHECK_USER_LOGIN->CheckAccessLevel();

$ACCESS_LEVEL = $USER_ACCESS_LEVEL['ACCESS_LEVEL'];

if ($ACCESS_LEVEL < 2) {

    $page_protect->log_out();

}

$ADL_PAGE_TITLE = "Search Quoted Clients";
require_once(BASE_URL . '/app/core/head.php');

?>
<link rel="stylesheet" type="text/css" href="/resources/lib/DataTable/datatables.min.css"/>
</head>
<body>

<?php require_once(BASE_URL . '/includes/navbar.php'); ?>

<div class="container">

    <div class="row">
        <div class="twelve columns">
            <ul class="ca-menu">
                <li>
                    <a href="/app/AddClient.php">
                        <span class="ca-icon"><i class="fa fa-user-plus"></i></span>
                        <div class="ca-content">
                            <h2 class="ca-main">Add New<br/> Client</h2>
                            <h3 class="ca-sub"></h3>
                        </div>
                    </a>
                </li>
                <?php if (isset($fflife) && $fflife == '1') { ?>
                    <li>
                        <a href="/app/SearchPolicies.php?EXECUTE=Life">
                            <span class="ca-icon"><i class="fa fa-search"></i></span>
                            <div class="ca-content">
                                <h2 class="ca-main">Search<br/>Life Policies</h2>
                                <h3 class="ca-sub"></h3>
                            </div>
                        </a>
                    </li>

                    <li>
                        <a href="/app/search.php">
                            <span class="ca-icon"><i class="fa fa-search"></i></span>
                            <div class="ca-content">
                                <h2 class="ca-main">Search<br/>Clients</h2>
                                <h3 class="ca-sub"></h3>
                            </div>
                        </a>
                    </li>

                <?php } ?>
            </ul>
        </div>
    </div>

    <div class="col-xs-12">

        <?php

        if (isset($fflife) && $fflife == '1') {
            ?>
            <table id="clients" class="table table-condensed">
                <thead>
                <tr>
                    <th></th>
                    <th>Date Added</th>
                    <th>Client Name</th>
                    <th>Phone #</th>
                    <th>Insurer</th>
                    <th>Closer</th>
                    <th>Status</th>
                    <th>View</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th></th>
                    <th>Date Added</th>
                    <th>Client Name</th>
                    <th>Phone #</th>
                    <th>Insurer</th>
                    <th>Closer</th>
                    <th>Status</th>
                    <th>View</th>
                </tr>
                </tfoot>
            </table>
        <?php }
        ?>

    </div>
</div>

<script type="text/javascript" src="/resources/lib/jquery/jquery-3.0.0.min.js"></script>
<script type="text/javascript" src="/resources/lib/jquery-ui-1.11.4/external/jquery/jquery.js"></script>
<script type="text/javascript" src="/resources/lib/DataTable/datatables.min.js"></script>
<script src="/resources/templates/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>

<script type="text/javascript">
    $(document).ready(function () {


        $('#LOADING').modal('show');
    })

    ;

    $(window).load(function () {
        $('#LOADING').modal('hide');
    });
</script>
<div class="modal modal-static fade" id="LOADING" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center">
                    <div align="center"><i class="fa fa-spinner fa-pulse fa-5x fa-lg"></i></div>
                    <br>
                    <h3>Populating client details... </h3>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function () {
        var table = $('#clients').DataTable({
            "response": true,
            "processing": true,
            "iDisplayLength": 15,
            "aLengthMenu": [[5, 10, 15, 25, 50, 100], [5, 10, 15, 25, 50, 100]],
            "language": {
                "processing": "<div></div><div></div><div></div><div></div><div></div>"
            },
            "ajax": "/addon/Trackers/JSON/search.php?EXECUTE=1&USER=<?php echo $hello_name; ?>&TOKEN=<?php echo $TOKEN; ?>",
            "columns": [
                {
                    "data": null,
                    "defaultContent": ''
                },
                {"data": "addedDate"},
                {"data": "clientName"},
                {"data": "phoneNumber"},
                {"data": "company"},
                {"data": "addedBy"},
                {"data": "status"},
                {
                    "data": "client_id",
                    "render": function (data) {
                        return '<a href="/addon/Trackers/client.php?search=' + data + '" class="btn btn-info btn-sm"><i class="fa fa-search"></i></a>';
                    }
                },
            ],
            "order": [[1, 'desc']]
        });

    });
</script>
<?php require_once(BASE_URL . '/app/Holidays.php'); ?>
</body>
</html>
