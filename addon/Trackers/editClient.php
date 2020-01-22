<?php
/** @noinspection PhpIncludeInspection */

use ADL\client;

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

$USER_TRACKING = 1;

require_once(BASE_URL . '/includes/adl_features.php');

require_once(BASE_URL . '/includes/time.php');

if (isset($FORCE_LOGOUT) && $FORCE_LOGOUT == 1) {
    $page_protect->log_out();
}

require_once(BASE_URL . '/includes/Access_Levels.php');
require_once(BASE_URL . '/includes/adlfunctions.php');
require_once(BASE_URL . '/includes/ADL_PDO_CON.php');

if (isset($ffanalytics) && $ffanalytics == 1) {
    require_once(BASE_URL . '/app/analyticstracking.php');
}

if (isset($fferror) && $fferror == 1) {
    ini_SET('display_errors', 1);
    ini_SET('display_startup_errors', 1);
    error_reporting(E_ALL);
}

if (isset($ffpost_code) && $ffpost_code == 1) {

    $PostcodeQuery = $pdo->prepare("select api_key from api_keys WHERE type ='PostCode' limit 1");
    $PostcodeQuery->execute() or die(print_r($query->errorInfo(), true));
    $PDre = $PostcodeQuery->fetch(PDO::FETCH_ASSOC);
    $PostCodeKey = $PDre['api_key'];
}

require_once(BASE_URL . '/classes/database_class.php');
require_once(BASE_URL . '/class/login/login.php');
$CHECK_USER_LOGIN = new UserActions($hello_name, "NoToken");
$CHECK_USER_LOGIN->CheckAccessLevel();

$USER_ACCESS_LEVEL = $CHECK_USER_LOGIN->CheckAccessLevel();

$ACCESS_LEVEL = $USER_ACCESS_LEVEL['ACCESS_LEVEL'];

if ($ACCESS_LEVEL < 1) {

    $page_protect->log_out();

}

$ADL_PAGE_TITLE = "Edit Client";
require_once(BASE_URL . '/app/core/head.php');

?>
<link rel="stylesheet" href="/resources/lib/jquery-ui-1.11.4/jquery-ui.min.css">
<link rel="stylesheet" href="/resources/lib/sweet-alert/sweet-alert.min.css"/>
<script type="text/javascript" src="/resources/lib/jquery/jquery-3.0.0.min.js"></script>
<script type="text/javascript" src="/resources/lib/jquery-ui-1.11.4/jquery-ui.min.js"></script>
<script src="/resources/templates/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
<script src="/resources/lib/sweet-alert/sweet-alert.min.js"></script>
<script>
    $(function () {
        $("#dob").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:-0"
        });
    });

    $(function () {
        $("#dob2").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:-0"
        });
    });

    $("readonly").keydown(function (e) {
        e.preventDefault();
    });
</script>

<?php
if ($ffpost_code == '1') {
    ?>

    <script src="/resources/lib/ideal-postcodes/jquery.postcodes.min.js"></script>

<?php } ?>
</head>

<body>
<?php
require_once(BASE_URL . '/includes/navbar.php');

$CID = filter_input(INPUT_GET, 'CID', FILTER_SANITIZE_NUMBER_INT);

if (isset($CID)) {
    $tracking_search = "%search=$CID%";
}

$EXECUTE = filter_input(INPUT_GET, 'EXECUTE', FILTER_SANITIZE_SPECIAL_CHARS);

