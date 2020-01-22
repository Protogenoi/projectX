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

<button type="button" class="btn btn-warning" data-toggle="modal"
        data-target="#addEwsStats">Add to EWS
</button>

<div id="addEwsStats" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    &times;
                </button>
                <h4 class="modal-title">Add EWS</h4>
            </div>
            <div class="modal-body">

                <form method="post"
                      action="/addon/Life/earlyWarningSystem/php/clientAddEwsStats.php?EXECUTE=1&CID=<?php echo $search; ?>">


                    <div class="col-md-12">

                        <div class="col-md-4">
                            <label for="name">Client name:</label>
                            <input type="text" name="customer"
                                   class="form-control"
                                   value="<?php if (isset($clientonefull)) {
                                       echo $clientonefull;
                                   } ?>">
                        </div>

                        <div class="col-md-4">
                            <label for="name">Insurer:</label>
                            <input type="text" name="insurer"
                                   class="form-control"
                                   value="<?php if (isset($WHICH_COMPANY)) {
                                       echo $WHICH_COMPANY;
                                   } ?>">
                        </div>

                        <div class="col-md-4">
                            <label for="name">Sale Date
                                date:</label>
                            <input type="text" id="saleDateEws" name="saleDate"
                                   class="form-control"
                                   placeholder="YYYY-MM-DD">
                        </div>

                        <div class="col-md-4">
                            <label for="policyNumber">Policy:</label>
                            <input type="text" id="policyNumber" name="policyNumber"
                                   class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label for="subDate">EWS Date</label>
                            <input type="text" id="ewsDate" name="ewsDate"
                                   class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label for="subDate">CB Date
                                date:</label>
                            <input type="text" id="cbDate" name="cbDate"
                                   class="form-control">
                        </div>


                        <div class="col-md-4">
                            <label for="name">Comms:</label>
                            <input name="comms" class="form-control"
                                   type="number" min="0" step="0.01"
                                   data-number-to-fixed="2"
                                   data-number-stepfactor="100">
                        </div>

                        <div class="col-md-4">
                            <label for="name">Closer:</label>
                            <input type="text" name="closer"
                                   class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label for="status">Status:</label>
                            <select name="status" class="form-control"
                                    required>
                                <option value="">Select Status</option>
                                <option
                                        value="Not converted" <?php if (isset($getEwsStatsResponse['status']) && $getEwsStatsResponse['status'] == 'Not converted') {
                                    echo 'selected';
                                } ?> >Not converted
                                </option>
                                <option
                                        value="Converted" <?php if (isset($getEwsStatsResponse['status']) && $getEwsStatsResponse['status'] == 'Converted') {
                                    echo 'selected';
                                } ?> >Converted
                                </option>
                                <option
                                        value="Submitted" <?php if (isset($getEwsStatsResponse['status']) && $getEwsStatsResponse['status'] == 'Submitted') {
                                    echo 'selected';
                                } ?> >Submitted
                                </option>
                                <option
                                        value="Issue" <?php if (isset($getEwsStatsResponse['status']) && $getEwsStatsResponse['status'] == 'Issue') {
                                    echo 'selected';
                                } ?> >Issue
                                </option>
                                <option
                                        value="Cancelled before submitted" <?php if (isset($getEwsStatsResponse['status']) && $getEwsStatsResponse['status'] == 'Cancelled before submitted') {
                                    echo 'selected';
                                } ?> >Cancelled before submitted
                                </option>
                                <option
                                        value="Underwritten" <?php if (isset($getEwsStatsResponse['status']) && $getEwsStatsResponse['status'] == 'Underwritten') {
                                    echo 'selected';
                                } ?> >Underwritten
                                </option>
                                <option value="Delayed start date" <?php if (isset($getEwsStatsResponse['status']) && $getEwsStatsResponse['status'] == 'Delayed start date') {
                                    echo 'selected';
                                } ?>>Delayed start date
                                </option>
                                <option
                                        value="LAPSED" <?php if (isset($getEwsStatsResponse['status']) && $getEwsStatsResponse['status'] == 'LAPSED') {
                                    echo 'selected';
                                } ?> >LAPSED
                                </option>
                                <option
                                        value="DD ISSUE" <?php if (isset($getEwsStatsResponse['status']) && $getEwsStatsResponse['status'] == 'DD ISSUE') {
                                    echo 'selected';
                                } ?> >DD ISSUE
                                </option>
                                <option
                                        value="CFO" <?php if (isset($getEwsStatsResponse['status']) && $getEwsStatsResponse['status'] == 'CFO') {
                                    echo 'selected';
                                } ?> >CFO
                                </option>
                                <option
                                        value="REINSTATED" <?php if (isset($getEwsStatsResponse['status']) && $getEwsStatsResponse['status'] == 'REINSTATED') {
                                    echo 'selected';
                                } ?> >REINSTATED
                                </option>
                                <option
                                        value="CANCELLED" <?php if (isset($getEwsStatsResponse['status']) && $getEwsStatsResponse['status'] == 'CANCELLED') {
                                    echo 'selected';
                                } ?> >CANCELLED
                                </option>
                                <option
                                        value="WILL CANCEL" <?php if (isset($getEwsStatsResponse['status']) && $getEwsStatsResponse['status'] == 'WILL CANCEL') {
                                    echo 'selected';
                                } ?> >WILL CANCEL
                                </option>
                                <option
                                        value="REDRAWN" <?php if (isset($getEwsStatsResponse['status']) && $getEwsStatsResponse['status'] == 'REDRAWN') {
                                    echo 'selected';
                                } ?> >REDRAWN
                                </option>
                            </select>
                        </div>

                    </div>

                    <br>
                    <br>

                    <div class="col-md-12">
                        <label for="comments" id="comments">Comments</label>
                        <textarea class="summernote" id="notes"
                                  name="notes"><?php if (isset($getEwsStatsResponse['notes'])) {
                                echo $getEwsStatsResponse['notes'];
                            } ?></textarea>
                    </div>

                    <div class="col-md-12">

                        <div class='col-md-4'>
                            <div class="form-group">
                                <div class='input-group date'>
                                    <input type='text' class="form-control"
                                           id="padDeadline"
                                           name="padDeadline"
                                           value="<?php
                                           if (isset($getEwsStatsResponse['dayDeadline'])) {
                                               echo $getEwsStatsResponse['dayDeadline'];
                                           }
                                           ?>"/>
                                    <span class="input-group-addon">
                                                                            <span class="glyphicon glyphicon-calendar"></span>
                                                                        </span>
                                </div>
                            </div>
                        </div>

                        <div class='col-md-4'>
                            <div class="form-group">
                                <div class='input-group date clockpicker'>
                                    <input type='text' class="form-control"
                                           id="padTime" name="padTime"
                                           placeholder="24 Hour Format"
                                           value="<?php
                                           if (isset($getEwsStatsResponse['timeDeadline'])) {
                                               echo $getEwsStatsResponse['timeDeadline'];
                                           }
                                           ?>"/>
                                    <span class="input-group-addon">
                                                                            <span class="glyphicon glyphicon-time"></span>
                                                                        </span>
                                </div>
                            </div>
                        </div>

                        <div class='col-md-4'>
                            <div class="form-group">
                                <select id="padTimeReminder"
                                        name="padTimeReminder"
                                        class="form-control">
                                    <option value="">Reminder</option>
                                    <option value="5" <?php if (isset($padUpdateResult['timeReminder']) && $timeReminder == 5) {
                                        echo "selected";
                                    } ?> >5mins
                                    </option>
                                    <option value="10" <?php if (isset($padUpdateResult['timeReminder']) && $timeReminder == 10) {
                                        echo "selected";
                                    } ?> >10mins
                                    </option>
                                    <option value="15" <?php if (isset($padUpdateResult['timeReminder']) && $timeReminder == 15) {
                                        echo "selected";
                                    } ?> >15mins
                                    </option>
                                    <option value="20" <?php if (isset($padUpdateResult['timeReminder']) && $timeReminder == 20) {
                                        echo "selected";
                                    } ?> >20mins
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class='col-md-4'>
                            <div class="form-group">
                                <select id="sendNotification"
                                        name="sendNotification"
                                        class="form-control">
                                    <option value="No">Send alert?</option>
                                    <option value="Yes" <?php if (isset($padUpdateResult['timeReminder'])) {
                                        echo "selected";
                                    } ?> >Yes
                                    <option value="Complete">Complete
                                    </option>
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-12">
                        <button type="submit"
                                class="btn btn-success btn-block"><span
                                    class="glyphicon glyphicon-edit"></span>
                            Add to EWS
                        </button>
                    </div>


                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning"
                        data-dismiss="modal">Close
                </button>
            </div>

        </div>

    </div>
</div>
