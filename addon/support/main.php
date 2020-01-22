<?php
/** @noinspection PhpIncludeInspection */
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
 *
 */

require_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_SPECIAL_CHARS) . '/app/core/doc_root.php';

require_once(BASE_URL . '/classes/access_user/access_user_class.php');
$page_protect = new Access_user;
$page_protect->access_page(filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS), "", 3);
$hello_name = ($page_protect->user_full_name != "") ? $page_protect->user_full_name : $page_protect->user;

$USER_TRACKING = 0;

require_once(BASE_URL . '/includes/adl_features.php');

require_once(BASE_URL . '/includes/time.php');

if (isset($FORCE_LOGOUT) && $FORCE_LOGOUT == 1) {
    $page_protect->log_out();
}

require_once(BASE_URL . '/includes/Access_Levels.php');
require_once(BASE_URL . '/includes/adlfunctions.php');
require_once(BASE_URL . '/includes/ADL_PDO_CON.php');

if (isset($ffanalytics) && $ffanalytics == '1') {
    require_once(BASE_URL . '/app/analyticstracking.php');
}

if (isset($fferror) && $fferror == '0') {
    ini_SET('display_errors', 1);
    ini_SET('display_startup_errors', 1);
    error_reporting(E_ALL);
}

require_once(BASE_URL . '/classes/database_class.php');
require_once(BASE_URL . '/class/login/login.php');
$CHECK_USER_LOGIN = new UserActions($hello_name, "NoToken");
$CHECK_USER_LOGIN->CheckAccessLevel();

$USER_ACCESS_LEVEL = $CHECK_USER_LOGIN->CheckAccessLevel();

$ACCESS_LEVEL = $USER_ACCESS_LEVEL['ACCESS_LEVEL'];

if ($ACCESS_LEVEL < 3) {

    $page_protect->log_out();

}

require_once(BASE_URL . '/class/supportTickets.php');

$EXECUTE = filter_input(INPUT_GET, 'EXECUTE', FILTER_SANITIZE_NUMBER_INT);
$ticketID = filter_input(INPUT_GET, 'ticketID', FILTER_SANITIZE_NUMBER_INT);
$filterTickets = filter_input(INPUT_POST, 'filterTickets', FILTER_SANITIZE_STRING);
$filterCategory = filter_input(INPUT_POST, 'filterCategory', FILTER_SANITIZE_STRING);

$assignedUsers = [
    'Matt',
    'Nick',
    'Leigh',
    'Paul',
    'Charles',
    'Andrew',
    'UNASSIGNED',
    'Michael'
];

asort($assignedUsers);

$categoryArray = [
    'Tech - Internal',
    'Matt & Nick',
    'RTR',
    'Office',
    'Other'
];

asort($categoryArray);

$filterArray = [
    'Open',
    'Closed',
    'Answered',
    'Waiting Reply',
    'In progress'
];

asort($filterArray);

$ticketStatuses = [
    'Open',
    'Closed',
    'Waiting Reply',
    'In progess'
];

asort($ticketStatuses);


