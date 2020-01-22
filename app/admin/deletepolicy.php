<?php
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
$page_protect->access_page(filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS), "", 9);
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

if ($ACCESS_LEVEL < 9) {

    header('Location: /../../../index.php?AccessDenied&USER=' . $hello_name . '&COMPANY=' . $COMPANY_ENTITY);
    die;

}

$DeleteLifePolicy = filter_input(INPUT_GET, 'DeleteLifePolicy', FILTER_SANITIZE_SPECIAL_CHARS);
$home = filter_input(INPUT_GET, 'home', FILTER_SANITIZE_SPECIAL_CHARS);

if (empty($DeleteLifePolicy)) {
    $DeleteLifePolicy = filter_input(INPUT_GET, 'DeleteLGPolicy', FILTER_SANITIZE_SPECIAL_CHARS);
}

$ADL_PAGE_TITLE = "Delete Policy";
require_once(BASE_URL . '/app/core/head.php');

?>
<link rel="stylesheet" href="/resources/lib/sweet-alert/sweet-alert.min.css"/>
<script src="/resources/lib/jquery/jquery-3.0.0.min.js"></script>
<script src="/resources/templates/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
<script src="/resources/lib/sweet-alert/sweet-alert.min.js"></script>
</head>

<body>

<?php require_once(BASE_URL . '/includes/navbar.php');


