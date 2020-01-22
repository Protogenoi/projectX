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
require_once(BASE_URL . '/classes/database_class.php');
require_once(BASE_URL . '/includes/ADL_PDO_CON.php');

if (isset($ffanalytics) && $ffanalytics == '1') {
    require_once(BASE_URL . '/app/analyticstracking.php');
}

if (isset($fferror) && $fferror == 1) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

$REF = filter_input(INPUT_GET, 'REF', FILTER_SANITIZE_SPECIAL_CHARS);
$RETURN = filter_input(INPUT_GET, 'RETURN', FILTER_SANITIZE_SPECIAL_CHARS);
$HOL_REF = filter_input(INPUT_GET, 'HOL_REF', FILTER_SANITIZE_SPECIAL_CHARS);

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

$database = new Database();

$database->query("SELECT campaign, company, employed, ni_num, id_provided, id_details, dob, title, firstname, end_date, lastname, CONCAT(title, ' ', firstname, ' ', lastname) AS NAME, position, start_date, added_date, added_by, updated_date, updated_by FROM employee_details WHERE employee_id=:REF");
$database->bind(':REF', $REF);
$database->execute();
$data2 = $database->single();

$database->query("SELECT mob, tel, email, add1, add2, add3, town, postal FROM employee_contact WHERE employee_id=:REF");
$database->bind(':REF', $REF);
$database->execute();
$data3 = $database->single();

$database->query("SELECT contact_name, contact_num, contact_relationship, contact_address, medical FROM employee_emergency WHERE employee_id=:REF");
$database->bind(':REF', $REF);
$database->execute();
$data4 = $database->single();

$CON_NAME = $data4['contact_name'];
$CON_NUM = $data4['contact_num'];
$CON_REL = $data4['contact_relationship'];
$CON_ADD = $data4['contact_address'];
$MEDICAL = $data4['medical'];


$EMAIL = $data3['email'];
$MOB = $data3['mob'];
$TEL = $data3['tel'];
$ADD1 = $data3['add1'];
$ADD2 = $data3['add2'];
$ADD3 = $data3['add3'];
$TOWN = $data3['town'];
$POSTAL = $data3['postal'];

$EMPLOYED = $data2['employed'];
$POSITION = $data2['position'];
$START_DATE = $data2['start_date'];
$END_DATE = $data2['end_date'];
$ADDED_DATE = $data2['added_date'];
$UPDATED_DATE = $data2['updated_date'];
$ADDED_BY = $data2['added_by'];
$UPDATED_BY = $data2['updated_by'];
$NI_NUM = $data2['ni_num'];
$ID_PROVIDED = $data2['id_provided'];
$ID_DETAILS = $data2['id_details'];
$NAME = "$data2[title] $data2[firstname] $data2[lastname]";
$FIRSTNAME = $data2['firstname'];
$LASTNAME = $data2['lastname'];
$TITLE = $data2['title'];
$ORIGDOB = $data2['dob'];
$EMP_COMPANY = $data2['company'];
$campaign = $data2['campaign'];


$DOB = date("l jS \of F Y", strtotime($ORIGDOB));

$HOL_START = filter_input(INPUT_GET, 'HOL_START', FILTER_SANITIZE_SPECIAL_CHARS);
$HOL_END = filter_input(INPUT_GET, 'HOL_END', FILTER_SANITIZE_SPECIAL_CHARS);
$HOL_REASON = filter_input(INPUT_GET, 'HOL_REASON', FILTER_SANITIZE_SPECIAL_CHARS);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>ADL | View Employee</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/resources/templates/bootstrap-3.3.5-dist/cosmo/bootstrap.min.css">
    <link rel="stylesheet" href="/resources/templates/bootstrap-3.3.5-dist/cosmo/bootstrap.css">
    <link rel="stylesheet" href="/resources/lib/sweet-alert/sweet-alert.min.css"/>
    <link rel="stylesheet" href="/resources/lib/jquery-ui-1.11.4/jquery-ui.min.css"/>
    <link rel="stylesheet" href="/resources/templates/ADL/Notices.css"/>
    <link href="/img/favicon.ico" rel="icon" type="image/x-icon"/>
    <style>
        .label {
            display: block;
            width: 100px;
        }

        .appbox {
            background: #48B0F7;
        }

        .noshows {
            background: #F55753;
        }

        .totalbox {
            background: #10CFBD;
        }

        .outbox {
            background: #F8D053;
        }

        .fa-edit {
            color: #FEAF20;
        }

        .fa-exclamation {
            color: #FEAF20;
        }

        .fa-phone {
            color: #2A6598;
        }

        .fa-check-circle {
            color: green;
        }
    </style>
    <script type="text/javascript" src="/resources/templates/fontawesome/svg-with-js/js/fontawesome-all.js"></script>
</head>
<body>

<?php

require_once(BASE_URL . '/includes/navbar.php');

?>