if (isset($EXECUTE)) {

    if ($EXECUTE == 1) {

        $selectSupportTicket = new ADL\supportTickets($pdo);
        $selectSupportTicket->setId($ticketID);
        // Restrict user to their assigned tasks only
        if (!in_array($hello_name, $Level_10_Access, true)) {
            $selectSupportTicket->setAssigned($hello_name);
        }
        $ticketComments = $selectSupportTicket->getSupportTicketsByID();

        $getSupportTickets = new ADL\supportTickets($pdo);
        // Restrict user to their assigned tasks only
        if (!in_array($hello_name, $Level_10_Access, true)) {
            $getSupportTickets->setAssigned($hello_name);
        }
        $supportTickets = $getSupportTickets->getSupportTickets();

        $getUploadedFiles = new ADL\supportTickets($pdo);
        $getUploadedFiles->setId($ticketID);
        $UploadedFiles = $getUploadedFiles->getUploadSupportTicketFilesByTicketID();

        $getSavedCredentials = new ADL\supportTickets($pdo);
        $getSavedCredentials->setId($ticketID);
        $savedCredentials = $getSavedCredentials->getSupportCredentials();

    }

    if ($EXECUTE == 2) {

        if ($filterTickets != "0" && $filterCategory == "0") {
            $getSupportTickets = new ADL\supportTickets($pdo);
            $getSupportTickets->setTicketStatus($filterTickets);
            // Restrict user to their assigned tasks only
            if (!in_array($hello_name, $Level_10_Access, true)) {
                $getSupportTickets->setAssigned($hello_name);
            }
            $supportTickets = $getSupportTickets->getSupportTicketsByStatus();

        } elseif ($filterTickets == "0" && $filterCategory != "0") {
            $getSupportTickets = new ADL\supportTickets($pdo);
            $getSupportTickets->setCategory($filterCategory);
            // Restrict user to their assigned tasks only
            if (!in_array($hello_name, $Level_10_Access, true)) {
                $getSupportTickets->setAssigned($hello_name);
            }
            $supportTickets = $getSupportTickets->getSupportTicketsByCategory();
        } elseif ($filterTickets != "0" && $filterCategory != "0") {
            $getSupportTickets = new ADL\supportTickets($pdo);
            $getSupportTickets->setCategory($filterCategory);
            $getSupportTickets->setTicketStatus($filterTickets);
            // Restrict user to their assigned tasks only
            if (!in_array($hello_name, $Level_10_Access, true)) {
                $getSupportTickets->setAssigned($hello_name);
            }
            $supportTickets = $getSupportTickets->getSupportTicketsByCategoryAndStatus();
        } else {
            $getSupportTickets = new ADL\supportTickets($pdo);
            // Restrict user to their assigned tasks only
            if (!in_array($hello_name, $Level_10_Access, true)) {
                $getSupportTickets->setAssigned($hello_name);
            }
            $supportTickets = $getSupportTickets->getSupportTickets();
        }

    }

    if ($EXECUTE == 3 || $EXECUTE == 4) {
        $getSupportTickets = new ADL\supportTickets($pdo);
        // Restrict user to their assigned tasks only
        if (!in_array($hello_name, $Level_10_Access, true)) {
            $getSupportTickets->setAssigned($hello_name);
        }
        $supportTickets = $getSupportTickets->getSupportTickets();
    }

} else {

    $getSupportTickets = new ADL\supportTickets($pdo);
    // Restrict user to their assigned tasks only
    if (!in_array($hello_name, $Level_10_Access, true)) {
        $getSupportTickets->setAssigned($hello_name);
    }
    $supportTickets = $getSupportTickets->getSupportTickets();

}

$getTicketStatusCount = new ADL\supportTickets($pdo);
// Restrict user to their assigned tasks only
if (!in_array($hello_name, $Level_10_Access, true)) {
    $getTicketStatusCount->setAssigned($hello_name);
}
$statusCounts = $getTicketStatusCount->getTicketStatusCounts();

$totalTicketCounts = 0;

if ($statusCounts != 'error') {
    foreach ($statusCounts as $rows):

        if ($rows['status'] == 'Open') {

            $openCount = $rows['counts'];
            $totalTicketCounts += $rows['counts'];

        } elseif ($rows['status'] == 'Closed') {

            $closedCount = $rows['counts'];
            $totalTicketCounts += $rows['counts'];

        } elseif ($rows['status'] == 'Waiting Reply') {

            $waitingCount = $rows['counts'];
            $totalTicketCounts += $rows['counts'];

        } elseif ($rows['status'] == 'In progess') {

            $inProgressCount = $rows['counts'];
            $totalTicketCounts += $rows['counts'];

        }

    endforeach;
}

$getTicketCategoryCount = new ADL\supportTickets($pdo);
// Restrict user to their assigned tasks only
if (!in_array($hello_name, $Level_10_Access, true)) {
    $getTicketCategoryCount->setAssigned($hello_name);
}
$categoryCounts = $getTicketCategoryCount->getTicketCategoryCounts();

if ($categoryCounts != 'error') {
    foreach ($categoryCounts as $rows):

        if ($rows['category'] == 'RTR') {

            $rtrCount = $rows['counts'];

        } elseif ($rows['category'] == 'Other') {

            $otherCount = $rows['counts'];

        } elseif ($rows['category'] == 'Tech - Internal') {

            $techCount = $rows['counts'];

        } elseif ($rows['category'] == 'Office') {

            $officeCount = $rows['counts'];

        }

    endforeach;
}

$ADL_PAGE_TITLE = "To Do";
require_once(BASE_URL . '/app/core/head.php');

