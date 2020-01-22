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
$page_protect->access_page(filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS), "", 10);
$hello_name = ($page_protect->user_full_name != "") ? $page_protect->user_full_name : $page_protect->user;

$USER_TRACKING = 0;

require_once(BASE_URL . '/includes/user_tracking.php');

require_once(BASE_URL . '/includes/time.php');

if (isset($FORCE_LOGOUT) && $FORCE_LOGOUT == 1) {
    $page_protect->log_out();
}

require_once(BASE_URL . '/includes/ADL_PDO_CON.php');
require_once(BASE_URL . '/includes/adl_features.php');
require_once(BASE_URL . '/includes/Access_Levels.php');

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

if ($ACCESS_LEVEL < 3) {

    header('Location: ../../../index.php?AccessDenied&USER=' . $hello_name . '&COMPANY=' . $COMPANY_ENTITY);
    die;

}

$RETURN = filter_input(INPUT_GET, 'RETURN', FILTER_SANITIZE_SPECIAL_CHARS);
$EXECUTE = filter_input(INPUT_GET, 'EXECUTE', FILTER_SANITIZE_SPECIAL_CHARS);

$ADL_PAGE_TITLE = "Doc Store";
require_once(BASE_URL . '/app/core/head.php');

?>
<link rel="stylesheet" href="/resources/lib/summernote-master/dist/summernote.css">
<link rel="stylesheet" type="text/css" href="/resources/lib/DataTable/datatables.min.css"/>
</head>
<body>

<?php require_once(BASE_URL . '/includes/navbar.php'); ?>

<div class="container">

    <div class="panel-group">
        <div class="panel panel-default">
            <div class="panel-heading">Doc Store</div>
            <div class="panel-body">

                <a data-toggle="modal" data-target="#mymodal" class="btn btn-default"><i class="fa fa-upload"></i>
                    Upload new files!</a>

                <table id="clients" class="display" width="auto" cellspacing="0">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Date</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Uploaded by</th>
                        <th>View</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th></th>
                        <th>Date</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Uploaded by</th>
                        <th>View</th>
                    </tr>
                    </tfoot>
                </table>

            </div>
        </div>
    </div>

</div>

<div class="modal fade" id="mymodal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalLabel">Add new documents.</h4>
            </div>
            <div class="modal-body">

                <form action="/app/docs/php/Upload.php?EXECUTE=1" method="POST" enctype="multipart/form-data">

                    <div class="form-group">
                        <label for="DOC_TITLE">Title:</label>
                        <input type="text" class="form-control" id="DOC_TITLE" name="DOC_TITLE"
                               placeholder="Document Title" required>
                    </div>

                    <div class="form-group">
                        <label for="DOC_CAT">Category:</label>
                        <select class="form-control" name='DOC_CAT' required>
                            <option value="">Select a category...</option>
                            <option value='Training'>Training</option>
                            <option value='Compliance'>Compliance</option>
                            <option value='Scripts'>Scripts</option>
                            <option value='Other'>Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <input type="file" class="form-control-file" id="file" name="file" aria-describedby="fileHelp"
                               required>
                        <small id="fileHelp" class="form-text text-muted">Max filesize 40MB.</small>
                    </div>

                    <button type="submit" class="btn btn-primary" name="btn-upload">UPLOAD</button>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" language="javascript" src="/resources/lib/jquery/jquery-3.0.0.min.js"></script>
<script type="text/javascript" src="/resources/lib/DataTable/datatables.min.js"></script>
<script type="text/javascript" language="javascript"
        src="/resources/templates/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
<script type="text/javascript" language="javascript">

    $(document).ready(function () {
        var table = $('#clients').DataTable({
            "response": true,
            "processing": true,
            "iDisplayLength": 25,
            "aLengthMenu": [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
            "language": {
                "processing": "<div></div><div></div><div></div><div></div><div></div>"
            },
            "ajax": "/app/docs/JSON/Upload.php?EXECUTE=1&USER=<?php echo $hello_name; ?>&TOKEN=<?php echo $TOKEN; ?>",
            "columns": [
                {
                    "className": 'details-control',
                    "orderable": false,
                    "data": null,
                    "defaultContent": ''
                },
                {"data": "docstore_uploads_date"},
                {"data": "docstore_uploads_title"},
                {"data": "docstore_uploads_cat"},
                {"data": "docstore_uploads_uploaded_by"},
                {
                    "data": "docstore_uploads_location",
                    "render": function (data, type, full, meta) {
                        return '<a href="/' + data + '" target="_blank"><i class="fa fa-search"></i></a>';
                    }
                }
            ]
        });

    });
</script>
</body>
</html>