if (isset($home)) {
    $CID = filter_input(INPUT_GET, 'CID', FILTER_SANITIZE_NUMBER_INT);
    $PID = filter_input(INPUT_GET, 'PID', FILTER_SANITIZE_NUMBER_INT);

    $query = $pdo->prepare("SELECT
                                  client_id, 
                                  id, 
                                  client_name, 
                                  sale_date, 
                                  policy_number, 
                                  premium, 
                                  type, 
                                  insurer, 
                                  added_date, 
                                  commission, 
                                  status, 
                                  added_by, 
                                  updated_by, 
                                  updated_date, 
                                  closer, 
                                  lead, 
                                  cover  
                            FROM 
                                home_policy 
                            WHERE 
                                id=:PID 
                            AND 
                                client_id=:CID");
    $query->bindParam(':PID', $PID, PDO::PARAM_INT);
    $query->bindParam(':CID', $CID, PDO::PARAM_INT);
    $query->execute();
    $data2 = $query->fetch(PDO::FETCH_ASSOC); ?>

    <div class="container">
        <div class="policyview">
            <div class="notice notice-danger fade in">
                <a href="#" class="close" data-dismiss="alert">&times;</a>
                <strong>Warning!</strong> You are about to permanently delete this Policy
                (<?php echo $data2["policy_number"] ?>) from the database.
            </div>
        </div>

        <div class="panel-group">
            <div class="panel panel-danger">
                <div class="panel-heading"><i class="fa fa-exclamation-triangle"></i> Delete Policy</div>
                <div class="panel-body">
                    <div class="column-right">


                        <form class="AddClient">
                            <p>
                                <label for="created">Added By</label>
                                <input type="text" value="<?php echo $data2["added_by"]; ?>" class="form-control"
                                       readonly style="width: 200px">
                            </p>
                            <p>
                                <label for="created">Date Added</label>
                                <input type="text" value="<?php echo $data2["added_date"]; ?>" class="form-control"
                                       readonly style="width: 200px">
                            </p>
                            <p>
                                <label for="created">Edited By</label>
                                <input type="text"
                                       value="<?php if (!empty($data2["updated_date"] && $data2["added_date"] != $data2["updated_date"])) {
                                           echo $data2["updated_by"];
                                       } ?>" class="form-control" readonly style="width: 200px">
                            </p>
                            <p>
                                <label for="created">Date Edited</label>
                                <input type="text" value="<?php if ($data2["added_date"] != $data2["updated_date"]) {
                                    echo $data2["updated_date"];
                                } ?>" class="form-control" readonly style="width: 200px">
                            </p>
                        </form>
                        <form id="from1" id="form1" class="AddClient" enctype="multipart/form-data" method="POST"
                              action="/php/deletepolicysubmit.php?home&CID=<?php echo $CID; ?>&PID=<?php echo $PID; ?>">
                            <button name='delete' class="btn btn-danger"><span
                                    class="glyphicon glyphicon-exclamation-sign"></span> Delete Policy
                            </button>
                        </form>

                    </div>

                    <form class="AddClient">
                        <div class="column-left">

                            <p>
                                <label for="client_name">Policy Holder</label>
                                <input type="text" id="client_name" name="client_name"
                                       value="<?php echo $data2['client_name']; ?>" class="form-control" readonly
                                       style="width: 200px">
                            </p>


                            <p>
                                <label for="sale_date">Sale Date:</label>
                                <input type="text" id="sale_date" name="sale_date"
                                       value="<?php echo $data2["sale_date"]; ?>" class="form-control" readonly
                                       style="width: 200px">
                            </p>


                            <p>
                                <label for="policy_number">Policy Number</label>
                                <input type="text" id="policy_number" name="policy_number"
                                       value="<?php echo $data2["policy_number"]; ?>" class="form-control" readonly
                                       style="width: 200px">
                            </p>


                            <p>
                                <label for="type">Type</label>
                                <input type="text" value="<?php echo $data2["type"]; ?>" class="form-control" readonly
                                       style="width: 200px">
                            </p>


                            <p>
                                <label for="insurer">Insurer</label>
                                <input type="text" value="<?php echo $data2["insurer"]; ?>" class="form-control"
                                       readonly style="width: 200px">
                            </p>


                        </div>

                        <p>
                        <div class="form-row">
                            <label for="premium">Premium:</label>
                            <div class="input-group">
                                <span class="input-group-addon">£</span>
                                <input style="width: 170px" type="number" value="<?php echo $data2[premium] ?>" min="0"
                                       step="0.01" data-number-to-fixed="2" data-number-stepfactor="100"
                                       class="form-control currency" id="premium" name="premium" class="form-control"
                                       readonly style="width: 200px"/>
                            </div>
                        </p>

                        <p>
                        <div class="form-row">
                            <label for="commission">Commission</label>
                            <div class="input-group">
                                <span class="input-group-addon">£</span>
                                <input style="width: 170px" type="number" value="<?php echo $data2[commission] ?>"
                                       min="0" step="0.01" data-number-to-fixed="2" data-number-stepfactor="100"
                                       class="form-control currency" id="commission" name="commission"
                                       class="form-control" readonly style="width: 200px"/>
                            </div>
                        </p>

                        <p>
                        <div class="form-row">
                            <label for="cover">Cover Amount</label>
                            <div class="input-group">
                                <span class="input-group-addon">£</span>
                                <input style="width: 170px" type="number" value="<?php echo $data2['cover']; ?>" min="0"
                                       step="0.01" data-number-to-fixed="2" data-number-stepfactor="100"
                                       class="form-control currency" id="cover" name="cover" class="form-control"
                                       readonly style="width: 200px"/>
                            </div>
                        </p>


                        <p>
                            <label for="PolicyStatus">Policy Status</label>
                            <input type="text" value="<?php echo $data2['status']; ?>" class="form-control" readonly
                                   style="width: 200px">
                            </select>
                        </p>

                        <p>
                            <label for="closer">Closer:</label>
                            <input type='text' id='closer' name='closer' value="<?php echo $data2["closer"]; ?>"
                                   class="form-control" readonly style="width: 200px">
                        </p>

                        <p>
                            <label for="lead">Lead Gen:</label>
                            <input type='text' id='lead' name='lead' value="<?php echo $data2["lead"]; ?>"
                                   class="form-control" readonly style="width: 200px">
                        </p>

                    </form>
                </div>

            </div>
        </div>
    </div>

<?php }

