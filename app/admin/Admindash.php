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
 * Written by michael <michael@adl-crm.uk>, 18/02/19 10:15
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
 *  Twilio - https://github.com/twilio
 *  SendGrid - https://github.com/sendgrid
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
require_once(BASE_URL . '/includes/ADL_PDO_CON.php');

if (isset($ffanalytics) && $ffanalytics == '1') {
    require_once(BASE_URL . '/app/analyticstracking.php');
}

if (isset($fferror) && $fferror == 1) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

$cnquery = $pdo->prepare("select company_name from company_details limit 1");
$cnquery->execute() or die(print_r($query->errorInfo(), true));
$companydetailsq = $cnquery->fetch(PDO::FETCH_ASSOC);

$companynamere = $companydetailsq['company_name'];

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

if ($ACCESS_LEVEL < 10) {

    header('Location: /../../../index.php?AccessDenied&USER=' . $hello_name . '&COMPANY=' . $COMPANY_ENTITY);
    die;

}
?>
<!DOCTYPE html>
<html lang="en">
<title>ADL | Control Panel</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="/resources/templates/bootstrap-3.3.5-dist/css/bootstrap.min.css">
<link rel="stylesheet" href="/resources/templates/bootstrap-3.3.5-dist/css/bootstrap-theme.min.css">
<link rel="stylesheet" href="/resources/templates/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" href="/resources/templates/ADL/control_panel.css">
<link rel="stylesheet" href="/resources/lib/sweet-alert/sweet-alert.min.css"/>
<link rel="stylesheet" href="/resources/templates/ADL/main.css" type="text/css"/>
<link rel="stylesheet" href="/resources/lib/sweet-alert/sweet-alert.min.css"/>
<link rel="stylesheet" href="/resources/lib/summernote-master/dist/summernote.css">
<link rel="stylesheet" href="/resources/lib/jquery-ui-1.11.4/jquery-ui.min.css">
<link href="/img/favicon.ico" rel="icon" type="image/x-icon"/>
</head>
<body>
<div id="wrapper">

    <div id="sidebar-wrapper">
        <h2>&nbsp; &nbsp; &nbsp;ADL CRM</h2>
        <div class="list-group"><br><br>

            <a class="list-group-item" href="/CRMmain.php"><i class="fa fa-home fa-fw"></i>&nbsp; CRM Home</a>

            <a class="list-group-item" href="Admindash.php?admindash=y"><i class="fa fa-cog fa-fw"></i>&nbsp; Admin
                Dashboard</a>

            <a class="list-group-item" href="?company=y"><i class="fa fa-info-circle fa-fw"></i>&nbsp; Company Info</a>

            <a class="list-group-item" href="?provider=y"><i class="fa fa-bank fa-fw"></i>&nbsp; Provider List</a>

            <a class="list-group-item" href="?users=y"><i class="fa fa-user fa-fw"></i>&nbsp; Users</a>

            <a class="list-group-item" href="?Emails=y"><i class="fa fa-envelope-o fa-fw"></i>&nbsp; Emails</a>

            <a class="list-group-item" href="?SMS=y"><i class="fa fa-commenting-o fa-fw"></i>&nbsp; SMS</a>

            <a class="list-group-item" href="?AssignTasks=y"><i class="fa fa-tasks fa-fw"></i>&nbsp; Task Assignment</a>

            <a class="list-group-item" href="?EWSSelect=y"><i class="fa fa-tasks fa-fw"></i>&nbsp; EWS Assignment</a>

            <a class="list-group-item" href="?Vicidial=y"><i class="fa fa-headphones fa-fw"></i>&nbsp; Bluetelecoms
                Integration</a>

            <a class="list-group-item" href="?Connex=y"><i class="fa fa-headphones fa-fw"></i>&nbsp; Connex Integration</a>

            <a class="list-group-item" href="?Settings=y"><i class="fa fa-desktop fa-fw"></i>&nbsp; Enable features</a>

            <a class="list-group-item" href="?Google=y"><i class="fa fa-google fa-fw"></i>&nbsp; Google Developer</a>

            <a class="list-group-item" href="?PostCode=y"><i class="fa fa-search fa-fw"></i>&nbsp; Post Code Lookup</a>

        </div>

    </div>

    <div id="page-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">

                    <?php
                    $SMSselect = filter_input(INPUT_GET, 'SMS', FILTER_SANITIZE_SPECIAL_CHARS);
                    $Emailselect = filter_input(INPUT_GET, 'Emails', FILTER_SANITIZE_SPECIAL_CHARS);
                    $AssignTasksselect = filter_input(INPUT_GET, 'AssignTasks', FILTER_SANITIZE_SPECIAL_CHARS);
                    $EWS_SELECT = filter_input(INPUT_GET, 'EWSSelect', FILTER_SANITIZE_SPECIAL_CHARS);
                    $usersselect = filter_input(INPUT_GET, 'users', FILTER_SANITIZE_SPECIAL_CHARS);
                    $adminselect = filter_input(INPUT_GET, 'admindash', FILTER_SANITIZE_SPECIAL_CHARS);
                    $vicidialselect = filter_input(INPUT_GET, 'Vicidial', FILTER_SANITIZE_SPECIAL_CHARS);
                    $connexselect = filter_input(INPUT_GET, 'Connex', FILTER_SANITIZE_SPECIAL_CHARS);
                    $providerselect = filter_input(INPUT_GET, 'provider', FILTER_SANITIZE_SPECIAL_CHARS);
                    $settingsselect = filter_input(INPUT_GET, 'Settings', FILTER_SANITIZE_SPECIAL_CHARS);
                    $companyselect = filter_input(INPUT_GET, 'company', FILTER_SANITIZE_SPECIAL_CHARS);
                    $Googleselect = filter_input(INPUT_GET, 'Google', FILTER_SANITIZE_SPECIAL_CHARS);
                    $PostCodeselect = filter_input(INPUT_GET, 'PostCode', FILTER_SANITIZE_SPECIAL_CHARS);

                    if ($companyselect == 'y') {

                        $cdquery = $pdo->prepare("select company_name, contact_person, ip_address, contact_number from company_details limit 1");
                        $cdquery->execute() or die(print_r($query->errorInfo(), true));
                        $companydetailsq = $cdquery->fetch(PDO::FETCH_ASSOC);

                        $cdname = $companydetailsq['company_name'];
                        $cdcp = $companydetailsq['contact_person'];
                        $cdip = $companydetailsq['ip_address'];
                        $cdcn = $companydetailsq['contact_number'];
                        ?>
                        <h1><i class="fa fa-info-circle"></i> Company Settings</h1>

                        <?php
                        $companydetails = filter_input(INPUT_GET, 'companydetails', FILTER_SANITIZE_SPECIAL_CHARS);

                        if (isset($companydetails)) {

                            $companydetails = filter_input(INPUT_GET, 'companydetails',
                                FILTER_SANITIZE_SPECIAL_CHARS);

                            if ($companydetails == 'y') {

                                print("<br><div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa  fa-check-circle-o fa-lg\"></i> Success:</strong> Company details updated!</div><br>");
                            }

                            if ($companydetails == 'failed') {

                                print("<br><div class=\"notice notice-danger\" role=\"alert\"><strong><i class=\"fa fa-exclamation-triangle fa-lg\"></i> Error:</strong> No changes have been made!</div><br>");
                            }
                        }

                        $companylogo = filter_input(INPUT_GET, 'companylogo', FILTER_SANITIZE_SPECIAL_CHARS);

                        if (isset($companylogo)) {

                            $companylogo = filter_input(INPUT_GET, 'companylogo', FILTER_SANITIZE_SPECIAL_CHARS);

                            if ($companylogo == 'y') {

                                print("<br><div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa  fa-check-circle-o fa-lg\"></i> Success:</strong> Company logo updated!</div><br>");
                            }

                            if ($companylogo == 'deleted') {

                                print("<br><div class=\"notice notice-danger\" role=\"alert\"><strong><i class=\"fa fa-exclamation-triangle fa-lg\"></i> Deleted:</strong> Image deleted!</div><br>");
                            }

                            if ($companylogo == 'failed') {

                                print("<br><div class=\"notice notice-danger\" role=\"alert\"><strong><i class=\"fa fa-exclamation-triangle fa-lg\"></i> Error:</strong> No changes have been made!</div><br>");
                            }
                        }
                        ?>

                        <p>Configuring these settings will dynamically update your CRM</p>
                        <br>

                        <ul class="nav nav-pills">
                            <li class="active"><a data-toggle="pill" href="#"><i class="fa  fa-info-circle"></i></a>
                            </li>
                            <li><a data-toggle="pill" href="#ComInfo">Company Info</a></li>
                            <li><a data-toggle="pill" href="#ComLogo">Add Logo</a></li>
                        </ul>
                        <br>

                        <div class="tab-content">
                            <div id="ComInfo" class="tab-pane fade">

                                <br>

                                <form class="form-horizontal" method="POST"
                                      action="php/AddCompanyDetails.php?company">
                                    <fieldset>
                                        <legend>Settings</legend>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="companyname">Company
                                                Name</label>
                                            <div class="col-md-4">
                                                <input id="companyname" name="companyname" placeholder=""
                                                       class="form-control input-md"
                                                       required="" <?php if (isset($cdname)) {
                                                    echo "value='$cdname'";
                                                } ?> type="text">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="contactname">Contact
                                                Name</label>
                                            <div class="col-md-4">
                                                <input id="contactname" name="contactname" placeholder=""
                                                       class="form-control input-md" <?php if (isset($cdname)) {
                                                    echo "value='$cdcp'";
                                                } ?> type="text">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="companynum">Contact #</label>
                                            <div class="col-md-4">
                                                <input id="companynum" name="companynum" placeholder=""
                                                       class="form-control input-md"
                                                       required="" <?php if (isset($cdname)) {
                                                    echo "value='$cdcn'";
                                                } ?> type="text">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="companyip">Company Public
                                                IP</label>
                                            <div class="col-md-4">
                                                <input id="companyip" name="companyip" placeholder="192.169.1.1"
                                                       class="form-control input-md"
                                                       required="" <?php if (isset($cdname)) {
                                                    echo "value='$cdip'";
                                                } ?> type="text">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="singlebutton"></label>
                                            <div class="col-md-4">
                                                <button id="singlebutton" name="singlebutton"
                                                        class="btn btn-primary">Submit
                                                </button>
                                            </div>
                                        </div>

                                    </fieldset>
                                </form>

                            </div>

                            <div id="ComLogo" class="tab-pane fade">

                                <form action="php/CompanyImageUpload.php?CompImage" method="post"
                                      enctype="multipart/form-data">
                                    <label for="file">Select file...
                                        <input type="file" name="file"/>
                                    </label>
                                    <label for="uploadtype">
                                        <div class="form-group">
                                            <select style="width: 170px" class="form-control" name="uploadtype"
                                                    required>
                                                <option value="">Select...</option>
                                                <option value="Login Logo">Login Logo</option>
                                                <option value="Email Account 1">Email Account 1</option>
                                                <option value="Email Account 2">Email Account 2</option>
                                                <option value="Email Account 3">Email Account 3</option>
                                                <option value="Email Account 4">Email Account 4</option>
                                            </select>
                                        </div>
                                    </label>
                                    <button type="submit" class="btn btn-success" name="submit"><span
                                                class="glyphicon glyphicon-arrow-up"> </span></button>
                                </form>

                                <?php
                                $cimages = $pdo->prepare("SELECT id, file from tbl_uploads where uploadtype = 'Login Logo' OR uploadtype like 'Email Account %'");
                                $cimages->execute();
                                $imgcomformid = 0;

                                echo "<table class=\"table table-hover\">";
                                echo
                                "<thead>
                                    <tr>
                                    <th colspan='3'><h3><span class=\"label label-info\">Uploaded Images</span></h3></th>
                                    </tr>
                                    <th>ID</th>
                                    <th>Image</th>
                                    <th></th>
                                    </tr>
                                    </thead>";

                                if ($cimages->rowCount() > 0) {
                                    while ($comimages = $cimages->fetch(PDO::FETCH_ASSOC)) {
                                        $imgcomformid++;

                                        echo '<tr>';
                                        echo "<td>" . $comimages['id'] . "</td>";
                                        echo "<td>" . $comimages['file'] . "</td>";
                                        echo "<form id='comimgdelete$imgcomformid' action='php/DeleteCompanyImage.php?deleteimage=y' method='POST'>";
                                        echo "<input type='hidden' name='uploadid' value='" . $comimages['id'] . "'>";
                                        echo "<input type='hidden' name='uploadfile' value='" . $comimages['file'] . "'>";
                                        echo "<td><button type=\"submit\" name=\"deletenotessubmit\" class=\"btn btn-danger btn-xs\"><span class=\"glyphicon glyphicon-remove\"></span></button></td>";
                                        echo "</form>";
                                        echo "</tr>";
                                        echo "\n";
                                        ?>
                                        <script>
                                            document.querySelector('#comimgdelete<?php echo $imgcomformid ?>').addEventListener('submit', function (e) {
                                                var form = this;
                                                e.preventDefault();
                                                swal({
                                                        title: "Delete task?",
                                                        text: "Are you sure you want to delete this task?",
                                                        type: "warning",
                                                        showCancelButton: true,
                                                        confirmButtonColor: '#DD6B55',
                                                        confirmButtonText: 'Yes, I am sure!',
                                                        cancelButtonText: "No, cancel it!",
                                                        closeOnConfirm: false,
                                                        closeOnCancel: false
                                                    },
                                                    function (isConfirm) {
                                                        if (isConfirm) {
                                                            swal({
                                                                title: 'Deleted!',
                                                                text: 'task deleted!',
                                                                type: 'success'
                                                            }, function () {
                                                                form.submit();
                                                            });

                                                        } else {
                                                            swal("Cancelled", "Task deleted", "error");
                                                        }
                                                    });
                                            });

                                        </script>
                                        <?php
                                    }
                                } else {
                                    echo "<div class=\"notice notice-warning\" role=\"alert\"><strong>Info!</strong> No Data/Information Available</div>";
                                }
                                echo "</table>";
                                ?>
                            </div>
                        </div>

                        <?php
                    }

                    if ($Googleselect == 'y') {

                        $gdquery = $pdo->prepare("select tracking_id, api from google_dev limit 1");
                        $gdquery->execute() or die(print_r($query->errorInfo(), true));
                        $gdre = $gdquery->fetch(PDO::FETCH_ASSOC);

                        $gdtracking = $gdre['tracking_id'];
                        $gdapi = $gdre['api'];
                        ?>

                        <h1><i class="fa fa-google"></i> Developer Settings</h1>
                        <?php
                        $google = filter_input(INPUT_GET, 'google', FILTER_SANITIZE_SPECIAL_CHARS);

                        if (isset($google)) {


                            if ($google == 'updated') {

                                print("<br><div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa  fa-check-circle-o fa-lg\"></i> Success:</strong> Google settings updated!</div><br>");
                            }

                            if ($google == 'y') {

                                print("<br><div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa  fa-check-circle-o fa-lg\"></i> Success:</strong> Google settings added!</div><br>");
                            }

                            if ($google == 'failed') {

                                print("<br><div class=\"notice notice-danger\" role=\"alert\"><strong><i class=\"fa fa-exclamation-triangle fa-lg\"></i> Error:</strong> No changes have been made!</div><br>");
                            }
                        }
                        ?>
                        <form class="form-horizontal" method="POST" action="php/AddGoogleDeveloper.php?add=y">
                            <fieldset>
                                <legend>Web/Android/iOS Development</legend>

                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="googletrackingid">Tracking ID</label>
                                    <div class="col-md-4">
                                        <input id="googletrackingid" name="googletrackingid"
                                               placeholder="" <?php if (isset($gdtracking)) {
                                            echo "value='$gdtracking'";
                                        } ?> class="form-control input-md" type="text">
                                        <span class="help-block">developers.google.com</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="googleapi">API</label>
                                    <div class="col-md-4">
                                        <input id="googleapi" name="googleapi"
                                               placeholder="" <?php if (isset($gdapi)) {
                                            echo "value='$gdapi'";
                                        } ?> class="form-control input-md" type="text">
                                        <span class="help-block">developers.google.com</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="submit"></label>
                                    <div class="col-md-4">
                                        <button id="submit" name="submit" class="btn btn-success">Submit</button>
                                    </div>
                                </div>

                            </fieldset>
                        </form>

                        <?php
                    }


                    if ($PostCodeselect == 'y') {

                        $PostcodeQuery = $pdo->prepare("select api_key from api_keys WHERE type ='PostCode' limit 1");
                        $PostcodeQuery->execute() or die(print_r($query->errorInfo(), true));
                        $PDre = $PostcodeQuery->fetch(PDO::FETCH_ASSOC);

                        $PostCodeKey = $PDre['api_key'];
                        ?>

                        <h1><i class="fa fa-search"></i> Post Code Lookup API Key</h1>

                        <?php
                        if ($PostCodeselect == 'y') {

                            $PostCodeMSG = filter_input(INPUT_GET, 'PostCodeMSG', FILTER_SANITIZE_NUMBER_INT);

                            if ($PostCodeMSG == '1') {

                                echo "<br><div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa  fa-check-circle-o fa-lg\"></i> Success:</strong> API Key updated!</div><br>";
                            }

                            if ($PostCodeMSG == '2') {

                                echo "<br><div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa  fa-check-circle-o fa-lg\"></i> Success:</strong> API Keey added!</div><br>";
                            }

                            if ($PostCodeMSG == '3') {

                                echo "<br><div class=\"notice notice-danger\" role=\"alert\"><strong><i class=\"fa fa-exclamation-triangle fa-lg\"></i> Error:</strong> No changes have been made!</div><br>";
                            }
                        }
                        ?>

                        <form class="form-horizontal" method="POST" action="php/AddAPIKey.php?AddType=PostCode">
                            <fieldset>
                                <legend>Ideal Post Code API Key</legend>

                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="PostCodeAPI">API</label>
                                    <div class="col-md-4">
                                        <input id="PostCodeAPI" name="PostCodeAPI"
                                               placeholder="" <?php if (isset($PostCodeKey)) {
                                            echo "value='$PostCodeKey'";
                                        } ?> class="form-control input-md" type="text">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="submit"></label>
                                    <div class="col-md-4">
                                        <button id="submit" name="submit" class="btn btn-success">Submit</button>
                                    </div>
                                </div>

                            </fieldset>
                        </form>


                        <?php
                    }


                    if ($SMSselect == 'y') {

                        $smsquery = $pdo->prepare("select smsprovider, smsusername, AES_DECRYPT(smspassword, UNHEX(:key)) AS smspassword from sms_accounts limit 1");
                        $smsquery->bindParam(':key', $EN_KEY, PDO::PARAM_STR, 500);
                        $smsquery->execute() or die(print_r($query->errorInfo(), true));
                        $smsaccountR = $smsquery->fetch(PDO::FETCH_ASSOC);

                        $smsuser = $smsaccountR['smsusername'];
                        $smspass = $smsaccountR['smspassword'];
                        $smspro = $smsaccountR['smsprovider'];
                        ?>

                        <h1><i class="fa fa-commenting-o"></i> SMS Configuration</h1>
                        <br>
                        <?php
                        $smsaccount = filter_input(INPUT_GET, 'smsaccount', FILTER_SANITIZE_SPECIAL_CHARS);


                        if (isset($smsaccount)) {


                            if ($smsaccount == 'y') {

                                print("<br><div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa  fa-check-circle-o fa-lg\"></i> Success:</strong> SMS account updated!</div><br>");
                            }

                            if ($smsaccount == 'messadded') {

                                print("<br><div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa  fa-check-circle-o fa-lg\"></i> Success:</strong> SMS message added updated!</div><br>");
                            }
                            if ($smsaccount == 'failed') {

                                print("<br><div class=\"notice notice-danger\" role=\"alert\"><strong><i class=\"fa fa-exclamation-triangle fa-lg\"></i> Error:</strong> No changes have been made!</div><br>");
                            }
                        }

                        $SMSupdated = filter_input(INPUT_GET, 'SMSupdated', FILTER_SANITIZE_SPECIAL_CHARS);

                        if (isset($SMSupdated)) {

                            $SMSupdated = filter_input(INPUT_GET, 'SMSupdated', FILTER_SANITIZE_SPECIAL_CHARS);

                            if ($SMSupdated == 'y') {

                                print("<br><div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa  fa-check-circle-o fa-lg\"></i> Success:</strong> SMS template updated!</div><br>");
                            }


                            if ($SMSupdated == 'failed') {

                                print("<br><div class=\"notice notice-danger\" role=\"alert\"><strong><i class=\"fa fa-exclamation-triangle fa-lg\"></i> Error:</strong> No changes have been made!</div><br>");
                            }
                        }
                        ?>

                        <ul class="nav nav-pills">
                            <li class="active"><a data-toggle="pill" href="#"><i class="fa  fa-envelope-o"></i></a>
                            </li>
                            <li><a data-toggle="pill" href="#SMSTemplates">SMS Templates</a></li>
                            <?php
                            $SMS_TAB_QRY = $pdo->prepare("SELECT company from sms_templates GROUP BY company");
                            $SMS_TAB_QRY->execute();
                            if ($SMS_TAB_QRY->rowCount() > 0) {
                                $SMS_INSURER_ARRAY = array();
                                while ($result = $SMS_TAB_QRY->fetch(PDO::FETCH_ASSOC)) {
                                    $SMS_COMPANY_VAR = preg_replace('/\s+/', '', $result['company']);
                                    array_push($SMS_INSURER_ARRAY, $SMS_COMPANY_VAR);

                                    ?>
                                    <li><a data-toggle="pill"
                                           href="#SMS_<?php echo $SMS_COMPANY_VAR; ?>"><?php echo $result['company']; ?></a>
                                    </li>
                                    <?php
                                }
                            }

                            ?>
                            <li><a data-toggle="pill" href="#SMSAddmessage">Add message</a></li>
                            <li><a data-toggle="pill" href="#TwilioSettings">Twilio</a></li>

                        </ul>
                        <br>

                        <div class="tab-content">

                            <?php
                            foreach ($SMS_INSURER_ARRAY as $SMS_COMPANY) { ?>
                                <div id="SMS_<?php echo $SMS_COMPANY; ?>" class="tab-pane fade"> <?php
                                    $query = $pdo->prepare("SELECT id, title, message, insurer, company from sms_templates WHERE REPLACE(`company`, ' ', '') =:COMPANY");
                                    $query->bindParam(':COMPANY', $SMS_COMPANY, PDO::PARAM_STR);
                                    $query->execute();
                                    if ($query->rowCount() > 0) { ?>


                                        <?php
                                        $i = 0; ?>
                                        <table class="table table-hover">
                                            <thead>
                                            <tr>
                                                <th colspan='4'>SMS Templates</th>
                                            </tr>
                                            <tr>
                                                <th>ID</th>
                                                <th>Title</th>
                                                <th>Message</th>
                                                <th>Insurer</th>
                                                <th>Company</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <?php
                                            while ($result = $query->fetch(PDO::FETCH_ASSOC)) {
                                                $i++;
                                                echo '<tr>';
                                                echo "<td>" . $result['id'] . "</td>";
                                                echo "<td>" . $result['title'] . "</td>";
                                                echo "<td>" . $result['message'] . "</td>";
                                                echo "<td>" . $result['insurer'] . "</td>";
                                                echo "<td>" . $result['company'] . "</td>";
                                                echo "<td>
<button data-toggle='modal' data-target='#editsms$SMS_COMPANY$i' class='btn btn-warning btn-xs'><i class='fa fa-edit'></i> </button>


<div id=\"editsms$SMS_COMPANY$i\" class=\"modal fade\" role=\"dialog\">
  <div class=\"modal-dialog\">

    <div class=\"modal-content\">
      <div class=\"modal-header\">
      <h4 class='modal-title'>SMS Template</h4>
        <button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
      </div>
      <div class=\"modal-body\">
        <form action=\"php/SMSupdate.php?updatesms=y\" name=\"updatesms\" class=\"form-horizontal\" method=\"POST\">
                
<fieldset>

<legend>SMS Edit</legend>

<input type=\"hidden\" name=\"id\" value='" . $result['id'] . "'>

<div class='form-group'>
  <label class='col-sm-2 control-label' for='title'></label>  
  <div class='col-sm-10'>
  <input id='title' name='title' placeholder='' value='" . $result['title'] . "' class='form-control input-md' required='' type='readonly'>
    
  </div>
</div>

<div class='form-group'>
  <label class='col-sm-2 control-label' for='insurer'></label>  
  <div class='col-sm-10'>
  <input id='insurer' name='insurer' placeholder='' value='" . $result['insurer'] . "' class='form-control input-md' required='' type='text'>
    
  </div>
</div>


<div class='form-group'>
  <label class='col-sm-2 control-label' for='message'></label>
  <div class='col-sm-10'>                     
    <textarea class='form-control' style='min-width: 100%' id='message' name='message'>" . $result['message'] . "</textarea>
  </div>
</div>

<div class='form-group'>
  <label class='col-sm-2 control-label' for='Company'></label>
  <div class='col-sm-10'>                     
    <textarea class='form-control' style='min-width: 100%' id='company' name='company'>" . $result['company'] . "</textarea>
  </div>
</div>

<div class='form-group'>
  <label class='col-sm-2 control-label' for='singlebutton'></label>
  <div class='col-sm-10'>
    <button id='singlebutton' name='singlebutton' class='btn btn-primary'>Submit changes</button>
  </div>
</div>

</fieldset>


        </form>
      </div>
      <div class=\"modal-footer\">
        <button type=\"button\" class=\"btn btn-warning\" data-dismiss=\"modal\">Close</button>
      </div>
    </div>

  </div>
</div>   
   </td>";
                                                echo "</tr>";
                                                echo "\n";
                                            } ?>
                                        </table>

                                    <?php } ?> </div>  <?php

                            }

                            ?>

                            <div id="SMSTemplates" class="tab-pane fade">

                                <?php
                                $query = $pdo->prepare("SELECT id, title, message, insurer, company from sms_templates");

                                echo "<table class=\"table table-hover\">";

                                echo "  <thead>
	<tr>
	<th colspan='4'>SMS Templates</th>
	</tr>
    	<tr>
	<th>ID</th>
	<th>Title</th>
	<th>Message</th>
        <th>Insurer</th>
        <th>Company</th>
	<th></th>
	</tr>
	</thead>";

                                $query->execute();
                                $i = 0;
                                if ($query->rowCount() > 0) {
                                    while ($result = $query->fetch(PDO::FETCH_ASSOC)) {
                                        $i++;
                                        echo '<tr>';
                                        echo "<td>" . $result['id'] . "</td>";
                                        echo "<td>" . $result['title'] . "</td>";
                                        echo "<td>" . $result['message'] . "</td>";
                                        echo "<td>" . $result['insurer'] . "</td>";
                                        echo "<td>" . $result['company'] . "</td>";
                                        echo "<td>
<button data-toggle='modal' data-target='#editsms$i' class='btn btn-warning btn-xs'><i class='fa fa-edit'></i> </button>


<div id=\"editsms$i\" class=\"modal fade\" role=\"dialog\">
  <div class=\"modal-dialog\">

    <div class=\"modal-content\">
      <div class=\"modal-header\">
      <h4 class='modal-title'>SMS Template</h4>
        <button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
      </div>
      <div class=\"modal-body\">
        <form action=\"php/SMSupdate.php?updatesms=y\" name=\"updatesms\" class=\"form-horizontal\" method=\"POST\">
                
<fieldset>

<legend>SMS Edit</legend>

<input type=\"hidden\" name=\"id\" value='" . $result['id'] . "'>

<div class='form-group'>
  <label class='col-sm-2 control-label' for='title'></label>  
  <div class='col-sm-10'>
  <input id='title' name='title' placeholder='' value='" . $result['title'] . "' class='form-control input-md' required='' type='readonly'>
    
  </div>
</div>

<div class='form-group'>
  <label class='col-sm-2 control-label' for='insurer'></label>  
  <div class='col-sm-10'>
  <input id='insurer' name='insurer' placeholder='' value='" . $result['insurer'] . "' class='form-control input-md' required='' type='text'>
    
  </div>
</div>


<div class='form-group'>
  <label class='col-sm-2 control-label' for='message'></label>
  <div class='col-sm-10'>                     
    <textarea class='form-control' style='min-width: 100%' id='message' name='message'>" . $result['message'] . "</textarea>
  </div>
</div>

<div class='form-group'>
  <label class='col-sm-2 control-label' for='Company'></label>
  <div class='col-sm-10'>                     
    <textarea class='form-control' style='min-width: 100%' id='company' name='company'>" . $result['company'] . "</textarea>
  </div>
</div>

<div class='form-group'>
  <label class='col-sm-2 control-label' for='singlebutton'></label>
  <div class='col-sm-10'>
    <button id='singlebutton' name='singlebutton' class='btn btn-primary'>Submit changes</button>
  </div>
</div>

</fieldset>


        </form>
      </div>
      <div class=\"modal-footer\">
        <button type=\"button\" class=\"btn btn-warning\" data-dismiss=\"modal\">Close</button>
      </div>
    </div>

  </div>
</div>   
   </td>";
                                        echo "</tr>";
                                        echo "\n";
                                    }
                                } else {
                                    echo "<div class=\"notice notice-warning\" role=\"alert\"><strong>Info!</strong> No Data/Information Available</div>";
                                }
                                echo "</table>";
                                ?>

                            </div>

                            <div id="SMSAddmessage" class="tab-pane fade">

                                <p>Add new SMS message(s).</p>

                                <form class="form-horizontal" method="POST"
                                      action="php/Addsmsaccounts.php?newsmsmessage=y">
                                    <fieldset>

                                        <legend>New SMS</legend>


                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="title">Title</label>
                                            <div class="col-md-4">
                                                <input id="smstitle" name="smstitle" placeholder=""
                                                       class="form-control input-md" type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="insurer">Insurer</label>
                                            <div class="col-md-4">
                                                <input id="insurer" name="insurer" placeholder=""
                                                       class="form-control input-md" type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="COMPANY">Company</label>
                                            <div class="col-md-4">
                                                <input id="insurer" name="COMPANY" placeholder=""
                                                       class="form-control input-md" type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="message">Message</label>
                                            <div class="col-md-4">
                                                    <textarea class="form-control" id="smsmessage"
                                                              name="smsmessage"></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="singlebutton"></label>
                                            <div class="col-md-4">
                                                <button id="singlebutton" name="singlebutton"
                                                        class="btn btn-primary">Submit
                                                </button>
                                            </div>
                                        </div>

                                    </fieldset>
                                </form>


                            </div>

                            <div id="TwilioSettings" class="tab-pane fade">

                                <?php
                                $TWILIO_QRY = $pdo->prepare("select twilio_account_sid, twilio_account_token from twilio_account LIMIT 1");
                                $TWILIO_QRY->execute() or die(print_r($query->errorInfo(), true));
                                $TWILIO_RESULT = $TWILIO_QRY->fetch(PDO::FETCH_ASSOC);

                                $TWILIO_SID = $TWILIO_RESULT['twilio_account_sid'];
                                $TWILIO_TOKEN = $TWILIO_RESULT['twilio_account_token'];
                                ?>

                                <form class="form-horizontal" method="POST"
                                      action="php/Addsmsaccounts.php?addsms&provider=Twilio">
                                    <fieldset>
                                        <legend>Twilio Settings</legend>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="SID">ACCOUNT SID</label>
                                            <div class="col-md-4">
                                                <input id="smsusername" name="SID"
                                                       placeholder="Used for Twilio REST API"
                                                       class="form-control input-md"
                                                       value="<?php if (isset($TWILIO_SID)) {
                                                           echo $TWILIO_SID;
                                                       } ?>" required type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="TOKEN">Auth Token</label>
                                            <div class="col-md-4">
                                                <input id="smspassword" name="TOKEN" placeholder=""
                                                       class="form-control input-md"
                                                       value="<?php if (isset($TWILIO_TOKEN)) {
                                                           echo $TWILIO_TOKEN;
                                                       } ?>" required type="password">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="singlebutton"></label>
                                            <div class="col-md-4">
                                                <button id="submitsms" name="submitsms" class="btn btn-success">
                                                    Submit
                                                </button>
                                            </div>
                                        </div>

                                    </fieldset>
                                </form>

                            </div>

                        </div>

                    <?php }

                    if ($Emailselect == 'y') {
                        ?>

                        <h1><i class="fa fa-envelope-o"></i> Email Configuration</h1>

                        <?php
                        $emailaccount = filter_input(INPUT_GET, 'emailaccount', FILTER_SANITIZE_SPECIAL_CHARS);

                        if (isset($emailaccount)) {

                            $emailaccount = filter_input(INPUT_GET, 'emailaccount', FILTER_SANITIZE_SPECIAL_CHARS);

                            if ($emailaccount == 'account1') {

                                print("<br><div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa  fa-check-circle-o fa-lg\"></i> Success:</strong> Settings for email account 1 updated!</div><br>");
                            }

                            if ($emailaccount == 'account2') {

                                print("<br><div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa  fa-check-circle-o fa-lg\"></i> Success:</strong> Settings for email account 2 updated!</div><br>");
                            }

                            if ($emailaccount == 'account3') {

                                print("<br><div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa  fa-check-circle-o fa-lg\"></i> Success:</strong> Settings for email account 3 updated!</div><br>");
                            }

                            if ($emailaccount == 'account4') {

                                print("<br><div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa  fa-check-circle-o fa-lg\"></i> Success:</strong> Settings for email account 4 updated!</div><br>");
                            }
                            if ($emailaccount == 'failed') {

                                print("<br><div class=\"notice notice-danger\" role=\"alert\"><strong><i class=\"fa fa-exclamation-triangle fa-lg\"></i> Error:</strong> No changes have been made!</div><br>");
                            }
                        }
                        ?>

                        <p>Email account settings can be found on your email providers web page. Too add an account
                            click one of the options below.</p>

                        <ul class="nav nav-pills">
                            <li class="active"><a data-toggle="pill" href="#"><i class="fa  fa-cogs"></i></a></li>
                            <li><a data-toggle="pill" href="#account1">Account 1</a></li>
                            <li><a data-toggle="pill" href="#account2">Account 2</a></li>
                            <li><a data-toggle="pill" href="#account3">Account 3</a></li>
                            <li><a data-toggle="pill" href="#account4">Account 4</a></li>
                            <li><a data-toggle="pill" href="#emailsig">Signatures</a></li>
                        </ul>
                        <br>

                        <div class="tab-content">
                            <div id="account1" class="tab-pane fade">
                                <?php
                                $emailaccid = "account1";

                                $E_ACT_ONE = $pdo->prepare("SELECT imap, imapport, popport, pop, emailtype, email, emailfrom, emailreply, emailbcc, emailsubject, smtp, smtpport, displayname, password FROM email_accounts WHERE emailaccount=:emailaccidholder");
                                $E_ACT_ONE->bindParam(':emailaccidholder', $emailaccid, PDO::PARAM_STR);
                                $E_ACT_ONE->execute() or die(print_r($E_ACT_ONE->errorInfo(), true));
                                $emailacc1 = $E_ACT_ONE->fetch(PDO::FETCH_ASSOC);

                                $emailfromdb = $emailacc1['emailfrom'];
                                $emailbccdb = $emailacc1['emailbcc'];
                                $emailreplydb = $emailacc1['emailreply'];
                                $emailsubjectdb = $emailacc1['emailsubject'];
                                $emailsmtpdb = $emailacc1['smtp'];
                                $emailsmtpportdb = $emailacc1['smtpport'];
                                $emaildisplaynamedb = $emailacc1['displayname'];
                                $passworddb = $emailacc1['password'];
                                $emaildb = $emailacc1['email'];
                                $emailtype = $emailacc1['emailtype'];
                                $pop = $emailacc1['pop'];
                                $popport = $emailacc1['popport'];
                                $imap = $emailacc1['imap'];
                                $imapport = $emailacc1['imapport'];
                                ?>
                                <form class="form-horizontal" method="POST" action="php/AddEmailAccounts.php?add=y">
                                    <fieldset>

                                        <legend>Email Settings (Account 1)</legend>

                                        <input type="hidden" value="account1" name="emailaccount">

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="displayname">Display
                                                Name</label>
                                            <div class="col-md-4">
                                                <input id="displayname" name="displayname"
                                                       placeholder="Company name"
                                                       value="<?php echo $emaildisplaynamedb; ?>"
                                                       class="form-control input-md" required="" type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="emailfrom">From:</label>
                                            <div class="col-md-4">
                                                <input id="displayname" name="emailfrom"
                                                       class="form-control input-md" required=""
                                                       value="<?php echo $emailfromdb; ?>" type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="emailreply">Reply to:</label>
                                            <div class="col-md-4">
                                                <input id="displayname" name="emailreply"
                                                       class="form-control input-md" required=""
                                                       value="<?php echo $emailreplydb; ?>" type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="emailbcc">Bcc
                                                (optional):</label>
                                            <div class="col-md-4">
                                                <input id="displayname" name="emailbcc"
                                                       class="form-control input-md"
                                                       value="<?php echo $emailbccdb; ?>" type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="emailsubject">Email
                                                Subject</label>
                                            <div class="col-md-4">
                                                <input id="displayname" name="emailsubject"
                                                       placeholder="Keyfacts Document" class="form-control input-md"
                                                       required="" value="<?php echo $emailsubjectdb; ?>"
                                                       type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="selectbasic">Type</label>
                                            <div class="col-md-4">
                                                <select id="emailtype" name="emailtype" class="form-control"
                                                        required>
                                                    <?php if (isset($emailsubjectdb)) { ?>
                                                        <option value="<?php echo $emailtype; ?>"><?php echo $emailtype; ?></option>

                                                    <?php } else { ?>
                                                        <option value="">Select...</option><?php } ?>
                                                    <option value="Customer-facing">Customer-facing (Outbound
                                                        emails)
                                                    </option>
                                                    <option value="Main Email">Main Email (Incoming emails)</option>
                                                    <option value="Catch All">Catch All (All emails)</option>
                                                    <option value="Key Facts">Key Facts</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="pop">Incoming mail server
                                                (POP)</label>
                                            <div class="col-md-4">
                                                <input id="pop" name="pop" placeholder="pop.123-reg.co.uk"
                                                       class="form-control input-md" required=""
                                                       value="<?php echo $pop; ?>" type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="popport">POP Port</label>
                                            <div class="col-md-4">
                                                <input id="popport" name="popport" placeholder=" 995"
                                                       class="form-control input-md" required=""
                                                       value="<?php echo $popport; ?>" type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="imap">Incoming mail server
                                                (IMAP)</label>
                                            <div class="col-md-4">
                                                <input id="imap" name="imap" placeholder="imap.123-reg.co.uk"
                                                       class="form-control input-md" required=""
                                                       value="<?php echo $imap; ?>" type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="imapport">IMAP Port</label>
                                            <div class="col-md-4">
                                                <input id="imapport" name="imapport" placeholder="993"
                                                       class="form-control input-md" required=""
                                                       value="<?php echo $imapport; ?>" type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="smtp">Outgoing mail server
                                                (SMTP)</label>
                                            <div class="col-md-4">
                                                <input id="smtp" name="smtp" placeholder="smtp.123-reg.co.uk"
                                                       class="form-control input-md" required=""
                                                       value="<?php echo $emailsmtpdb; ?>" type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="smtpport">SMTP Port</label>
                                            <div class="col-md-4">
                                                <input id="smtpport" name="smtpport" placeholder="465"
                                                       class="form-control input-md" required=""
                                                       value="<?php echo $emailsmtpportdb; ?>" type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="email">Email</label>
                                            <div class="col-md-4">
                                                <input id="email" name="email" placeholder="bobross@123-reg.co.uk"
                                                       class="form-control input-md" required=""
                                                       value="<?php echo $emaildb; ?>" type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="password">Password:</label>
                                            <div class="col-md-4">
                                                <input id="password" name="password"
                                                       placeholder="******************"
                                                       class="form-control input-md" required=""
                                                       value="<?php echo $passworddb; ?>" type="password">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label"></label>
                                            <div class="col-md-4">
                                                <button id="submitemailsettings" name="submitemailsettings"
                                                        class="btn btn-success">Submit
                                                </button>
                                            </div>
                                        </div>

                                    </fieldset>
                                </form>
                            </div>

                            <div id="account2" class="tab-pane fade">
                                <?php
                                $emailaccid2 = "account2";

                                $E_ACT_TWO = $pdo->prepare("select imap, imapport, popport, pop, emailtype, email, emailfrom, emailreply, emailbcc, emailsubject, smtp, smtpport, displayname, password from email_accounts where emailaccount=:emailaccid2holder");
                                $E_ACT_TWO->bindParam(':emailaccid2holder', $emailaccid2, PDO::PARAM_STR);
                                $E_ACT_TWO->execute() or die(print_r($E_ACT_TWO->errorInfo(), true));
                                $emailacc2 = $E_ACT_TWO->fetch(PDO::FETCH_ASSOC);

                                $emailfromdb2 = $emailacc2['emailfrom'];
                                $emailbccdb2 = $emailacc2['emailbcc'];
                                $emailreplydb2 = $emailacc2['emailreply'];
                                $emailsubjectdb2 = $emailacc2['emailsubject'];
                                $emailsmtpdb2 = $emailacc2['smtp'];
                                $emailsmtpportdb2 = $emailacc2['smtpport'];
                                $emaildisplaynamedb2 = $emailacc2['displayname'];
                                $passworddb2 = $emailacc2['password'];
                                $emaildb2 = $emailacc2['email'];
                                $emailtype2 = $emailacc2['emailtype'];
                                $pop2 = $emailacc2['pop'];
                                $popport2 = $emailacc2['popport'];
                                $imap2 = $emailacc2['imap'];
                                $imapport2 = $emailacc2['imapport'];
                                ?>

                                <form class="form-horizontal" method="POST" action="php/AddEmailAccounts.php?add=y">
                                    <fieldset>

                                        <legend>Email Settings (Account 2)</legend>

                                        <input type="hidden" value="account2" name="emailaccount">

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="displayname">Display
                                                Name</label>
                                            <div class="col-md-4">
                                                <input id="displayname" name="displayname"
                                                       placeholder="Company name"
                                                       value="<?php echo $emaildisplaynamedb2; ?>"
                                                       class="form-control input-md" required="" type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="emailfrom">From:</label>
                                            <div class="col-md-4">
                                                <input id="displayname" name="emailfrom"
                                                       class="form-control input-md" required=""
                                                       value="<?php echo $emailfromdb2; ?>" type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="emailreply">Reply to:</label>
                                            <div class="col-md-4">
                                                <input id="displayname" name="emailreply"
                                                       class="form-control input-md" required=""
                                                       value="<?php echo $emailreplydb2; ?>" type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="emailbcc">Bcc
                                                (optional):</label>
                                            <div class="col-md-4">
                                                <input id="displayname" name="emailbcc"
                                                       class="form-control input-md"
                                                       value="<?php echo $emailbccdb2; ?>" type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="emailsubject">Email
                                                Subject</label>
                                            <div class="col-md-4">
                                                <input id="displayname" name="emailsubject"
                                                       placeholder="Keyfacts Document" class="form-control input-md"
                                                       required="" value="<?php echo $emailsubjectdb2; ?>"
                                                       type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="selectbasic">Type</label>
                                            <div class="col-md-4">
                                                <select id="emailtype" name="emailtype" class="form-control"
                                                        required>
                                                    <?php if (isset($emailsubjectdb2)) { ?>
                                                        <option value="<?php echo $emailtype2; ?>"><?php echo $emailtype2; ?></option>

                                                    <?php } else { ?>
                                                        <option value="">Select...</option><?php } ?>
                                                    <option value="Customer-facing">Customer-facing (Outbound
                                                        emails)
                                                    </option>
                                                    <option value="Main Email">Main Email (Incoming emails)</option>
                                                    <option value="Catch All">Catch All (All emails)</option>
                                                    <option value="Key Facts">Key Facts</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="pop">Incoming mail server
                                                (POP)</label>
                                            <div class="col-md-4">
                                                <input id="pop" name="pop" placeholder="pop.123-reg.co.uk"
                                                       class="form-control input-md" required=""
                                                       value="<?php echo $pop2; ?>" type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="popport">POP Port</label>
                                            <div class="col-md-4">
                                                <input id="popport" name="popport" placeholder=" 995"
                                                       class="form-control input-md" required=""
                                                       value="<?php echo $popport2; ?>" type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="imap">Incoming mail server
                                                (IMAP)</label>
                                            <div class="col-md-4">
                                                <input id="imap" name="imap" placeholder="imap.123-reg.co.uk"
                                                       class="form-control input-md" required=""
                                                       value="<?php echo $imap2; ?>" type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="imapport">IMAP Port</label>
                                            <div class="col-md-4">
                                                <input id="imapport" name="imapport" placeholder="993"
                                                       class="form-control input-md" required=""
                                                       value="<?php echo $imapport2; ?>" type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="smtp">Outgoing mail server
                                                (SMTP)</label>
                                            <div class="col-md-4">
                                                <input id="smtp" name="smtp" placeholder="smtp.123-reg.co.uk"
                                                       class="form-control input-md" required=""
                                                       value="<?php echo $emailsmtpdb2; ?>" type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="smtpport">SMTP Port</label>
                                            <div class="col-md-4">
                                                <input id="smtpport" name="smtpport" placeholder="465"
                                                       class="form-control input-md" required=""
                                                       value="<?php echo $emailsmtpportdb2; ?>" type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="email">Email</label>
                                            <div class="col-md-4">
                                                <input id="email" name="email" placeholder="bobross@123-reg.co.uk"
                                                       class="form-control input-md" required=""
                                                       value="<?php echo $emaildb2; ?>" type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="password">Password:</label>
                                            <div class="col-md-4">
                                                <input id="password" name="password"
                                                       placeholder="******************"
                                                       class="form-control input-md" required=""
                                                       value="<?php echo $passworddb2; ?>" type="password">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label"></label>
                                            <div class="col-md-4">
                                                <button id="submitemailsettings" name="submitemailsettings"
                                                        class="btn btn-success">Submit
                                                </button>
                                            </div>
                                        </div>

                                    </fieldset>
                                </form>

                            </div>
                            <div id="account3" class="tab-pane fade">
                                <?php
                                $emailaccid3 = "account3";

                                $E_ACT_THREE = $pdo->prepare("select imap, imapport, popport, pop, emailtype, email, emailfrom, emailreply, emailbcc, emailsubject, smtp, smtpport, displayname, password from email_accounts where emailaccount=:emailaccid3holder");
                                $E_ACT_THREE->bindParam(':emailaccid3holder', $emailaccid3, PDO::PARAM_STR);
                                $E_ACT_THREE->execute() or die(print_r($E_ACT_THREE->errorInfo(), true));
                                $emailacc3 = $E_ACT_THREE->fetch(PDO::FETCH_ASSOC);

                                $emailfromdb3 = $emailacc3['emailfrom'];
                                $emailbccdb3 = $emailacc3['emailbcc'];
                                $emailreplydb3 = $emailacc3['emailreply'];
                                $emailsubjectdb3 = $emailacc3['emailsubject'];
                                $emailsmtpdb3 = $emailacc3['smtp'];
                                $emailsmtpportdb3 = $emailacc3['smtpport'];
                                $emaildisplaynamedb3 = $emailacc3['displayname'];
                                $passworddb3 = $emailacc3['password'];
                                $emaildb3 = $emailacc3['email'];
                                $emailtype3 = $emailacc3['emailtype'];
                                $pop3 = $emailacc3['pop'];
                                $popport3 = $emailacc3['popport'];
                                $imap3 = $emailacc3['imap'];
                                $imapport3 = $emailacc3['imapport'];
                                ?>
                                <form class="form-horizontal" method="POST" action="php/AddEmailAccounts.php?add=y">
                                    <fieldset>

                                        <legend>Email Settings (Account 3)</legend>

                                        <input type="hidden" value="account3" name="emailaccount">

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="displayname">Display
                                                Name</label>
                                            <div class="col-md-4">
                                                <input id="displayname" name="displayname"
                                                       placeholder="Company name"
                                                       value="<?php echo $emaildisplaynamedb3; ?>"
                                                       class="form-control input-md" required="" type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="emailfrom">From:</label>
                                            <div class="col-md-4">
                                                <input id="displayname" name="emailfrom"
                                                       class="form-control input-md" required=""
                                                       value="<?php echo $emailfromdb3; ?>" type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="emailreply">Reply to:</label>
                                            <div class="col-md-4">
                                                <input id="displayname" name="emailreply"
                                                       class="form-control input-md" required=""
                                                       value="<?php echo $emailreplydb3; ?>" type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="emailbcc">Bcc
                                                (optional):</label>
                                            <div class="col-md-4">
                                                <input id="displayname" name="emailbcc"
                                                       class="form-control input-md"
                                                       value="<?php echo $emailbccdb3; ?>" type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="emailsubject">Email
                                                Subject</label>
                                            <div class="col-md-4">
                                                <input id="displayname" name="emailsubject"
                                                       placeholder="Keyfacts Document" class="form-control input-md"
                                                       required="" value="<?php echo $emailsubjectdb3; ?>"
                                                       type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="selectbasic">Type</label>
                                            <div class="col-md-4">
                                                <select id="emailtype" name="emailtype" class="form-control"
                                                        required>
                                                    <?php if (isset($emailsubjectdb3)) { ?>
                                                        <option value="<?php echo $emailtype3; ?>"><?php echo $emailtype3; ?></option>

                                                    <?php } else { ?>
                                                        <option value="">Select...</option><?php } ?>
                                                    <option value="Customer-facing">Customer-facing (Outbound
                                                        emails)
                                                    </option>
                                                    <option value="Main Email">Main Email (Incoming emails)</option>
                                                    <option value="Catch All">Catch All (All emails)</option>
                                                    <option value="Key Facts">Key Facts</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="pop">Incoming mail server
                                                (POP)</label>
                                            <div class="col-md-4">
                                                <input id="pop" name="pop" placeholder="pop.123-reg.co.uk"
                                                       class="form-control input-md" required=""
                                                       value="<?php echo $pop3; ?>" type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="popport">POP Port</label>
                                            <div class="col-md-4">
                                                <input id="popport" name="popport" placeholder=" 995"
                                                       class="form-control input-md" required=""
                                                       value="<?php echo $popport3; ?>" type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="imap">Incoming mail server
                                                (IMAP)</label>
                                            <div class="col-md-4">
                                                <input id="imap" name="imap" placeholder="imap.123-reg.co.uk"
                                                       class="form-control input-md" required=""
                                                       value="<?php echo $imap3; ?>" type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="imapport">IMAP Port</label>
                                            <div class="col-md-4">
                                                <input id="imapport" name="imapport" placeholder="993"
                                                       class="form-control input-md" required=""
                                                       value="<?php echo $imapport3; ?>" type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="smtp">Outgoing mail server
                                                (SMTP)</label>
                                            <div class="col-md-4">
                                                <input id="smtp" name="smtp" placeholder="smtp.123-reg.co.uk"
                                                       class="form-control input-md" required=""
                                                       value="<?php echo $emailsmtpdb3; ?>" type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="smtpport">SMTP Port</label>
                                            <div class="col-md-4">
                                                <input id="smtpport" name="smtpport" placeholder="465"
                                                       class="form-control input-md" required=""
                                                       value="<?php echo $emailsmtpportdb3; ?>" type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="email">Email</label>
                                            <div class="col-md-4">
                                                <input id="email" name="email" placeholder="bobross@123-reg.co.uk"
                                                       class="form-control input-md" required=""
                                                       value="<?php echo $emaildb3; ?>" type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="password">Password:</label>
                                            <div class="col-md-4">
                                                <input id="password" name="password"
                                                       placeholder="******************"
                                                       class="form-control input-md" required=""
                                                       value="<?php echo $passworddb3; ?>" type="password">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label"></label>
                                            <div class="col-md-4">
                                                <button id="submitemailsettings" name="submitemailsettings"
                                                        class="btn btn-success">Submit
                                                </button>
                                            </div>
                                        </div>

                                    </fieldset>
                                </form>

                            </div>
                            <div id="account4" class="tab-pane fade">

                                <?php
                                $emailaccid4 = "account4";

                                $E_ACT_FOUR = $pdo->prepare("select imap, imapport, popport, pop, emailtype, email, emailfrom, emailreply, emailbcc, emailsubject, smtp, smtpport, displayname, password from email_accounts where emailaccount=:emailaccid4holder");
                                $E_ACT_FOUR->bindParam(':emailaccid4holder', $emailaccid4, PDO::PARAM_STR);
                                $E_ACT_FOUR->execute() or die(print_r($E_ACT_FOUR->errorInfo(), true));
                                $emailacc4 = $E_ACT_FOUR->fetch(PDO::FETCH_ASSOC);

                                $emailfromdb4 = $emailacc4['emailfrom'];
                                $emailbccdb4 = $emailacc4['emailbcc'];
                                $emailreplydb4 = $emailacc4['emailreply'];
                                $emailsubjectdb4 = $emailacc4['emailsubject'];
                                $emailsmtpdb4 = $emailacc4['smtp'];
                                $emailsmtpportdb4 = $emailacc4['smtpport'];
                                $emaildisplaynamedb4 = $emailacc4['displayname'];
                                $passworddb4 = $emailacc4['password'];
                                $emaildb4 = $emailacc4['email'];
                                $emailtype4 = $emailacc4['emailtype'];
                                $pop4 = $emailacc4['pop'];
                                $popport4 = $emailacc4['popport'];
                                $imap4 = $emailacc4['imap'];
                                $imapport4 = $emailacc4['imapport'];
                                ?>

                                <form class="form-horizontal" method="POST" action="php/AddEmailAccounts.php?add=y">
                                    <fieldset>

                                        <legend>Email Settings (Account 4)</legend>

                                        <input type="hidden" value="account4" name="emailaccount">

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="displayname">Display
                                                Name</label>
                                            <div class="col-md-4">
                                                <input id="displayname" name="displayname"
                                                       placeholder="Company name"
                                                       value="<?php echo $emaildisplaynamedb4; ?>"
                                                       class="form-control input-md" required="" type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="emailfrom">From:</label>
                                            <div class="col-md-4">
                                                <input id="displayname" name="emailfrom"
                                                       class="form-control input-md" required=""
                                                       value="<?php echo $emailfromdb4; ?>" type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="emailreply">Reply to:</label>
                                            <div class="col-md-4">
                                                <input id="displayname" name="emailreply"
                                                       class="form-control input-md" required=""
                                                       value="<?php echo $emailreplydb4; ?>" type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="emailbcc">Bcc
                                                (optional):</label>
                                            <div class="col-md-4">
                                                <input id="displayname" name="emailbcc"
                                                       class="form-control input-md"
                                                       value="<?php echo $emailbccdb4; ?>" type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="emailsubject">Email
                                                Subject</label>
                                            <div class="col-md-4">
                                                <input id="displayname" name="emailsubject"
                                                       placeholder="Keyfacts Document" class="form-control input-md"
                                                       required="" value="<?php echo $emailsubjectdb4; ?>"
                                                       type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="selectbasic">Type</label>
                                            <div class="col-md-4">
                                                <select id="emailtype" name="emailtype" class="form-control"
                                                        required>
                                                    <?php if (isset($emailsubjectdb3)) { ?>
                                                        <option value="<?php echo $emailtype4; ?>"><?php echo $emailtype4; ?></option>

                                                    <?php } else { ?>
                                                        <option value="">Select...</option><?php } ?>
                                                    <option value="Customer-facing">Customer-facing (Outbound
                                                        emails)
                                                    </option>
                                                    <option value="Main Email">Main Email (Incoming emails)</option>
                                                    <option value="Catch All">Catch All (All emails)</option>
                                                    <option value="Key Facts">Key Facts</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="pop">Incoming mail server
                                                (POP)</label>
                                            <div class="col-md-4">
                                                <input id="pop" name="pop" placeholder="pop.123-reg.co.uk"
                                                       class="form-control input-md" required=""
                                                       value="<?php echo $pop4; ?>" type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="popport">POP Port</label>
                                            <div class="col-md-4">
                                                <input id="popport" name="popport" placeholder=" 995"
                                                       class="form-control input-md" required=""
                                                       value="<?php echo $popport4; ?>" type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="imap">Incoming mail server
                                                (IMAP)</label>
                                            <div class="col-md-4">
                                                <input id="imap" name="imap" placeholder="imap.123-reg.co.uk"
                                                       class="form-control input-md" required=""
                                                       value="<?php echo $imap4; ?>" type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="imapport">IMAP Port</label>
                                            <div class="col-md-4">
                                                <input id="imapport" name="imapport" placeholder="993"
                                                       class="form-control input-md" required=""
                                                       value="<?php echo $imapport4; ?>" type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="smtp">Outgoing mail server
                                                (SMTP)</label>
                                            <div class="col-md-4">
                                                <input id="smtp" name="smtp" placeholder="smtp.123-reg.co.uk"
                                                       class="form-control input-md" required=""
                                                       value="<?php echo $emailsmtpdb4; ?>" type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="smtpport">SMTP Port</label>
                                            <div class="col-md-4">
                                                <input id="smtpport" name="smtpport" placeholder="465"
                                                       class="form-control input-md" required=""
                                                       value="<?php echo $emailsmtpportdb4; ?>" type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="email">Email</label>
                                            <div class="col-md-4">
                                                <input id="email" name="email" placeholder="bobross@123-reg.co.uk"
                                                       class="form-control input-md" required=""
                                                       value="<?php echo $emaildb4; ?>" type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="password">Password:</label>
                                            <div class="col-md-4">
                                                <input id="password" name="password"
                                                       placeholder="******************"
                                                       class="form-control input-md" required=""
                                                       value="<?php echo $passworddb4; ?>" type="password">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label"></label>
                                            <div class="col-md-4">
                                                <button id="submitemailsettings" name="submitemailsettings"
                                                        class="btn btn-success">Submit
                                                </button>
                                            </div>
                                        </div>

                                    </fieldset>
                                </form>

                            </div>

                            <div id="emailsig" class="tab-pane fade">

                                <form method="post" action="php/AddEmailSignature.php?emailsignature=y"
                                      class="form-horizontal">

                                    <div class="form-group">
                                        <label class="col-md-12 control-label" for="emailid"></label>
                                        <div class="col-md-4">
                                            <select id="emailid" name="emailid" class="form-control" required>
                                                <option value="">Link Signature To An Email Account</option>
                                                <?php
                                                $linkacc = $pdo->prepare("SELECT emailtype, id from email_accounts");
                                                $linkacc->execute();

                                                if ($linkacc->rowCount() > 0) {
                                                    while ($linkacvar = $linkacc->fetch(PDO::FETCH_ASSOC)) {

                                                        $lkid = $linkacvar['id'];
                                                        $lkve = $linkacvar['emailtype'];

                                                        echo "<option value='$lkid'>$lkve</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-12">
                                                <textarea class="form-control summernote" id="message"
                                                          name="message"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12 control-label" for="save"></label>
                                        <div class="col-md-4">
                                            <button type="submit" class="btn btn-success "><span
                                                        class="glyphicon glyphicon-envelope"></span> Save
                                            </button>
                                        </div>
                                    </div>


                                </form>
                                <?php
                                $query = $pdo->prepare("select sig from email_signatures");
                                $query->execute();
                                if ($query->rowCount() > 0) {
                                    while ($pullsigs = $query->fetch(PDO::FETCH_ASSOC)) {
                                        $pullsigs_SIG = html_entity_decode($pullsigs['sig']);
                                        ?>


                                        <div class="form-group">
                                            <div class="col-md-12">
                                                        <textarea class="form-control summernote" id="message"
                                                                  name="message"><?php echo $pullsigs_SIG; ?></textarea>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>


                            </div>


                        </div>


                        <?php
                    }

                    if ($AssignTasksselect == 'y') {

                        $TaskAssigned = filter_input(INPUT_GET, 'TaskAssigned', FILTER_SANITIZE_SPECIAL_CHARS);

                        if (isset($TaskAssigned)) {

                            $TaskAssignedTo = filter_input(INPUT_GET, 'TaskAssignedTo',
                                FILTER_SANITIZE_SPECIAL_CHARS);
                            $TASKUPDATED = filter_input(INPUT_GET, 'TASKUPDATED', FILTER_SANITIZE_SPECIAL_CHARS);

                            if ($TaskAssigned == 'y') {

                                echo "<br><div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa  fa-check-circle-o fa-lg\"></i> Success:</strong> Task $TASKUPDATED has been updated and assigned to $TaskAssignedTo!</div><br>";
                            }

                            if ($TaskAssigned == 'failed') {

                                print("<br><div class=\"notice notice-danger\" role=\"alert\"><strong><i class=\"fa fa-exclamation-triangle fa-lg\"></i> Error:</strong> No changes have been made!</div><br>");
                            }
                        }
                        ?>

                        <h1><i class="fa fa-tasks"></i> Assign Tasks</h1>


                        <legend>Tasks currently set</legend>

                    <?php
                    $query = $pdo->prepare("SELECT assigned, task from Set_Client_Tasks");
                    ?>

                        <table id="Already Assigned" class="table table-hover">
                            <thead>
                            <tr>
                                <th>Task</th>
                                <th>Assigned</th>
                            </tr>
                            </thead>

                            <?php
                            $query->execute();
                            if ($query->rowCount() > 0) {
                                while ($result = $query->fetch(PDO::FETCH_ASSOC)) {

                                    echo '<tr>';
                                    echo "<td>" . $result['task'] . "</td>";
                                    echo "<td>" . $result['assigned'] . "</td>";
                                    echo '</tr>';
                                }
                            } else {
                                ?>

                                <div class="notice notice-info" role="alert"><strong><i
                                                class="fa fa-exclamation-triangle fa-lg"></i> Info:</strong> No
                                    Tasks have been assigned yet!
                                </div><br>


                            <?php } ?>
                        </table>
                        <legend>Update who get assigned</legend>
                    <?php
                    $TaskArray = array("48", "7 day", "18 day", "21 day");
                    $arrlength = count($TaskArray);
                    ?>

                        <form class="form-inline" id="assinform" name="assinform"
                              action="php/AssignTask.php?AssignTasks=1" method='POST'>
                            <fieldset>

                                <div class="form-group">
                                    <label class="control-label" for="tasknames">Task</label>


                                    <select name="tasknames" id="tasknames">
                                        <?php for ($x = 0; $x < $arrlength; $x++) {
                                            ?>
                                            <option value="<?php if (isset($TaskArray)) {
                                                echo $TaskArray[$x];
                                            } ?>"><?php if (isset($TaskArray)) {
                                                    echo $TaskArray[$x];
                                                } ?></option>

                                        <?php } ?>
                                    </select>

                                    <div class="form-group">
                                        <label class="control-label" for="taskuser">Assign To</label>
                                        <select id="taskuser" name="taskuser" class="form-control">
                                            <option value="">Select Agent...</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 control-label" for="submittask"></label>
                                        <div class="col-md-4">
                                            <button class="btn btn-success"><i class="fa  fa-check-circle-o"></i>
                                            </button>
                                        </div>
                                    </div>

                            </fieldset>
                        </form>
                        <script>
                            document.querySelector('#assinform').addEventListener('submit', function (e) {
                                var form = this;
                                e.preventDefault();
                                swal({
                                        title: "Assign Task?",
                                        text: "Are you sure you want to assign this task?",
                                        type: "warning",
                                        showCancelButton: true,
                                        confirmButtonColor: '#DD6B55',
                                        confirmButtonText: 'Yes, I am sure!',
                                        cancelButtonText: "No, cancel it!",
                                        closeOnConfirm: false,
                                        closeOnCancel: false
                                    },
                                    function (isConfirm) {
                                        if (isConfirm) {
                                            swal({
                                                title: 'Assigned!',
                                                text: 'Task assigned!',
                                                type: 'success'
                                            }, function () {
                                                form.submit();
                                            });

                                        } else {
                                            swal("Cancelled", "No changes were made", "error");
                                        }
                                    });
                            });

                        </script>
                        <?php
                    }

                    if ($EWS_SELECT == 'y') {
                        $EWSAssigned = filter_input(INPUT_GET, 'EWSAssigned', FILTER_SANITIZE_SPECIAL_CHARS);

                        if (isset($EWSAssigned)) {

                            $EWSAssignedTo = filter_input(INPUT_GET, 'TaskAssignedTo',
                                FILTER_SANITIZE_SPECIAL_CHARS);
                            $EWSUPDATED = filter_input(INPUT_GET, 'EWSUPDATED', FILTER_SANITIZE_SPECIAL_CHARS);

                            if ($EWSAssigned == 'y') {

                                echo "<br><div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa  fa-check-circle-o fa-lg\"></i> Success:</strong> EWS status $EWSUPDATED has been updated and assigned to $EWSAssignedTo!</div><br>";
                            }

                            if ($EWSAssigned == 'failed') {

                                print("<br><div class=\"notice notice-danger\" role=\"alert\"><strong><i class=\"fa fa-exclamation-triangle fa-lg\"></i> Error:</strong> No changes have been made!</div><br>");
                            }
                        }
                        ?>

                        <h1><i class="fa fa-tasks"></i> Assign EWS statuses</h1>


                        <legend>EWS currently set</legend>

                    <?php
                    $query = $pdo->prepare("SELECT assigned, status from assign_ews_status");
                    ?>

                        <table id="Already Assigned" class="table table-hover">
                            <thead>
                            <tr>
                                <th>Status</th>
                                <th>Assigned</th>
                            </tr>
                            </thead>

                            <?php
                            $query->execute();
                            if ($query->rowCount() > 0) {
                                while ($result = $query->fetch(PDO::FETCH_ASSOC)) {

                                    echo '<tr>';
                                    echo "<td>" . $result['status'] . "</td>";
                                    echo "<td>" . $result['assigned'] . "</td>";
                                    echo '</tr>';
                                }
                            } else {
                                ?>

                                <div class="notice notice-info" role="alert"><strong><i
                                                class="fa fa-exclamation-triangle fa-lg"></i> Info:</strong> No
                                    EWS warning have been assigned yet!
                                </div><br>


                            <?php } ?>
                        </table>
                        <legend>Update who get assigned</legend>
                    <?php
                    $EWSArray = array(
                        "BOUNCED DD",
                        "CANCELLED",
                        "CANCELLED DD",
                        "CFO",
                        "LAPSED",
                        "RE-INSTATED",
                        "REDRAWN",
                        "WILL CANCEL",
                        "WILL REDRAW"
                    );
                    $arrlength = count($EWSArray);
                    $LVL_8_COUNT = count($Level_8_Access);
                    ?>

                        <form class="form-inline" id="EWSFORM" name="EWSFORM" action="php/AssignEWS.php?EXECUTE=1"
                              method='POST'>
                            <fieldset>

                                <div class="form-group">
                                    <label class="control-label" for="EWS_STATUS">EWS Status</label>
                                    <select name="EWS_STATUS" id="EWS_STATUS">
                                        <?php for ($x = 0; $x < $arrlength; $x++) {
                                            ?>
                                            <option value="<?php if (isset($EWSArray)) {
                                                echo $EWSArray[$x];
                                            } ?>"><?php if (isset($EWSArray)) {
                                                    echo $EWSArray[$x];
                                                } ?></option>

                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="control-label" for="EWS_USER">EWS User</label>
                                    <select name="EWS_USER" id="EWS_USER">
                                        <?php for ($x = 0; $x < $LVL_8_COUNT; $x++) {
                                            ?>
                                            <option value="<?php if (isset($Level_8_Access)) {
                                                echo $Level_8_Access[$x];
                                            } ?>"><?php if (isset($Level_8_Access)) {
                                                    echo $Level_8_Access[$x];
                                                } ?></option>

                                        <?php } ?>
                                    </select>
                                </div>


                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="submittask"></label>
                                    <div class="col-md-4">
                                        <button class="btn btn-success"><i class="fa  fa-check-circle-o"></i>
                                        </button>
                                    </div>
                                </div>

                            </fieldset>
                        </form>
                        <script>
                            document.querySelector('#EWSFORM').addEventListener('submit', function (e) {
                                var form = this;
                                e.preventDefault();
                                swal({
                                        title: "Assign EWS?",
                                        text: "Are you sure you want to assign EWS to this user?",
                                        type: "warning",
                                        showCancelButton: true,
                                        confirmButtonColor: '#DD6B55',
                                        confirmButtonText: 'Yes, I am sure!',
                                        cancelButtonText: "No, cancel it!",
                                        closeOnConfirm: false,
                                        closeOnCancel: false
                                    },
                                    function (isConfirm) {
                                        if (isConfirm) {
                                            swal({
                                                title: 'Assigned!',
                                                text: 'EWS assigned!',
                                                type: 'success'
                                            }, function () {
                                                form.submit();
                                            });

                                        } else {
                                            swal("Cancelled", "No changes were made", "error");
                                        }
                                    });
                            });

                        </script>
                        <?php
                    }

                    if ($usersselect == 'y') {
                        ?>

                        <h1><i class="fa fa-user"></i> User configuration</h1>

                        <?php
                        $adduser = filter_input(INPUT_GET, 'adduser', FILTER_SANITIZE_NUMBER_INT);

                        if (isset($adduser)) {
                            $user = filter_input(INPUT_GET, 'user', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                            if ($adduser == '1') {

                                echo "<div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa fa-check\"></i> Success:</strong> User account for $user has been created!</div>";
                            }

                            if ($adduser == '0') {

                                echo "<div class=\"notice notice-danger\" role=\"alert\"><strong><i class=\"fa-exclamation-triangle fa-lg\"></i> Warning:</strong> User account not created. The following was not matched! $message</div>";
                            }
                            if ($adduser == '2') {

                                echo "<div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa fa-check\"></i> Success:</strong> User account for $user has been updated!</div>";
                            }
                        }
                        ?>

                        <ul class="nav nav-pills">
                            <li class="active"><a data-toggle="pill" href="#usertracking">User activity</a></li>
                            <li><a data-toggle="pill" href="#trackinghistory">Tracking History</a></li>
                            <li><a data-toggle="pill" href="#adduser">Add user</a></li>
                            <li><a data-toggle="pill" href="#modifyuser">Modify Users</a></li>
                        </ul>
                        <br>

                        <div class="tab-content">

                            <div id="usertracking" class="tab-pane fade in active">

                                <?php

                                $USR_TRKN = $pdo->prepare("SELECT user_tracking_id FROM user_tracking");
                                $USR_TRKN->execute();
                                if ($USR_TRKN->rowCount() > 0) {

                                    require_once(__DIR__ . '/models/users/UserTrackingModel.php');
                                    $UserTracking = new UserTrackingModal($pdo);
                                    $UserTrackingList = $UserTracking->getUserTracking();
                                    require_once(__DIR__ . '/views/users/UserTracking.php');
                                }

                                ?>


                            </div>

                            <div id="trackinghistory" class="tab-pane fade in">


                                <?php

                                $TRACKING_USER = filter_input(INPUT_POST, 'TRACKING_USER',
                                    FILTER_SANITIZE_SPECIAL_CHARS);
                                $TRACKING_DATE = filter_input(INPUT_POST, 'TRACKING_DATE',
                                    FILTER_SANITIZE_SPECIAL_CHARS);
                                $TRACKING_DATETO = filter_input(INPUT_POST, 'TRACKING_DATETO',
                                    FILTER_SANITIZE_SPECIAL_CHARS);

                                ?>

                                <div class="col-xs-12">

                                    <form method="POST" action="?users=y" class="form-horizontal">
                                        <fieldset>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="TRACKING_DATE">Date
                                                    from:</label>
                                                <div class="col-md-4">
                                                    <input class="form-control" type="text" name="TRACKING_DATE"
                                                           id="TRACKING_DATE" required
                                                           value="<?php if (isset($TRACKING_DATE)) {
                                                               echo $TRACKING_DATE;
                                                           } ?>">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="TRACKING_DATE">Date
                                                    to:</label>
                                                <div class="col-md-4">
                                                    <input class="form-control" type="text" name="TRACKING_DATETO"
                                                           id="TRACKING_DATETO" required
                                                           value="<?php if (isset($TRACKING_DATETO)) {
                                                               echo $TRACKING_DATETO;
                                                           } ?>">
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="TRACKING_USER">Select
                                                    user</label>
                                                <div class="col-md-4">
                                                    <select id="taskuser" name="TRACKING_USER" class="form-control"
                                                            onchange="this.form.submit()">
                                                        <option value="">Select user...</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </fieldset>


                                    </form>
                                </div>

                                <br><br><br><br><br><br>
                                <br><br><br> <?php

                                if (isset($TRACKING_USER) && $TRACKING_DATE) {

                                    $HIS_TRKN = $pdo->prepare("SELECT tracking_history_id FROM tracking_history");
                                    $HIS_TRKN->execute();
                                    if ($HIS_TRKN->rowCount() > 0) { ?>

                                        <div class='panel panel-primary'>
                                            <div class='panel-heading'><?php if (isset($TRACKING_USER)) {
                                                    echo $TRACKING_USER;
                                                } ?> Tracking Summary
                                            </div>
                                            <div class='panel-body'>


                                                <?php

                                                require_once(__DIR__ . '/models/users/HistoryOverviewModel.php');
                                                $HistoryOverview = new HistoryOverviewModal($pdo);
                                                $HistoryOverviewList = $HistoryOverview->getHistoryOverview($TRACKING_USER,
                                                    $TRACKING_DATE, $TRACKING_DATETO);
                                                require_once(__DIR__ . '/views/users/HistoryOverview.php');

                                                require_once(__DIR__ . '/models/users/HistoryTrackingModel.php');
                                                $HistoryTracking = new HistoryTrackingModal($pdo);
                                                $HistoryTrackingList = $HistoryTracking->getHistoryTracking($TRACKING_USER,
                                                    $TRACKING_DATE, $TRACKING_DATETO);
                                                require_once(__DIR__ . '/views/users/HistoryTracking.php');

                                                ?>

                                            </div>
                                        </div>

                                        <?php

                                    }

                                } else { ?>

                                    <div class='panel panel-primary'>
                                        <div class='panel-heading'>Tracking Summary</div>
                                        <div class='panel-body'> <?php

                                            require_once(__DIR__ . '/models/users/AllOverviewModel.php');
                                            $AllOverviev = new AllOvervievModal($pdo);
                                            $AllOvervievList = $AllOverviev->getAllOverviev();
                                            require_once(__DIR__ . '/views/users/AllOverview.php');

                                            ?>

                                        </div>
                                    </div> <?php

                                }


                                ?>


                            </div>


                            <div id="adduser" class="tab-pane fade in">
                                <form class="form-horizontal" name="form1" method="post"
                                      action="php/AddNewUser.php?adduser=1" autocomplete="off">
                                    <fieldset>
                                        <legend>Add new user</legend>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="login">Login:</label>
                                            <div class="col-md-4">
                                                <input class="form-control" style="width: 170px" type="text"
                                                       name="login" placeholder="(min, 4 chars)" required>
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="password">Company:</label>
                                            <div class="col-md-4">
                                                <select class="form-control" name='COMPANY_ENTITY'>
                                                    <option value='Project X'>Project X</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="password">Password:</label>
                                            <div class="col-md-4">
                                                <input class="form-control" style="width: 170px" type="password"
                                                       name="password" placeholder="(min. 4 chars)" required>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="confirm">Confirm
                                                password:</label>
                                            <div class="col-md-4">
                                                <input class="form-control" style="width: 170px" type="password"
                                                       name="confirm" required>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="name">Real name:</label>
                                            <div class="col-md-4">
                                                <input class="form-control" style="width: 170px" type="text"
                                                       name="name" required>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="email">E-mail:</label>
                                            <div class="col-md-4">
                                                <input class="form-control" style="width: 170px" type="text"
                                                       name="email" required>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="info">Role:</label>
                                            <div class="col-md-4">
                                                <input class="form-control" style="width: 170px" type="text"
                                                       name="info" required>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="singlebutton"></label>
                                            <div class="col-md-4">
                                                <button id="singlebutton" type="submit" name="UserSubmit"
                                                        value="Submit" class="btn btn-primary "><span
                                                            class="glyphicon glyphicon-plus"></span> Add User
                                                </button>
                                            </div>
                                        </div>
                                    </fieldset>
                                </form>


                            </div>

                            <div id="modifyuser" class="tab-pane fade">

                                <?php

                                $USR_CHECK = $pdo->prepare("SELECT id FROM users");
                                $USR_CHECK->execute();
                                if ($USR_CHECK->rowCount() > 0) {

                                    require_once(__DIR__ . '/models/users/UserModel.php');
                                    $User = new UserModal($pdo);
                                    $UserList = $User->getUser();
                                    require_once(__DIR__ . '/views/users/User.php');
                                }

                                ?>

                            </div>


                        </div>

                    <?php }

                    if ($adminselect == 'y') {
                        ?>

                        <h1><i class="fa fa-cog"></i> Admin Dashboard</h1>
                        <p>This template has a responsive menu toggling system. The menu will appear collapsed on
                            smaller screens, and will appear non-collapsed on larger screens. When toggled using the
                            button below, the menu will appear/disappear. On small screens, the page content will be
                            pushed off canvas.</p>
                        <p>Make sure to keep all page content within the <code>#page-content-wrapper</code>.</p>

                    <?php }

                    if ($vicidialselect == 'y') {
                        ?>

                        <h1><i class="fa fa-headphones"></i> Vicidial Integration</h1>

                        <?php
                        $vicidialaccount = filter_input(INPUT_GET, 'vicidialaccount',
                            FILTER_SANITIZE_SPECIAL_CHARS);

                        if (isset($vicidialaccount)) {

                            $vicidialaccount = filter_input(INPUT_GET, 'vicidialaccount',
                                FILTER_SANITIZE_SPECIAL_CHARS);

                            if ($vicidialaccount == 'database') {

                                print("<br><div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa  fa-check-circle-o fa-lg\"></i> Success:</strong> Dialler settings for the database server have been updated!</div><br>");
                            }

                            if ($vicidialaccount == 'telephony') {

                                print("<br><div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa  fa-check-circle-o fa-lg\"></i> Success:</strong> Dialler settings for the telephony server have been updated!</div><br>");
                            }
                            if ($vicidialaccount == 'failed') {

                                print("<br><div class=\"notice notice-danger\" role=\"alert\"><strong><i class=\"fa fa-exclamation-triangle fa-lg\"></i> Error:</strong> No changes have been made!</div><br>");
                            }
                        }
                        ?>

                        <p>To pull information from your Vicidial system you will need to enter some settings.</p>

                        <ul class="nav nav-pills">
                            <li class="active"><a data-toggle="pill" href="#"><i class="fa fa-database"></i></a>
                            </li>
                            <li><a data-toggle="pill" href="#dbserverset">Database Server</a></li>
                            <li><a data-toggle="pill" href="#telserverset">Telephony Server</a></li>
                        </ul>
                        <br>

                        <div class="tab-content">

                            <div id="dbserverset" class="tab-pane fade">

                                <?php
                                $servertype = "Database";

                                $query = $pdo->prepare("SELECT url, username, password, sqlpass, sqluser FROM vicidial_accounts where servertype=:typeholder");

                                $query->bindParam(':typeholder', $servertype, PDO::PARAM_STR, 500);
                                $query->execute() or die(print_r($query->errorInfo(), true));
                                $dataacc = $query->fetch(PDO::FETCH_ASSOC);

                                $dataacurl = $dataacc['url'];
                                $dataacusername = $dataacc['username'];
                                $dataacpassword = $dataacc['password'];
                                $dataacsqlpass = $dataacc['sqlpass'];
                                $dataacuser = $dataacc['sqluser'];
                                ?>

                                <form class="form-horizontal" method="POST"
                                      action="php/AddDiallerSettings.php?db=y">
                                    <fieldset>

                                        <legend>Database server</legend>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="dbserverurl">URL/FQDN</label>
                                            <div class="col-md-4">
                                                <input id="dbserverurl" name="dbserverurl"
                                                       placeholder="dial132.bluetelecoms.com"
                                                       value="<?php echo $dataacurl; ?>"
                                                       class="form-control input-md" required="" type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="dbserveruser">Dialer
                                                username</label>
                                            <div class="col-md-4">
                                                <input id="dbserveruser" name="dbserveruser" placeholder="9999"
                                                       class="form-control input-md"
                                                       value="<?php echo $dataacusername; ?>" required=""
                                                       type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="dbserverpass">Dialer
                                                password</label>
                                            <div class="col-md-4">
                                                <input id="dbserverpass" name="dbserverpass"
                                                       placeholder="***********" class="form-control input-md"
                                                       value="<?php echo $dataacpassword; ?>" required=""
                                                       type="password">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="dbsqluser">SQL user</label>
                                            <div class="col-md-4">
                                                <input id="dbsqluser" name="dbsqluser"
                                                       placeholder="Only if the default has been changed"
                                                       class="form-control input-md"
                                                       value="<?php echo $dataacuser; ?>" type="text">
                                                <span class="help-block">(optional)</span>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="dbsqlpass">SQL pass</label>
                                            <div class="col-md-4">
                                                <input id="dbsqlpass" name="dbsqlpass"
                                                       placeholder="Only if the default has been changed"
                                                       class="form-control input-md"
                                                       value="<?php echo $dataacsqlpass; ?>" type="password">
                                                <span class="help-block">(optional)</span>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="dbserversubmit"></label>
                                            <div class="col-md-4">
                                                <button id="dbserversubmit" name="dbserversubmit"
                                                        class="btn btn-success">Submit
                                                </button>
                                            </div>
                                        </div>

                                    </fieldset>
                                </form>


                            </div>


                            <div id="telserverset" class="tab-pane fade">

                                <?php
                                $servertype2 = "Telephony";

                                $query = $pdo->prepare("SELECT url, username, password FROM vicidial_accounts where servertype=:typeholder");

                                $query->bindParam(':typeholder', $servertype2, PDO::PARAM_STR, 500);
                                $query->execute() or die(print_r($query->errorInfo(), true));
                                $telacc = $query->fetch(PDO::FETCH_ASSOC);

                                $telaccurl = $telacc['url'];
                                $telaccusername = $telacc['username'];
                                $telaccpassword = $telacc['password'];
                                ?>


                                <form class="form-horizontal" method="POST"
                                      action="php/AddDiallerSettings.php?tel=y">
                                    <fieldset>

                                        <legend>Telephony server</legend>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label"
                                                   for="telserverurl">URL/FQDN</label>
                                            <div class="col-md-4">
                                                <input id="telserverurl" name="telserverurl"
                                                       placeholder="dial132.bluetelecoms.com"
                                                       value="<?php echo $telaccurl; ?>"
                                                       class="form-control input-md" required="" type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="telserveruser">Dialer
                                                username</label>
                                            <div class="col-md-4">
                                                <input id="telserveruser" name="telserveruser" placeholder="9999"
                                                       class="form-control input-md"
                                                       value="<?php echo $telaccusername; ?>" required=""
                                                       type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="telserverpass">Dialer
                                                password</label>
                                            <div class="col-md-4">
                                                <input id="telserverpass" name="telserverpass"
                                                       placeholder="***********" class="form-control input-md"
                                                       value="<?php echo $telaccpassword; ?>" required=""
                                                       type="password">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="telserversubmit"></label>
                                            <div class="col-md-4">
                                                <button id="telserversubmit" name="telserversubmit"
                                                        class="btn btn-success">Submit
                                                </button>
                                            </div>
                                        </div>

                                    </fieldset>
                                </form>


                            </div>

                        </div>

                    <?php }

                    if ($providerselect == 'y') {
                        ?>
                        <h1><i class="fa fa-bank"></i> Provider List</h1>

                        <?php
                        $RETURN = filter_input(INPUT_GET, 'RETURN', FILTER_SANITIZE_SPECIAL_CHARS);

                        if (isset($RETURN)) {
                            if ($RETURN == 'UPDATED') {

                                print("<br><div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa  fa-check-circle-o fa-lg\"></i> Success:</strong> Company details have been updated!</div><br>");
                            }

                            if ($RETURN == 'FAIL') {

                                print("<br><div class=\"notice notice-danger\" role=\"alert\"><strong><i class=\"fa fa-exclamation-triangle fa-lg\"></i> Error:</strong> No changes have been made!</div><br>");
                            }
                        }
                        ?>

                        <h1>Add new or update insurance providers</h1><br>

                        <ul class="nav nav-pills">
                            <li class="active"><a data-toggle="pill" href="#provider"><i
                                            class="fa fa-align-justify"></i> List</a></li>
                        </ul>
                        <br>
                        <div class="tab-content">
                            <div id="providersettings" class="tab-pane fade in active">

                                <form method="post" action="php/AddCompany.php?EXECUTE=1">
                                    <table id="providersettings" class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th>Company</th>
                                            <th>Percent</th>
                                            <th>Active</th>
                                        </tr>
                                        </thead>

                                        <td><input size="12" class="form-control" type="text" name="PRO_COMPANY"
                                                   placeholder="Add a new company" required></td>
                                        <td><input size="12" class="form-control" type="text" name="PRO_PERCENT"
                                                   placeholder="Percentage taken by company for financial calculations">
                                        </td>
                                        <td>
                                            <select name="PRO_ACTIVE" class="form-control" required>
                                                <option value=" ">Make active to show when adding clients/policies
                                                </option>
                                                <option value="0">Disabled</option>
                                                <option value="1">Active</option>

                                            </select>
                                        </td>
                                        <td>
                                            <button type="submit" class="btn btn-success btn-sm"><i
                                                        class="fa fa-save"></i> ADD
                                            </button>
                                        </td>
                                    </table>
                                </form>


                                <?php
                                $PRO_QRY = $pdo->prepare("SELECT insurance_company_id, insurance_company_name, insurance_company_percent, insurance_company_active FROM insurance_company");
                                $PRO_QRY->execute() or die(print_r($query->errorInfo(), true));
                                ?>


                                <table id="providersettings" class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th>Company</th>
                                        <th>Percent</th>
                                        <th>Active</th>
                                    </tr>
                                    </thead>

                                    <?php
                                    while ($result = $PRO_QRY->fetch(PDO::FETCH_ASSOC)) {

                                        $PRO_ID = $result['insurance_company_id'];
                                        $PRO_COMPANY = $result['insurance_company_name'];
                                        $PRO_PERCENT = $result['insurance_company_percent'];
                                        $PRO_ACTIVE = $result['insurance_company_active'];
                                        ?>
                                        <form method="post" action="php/AddCompany.php?EXECUTE=1">
                                            <tr><input type="hidden" value="<?php echo $PRO_ID; ?>"
                                                       name="PRO_ID">
                                                <td><input size="12" class="form-control" type="text"
                                                           name="PRO_COMPANY"
                                                           value="<?php if (isset($PRO_COMPANY)) {
                                                               echo $PRO_COMPANY;
                                                           } ?>" required></td>
                                                <td><input size="12" class="form-control" type="text"
                                                           name="PRO_PERCENT"
                                                           value="<?php if (isset($PRO_PERCENT)) {
                                                               echo $PRO_PERCENT;
                                                           } ?>"></td>
                                                <td>
                                                    <select name="PRO_ACTIVE" class="form-control" required>
                                                        <option <?php if (isset($PRO_ACTIVE)) {
                                                            if ($PRO_ACTIVE == '0') {
                                                                echo "selected";
                                                            }
                                                        } ?> value="0">Disabled
                                                        </option>
                                                        <option <?php if (isset($PRO_ACTIVE)) {
                                                            if ($PRO_ACTIVE == '1') {
                                                                echo "selected";
                                                            }
                                                        } ?> value="1">Active
                                                        </option>

                                                    </select>
                                                </td>
                                                <td>
                                                    <button type="submit" class="btn btn-warning btn-sm"><i
                                                                class="fa fa-save"></i> UPDATE
                                                    </button>
                                                </td>
                                            </tr>
                                        </form>
                                    <?php } ?>
                                </table>

                            </div>
                        </div>

                    <?php }

                    if ($connexselect == 'y') {
                        ?>

                        <h1><i class="fa fa-headphones"></i> Connex Integration</h1>

                        <?php
                        $connexaccount = filter_input(INPUT_GET, 'connexaccount', FILTER_SANITIZE_SPECIAL_CHARS);

                        if (isset($connexaccount)) {

                            $connexaccount = filter_input(INPUT_GET, 'connexaccount',
                                FILTER_SANITIZE_SPECIAL_CHARS);

                            if ($connexaccount == 'database') {

                                print("<br><div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa  fa-check-circle-o fa-lg\"></i> Success:</strong> Dialler settings have been updated!</div><br>");
                            }

                            if ($connexaccount == 'failed') {

                                print("<br><div class=\"notice notice-danger\" role=\"alert\"><strong><i class=\"fa fa-exclamation-triangle fa-lg\"></i> Error:</strong> No changes have been made!</div><br>");
                            }
                        }
                        ?>

                        <p>To pull information from your Connex system you will need to enter some settings.</p>

                        <ul class="nav nav-pills">
                            <li class="active"><a data-toggle="pill" href="connexsettings"><i
                                            class="fa fa-database"></i> Settings</a></li>

                        </ul>
                        <br>

                        <div class="tab-content">

                            <div id="connexsettings" class="tab-pane fade in active">

                                <?php
                                $servertype = "Web";

                                $query = $pdo->prepare("SELECT url, username, password FROM connex_accounts where servertype=:typeholder");

                                $query->bindParam(':typeholder', $servertype, PDO::PARAM_STR, 500);
                                $query->execute() or die(print_r($query->errorInfo(), true));
                                $dataacc = $query->fetch(PDO::FETCH_ASSOC);

                                $dataacurl = $dataacc['url'];
                                $dataacusername = $dataacc['username'];
                                $dataacpassword = $dataacc['password'];
                                ?>

                                <form class="form-horizontal" method="POST"
                                      action="php/AddDiallerSettings.php?data=y">
                                    <fieldset>

                                        <legend>Connex Server</legend>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="dbserverurl">URL/FQDN</label>
                                            <div class="col-md-4">
                                                <input id="dbserverurl" name="dbserverurl"
                                                       placeholder="dial132.bluetelecoms.com"
                                                       value="<?php echo $dataacurl; ?>"
                                                       class="form-control input-md" required="" type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="dbserveruser">Dialer
                                                username</label>
                                            <div class="col-md-4">
                                                <input id="dbserveruser" name="dbserveruser" placeholder="9999"
                                                       class="form-control input-md"
                                                       value="<?php echo $dataacusername; ?>" required=""
                                                       type="text">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="dbserverpass">Dialer
                                                password</label>
                                            <div class="col-md-4">
                                                <input id="dbserverpass" name="dbserverpass"
                                                       placeholder="***********" class="form-control input-md"
                                                       value="<?php echo $dataacpassword; ?>" required=""
                                                       type="password">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="dbserversubmit"></label>
                                            <div class="col-md-4">
                                                <button id="dbserversubmit" name="dbserversubmit"
                                                        class="btn btn-success">Submit
                                                </button>
                                            </div>
                                        </div>

                                    </fieldset>
                                </form>
                            </div>
                        </div>

                    <?php }
                    if ($settingsselect == 'y') {
                        ?>

                        <h1><i class="fa fa-desktop"></i> CRM Features</h1>
                        <?php
                        $featuresupdated = filter_input(INPUT_GET, 'featuresupdated',
                            FILTER_SANITIZE_SPECIAL_CHARS);

                        if (isset($featuresupdated)) {

                            if ($featuresupdated == 'ydatabase') {

                                print("<br><div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa  fa-check-circle-o fa-lg\"></i> Success:</strong> CRM features updated!</div><br>");
                            }
                            if ($featuresupdated == 'failed') {

                                print("<br><div class=\"notice notice-danger\" role=\"alert\"><strong><i class=\"fa fa-exclamation-triangle fa-lg\"></i> Error:</strong> No changes have been made!</div><br>");
                            }
                        }
                        ?>

                        <p>Enable or disable CRM features</p>
                        <br>
                        <?php
                        $query = $pdo->prepare("SELECT clientLetters, compliance, financials, ews, trackers, employee, dealsheets, post_code, pba, error, twitter, gmaps, analytics, callbacks, dialler, intemails, clientemails, keyfactsemail, genemail, recemail, sms, calendar, audits, life, home, pension FROM adl_features LIMIT 1");
                        $query->execute() or die(print_r($query->errorInfo(), true));
                        $queryfeatures = $query->fetch(PDO::FETCH_ASSOC);

                        $fcallbacks = $queryfeatures['callbacks'];
                        $fdialler = $queryfeatures['dialler'];
                        $fintemails = $queryfeatures['intemails'];
                        $fclientemails = $queryfeatures['clientemails'];
                        $fkeyfactsemail = $queryfeatures['keyfactsemail'];
                        $fgenemail = $queryfeatures['genemail'];
                        $frecemail = $queryfeatures['recemail'];
                        $fsms = $queryfeatures['sms'];
                        $fcalendar = $queryfeatures['calendar'];
                        $faudits = $queryfeatures['audits'];
                        $flife = $queryfeatures['life'];
                        $fhome = $queryfeatures['home'];
                        $fpensions = $queryfeatures['pension'];
                        $fanalytics = $queryfeatures['analytics'];
                        $ftwitter = $queryfeatures['twitter'];
                        $fgmaps = $queryfeatures['gmaps'];
                        $ferror = $queryfeatures['error'];
                        $fpba = $queryfeatures['pba'];
                        $fpost_code = $queryfeatures['post_code'];
                        $fdealsheets = $queryfeatures['dealsheets'];
                        $femployee = $queryfeatures['employee'];
                        $ftracker = $queryfeatures['trackers'];
                        $fews = $queryfeatures['ews'];
                        $ffinancials = $queryfeatures['financials'];
                        $fcompliance = $queryfeatures['compliance'];
                        $fClientLetters = $queryfeatures['clientLetters'];

                        ?>


                        <form class="form-horizontal" method="POST" action="php/AddFeatures.php?add=y">
                            <fieldset>

                                <legend>Features</legend>

                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="featuredialler">Dialler
                                        Integration</label>
                                    <div class="col-md-4">
                                        <label class="radio-inline" for="featuredialler-0">
                                            <input name="featuredialler" id="featuredialler-0"
                                                   value="0" <?php if (!isset($fdialler)) {
                                                echo 'checked="checked"';
                                            } elseif ($fdialler == '0') {
                                                echo 'checked="checked"';
                                            } ?> type="radio">
                                            0
                                        </label>
                                        <label class="radio-inline" for="featuredialler-1">
                                            <input name="featuredialler" id="featuredialler-1"
                                                   value="1" <?php if ($fdialler == '1') {
                                                echo 'checked="checked"';
                                            } ?> type="radio">
                                            1
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="featureanalytics">Google
                                        Analytics</label>
                                    <div class="col-md-4">
                                        <label class="radio-inline" for="featureanalytics-0">
                                            <input name="featureanalytics" id="featureanalytics-0"
                                                   value="0" <?php if (!isset($fanalytics)) {
                                                echo 'checked="checked"';
                                            } elseif ($fanalytics == '0') {
                                                echo 'checked="checked"';
                                            } ?> type="radio">
                                            0
                                        </label>
                                        <label class="radio-inline" for="fanalytics-1">
                                            <input name="featureanalytics" id="featureanalytics-1"
                                                   value="1" <?php if ($fanalytics == '1') {
                                                echo 'checked="checked"';
                                            } ?> type="radio">
                                            1
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="featureClientLetters">Client
                                        Letters</label>
                                    <div class="col-md-4">
                                        <label class="radio-inline" for="featureClientLetters-0">
                                            <input name="featureClientLetters" id="featureanalytics-0"
                                                   value="0" <?php if (!isset($fClientLetters)) {
                                                echo 'checked="checked"';
                                            } elseif ($fClientLetters == '0') {
                                                echo 'checked="checked"';
                                            } ?> type="radio">
                                            0
                                        </label>
                                        <label class="radio-inline" for="featureClientLetters-1">
                                            <input name="featureClientLetters" id="featureClientLetters-1"
                                                   value="1" <?php if ($fClientLetters == '1') {
                                                echo 'checked="checked"';
                                            } ?> type="radio">
                                            1
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="featuregmaps">Google Maps</label>
                                    <div class="col-md-4">
                                        <label class="radio-inline" for="featuregmaps-0">
                                            <input name="featuregmaps" id="featuregmaps-0"
                                                   value="0" <?php if (!isset($fgmaps)) {
                                                echo 'checked="checked"';
                                            } elseif ($fgmaps == '0') {
                                                echo 'checked="checked"';
                                            } ?> type="radio">
                                            0
                                        </label>
                                        <label class="radio-inline" for="featuregmaps-1">
                                            <input name="featuregmaps" id="featuregmaps-1"
                                                   value="1" <?php if ($fgmaps == '1') {
                                                echo 'checked="checked"';
                                            } ?> type="radio">
                                            1
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="featuretwitter">Twitter</label>
                                    <div class="col-md-4">
                                        <label class="radio-inline" for="featuretwitter-0">
                                            <input name="featuretwitter" id="featuretwitter-0"
                                                   value="0" <?php if (!isset($ftwitter)) {
                                                echo 'checked="checked"';
                                            } elseif ($ftwitter == '0') {
                                                echo 'checked="checked"';
                                            } ?> type="radio">
                                            0
                                        </label>
                                        <label class="radio-inline" for="featuregmaps-1">
                                            <input name="featuretwitter" id="featuretwitter-1"
                                                   value="1" <?php if ($ftwitter == '1') {
                                                echo 'checked="checked"';
                                            } ?> type="radio">
                                            1
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="featurecallbacks">Callbacks</label>
                                    <div class="col-md-4">
                                        <label class="radio-inline" for="callbacks-0">
                                            <input name="featurecallbacks" id="featurecallbacks-0"
                                                   value="0" <?php if (!isset($fcallbacks)) {
                                                echo 'checked="checked"';
                                            } elseif ($fcallbacks == '0') {
                                                echo 'checked="checked"';
                                            } ?> type="radio">
                                            0
                                        </label>
                                        <label class="radio-inline" for="featurecallbacks-1">
                                            <input name="featurecallbacks" id="featurecallbacks-1"
                                                   value="1" <?php if ($fcallbacks == '1') {
                                                echo 'checked="checked"';
                                            } ?> type="radio">
                                            1
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="featurepost_code">Post Code
                                        Lookups</label>
                                    <div class="col-md-4">
                                        <label class="radio-inline" for="callbacks-0">
                                            <input name="featurepost_code" id="featurepost_code-0"
                                                   value="0" <?php if (!isset($fpost_code)) {
                                                echo 'checked="checked"';
                                            } elseif ($fpost_code == '0') {
                                                echo 'checked="checked"';
                                            } ?> type="radio">
                                            0
                                        </label>
                                        <label class="radio-inline" for="featurepost_code-1">
                                            <input name="featurepost_code" id="featurepost_code-1"
                                                   value="1" <?php if ($fpost_code == '1') {
                                                echo 'checked="checked"';
                                            } ?> type="radio">
                                            1
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="featuresintemail">Internal
                                        Emails</label>
                                    <div class="col-md-4">
                                        <label class="radio-inline" for="featuresintemail-0">
                                            <input name="featuresintemail" id="featuresintemail-0"
                                                   value="0" <?php if (!isset($fintemails)) {
                                                echo 'checked="checked"';
                                            } elseif ($fintemails == '0') {
                                                echo 'checked="checked"';
                                            } ?> type="radio">
                                            0
                                        </label>
                                        <label class="radio-inline" for="featuresintemail-1">
                                            <input name="featuresintemail" id="featuresintemail-1"
                                                   value="1" <?php if ($fintemails == '1') {
                                                echo 'checked="checked"';
                                            } ?> type="radio">
                                            1
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="featureclientemails">Client
                                        Emails</label>
                                    <div class="col-md-4">
                                        <label class="radio-inline" for="featureclientemails-0">
                                            <input name="featureclientemails" id="featureclientemails-0"
                                                   value="0" <?php if (!isset($fclientemails)) {
                                                echo 'checked="checked"';
                                            } elseif ($fclientemails == '0') {
                                                echo 'checked="checked"';
                                            } ?> type="radio">
                                            0
                                        </label>
                                        <label class="radio-inline" for="featureclientemails-1">
                                            <input name="featureclientemails" id="featureclientemails-1"
                                                   value="1" <?php if ($fclientemails == '1') {
                                                echo 'checked="checked"';
                                            } ?> type="radio">
                                            1
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="featurekeyfacts">Keyfacts
                                        Email</label>
                                    <div class="col-md-4">
                                        <label class="radio-inline" for="featurekeyfacts-0">
                                            <input name="featurekeyfacts" id="featurekeyfacts-0"
                                                   value="0" <?php if (!isset($fkeyfactsemail)) {
                                                echo 'checked="checked"';
                                            } elseif ($fkeyfactsemail == '0') {
                                                echo 'checked="checked"';
                                            } ?> type="radio">
                                            0
                                        </label>
                                        <label class="radio-inline" for="featurekeyfacts-1">
                                            <input name="featurekeyfacts" id="featurekeyfacts-1"
                                                   value="1" <?php if ($fkeyfactsemail == '1') {
                                                echo 'checked="checked"';
                                            } ?> type="radio">
                                            1
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="featuregenemail">Generic
                                        Emails</label>
                                    <div class="col-md-4">
                                        <label class="radio-inline" for="featuregenemail-0">
                                            <input name="featuregenemail" id="featuregenemail-0"
                                                   value="0" <?php if (!isset($fgenemail)) {
                                                echo 'checked="checked"';
                                            } elseif ($fgenemail == '0') {
                                                echo 'checked="checked"';
                                            } ?> type="radio">
                                            0
                                        </label>
                                        <label class="radio-inline" for="featuregenemail-1">
                                            <input name="featuregenemail" id="featuregenemail-1"
                                                   value="1" <?php if ($fgenemail == '1') {
                                                echo 'checked="checked"';
                                            } ?> type="radio">
                                            1
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="featuresreemails">Receive
                                        Emails</label>
                                    <div class="col-md-4">
                                        <label class="radio-inline" for="featuresreemails-0">
                                            <input name="featuresreemails" id="featuresreemails-0"
                                                   value="0" <?php if (!isset($frecemail)) {
                                                echo 'checked="checked"';
                                            } elseif ($frecemail == '0') {
                                                echo 'checked="checked"';
                                            } ?> type="radio">
                                            0
                                        </label>
                                        <label class="radio-inline" for="featuresreemails-1">
                                            <input name="featuresreemails" id="featuresreemails-1"
                                                   value="1" <?php if ($frecemail == '1') {
                                                echo 'checked="checked"';
                                            } ?> type="radio">
                                            1
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="featuresms">Send SMS</label>
                                    <div class="col-md-4">
                                        <label class="radio-inline" for="featuresms-0">
                                            <input name="featuresms" id="featuresms-0"
                                                   value="0" <?php if (!isset($fsms)) {
                                                echo 'checked="checked"';
                                            } elseif ($fsms == '0') {
                                                echo 'checked="checked"';
                                            } ?> type="radio">
                                            0
                                        </label>
                                        <label class="radio-inline" for="featuresms-1">
                                            <input name="featuresms" id="featuresms-1"
                                                   value="1" <?php if ($fsms == '1') {
                                                echo 'checked="checked"';
                                            } ?> type="radio">
                                            1
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="featurescal">Calendar</label>
                                    <div class="col-md-4">
                                        <label class="radio-inline" for="featurescal-0">
                                            <input name="featurescal" id="featurescal-0"
                                                   value="0" <?php if (!isset($fcalendar)) {
                                                echo 'checked="checked"';
                                            } elseif ($fcalendar == '0') {
                                                echo 'checked="checked"';
                                            } ?> type="radio">
                                            0
                                        </label>
                                        <label class="radio-inline" for="featurescal-1">
                                            <input name="featurescal" id="featurescal-1"
                                                   value="1" <?php if ($fcalendar == '1') {
                                                echo 'checked="checked"';
                                            } ?> type="radio">
                                            1
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="featureaudits">Call Audits</label>
                                    <div class="col-md-4">
                                        <label class="radio-inline" for="featureaudits-0">
                                            <input name="featureaudits" id="featureaudits-0"
                                                   value="0" <?php if (!isset($faudits)) {
                                                echo 'checked="checked"';
                                            } elseif ($faudits == '0') {
                                                echo 'checked="checked"';
                                            } ?> type="radio">
                                            0
                                        </label>
                                        <label class="radio-inline" for="featureaudits-1">
                                            <input name="featureaudits" id="featureaudits-1"
                                                   value="1" <?php if ($faudits == '1') {
                                                echo 'checked="checked"';
                                            } ?> type="radio">
                                            1
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="featurelife">Life Insurnace</label>
                                    <div class="col-md-4">
                                        <label class="radio-inline" for="featurelife-0">
                                            <input name="featurelife" id="featurelife-0"
                                                   value="0" <?php if (!isset($flife)) {
                                                echo 'checked="checked"';
                                            } elseif ($flife == '0') {
                                                echo 'checked="checked"';
                                            } ?> type="radio">
                                            0
                                        </label>
                                        <label class="radio-inline" for="featurelife-1">
                                            <input name="featurelife" id="featurelife-1"
                                                   value="1" <?php if ($flife == '1') {
                                                echo 'checked="checked"';
                                            } ?> type="radio">
                                            1
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="featurepba">PBA</label>
                                    <div class="col-md-4">
                                        <label class="radio-inline" for="featurepba-0">
                                            <input name="featurepba" id="featurepba-0"
                                                   value="0" <?php if (!isset($fpba)) {
                                                echo 'checked="checked"';
                                            } elseif ($fpba == '0') {
                                                echo 'checked="checked"';
                                            } ?> type="radio">
                                            0
                                        </label>
                                        <label class="radio-inline" for="featurepba-1">
                                            <input name="featurepba" id="featurepba-1"
                                                   value="1" <?php if ($fpba == '1') {
                                                echo 'checked="checked"';
                                            } ?> type="radio">
                                            1
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="featurepensions">Pensions</label>
                                    <div class="col-md-4">
                                        <label class="radio-inline" for="featurepensions-0">
                                            <input name="featurepensions" id="featurepensions-0"
                                                   value="0" <?php if (!isset($fpensions)) {
                                                echo 'checked="checked"';
                                            } elseif ($fpensions == '0') {
                                                echo 'checked="checked"';
                                            } ?> type="radio">
                                            0
                                        </label>
                                        <label class="radio-inline" for="featurepensions-1">
                                            <input name="featurepensions" id="featurepensions-1"
                                                   value="1" <?php if ($fpensions == '1') {
                                                echo 'checked="checked"';
                                            } ?> type="radio">
                                            1
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="featurehome">Home Insurance</label>
                                    <div class="col-md-4">
                                        <label class="radio-inline" for="featurehome-0">
                                            <input name="featurehome" id="featurehome-0"
                                                   value="0" <?php if (!isset($fhome)) {
                                                echo 'checked="checked"';
                                            } elseif ($fhome == '0') {
                                                echo 'checked="checked"';
                                            } ?> type="radio">
                                            0
                                        </label>
                                        <label class="radio-inline" for="featurehome-1">
                                            <input name="featurehome" id="featurehome-1"
                                                   value="1" <?php if ($fhome == '1') {
                                                echo 'checked="checked"';
                                            } ?> type="radio">
                                            1
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="featureerror">Enable Error
                                        Checking</label>
                                    <div class="col-md-4">
                                        <label class="radio-inline" for="featureerror-0">
                                            <input name="featureerror" id="featureerror-0"
                                                   value="0" <?php if (!isset($ferror)) {
                                                echo 'checked="checked"';
                                            } elseif ($ferror == '0') {
                                                echo 'checked="checked"';
                                            } ?> type="radio">
                                            0
                                        </label>
                                        <label class="radio-inline" for="featureerror-1">
                                            <input name="featureerror" id="featureerror-1"
                                                   value="1" <?php if ($ferror == '1') {
                                                echo 'checked="checked"';
                                            } ?> type="radio">
                                            1
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="featuredealsheets">Enable
                                        Dealsheets</label>
                                    <div class="col-md-4">
                                        <label class="radio-inline" for="featuredealsheets-0">
                                            <input name="featuredealsheets" id="featuredealsheets-0"
                                                   value="0" <?php if (!isset($fdealsheets)) {
                                                echo 'checked="checked"';
                                            } elseif ($fdealsheets == '0') {
                                                echo 'checked="checked"';
                                            } ?> type="radio">
                                            0
                                        </label>
                                        <label class="radio-inline" for="featuredealsheets-1">
                                            <input name="featuredealsheets" id="featuredealsheets-1"
                                                   value="1" <?php if ($fdealsheets == '1') {
                                                echo 'checked="checked"';
                                            } ?> type="radio">
                                            1
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="featureemployee">Enable Employee
                                        Database</label>
                                    <div class="col-md-4">
                                        <label class="radio-inline" for="featureemployee-0">
                                            <input name="featureemployee" id="featureemployee-0"
                                                   value="0" <?php if (!isset($femployee)) {
                                                echo 'checked="checked"';
                                            } elseif ($femployee == '0') {
                                                echo 'checked="checked"';
                                            } ?> type="radio">
                                            0
                                        </label>
                                        <label class="radio-inline" for="featureemployee-1">
                                            <input name="featureemployee" id="featureemployee-1"
                                                   value="1" <?php if ($femployee == '1') {
                                                echo 'checked="checked"';
                                            } ?> type="radio">
                                            1
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="featuretracker">Enable
                                        Trackers</label>
                                    <div class="col-md-4">
                                        <label class="radio-inline" for="featuretracker-0">
                                            <input name="featuretracker" id="featuretracker-0"
                                                   value="0" <?php if (!isset($ftracker)) {
                                                echo 'checked="checked"';
                                            } elseif ($ftracker == '0') {
                                                echo 'checked="checked"';
                                            } ?> type="radio">
                                            0
                                        </label>
                                        <label class="radio-inline" for="featuretracker-1">
                                            <input name="featuretracker" id="featuretracker-1"
                                                   value="1" <?php if ($ftracker == '1') {
                                                echo 'checked="checked"';
                                            } ?> type="radio">
                                            1
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="featureews">Enable EWS</label>
                                    <div class="col-md-4">
                                        <label class="radio-inline" for="featureews-0">
                                            <input name="featureews" id="featureews-0"
                                                   value="0" <?php if (!isset($fews)) {
                                                echo 'checked="checked"';
                                            } elseif ($fews == '0') {
                                                echo 'checked="checked"';
                                            } ?> type="radio">
                                            0
                                        </label>
                                        <label class="radio-inline" for="featureews-1">
                                            <input name="featureews" id="featureews-1"
                                                   value="1" <?php if ($fews == '1') {
                                                echo 'checked="checked"';
                                            } ?> type="radio">
                                            1
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="featurefinancials">Enable
                                        Financials</label>
                                    <div class="col-md-4">
                                        <label class="radio-inline" for="featurefinancials-0">
                                            <input name="featurefinancials" id="featurefinancials-0"
                                                   value="0" <?php if (!isset($ffinancials)) {
                                                echo 'checked="checked"';
                                            } elseif ($ffinancials == '0') {
                                                echo 'checked="checked"';
                                            } ?> type="radio">
                                            0
                                        </label>
                                        <label class="radio-inline" for="featurefinancials-1">
                                            <input name="featurefinancials" id="featurefinancials-1"
                                                   value="1" <?php if ($ffinancials == '1') {
                                                echo 'checked="checked"';
                                            } ?> type="radio">
                                            1
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="featurecompliance">Enable
                                        Compliance</label>
                                    <div class="col-md-4">
                                        <label class="radio-inline" for="featurecompliance-0">
                                            <input name="featurecompliance" id="featurecompliance-0"
                                                   value="0" <?php if (!isset($fcompliance)) {
                                                echo 'checked="checked"';
                                            } elseif ($fcompliance == '0') {
                                                echo 'checked="checked"';
                                            } ?> type="radio">
                                            0
                                        </label>
                                        <label class="radio-inline" for="featurecompliance-1">
                                            <input name="featurecompliance" id="featurecompliance-1"
                                                   value="1" <?php if ($fcompliance == '1') {
                                                echo 'checked="checked"';
                                            } ?> type="radio">
                                            1
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="featuressubmit"></label>
                                    <div class="col-md-4">
                                        <button id="featuressubmit" name="featuressubmit" class="btn btn-success">
                                            Submit
                                        </button>
                                    </div>
                                </div>

                            </fieldset>
                        </form>


                    <?php } ?>
                    <br>
                    <br>
                    <br>

                    <a href="#menu-toggle" class="btn btn-default" id="menu-toggle">Toggle Menu</a>
                </div>
            </div>
        </div>
    </div>

