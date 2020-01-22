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

require_once(__DIR__ . '../../classes/access_user/access_user_class.php');
$page_protect = new Access_user;
$page_protect->access_page(filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS), "", 1);
$hello_name = ($page_protect->user_full_name != "") ? $page_protect->user_full_name : $page_protect->user;

$USER_TRACKING = 0;

require_once(__DIR__ . '../../includes/user_tracking.php');

require_once(__DIR__ . '/../includes/time.php');

if (isset($FORCE_LOGOUT) && $FORCE_LOGOUT == 1) {
    $page_protect->log_out();
}


require_once(__DIR__ . '../../includes/adl_features.php');
require_once(__DIR__ . '../../includes/Access_Levels.php');

if ($ffanalytics == '1') {
    require_once(__DIR__ . '../../app/analyticstracking.php');
}

if (isset($fferror)) {
    if ($fferror == '1') {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }
}

if ($ffkeyfactsemail == '0') {

    header('Location: ../email/Emails.php');
    die;
}

require_once(__DIR__ . '../../classes/database_class.php');
require_once(__DIR__ . '../../class/login/login.php');

$CHECK_USER_LOGIN = new UserActions($hello_name, "NoToken");
$CHECK_USER_LOGIN->CheckAccessLevel();

$USER_ACCESS_LEVEL = $CHECK_USER_LOGIN->CheckAccessLevel();

$ACCESS_LEVEL = $USER_ACCESS_LEVEL['ACCESS_LEVEL'];

if ($ACCESS_LEVEL < 1) {

    header('Location: /../index.php?AccessDenied&USER=' . $hello_name . '&COMPANY=' . $COMPANY_ENTITY);
    die;

}
?>
<!DOCTYPE html>
<!--
 Copyright (C) ADL CRM - All Rights Reserved
 Unauthorised copying of this file, via any medium is strictly prohibited
 Proprietary and confidential
 Written by Michael Owen <michael@adl-crm.uk>, 2018
-->
<html lang="en">
<title>ADL | KeyFacts Email</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="/resources/templates/bootstrap-3.3.5-dist/css/bootstrap.min.css">
<link rel="stylesheet" href="/resources/templates/bootstrap-3.3.5-dist/css/bootstrap-theme.min.css">
<link rel="stylesheet" href="/resources/templates/ADL/main.css" type="text/css"/>
<link rel="stylesheet" href="/resources/lib/sweet-alert/sweet-alert.min.css"/>
<link href="/img/favicon.ico" rel="icon" type="image/x-icon"/>
<script type="text/javascript" language="javascript"
        src="/resources/templates/fontawesome/svg-with-js/js/fontawesome-all.js"></script>
</head>
<body>

<?php require_once(__DIR__ . '/../includes/navbar.php');
?>

<div class="container">

    <?php require_once(__DIR__ . '../../email/php/Notifications.php'); ?>

    <div class="panel panel-primary">
        <div class="panel-heading">Key Facts Email

            <button type="button" class="btn btn-info btn-sm pull-right" data-toggle="modal" data-target="#emailmodal">
                <span class="glyphicon glyphicon-envelope" data-></span> Generic Email
            </button>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" id="emailform" name="emailform" method="post" enctype="multipart/form-data"
                  action="php/SendKeyFacts.php">
                <fieldset>

                    <div class="form-group">
                        <label class="col-md-4 control-label" for="email">Email</label>
                        <div class="col-md-4">
                            <input id="email" name="email" placeholder="bobross@gmail.com" class="form-control input-md"
                                   required="" type="text">

                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label" for="recipient">Client Name</label>
                        <div class="col-md-4">
                            <input id="recipient" name="recipient" placeholder="Mr Ross" class="form-control input-md"
                                   type="text">

                        </div>
                    </div>


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
                                    class="glyphicon glyphicon-envelope"></span> Send KeyFacts Email
                            </button>
                        </div>
                    </div>

                </fieldset>
            </form>

        </div>
    </div>
</div>


<div id="emailmodal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">


        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Generic Email</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="genemailform" method="post" enctype="multipart/form-data">

                    <div class="panel panel-default">
                        <div class="panel-heading">Send Email</div>
                        <div class="panel-body">

                            <fieldset>


                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="email">Email</label>
                                    <div class="col-md-4">
                                        <input id="email" name="email" placeholder="bobross@gmail.com"
                                               class="form-control input-md" required="" type="text">

                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="subject">Subject</label>
                                    <div class="col-md-4">
                                        <input id="subject" name="subject" placeholder="" class="form-control input-md"
                                               type="text">

                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="recipient">Recipient</label>
                                    <div class="col-md-4">
                                        <input id="recipient" name="recipient" placeholder="Mr Ross"
                                               class="form-control input-md" type="text">

                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="message">Message</label>
                                    <div class="col-md-4">
                                        <textarea class="form-control" id="message" name="message"></textarea>
                                    </div>
                                </div>

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
                                        <button type="submit" class="btn btn-success "><span
                                                class="glyphicon glyphicon-envelope"></span> Send Email
                                        </button>
                                    </div>
                                </div>

                            </fieldset>
                </form>

            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
    </div>
</div>

</div>
</div>
<script type="text/javascript" language="javascript" src="/resources/lib/jquery/jquery-3.0.0.min.js"></script>
<script type="text/javascript" language="javascript" src="/resources/lib/jquery-ui-1.11.4/jquery-ui.min.js"></script>
<script src="/resources/templates/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
<script src="/resources/lib/sweet-alert/sweet-alert.min.js"></script>
<script>
    $(document).ready(function () {
        $('#genemailform').on('submit', function (e) {
            $.ajax({
                url: 'php/SendGeneric.php',
                data: $(this).serialize(),
                type: 'POST',
                success: function (data) {
                    timer: 5000,
                        window.location.reload(true);
                    console.log(data);

                    swal("Success!", "Message sent!", "success");
                },
                error: function (data) {

                    swal("Oops...", "Something went wrong :(", "error");
                }
            });
            e.preventDefault();
        });
    });
</script>
</body>
</html>
