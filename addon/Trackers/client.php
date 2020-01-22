<?php

/** @noinspection PhpIncludeInspection */

use ADL\client;
use ADL\clientNote;


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
$page_protect->access_page(filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS), "", 2);
$hello_name = ($page_protect->user_full_name != "") ? $page_protect->user_full_name : $page_protect->user;

$USER_TRACKING = 1;

require_once(BASE_URL . '/includes/adl_features.php');
require_once(BASE_URL . '/includes/Access_Levels.php');
require_once(BASE_URL . '/includes/adlfunctions.php');
require_once(BASE_URL . '/classes/database_class.php');

if (isset($ffanalytics) && $ffanalytics == 1) {
    require_once(BASE_URL . '/app/analyticstracking.php');
}

if (isset($fferror) && $fferror == 1) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

$editNotes = filter_input(INPUT_GET, 'editNotes', FILTER_SANITIZE_NUMBER_INT);
$search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_NUMBER_INT);

if (isset($search)) {
    $likesearch = "$search-%";
    $tracking_search = "%search=$search%";
}

if (isset($search) && $search < 0 || empty($search)) {
    header('Location: /../../CRMmain.php?noCID');
    die;
}

require_once(BASE_URL . '/classes/database_class.php');
require_once(BASE_URL . '/class/login/login.php');

$CHECK_USER_LOGIN = new UserActions($hello_name, "NoToken");
$CHECK_USER_LOGIN->CheckAccessLevel();
$USER_ACCESS_LEVEL = $CHECK_USER_LOGIN->CheckAccessLevel();
$ACCESS_LEVEL = $USER_ACCESS_LEVEL['ACCESS_LEVEL'];

if ($ACCESS_LEVEL < 2) {
    $page_protect->log_out();
}

require_once(BASE_URL . '/includes/ADL_PDO_CON.php');
require_once(BASE_URL . '/class/clientNote.php');
require_once(BASE_URL . '/class/client.php');

$newClient = new ADL\client($pdo);
$newClient->setClientID($search);
$newClient->setCompanyEntity($COMPANY_ENTITY);
$newClientResponse = $newClient->getPotentialClient();

if ($newClientResponse == 'error') {
    header('Location: /../../CRMmain.php?error');
}

if (isset($newClientResponse['company'])) {
    $company = $newClientResponse['company'];
}
if (isset($newClientResponse['owner'])) {
    $owner = $newClientResponse['owner'];
}
if (isset($newClientResponse['addedDate'])) {
    $addedDate = $newClientResponse['addedDate'];
}

if (isset($newClientResponse['clientName'])) {
    $clientName = $newClientResponse['clientName'];
}

if (isset($newClientResponse['phoneNumber'])) {
    $phoneNumber = $newClientResponse['phoneNumber'];
}
if (isset($newClientResponse['alt_number'])) {
    $altPhoneNumber = $newClientResponse['alt_number'];
}
if (isset($newClientResponse['email'])) {
    $email = $newClientResponse['email'];
}

if (isset($newClientResponse['title'])) {
    $altPhoneNumber = $newClientResponse['title'];
}

if (isset($newClientResponse['status'])) {
    $altPhoneNumber = $newClientResponse['status'];
}