<div class="content full-height">
    <div class="container-fluid full-height">
        <div class="row fixed-toolbar">
            <div class="col-xs-5">
                <a href="Search.php" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back</a>
            </div>
            <div class="col-xs-7">
                <div class="text-right">
                    <?php if (isset($RETURN)) {
                        if ($RETURN == 'ALREADYBOOKED') { ?>
                            <a class="btn btn-success" href="php/Employee.php?EXECUTE=<?php if (isset($HOL_REF)) {
                                echo 7;
                            } else {
                                echo 7;
                            } ?>&HOL_START=<?php echo $HOL_START; ?>&HOL_END=<?php echo $HOL_END; ?>&NAME=<?php echo "$FIRSTNAME $LASTNAME"; ?>&HOL_REASON=<?php echo $HOL_REASON; ?>&REF=<?php echo $REF; ?>"><i
                                    class="fa fa-calendar-alt"></i> Authorise Holiday</a>
                        <?php }
                    } ?>
                    <a class="btn btn-info" data-toggle="modal" data-target="#BookModal" data-backdrop="static"
                       data-keyboard="false"><i class="fa fa-calendar-check"></i> Add Holidays</a>


                    <a class="btn btn-primary" data-toggle="modal" data-target="#myModal" data-backdrop="static"
                       data-keyboard="false"><i class="fa fa-edit"></i> Edit</a>
                    <?php if (isset($EMPLOYED)) {
                        if ($EMPLOYED == '1') { ?> <a class="btn btn-danger" data-toggle="modal"
                                                      data-target="#FireModal" data-backdrop="static"
                                                      data-keyboard="false"><i class="fa fa-eraser"></i>
                            FIRE!</a>  <?php }
                    } ?>
                    <?php if (isset($EMPLOYED)) {
                        if ($EMPLOYED == '0') { ?> <a class="btn btn-default" data-toggle="modal"
                                                      data-target="#HireModal" data-backdrop="static"
                                                      data-keyboard="false"><i class="fa fa-handshake-o"></i>
                            RE-HIRE!</a>  <?php }
                    } ?>
                    <a class="btn btn-warning" href="#"><i class="fa fa-trash"></i> Delete</a>

                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <?php include('php/Notifications.php'); ?>
            </div>
            <div class="row">
                <h1><?php echo $NAME; ?><?php if (isset($POSITION)) {
                        echo " - $POSITION - $EMP_COMPANY </h1>";
                    }
                    if (isset($EMPLOYED)) {
                        if ($EMPLOYED == '0') {
                            echo "<h3><strong> No longer an employee</strong></h3>";
                        }
                    } ?>
                    <ul class="nav nav-pills nav-justified">
                        <li class="active"><a data-toggle="pill" href="#Menu1">Summary</a></li>
                        <li><a data-toggle="pill" href="#Menu2">Emergency Details</a></li>
                        <li><a data-toggle="pill" href="#menu4">Timeline</a></li>
                        <li><a data-toggle="pill" href="#Menu3">Files & Uploads</a></li>
                        <li><a data-toggle="pill" href="#Menu5">Holidays</a></li>
                    </ul>
            </div>

            <div class="row">
                <div class="panel">
                    <div class="panel-body">
                        <div class="tab-content">

                            <div id="Menu1" class="tab-pane fade in active">
                                <div class='row'>

                                    <?php

                                    $HOLS_COUNT_QRY = $pdo->prepare("select SUM(days) AS count from employee_holidays WHERE employee_id=:REF AND DATE(start) >='2019'");
                                    $HOLS_COUNT_QRY->bindParam(':REF', $REF, PDO::PARAM_INT);
                                    $HOLS_COUNT_QRY->execute() or die(print_r($HOLS_COUNT_QRY->errorInfo(), true));
                                    $result = $HOLS_COUNT_QRY->fetch(PDO::FETCH_ASSOC);
                                    $HOL_COUNT = $result['count'];

                                    ?>

                                    <div class='col-xs-6 col-sm-3 p-r-5 sm-p-l-15'>
                                        <div class='panel panel-default m-b-10 b-regular sm-m-'>
                                            <div class='panel-body p-b-10 p-t-10 appbox'>
                                                <div class='text-center'>
                                                    <h3 class='bold text-white no-margin'><?php if (isset($HOL_COUNT)) {
                                                            echo $HOL_COUNT;
                                                        } else {
                                                            echo "0";
                                                        } ?></h3>
                                                    <div class='m-t-10 text-white sm-m-b-5'>Holidays Booked</div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                    <?php

                                    $AWOL_QRY = $pdo->prepare("select (SUM(sick)+SUM(awol)) AS count from employee_register where employee_id=:REF");
                                    $AWOL_QRY->bindParam(':REF', $REF, PDO::PARAM_INT);
                                    $AWOL_QRY->execute() or die(print_r($AWOL_QRY->errorInfo(), true));
                                    $result_APP_COUNT_STATUS = $AWOL_QRY->fetch(PDO::FETCH_ASSOC);
                                    $AWOL_QRY_RESULT = $result_APP_COUNT_STATUS['count'];

                                    ?>

                                    <div class='col-xs-6 col-sm-3 p-l-5 p-r-5 sm-p-r-15'>
                                        <div class='panel panel-default m-b-10 b-regular'>
                                            <div class='panel-body p-b-10 p-t-10 noshows'>
                                                <div class='text-center'>
                                                    <h3 class='bold text-white no-margin'><?php if (isset($AWOL_QRY_RESULT)) {
                                                            echo $AWOL_QRY_RESULT;
                                                        } else {
                                                            echo "0";
                                                        } ?></h3>
                                                    <div class='m-t-10 text-white sm-m-b-5'>Days off (Sick/AWOL)</div>
                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                    <?php

                                    $CON_QRY_COUNT = $pdo->prepare("select sum(sales) AS sales, SUM(leads) AS leads from lead_rag where year='2019' AND employee_id=:REF");
                                    $CON_QRY_COUNT->bindParam(':REF', $REF, PDO::PARAM_INT);
                                    $CON_QRY_COUNT->execute() or die(print_r($CON_QRY_COUNT->errorInfo(), true));
                                    $CON_QRY_result = $CON_QRY_COUNT->fetch(PDO::FETCH_ASSOC);
                                    $CON_QRY_COUNT_SALES = $CON_QRY_result['sales'];
                                    $CON_QRY_COUNT_LEADS = $CON_QRY_result['leads'];

                                    if ($CON_QRY_COUNT_SALES > 0) {

                                        $Conversionrate = $CON_QRY_COUNT_LEADS / $CON_QRY_COUNT_SALES;
                                        $Formattedrate = number_format($Conversionrate, 1);
                                    } else {
                                        $Formattedrate = 0;
                                    }


                                    ?>

                                    <div class='col-xs-6 col-sm-3 p-r-5 p-l-5 sm-p-l-15'>
                                        <div class='panel panel-default m-b-10 b-regular'>
                                            <div class='panel-body p-b-10 p-t-10 totalbox'>
                                                <div class='text-center'>
                                                    <h3 class='bold text-white no-margin'><?php if (isset($Formattedrate)) {
                                                            echo "$CON_QRY_COUNT_LEADS/$CON_QRY_COUNT_SALES ($Formattedrate)";
                                                        } else {
                                                            echo "No Data";
                                                        } ?></h3>
                                                    <div class='m-t-10 text-white'>Conversion Rate</div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <?php

                                    #$APP_COUNT_OUTSTAN = $pdo->prepare("select SUM(price) AS price from appointments WHERE client_id=:REF AND status NOT IN ('Complete','No Show')");
                                    #$APP_COUNT_OUTSTAN->bindParam(':REF', $REF, PDO::PARAM_INT);
                                    #$APP_COUNT_OUTSTAN->execute()or die(print_r($query->errorInfo(), true));
                                    #$result_APP_COUNT_OUTSTAN=$APP_COUNT_OUTSTAN->fetch(PDO::FETCH_ASSOC);
                                    #$APP_COUNT_OUTSTAN_RESULT=$result_APP_COUNT_OUTSTAN['price'];

                                    #$APP_FORMATTED_OUTSTAN = number_format($APP_COUNT_OUTSTAN_RESULT, 2);

                                    ?>

                                    <div class='col-xs-6 col-sm-3 p-l-5 sm-p-r-15'>
                                        <div class='panel panel-default m-b-10 b-regular'>
                                            <div class='panel-body p-b-10 p-t-10 outbox'>
                                                <div class='text-center'>
                                                    <h3 class='bold text-white no-margin'><?php if (!empty($APP_COUNT_OUTSTAN_RESULT)) {
                                                            echo "£$APP_FORMATTED_OUTSTAN";
                                                        } else {
                                                            echo "£0.00";
                                                        } ?></h3>
                                                    <div class='m-t-10 text-white'>Outstanding</div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>
                                </div>
                                <div class='col-sm-6 sm-p-r-15 sm-p-l-15 p-r-5'>
                                    <table class='table table-condensed bg-white no-margin'>
                                        <tbody>
                                        <tr>
                                            <td class='hint-text col-xs-5 col-sm-4'><i class="fa fa-mobile"></i> Mobile
                                            </td>
                                            <td class='font-bold'><?php if (isset($MOB)) {
                                                    echo $MOB;
                                                } ?></td>
                                        </tr>
                                        <tr>
                                            <td class='hint-text col-xs-5 col-sm-4'><i class="fa fa-phone"></i>
                                                Telephone
                                            </td>
                                            <td class='font-bold'><?php if (isset($TEL)) {
                                                    echo $TEL;
                                                } ?></td>
                                        </tr>
                                        <tr>
                                            <td class='hint-text col-xs-5 col-sm-4'><i class="fa fa-envelope"></i> Email
                                            </td>
                                            <td class='font-bold'><?php if (isset($EMAIL)) {
                                                    echo $EMAIL;
                                                } ?></td>
                                        </tr>
                                        <tr>
                                            <td class='hint-text col-xs-5 col-sm-4'><i class="fa fa-clipboard"></i> ID
                                                Provided
                                            </td>
                                            <td class='font-bold'><?php if (isset($ID_PROVIDED)) {

                                                    switch ($ID_PROVIDED) {
                                                        case "1":
                                                            $ID_PROVIDED = "Passport Number";
                                                            break;
                                                        case "2":
                                                            $ID_PROVIDED = "Driving License";
                                                            break;
                                                        case "3":
                                                            $ID_PROVIDED = "Bank Card Check";
                                                            break;
                                                        case "4":
                                                            $ID_PROVIDED = "None";
                                                            break;
                                                        default:
                                                            $ID_PROVIDED = "None provided";

                                                    }
                                                    echo $ID_PROVIDED;
                                                } ?></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class='col-sm-6 sm-p-r-15 sm-p-l-15 p-l-5'>
                                    <table class='table table-condensed bg-white no-margin'>
                                        <tbody>
                                        <tr>
                                            <td class='hint-text col-xs-5 col-sm-4'><i class="fa fa-birthday-cake"></i>
                                                Birthday
                                            </td>
                                            <td class='font-bold'><?php if (isset($DOB)) {
                                                    echo $DOB;
                                                } ?></td>
                                        </tr>

                                        <tr>
                                            <td class='hint-text col-xs-5 col-sm-4'><i
                                                    class="fa fa-drivers-license"></i> NI Number
                                            </td>
                                            <td class='font-bold'><?php if (isset($NI_NUM)) {
                                                    echo $NI_NUM;
                                                } ?></td>
                                        </tr>
                                        <tr>
                                            <td class='hint-text col-xs-5 col-sm-4'><i class="fa fa-calendar-alt"></i>
                                                Employment Dates
                                            </td>
                                            <td class='font-bold'><?php if (isset($START_DATE)) {
                                                    echo "<strong>$START_DATE</strong>";
                                                }
                                                if (isset($END_DATE)) {
                                                    echo "<strong> - $END_DATE</strong>";
                                                } ?></td>
                                        </tr>

                                        <tr>
                                            <td class='hint-text col-xs-5 col-sm-4'><i class="fa fa-check-circle"></i>
                                                ID Details
                                            </td>
                                            <td class='font-bold'><?php if (isset($ID_DETAILS)) {
                                                    echo $ID_DETAILS;
                                                } ?></td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>

                                <div class='row'>
                                    <div class='col-lg-12'>
                                        <table class='table table-condensed bg-white no-margin'>
                                            <tbody>
                                            <tr>
                                                <td class='no-border col-sm-2 col-xs-5 hint-text'><i
                                                        class="fa fa-medkit"></i> Medical Conditions
                                                </td>
                                                <td class='no-border p-l-15'>
                                                    <div class='text-italic'><p><?php if (isset($MEDICAL)) {
                                                                echo $MEDICAL;
                                                            } ?></p></div>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>


                            </div>

                            <div id="menu4" class="tab-pane fade">

                                <div class='container'>
                                    <div class="row">
                                        <form method="post" id="clientnotessubtab"
                                              action="php/Employee.php?EXECUTE=9&REF=<?php echo $REF; ?>"
                                              class="form-horizontal">


                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="textarea"></label>
                                                <div class="col-md-4">
                                                    <textarea class="form-control" id="notes" name="notes"
                                                              maxlength="2000" placeholder="Add a note"
                                                              required></textarea>
                                                    <center><font color="red"><i><span id="chars">2000</span> characters
                                                                remaining</i></font></center>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="singlebutton"></label>
                                                <div class="col-md-4">
                                                    <button class="btn btn-primary btn-block"><i
                                                            class="fa fa-pencil-square-o"></i> Submit
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <h3><span class="label label-info">Timeline</span></h3>

                                <?php

                                $clientnote = $pdo->prepare("select note_type, message, added_by, added_date from employee_timeline where employee_id =:REF ORDER BY added_date DESC");
                                $clientnote->bindParam(':REF', $REF, PDO::PARAM_INT);

                                $clientnote->execute();
                                if ($clientnote->rowCount() > 0) { ?>

                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>User</th>
                                            <th>Note Type</th>
                                            <th>Message</th>
                                        </tr>
                                        </thead>

                                        <?php
                                        while ($result = $clientnote->fetch(PDO::FETCH_ASSOC)) {

                                            $TLdate = $result['added_date'];
                                            $TLwho = $result['added_by'];
                                            $TLmessage = $result['message'];
                                            $TLnotetype = $result['note_type'];

                                            switch ($TLnotetype) {

                                                case "Employee Added":
                                                    $TMicon = "fa-user-plus";
                                                    break;
                                                case "Holiday Booked":
                                                    $TMicon = "fa-plane";
                                                    break;
                                                case "Inventory Updated":
                                                    $TMicon = "fa-list-ul";
                                                    break;
                                                case "CRM Alert":
                                                case "Policy Added":
                                                case "Doc Read";
                                                    $TMicon = "fa-check";
                                                    break;
                                                case "File Upload":
                                                case"Upload";
                                                case"LGkeyfacts";
                                                case"Recording";
                                                    $TMicon = "fa-upload";
                                                    break;
                                                case stristr($TLnotetype, "Tasks"):
                                                    $TMicon = "fa-tasks";
                                                    break;
                                                case "Note Added":
                                                    $TMicon = "fa-pencil";
                                                    break;
                                                case stristr($TLnotetype, "Callback"):
                                                    $TMicon = "fa-calendar-alt";
                                                    break;
                                                case "Email Sent":
                                                    $TMicon = "fa-envelope-o";
                                                    break;
                                                case "Employee Edited":
                                                    $TMicon = "fa-edit";
                                                    break;
                                                case "Doc Question":
                                                    $TMicon = "fa-question";
                                                    break;
                                                case "Call Audit";
                                                    $TMicon = "fa-headphones";
                                                    break;
                                                case "Test Marked";
                                                case "Took Test";
                                                    $TMicon = "fa-university";
                                                    break;
                                                case "Sent SMS":
                                                    $TMicon = "fa-phone";
                                                    break;
                                                default:
                                                    $TMicon = "fa-bomb";

                                            }

                                            echo '<tr>';
                                            echo "<td>$TLdate</td>";
                                            echo "<td>$TLwho</td>";
                                            echo "<td><i class='fa $TMicon'></i> $TLnotetype</td>";
                                            echo "<td><strong>$TLmessage</b></td>";
                                            echo "</tr>";

                                        } ?>

                                    </table>

                                <?php } ?>


                            </div>


                            <div id="Menu5" class="tab-pane fade">
                                <?php if (isset($RETURN)) {
                                    if ($RETURN == 'ALREADYBOOKED') { ?>

                                        <span class="label label-default">Booked Holidays</span>

                                        <?php

                                        $BOOKED_QRY = $pdo->prepare("select CONCAT(employee_details.firstname, ' ', employee_details.lastname) AS NAME, employee_holidays.start, employee_holidays.end, employee_holidays.title, employee_holidays.added_by, employee_holidays.updated_date FROM employee_holidays JOIN employee_details on employee_holidays.employee_id = employee_details.employee_id WHERE employee_holidays.start between :start AND :end ORDER BY DATE(employee_holidays.start) ASC");
                                        $BOOKED_QRY->bindParam(':start', $HOL_START, PDO::PARAM_STR);
                                        $BOOKED_QRY->bindParam(':end', $HOL_END, PDO::PARAM_STR);
                                        $BOOKED_QRY->execute();
                                        if ($BOOKED_QRY->rowCount() > 0) { ?>

                                            <table class="table table-hover">
                                                <thead>
                                                <tr>
                                                    <th>Authorised</th>
                                                    <th>Employee</th>
                                                    <th>Dates</th>
                                                    <th>Reason</th>
                                                </tr>
                                                </thead>

                                                <?php
                                                while ($result = $BOOKED_QRY->fetch(PDO::FETCH_ASSOC)) {

                                                    $BOOKED_QR_START = $result['start'];
                                                    $BOOKED_QR_EMPLOYEE = $result['NAME'];
                                                    $BOOKED_QR_END = $result['end'];
                                                    $BOOKED_QR_TITLE = $result['title'];
                                                    $BOOKED_QR_BY = $result['added_by'];
                                                    $BOOKED_QR_DATE = $result['updated_date'];

                                                    echo '<tr>';
                                                    echo "<td>$BOOKED_QR_DATE | $BOOKED_QR_BY</td>";
                                                    echo "<td>$BOOKED_QR_EMPLOYEE</td>";
                                                    echo "<td><i class='fa fa-calendar-o'></i> $BOOKED_QR_START - $BOOKED_QR_END</td>";
                                                    echo "<td><strong>$BOOKED_QR_TITLE</b></td>";
                                                    echo "</tr>";

                                                } ?>

                                            </table>

                                        <?php }
                                    }
                                } ?>

                                <span class="label label-info">Holidays</span>

                                <?php

                                $HOL_QRY = $pdo->prepare("select hol_id, start, end, title, added_by, updated_date from employee_holidays where employee_id =:REF ORDER BY DATE(start) ASC");
                                $HOL_QRY->bindParam(':REF', $REF, PDO::PARAM_INT);

                                $HOL_QRY->execute();
                                if ($HOL_QRY->rowCount() > 0) { ?>

                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th>Authorised</th>
                                            <th>Dates</th>
                                            <th>Reason</th>
                                            <th>Options</th>
                                        </tr>
                                        </thead>

                                        <?php
                                        while ($result = $HOL_QRY->fetch(PDO::FETCH_ASSOC)) {

                                            $HOL_QR_ID = $result['hol_id'];
                                            $HOL_QR_START = $result['start'];
                                            $HOL_QR_END = $result['end'];
                                            $HOL_QR_TITLE = $result['title'];
                                            $HOL_QR_BY = $result['added_by'];
                                            $HOL_QR_DATE = $result['updated_date'];

                                            echo '<tr>';
                                            echo "<td>$HOL_QR_DATE | $HOL_QR_BY</td>";
                                            echo "<td><i class='fa fa-calendar-o'></i> $HOL_QR_START - $HOL_QR_END</td>";
                                            echo "<td><strong>$HOL_QR_TITLE</b></td>";
                                            echo "<td><a href='?RETURN=EDITMODAL&&REF=$REF&HOL_START=$HOL_QR_START&HOL_END=$HOL_QR_END&HOL_REASON=$HOL_QR_TITLE&HOL_REF=$HOL_QR_ID' class='btn btn-warning btn-xs'><i class='fa fa-edit'></i> </a></td>";
                                            echo "</tr>";

                                        } ?>

                                    </table>

                                <?php } ?>
                            </div>

                            <div id="Menu2" class="tab-pane fade">

                                <div class='col-sm-6 sm-p-r-15 sm-p-l-15 p-r-5'>
                                    <table class='table table-condensed bg-white no-margin'>
                                        <tbody>
                                        <tr>
                                            <td class='hint-text col-xs-5 col-sm-4'><i class="fa fa-mobile"></i> Contact
                                                Name
                                            </td>
                                            <td class='font-bold'><?php if (isset($CON_NAME)) {
                                                    echo $CON_NAME;
                                                } ?></td>
                                        </tr>

                                        <tr>
                                            <td class='hint-text col-xs-5 col-sm-4'><i class="fa fa-envelope"></i>
                                                Relationship
                                            </td>
                                            <td class='font-bold'><?php if (isset($CON_REL)) {
                                                    echo $CON_REL;
                                                } ?></td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>

                                <div class='col-sm-6 sm-p-r-15 sm-p-l-15 p-r-5'>
                                    <table class='table table-condensed bg-white no-margin'>
                                        <tbody>

                                        <tr>
                                            <td class='hint-text col-xs-5 col-sm-4'><i class="fa fa-phone"></i>
                                                Telephone
                                            </td>
                                            <td class='font-bold'><?php if (isset($CON_NUM)) {
                                                    echo $CON_NUM;
                                                } ?></td>
                                        </tr>

                                        <tr>
                                            <td class='hint-text col-xs-5 col-sm-4'><i class="fa fa-phone"></i> Address
                                            </td>
                                            <td class='font-bold'><?php if (isset($CON_ADD)) {
                                                    echo $CON_ADD;
                                                } ?></td>
                                        </tr>


                                        </tbody>
                                    </table>
                                </div>


                            </div>

                            <div id="Menu3" class="tab-pane fade">

                                <form action="../../uploadsubmit.php?EXECUTE=10&REF=<?php echo $REF; ?>" method="post"
                                      enctype="multipart/form-data">
                                    <label for="file">Select file to upload<input type="file" name="file"/></label>

                                    <label for="uploadtype">
                                        <div class="form-group">
                                            <select style="width: 170px" class="form-control" name="uploadtype"
                                                    required>
                                                <option value="">Select...</option>
                                                <option value="Contract">Employee Contract</option>
                                                <option value="Old Contract">Old Employee Contract</option>
                                                <option value="New Starter Form">New Starter Form</option>
                                                <option value="HM Checklist">HM Checklist</option>
                                                <option value="Copy of ID">Copy of ID</option>
                                                <option value="CV">CV</option>
                                                <option value="Written Warning">Written Warning</option>
                                                <option value="Correspondence">Correspondence</option>
                                                <option value="Payslip">Payslip</option>
                                                <option value="Holiday Form">Holiday Form</option>
                                                <option value="Sub Request">Sub Request</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                    </label>

                                    <button type="submit" class="btn btn-success" name="btn-upload"><span
                                            class="glyphicon glyphicon-arrow-up"> </span></button>
                                </form>
                                <br/><br/>


                                <div class="list-group">
                                    <span class="label label-primary">Employee Files</span>
                                    <?php try {

                                        $queryup = $pdo->prepare("SELECT file, uploadtype FROM employee_upload WHERE employee_id=:REF");
                                        $queryup->bindParam(':REF', $REF, PDO::PARAM_INT);
                                        $queryup->execute();

                                        if ($queryup->rowCount() > 0) {
                                            while ($row = $queryup->fetch(PDO::FETCH_ASSOC)) {

                                                $file = $row['file'];
                                                $uploadtype = $row['uploadtype'];

                                                switch ($uploadtype) {
                                                    case "New Starter Form":
                                                    case "HM Checklist":
                                                        $typeimage = "fa-file-pdf-o";
                                                        break;
                                                    case "Happy Call":
                                                        $typeimage = "fa-headphones";
                                                        break;
                                                    case "Other":
                                                        $typeimage = "fa-file-text-o";
                                                        break;
                                                    case "lifenotes":
                                                        $typeimage = "fa-file-text-o";
                                                        break;
                                                    case "Contract";
                                                    case "Old Contract";
                                                        $typeimage = "fa-balance-scale";
                                                        break;
                                                    case "Holiday Form":
                                                        $typeimage = "fa-plane";
                                                        break;
                                                    case "Written Warning":
                                                        $typeimage = "fa-exclamation-triangle";
                                                        break;
                                                    default:
                                                        $typeimage = $uploadtype;

                                                }

                                                if ($row['uploadtype'] == 'Other') {
                                                    if (file_exists("../uploads/employee/$REF/$file")) { ?>
                                                        <a class="list-group-item"
                                                           href="../uploads/employee/<?php echo $REF; ?>/<?php echo $file; ?>"
                                                           target="_blank"><i class="fa <?php echo $typeimage; ?> fa-fw"
                                                                              aria-hidden="true"></i>
                                                            &nbsp; <?php echo "$uploadtype | $file"; ?></a>
                                                    <?php }
                                                }

                                                if ($row['uploadtype'] == 'HM Checklist') {
                                                    if (file_exists("../uploads/employee/$REF/$file")) { ?>
                                                        <a class="list-group-item"
                                                           href="../uploads/employee/<?php echo $REF; ?>/<?php echo $file; ?>"
                                                           target="_blank"><i class="fa <?php echo $typeimage; ?> fa-fw"
                                                                              aria-hidden="true"></i>
                                                            &nbsp; <?php echo "$uploadtype | $file"; ?></a>
                                                    <?php }
                                                }

                                                if ($row['uploadtype'] == 'New Starter Form') {
                                                    if (file_exists("../uploads/employee/$REF/$file")) { ?>
                                                        <a class="list-group-item"
                                                           href="../uploads/employee/<?php echo $REF; ?>/<?php echo $file; ?>"
                                                           target="_blank"><i class="fa <?php echo $typeimage; ?> fa-fw"
                                                                              aria-hidden="true"></i>
                                                            &nbsp; <?php echo "$uploadtype | $file"; ?></a>
                                                    <?php }
                                                }

                                                if ($row['uploadtype'] == 'Contract') {
                                                    if (file_exists("../uploads/employee/$REF/$file")) { ?>
                                                        <a class="list-group-item"
                                                           href="../uploads/employee/<?php echo $REF; ?>/<?php echo $file; ?>"
                                                           target="_blank"><i class="fa <?php echo $typeimage; ?> fa-fw"
                                                                              aria-hidden="true"></i>
                                                            &nbsp; <?php echo "$uploadtype | $file"; ?></a>
                                                    <?php }
                                                }
                                                if ($row['uploadtype'] == 'Old Contract') {
                                                    if (file_exists("../uploads/employee/$REF/$file")) { ?>
                                                        <a class="list-group-item"
                                                           href="../uploads/employee/<?php echo $REF; ?>/<?php echo $file; ?>"
                                                           target="_blank"><i class="fa <?php echo $typeimage; ?> fa-fw"
                                                                              aria-hidden="true"></i>
                                                            &nbsp; <?php echo "$uploadtype | $file"; ?></a>
                                                    <?php }
                                                }
                                                if ($row['uploadtype'] == 'Holiday Form') {
                                                    if (file_exists("../uploads/employee/$REF/$file")) { ?>
                                                        <a class="list-group-item"
                                                           href="../uploads/employee/<?php echo $REF; ?>/<?php echo $file; ?>"
                                                           target="_blank"><i class="fa <?php echo $typeimage; ?> fa-fw"
                                                                              aria-hidden="true"></i>
                                                            &nbsp; <?php echo "$uploadtype | $file"; ?></a>
                                                    <?php }
                                                }
                                                if ($row['uploadtype'] == 'Written Warning') {
                                                    if (file_exists("../uploads/employee/$REF/$file")) { ?>
                                                        <a class="list-group-item"
                                                           href="../uploads/employee/<?php echo $REF; ?>/<?php echo $file; ?>"
                                                           target="_blank"><i class="fa <?php echo $typeimage; ?> fa-fw"
                                                                              aria-hidden="true"></i>
                                                            &nbsp; <?php echo "$uploadtype | $file"; ?></a>
                                                    <?php }
                                                }

                                            }
                                        } else { ?>
                                            <a class="list-group-item"><i class="fa fa-exclamation fa-fw"
                                                                          aria-hidden="true"></i> &nbsp; No files have
                                                been uploaded to this employee</a>

                                        <?php }
                                    } catch (PDOException $e) {
                                        echo 'Connection failed: ' . $e->getMessage();

                                    } ?>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
