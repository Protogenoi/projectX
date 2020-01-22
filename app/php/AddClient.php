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

use ADL\clientNote;

require_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_SPECIAL_CHARS) . '/app/core/doc_root.php';

require_once(BASE_URL . '/classes/access_user/access_user_class.php');
$page_protect = new Access_user;
$page_protect->access_page(filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS), "", 3);
$hello_name = ($page_protect->user_full_name != "") ? $page_protect->user_full_name : $page_protect->user;

require_once(BASE_URL . '/includes/adl_features.php');

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

if (isset($ffanalytics) && $ffanalytics == '1') {
    require_once(BASE_URL . '/app/analyticstracking.php');
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

    header('Location: /../../../index.php?AccessDenied&USER=' . $hello_name . '&COMPANY=' . $COMPANY_ENTITY);
    die;

}

$EXECUTE = filter_input(INPUT_GET, 'EXECUTE', FILTER_SANITIZE_NUMBER_INT);

if (isset($EXECUTE) && $EXECUTE == 1) {


    $override = filter_input(INPUT_GET, 'override', FILTER_SANITIZE_SPECIAL_CHARS);

    $INSURER = filter_input(INPUT_POST, 'custype', FILTER_SANITIZE_SPECIAL_CHARS);
    $INSURER_ARRAY_ONE = array("Vitality", "Aviva", "One Family", "Royal London");

    $TITLE = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
    $first = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_SPECIAL_CHARS);
    $last = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_SPECIAL_CHARS);
    $dob = filter_input(INPUT_POST, 'dob', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $phone = filter_input(INPUT_POST, 'phone_number', FILTER_SANITIZE_NUMBER_INT);
    $alt = filter_input(INPUT_POST, 'alt_number', FILTER_SANITIZE_NUMBER_INT);
    $TITLE2 = filter_input(INPUT_POST, 'title2', FILTER_SANITIZE_SPECIAL_CHARS);
    $first2 = filter_input(INPUT_POST, 'first_name2', FILTER_SANITIZE_SPECIAL_CHARS);
    $last2 = filter_input(INPUT_POST, 'last_name2', FILTER_SANITIZE_SPECIAL_CHARS);
    $dob2 = filter_input(INPUT_POST, 'dob2', FILTER_SANITIZE_SPECIAL_CHARS);
    $email2 = filter_input(INPUT_POST, 'email2', FILTER_SANITIZE_EMAIL);
    $add1 = filter_input(INPUT_POST, 'address1', FILTER_SANITIZE_SPECIAL_CHARS);
    $add2 = filter_input(INPUT_POST, 'address2', FILTER_SANITIZE_SPECIAL_CHARS);
    $add3 = filter_input(INPUT_POST, 'address3', FILTER_SANITIZE_SPECIAL_CHARS);
    $town = filter_input(INPUT_POST, 'town', FILTER_SANITIZE_SPECIAL_CHARS);
    $post = filter_input(INPUT_POST, 'post_code', FILTER_SANITIZE_SPECIAL_CHARS);

    $convertClient = filter_input(INPUT_POST, 'convertClient', FILTER_SANITIZE_SPECIAL_CHARS);

    $TITLE_ARRAY = array("Mr", "Dr", "Miss", "Ms", "Mrs", "Other");

    if (!in_array($TITLE, $TITLE_ARRAY)) {
        $TITLE = "Other";
    }

    if (!empty($TITLE2)) {
        if (!in_array($TITLE2, $TITLE_ARRAY)) {
            $TITLE2 = "Other";
        }
    }

    $correct_dob = date("Y-m-d", strtotime($dob));
    if (!empty($dob2)) {
        $correct_dob2 = date("Y-m-d", strtotime($dob2));
    } else {
        $correct_dob2 = "NA";
    }
    $database = new Database();
    $database->beginTransaction();

    $database->query("SELECT client_id, first_name, last_name FROM client_details WHERE post_code=:post AND address1 =:add1 AND owner=:OWNER");
    $database->bind(':OWNER', $COMPANY_ENTITY);
    $database->bind(':post', $post);
    $database->bind(':add1', $add1);
    $database->execute();

    if ($database->rowCount() >= 1) {
        $row = $database->single();

        $dupeclientid = $row['client_id'];

        $DUPE_CLIENT_EXISTS = 1;

    }

    if (isset($override) && $override == 'ignoreDupe') {
        unset($DUPE_CLIENT_EXISTS);
    }

    if (empty($DUPE_CLIENT_EXISTS)) {

        $database->query("INSERT INTO
                    client_details
                SET 
                    owner=:OWNER, 
                    company=:company, 
                    title=:title, 
                    first_name=:first, 
                    last_name=:last, 
                    dob=:dob, 
                    email=:email, 
                    phone_number=:phone, 
                    alt_number=:alt, 
                    title2=:title2, 
                    first_name2=:first2, 
                    last_name2=:last2, 
                    dob2=:dob2, 
                    email2=:email2, 
                    address1=:add1, 
                    address2=:add2, 
                    address3=:add3, 
                    town=:town, 
                    post_code=:post, 
                    submitted_by=:hello, 
                    recent_edit=:hello2");
        $database->bind(':OWNER', $COMPANY_ENTITY);
        $database->bind(':company', $INSURER);
        $database->bind(':title', $TITLE);
        $database->bind(':first', $first);
        $database->bind(':last', $last);
        $database->bind(':dob', $correct_dob);
        $database->bind(':email', $email);
        $database->bind(':phone', $phone);
        $database->bind(':alt', $alt);
        $database->bind(':title2', $TITLE2);
        $database->bind(':first2', $first2);
        $database->bind(':last2', $last2);
        $database->bind(':dob2', $correct_dob2);
        $database->bind(':email2', $email2);
        $database->bind(':add1', $add1);
        $database->bind(':add2', $add2);
        $database->bind(':add3', $add3);
        $database->bind(':town', $town);
        $database->bind(':post', $post);
        $database->bind(':hello', $hello_name);
        $database->bind(':hello2', $hello_name);
        $database->execute();
        $CID = $database->lastInsertId();

        $database->endTransaction();

        if ($database->rowCount() >= 0) {

            require_once(BASE_URL . '/includes/ADL_PDO_CON.php');
            require_once(BASE_URL . '/class/clientNote.php');

            if (isset($convertClient)) {
                $clientNoteMessage = "Client Converted";
            } else {
                $clientNoteMessage = "Client Uploaded";
            }

            $clientNoteNoteType = "Client Added";
            $clientNoteAddedBy = $hello_name;
            $clientNoteReference = $TITLE . " " . $first . " " . $last;

            $addTimelineNotes = new ADL\clientNote($CID, $pdo);
            $result = $addTimelineNotes->addClientNote($clientNoteMessage,
                $clientNoteNoteType,
                $clientNoteAddedBy,
                $clientNoteReference);

            //CONVERT POTENTIAL CLIENT

            if (isset($convertClient) && is_numeric($convertClient)) {

                $getTimelineNotes = new ADL\clientNote($convertClient, $pdo);
                $timelineNotesTimelineNotes = $getTimelineNotes->allPotentialClientNote();

                foreach ($timelineNotesTimelineNotes as $rows):

                    $sent_by = $rows['sent_by'];
                    $client_name = $rows['client_name'];
                    $note_type = $rows['note_type'];
                    $message = $rows['message'];
                    $date_sent = $rows['date_sent'];

                    $query = $pdo->prepare("INSERT INTO client_note SET client_id=:CID, client_name=:REF, sent_by=:SENT, note_type=:NOTE, message=:MSG, date_sent=:date_sent");
                    $query->bindParam(':CID', $CID, PDO::PARAM_INT);
                    $query->bindParam(':SENT', $sent_by, PDO::PARAM_STR, 100);
                    $query->bindParam(':REF', $client_name, PDO::PARAM_STR);
                    $query->bindParam(':NOTE', $note_type, PDO::PARAM_STR, 2500);
                    $query->bindParam(':MSG', $message, PDO::PARAM_STR);
                    $query->bindParam(':date_sent', $date_sent, PDO::PARAM_STR);

                    $query = $pdo->prepare("SELECT tracker_id FROM closer_trackers WHERE phone = :phone");
                    $query->bindParam(':phone', $phone, PDO::PARAM_STR);
                    $query->execute();
                    $EID_RESULT = $query->fetch(PDO::FETCH_ASSOC);

                    $TID = $EID_RESULT['tracker_id'];

                    if (isset($TID) && is_numeric($TID)) {

                        $status = 'Sale';

                        $UPDATE = $pdo->prepare("UPDATE closer_trackers set sale=:sale, insurer=:INSURER, client=:client, phone=:phone WHERE tracker_id=:id");
                        $UPDATE->bindParam(':id', $TID, PDO::PARAM_INT);
                        $UPDATE->bindParam(':INSURER', $INSURER, PDO::PARAM_STR);
                        $UPDATE->bindParam(':client', $client_name, PDO::PARAM_STR);
                        $UPDATE->bindParam(':phone', $phone, PDO::PARAM_STR);
                        $UPDATE->bindParam(':sale', $status, PDO::PARAM_STR);
                        $UPDATE->execute();

                    }

                    if ($query->execute()) {
                        $query = $pdo->prepare("DELETE FROM potential_clients WHERE client_id=:CID LIMIT 1");
                        $query->bindParam(':CID', $convertClient, PDO::PARAM_INT);
                        $query->execute();
                    }


                endforeach;

            }

            if ($result == 'success') {

                $toastrTitle = 'New client added!';
                $toastrMessage = "$clientNoteReference ($INSURER)";
                $toastrResponse = 1;


                header('Location: ../Client.php?toastrResponse=' . $toastrResponse . '&toastrMessage=' . $toastrMessage . '&toastrTitle=' . $toastrTitle . '.&search=' . $CID);
                die;

            } else {

                $toastrTitle = 'Timeline error!';
                $toastrMessage = 'Error!';
                $toastrResponse = 2;

                header('Location: ../Client.php?toastrResponse=' . $toastrResponse . '&toastrMessage=' . $toastrMessage . '&toastrTitle=' . $toastrTitle . '.&search=' . $CID);
                die;

            }


        }

    }


    if ($INSURER == 'Home Insurance') {

        header('Location: /../../app/Client.php?search=' . $CID);
        die;

    }

    if (isset($DUPE_CLIENT_EXISTS) && $DUPE_CLIENT_EXISTS == 1) {

        if (isset($ffpost_code) && $ffpost_code == 1) {
            require_once(BASE_URL . '/includes/ADL_PDO_CON.php');

            $PostcodeQuery = $pdo->prepare("select api_key from api_keys WHERE type ='PostCode' limit 1");
            $PostcodeQuery->execute() or die(print_r($query->errorInfo(), true));
            $PDre = $PostcodeQuery->fetch(PDO::FETCH_ASSOC);
            $PostCodeKey = $PDre['api_key'];
        }
        $ADL_PAGE_TITLE = "Dupe Client";
        require_once(BASE_URL . '/app/core/head.php');

        ?>

        <link rel="stylesheet" href="/resources/templates/ADL/main.css" type="text/css"/>
        <link rel="stylesheet" href="/resources/templates/ADL/PostCode.css" type="text/css"/>
        <link rel="stylesheet" href="/resources/lib/jquery-ui-1.11.4/jquery-ui.min.css">
        <script type="text/javascript" language="javascript" src="/resources/lib/jquery/jquery-3.0.0.min.js"></script>
        <script type="text/javascript" language="javascript"
                src="/resources/lib/jquery-ui-1.11.4/jquery-ui.min.js"></script>
        <script src="/resources/templates/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
        <?php if (isset($ffpost_code) && $ffpost_code == 1) { ?>
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

        <?php require_once(BASE_URL . '/includes/navbar.php'); ?>

        <div class="container">


            <div class="notice notice-danger fade in"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Error!</strong>
                Duplicate address details found<br><br>Existing client name: <?php echo "$first $last"; ?><br>
                Address: <?php echo "$add1 $post"; ?>.<br><br><a
                        href='/app/Client.php?search=<?php echo $dupeclientid; ?>' class="btn btn-default"
                        target="_blank"><i
                            class='fa fa-eye'> View Client</i></a></div>

            <div class="panel-group">
                <div class="panel panel-primary">
                    <div class="panel-heading"><i class="fa fa-user-plus"></i> Add Client</div>
                    <div class="panel-body">

                        <form class="AddClient" id="AddProduct"
                              action="/app/php/AddClient.php?EXECUTE=1&override=ignoreDupe" method="POST"
                              autocomplete="off">

                            <div class="col-md-4">

                                <h3><span class="label label-info">Client Details (1)</span></h3>
                                <br>

                                <p>
                                <div class="form-group">
                                    <label for="custtype">Product:</label>
                                    <select class="form-control" name="custype" id="custype" style="width: 170px"
                                            required>
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
                                                ?>" <?php if ($CUSTYPE == $CUSTYPE) {
                                                    echo 'selected';
                                                } ?>><?php
                                                    if (isset($CUSTYPE)) {
                                                        echo $CUSTYPE;
                                                    }
                                                    ?></option>
                                                <?php
                                            }
                                        }

                                        if ($ffhome == 1) { ?>
                                            <option value="Home Insurance">Home Insurance</option>
                                        <?php }

                                        ?>

                                    </select>
                                </div>
                                </p>

                                <p>
                                <div class="form-group">
                                    <label for="title">Title:</label>
                                    <select class="form-control" name="title" id="title" style="width: 170px" required>
                                        <option value="Mr" <?php if (isset($TITLE) && $TITLE == 'Mr') {
                                            echo 'selected';
                                        } ?>>Mr
                                        </option>
                                        <option value="Dr" <?php if (isset($TITLE) && $TITLE == 'Dr') {
                                            echo 'selected';
                                        } ?>>Dr
                                        </option>
                                        <option value="Prof" <?php if (isset($TITLE) && $TITLE == 'Prof') {
                                            echo 'selected';
                                        } ?>>Prof
                                        </option>
                                        <option value="Miss" <?php if (isset($TITLE) && $TITLE == 'Miss') {
                                            echo 'selected';
                                        } ?>>Miss
                                        </option>
                                        <option value="Mrs" <?php if (isset($TITLE) && $TITLE == 'Mrs') {
                                            echo 'selected';
                                        } ?>>Mrs
                                        </option>
                                        <option value="Ms" <?php if (isset($TITLE) && $TITLE == 'Ms') {
                                            echo 'selected';
                                        } ?>>Ms
                                        </option>
                                        <option value="Other" <?php if (isset($TITLE) && $TITLE == 'Other') {
                                            echo 'selected';
                                        } ?>>Other
                                        </option>
                                    </select>
                                </div>
                                </p>

                                <p>
                                    <label for="first_name">First Name:</label>
                                    <input type="text" id="first_name" name="first_name" class="form-control"
                                           style="width: 170px" required value="<?php if (isset($first)) {
                                        echo $first;
                                    } ?>">
                                </p>
                                <p>
                                    <label for="last_name">Last Name:</label>
                                    <input type="text" id="last_name" name="last_name" class="form-control"
                                           style="width: 170px" required value="<?php if (isset($last)) {
                                        echo $last;
                                    } ?>">
                                </p>
                                <p>
                                    <label for="dob">Date of Birth:</label>
                                    <input type="text" id="dob" name="dob" class="form-control" style="width: 170px"
                                           required value="<?php if (isset($dob)) {
                                        echo $dob;
                                    } ?>">
                                </p>
                                <p>
                                    <label for="email">Email:</label>
                                    <input type="email" id="email" class="form-control" style="width: 170px"
                                           name="email" placeholder="Use no@email.no for no email address"
                                           title="Use no@email.no for no email address"
                                           value="<?php if (isset($email)) {
                                               echo $email;
                                           } ?>">
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
                                        <option value=""></option>
                                        <option value="Mr" <?php if (isset($TITLE2) && $TITLE2 == 'Mr') {
                                            echo 'selected';
                                        } ?>>Mr
                                        </option>
                                        <option value="Dr" <?php if (isset($TITLE2) && $TITLE2 == 'Dr') {
                                            echo 'selected';
                                        } ?>>Dr
                                        </option>
                                        <option value="Prof" <?php if (isset($TITLE2) && $TITLE2 == 'Prof') {
                                            echo 'selected';
                                        } ?>>Prof
                                        </option>
                                        <option value="Miss" <?php if (isset($TITLE2) && $TITLE2 == 'Miss') {
                                            echo 'selected';
                                        } ?>>Miss
                                        </option>
                                        <option value="Mrs" <?php if (isset($TITLE2) && $TITLE2 == 'Mrs') {
                                            echo 'selected';
                                        } ?>>Mrs
                                        </option>
                                        <option value="Ms" <?php if (isset($TITLE2) && $TITLE2 == 'Ms') {
                                            echo 'selected';
                                        } ?>>Ms
                                        </option>
                                        <option value="Other" <?php if (isset($TITLE2) && $TITLE2 == 'Other') {
                                            echo 'selected';
                                        } ?>>Other
                                        </option>
                                    </select>
                                </div>
                                </p>

                                <p>
                                    <label for="first_name2">First Name:</label>
                                    <input type="text" id="first_name2" name="first_name2" class="form-control"
                                           style="width: 170px" value="<?php if (isset($first2)) {
                                        echo $first2;
                                    } ?>">
                                </p>
                                <p>
                                    <label for="last_name2">Last Name:</label>
                                    <input type="text" id="last_name2" name="last_name2" class="form-control"
                                           style="width: 170px" value="<?php if (isset($last2)) {
                                        echo $last2;
                                    } ?>">
                                </p>
                                <p>
                                    <label for="dob2">Date of Birth:</label>
                                    <input type="text" id="dob2" name="dob2" class="form-control" style="width: 170px"
                                           value="<?php if (isset($dob2)) {
                                               echo $dob2;
                                           } ?>">
                                </p>
                                <p>
                                    <label for="email2">Email:</label>
                                    <input type="email" id="email2" name="email2" class="form-control"
                                           style="width: 170px" value="<?php if (isset($email2)) {
                                        echo $email2;
                                    } ?>">
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
                                           title="Enter a valid phone number" value="<?php if (isset($phone)) {
                                        echo $phone;
                                    } ?>">
                                </p>
                                <p>
                                    <label for="alt_number">Alt Number:</label>
                                    <input type="tel" id="alt_number" name="alt_number" class="form-control"
                                           style="width: 170px" pattern=".{11}|.{11,11}" maxlength="11"
                                           title="Enter a valid phone number" value="<?php if (isset($alt)) {
                                        echo $alt;
                                    } ?>">
                                </p>
                                <br>
                                <?php if ($ffpost_code == '1') { ?>
                                    <div id="lookup_field"></div>
                                    <?php
                                }

                                if ($ffpost_code == '0') {
                                    ?>

                                    <div class="alert alert-info"><strong>Info!</strong> Post code lookup feature not
                                        enabled.
                                    </div>

                                <?php } ?>
                                <p>
                                    <label for="address1">Address Line 1:</label>
                                    <input type="text" id="address1" name="address1" class="form-control"
                                           style="width: 170px" required value="<?php if (isset($add1)) {
                                        echo $add1;
                                    } ?>">
                                </p>
                                <p>
                                    <label for="address2">Address Line 2:</label>
                                    <input type="text" id="address2" name="address2" class="form-control"
                                           style="width: 170px" value="<?php if (isset($add2)) {
                                        echo $add2;
                                    } ?>">
                                </p>
                                <p>
                                    <label for="address3">Address Line 3:</label>
                                    <input type="text" id="address3" name="address3" class="form-control"
                                           style="width: 170px" value="<?php if (isset($add3)) {
                                        echo $add3;
                                    } ?>">
                                </p>
                                <p>
                                    <label for="town">Post Town:</label>
                                    <input type="text" id="town" name="town" class="form-control" style="width: 170px"
                                           value="<?php if (isset($town)) {
                                               echo $town;
                                           } ?>">
                                </p>
                                <p>
                                    <label for="post_code">Post Code:</label>
                                    <input type="text" id="post_code" name="post_code" class="form-control"
                                           style="width: 170px" required value="<?php if (isset($post)) {
                                        echo $post;
                                    } ?>">
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
                                <button type="submit" class="btn btn-warning"><i class="fa fa-plus"></i> Add client
                                    anyway!!
                                </button>
                            </center>
                        </form>
                    </div>
                </div>
            </div>


        </div>
        </body>
        </html>
    <?php }

} else {
    header('Location: /../../../../../CRMmain.php?error=1');
    die;
}