?>
<link rel="stylesheet" href="/resources/lib/summernote-master/dist/summernote.css">
<style>
    ul.timeline {
        list-style-type: none;
        position: relative;
    }

    ul.timeline:before {
        content: ' ';
        background: #d4d9df;
        display: inline-block;
        position: absolute;
        left: 29px;
        width: 2px;
        height: 100%;
        z-index: 400;
    }

    ul.timeline > li {
        margin: 20px 0;
        padding-left: 20px;
    }

    ul.timeline > li:before {
        content: ' ';
        background: white;
        display: inline-block;
        position: absolute;
        border-radius: 50%;
        border: 3px solid #22c0e8;
        left: 20px;
        width: 20px;
        height: 20px;
        z-index: 400;
    }
</style>
<script type="text/javascript" src="/resources/lib/jquery/jquery-3.0.0.min.js"></script>
<script type="text/javascript" src="/resources/lib/jquery-ui-1.11.4/jquery-ui.min.js"></script>
<script type="text/javascript" src="/resources/lib/jquery-ui-1.11.4/external/jquery/jquery.js"></script>
<script type="text/javascript" src="/resources/templates/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/resources/lib/toastr/js/toastr.min.js"></script>
<script type="text/javascript" src="/resources/lib/summernote-master/dist/summernote.js"></script>
<script src="/resources/lib/moment/moment.js"></script>
<script type="text/javascript">
    $(function () {
        $('.summernote').summernote({
            height: 200
        });
    });
</script>
</head>

<body>
<?php
require_once(BASE_URL . '/includes/navbar.php');

?>