</div>
<script src="/resources/lib/sweet-alert/sweet-alert.min.js"></script>
<script src="/resources/lib/sweet-alert/sweet-alert.min.js"></script>
<script src="/resources/lib/jquery/jquery-3.0.0.min.js"></script>
<script src="/resources/templates/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
<script>
    $("#menu-toggle").click(function (e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
</script>
<script type="text/javascript" src="/resources/lib/summernote-master/dist/summernote.js"></script>

<script type="text/javascript">
    $(function () {
        $('.summernote').summernote({
            height: 200
        });


    });
</script>
<script type="text/JavaScript">
    var $select = $('#taskuser');
    $.getJSON('/app/JSON/ADL_USERS.php?EXECUTE=1&USER=<?php echo $hello_name; ?>&TOKEN=<?php echo $TOKEN; ?>', function (data) {
        $select.html('agent_name');
        $.each(data, function (key, val) {
            $select.append('<option value="' + val.FULL_NAME + '">' + val.FULL_NAME + '</option>');
        })
    });
</script>
<script src="/resources/lib/jquery-ui-1.11.4/jquery-ui.min.js"></script>
<script>
    $(function () {
        $("#TRACKING_DATE").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:-0"
        });
    });
</script>
<script>
    $(function () {
        $("#TRACKING_DATETO").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:-0"
        });
    });
</script>
</body>

</html>
