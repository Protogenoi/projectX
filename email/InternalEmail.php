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

require_once(BASE_URL . '/includes/time.php');
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

if (isset($ffintemails) && $ffintemails == '0') {

    header('Location: ../email/Emails.php');
    die;
}

$ADL_PAGE_TITLE = "Internal Emails";
require_once(BASE_URL . '/app/core/head.php');

?>
<link rel="stylesheet" href="/resources/lib/summernote-master/dist/summernote.css">
<script type="text/javascript" src="/resources/lib/jquery/jquery-3.0.0.min.js"></script>
<script type="text/javascript" src="/resources/lib/jquery-ui-1.11.4/jquery-ui.min.js"></script>
<script type="text/javascript" src="/resources/lib/jquery-ui-1.11.4/external/jquery/jquery.js"></script>
<script type="text/javascript" src="/resources/templates/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/resources/lib/toastr/js/toastr.min.js"></script>
<script type="text/javascript" src="/resources/lib/summernote-master/dist/summernote.js"></script>
</head>
<body>

<?php require_once(BASE_URL . '/includes/navbar.php'); ?>
<div class="container">

    <?php email_sent_catch(); ?>

    <div class="panel panel-primary">
        <div class="panel-heading">Send Internal Email</div>
        <div class="panel-body">
            <fieldset>
                <form method="post" action="php/SendInternal.php?EXECUTE=1" enctype="multipart/form-data"
                      class="form-horizontal">

                    <div class="form-group">
                        <label class="col-md-4 control-label" for="email">Email</label>
                        <div class="col-md-4">
                            <select name="email" class="form-control" required>
                                <option></option>
                                <option value="nick@firstprioritygroup.co.uk">Nick</option>
                                <option value="matt@firstprioritygroup.co.uk">Matt</option>
                                <option value="michael@firstprioritygroup.co.uk">Michael</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label" for="subject">Subject:</label>
                        <div class="col-md-4">
                            <input id="subject" name="subject" placeholder="" class="form-control input-md" type="text"
                                   required>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                            <textarea class="form-control summernote" id="message" name="message"></textarea>
                        </div>
                    </div>


                    <input name="recipient" id="recipient" type="hidden"/>

                    <div class="form-group">
                        <label class="col-md-4 control-label" for="fileToUpload">Add attachment</label>
                        <div class="col-md-4">
                            <input id="fileToUpload" name="fileToUpload" class="input-file" type="file">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label" for="fileToUpload2">Add attachment (2)</label>
                        <div class="col-md-4">
                            <input id="fileToUpload2" name="fileToUpload2" class="input-file" type="file">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label" for="fileToUpload3">Add attachment (3)</label>
                        <div class="col-md-4">
                            <input id="fileToUpload3" name="fileToUpload3" class="input-file" type="file">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label" for="fileToUpload4">Add attachment (4)</label>
                        <div class="col-md-4">
                            <input id="fileToUpload4" name="fileToUpload4" class="input-file" type="file">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label" for="fileToUpload5">Add attachment (5)</label>
                        <div class="col-md-4">
                            <input id="fileToUpload5" name="fileToUpload5" class="input-file" type="file">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label" for="fileToUpload6">Add attachment (6)</label>
                        <div class="col-md-4">
                            <input id="fileToUpload6" name="fileToUpload6" class="input-file" type="file">
                        </div>
                    </div>

                    <br>
                    <br>

                    <div class="form-group">
                        <label class="col-md-4 control-label" for="Send Email"></label>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-warning "><span
                                    class="glyphicon glyphicon-envelope"></span> Send Email
                            </button>
                        </div>
                    </div>


                </form>
            </fieldset>

        </div>
    </div>

</div>


<script type="text/javascript" src="/resources/lib/summernote-master/dist/summernote.js"></script>

<script type="text/javascript">
    $(function () {
        $('.summernote').summernote({
            height: 200
        });


    });
</script>
</body>
</html>
