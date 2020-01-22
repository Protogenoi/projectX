<?php

/** @noinspection PhpIncludeInspection */
/** @noinspection ALL */

/**
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
 *  toastr - https://github.com/CodeSeven/toastr
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
require_once(BASE_URL . '/includes/adlfunctions.php');

if (isset($ffanalytics) && $ffanalytics == '1') {
    require_once(BASE_URL . '/app/analyticstracking.php');
}

if (isset($fferror) && $fferror == '1') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

require_once(BASE_URL . '/classes/database_class.php');
require_once(BASE_URL . '/class/login/login.php');
$CHECK_USER_LOGIN = new UserActions($hello_name, "NoToken");
$CHECK_USER_LOGIN->CheckAccessLevel();

$USER_ACCESS_LEVEL = $CHECK_USER_LOGIN->CheckAccessLevel();

$ACCESS_LEVEL = $USER_ACCESS_LEVEL['ACCESS_LEVEL'];

if ($ACCESS_LEVEL < 10) {

    header('Location: /../../../index.php?AccessDenied&USER=' . $hello_name . '&COMPANY=' . $COMPANY_ENTITY);
    die;

}
$ADL_PAGE_TITLE = "Export";
require_once(BASE_URL . '/app/core/head.php');

?>
<link rel="stylesheet" href="/resources/lib/jquery-ui-1.11.4/jquery-ui.min.css">
<script type="text/javascript" language="javascript" src="/resources/lib/jquery/jquery-3.0.0.min.js"></script>
<script type="text/javascript" language="javascript"
        src="/resources/lib/jquery-ui-1.11.4/external/jquery/jquery.js"></script>
<script src="/resources/lib/jquery-ui-1.11.4/jquery-ui.min.js"></script>
<script src="/resources/templates/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
<script>
    $(function () {
        $("#VITfrom").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+1"
        });
    });

    $(function () {
        $("#VITto").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+1"
        });
    });

    $(function () {
        $("#datefrom2").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+1"
        });
    });

    $(function () {
        $("#dateto2").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+1"
        });
    });

    $(function () {
        $("#datefrom3").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+1"
        });
    });

    $(function () {
        $("#dateto3").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+1"
        });
    });

    $(function () {
        $("#datefrom4").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+1"
        });
    });

    $(function () {
        $("#dateto4").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+1"
        });
    });

    $(function () {
        $("#datefrom5").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+1"
        });
    });

    $(function () {
        $("#dateto5").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+1"
        });
    });

    $(function () {
        $("#datefrom6").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+1"
        });
    });

    $(function () {
        $("#dateto6").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+1"
        });
    });

    $(function () {
        $("#datefrom7").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+1"
        });
    });

    $(function () {
        $("#dateto7").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+1"
        });
    });

    $(function () {
        $("#datefrom8").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+1"
        });
    });

    $(function () {
        $("#dateto8").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+1"
        });
    });
    $(function () {
        $("#datefromJUST").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+1"
        });
    });

    $(function () {
        $("#datetoJUST").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+1"
        });
    });
    $(function () {
        $("#DATEFROMOTHER").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+1"
        });
    });

    $(function () {
        $("#DATETOOTHER").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+1"
        });
    });
</script>
</head>
<body>
<?php require_once(BASE_URL . '/includes/navbar.php'); ?>

<div class="container">
    <div class="column-left">

        <form class="AddClient" action="/addon/Life/export/Export.php?EXECUTE=1" method="post">
            <h3>Export Business Register</h3>
            <p>
                <label for="Select" required>Select Policies that</label>
                <select name="Select">
                    <option value="submitted_date">Were Added</option>
                    <option value="sale_date">Were Sold</option>
                </select>
            </p>
            <br>

            <p>
                <select name="INSURER">
                    <option value="Royal London">Royal London</option>
                    <option value="National Friendly">National Friendly</option>
                    <option value="HSBC">HSBC</option>
                    <option value="LV">LV</option>
                    <option value="One Family">One Family</option>
                    <option value="Aviva">Aviva</option>
                    <option value="The Exeter">The Exeter</option>
                    <option value="New Vitality">New Vitality</option>
                    <option value="Vitality">Vitality</option>
                    <option value="Zurich">Zurich</option>
                    <option value="Scottish Widows">Scottish Widows</option>
                    <option value="Aegon">Aegon</option>
                </select>
            </p>

            <p>
                <label for="datefrom">From:</label>
                <input type="text" id="VITfrom" name="DATE_FROM" value="<?php if (isset($DATE_FROM)) {
                    echo $DATE_FROM;
                } ?>" required>
            </p>

            <p>
                <label for="dateto">To:</label>
                <input type="text" id="VITto" name="DATE_TO" value="<?php if (isset($DATE_TO)) {
                    echo $DATE_TO;
                } ?>" required>
            </p>

            <p>
                <button type="submit" class="btn btn-success "><span class="glyphicon glyphicon-save"></span> Export
                </button>
            </p>
        </form>

        <form class="AddClient" action="/addon/Life/export/Export.php?query=BYUSER" method="post">
            <h3>Export Business Register by user:</h3>
            <select id="USER" name="USER" class="form-control">
                <?php
                $query = $pdo->prepare("SELECT submitted_by from client_policy group by submitted_by");
                $query->execute();
                if ($query->rowCount() > 0) {
                    while ($result = $query->fetch(PDO::FETCH_ASSOC)) {

                        $submitted_by = $result['submitted_by'];

                        ?>
                        <option value="<?php if (isset($submitted_by)) {
                            echo $submitted_by;
                        } ?>"><?php if (isset($submitted_by)) {
                                echo $submitted_by;
                            } ?></option>
                    <?php }
                } ?>
            </select>
            </p>
            <button type="submit" class="btn btn-success "><span class="glyphicon glyphicon-save"></span> Export
            </button>
            </p>
        </form>

    </div>

    <div class="column-center">

    </div>

</div>
</body>
</html>
