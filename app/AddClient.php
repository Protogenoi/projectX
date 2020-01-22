<?php
/** @noinspection PhpIncludeInspection */

use ADL\client;

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
$page_protect->access_page(filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS), "", 3);
$hello_name = ($page_protect->user_full_name != "") ? $page_protect->user_full_name : $page_protect->user;

require_once(BASE_URL . '/includes/adl_features.php');

if (isset($ffanalytics) && $ffanalytics == 1) {
    require_once(BASE_URL . '/app/analyticstracking.php');
}

if (isset($fferror) && $fferror == 1) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

require_once(BASE_URL . '/includes/Access_Levels.php');
require_once(BASE_URL . '/includes/adlfunctions.php');

$USER_TRACKING = 0;

require_once(BASE_URL . '/includes/user_tracking.php');

require_once(BASE_URL . '/includes/time.php');

if (isset($FORCE_LOGOUT) && $FORCE_LOGOUT == 1) {
    $page_protect->log_out();
}

if (isset($ffpost_code) && $ffpost_code == 1) {
    require_once(BASE_URL . '/includes/ADL_PDO_CON.php');

    $PostcodeQuery = $pdo->prepare("select api_key from api_keys WHERE type ='PostCode' limit 1");
    $PostcodeQuery->execute() or die(print_r($query->errorInfo(), true));
    $PDre = $PostcodeQuery->fetch(PDO::FETCH_ASSOC);
    $PostCodeKey = $PDre['api_key'];
}

$EXECUTE = filter_input(INPUT_GET, 'EXECUTE', FILTER_SANITIZE_NUMBER_INT);

if (isset($EXECUTE) && $EXECUTE == 1) {

    $CID = filter_input(INPUT_GET, 'CID', FILTER_SANITIZE_NUMBER_INT);

    require_once(BASE_URL . '/class/client.php');

    $newClient = new ADL\client($pdo);
    $newClient->setClientID($CID);
    $newClient->setCompanyEntity($COMPANY_ENTITY);
    $newClientResponse = $newClient->getPotentialClient();

    $clientName = $newClientResponse['clientName'];
    $clientName2 = $newClientResponse['clientName2'];
    $phone = $newClientResponse['phoneNumber'];
    $altPhone = $newClientResponse['altNumber'];
    $email = $newClientResponse['email'];
    $title = $newClientResponse['title'];
    $title2 = $newClientResponse['title2'];
    $dob = $newClientResponse['dob'];
    $dob2 = $newClientResponse['dob2'];
    $address1 = $newClientResponse['address1'];
    $address2 = $newClientResponse['address2'];
    $address3 = $newClientResponse['address3'];
    $town = $newClientResponse['town'];
    $post = $newClientResponse['post_code'];
    $company = $newClientResponse['company'];

    $clientNames = explode(" ", $clientName);
    $clientNames2 = explode(" ", $clientName2);

}


$ADL_PAGE_TITLE = "Add Client";
require_once(BASE_URL . '/app/core/head.php');

?>

<link rel="stylesheet" href="/resources/templates/ADL/main.css" type="text/css"/>
<link rel="stylesheet" href="/resources/templates/ADL/PostCode.css" type="text/css"/>
<link rel="stylesheet" href="/resources/lib/jquery-ui-1.11.4/jquery-ui.min.css">
<script type="text/javascript" src="/resources/lib/jquery/jquery-3.0.0.min.js"></script>
<script type="text/javascript" src="/resources/lib/jquery-ui-1.11.4/jquery-ui.min.js"></script>
<script src="/resources/templates/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>

<?php if ($ffpost_code == '1') { ?>
    <script src="/resources/lib/ideal-postcodes/jquery.postcodes.min.js"></script>
<?php } ?>

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
</script>
</head>
<body>

