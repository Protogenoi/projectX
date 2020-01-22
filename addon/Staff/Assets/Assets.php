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

require_once(__DIR__ . '/../../../classes/access_user/access_user_class.php');
$page_protect = new Access_user;
$page_protect->access_page(filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS), "", 10);
$hello_name = ($page_protect->user_full_name != "") ? $page_protect->user_full_name : $page_protect->user;

$USER_TRACKING = 0;

require_once(__DIR__ . '/../../../includes/user_tracking.php');

require_once(__DIR__ . '/../../../includes/time.php');

if (isset($FORCE_LOGOUT) && $FORCE_LOGOUT == 1) {
    $page_protect->log_out();
}

require_once(__DIR__ . '/../../../includes/adl_features.php');
require_once(__DIR__ . '/../../../includes/Access_Levels.php');

if (isset($fferror)) {
    if ($fferror == '1') {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }
}

if ($ffanalytics == '1') {
    require_once(__DIR__ . '/../../../app/analyticstracking.php');
}

require_once(__DIR__ . '/../../../classes/database_class.php');
require_once(__DIR__ . '/../../../class/login/login.php');

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

    header('Location: /../../../../index.php?AccessDenied&USER=' . $hello_name . '&COMPANY=' . $COMPANY_ENTITY);
    die;

}

$MONTH = filter_input(INPUT_GET, 'MONTH', FILTER_SANITIZE_SPECIAL_CHARS);
$YEAR = filter_input(INPUT_GET, 'YEAR', FILTER_SANITIZE_SPECIAL_CHARS);
$ASSETID = filter_input(INPUT_GET, 'ASSETID', FILTER_SANITIZE_SPECIAL_CHARS);
$DEVICE = filter_input(INPUT_GET, 'DEVICE', FILTER_SANITIZE_SPECIAL_CHARS);
$SEARCH = filter_input(INPUT_GET, 'SEARCH', FILTER_SANITIZE_SPECIAL_CHARS);

$RETURN = filter_input(INPUT_GET, 'RETURN', FILTER_SANITIZE_SPECIAL_CHARS);

if (isset($RETURN)) {
    $DATE = filter_input(INPUT_GET, 'DATE', FILTER_SANITIZE_SPECIAL_CHARS);
} else {
    $DATE = filter_input(INPUT_POST, 'DATE', FILTER_SANITIZE_SPECIAL_CHARS);
}
?>
<!DOCTYPE html>
<html lang="en">
<title>ADL | Company Assets</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="/resources/templates/ADL/main.css" type="text/css"/>
<link rel="stylesheet" href="/resources/templates/bootstrap-3.3.5-dist/css/bootstrap.min.css">
<link rel="stylesheet" href="/resources/templates/bootstrap-3.3.5-dist/css/bootstrap-theme.min.css">
<link rel="stylesheet" href="/resources/templates/bootstrap-3.3.5-dist/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/resources/lib/DataTable/datatables.min.css"/>
<link rel="stylesheet" href="/resources/templates/font-awesome/css/font-awesome.min.css"/>
<link rel="stylesheet" href="/resources/lib/jquery-ui-1.11.4/jquery-ui.min.css">
<link href="/img/favicon.ico" rel="icon" type="image/x-icon"/>
<script type="text/javascript" language="javascript"
        src="/resources/templates/fontawesome/svg-with-js/js/fontawesome-all.js"></script>
</head>
<body>

<?php require_once(__DIR__ . '/../../../includes/navbar.php'); ?>

