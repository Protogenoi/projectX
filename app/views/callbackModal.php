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

?>

<div id='CK_MODAL' class='modal fade' role='dialog'>
    <div class='modal-dialog modal-lg'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal'>&times;</button>
                <h4 class='modal-title'><i class="fa fa-clock-o"></i> Set a Callback</h4>
            </div>
            <div class='modal-body'>

                <ul class="nav nav-pills nav-justified">
                    <li class="active"><a data-toggle="pill" href="#CB_ONE">New Callback</a></li>
                    <li><a data-toggle="pill" href="#CB_TWO">Active Callbacks</a></li>
                </ul>

                <div class="panel">
                    <div class="panel-body">
                        <div class="tab-content">
                            <div id="CB_ONE" class="tab-pane fade in active">
                                <div class="col-lg-12 col-md-12">

                                    <form class="form-horizontal"
                                          action='php/AddCallback.php?setcall=y&search=<?php echo $search; ?>'
                                          method='POST'>
                                        <fieldset>

                                            <div class='container'>
                                                <div class='row'>
                                                    <div class='col-md-4'>
                                                        <div class='form-group'>
                                                            <select id='getcallback_client'
                                                                    name='callbackclient'
                                                                    class='form-control'>
                                                                <option
                                                                    value='<?php echo $clientonefull; ?>'><?php echo $clientonefull; ?></option>
                                                                <?php if (isset($clienttwofull)) { ?>
                                                                    <option
                                                                        value='<?php echo $clienttwofull; ?>'><?php echo $clienttwofull; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class='row'>
                                                    <div class='col-md-4'>
                                                        <div class='form-group'>
                                                            <select id='assign' name='assign'
                                                                    class='form-control'>
                                                                <option
                                                                    value='<?php echo $hello_name; ?>'><?php echo $hello_name; ?></option>

                                                                <?php
                                                                $calluser = $pdo->prepare("SELECT login, real_name from users where extra_info ='User'");
                                                                $calluser->execute() or die(print_r($calluser->errorInfo(),
                                                                    true));
                                                                if ($calluser->rowCount() > 0) {
                                                                    while ($row = $calluser->fetch(PDO::FETCH_ASSOC)) {
                                                                        ?>

                                                                        <option
                                                                            value='<?php echo $row['login']; ?>'><?php echo $row['real_name']; ?></option>

                                                                        <?php
                                                                    }
                                                                }
                                                                ?>

                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class='col-md-4'>
                                                        <div class="form-group">
                                                            <div class='input-group date'
                                                                 id='datetimepicker1'>
                                                                <input type='text' class="form-control"
                                                                       id="callback_date"
                                                                       name="callbackdate"
                                                                       placeholder="YYYY-MM-DD" value="<?php
                                                                if (isset($CB_DATE)) {
                                                                    echo $CB_DATE;
                                                                }
                                                                ?>" required/>
                                                                <span class="input-group-addon">
                                                                            <span
                                                                                class="glyphicon glyphicon-calendar"></span>
                                                                        </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class='col-md-4'>
                                                        <div class="form-group">
                                                            <div class='input-group date clockpicker'>
                                                                <input type='text' class="form-control"
                                                                       id="clockpicker" name="callbacktime"
                                                                       placeholder="24 Hour Format"
                                                                       value="<?php
                                                                       if (isset($CB_TIME)) {
                                                                           echo $CB_TIME;
                                                                       }
                                                                       ?>" required/>
                                                                <span class="input-group-addon">
                                                                            <span
                                                                                class="glyphicon glyphicon-time"></span>
                                                                        </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class='col-md-4'>
                                                        <div class="form-group">
                                                            <select id="callreminder" name="callreminder"
                                                                    class="form-control" required>
                                                                <option value="">Reminder</option>
                                                                <option value="-5 minutes">5mins</option>
                                                                <option value="-10 minutes">10mins</option>
                                                                <option value="-15 minutes">15mins</option>
                                                                <option value="-20 minutes">20mins</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class='col-md-8'>
                                                        <div class="form-group">
                                                                    <textarea class="form-control summernote"
                                                                              id="textarea" name="callbacknotes"
                                                                              placeholder="Call back notes"><?php
                                                                        if (isset($NOTES)) {
                                                                            echo $NOTES;
                                                                        }
                                                                        ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="btn-group">
                                                <button id="callsub" name="callsub" class="btn btn-primary">
                                                    <i
                                                        class='fa  fa-check-circle-o'></i> New callback
                                                </button>
                                            </div>
                                        </fieldset>
                                    </form>

                                </div>
                            </div>
                            <div id="CB_TWO" class="tab-pane fade">
                                <div class="row">

                                    <?php
                                    $query = $pdo->prepare("SELECT CONCAT(callback_time, ' - ', callback_date) AS calltimeid, callback_date, callback_time, reminder, CONCAT(callback_date, ' - ',callback_time)AS ordersort, client_id, id, client_name, notes, complete from scheduled_callbacks WHERE client_id=:CID AND complete='n' ORDER BY ordersort ASC");
                                    $query->bindParam(':CID', $search, PDO::PARAM_INT);
                                    $query->execute();
                                    if ($query->rowCount() > 0) {
                                        ?>

                                        <table class="table table-hover">
                                            <thead>
                                            <th>Client</th>
                                            <th>Callback</th>
                                            <th></th>
                                            </thead>

                                            <?php
                                            while ($calllist = $query->fetch(PDO::FETCH_ASSOC)) {
                                                $callbackid = $calllist['id'];
                                                $search = $calllist['client_id'];
                                                $NAME = $calllist['client_name'];
                                                $TIME = $calllist['calltimeid'];
                                                $NOTES = $calllist['notes'];
                                                $REMINDER = $calllist['reminder'];
                                                $CB_DATE = $calllist['callback_date'];
                                                $CB_TIME = $calllist['callback_time'];
                                                echo "<tr>";
                                                echo "<td class='text-left'><a href='/app/Client.php?search=$search'>" . $calllist['client_name'] . "</a></td>";
                                                echo "<td class='text-left'>" . $calllist['calltimeid'] . "</td>";
                                                echo "<td><a href='/app/php/AddCallback.php?search=$search&CBK_ID=$callbackid&EXECUTE=1' class='btn btn-success btn-sm'><i class='fa fa-check'></i> Complete</a></td>";
                                                echo "</tr>";
                                                ?>

                                            <?php } ?>
                                        </table>

                                        <?php
                                    } else {
                                        echo "<br><br><div class=\"notice notice-warning\" role=\"alert\"><strong>Info!</strong> No call backs found</div>";
                                    }
                                    ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class='modal-footer'>
                    <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