<?php require_once(BASE_URL . '/includes/navbar.php'); ?>
<br>
<div class="container">

    <div class="panel-group">
        <div class="panel panel-primary">
            <div class="panel-heading"><i class="fa fa-user-plus"></i> Add Client</div>
            <div class="panel-body">

                <form class="AddClient" id="AddProduct" action="php/AddClient.php?EXECUTE=1" method="POST"
                      autocomplete="off">

                    <div class="col-md-4">

                        <?php

                        if (isset($EXECUTE) && $EXECUTE == 1) { ?>

                            <input type="hidden" name="convertClient" value="<?php if (isset($CID)) {
                                echo $CID;
                            } ?>">

                        <?php }

                        ?>

                        <h3><span class="label label-info">Client Details (1)</span></h3>
                        <br>

                        <p>
                        <div class="form-group">
                            <label for="custtype">Product:</label>
                            <select class="form-control" name="custype" id="custype" style="width: 170px" required>
                                <?php
                                $COMP_QRY = $pdo->prepare("SELECT insurance_company_name from insurance_company where insurance_company_active='1' ORDER BY insurance_company_id DESC");
                                $COMP_QRY->execute();
                                if ($COMP_QRY->rowCount() > 0) { ?>
                                    <option value="">Select...</option>
                                    <?php
                                    while ($result = $COMP_QRY->fetch(PDO::FETCH_ASSOC)) {

                                        $CUSTYPE = $result['insurance_company_name'];

                                        ?>
                                        <option value="<?php
                                        if (isset($CUSTYPE)) {
                                            echo $CUSTYPE;
                                        }
                                        ?>" <?php if (isset($company) && $company == $CUSTYPE) {
                                            echo 'selected';
                                        } ?>><?php
                                            if (isset($CUSTYPE)) {
                                                echo $CUSTYPE;
                                            }
                                            ?></option>
                                        <?php
                                    }
                                }

                                if (isset($ffhome) && $ffhome == 1) { ?>
                                    <option value="Home Insurance">Home Insurance</option>
                                <?php }

                                ?>

                            </select>
                        </div>
                        </p>

                        <?php

                        $titleArray = ['Mr', 'Dr', 'Ms', 'Miss', 'Mrs', 'Lord', 'Prof', 'Other'];
                        asort($titleArray);
                        ?>

                        <p>
                        <div class="form-group">
                            <label for="title">Title:</label>
                            <select class="form-control" name="title" id="title" style="width: 170px" required>
                                <option value="">Select...</option>

                                <?php

                                foreach ($titleArray as $rows):

                                    ?>
                                    <option value="<?php echo $rows; ?>" <?php if (isset($title) && $title == $rows) {
                                        echo 'selected';
                                    } ?>><?php echo $rows; ?></option>
                                <?php endforeach; ?>

                            </select>
                        </div>
                        </p>

                        <p>
                            <label for="first_name">First Name:</label>
                            <input type="text" id="first_name" name="first_name" class="form-control"
                                   style="width: 170px" required <?php if (isset($clientNames)) {
                                echo "value='$clientNames[0]'";
                            } ?>>
                        </p>
                        <p>
                            <label for="last_name">Last Name:</label>
                            <input type="text" id="last_name" name="last_name" class="form-control"
                                   style="width: 170px"
                                   required <?php if (isset($clientNames) && !empty($clientNames[1])) {
                                echo "value='$clientNames[1]'";
                            } ?>>
                        </p>
                        <p>
                            <label for="dob">Date of Birth:</label>
                            <input type="text" id="dob" name="dob" class="form-control" style="width: 170px"
                                   required <?php if (isset($dob)) {
                                echo "value='$dob'";
                            } ?> >
                        </p>
                        <p>
                            <label for="email">Email:</label>
                            <input type="email" id="email" class="form-control" style="width: 170px" name="email"
                                   placeholder="Use no@email.no for no email address"
                                   title="Use no@email.no for no email address"
                                <?php if (isset($email)) {
                                    echo "value='$email'";
                                } ?>>
                        </p>

                        <br>

                    </div>
                    <div class="col-md-4">
                        <p>

                        <h3><span class="label label-info">Client Details (2)</span></h3>
                        <br>

                        </p>
                        <p>
                        <div class="form-group">
                            <label for="title2">Title:</label>
                            <select class="form-control" name="title2" id="title2" style="width: 170px">
                                <option value="">Select...</option>
                                <?php

                                foreach ($titleArray as $rows):

                                    ?>
                                    <option value="<?php echo $rows; ?>" <?php if (isset($title2) && $title2 == $rows) {
                                        echo 'selected';
                                    } ?>><?php echo $rows; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        </p>

                        <p>
                            <label for="first_name2">First Name:</label>
                            <input type="text" id="first_name2" name="first_name2" class="form-control"
                                   style="width: 170px"
                                <?php if (isset($clientNames2)) {
                                    echo "value='$clientNames2[0]'";
                                } ?>>
                        </p>
                        <p>
                            <label for="last_name2">Last Name:</label>
                            <input type="text" id="last_name2" name="last_name2" class="form-control"
                                   style="width: 170px"
                                <?php if (isset($clientNames2) && !empty($clientNames2[1])) {
                                    echo "value='$clientNames2[1]'";
                                } ?>>
                        </p>
                        <p>
                            <label for="dob2">Date of Birth:</label>
                            <input type="text" id="dob2" name="dob2" class="form-control" style="width: 170px"
                                <?php if (isset($dob2)) {
                                    echo "value='$dob2'";
                                } ?>>
                        </p>
                        <p>
                            <label for="email2">Email:</label>
                            <input type="email" id="email2" name="email2" class="form-control"
                                   style="width: 170px" <?php if (isset($email2)) {
                                echo "value='$email2'";
                            } ?>>
                        </p>
                        <br>
                    </div>

                    <div class="col-md-4">
                        <p>

                        <h3><span class="label label-info">Contact Details</span></h3>
                        <br>
                        </p>
                        <p>
                            <label for="phone_number">Contact Number:</label>
                            <input type="tel" id="phone_number" name="phone_number" class="form-control"
                                   style="width: 170px" required pattern=".{11}|.{11,11}" maxlength="11"
                                   title="Enter a valid phone number" <?php if (isset($phone)) {
                                echo "value='$phone'";
                            } ?> >
                        </p>
                        <p>
                            <label for="alt_number">Alt Number:</label>
                            <input type="tel" id="alt_number" name="alt_number" class="form-control"
                                   style="width: 170px" pattern=".{11}|.{11,11}" maxlength="11"
                                   title="Enter a valid phone number"
                                <?php if (isset($altPhone)) {
                                    echo "value='$altPhone'";
                                } ?>>
                        </p>
                        <br>
                        <?php if ($ffpost_code == '1') { ?>
                            <div id="lookup_field"></div>
                            <?php
                        }

                        if ($ffpost_code == '0') {
                            ?>

                            <div class="alert alert-info"><strong>Info!</strong> Post code lookup feature not enabled.
                            </div>

                        <?php } ?>
                        <p>
                            <label for="address1">Address Line 1:</label>
                            <input type="text" id="address1" name="address1" class="form-control" style="width: 170px"
                                   required
                                <?php if (isset($address1)) {
                                    echo "value='$address1'";
                                } ?>>
                        </p>
                        <p>
                            <label for="address2">Address Line 2:</label>
                            <input type="text" id="address2" name="address2" class="form-control" style="width: 170px"
                                <?php if (isset($address2)) {
                                    echo "value='$address2'";
                                } ?>>
                        </p>
                        <p>
                            <label for="address3">Address Line 3:</label>
                            <input type="text" id="address3" name="address3" class="form-control"
                                   style="width: 170px"
                                <?php if (isset($address3)) {
                                    echo "value='$address3'";
                                } ?>>
                        </p>
                        <p>
                            <label for="town">Post Town:</label>
                            <input type="text" id="town" name="town" class="form-control" style="width: 170px"
                                <?php if (isset($town)) {
                                    echo "value='$town'";
                                } ?>>
                        </p>
                        <p>
                            <label for="post_code">Post Code:</label>
                            <input type="text" id="post_code" name="post_code" class="form-control" style="width: 170px"
                                   required
                                <?php if (isset($post)) {
                                    echo "value='$post'";
                                } ?>>
                        </p>
                        <?php if ($ffpost_code == '1') { ?>
                            <script>
                                $('#lookup_field').setupPostcodeLookup({
                                    api_key: '<?php if (isset($PostCodeKey)) {
                                        echo $PostCodeKey;
                                    } ?>',
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
                    </div>
                    <br>
                    <br>
                    <center>
                        <button type="submit" class="btn btn-success"><i class="fa fa-plus"></i> Add Client</button>
                    </center>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
