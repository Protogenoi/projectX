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
require_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_SPECIAL_CHARS) . '/app/core/doc_root.php';

require_once(BASE_URL . '/classes/access_user/access_user_class.php');
$page_protect = new Access_user;
$page_protect->access_page(filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS), "", 8);
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

if ($ffanalytics == '1') {
    require_once(BASE_URL . '/app/analyticstracking.php');
}

if (isset($fferror)) {
    if ($fferror == '1') {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }
}

if (in_array($hello_name, $Level_8_Access, true)) {


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

    if ($ACCESS_LEVEL < 8) {

        header('Location: /../../index.php?AccessDenied&USER=' . $hello_name . '&COMPANY=' . $COMPANY_ENTITY);
        die;

    }

    $ADL_PAGE_TITLE = "Search Compliants";
    require_once(BASE_URL . '/app/core/head.php');

    ?>
    <link rel="stylesheet" type="text/css" href="/resources/lib/DataTable/datatables.min.css"/>
    </head>
    <body>

    <?php require_once(BASE_URL . '/includes/navbar.php'); ?>


    <div class="container">

        <div class='notice notice-info' role='alert'><strong><i class='fa fa-edit fa-exclamation'></i> Info:</strong>
            Clients shown below have a complaint logged to them.
            <br>To log a complaint to a client:
            <ul>
                <li> Go to the clients profile.</li>
                <li> Click Timeline.</li>
                <li> When adding a note click the drop down and select "Log Complaint".</li>
            </ul>
        </div>

        <?php

        if ($fflife == '1') {
            ?>
            <table id="clients" class="display" cellspacing="0">
                <thead>
                <tr>
                    <th></th>
                    <th>Date Added</th>
                    <th>Client Name</th>
                    <th>Client Name</th>
                    <th>Post Code</th>
                    <th>Phone #</th>
                    <th>Company</th>
                    <th>View</th>
                    <th>Add Policy</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th></th>
                    <th>Date Added</th>
                    <th>Client Name</th>
                    <th>Client Name</th>
                    <th>Post Code</th>
                    <th>Phone #</th>
                    <th>Company</th>
                    <th>View</th>
                    <th>Add Policy</th>
                </tr>
                </tfoot>
            </table>
        <?php }
        ?>

    </div>

    <script type="text/javascript" language="javascript" src="/resources/lib/jquery/jquery-3.0.0.min.js"></script>
    <script type="text/javascript" language="javascript"
            src="/resources/lib/jquery-ui-1.11.4/external/jquery/jquery.js"></script>
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
    <div class="modal modal-static fade" id="LOADING" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-center">
                        <center><i class="fa fa-spinner fa-pulse fa-5x fa-lg"></i></center>
                        <br>
                        <h3>Populating client details... </h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" language="javascript">

        $(document).ready(function () {
            var table = $('#clients').DataTable({
                "response": true,
                "processing": true,
                "iDisplayLength": 10,
                "aLengthMenu": [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
                "language": {
                    "processing": "<div></div><div></div><div></div><div></div><div></div>"
                },
                "ajax": "/app/JSON/ComplaintsSearch.php?EXECUTE=1&USER=<?php echo $hello_name; ?>&TOKEN=<?php echo $TOKEN; ?>",
                "columns": [
                    {
                        "className": 'details-control',
                        "orderable": false,
                        "data": null,
                        "defaultContent": ''
                    },
                    {"data": "submitted_date"},
                    {"data": "Name"},
                    {"data": "Name2"},
                    {"data": "post_code"},
                    {"data": "phone_number"},
                    {"data": "company"},
                    {
                        "data": "client_id",
                        "render": function (data, type, full, meta) {
                            return '<a href="/app/Client.php?search=' + data + '">View</a>';
                        }
                    },
                    {
                        "data": "client_id",
                        "render": function (data, type, full, meta) {
                            return '<a href="/Life/AddPolicy.php?EXECUTE=1&search=' + data + '">Add Policy</a>';
                        }
                    }
                ]
            });

        });
    </script>
    <?php require_once(BASE_URL . '/app/Holidays.php'); ?>
    </body>
    </html>
<?php } else {
    header('Location: /../../CRMmain.php?AccessDenied');
    die;
}