<div class="container">

    <div class="col-md-12" style="text-align: center; font-weight: bolder">


        <div class="col-md-2">
        </div>

        <div class="col-md-2">
            <div class="panel panel-danger">
                <div class="panel-heading">Open</div>
                <div class="panel-body" style="color: #ff0000"><?php if (isset($openCount)) {
                        echo $openCount;
                    } else {
                        echo 0;
                    } ?></div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="panel panel-primary">
                <div class="panel-heading">Closed</div>
                <div class="panel-body" style="color: #337ab7"><?php if (isset($closedCount)) {
                        echo $closedCount;
                    } else {
                        echo 0;
                    } ?></div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="panel panel-default">
                <div class="panel-heading">Answered</div>
                <div class="panel-body" style="color: #000000">0</div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="panel panel-success">
                <div class="panel-heading">In Progess</div>
                <div class="panel-body" style="color: #000000"><?php if (isset($inProgressCount)) {
                        echo $inProgressCount;
                    } else {
                        echo 0;
                    } ?></div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="panel panel-warning">
                <div class="panel-heading">Waiting Reply</div>
                <div class="panel-body" style="color: #000000"><?php if (isset($waitingCount)) {
                        echo $waitingCount;
                    } else {
                        echo 0;
                    } ?></div>
            </div>
        </div>

    </div>

    <div class="col-md-12" style="text-align: center; font-weight: bolder">

        <div class="col-md-2">
        </div>

        <div class="col-md-2">
            <div class="panel panel-info">
                <div class="panel-heading">Office</div>
                <div class="panel-body" style="color: #000000"><?php if (isset($officeCount)) {
                        echo $officeCount;
                    } else {
                        echo 0;
                    } ?></div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="panel panel-info">
                <div class="panel-heading">Other</div>
                <div class="panel-body" style="color: #000000"><?php if (isset($otherCount)) {
                        echo $otherCount;
                    } else {
                        echo 0;
                    } ?></div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="panel panel-info">
                <div class="panel-heading">RTR</div>
                <div class="panel-body" style="color: #000000"><?php if (isset($rtrCount)) {
                        echo $rtrCount;
                    } else {
                        echo 0;
                    } ?></div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="panel panel-info">
                <div class="panel-heading">Tech</div>
                <div class="panel-body" style="color: #000000"><?php if (isset($techCount)) {
                        echo $techCount;
                    } else {
                        echo 0;
                    } ?></div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="panel panel-info">
                <div class="panel-heading">Total</div>
                <div class="panel-body" style="color: #000000"><?php if (isset($totalTicketCounts)) {
                        echo $totalTicketCounts;
                    } else {
                        echo 0;
                    } ?></div>
            </div>
        </div>

    </div>


    <div class="col-md-12">

        <div class="col-md-2">

            <div class="panel panel-default">

                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-cog"></i> Settings</h3>
                </div>

                <div class="panel-body">

                    <div class="btn-group">
                        <a class="btn btn-xs btn-secondary btn-success" data-toggle="modal"
                           data-target="#createModal"><i
                                    class="fa fa-edit"></i> Create</a>
                    </div>

                    <div id="createModal" class="modal fade" role="dialog">
                        <div class="modal-dialog modal-lg">

                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Create New To do</h4>
                                </div>
                                <div class="modal-body">
                                    <form action="/addon/support/php/create.php?EXECUTE=1" method="post">
                                        <div class="form-group">
                                            <label for="task" class="control-label">Task:</label>
                                            <input type="text" class="form-control" id="task" name="task" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="category">Category</label>
                                            <select name="category" id="category" class="form-control" required>
                                                <option></option>

                                                <?php

                                                foreach ($categoryArray as $rows):
                                                    ?>
                                                    <option value="<?php echo $rows; ?>"><?php echo $rows; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="assign">Assign:</label>
                                            <select name="assign[]" id="assign" class="form-control" multiple="multiple"
                                                    required>
                                                <option></option>

                                                <?php

                                                foreach ($assignedUsers as $rows):
                                                    ?>
                                                    <option value="<?php echo $rows; ?>"><?php echo $rows; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="sendText">Send SMS/Email?</label>
                                            <select name="sendText" id="sendText" class="form-control">
                                                <option value="No">No</option>

                                                <?php

                                                $sendTxtArray = [
                                                    'Michael',
                                                    'Nick',
                                                    'Matt',
                                                    'Everyone'
                                                ];

                                                asort($sendTxtArray);

                                                foreach ($sendTxtArray as $rows):
                                                    ?>
                                                    <option value="<?php echo $rows; ?>"><?php echo $rows; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="content" class="control-label">Message:</label>
                                            <textarea class="form-control summernote" id="content"
                                                      name="content"></textarea>
                                        </div>

                                        <button type="submit" class="btn btn-block btn-success">Submit</button>

                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>

                        </div>
                    </div>

                    <br><br>

                    <form method="post" action="/addon/support/main.php?EXECUTE=2">

                        <div class="form-group">
                            <label for="filterTickets"><i class="fa fa-search"></i> Filter To do</label>
                            <select class="form-control" name="filterTickets" id="filterTickets">
                                <option value="0">Status</option>
                                <?php

                                foreach ($filterArray as $rows):
                                    ?>

                                    <option value="<?php echo $rows ?>" <?php if ($rows == $filterTickets) {
                                        echo "selected";
                                    } ?> ><?php echo $rows ?></option>

                                <?php endforeach; ?>

                            </select>

                            <select class="form-control" name="filterCategory" id="filterCategory">
                                <option value="0">Category</option>
                                <?php

                                foreach ($categoryArray as $rows):
                                    ?>

                                    <option value="<?php echo $rows ?>" <?php if ($rows == $filterCategory) {
                                        echo "selected";
                                    } ?> ><?php echo $rows ?></option>

                                <?php endforeach; ?>

                            </select>
                        </div>

                        <div class="btn-group">
                            <button type="submit" class="btn btn-xs btn-success"><i class="fa fa-filter"></i> Filter
                            </button>
                            <a href="?" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> Reset</a>
                        </div>

                    </form>

                </div>

            </div>

        </div>

        <div class="col-md-10">

            <ul class="nav nav-pills">
                <li class="active"><a data-toggle="pill" href="#allTab">All</a></li>
                <?php if (in_array($hello_name, $Level_10_Access, true)) { ?>
                    <li><a data-toggle="pill" href="#officeTab">Office</a></li>
                    <li><a data-toggle="pill" href="#rtrTab">RTR</a></li>
                    <li><a data-toggle="pill" href="#techTab">Tech - Int</a></li>
                    <?php if (in_array($hello_name, $ALL_ACCESS, true)) { ?>
                        <li><a data-toggle="pill" href="#mattAndNickTab">Matt Nick</a></li>
                    <?php }
                } ?>
            </ul>

            <?php

            if (in_array($hello_name, $Level_10_Access, true)) {
                // GET ALL TECH SUPPORT TICKETS
                $getSupportTickets = new ADL\supportTickets($pdo);
                $getSupportTickets->setCategory('Tech - Internal');
                $techSupportTickets = $getSupportTickets->getSupportTicketsByCategory();


                if (in_array($hello_name, $ALL_ACCESS, true)) {
                    // GET ALL TECH SUPPORT TICKETS
                    $getSupportTickets = new ADL\supportTickets($pdo);
                    $getSupportTickets->setCategory('Matt & Nick');
                    $mattAndNickTickets = $getSupportTickets->getSupportTicketsByCategory();

                }

                // GET ALL RTR SUPPORT TICKETS
                $getSupportTickets = new ADL\supportTickets($pdo);
                $getSupportTickets->setCategory('RTR');
                $rtrSupportTickets = $getSupportTickets->getSupportTicketsByCategory();

                // GET ALL OFFICE SUPPORT TICKETS
                $getSupportTickets = new ADL\supportTickets($pdo);
                $getSupportTickets->setCategory('Office');
                $officeSupportTickets = $getSupportTickets->getSupportTicketsByCategory();

            }

            ?>

            <div class="tab-content">
                <div id="allTab" class="tab-pane fade in active">
                    <?php require_once(BASE_URL . '/addon/support/views/all-view.php'); ?>
                </div>

                <?php if (in_array($hello_name, $Level_10_Access, true)) { ?>
                    <div id="rtrTab" class="tab-pane fade">
                        <?php require_once(BASE_URL . '/addon/support/views/rtr-view.php'); ?>
                    </div>

                    <div id="officeTab" class="tab-pane fade">
                        <?php require_once(BASE_URL . '/addon/support/views/office-view.php'); ?>
                    </div>

                    <div id="techTab" class="tab-pane fade">
                        <?php require_once(BASE_URL . '/addon/support/views/tech-view.php'); ?>
                    </div>

                    <?php if (in_array($hello_name, $ALL_ACCESS, true)) { ?>

                        <div id="mattAndNickTab" class="tab-pane fade">
                            <?php require_once(BASE_URL . '/addon/support/views/mattAndNick-view.php'); ?>
                        </div>
                    <?php }

                } ?>

            </div>

        </div>


    </div>