if (in_array($hello_name, $Level_10_Access, true)) { ?>

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">You are now editing <?php echo $NAME; ?></h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <ul class="nav nav-pills nav-justified">
                        <li class="active"><a data-toggle="pill" href="#Modal1">Employee</a></li>
                        <li><a data-toggle="pill" href="#Modal2">Contact Details</a></li>
                        <li><a data-toggle="pill" href="#Modal3">Emergency Details</a></li>
                    </ul>
                </div>

                <div class="panel">
                    <div class="panel-body">
                        <form class="form" action="php/Employee.php?EXECUTE=1&REF=<?php echo $REF; ?>" method="POST"
                              id="editform">
                            <div class="tab-content">
                                <div id="Modal1" class="tab-pane fade in active">

                                    <div class="col-lg-12 col-md-12">

                                        <div class="row">

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="control-label">Start Date</label>
                                                    <input type="text" name="start_date" class="form-control"
                                                           value="<?php if (isset($START_DATE)) {
                                                               echo $START_DATE;
                                                           } ?>">
                                                </div>
                                            </div>


                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="control-label">Position</label>
                                                    <select name="position" class="form-control" required>
                                                        <option <?php if (isset($POSITION)) {
                                                            if ($POSITION == 'Life Lead Gen') {
                                                                echo "selected";
                                                            }
                                                        } ?> value="Life Lead Gen">Life Lead Gen
                                                        </option>
                                                        <option <?php if (isset($POSITION)) {
                                                            if ($POSITION == 'Manager') {
                                                                echo "selected";
                                                            }
                                                        } ?> value="Manager">Manager
                                                        </option>
                                                        <option <?php if (isset($POSITION)) {
                                                            if ($POSITION == 'Closer') {
                                                                echo "selected";
                                                            }
                                                        } ?> value="Closer">Closer
                                                        </option>
                                                        <option <?php if (isset($POSITION)) {
                                                            if ($POSITION == 'Auditor') {
                                                                echo "selected";
                                                            }
                                                        } ?> value="Auditor">Auditor
                                                        </option>
                                                        <option <?php if (isset($POSITION)) {
                                                            if ($POSITION == 'Admin') {
                                                                echo "selected";
                                                            }
                                                        } ?> value="Admin">Admin
                                                        </option>
                                                        <option <?php if (isset($POSITION)) {
                                                            if ($POSITION == 'HR') {
                                                                echo "selected";
                                                            }
                                                        } ?> value="HR">HR
                                                        </option>
                                                        <option <?php if (isset($POSITION)) {
                                                            if ($POSITION == 'IT') {
                                                                echo "selected";
                                                            }
                                                        } ?> value="IT">IT
                                                        </option>
                                                        <option <?php if (isset($POSITION)) {
                                                            if ($POSITION == 'Director') {
                                                                echo "selected";
                                                            }
                                                        } ?> value="Director">Director
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="control-label">Company</label>
                                                    <select name="company" class="form-control" required>
                                                        <option value=""></option>
                                                        <option <?php if (isset($EMP_COMPANY)) {
                                                            if ($EMP_COMPANY == 'Project X') {
                                                                echo "selected";
                                                            }
                                                        } ?> value="Project X">Project X
                                                        </option>
                                                        <option <?php if (isset($EMP_COMPANY)) {
                                                            if ($EMP_COMPANY == 'Bluestone Protect') {
                                                                echo "selected";
                                                            }
                                                        } ?> value="Bluestone Protect">Bluestone Protect
                                                        </option>
                                                        <option <?php if (isset($EMP_COMPANY)) {
                                                            if ($EMP_COMPANY == 'Life Assured') {
                                                                echo "selected";
                                                            }
                                                        } ?> value="Life Assured">Life Assured
                                                        </option>
                                                        <option <?php if (isset($EMP_COMPANY)) {
                                                            if ($EMP_COMPANY == 'The Review Bureau') {
                                                                echo "selected";
                                                            }
                                                        } ?> value="The Review Bureau">The Review Bureau
                                                        </option>
                                                        <option <?php if (isset($EMP_COMPANY)) {
                                                            if ($EMP_COMPANY == 'We Insure') {
                                                                echo "selected";
                                                            }
                                                        } ?> value="We Insure">We Insure
                                                        </option>
                                                        <option <?php if (isset($EMP_COMPANY)) {
                                                            if ($EMP_COMPANY == 'Protect Family Plans') {
                                                                echo "selected";
                                                            }
                                                        } ?> value="Protect Family Plans">Protect Family Plans
                                                        </option>
                                                        <option <?php if (isset($EMP_COMPANY)) {
                                                            if ($EMP_COMPANY == 'Protected Life Ltd') {
                                                                echo "selected";
                                                            }
                                                        } ?> value="Protected Life Ltd">Protected Life Ltd
                                                        </option>
                                                        <option <?php if (isset($EMP_COMPANY)) {
                                                            if ($EMP_COMPANY == 'The Financial Assessment Centre') {
                                                                echo "selected";
                                                            }
                                                        } ?> value="The Financial Assessment Centre">The Financial
                                                            Assessment Centre
                                                        </option>
                                                        <option <?php if (isset($EMP_COMPANY)) {
                                                            if ($EMP_COMPANY == 'Assured Protect and Mortgages') {
                                                                echo "selected";
                                                            }
                                                        } ?> value="Assured Protect and Mortgages">Assured Protect and
                                                            Mortgages
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="control-label">Title</label>
                                                    <select name="title" class="form-control">
                                                        <option value="<?php if (isset($TITLE)) {
                                                            echo $TITLE;
                                                        } ?>"><?php if (isset($TITLE)) {
                                                                echo $TITLE;
                                                            } ?></option>
                                                        <option value="Mr">Mr</option>
                                                        <option value="Mrs">Mrs</option>
                                                        <option value="Ms">Ms</option>
                                                        <option value="Miss">Miss</option>

                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="control-label">First Name</label>
                                                    <input type="text" name="firstname" class="form-control"
                                                           value="<?php if (isset($FIRSTNAME)) {
                                                               echo $FIRSTNAME;
                                                           } ?>">
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="control-label">Last Name</label>
                                                    <input type="text" name="lastname" class="form-control"
                                                           value="<?php if (isset($LASTNAME)) {
                                                               echo $LASTNAME;
                                                           } ?>">
                                                </div>
                                            </div>


                                        </div>

                                        <div class="row">

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="control-label">DOB</label>
                                                    <input type="text" name="dob" class="form-control"
                                                           id="datepickerEdit" value="<?php if (isset($ORIGDOB)) {
                                                        echo $ORIGDOB;
                                                    } ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="control-label">Campaign</label>
                                                    <select name="campaign" class="form-control" required>
                                                        <option
                                                            value="1300" <?php if (isset($campaign) && $campaign == 1300) {
                                                            echo 'selected';
                                                        } ?> >1300
                                                        </option>
                                                        <option
                                                            value="1700" <?php if (isset($campaign) && $campaign == 1700) {
                                                            echo 'selected';
                                                        } ?> >1700
                                                        </option>
                                                        <option
                                                            value="9996" <?php if (isset($campaign) && $campaign == 9996) {
                                                            echo 'selected';
                                                        } ?> >9996
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="control-label">NI</label>
                                                    <input value="<?php if (isset($NI_NUM)) {
                                                        echo $NI_NUM;
                                                    } ?> " type="text" name="ni_num" id="ni_num" class="form-control"
                                                           pattern="[A-Za-z0-9]{2}-[0-9]{2}-[0-9]{2}-[0-9]{2}-[a-zA-Z]{1}"
                                                           title="Correct format JH-55-55-55-X"
                                                           placeholder="JH-55-55-55-X">
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="control-label">ID Provided</label>
                                                    <select name="id_provided" class="form-control" required>
                                                        <option <?php if (isset($ID_PROVIDED)) {
                                                            if ($ID_PROVIDED == '1') {
                                                                echo "selected";
                                                            }
                                                        } ?> value="1">Passport Number
                                                        </option>
                                                        <option <?php if (isset($ID_PROVIDED)) {
                                                            if ($ID_PROVIDED == '2') {
                                                                echo "selected";
                                                            }
                                                        } ?> value="2">Driving License Number
                                                        </option>
                                                        <option <?php if (isset($ID_PROVIDED)) {
                                                            if ($ID_PROVIDED == '3') {
                                                                echo "selected";
                                                            }
                                                        } ?> value="3">Bank Card Check
                                                        </option>
                                                        <option <?php if (isset($ID_PROVIDED)) {
                                                            if ($ID_PROVIDED == '4') {
                                                                echo "selected";
                                                            }
                                                        } ?> value="None">None
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="control-label">ID Details</label>
                                                    <input value="<?php if (isset($ID_DETAILS)) {
                                                        echo $ID_DETAILS;
                                                    } ?>" type="text" name="id_details" id="ni_num"
                                                           class="form-control">
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="control-label">Change Reason</label>
                                                    <select name="change" class="form-control" required>
                                                        <option value="Updated employee details">Updated employee
                                                            details
                                                        </option>
                                                        <option value="Updated contact details">Updated contact
                                                            details
                                                        </option>
                                                        <option value="Updated emergency details">Updated emergency
                                                            details
                                                        </option>
                                                        <option value="New Position">New Position</option>
                                                        <option value="Admin Change">Admin Change</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div id="Modal2" class="tab-pane fade">

                                    <div class="row">

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label class="control-label">Mobile</label>
                                                <input type="text" name="mob" class="form-control"
                                                       value="<?php if (isset($MOB)) {
                                                           echo $MOB;
                                                       } ?>">
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label class="control-label">Tel</label>
                                                <input type="text" name="tel" class="form-control"
                                                       value="<?php if (isset($TEL)) {
                                                           echo $TEL;
                                                       } ?>">
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label class="control-label">Email</label>
                                                <input type="text" name="email" class="form-control"
                                                       value="<?php if (isset($EMAIL)) {
                                                           echo $EMAIL;
                                                       } ?>">
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="control-label">Address Line 1</label>
                                                <input type="text" name="add1" class="form-control"
                                                       value="<?php if (isset($ADD1)) {
                                                           echo $ADD1;
                                                       } ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="control-label">Address Line 2</label>
                                                <input type="text" name="add2" class="form-control"
                                                       value="<?php if (isset($ADD2)) {
                                                           echo $ADD2;
                                                       } ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="control-label">Address Line 3</label>
                                                <input type="text" name="add3" class="form-control"
                                                       value="<?php if (isset($ADD3)) {
                                                           echo $ADD3;
                                                       } ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="control-label">Town</label>
                                                <input type="text" name="town" class="form-control"
                                                       value="<?php if (isset($TOWN)) {
                                                           echo $TOWN;
                                                       } ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="control-label">Post Code</label>
                                                <input type="text" name="postal" class="form-control"
                                                       value="<?php if (isset($POSTAL)) {
                                                           echo $POSTAL;
                                                       } ?>">
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div id="Modal3" class="tab-pane fade">

                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label class="control-label">Contact Name</label>
                                                <input type="text" name="contact_name" class="form-control"
                                                       value="<?php if (isset($CON_NAME)) {
                                                           echo $CON_NAME;
                                                       } ?>">
                                            </div>
                                        </div>


                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label class="control-label">Contact Number</label>
                                                <input type="text" name="contact_num" class="form-control"
                                                       value="<?php if (isset($CON_NUM)) {
                                                           echo $CON_NUM;
                                                       } ?>">
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label class="control-label">Relationship</label>
                                                <input type="text" name="contact_relationship" class="form-control"
                                                       value="<?php if (isset($CON_REL)) {
                                                           echo $CON_REL;
                                                       } ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="control-label">Address</label>
                                                <textarea name="contact address" class="form-control"
                                                          rows="5"><?php if (isset($CON_ADD)) {
                                                        echo $CON_ADD;
                                                    } ?></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="control-label">Medical Conditions</label>
                                                <textarea name="medical" class="form-control" rows="5"
                                                          placeholder="Any conditions if so, what? And any treatment/medication required? Including any allergies"><?php if (isset($MEDICAL)) {
                                                        echo $MEDICAL;
                                                    } ?></textarea>
                                            </div>
                                        </div>
                                    </div>


                                </div>

                            </div>

                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> Save</button>
                <script>
                    document.querySelector('#editform').addEventListener('submit', function (e) {
                        var form = this;
                        e.preventDefault();
                        swal({
                                title: "Edit Employee?",
                                text: "Confirm to update employee details!",
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
                                        title: 'Updated!',
                                        text: 'Employee updated!',
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
                </form>
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="HireModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">You are now going to employ <?php echo $NAME; ?></h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <ul class="nav nav-pills nav-justified">
                        <li class="active"><a data-toggle="pill" href="#Modal1">Employee</a></li>
                    </ul>
                </div>

                <div class="panel">
                    <div class="panel-body">
                        <form class="hireform" action="php/Employee.php?EXECUTE=5&REF=<?php echo $REF; ?>" method="POST"
                              id="hireform">
                            <div class="tab-content">
                                <div id="Modal1" class="tab-pane fade in active">

                                    <div class="col-lg-12 col-md-12">

                                        <div class="row">

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="control-label">Start Date</label>
                                                    <input type="text" readonly name="start_date" class="form-control"
                                                           value="<?php if (isset($START_DATE)) {
                                                               echo $START_DATE;
                                                           } ?>">
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="control-label">Finish Date</label>
                                                    <input type="text" name="finish_date" class="form-control" readonly
                                                           value="<?php echo $date = date('Y-m-d'); ?>">
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="control-label">Position</label>
                                                    <input type="text" readonly name="position" class="form-control"
                                                           value="<?php if (isset($POSITION)) {
                                                               echo $POSITION;
                                                           } ?>">
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="control-label">Title</label>
                                                    <select readonly name="title" class="form-control">
                                                        <option value="<?php if (isset($TITLE)) {
                                                            echo $TITLE;
                                                        } ?>"><?php if (isset($TITLE)) {
                                                                echo $TITLE;
                                                            } ?></option>

                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="control-label">First Name</label>
                                                    <input readonly type="text" name="firstname" class="form-control"
                                                           value="<?php if (isset($FIRSTNAME)) {
                                                               echo $FIRSTNAME;
                                                           } ?>">
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="control-label">Last Name</label>
                                                    <input readonly type="text" name="lastname" class="form-control"
                                                           value="<?php if (isset($LASTNAME)) {
                                                               echo $LASTNAME;
                                                           } ?>">
                                                </div>
                                            </div>


                                        </div>

                                        <div class="row">

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="control-label">DOB</label>
                                                    <input readonly type="text" name="dob" class="form-control"
                                                           id="datepickerEdit" value="<?php if (isset($ORIGDOB)) {
                                                        echo $ORIGDOB;
                                                    } ?>">
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Hire Reason</label>
                                                        <textarea name="notes" class="form-control" rows="5"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>


                            </div>

                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> HIRE!</button>
                <script>
                    document.querySelector('#hireform').addEventListener('submit', function (e) {
                        var form = this;
                        e.preventDefault();
                        swal({
                                title: "Hire Employee?",
                                text: "Confirm to hire employee!",
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
                                        title: 'Hired!',
                                        text: 'Employee updated!',
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
                </form>
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="FireModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">You are now going to fire <?php echo $NAME; ?></h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <ul class="nav nav-pills nav-justified">
                        <li class="active"><a data-toggle="pill" href="#Modal1">Employee</a></li>
                    </ul>
                </div>

                <div class="panel">
                    <div class="panel-body">
                        <form class="fireform" action="php/Employee.php?EXECUTE=3&REF=<?php echo $REF; ?>" method="POST"
                              id="fireform">
                            <div class="tab-content">
                                <div id="Modal1" class="tab-pane fade in active">

                                    <div class="col-lg-12 col-md-12">

                                        <div class="row">

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="control-label">Start Date</label>
                                                    <input type="text" readonly name="start_date" class="form-control"
                                                           value="<?php if (isset($START_DATE)) {
                                                               echo $START_DATE;
                                                           } ?>">
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="control-label">Finish Date</label>
                                                    <input type="text" name="finish_date" class="form-control"
                                                           value="<?php echo $date = date('Y-m-d'); ?>">
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="control-label">Position</label>
                                                    <input type="text" readonly name="position" class="form-control"
                                                           value="<?php if (isset($POSITION)) {
                                                               echo $POSITION;
                                                           } ?>">
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="control-label">Title</label>
                                                    <select readonly name="title" class="form-control">
                                                        <option value="<?php if (isset($TITLE)) {
                                                            echo $TITLE;
                                                        } ?>"><?php if (isset($TITLE)) {
                                                                echo $TITLE;
                                                            } ?></option>

                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="control-label">First Name</label>
                                                    <input readonly type="text" name="firstname" class="form-control"
                                                           value="<?php if (isset($FIRSTNAME)) {
                                                               echo $FIRSTNAME;
                                                           } ?>">
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="control-label">Last Name</label>
                                                    <input readonly type="text" name="lastname" class="form-control"
                                                           value="<?php if (isset($LASTNAME)) {
                                                               echo $LASTNAME;
                                                           } ?>">
                                                </div>
                                            </div>


                                        </div>

                                        <div class="row">

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="control-label">DOB</label>
                                                    <input readonly type="text" name="dob" class="form-control"
                                                           id="datepickerEdit" value="<?php if (isset($ORIGDOB)) {
                                                        echo $ORIGDOB;
                                                    } ?>">
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Fire Reason</label>
                                                        <textarea name="change" class="form-control"
                                                                  rows="5"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>


                            </div>

                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> FIRE!</button>
                <script>
                    document.querySelector('#fireform').addEventListener('submit', function (e) {
                        var form = this;
                        e.preventDefault();
                        swal({
                                title: "Fire Employee?",
                                text: "Confirm to fire employee!",
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
                                        title: 'Fire!',
                                        text: 'Employee updated!',
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
                </form>
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>
<?php if (isset($RETURN)) {
if ($RETURN == 'EDITMODAL') { ?>
<div class="modal fade" id="EditBook" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Change a Holiday for <?php echo $NAME; ?></h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <ul class="nav nav-pills nav-justified">
                        <li class="active"><a data-toggle="pill" href="#Modal6">Change a Holiday</a></li>
                    </ul>
                </div>

                <div class="panel">
                    <div class="panel-body">
                        <form class="form"
                              action="php/Employee.php?EXECUTE=8&HOLREF=<?php echo $HOL_REF; ?>&REF=<?php echo $REF; ?>&NAME=<?php echo "$FIRSTNAME $LASTNAME"; ?>"
                              method="POST" id="EditHOL">
                            <div class="tab-content">
                                <div id="Modal6" class="tab-pane fade in active">

                                    <div class="col-lg-12 col-md-12">

                                        <div class="row">

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="control-label">Start Date</label>
                                                    <input type="text" name="HOL_START" id="HOL_START"
                                                           class="form-control" value="<?php if (isset($HOL_START)) {
                                                        echo $HOL_START;
                                                    } ?>" required="">
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="control-label">End Date</label>
                                                    <input type="text" name="HOL_END" id="HOL_END" class="form-control"
                                                           value="<?php if (isset($HOL_END)) {
                                                               echo $HOL_END;
                                                           } ?>" required="">
                                                </div>
                                            </div>


                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label class="control-label">Reason for Holiday</label>
                                                    <textarea name="HOL_REASON" class="form-control"
                                                              rows="5"><?php if (isset($HOL_REASON)) {
                                                            echo $HOL_REASON;
                                                        } ?></textarea>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>


                            </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> Edit Booked Holiday
                    </button>
                    <script>
                        document.querySelector('#EditHOL').addEventListener('submit', function (e) {
                            var form = this;
                            e.preventDefault();
                            swal({
                                    title: "Edit booked holiday?",
                                    text: "Confirm to edit holiday request!",
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
                                            title: 'Checking availability!',
                                            text: 'Checking Calendar!',
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
                    </form>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php }
    } ?>
    <div class="modal fade" id="BookModal" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Holiday Request for <?php echo $NAME; ?></h4>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <ul class="nav nav-pills nav-justified">
                            <li class="active"><a data-toggle="pill" href="#Modal6">Holiday Request Form</a></li>
                            <li><a data-toggle="pill" href="#Modal5">Booked Holidays</a></li>
                        </ul>
                    </div>

                    <div class="panel">
                        <div class="panel-body">
                            <form class="form"
                                  action="php/Employee.php?EXECUTE=6&REF=<?php echo $REF; ?>&NAME=<?php echo "$FIRSTNAME $LASTNAME"; ?>"
                                  method="POST" id="HOLform">
                                <div class="tab-content">
                                    <div id="Modal6" class="tab-pane fade in active">

                                        <div class="col-lg-12 col-md-12">

                                            <div class="row">

                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Start Date</label>
                                                        <input type="text" name="HOL_START" id="HOL_START"
                                                               class="form-control"
                                                               value="<?php if (isset($HOL_START)) {
                                                                   echo $HOL_START;
                                                               } ?>" required="">
                                                    </div>
                                                </div>

                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label class="control-label">End Date</label>
                                                        <input type="text" name="HOL_END" id="HOL_END"
                                                               class="form-control" value="<?php if (isset($HOL_END)) {
                                                            echo $HOL_END;
                                                        } ?>" required="">
                                                    </div>
                                                </div>


                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Reason for Holiday</label>
                                                        <textarea name="HOL_REASON" class="form-control"
                                                                  rows="5"><?php if (isset($HOL_REASON)) {
                                                                echo $HOL_REASON;
                                                            } ?></textarea>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>


                                    <div id="Modal5" class="tab-pane fade">
                                        <div class="row">


                                        </div>

                                    </div>

                                </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> Authorise
                        </button>
                        <script>
                            document.querySelector('#HOLform').addEventListener('submit', function (e) {
                                var form = this;
                                e.preventDefault();
                                swal({
                                        title: "Authorise?",
                                        text: "Confirm to authorise holiday request!",
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
                                                title: 'Checking availability!',
                                                text: 'Checking Calendar!',
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
                        </form>
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i>
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
        <script type="text/javascript" language="javascript" src="/resources/lib/jquery/jquery-3.0.0.min.js"></script>
        <script type="text/javascript" language="javascript"
                src="/resources/lib/jquery-ui-1.11.4/jquery-ui.min.js"></script>
        <script src="/resources/lib/sweet-alert/sweet-alert.min.js"></script>
        <script src="/resources/templates/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>

        <script>
            $(document).ready(function () {
                if (window.location.href.split('#').length > 1) {
                    $tab_to_nav_to = window.location.href.split('#')[1];
                    if ($(".nav-pills > li > a[href='#" + $tab_to_nav_to + "']").length) {
                        $(".nav-pills > li > a[href='#" + $tab_to_nav_to + "']")[0].click();
                    }
                }
            });

        </script>
        <script>
            $(function () {
                $("#HOL_START").datepicker({
                    dateFormat: 'yy-mm-dd',
                    changeMonth: true,
                    changeYear: true,
                    yearRange: "-100:-0"
                });
            });
        </script>
        <script>
            $(function () {
                $("#HOL_END").datepicker({
                    dateFormat: 'yy-mm-dd',
                    changeMonth: true,
                    changeYear: true,
                    yearRange: "-100:-0"
                });
            });
        </script>
        <?php if (isset($RETURN)) {
            if ($RETURN == 'EDITMODAL') { ?>

                <script type="text/javascript">
                    $(document).ready(function () {

                        $('#EditBook').modal('show');

                    });
                </script>
                <?php
            }
        }
        ?>
</body>
</html>