<div class="container">

    <div class='notice notice-default' role='alert'>
        <h1><strong>
                <center>Asset Management</center>
            </strong></h1>
    </div>
    <br>
    <?php include('../php/Notifications.php'); ?>
    <br>
    <div class="row fixed-toolbar">
        <div class="col-xs-5">
            <a href="../Main_Menu.php" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
        <div class="col-xs-7">
            <div class="text-right">
                <?php if (isset($DEVICE)) { ?>
                    <a class="btn btn-warning" data-toggle="modal" data-target="#comp_modal" data-backdrop="static"
                       data-keyboard="false"><i class="fa fa-plus-square"></i> Add Item Details</a>
                <?php } else { ?>
                    <a class="btn btn-warning" data-toggle="modal" data-target="#AddModal" data-backdrop="static"
                       data-keyboard="false"><i class="fa fa-plus-square"></i> Add Item</a>
                <?php } ?>
                <a class="btn btn-info" href='?SEARCH=1'><i class="fa fa-search"></i> Search Inventory</a>
            </div>
        </div>
    </div>
    <br>

    <?php if (isset($ASSETID)) {
        if ($ASSETID > '0') {
            if (isset($DEVICE)) {
                ?>

                <div class="modal fade" id="comp_modal" role="dialog">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Add asset details</h4>
                            </div>
                            <div class="modal-body">

                                <div class="row">
                                    <ul class="nav nav-pills nav-justified">
                                        <li class="active"><a data-toggle="pill" href="#Modal1">Add Item</a></li>
                                    </ul>
                                </div>

                                <div class="panel">
                                    <div class="panel-body">

                                        <?php

                                        switch ($DEVICE) {
                                            case "Computer";
                                                $EXECUTE_ID = 2;
                                                break;
                                            case "Keyboard";
                                                $EXECUTE_ID = 3;
                                                break;
                                            case "Mouse";
                                                $EXECUTE_ID = 4;
                                                break;
                                            case "Headset";
                                                $EXECUTE_ID = 5;
                                                break;
                                            case "Hardphone";
                                                $EXECUTE_ID = 6;
                                                break;
                                            case "Network Device";
                                                $EXECUTE_ID = 7;
                                                break;
                                            case "Printer";
                                                $EXECUTE_ID = 8;
                                                break;
                                            case "Monitor";
                                                $EXECUTE_ID = 9;
                                                break;
                                            default:
                                                $EXECUTE_ID = 0;

                                        }

                                        ?>

                                        <form class="form"
                                              action="../php/Assets.php?EXECUTE=<?php echo $EXECUTE_ID; ?>&ASSETID=<?php echo $ASSETID; ?>"
                                              method="POST" id="ASSETform">

                                            <div class="tab-content">
                                                <div id="Modal1" class="tab-pane fade in active">

                                                    <div class="col-lg-12 col-md-12">

                                                        <div class="row">
                                                            <?php if ($DEVICE == 'Computer') { ?>
                                                                <div class="col-sm-4">
                                                                    <div class="form-group">
                                                                        <label class="control-label">OS</label>
                                                                        <input type="text" name="OS"
                                                                               class="form-control" value=""
                                                                               placeholder="Windows/Linux/MAC">
                                                                    </div>
                                                                </div>


                                                                <div class="col-sm-4">
                                                                    <div class="form-group">
                                                                        <label class="control-label">MAC</label>
                                                                        <input type="text" name="MAC"
                                                                               class="form-control" value=""
                                                                               placeholder="Ethernet Address">
                                                                    </div>
                                                                </div>

                                                                <div class="col-sm-4">
                                                                    <div class="form-group">
                                                                        <label class="control-label">RAM</label>
                                                                        <input type="text" name="RAM"
                                                                               class="form-control" value=""
                                                                               placeholder="Ethernet Address">
                                                                    </div>
                                                                </div>

                                                                <div class="col-sm-4">
                                                                    <div class="form-group">
                                                                        <label class="control-label">Hostname</label>
                                                                        <input type="text" name="HOSTNAME"
                                                                               class="form-control" value=""
                                                                               placeholder="BIOS Name">
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label">Notes</label>
                                                                            <textarea name="NOTES" class="form-control"
                                                                                      rows="5"
                                                                                      placeholder="Any other details"></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php }

                                                            if ($DEVICE == 'Keyboard' || $DEVICE == 'Mouse') { ?>
                                                                <div class="row">
                                                                    <div class="col-sm-4">
                                                                        <div class="form-group">
                                                                            <label class="control-label">Connection
                                                                                Type</label>
                                                                            <select name="CONNECTION"
                                                                                    class="form-control" required>
                                                                                <option value=""></option>
                                                                                <option value="USB">USB</option>
                                                                                <option value="Serial">Serial</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label">Notes</label>
                                                                            <textarea name="NOTES" class="form-control"
                                                                                      rows="5"
                                                                                      placeholder="Any other details"></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            <?php }
                                                            if ($DEVICE == 'Headset' || $DEVICE == 'Monitor') {

                                                                if ($DEVICE == 'Headset') {


                                                                    $head_QRY = $pdo->prepare("select CONCAT(firstname, ' ', lastname) AS HEADSETNAME FROM employee_details");
                                                                    $head_QRY->execute();
                                                                    if ($head_QRY->rowCount() > 0) { ?>

                                                                        <div class="row">
                                                                            <div class="col-sm-4">
                                                                                <div class="form-group">
                                                                                    <label class="control-label">Assign
                                                                                        to</label>
                                                                                    <select name="HEADSETNAME"
                                                                                            class="form-control"
                                                                                            required>
                                                                                        <option value="Unassigned">
                                                                                            Unassigned
                                                                                        </option>
                                                                                        <?php
                                                                                        while ($result = $head_QRY->fetch(PDO::FETCH_ASSOC)) {
                                                                                            $HEADSETNAME = $result['HEADSETNAME'];
                                                                                            ?>
                                                                                            <option
                                                                                                value="<?php if (isset($HEADSETNAME)) {
                                                                                                    echo $HEADSETNAME;
                                                                                                } ?>"><?php if (isset($HEADSETNAME)) {
                                                                                                    echo $HEADSETNAME;
                                                                                                } ?></option>

                                                                                            <?php


                                                                                        } ?>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    <?php }
                                                                } ?>

                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label">Notes</label>
                                                                            <textarea name="NOTES" class="form-control"
                                                                                      rows="5"
                                                                                      placeholder="Any other details"></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            <?php }
                                                            if ($DEVICE == 'Hardphone') { ?>

                                                                <div class="row">
                                                                    <div class="col-sm-4">
                                                                        <div class="form-group">
                                                                            <label class="control-label">MAC</label>
                                                                            <input type="text" name="MAC"
                                                                                   class="form-control" value=""
                                                                                   placeholder="Ethernet Address">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label">Notes</label>
                                                                            <textarea name="NOTES" class="form-control"
                                                                                      rows="5"
                                                                                      placeholder="Any other details (condition/fault reason)"></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            <?php }
                                                            if ($DEVICE == 'Network Device' || $DEVICE == 'Printer') { ?>
                                                                <div class="col-sm-4">
                                                                    <div class="form-group">
                                                                        <label class="control-label">IP</label>
                                                                        <input type="text" name="IP"
                                                                               class="form-control" value=""
                                                                               placeholder="192.168.1.1">
                                                                    </div>
                                                                </div>


                                                                <div class="col-sm-4">
                                                                    <div class="form-group">
                                                                        <label class="control-label">MAC</label>
                                                                        <input type="text" name="MAC"
                                                                               class="form-control" value=""
                                                                               placeholder="Ethernet Address">
                                                                    </div>
                                                                </div>

                                                                <div class="col-sm-4">
                                                                    <div class="form-group">
                                                                        <label class="control-label">Hostname</label>
                                                                        <input type="text" name="HOSTNAME"
                                                                               class="form-control" value=""
                                                                               placeholder="Hostname">
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label">Notes</label>
                                                                            <textarea name="NOTES" class="form-control"
                                                                                      rows="5"
                                                                                      placeholder="Any other details"></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Save</button>

                                <script>
                                    document.querySelector('#ASSETform').addEventListener('submit', function (e) {
                                        var form = this;
                                        e.preventDefault();
                                        swal({
                                                title: "Add Asset details?",
                                                text: "Confirm to add asset details to inventory!",
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
                                                        title: 'Asset updated!',
                                                        text: 'Asset details Updated!',
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

                                <button type="button" class="btn btn-default" data-dismiss="modal"><i
                                        class="fa fa-times"></i> Close
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php


            }


        }
    }

    if (isset($SEARCH)) { ?>
        <form>
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="control-label">Category</label>
                        <select name="SEARCH" class="form-control" onchange="this.form.submit()" required>
                            <option value="">Search by category</option>
                            <option <?php if (isset($SEARCH)) {
                                if ($SEARCH == '2') {
                                    echo "selected";
                                }
                            } ?> value="2">Computer
                            </option>
                            <option <?php if (isset($SEARCH)) {
                                if ($SEARCH == '3') {
                                    echo "selected";
                                }
                            } ?> value="3">Keyboard
                            </option>
                            <option <?php if (isset($SEARCH)) {
                                if ($SEARCH == '4') {
                                    echo "selected";
                                }
                            } ?> value="4">Mouse
                            </option>
                            <option <?php if (isset($SEARCH)) {
                                if ($SEARCH == '5') {
                                    echo "selected";
                                }
                            } ?> value="5">Headset
                            </option>
                            <option <?php if (isset($SEARCH)) {
                                if ($SEARCH == '6') {
                                    echo "selected";
                                }
                            } ?> value="6">Hardphone
                            </option>
                            <option <?php if (isset($SEARCH)) {
                                if ($SEARCH == '7') {
                                    echo "selected";
                                }
                            } ?> value="7">Network Device
                            </option>
                            <option <?php if (isset($SEARCH)) {
                                if ($SEARCH == '8') {
                                    echo "selected";
                                }
                            } ?> value="8">Printer
                            </option>
                            <option <?php if (isset($SEARCH)) {
                                if ($SEARCH == '9') {
                                    echo "selected";
                                }
                            } ?> value="9">Monitor
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </form>
        <?php if ($SEARCH == '1') { ?>

            <table id="assets" class="display" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th></th>
                    <th>Updated</th>
                    <th>Asset</th>
                    <th>Manufacturer</th>
                    <th>Device</th>
                    <th>Fault</th>
                    <th>Fault Reason</th>
                    <th>View</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th></th>
                    <th>Updated</th>
                    <th>Asset</th>
                    <th>Manufacturer</th>
                    <th>Device</th>
                    <th>Fault</th>
                    <th>Fault Reason</th>
                    <th>View</th>
                </tr>
                </tfoot>
            </table>

        <?php }

        if ($SEARCH == '2') { ?>
            <h3><span class="label label-info">Computers</span></h3>
            <br>
            <table id="int_computers" class="display" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th></th>
                    <th>Date</th>
                    <th>Asset</th>
                    <th>OS</th>
                    <th>MAC</th>
                    <th>RAM</th>
                    <th>Hostname</th>
                    <th>Manufacturer</th>
                    <th>View</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th></th>
                    <th>Date</th>
                    <th>Asset</th>
                    <th>OS</th>
                    <th>MAC</th>
                    <th>RAM</th>
                    <th>Hostname</th>
                    <th>Manufacturer</th>
                    <th>View</th>
                </tr>
                </tfoot>
            </table>

            <?php
        }

        if ($SEARCH == '3') { ?>
            <h3><span class="label label-info">Keyboards</span></h3>
            <br>
            <table id="int_keyboards" class="display" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th></th>
                    <th>Date</th>
                    <th>Asset</th>
                    <th>Connection</th>
                    <th>Manufacturer</th>
                    <th>View</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th></th>
                    <th>Date</th>
                    <th>Asset</th>
                    <th>Connection</th>
                    <th>Manufacturer</th>
                    <th>View</th>
                </tr>
                </tfoot>
            </table>

            <?php
        }


        if ($SEARCH == '4') { ?>
            <h3><span class="label label-info">Mice</span></h3>
            <br>
            <table id="int_mice" class="display" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th></th>
                    <th>Date</th>
                    <th>Asset</th>
                    <th>Connection</th>
                    <th>Manufacturer</th>
                    <th>View</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th></th>
                    <th>Date</th>
                    <th>Asset</th>
                    <th>Connection</th>
                    <th>Manufacturer</th>
                    <th>View</th>
                </tr>
                </tfoot>
            </table>

            <?php
        }
        if ($SEARCH == '5') { ?>
            <h3><span class="label label-info">Headsets</span></h3>
            <br>
            <table id="int_headsets" class="display" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th></th>
                    <th>Date</th>
                    <th>Asset</th>
                    <th>Employee</th>
                    <th>Manufacturer</th>
                    <th>View</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th></th>
                    <th>Date</th>
                    <th>Asset</th>
                    <th>Employee</th>
                    <th>Manufacturer</th>
                    <th>View</th>
                </tr>
                </tfoot>
            </table>

            <?php
        }
        if ($SEARCH == '6') { ?>
            <h3><span class="label label-info">IP Phones</span></h3>
            <br>
            <table id="int_phones" class="display" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th></th>
                    <th>Date</th>
                    <th>Asset</th>
                    <th>MAC</th>
                    <th>Manufacturer</th>
                    <th>View</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th></th>
                    <th>Date</th>
                    <th>Asset</th>
                    <th>MAC</th>
                    <th>Manufacturer</th>
                    <th>View</th>
                </tr>
                </tfoot>
            </table>

            <?php
        }
        if ($SEARCH == '7') { ?>
            <h3><span class="label label-info">Network</span></h3>
            <br>
            <table id="int_network" class="display" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th></th>
                    <th>Date</th>
                    <th>Asset</th>
                    <th>IP</th>
                    <th>MAC</th>
                    <th>Hostname</th>
                    <th>Manufacturer</th>
                    <th>View</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th></th>
                    <th>Date</th>
                    <th>Asset</th>
                    <th>OS</th>
                    <th>MAC</th>
                    <th>Hostname</th>
                    <th>Manufacturer</th>
                    <th>View</th>
                </tr>
                </tfoot>
            </table>

            <?php
        }
        if ($SEARCH == '8') { ?>
            <h3><span class="label label-info">Printer</span></h3>
            <br>
            <table id="int_printers" class="display" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th></th>
                    <th>Date</th>
                    <th>Asset</th>
                    <th>IP</th>
                    <th>MAC</th>
                    <th>Hostname</th>
                    <th>Manufacturer</th>
                    <th>View</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th></th>
                    <th>Date</th>
                    <th>Asset</th>
                    <th>OS</th>
                    <th>MAC</th>
                    <th>Hostname</th>
                    <th>Manufacturer</th>
                    <th>View</th>
                </tr>
                </tfoot>
            </table>

            <?php
        }
        if ($SEARCH == '9') { ?>
            <h3><span class="label label-info">Monitor</span></h3>
            <br>
            <table id="int_monitors" class="display" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th></th>
                    <th>Date</th>
                    <th>Asset</th>
                    <th>Manufacturer</th>
                    <th>View</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th></th>
                    <th>Date</th>
                    <th>Asset</th>
                    <th>Manufacturer</th>
                    <th>View</th>
                </tr>
                </tfoot>
            </table>

            <?php
        }
    }
    ?>

</div>

<div class="modal fade" id="AddModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Item to inventory stock</h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <ul class="nav nav-pills nav-justified">
                        <li class="active"><a data-toggle="pill" href="#Modal1">Add Item</a></li>
                    </ul>
                </div>

                <div class="panel">
                    <div class="panel-body">
                        <form class="form" action="../php/Assets.php?EXECUTE=1" method="POST" id="Addform">
                            <div class="tab-content">
                                <div id="Modal1" class="tab-pane fade in active">

                                    <div class="col-lg-12 col-md-12">
                                        <div class="row">

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="control-label">Asset Name</label>
                                                    <input type="text" name="ASSET_NAME" class="form-control" value=""
                                                           placeholder="Computer DELL XPS">
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="control-label">Manufacturer</label>
                                                    <input type="text" name="MANUFACTURER" class="form-control" value=""
                                                           placeholder="Dell/Intel">
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="control-label">Device</label>
                                                    <select name="DEVICE" class="form-control">
                                                        <option value=""></option>
                                                        <option value="Computer">Computer</option>
                                                        <option value="Keyboard">Keyboard</option>
                                                        <option value="Mouse">Mouse</option>
                                                        <option value="Headset">Headset</option>
                                                        <option value="Hardphone">Hardphone</option>
                                                        <option value="Network Device">Network Device</option>
                                                        <option value="Printer">Printer</option>
                                                        <option value="Monitor">Monitor</option>
                                                    </select>
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
                <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> Save</button>
                <script>
                    document.querySelector('#Addform').addEventListener('submit', function (e) {
                        var form = this;
                        e.preventDefault();
                        swal({
                                title: "Add Asset?",
                                text: "Confirm to add asset to inventory!",
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
                                        title: 'Asset added!',
                                        text: 'Inventory Updated!',
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

<script type="text/javascript" language="javascript" src="/resources/lib/jquery/jquery-3.0.0.min.js"></script>
<script type="text/javascript" language="javascript"
        src="/resources/lib/jquery-ui-1.11.4/external/jquery/jquery.js"></script>
<script type="text/javascript" src="/resources/lib/DataTable/datatables.min.js"></script>
<script src="/resources/lib/jquery-ui-1.11.4/jquery-ui.min.js"></script>
<script src="/resources/templates/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
<?php if (isset($SEARCH)) {
if ($SEARCH == '1') {
    ?>
    <script type="text/javascript" language="javascript">

        $(document).ready(function () {
            var table = $('#assets').DataTable({
                "response": true,
                "processing": true,
                "iDisplayLength": 25,
                "aLengthMenu": [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
                "language": {
                    "processing": "<div></div><div></div><div></div><div></div><div></div>"
                },
                "ajax": "/../../../addon/Staff/datatables/Assets.php?EXECUTE=1&USER=<?php echo $hello_name; ?>&TOKEN=<?php echo $TOKEN; ?>",
                "columns": [
                    {
                        "className": 'details-control',
                        "orderable": false,
                        "data": null,
                        "defaultContent": ''
                    },
                    {"data": "updated_date"},
                    {"data": "asset_name"},
                    {"data": "manufactorer"},
                    {"data": "device"},
                    {"data": "fault"},
                    {"data": "fault_reason"},
                    {
                        "data": "inv_id",
                        "render": function (data, type, full, meta) {
                            return '<a href="?RETURN=SELECTASSET&ASSETID=' + data + '"><i class="fa fa-search"></i></a>';
                        }
                    }
                ]
            });

        });
    </script>
<?php }
if ($SEARCH == '2') { ?>
    <script type="text/javascript" language="javascript">

        $(document).ready(function () {
            var table = $('#int_computers').DataTable({
                "response": true,
                "processing": true,
                "iDisplayLength": 25,
                "aLengthMenu": [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
                "language": {
                    "processing": "<div></div><div></div><div></div><div></div><div></div>"
                },
                "ajax": "/../../../addon/Staff/datatables/Assets.php?EXECUTE=2&USER=<?php echo $hello_name; ?>&TOKEN=<?php echo $TOKEN; ?>",
                "columns": [
                    {
                        "className": 'details-control',
                        "orderable": false,
                        "data": null,
                        "defaultContent": ''
                    },
                    {"data": "updated_date"},
                    {"data": "asset_name"},
                    {"data": "os"},
                    {"data": "mac"},
                    {"data": "ram"},
                    {"data": "hostname"},
                    {"data": "manufactorer"},
                    {
                        "data": "inv_id",
                        "render": function (data, type, full, meta) {
                            return '<a href="?RETURN=SELECTASSET&SEARCH=2&ASSETID=' + data + '"><i class="fa fa-search"></i></a>';
                        }
                    }
                ]
            });

        });
    </script>
<?php }
if ($SEARCH == '3') { ?>
    <script type="text/javascript" language="javascript">

        $(document).ready(function () {
            var table = $('#int_keyboards').DataTable({
                "response": true,
                "processing": true,
                "iDisplayLength": 25,
                "aLengthMenu": [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
                "language": {
                    "processing": "<div></div><div></div><div></div><div></div><div></div>"
                },
                "ajax": "/../../../addon/Staff/datatables/Assets.php?EXECUTE=3&USER=<?php echo $hello_name; ?>&TOKEN=<?php echo $TOKEN; ?>",
                "columns": [
                    {
                        "className": 'details-control',
                        "orderable": false,
                        "data": null,
                        "defaultContent": ''
                    },
                    {"data": "updated_date"},
                    {"data": "asset_name"},
                    {"data": "connection_type"},
                    {"data": "manufactorer"},
                    {
                        "data": "inv_id",
                        "render": function (data, type, full, meta) {
                            return '<a href="?RETURN=SELECTASSET&SEARCH=3&ASSETID=' + data + '"><i class="fa fa-search"></i></a>';
                        }
                    }
                ]
            });

        });
    </script>
<?php }
if ($SEARCH == '4') { ?>
    <script type="text/javascript" language="javascript">

        $(document).ready(function () {
            var table = $('#int_mice').DataTable({
                "response": true,
                "processing": true,
                "iDisplayLength": 25,
                "aLengthMenu": [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
                "language": {
                    "processing": "<div></div><div></div><div></div><div></div><div></div>"
                },
                "ajax": "/../../../addon/Staff/datatables/Assets.php?EXECUTE=4&USER=<?php echo $hello_name; ?>&TOKEN=<?php echo $TOKEN; ?>",
                "columns": [
                    {
                        "className": 'details-control',
                        "orderable": false,
                        "data": null,
                        "defaultContent": ''
                    },
                    {"data": "updated_date"},
                    {"data": "asset_name"},
                    {"data": "connection_type"},
                    {"data": "manufactorer"},
                    {
                        "data": "inv_id",
                        "render": function (data, type, full, meta) {
                            return '<a href="?RETURN=SELECTASSET&SEARCH=4&ASSETID=' + data + '"><i class="fa fa-search"></i></a>';
                        }
                    }
                ]
            });

        });
    </script>
<?php }
if ($SEARCH == '5') { ?>
    <script type="text/javascript" language="javascript">

        $(document).ready(function () {
            var table = $('#int_headsets').DataTable({
                "response": true,
                "processing": true,
                "iDisplayLength": 25,
                "aLengthMenu": [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
                "language": {
                    "processing": "<div></div><div></div><div></div><div></div><div></div>"
                },
                "ajax": "/../../../addon/Staff/datatables/Assets.php?EXECUTE=5&USER=<?php echo $hello_name; ?>&TOKEN=<?php echo $TOKEN; ?>",
                "columns": [
                    {
                        "className": 'details-control',
                        "orderable": false,
                        "data": null,
                        "defaultContent": ''
                    },
                    {"data": "updated_date"},
                    {"data": "asset_name"},
                    {"data": "assigned"},
                    {"data": "manufactorer"},
                    {
                        "data": "inv_id",
                        "render": function (data, type, full, meta) {
                            return '<a href="?RETURN=SELECTASSET&SEARCH=5&ASSETID=' + data + '"><i class="fa fa-search"></i></a>';
                        }
                    }
                ]
            });

        });
    </script>
<?php }
if ($SEARCH == '6') { ?>
    <script type="text/javascript" language="javascript">

        $(document).ready(function () {
            var table = $('#int_phones').DataTable({
                "response": true,
                "processing": true,
                "iDisplayLength": 25,
                "aLengthMenu": [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
                "language": {
                    "processing": "<div></div><div></div><div></div><div></div><div></div>"
                },
                "ajax": "/../../../addon/Staff/datatables/Assets.php?EXECUTE=6&USER=<?php echo $hello_name; ?>&TOKEN=<?php echo $TOKEN; ?>",
                "columns": [
                    {
                        "className": 'details-control',
                        "orderable": false,
                        "data": null,
                        "defaultContent": ''
                    },
                    {"data": "updated_date"},
                    {"data": "asset_name"},
                    {"data": "mac"},
                    {"data": "manufactorer"},
                    {
                        "data": "inv_id",
                        "render": function (data, type, full, meta) {
                            return '<a href="?RETURN=SELECTASSET&SEARCH=6&ASSETID=' + data + '"><i class="fa fa-search"></i></a>';
                        }
                    }
                ]
            });

        });
    </script>
<?php }
if ($SEARCH == '7') { ?>
    <script type="text/javascript" language="javascript">

        $(document).ready(function () {
            var table = $('#int_network').DataTable({
                "response": true,
                "processing": true,
                "iDisplayLength": 25,
                "aLengthMenu": [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
                "language": {
                    "processing": "<div></div><div></div><div></div><div></div><div></div>"
                },
                "ajax": "/../../../addon/Staff/datatables/Assets.php?EXECUTE=7&USER=<?php echo $hello_name; ?>&TOKEN=<?php echo $TOKEN; ?>",
                "columns": [
                    {
                        "className": 'details-control',
                        "orderable": false,
                        "data": null,
                        "defaultContent": ''
                    },
                    {"data": "updated_date"},
                    {"data": "asset_name"},
                    {"data": "ip"},
                    {"data": "mac"},
                    {"data": "hostname"},
                    {"data": "manufactorer"},
                    {
                        "data": "inv_id",
                        "render": function (data, type, full, meta) {
                            return '<a href="?RETURN=SELECTASSET&SEARCH=7&ASSETID=' + data + '"><i class="fa fa-search"></i></a>';
                        }
                    }
                ]
            });

        });
    </script>
<?php }
if ($SEARCH == '8') { ?>
    <script type="text/javascript" language="javascript">

        $(document).ready(function () {
            var table = $('#int_printers').DataTable({
                "response": true,
                "processing": true,
                "iDisplayLength": 25,
                "aLengthMenu": [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
                "language": {
                    "processing": "<div></div><div></div><div></div><div></div><div></div>"
                },
                "ajax": "/../../../addon/Staff/datatables/Assets.php?EXECUTE=8&USER=<?php echo $hello_name; ?>&TOKEN=<?php echo $TOKEN; ?>",
                "columns": [
                    {
                        "className": 'details-control',
                        "orderable": false,
                        "data": null,
                        "defaultContent": ''
                    },
                    {"data": "updated_date"},
                    {"data": "asset_name"},
                    {"data": "ip"},
                    {"data": "mac"},
                    {"data": "hostname"},
                    {"data": "manufactorer"},
                    {
                        "data": "inv_id",
                        "render": function (data, type, full, meta) {
                            return '<a href="?RETURN=SELECTASSET&SEARCH=8&ASSETID=' + data + '"><i class="fa fa-search"></i></a>';
                        }
                    }
                ]
            });

        });
    </script>
<?php }
if ($SEARCH == '9') { ?>
    <script type="text/javascript" language="javascript">

        $(document).ready(function () {
            var table = $('#int_monitors').DataTable({
                "response": true,
                "processing": true,
                "iDisplayLength": 25,
                "aLengthMenu": [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
                "language": {
                    "processing": "<div></div><div></div><div></div><div></div><div></div>"
                },
                "ajax": "/../../../addon/Staff/datatables/Assets.php?EXECUTE=9&USER=<?php echo $hello_name; ?>&TOKEN=<?php echo $TOKEN; ?>",
                "columns": [
                    {
                        "className": 'details-control',
                        "orderable": false,
                        "data": null,
                        "defaultContent": ''
                    },
                    {"data": "updated_date"},
                    {"data": "asset_name"},
                    {"data": "manufactorer"},
                    {
                        "data": "inv_id",
                        "render": function (data, type, full, meta) {
                            return '<a href="?RETURN=SELECTASSET&SEARCH=9&ASSETID=' + data + '"><i class="fa fa-search"></i></a>';
                        }
                    }
                ]
            });

        });
    </script>
<?php }
} ?>
<?php if (isset($RETURN)) {
    if ($RETURN == 'SELECTASSET') { ?>
        <div class="modal fade" id="update_modal" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Update asset details</h4>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <ul class="nav nav-pills nav-justified">
                                <li class="active"><a data-toggle="pill" href="#Modal1">Update Item</a></li>
                            </ul>
                        </div>

                        <?php

                        $database = new Database();

                        $database->query("SELECT asset_name, manufactorer, added_by, updated_by, updated_date, added_date, device, fault, fault_reason FROM inventory WHERE inv_id=:REF");
                        $database->bind(':REF', $ASSETID);
                        $database->execute();
                        $data2 = $database->single();

                        $EDIT_ASSET_NAME = $data2['asset_name'];
                        $EDIT_ASSET_MAN = $data2['manufactorer'];
                        $EDIT_ASSET_WHO = $data2['added_by'];
                        $EDIT_ASSET_UPWHO = $data2['updated_by'];
                        $EDIT_ASSET_DATE = $data2['added_date'];
                        $EDIT_ASSET_UPDATE = $data2['updated_date'];
                        $EDIT_ASSET_DEVICE = $data2['device'];
                        $EDIT_ASSET_FAULT = $data2['fault'];
                        $EDIT_ASSET_REASON = $data2['reason'];
                        $EDIT_ASSET_FAULT_NOTES = $data2['fault_reason'];


                        ?>

                        <div class="panel">
                            <div class="panel-body">

                                <?php

                                switch ($EDIT_ASSET_DEVICE) {
                                    case "Computer";
                                        $EXECUTE_ID = 2;
                                        break;
                                    case "Keyboard";
                                        $EXECUTE_ID = 3;
                                        break;
                                    case "Mouse";
                                        $EXECUTE_ID = 4;
                                        break;
                                    case "Headset";
                                        $EXECUTE_ID = 5;
                                        break;
                                    case "Hardphone";
                                        $EXECUTE_ID = 6;
                                        break;
                                    case "Network Device";
                                        $EXECUTE_ID = 7;
                                        break;
                                    case "Printer";
                                        $EXECUTE_ID = 8;
                                        break;
                                    case "Monitor";
                                        $EXECUTE_ID = 9;
                                        break;
                                    default:
                                        $EXECUTE_ID = 0;

                                }

                                if (isset($EXECUTE_ID)) {
                                    if ($EXECUTE_ID == '2') {
                                        $database->query("SELECT os, mac, hostname, ram, notes FROM int_computers WHERE inv_id=:REF");
                                        $database->bind(':REF', $ASSETID);
                                        $database->execute();
                                        $data3 = $database->single();

                                        $EDIT_ASSET_OS = $data3['os'];
                                        $EDIT_ASSET_MAC = $data3['mac'];
                                        $EDIT_ASSET_HOST = $data3['hostname'];
                                        $EDIT_ASSET_RAM = $data3['ram'];
                                        $EDIT_ASSET_NOTES = $data3['notes'];
                                    }
                                    if ($EXECUTE_ID == '3') {
                                        $database->query("SELECT connection_type, notes FROM int_keyboards WHERE inv_id=:REF");
                                        $database->bind(':REF', $ASSETID);
                                        $database->execute();
                                        $data3 = $database->single();

                                        $EDIT_ASSET_CON = $data3['connection_type'];
                                        $EDIT_ASSET_NOTES = $data3['notes'];
                                    }
                                    if ($EXECUTE_ID == '4') {
                                        $database->query("SELECT connection_type, notes FROM int_mice WHERE inv_id=:REF");
                                        $database->bind(':REF', $ASSETID);
                                        $database->execute();
                                        $data3 = $database->single();

                                        $EDIT_ASSET_CON = $data3['connection_type'];
                                        $EDIT_ASSET_NOTES = $data3['notes'];
                                    }
                                    if ($EXECUTE_ID == '5') {
                                        $database->query("SELECT notes FROM int_headsets WHERE inv_id=:REF");
                                        $database->bind(':REF', $ASSETID);
                                        $database->execute();
                                        $data3 = $database->single();

                                        $EDIT_ASSET_NOTES = $data3['notes'];
                                    }
                                    if ($EXECUTE_ID == '6') {
                                        $database->query("SELECT mac, notes FROM int_phones WHERE inv_id=:REF");
                                        $database->bind(':REF', $ASSETID);
                                        $database->execute();
                                        $data3 = $database->single();

                                        $EDIT_ASSET_NOTES = $data3['notes'];
                                        $EDIT_ASSET_MAC = $data3['mac'];
                                    }
                                    if ($EXECUTE_ID == '7') {
                                        $database->query("SELECT mac, notes, ip, hostname FROM int_network WHERE inv_id=:REF");
                                        $database->bind(':REF', $ASSETID);
                                        $database->execute();
                                        $data3 = $database->single();

                                        $EDIT_ASSET_IP = $data3['ip'];
                                        $EDIT_ASSET_HOST = $data3['hostname'];
                                        $EDIT_ASSET_NOTES = $data3['notes'];
                                        $EDIT_ASSET_MAC = $data3['mac'];
                                    }
                                    if ($EXECUTE_ID == '8') {
                                        $database->query("SELECT mac, notes, ip, hostname FROM int_printers WHERE inv_id=:REF");
                                        $database->bind(':REF', $ASSETID);
                                        $database->execute();
                                        $data3 = $database->single();

                                        $EDIT_ASSET_IP = $data3['ip'];
                                        $EDIT_ASSET_HOST = $data3['hostname'];
                                        $EDIT_ASSET_NOTES = $data3['notes'];
                                        $EDIT_ASSET_MAC = $data3['mac'];
                                    }
                                    if ($EXECUTE_ID == '9') {
                                        $database->query("SELECT notes FROM int_monitors WHERE inv_id=:REF");
                                        $database->bind(':REF', $ASSETID);
                                        $database->execute();
                                        $data3 = $database->single();

                                        $EDIT_ASSET_NOTES = $data3['notes'];
                                    }

                                }


                                ?>

                                <form class="form"
                                      action="../php/Assets.php?EXECUTE=<?php echo $EXECUTE_ID; ?>&ASSETID=<?php echo $ASSETID; ?>"
                                      method="POST" id="UPDATEASSETform">

                                    <div class="tab-content">
                                        <div id="Modal1" class="tab-pane fade in active">

                                            <div class="col-lg-12 col-md-12">


                                                <div class="row">

                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label class="control-label">Asset Name</label>
                                                            <input type="text" name="ASSET_NAME" class="form-control"
                                                                   value="<?php if (isset($EDIT_ASSET_NAME)) {
                                                                       echo $EDIT_ASSET_NAME;
                                                                   } ?>" placeholder="Computer DELL XPS">
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label class="control-label">Manufacturer</label>
                                                            <input type="text" name="MANUFACTURER" class="form-control"
                                                                   value="<?php if (isset($EDIT_ASSET_MAN)) {
                                                                       echo $EDIT_ASSET_MAN;
                                                                   } ?>" placeholder="Dell/Intel">
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label class="control-label">Device</label>
                                                            <select name="DEVICE" class="form-control">
                                                                <option <?php if (isset($EDIT_ASSET_DEVICE)) {
                                                                    if ($EDIT_ASSET_DEVICE == 'Computer') {
                                                                        echo "selected";
                                                                    }
                                                                } ?> value="Computer">Computer
                                                                </option>
                                                                <option <?php if (isset($EDIT_ASSET_DEVICE)) {
                                                                    if ($EDIT_ASSET_DEVICE == 'Keyboard') {
                                                                        echo "selected";
                                                                    }
                                                                } ?>value="Keyboard">Keyboard
                                                                </option>
                                                                <option <?php if (isset($EDIT_ASSET_DEVICE)) {
                                                                    if ($EDIT_ASSET_DEVICE == 'Mouse') {
                                                                        echo "selected";
                                                                    }
                                                                } ?> value="Mouse">Mouse
                                                                </option>
                                                                <option <?php if (isset($EDIT_ASSET_DEVICE)) {
                                                                    if ($EDIT_ASSET_DEVICE == 'Headset') {
                                                                        echo "selected";
                                                                    }
                                                                } ?> value="Headset">Headset
                                                                </option>
                                                                <option <?php if (isset($EDIT_ASSET_DEVICE)) {
                                                                    if ($EDIT_ASSET_DEVICE == 'Hardphone') {
                                                                        echo "selected";
                                                                    }
                                                                } ?> value="Hardphone">Hardphone
                                                                </option>
                                                                <option <?php if (isset($EDIT_ASSET_DEVICE)) {
                                                                    if ($EDIT_ASSET_DEVICE == 'Network Device') {
                                                                        echo "selected";
                                                                    }
                                                                } ?> value="Network Device">Network Device
                                                                </option>
                                                                <option <?php if (isset($EDIT_ASSET_DEVICE)) {
                                                                    if ($EDIT_ASSET_DEVICE == 'Printer') {
                                                                        echo "selected";
                                                                    }
                                                                } ?> value="Printer">Printer
                                                                </option>
                                                                <option <?php if (isset($EDIT_ASSET_DEVICE)) {
                                                                    if ($EDIT_ASSET_DEVICE == 'Monitor') {
                                                                        echo "selected";
                                                                    }
                                                                } ?> value="Monitor">Monitor
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="row">
                                                    <?php if ($EDIT_ASSET_DEVICE == 'Computer') { ?>
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label class="control-label">OS</label>
                                                                <input type="text" name="OS" class="form-control"
                                                                       value="<?php if (isset($EDIT_ASSET_OS)) {
                                                                           echo $EDIT_ASSET_OS;
                                                                       } ?>" placeholder="Windows/Linux/MAC">
                                                            </div>
                                                        </div>


                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label class="control-label">MAC</label>
                                                                <input type="text" name="MAC" class="form-control"
                                                                       value="<?php if (isset($EDIT_ASSET_MAC)) {
                                                                           echo $EDIT_ASSET_MAC;
                                                                       } ?>" placeholder="Ethernet Address">
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label class="control-label">RAM</label>
                                                                <input type="text" name="RAM" class="form-control"
                                                                       value="<?php if (isset($EDIT_ASSET_RAM)) {
                                                                           echo $EDIT_ASSET_RAM;
                                                                       } ?>" placeholder="Ethernet Address">
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label class="control-label">Hostname</label>
                                                                <input type="text" name="HOSTNAME" class="form-control"
                                                                       value="<?php if (isset($EDIT_ASSET_HOST)) {
                                                                           echo $EDIT_ASSET_HOST;
                                                                       } ?>" placeholder="BIOS Name">
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="form-group">
                                                                    <label class="control-label">Notes</label>
                                                                    <textarea name="NOTES" class="form-control" rows="5"
                                                                              placeholder="Any other details"><?php if (isset($EDIT_ASSET_NOTES)) {
                                                                            echo $EDIT_ASSET_NOTES;
                                                                        } ?></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php }

                                                    if ($EDIT_ASSET_DEVICE == 'Keyboard' || $EDIT_ASSET_DEVICE == 'Mouse') { ?>
                                                        <div class="row">
                                                            <div class="col-sm-4">
                                                                <div class="form-group">
                                                                    <label class="control-label">Connection Type</label>
                                                                    <select name="CONNECTION" class="form-control"
                                                                            required>
                                                                        <option <?php if (isset($EDIT_ASSET_CON)) {
                                                                            if ($EDIT_ASSET_CON == 'USB') {
                                                                                echo "selected";
                                                                            }
                                                                        } ?> value="USB">USB
                                                                        </option>
                                                                        <option <?php if (isset($EDIT_ASSET_CON)) {
                                                                            if ($EDIT_ASSET_CON == 'Serial') {
                                                                                echo "selected";
                                                                            }
                                                                        } ?> value="Serial">Serial
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="form-group">
                                                                    <label class="control-label">Notes</label>
                                                                    <textarea name="NOTES" class="form-control" rows="5"
                                                                              placeholder="Any other details"><?php if (isset($EDIT_ASSET_NOTES)) {
                                                                            echo $EDIT_ASSET_NOTES;
                                                                        } ?></textarea>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    <?php }
                                                    if ($EDIT_ASSET_DEVICE == 'Headset' || $EDIT_ASSET_DEVICE == 'Monitor') {

                                                        if ($EDIT_ASSET_DEVICE == 'Headset') {


                                                            $head_QRY = $pdo->prepare("select CONCAT(firstname, ' ', lastname) AS HEADSETNAME FROM employee_details");
                                                            $head_QRY->execute();
                                                            if ($head_QRY->rowCount() > 0) { ?>

                                                                <div class="row">
                                                                    <div class="col-sm-4">
                                                                        <div class="form-group">
                                                                            <label class="control-label">Assign
                                                                                to</label>
                                                                            <select name="HEADSETNAME"
                                                                                    class="form-control" required>
                                                                                <option value="Unassigned">Unassigned
                                                                                </option>
                                                                                <?php
                                                                                while ($result = $head_QRY->fetch(PDO::FETCH_ASSOC)) {
                                                                                    $HEADSETNAME = $result['HEADSETNAME'];
                                                                                    ?>
                                                                                    <option
                                                                                        value="<?php if (isset($HEADSETNAME)) {
                                                                                            echo $HEADSETNAME;
                                                                                        } ?>"><?php if (isset($HEADSETNAME)) {
                                                                                            echo $HEADSETNAME;
                                                                                        } ?></option>

                                                                                    <?php


                                                                                } ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php }
                                                        } ?>

                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="form-group">
                                                                    <label class="control-label">Notes</label>
                                                                    <textarea name="NOTES" class="form-control" rows="5"
                                                                              placeholder="Any other details"><?php if (isset($EDIT_ASSET_NOTES)) {
                                                                            echo $EDIT_ASSET_NOTES;
                                                                        } ?></textarea>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    <?php }

                                                    if ($EDIT_ASSET_DEVICE == 'Hardphone') { ?>

                                                        <div class="row">
                                                            <div class="col-sm-4">
                                                                <div class="form-group">
                                                                    <label class="control-label">MAC</label>
                                                                    <input type="text" name="MAC" class="form-control"
                                                                           value="<?php if (isset($EDIT_ASSET_MAC)) {
                                                                               echo $EDIT_ASSET_MAC;
                                                                           } ?>" placeholder="Ethernet Address">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="form-group">
                                                                    <label class="control-label">Notes</label>
                                                                    <textarea name="NOTES" class="form-control" rows="5"
                                                                              placeholder="Any other details (condition/fault reason)"><?php if (isset($EDIT_ASSET_NOTES)) {
                                                                            echo $EDIT_ASSET_NOTES;
                                                                        } ?></textarea>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    <?php }
                                                    if ($EDIT_ASSET_DEVICE == 'Network Device' || $EDIT_ASSET_DEVICE == 'Printer') { ?>
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label class="control-label">IP</label>
                                                                <input type="text" name="IP" class="form-control"
                                                                       value="<?php if (isset($EDIT_ASSET_IP)) {
                                                                           echo $EDIT_ASSET_IP;
                                                                       } ?>" placeholder="192.168.1.1">
                                                            </div>
                                                        </div>


                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label class="control-label">MAC</label>
                                                                <input type="text" name="MAC" class="form-control"
                                                                       value="<?php if (isset($EDIT_ASSET_MAC)) {
                                                                           echo $EDIT_ASSET_MAC;
                                                                       } ?>" placeholder="Ethernet Address">
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label class="control-label">Hostname</label>
                                                                <input type="text" name="HOSTNAME" class="form-control"
                                                                       value="<?php if (isset($EDIT_ASSET_HOST)) {
                                                                           echo $EDIT_ASSET_HOST;
                                                                       } ?>" placeholder="Hostname">
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="form-group">
                                                                    <label class="control-label">Notes</label>
                                                                    <textarea name="NOTES" class="form-control" rows="5"
                                                                              placeholder="Any other details"><?php if (isset($EDIT_ASSET_NOTES)) {
                                                                            echo $EDIT_ASSET_NOTES;
                                                                        } ?></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } ?>

                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label class="control-label">Device Faulty?</label>
                                                                <select name="FAULT" class="form-control" required>
                                                                    <option <?php if (isset($EDIT_ASSET_FAULT)) {
                                                                        if ($EDIT_ASSET_FAULT == '0') {
                                                                            echo "selected";
                                                                        }
                                                                    } ?> value="0">No
                                                                    </option>
                                                                    <option <?php if (isset($EDIT_ASSET_FAULT)) {
                                                                        if ($EDIT_ASSET_FAULT == '1') {
                                                                            echo "selected";
                                                                        }
                                                                    } ?> value="1">Yes
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Notes</label>
                                                                <textarea name="FAULT_REASON" class="form-control"
                                                                          rows="5"
                                                                          placeholder="Description of fault"><?php if (isset($EDIT_ASSET_FAULT_NOTES)) {
                                                                        echo $EDIT_ASSET_FAULT_NOTES;
                                                                    } ?></textarea>
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
                        <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> Save</button>

                        <script>
                            document.querySelector('#UPDATEASSETform').addEventListener('submit', function (e) {
                                var form = this;
                                e.preventDefault();
                                swal({
                                        title: "Update Asset details?",
                                        text: "Confirm to update asset details!",
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
                                                title: 'Asset updated!',
                                                text: 'Asset details Updated!',
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
        <script type="text/javascript">
            $(document).ready(function () {

                $('#update_modal').modal('show');

            });
        </script>
        <?php
    }
}
if (isset($DEVICE)) { ?>
    <script type="text/javascript">
        $(document).ready(function () {

            $('#comp_modal').modal('show');

        });
    </script>
<?php }
?>
</body>
</html>