</div>

<?php

if (isset($EXECUTE)) {

    if ($EXECUTE == 1) { ?>

        <div id="editModal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">

                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">View To Do <?php if (isset($ticketID)) {
                                echo $ticketID;
                            } ?> Assigned to <?php if (isset($ticketComments[0]['assigned'])) {
                                echo $ticketComments[0]['assigned'];
                            } ?></h4>
                    </div>
                    <div class="modal-body">
                        <div class="container mt-5 mb-5">
                            <div class="row">
                                <div class="col-md-6 offset-md-3">
                                    <h4><?php echo "Task: " . $ticketComments[0]['task']; ?> </h4>

                                    <ul class="timeline">

                                        <?php

                                        foreach ($ticketComments as $rows):

                                            if (!empty($rows['content'])) {

                                                $supportContent = html_entity_decode($rows['content']); ?>


                                                <li>
                                                    <span style="color: #4081ff; font-weight: bolder"><?php echo $rows['addedBy']; ?></span>
                                                    <span id="myEditText<?php echo $rows['id']; ?>"
                                                          style="font-size: x-small; font-style: italic;"></span>
                                                    <script>
                                                        var time = moment('<?php echo $rows['addedDate']; ?>', 'YYYY-MM-DD hh:mm:s').fromNow();
                                                        document.getElementById("myEditText<?php echo $rows['id']; ?>").innerHTML = "(" + time + ")";
                                                    </script>
                                                    <p><?php echo $supportContent; ?></p>
                                                </li>


                                            <?php }

                                        endforeach;

                                        ?>

                                    </ul>
                                </div>
                            </div>
                        </div>

                        <?php if (isset($UploadedFiles[0]['addedBy'])) { ?>

                            <div class="col-md-12">
                                <div class="row">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">Uploads</div>
                                        <div class="panel-body">

                                            <?php

                                            if (isset($UploadedFiles) && $UploadedFiles != 'error') { ?>


                                                <?php

                                                foreach ($UploadedFiles as $rows): ?>

                                                    <a href="/addon/support/uploads/<?php echo $ticketID ?>/<?php echo $rows['fileName'] ?>"
                                                       target="_blank"><?php echo $rows['fileName']; ?></a>  |

                                                <?php endforeach;

                                            }

                                            ?>

                                        </div>
                                    </div>

                                </div>
                            </div>

                        <?php }

                        if (isset($savedCredentials[0]['cred_id_fk'])) {

                            ?>

                            <div class="notice notice-success" role="alert" style="text-align: center"><i
                                        class="fas fa-lock"></i> Credentials have been saved!
                            </div>

                        <?php } ?>

                        <form action="/addon/support/php/edit.php?EXECUTE=1&ticketID=<?php if (isset($ticketID)) {
                            echo $ticketID;
                        } ?>" method="post">

                            <div class="form-group">
                                <label for="category">Category:</label>
                                <select name="category" id="category" class="form-control">
                                    <option></option>
                                    <?php

                                    foreach ($categoryArray as $rows):
                                        ?>
                                        <option
                                                value="<?php echo $rows; ?>" <?php if ($rows == $ticketComments[0]['category']) {
                                            echo 'selected';
                                        } ?> ><?php echo $rows; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="ticketStatus">Status:</label>
                                <select name="ticketStatus" id="ticketStatus" class="form-control" required>
                                    <?php

                                    foreach ($ticketStatuses as $rows):
                                        ?>
                                        <option value="<?php echo $rows; ?>" <?php if ($rows == $ticketComments[0]['status']) {
                                            echo 'selected';
                                        } ?> ><?php echo $rows; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <?php if (in_array($hello_name, $Level_10_Access, true)) { ?>

                                <div class="form-group">
                                    <label for="assigned">Assigned:</label>
                                    <select name="assigned" id="assigned" class="form-control" required>
                                        <?php

                                        foreach ($assignedUsers as $rows):
                                            ?>
                                            <option value="<?php echo $rows; ?>" <?php if ($rows == $ticketComments[0]['assigned']) {
                                                echo 'selected';
                                            } ?> ><?php echo $rows; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                            <?php } ?>

                            <div class="form-group">
                                <label for="content" class="control-label">Message:</label>
                                <textarea class="form-control summernote" id="content" name="content"></textarea>
                            </div>

                            <button type="submit" class="btn btn-block btn-success">Submit</button>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>

        <script type="text/javascript">
            $(window).load(function () {
                $('#editModal').modal('show');
            });
        </script>

    <?php } elseif ($EXECUTE == 3) { ?>

        <div id="uploadModal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">

                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Upload files to To Do #<?php if (isset($ticketID)) {
                                echo $ticketID;
                            } ?></h4>
                    </div>
                    <div class="modal-body">


                        <form action="/addon/support/php/upload.php?EXECUTE=1&ticketID=<?php if (isset($ticketID)) {
                            echo $ticketID;
                        } ?>" method="POST"
                              enctype="multipart/form-data">
                            <label for="file"><input type="file" name="file[]" multiple/></label>

                            <button type="submit" class="btn btn-success" name="btn-upload"><i class="fa fa-upload"></i>
                                Upload file(s)
                            </button>
                        </form>


                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>

        <script type="text/javascript">
            $(window).load(function () {
                $('#uploadModal').modal('show');
            });
        </script>

    <?php }

    elseif ($EXECUTE == 4) { ?>

        <div id="credentialModal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">

                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Credentials for to To Do #<?php if (isset($ticketID)) {
                                echo $ticketID;
                            } ?></h4>
                    </div>
                    <div class="modal-body">

                        <form action="/addon/support/php/credentials.php?EXECUTE=1&ticketID=<?php if (isset($ticketID)) {
                            echo $ticketID;
                        } ?>" method="POST">

                            <div class="form-group">
                                <label for="site" class="control-label">Site:</label>
                                <input type="text" class="form-control" id="site" name="site">
                            </div>

                            <div class="form-group">
                                <label for="user" class="control-label">Email:</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>

                            <div class="form-group">
                                <label for="user" class="control-label">User:</label>
                                <input type="text" class="form-control" id="user" name="user" required>
                            </div>

                            <div class="form-group">
                                <label for="pass" class="control-label">Pass:</label>
                                <input type="password" class="form-control" id="pass" name="pass" required>
                            </div>

                            <div class="form-group">
                                <label for="company" class="control-label">Company:</label>
                                <input type="text" class="form-control" id="company" name="company" required>
                            </div>

                            <div class="form-group">
                                <label for="ipAddress" class="control-label">IP:</label>
                                <input type="text" class="form-control" id="ipAddress" name="ipAddress">
                            </div>


                            <div class="form-group">
                                <label for="info" class="control-label">Info:</label>
                                <textarea class="form-control summernote" id="info" name="info"
                                          required></textarea>
                            </div>

                            <button type="submit" class="btn btn-block btn-success">Submit</button>

                        </form>


                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>

        <script type="text/javascript">
            $(window).load(function () {
                $('#credentialModal').modal('show');
            });
        </script>

    <?php }

}
?>

<?php require_once(BASE_URL . '/app/php/toastr.php'); ?>
</body>
</html>