if (isset($DeleteLifePolicy)) {
if ($DeleteLifePolicy == '1') {

$PID = filter_input(INPUT_POST, 'policyID', FILTER_SANITIZE_NUMBER_INT);
$CID = filter_input(INPUT_POST, 'CID', FILTER_SANITIZE_NUMBER_INT);

$query = $pdo->prepare("SELECT
                                     client_id, 
                                     id, 
                                     polterm,
                                     client_name, 
                                     sale_date, 
                                     application_number, 
                                     policy_number, 
                                     premium, 
                                     type, 
                                     insurer, 
                                     submitted_by, 
                                     commission, 
                                     CommissionType, 
                                     policystatus, 
                                     submitted_date, 
                                     edited, 
                                     date_edited, 
                                     drip, 
                                     comm_term, 
                                     soj, 
                                     closer, 
                                     lead, 
                                     covera 
                                FROM 
                                    client_policy 
                                WHERE 
                                    id = :PID AND client_id=:CID");
$query->bindParam(':PID', $PID, PDO::PARAM_INT);
$query->bindParam(':CID', $CID, PDO::PARAM_INT);
$query->execute();
$data2 = $query->fetch(PDO::FETCH_ASSOC);

?>

<div class="container">

    <div class="warningalert">
        <div class="notice notice-danger fade in">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            <strong>Warning!</strong> You are about to permanently delete this Policy
            (<?php echo $data2["policy_number"] ?>) from the database.
        </div>

        <div class="panel-group">
            <div class="panel panel-danger">
                <div class="panel-heading">Delete Policy</div>
                <div class="panel-body">
                    <form class="AddClient">
                        <br>


                        <div class="column-left">

                            <p>
                                <label for="client_name">Policy Holder</label>
                                <input class="form-control" style="width: 170px" type="text" id="client_name"
                                       name="client_name" value="<?php echo $data2['client_name']; ?>" disabled>


                            <p>
                                <label for="soj">Single or Joint:</label>
                                <select class="form-control" style="width: 170px" name="soj" disabled>
                                    <option value="<?php echo $data2['soj']; ?>"><?php echo $data2['soj']; ?></option>
                                </select>
                            </p>

                            <p>
                                <label for="sale_date">Sale Date:</label>
                                <input class="form-control" style="width: 170px" type="text" id="sale_date"
                                       name="sale_date"
                                       value="<?php echo $data2["sale_date"]; ?>" disabled>


                            <p>
                                <label for="policy_number">Policy Number</label>
                                <input class="form-control" style="width: 170px" type="text" id="policy_number"
                                       name="policy_number" value="<?php echo $data2["policy_number"]; ?>" disabled>


                            <p>
                                <label for="application_number">Application Number:</label>
                                <input class="form-control" style="width: 170px" type="text" id="application_number"
                                       name="application_number" value="<?php echo $data2["application_number"]; ?>"
                                       disabled>


                            <p>
                                <label for="type">Type</label>
                                <select class="form-control" style="width: 170px" name="type" style="width: 200px"
                                        disabled>
                                    <option value="<?php echo $data2["type"]; ?>"><?php echo $data2["type"]; ?></option>
                                </select>


                            <p>
                                <label for="insurer">Insurer</label>
                                <select class="form-control" style="width: 170px" name="insurer" style="width: 200px"
                                        disabled>
                                    <option
                                        value="<?php echo $data2["insurer"]; ?>"><?php echo $data2["insurer"]; ?></option>
                                </select>
                            </p>

                        </div>


                        <p>
                        <div class="form-row">
                            <label for="premium">Premium:</label>
                            <div class="input-group">
                                <span class="input-group-addon">£</span>
                                <input style="width: 140px" type="number" value="<?php echo $data2['premium']; ?>"
                                       min="0"
                                       step="0.01" data-number-to-fixed="2" data-number-stepfactor="100"
                                       class="form-control currency" id="premium" name="premium" disabled/>
                            </div>
                        </p>

                        <p>
                        <div class="form-row">
                            <label for="commission">Commission</label>
                            <div class="input-group">
                                <span class="input-group-addon">£</span>
                                <input style="width: 140px" type="number" value="<?php echo $data2['commission']; ?>"
                                       min="0" step="0.01" data-number-to-fixed="2" data-number-stepfactor="100"
                                       class="form-control currency" id="commission" name="commission" disabled/>
                            </div>
                        </p>

                        <p>
                        <div class="form-row">
                            <label for="polterm">Policy Term</label>
                            <div class="input-group">
                                <span class="input-group-addon">yrs</span>
                                <input style="width: 130px" type="text" class="form-control" id="polterm" name="polterm"
                                       value="<?php echo $data2['polterm']; ?>" disabled/>
                            </div>
                        </p>

                        <p>
                            <label for="CommissionType">Commission Type</label>
                            <select class="form-control" style="width: 170px" name="CommissionType" style="width: 200px"
                                    disabled>
                                <option
                                    value="<?php echo $data2["CommissionType"]; ?>"><?php echo $data2["CommissionType"]; ?></option>
                            </select>
                        </p>

                        <p>
                            <label for="comm_term">Clawback Term</label>
                            <select class="form-control" style="width: 170px" name="comm_term" disabled>
                                <option
                                    value="<?php echo $data2["comm_term"]; ?>"><?php echo $data2["comm_term"]; ?></option>
                            </select>
                        </p>

                        <p>
                        <div class="form-row">
                            <label for="commission">Drip</label>
                            <div class="input-group">
                                <span class="input-group-addon">£</span>
                                <input style="width: 140px" type="number" value="<?php echo $data2["drip"]; ?>" min="0"
                                       step="0.01" data-number-to-fixed="2" data-number-stepfactor="100"
                                       class="form-control currency" id="drip" name="drip" disabled/>
                            </div>
                        </p>

                        <p>
                            <label for="PolicyStatus">Policy Status</label>
                            <select class="form-control" style="width: 170px" name="PolicyStatus" style="width: 200px"
                                    disabled>
                                <option
                                    value="<?php echo $data2['policystatus']; ?>"><?php echo $data2['policystatus']; ?></option>
                            </select>
                        </p>

                        <label for="closer">Closer:</label>
                        <input class="form-control" style="width: 170px" type='text' id='closer' name='closer'
                               style="width: 170px" value="<?php echo $data2["closer"]; ?>" disabled>


                        <br>

                        <p>
                            <label for="lead">Lead Gen:</label>
                            <input class="form-control" style="width: 170px" type='text' id='lead' name='lead'
                                   style="width: 170px" value="<?php echo $data2["lead"]; ?>" disabled>

                    </form>


                    <form id="from1" id="form1" class="AddClient" method="POST"
                          action="php/deletepolicysubmit.php?DeleteLifePolicy=1">
                        <input type="hidden" id="deletepolicyID" name="deletepolicyID"
                               value="<?php echo $data2["id"]; ?>">
                        <input type="hidden" id="client_id" name="client_id" value="<?php echo $data2["client_id"]; ?>">
                        <input type="hidden" id="name" name="name" value="<?php echo $data2['client_name']; ?>">
                        <input type="hidden" id="policy_number" name="policy_number"
                               value="<?php echo $data2["policy_number"]; ?>">
                        <button name='delete' class="btn btn-danger"><span
                                class="glyphicon glyphicon-exclamation-sign"></span> Delete Policy
                        </button>
                    </form>
                    <?php }
                    } ?>
                    <script>
                        document.querySelector('#from1').addEventListener('submit', function (e) {
                            var form = this;
                            e.preventDefault();
                            swal({
                                    title: "Delete policy?",
                                    text: "You will not be able to recover any deleted data!",
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
                                            text: 'Policy details deleted!',
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
                </div>
            </div>
        </div>
    </div>
</body>
</html>