$ADL_PAGE_TITLE = "Client";
require_once(BASE_URL . '/app/core/head.php');
?>
<link rel="stylesheet" href="/resources/lib/sweet-alert/sweet-alert.min.css"/>
<link rel="stylesheet" href="/resources/lib/summernote-master/dist/summernote.css">
</head>
<body>
<?php require_once(BASE_URL . '/includes/navbar.php'); ?>
<br>
<div class="container">

    <?php require_once(BASE_URL . '/includes/user_tracking.php');

    if (isset($bday) && $bday == 1) {

        require_once(BASE_URL . '/resources/lib/bday/baloons.html');

    }

    require_once(BASE_URL . '/addon/Trackers/views/client/clientNav-view.php');

    ?>

    <div class="tab-content">
        <div id="home" class="tab-pane fade in active">
            <?php
            require_once(BASE_URL . '/addon/Trackers/views/client/Notifications.php');
            require_once(BASE_URL . '/addon/Trackers/views/client/viewClient-view.php');
            ?>

        </div>

        <div id="smsModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><i class='far fa-comment-dots'></i> Send Message</h4>
                    </div>
                    <div class="modal-body">
                        <?php if ($ffsms == '1') { ?>
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#smsTab" data-toggle="tab"><i class="fa fa-mobile"></i> SMS</a>
                                </li>
                                <li><a href="#whatsAppTab" data-toggle="tab"><i class="fab fa-whatsapp"></i>
                                        WhatsApp</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="smsTab">

                                    <?php
                                    $CHECK_NUM = strlen($newClientResponse['phoneNumber']);
                                    if ($CHECK_NUM > 11) {
                                        $CHK_NUM = '0';
                                    }
                                    if ($CHECK_NUM <= 10) {
                                        $CHK_NUM = '0';
                                    }
                                    if ($CHECK_NUM == 11) {
                                        $CHK_NUM = '1';
                                    }
                                    if ($CHK_NUM == '0') {
                                        ?>

                                        <div class="notice notice-danger" role="alert"><strong><i
                                                    class="fa fa-exclamation-circle fa-lg"></i> Invalid
                                                Number:</strong>
                                            Please check that the phone number is correct and is in the correct
                                            format
                                            (i.e.
                                            07401434619).
                                        </div>

                                    <?php }
                                    if ($CHK_NUM == '1') {
                                        ?>
                                        <form class="AddClient">
                                            <p>
                                                <label for="phoneNumber">Contact Number:</label>
                                                <input class="form-control" type="tel" id="phoneNumber"
                                                       name="phoneNumber"
                                                       value="<?php echo $newClientResponse['phoneNumber']; ?>"
                                                       readonly>
                                            </p>
                                        </form>


                                        <br>
                                        <?php if (in_array($hello_name, $Manager_Access, true)) { ?>

                                            <form class="AddClient" method="POST"
                                                  action="<?php if ($CHK_NUM == '0') {
                                                      echo "#";
                                                  }
                                                  if ($CHK_NUM == '1') {
                                                      echo "/addon/Trackers/SMS/CusSend.php?EXECUTE=1";
                                                  } ?>">

                                                <input type="hidden" name="search" value="<?php echo $search; ?>">
                                                <div class="form-group">
                                                    <label for="message">Custom MSG:</label>
                                                    <textarea class="form-control" name="message"
                                                              required></textarea>
                                                </div>

                                                <input type="hidden" id="FullName" name="FullName"
                                                       value="<?php if (isset($clientName)) {
                                                           echo $clientName;
                                                       } ?>">
                                                <input type="hidden" id="phoneNumber" name="phoneNumber"
                                                       value="<?php if (isset($phoneNumber)) {
                                                           echo $phoneNumber;
                                                       } ?>">
                                                <div style="text-align: center;">
                                                    <button type='submit' class='btn btn-primary'><i
                                                            class='fa fa-mobile'></i>
                                                        SEND CUSTOM SMS
                                                    </button>
                                                </div>

                                            </form>

                                            <?php
                                        }
                                    } ?>

                                </div>
                                <div class="tab-pane" id="whatsAppTab">

                                    <?php
                                    $CHECK_NUM = strlen($newClientResponse['phoneNumber']);
                                    if ($CHECK_NUM > 11) {
                                        $CHK_NUM = '0';
                                    }
                                    if ($CHECK_NUM <= 10) {
                                        $CHK_NUM = '0';
                                    }
                                    if ($CHECK_NUM == 11) {
                                        $CHK_NUM = '1';
                                    }
                                    if ($CHK_NUM == '0') {
                                        ?>

                                        <div class="notice notice-danger" role="alert"><strong><i
                                                    class="fa fa-exclamation-circle fa-lg"></i> Invalid
                                                Number:</strong>
                                            Please check that the phone number is correct and is in the correct
                                            format
                                            (i.e.
                                            07401434619).
                                        </div>

                                    <?php }
                                    if ($CHK_NUM == '1') {
                                        ?>
                                        <form class="AddClient">
                                            <p>
                                                <label for="phoneNumber">Contact Number:</label>
                                                <input class="form-control" type="tel" id="phoneNumber"
                                                       name="phoneNumber"
                                                       value="<?php echo $newClientResponse['phoneNumber'] ?>"
                                                       readonly>
                                            </p>
                                        </form>


                                        <br>


                                        <form class="AddClient" method="POST" action="<?php if ($CHK_NUM == '0') {
                                            echo "#";
                                        }
                                        if (in_array($hello_name, $Level_10_Access, true)) {
                                            if ($CHK_NUM == '1') {
                                                echo "/addon/whatsApp/php/send.php?EXECUTE=1&CID=$search";
                                            }
                                        } ?>">

                                            <div class="form-group">
                                                <label for="message">Custom WhatsApp:</label>
                                                <textarea class="form-control" name="whatsAppMessage"
                                                          required></textarea>
                                            </div>

                                            <div style="text-align: center;">
                                                <button type='submit' class='btn btn-primary'><i
                                                        class='fa fa-mobile'></i>
                                                    Send WhatsApp message
                                                </button>
                                            </div>

                                        </form>

                                        <?php
                                    } ?>

                                </div>
                            </div>

                            <br>


                        <?php } else {
                            ?>

                            <div class="alert alert-info"><strong>Info!</strong> SMS feature not enabled.</div>
                        <?php } ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <?php if (in_array($hello_name, $Level_10_Access, true)) { ?>

            <div id="TRACKING" class="tab-pane fade">
                <div class="container">

                    <?php
                    require_once(BASE_URL . '/app/models/client/UserTrackingModel.php');
                    $UserTracking = new UserTrackingModal($pdo);
                    $UserTrackingList = $UserTracking->getUserTracking($search);
                    require_once(BASE_URL . '/app/views/client/UserTracking.php');
                    ?>
                </div>
            </div>

        <?php } ?>


        <div id="menu4" class="tab-pane fade">

            <div class='container'>
                <div class="row">
                    <form method="post" id="clientnotessubtab" action="/addon/Trackers/php/addNotes.php?EXECUTE=1"
                          class="form-horizontal">
                        <legend><h3><span class="label label-info">Add notes</span></h3></legend>
                        <input type="hidden" name="CID" value="<?php echo $search ?>">

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="client_name"></label>
                            <div class="col-md-4">
                                <select id="selectbasic" name="client_name" class="form-control" required>
                                    <option
                                        value="<?php echo $newClientResponse['clientName']; ?>"><?php echo $newClientResponse['clientName']; ?></option>
                                    <option value="Compliant">Log Compliant</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12 control-label" for="textarea"></label>
                            <div class="col-md-12">
                                <textarea id="notes" name="notes" class="summernote" maxlength="2000"
                                          required></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="singlebutton"></label>
                            <div class="col-md-4">
                                <button class="btn btn-primary btn-block"><i class="fas fa-edit"></i> Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <?php

            $getTimelineNotes = new ADL\clientNote($search, $pdo);
            $timelineNotesTimelineNotes = $getTimelineNotes->allPotentialClientNote();
            require(BASE_URL . '/addon/Trackers/views/client/clientTimeline.php');

            ?>

        </div>

    </div>

    <div id="email1pop" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">
                        Email: <?php echo $newClientResponse['clientName']; ?>
                        <i>(<?php echo $newClientResponse['email']; ?>
                            )</i></h4>
                </div>
                <div class="modal-body">

                    <div class="col-md-12">

                        <form class="AddClient" method="post"
                              action="/addon/sendGrid/php/sendEmail.php?EXECUTE=2&cbClient=1"
                              enctype="multipart/form-data">

                            <input type="hidden" name="search" value="<?php echo $search; ?>">
                            <input type="hidden" name="recipient"
                                   value="<?php echo $newClientResponse['clientName']; ?>">
                            <input type="hidden" name="email" value="<?php echo $newClientResponse['email']; ?>">

                            <div class="form-group">
                                <label for="message">Email Templates</label>
                                <select name="message" id="message" class="form-control" required>
                                    <option value="">Select...</option>
                                    <option value="Life insurance quotation">Life insurance quotation</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="insurer">Insurer:</label>
                                <select class="form-control" name="insurer" id="insurer"
                                        required>
                                    <option value="">Select insurer...</option>
                                    <option value="Royal London">Royal London</option>
                                    <option value="LV">LV</option>
                                    <option value="One Family">One Family</option>
                                    <option value="Aegon">Aegon</option>
                                    <option value="HSBC">HSBC</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="attachment1">Attachment:</label>
                                <input type="file" name="fileToUpload" id="fileToUpload" class="form-control"
                                       required>
                            </div>

                            <br>
                            <br>
                            <div style="text-align: center;">
                                <button type="submit" class="btn btn-primary "><span
                                        class="glyphicon glyphicon-envelope"></span> Send Email Template
                                </button>
                            </div>
                        </form>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal"><span
                            class="glyphicon glyphicon-remove-sign"></span>Close
                    </button>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.querySelector('#clientnotessubtab').addEventListener('submit', function (e) {
            const form = this;
            e.preventDefault();
            swal({
                    title: "Submit notes?",
                    text: "Confirm to send notes!",
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
                            title: 'Notes submitted!',
                            text: 'Notes saved!',
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
    <script type="text/javascript" src="/resources/lib/jquery/jquery-3.0.0.min.js"></script>
    <script src="/resources/templates/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
    <script src="/resources/lib/sweet-alert/sweet-alert.min.js"></script>
    <script type="text/javascript" src="/resources/lib/summernote-master/dist/summernote.js"></script>
    <script src="/resources/lib/js-webshim/minified/polyfiller.js"></script>
    <script>
        webshims.setOptions('forms-ext', {
            replaceUI: 'auto',
            types: 'number'
        });
        webshims.polyfill('forms forms-ext');
    </script>
    <script type="text/javascript">
        $(function () {
            $('.summernote').summernote({
                height: 200
            });
        });
    </script>

    <?php require_once(BASE_URL . '/app/Holidays.php'); ?>
    <?php require_once(BASE_URL . '/app/php/toastr.php'); ?>

    <div class="closerTrackerLiveResults">

    </div>


    <script>
        function refresh_div() {
            jQuery.ajax({
                url: '/addon/Trackers/php/closerTrackersLiveResults.php',
                type: 'POST',
                success: function (results) {
                    jQuery(".closerTrackerLiveResults").html(results);
                }
            });
        }

        t = setInterval(refresh_div, 7000);
    </script>

</body>
</html>