if (isset($EXECUTE)) {

    require_once(BASE_URL . '/class/client.php');

    $newClient = new ADL\client($pdo);
    $newClient->setClientID($CID);
    $newClient->setCompanyEntity($COMPANY_ENTITY);
    $newClientResponse = $newClient->getPotentialClient();
    ?>

    <div class="container">

        <?php require_once(BASE_URL . '/includes/user_tracking.php'); ?>

        <div class="editclient">
            <div class="notice notice-warning">
                <a href="#" class="close" data-dismiss="alert">&times;</a>
                <strong>Warning!</strong> You are now
                editing <?php echo $newClientResponse['clientName'] ?> details.
            </div>

            <div class="panel-group">
                <div class="panel panel-warning">
                    <div class="panel-heading">Edit Client</div>
                    <div class="panel-body">
                        <form id="from1" class="AddClient" action="/addon/Trackers/php/editClient.php?EXECUTE=1"
                              method="POST" autocomplete="off">
                            <input class="form-control" type="hidden" name="CID" value="<?php echo $CID ?>">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="col-md-2">

                                        <h4><span class="label label-info">Company</span></h4>
                                    </div>
                                    <div class="col-md-2">
                                        <select class="form-control" name="company" id="company" style="width: 170px"
                                                required="yes">

                                            <?php
                                            if (isset($newClientResponse['company'])) {


                                                $COMP_QRY = $pdo->prepare("SELECT insurance_company_name from insurance_company where insurance_company_active='1' ORDER BY insurance_company_id DESC");
                                                $COMP_QRY->execute();
                                                if ($COMP_QRY->rowCount() > 0) {
                                                    while ($result = $COMP_QRY->fetch(PDO::FETCH_ASSOC)) {

                                                        $CUSTYPE = $result['insurance_company_name'];

                                                        ?>
                                                        <option value="<?php if (isset($CUSTYPE)) {
                                                            echo $CUSTYPE;
                                                        } ?>" <?php if (isset($newClientResponse['company'])) {
                                                            if ($newClientResponse['company'] == $CUSTYPE) {
                                                                echo "selected";
                                                            }
                                                        } ?> ><?php if (isset($CUSTYPE)) {
                                                                echo $CUSTYPE;
                                                            } ?></option>
                                                    <?php }
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="col-md-2">

                                        <h4><span class="label label-info">Tracker Status</span></h4>
                                    </div>
                                    <div class="col-md-2">
                                        <select class="form-control" name="status" id="status" style="width: 170px"
                                                required="yes">
                                            <option value="">DISPO</option>
                                            <option <?php if (isset($newClientResponse['status'])) {
                                                if ($newClientResponse['status'] == 'SALE') {
                                                    echo "selected";
                                                }
                                            } ?> value="SALE">Sale
                                            </option>
                                            <option <?php if (isset($newClientResponse['status'])) {
                                                if ($newClientResponse['status'] == 'QUN') {
                                                    echo "selected";
                                                }
                                            } ?> value="QUN">Underwritten
                                            </option>
                                            <option <?php if (isset($newClientResponse['status'])) {
                                                if ($newClientResponse['status'] == 'QQQ') {
                                                    echo "selected";
                                                }
                                            } ?> value="QQQ">Quoted
                                            </option>
                                            <option <?php if (isset($newClientResponse['status'])) {
                                                if ($newClientResponse['status'] == 'QNQ') {
                                                    echo "selected";
                                                }
                                            } ?> value="QNQ">No Quote
                                            </option>
                                            <option <?php if (isset($newClientResponse['status'])) {
                                                if ($newClientResponse['status'] == 'QML') {
                                                    echo "selected";
                                                }
                                            } ?> value="QML">Quote Mortgage Lead
                                            </option>
                                            <option <?php if (isset($newClientResponse['status'])) {
                                                if ($newClientResponse['status'] == 'QDE') {
                                                    echo "selected";
                                                }
                                            } ?> value="QDE">Decline
                                            </option>
                                            <option <?php if (isset($newClientResponse['status'])) {
                                                if ($newClientResponse['status'] == 'QCBK') {
                                                    echo "selected";
                                                }
                                            } ?> value="QCBK">Quoted Callback
                                            </option>
                                            <option <?php if (isset($newClientResponse['status'])) {
                                                if ($newClientResponse['status'] == 'NoCard') {
                                                    echo "selected";
                                                }
                                            } ?> value="NoCard">No Card
                                            </option>
                                            <option <?php if (isset($newClientResponse['status'])) {
                                                if ($newClientResponse['status'] == 'DIDNO') {
                                                    echo "selected";
                                                }
                                            } ?> value="DIDNO">Quote Not Beaten
                                            </option>
                                            <option <?php if (isset($newClientResponse['status'])) {
                                                if ($newClientResponse['status'] == 'DETRA') {
                                                    echo "selected";
                                                }
                                            } ?> value="DETRA">Declined but passed to upsale
                                            </option>
                                            <option <?php if (isset($newClientResponse['status'])) {
                                                if ($newClientResponse['status'] == 'Hangup on XFER') {
                                                    echo "selected";
                                                }
                                            } ?> value="Hangup on XFER">Hangup on XFER
                                            </option>
                                            <option <?php if (isset($newClientResponse['status'])) {
                                                if ($newClientResponse['status'] == 'Thought we were an insurer') {
                                                    echo "selected";
                                                }
                                            } ?> value="Thought we were an insurer">Thought we were an insurer
                                            </option>
                                            <option <?php if (isset($newClientResponse['status'])) {
                                                if ($newClientResponse['status'] == 'Other') {
                                                    echo "selected";
                                                }
                                            } ?> value="Other">Other
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <br>

                            <div class="col-md-12"></div>


                            <div class="col-md-4">
                                <h3><span class="label label-primary">Client Details (1)</span></h3>
                                <br>


                                <div class="form-group">
                                    <label for="title">Title:</label>
                                    <select class="form-control" name="title" id="title" style="width: 170px"
                                            required>
                                        <?php if (empty($newClientResponse['title'])) { ?>
                                            <option value="">Select...</option>
                                            <option value="Mr">Mr</option>
                                            <option value="Dr">Dr</option>
                                            <option value="Prof">Prof</option>
                                            <option value="Miss">Miss</option>
                                            <option value="Mrs">Mrs</option>
                                            <option value="Ms">Ms</option>
                                            <option value="Lord">Lord</option>
                                            <option value="Other">Other</option>
                                        <?php } else { ?>
                                            <option <?php if (isset($newClientResponse['title'])) {
                                                if ($newClientResponse['title'] == 'Mr') {
                                                    echo "selected";
                                                }
                                            } ?> value="Mr">Mr
                                            </option>
                                            <option <?php if (isset($newClientResponse['title'])) {
                                                if ($newClientResponse['title'] == 'Dr') {
                                                    echo "selected";
                                                }
                                            } ?> value="Dr">Dr
                                            </option>
                                            <option <?php if (isset($newClientResponse['title'])) {
                                                if ($newClientResponse['title'] == 'Prof') {
                                                    echo "selected";
                                                }
                                            } ?> value="Prof">Prof
                                            </option>
                                            <option <?php if (isset($newClientResponse['title'])) {
                                                if ($newClientResponse['title'] == 'Miss') {
                                                    echo "selected";
                                                }
                                            } ?> value="Miss">Miss
                                            </option>
                                            <option <?php if (isset($newClientResponse['title'])) {
                                                if ($newClientResponse['title'] == 'Mrs') {
                                                    echo "selected";
                                                }
                                            } ?> value="Mrs">Mrs
                                            </option>
                                            <option <?php if (isset($newClientResponse['title'])) {
                                                if ($newClientResponse['title'] == 'Ms') {
                                                    echo "selected";
                                                }
                                            } ?> value="Ms">Ms
                                            </option>
                                            <option <?php if (isset($newClientResponse['title'])) {
                                                if ($newClientResponse['title'] == 'Lord') {
                                                    echo "selected";
                                                }
                                            } ?> value="Lord">Lord
                                            </option>
                                            <option <?php if (isset($newClientResponse['title'])) {
                                                if ($newClientResponse['title'] == 'Other') {
                                                    echo "selected";
                                                }
                                            } ?> value="Other">Other
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <br>


                                <label for="first_name">Client Name:</label>
                                <input class="form-control" type="text" id="clientName" name="clientName"
                                       value="<?php echo $newClientResponse['clientName']; ?>"
                                       style="width: 170px" required>
                                <br>

                                <label for="dob">Date of Birth:</label>
                                <input class="form-control" type="text" id="dob" name="dob"
                                       value="<?php if (isset($newClientResponse['dob'])) {
                                           echo $newClientResponse['dob'];
                                       } ?>"
                                       style="width: 170px">
                                <br>


                                <label for="email">Email:</label>
                                <input class="form-control" type="email" id="email" name="email"
                                       value="<?php if (isset($newClientResponse['email'])) {
                                           echo $newClientResponse['email'];
                                       } ?>"
                                       style="width: 170px" required>
                                <br>


                            </div>

                            <div class="col-md-4">


                                <h3><span class="label label-primary">Client Details (2)</span></h3>
                                <br>


                                <div class="form-group">
                                    <label for="title2">Title:</label>
                                    <select class="form-control" name="title2" id="title2" style="width: 170px">
                                        <?php if (empty($newClientResponse['title2'])) { ?>
                                            <option value=""></option>
                                            <option value="Mr">Mr</option>
                                            <option value="Dr">Dr</option>
                                            <option value="Prof">Prof</option>
                                            <option value="Miss">Miss</option>
                                            <option value="Mrs">Mrs</option>
                                            <option value="Ms">Ms</option>
                                            <option value="Lord">Lord</option>
                                            <option value="Other">Other</option>
                                        <?php } else { ?>
                                            <option value=""></option>
                                            <option <?php if (isset($newClientResponse['title2'])) {
                                                if ($newClientResponse['title2'] == 'Mr') {
                                                    echo "selected";
                                                }
                                            } ?> value="Mr">Mr
                                            </option>
                                            <option <?php if (isset($newClientResponse['title2'])) {
                                                if ($newClientResponse['title2'] == 'Dr') {
                                                    echo "selected";
                                                }
                                            } ?> value="Dr">Dr
                                            </option>
                                            <option <?php if (isset($newClientResponse['title2'])) {
                                                if ($newClientResponse['title2'] == 'Prof') {
                                                    echo "selected";
                                                }
                                            } ?> value="Prof">Prof
                                            </option>
                                            <option <?php if (isset($newClientResponse['title2'])) {
                                                if ($newClientResponse['title2'] == 'Miss') {
                                                    echo "selected";
                                                }
                                            } ?> value="Miss">Miss
                                            </option>
                                            <option <?php if (isset($newClientResponse['title2'])) {
                                                if ($newClientResponse['title2'] == 'Mrs') {
                                                    echo "selected";
                                                }
                                            } ?> value="Mrs">Mrs
                                            </option>
                                            <option <?php if (isset($newClientResponse['title2'])) {
                                                if ($newClientResponse['title2'] == 'Ms') {
                                                    echo "selected";
                                                }
                                            } ?> value="Ms">Ms
                                            </option>
                                            <option <?php if (isset($newClientResponse['title2'])) {
                                                if ($newClientResponse['title2'] == 'Lord') {
                                                    echo "selected";
                                                }
                                            } ?> value="Lord">Lord
                                            </option>
                                            <option <?php if (isset($newClientResponse['title2'])) {
                                                if ($newClientResponse['title2'] == 'Other') {
                                                    echo "selected";
                                                }
                                            } ?> value="Other">Other
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <br>


                                <label for="first_name2">Client Name:</label>
                                <input class="form-control" type="text" id="first_name2" name="first_name2"
                                       value="<?php if (isset($newClientResponse['first_name2'])) {
                                           echo $newClientResponse['first_name2'];
                                       } ?>"
                                       style="width: 170px">
                                <br>

                                <label for="dob2">Date of Birth:</label>
                                <input class="form-control" type="text" id="dob2" name="dob2"
                                       value="<?php if (isset($newClientResponse['dob2'])) {
                                           echo $newClientResponse['dob2'];
                                       } ?>"
                                       style="width: 170px">
                                <br>

                            </div>

                            <div class="col-md-4">

                                <h3><span class="label label-primary">Contact Details</span></h3>
                                <br>


                                <label for="phoneNumber">Contact Number:</label>
                                <input class="form-control" type="tel" id="phoneNumber" name="phoneNumber"
                                       value="<?php if (isset($newClientResponse['phoneNumber'])) {
                                           echo $newClientResponse['phoneNumber'];
                                       } ?>"
                                       style="width: 170px" required pattern=".{11}|.{11,11}" maxlength="11"
                                       title="Enter a valid phone number">
                                <br>


                                <label for="altNumber">Alt Number:</label>
                                <input class="form-control" type="tel" id="altNumber" name="altNumber"
                                       value="<?php if (isset($newClientResponse['altNumber'])) {
                                           echo $newClientResponse['altNumber'];
                                       } ?>"
                                       style="width: 170px" pattern=".{11}|.{11,11}" maxlength="11"
                                       title="Enter a valid phone number">
                                <br>


                                <br>
                                <?php
                                if (isset($ffpost_code)) {

                                    if ($ffpost_code == '1') { ?>
                                        <div id="lookup_field"></div>
                                    <?php }

                                    if ($ffpost_code == '0') {
                                        ?>

                                        <div class="alert alert-info"><strong>Info!</strong> Post code lookup
                                            feature not enabled.
                                        </div>

                                    <?php }

                                }

                                ?>
                                <br>

                                <label for="address1">Address Line 1:</label>
                                <input class="form-control" type="text" id="address1" name="address1"
                                       value="<?php if (isset($newClientResponse['address1'])) {
                                           echo $newClientResponse['address1'];
                                       } ?>"
                                       style="width: 170px">
                                <br>


                                <label for="address2">Address Line 2:</label>
                                <input class="form-control" type="text" id="address2" name="address2"
                                       value="<?php if (isset($newClientResponse['address2'])) {
                                           echo $newClientResponse['address2'];
                                       } ?>"
                                       style="width: 170px">
                                <br>


                                <label for="address3">Address Line 3:</label>
                                <input class="form-control" type="text" id="address3" name="address3"
                                       value="<?php if (isset($newClientResponse['address3'])) {
                                           echo $newClientResponse['address3'];
                                       } ?>"
                                       style="width: 170px">
                                <br>


                                <label for="town">Post Town:</label>
                                <input class="form-control" type="text" id="town" name="town"
                                       value="<?php if (isset($newClientResponse['town'])) {
                                           echo $newClientResponse['town'];
                                       } ?>"
                                       style="width: 170px">
                                <br>


                                <label for="post_code">Post Code:</label>
                                <input class="form-control" type="text" id="post_code" name="post_code"
                                       value="<?php if (isset($newClientResponse['post_code'])) {
                                           echo $newClientResponse['post_code'];
                                       } ?>"
                                       style="width: 170px">
                                <br>

                                <?php if (isset($PostCodeKey)) { ?>
                                    <script>
                                        $('#lookup_field').setupPostcodeLookup({
                                            api_key: '<?php echo $PostCodeKey; ?>',
                                            output_fields: {
                                                line_1: '#address1',
                                                line_2: '#address2',
                                                line_3: '#address3',
                                                post_town: '#town',
                                                postcode: '#post_code'
                                            }
                                        });
                                    </script>
                                <?php } ?>
                                <br>


                                <br>
                                <button class="btn btn-success "><span class="glyphicon glyphicon-ok"></span> Save
                                </button>

                        </form>
                        <a href="/addon/Trackers/client.php?search=<?php echo $CID; ?>" class="btn btn-warning"><span
                                class="glyphicon glyphicon-chevron-left"></span> Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

<?php }
?>
<script>
    document.querySelector('#from1').addEventListener('submit', function (e) {
        const form = this;
        e.preventDefault();
        swal({
                title: "Save changes?",
                text: "You will not be able to recover any overwritten data!",
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
                        title: 'Complete!',
                        text: 'Client details updated!',
                        type: 'success'
                    }, function () {
                        form.submit();
                    });

                } else {
                    swal("Cancelled", "No Changes have been submitted", "error");
                }
            });
    });

</script>
</body>
</html>
