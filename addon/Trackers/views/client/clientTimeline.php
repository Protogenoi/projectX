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

<br><br>
<script src="/resources/lib/moment/moment.js"></script>
<table class="table table-hover">
    <thead>
    <tr>
        <th>Client Tiimeline</th>
    </tr>
    <tr>
        <th>Date</th>
        <th>User</th>
        <th>Reference</th>
        <th>Note Type</th>
        <th>Message</th>
        <?php if (isset($hello_name) && $hello_name == 'Michael' && isset($editNotes) && $editNotes == 1) { ?>
            <th></th>
        <?php } ?>
    </tr>
    </thead>
    <?php

    if (!empty($timelineNotesTimelineNotes)) {

        $i = 0;

    foreach ($timelineNotesTimelineNotes

             as $timelineNotes) {

        $i++;

        if (isset($timelineNotes['client_name']) && $timelineNotes['client_name'] == 'Compliant') {
            $timelineNotes['note_type'] = "Compliant Logged";
        }

        switch ($timelineNotes['note_type']):
            case "Client Added":
            case "Potential Client Added":
                $TMicon = "fa-user-plus";
                break;
            case "Policy Deleted":
            case "All Workflows and Tasks have been deleted (Policy On Hold)!":
            case "All Workflows and Tasks have been deleted (Policy Issue)!":
            case "All Workflows and Tasks have been deleted (Policy Cancelled before submit)!":
            case "All Workflows and Tasks have been deleted (Policy Delayed start date)!":
            case "SMS notice dismissed":
                $TMicon = "fa-exclamation";
                break;
            case "WhatsApp Reply":
            case "WhatsApp Update":
            case "WhatsApp Delivered":
            case "WhatsApp Sent":
                $TMicon = "fa-whatsapp";
                break;
            case "CRM Alert":
            case "Policy Added":
            case "ADL Alert":
            case "Client SMS Reply":
                $TMicon = "fa-phone-square";
                break;
            case "Added to pad":
            case "Pad alert complete":
            case "Closer Tracker alert complete":
            case "Tracker Alert":
            case "Pad alert dismissed":
            case "Closer Tracker alert dismissed":
            case "Closer Tracker alert set":
            case "Updated pad":
                $TMicon = "fa-file-alt";
                break;
            case "LV EWS Uploaded":
            case "Aviva EWS Uploaded":
            case "Royal London EWS Uploaded":
            case "Aviva EWS Master Uploaded":
            case "Royal London EWS Master Uploaded":
            case "EWS Status update":
            case "EWS Stats Upload":
            case "EWS Update":
            case "SMS Failed";
            case "Compliant Logged":
            case "Email Failed";
            case "Response spamreport":
            case "Response dropped":
            case "Response deferred":
            case "Response bounce":
            case "Response unsubscribe":
            case "Response group_unsubscribe":
                $TMicon = "fa-exclamation-triangle";
                break;
            case "Deleted File Upload";
                $TMicon = "fa-trash";
                break;
            case "Financial Uploaded":
            case "Legal and General Financial Uploaded":
            case "LV Financial Uploaded":
            case "Royal London Financial Uploaded":
            case "Aviva Financial Uploaded":
            case "Vitality Financial Uploaded":
            case "One Family Financial Uploaded":
            case "WOL Financial Uploaded":
                $TMicon = "fa-pound-sign";
                break;
            case"LGPolicy Summary";
            case "RLpolicy":
            case "HSBCpolicy":
            case "EXETERpolicy":
            case"LVPolicy Summary";
            case"EXETERPolicy Summary";
            case"HSBCPolicy Summary";
            case "Dealsheet":
            case"LGpolicy";
            case"Zurichpolicy";
            case"Vitalitypolicy";
            case"Aegonpolicy";
            case"LVpolicy";
            case"SWpolicy";
            case"SWkeyfacts";
            case"LGkeyfacts";
            case"Aegonkeyfacts";
            case"Avivakeyfacts";
            case"LVkeyfacts";
            case"HSBCkeyfacts";
            case"EXETERkeyfacts";
            case"Zurichkeyfacts";
            case"Vitalitykeyfacts";
            case"RLkeyfacts";
            case"Avivapolicy";
            case"Recording";
            case"Closer Call Recording";
            case "Closer and Agent Call Recording":
            case"Agent Call Recording";
            case"Admin Call Recording";
            case "LifeCloserAudit":
            case "LifeLeadAudit":
            case"Other";
                $TMicon = "fa-upload";
                break;
            case stristr($timelineNotes['note_type'], "Tasks"):
                $TMicon = "fa-tasks";
                break;
            case stristr($timelineNotes['note_type'], "Callback"):
                $TMicon = "fa-calendar-check-o";
                break;
            case "Audit Submitted":
                $TMicon = "fa-headphones";
                break;
            case "Client Note":
            case "lifenotes":
            case "Policy Details Updated":
            case "Policy Update":
                $TMicon = "fa-pencil-alt";
                break;
            case "Task 24 48":
            case "Task 5 day":
            case "Task 7 day":
            case "Task 21 day":
            case "Task 48":
            case "Task CYD":
            case "Task 18 day":
            case "Tasks 24 48":
            case "Task 48 hour":
            case "Workflows and Tasks added!":
            case "Vitality Workflows and Tasks added!":
            case "Tasks 5 day":
            case "Tasks CYD":
            case "Tasks 18 day":
            case "Tasks Trust":
                $TMicon = "fa-tasks";
                break;
            case "Email Sent":
            case "Response: open":
            case "Response: delivered":
            case "Response: processed":
            case "Response: click":
            case "Response: group_resubscribe":
                $TMicon = "fa-envelope";
                break;
            case "Client Edited":
            case "TONIC Acount Updates ":
            case "Client Details Updated":
                $TMicon = "fa-edit";
                break;
            case "SMS Delivered":
            case "SMS Update":
                $TMicon = "fa-mobile-alt";
                break;
            case "Sent SMS":
            case "Callback":
            case stristr($timelineNotes['note_type'], "Sent SMS"):
            case stristr($timelineNotes['note_type'], "Already Sent SMS"):
                $TMicon = "fa-phone";
                break;
            default:
                $TMicon = "fa-bomb";
        endswitch;

        if (strpos($timelineNotes['sent_by'], ' ')) {
            $adlUser = substr($timelineNotes['sent_by'], 0, strpos($timelineNotes['sent_by'], ' '));
        } else {
            $adlUser = $timelineNotes['sent_by'];
        }

        $TIMELINE_MESSAGE = html_entity_decode($timelineNotes['message']);
        if ($timelineNotes['note_type'] == 'LifeCloserAudit') {
            $TIME_LINE_NOTE_TYPE = 'Closer Audit Upload';
        } elseif ($timelineNotes['note_type'] == 'LifeLeadAudit') {
            $TIME_LINE_NOTE_TYPE = 'Lead Audit Upload';
        } else {
            $TIME_LINE_NOTE_TYPE = $timelineNotes['note_type'];
        }

        ?>

        <tr>
            <td><?php echo $timelineNotes['date_sent']; ?><br><span id="myText<?php echo $i; ?>"
                                                                    style="font-size: x-small; font-style: italic;"></span>
            </td>
            <script>
                var time = moment('<?php echo $timelineNotes['date_sent']; ?>', 'YYYY-MM-DD hh:mm:s').fromNow();
                document.getElementById("myText<?php echo $i; ?>").innerHTML = "(" + time + ")";
                console.log(time);
            </script>
            <td <?php if ($adlUser == $hello_name) {
                echo "style='font-weight:bold'";
            } ?>><?php echo $adlUser; ?></td>
            <td><?php echo $timelineNotes['client_name']; ?></td>
            <td><i class='<?php if ($TMicon == 'fa-whatsapp') {
                    echo "fab $TMicon";
                } else {
                    echo "fa $TMicon";
                } ?>'></i> <?php echo $TIME_LINE_NOTE_TYPE; ?></td>
            <?php
            if (in_array($hello_name, $Level_3_Access, true)) {
                echo "<td><b>$TIMELINE_MESSAGE</b></td>";
            } else {
                echo "<td><b>$TIMELINE_MESSAGE</b></td>";
            }
            if (isset($hello_name) && $hello_name == 'Michael' && isset($editNotes) && $editNotes == 1) { ?>
            <th>
                <button type="button" class="btn btn-warning btn-xs" data-toggle="modal"
                        data-target="#clientTimelineModal<?php echo $i; ?>"><i class="fa fa-cogs"></i>
                </button>
            </th>
        </tr>

        <div id="clientTimelineModal<?php echo $i; ?>" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">

                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Edit Client Note</h4>
                    </div>
                    <div class="modal-body">

                        <form name="editClientNoteForm<?php echo $i; ?>"
                              id="editClientNoteForm<?php echo $i; ?>"
                              action="/app/php/clientNote.php?EXECUTE=2&CID=<?php echo $search; ?>&NID=<?php echo $timelineNotes['note_id']; ?>"
                              method="POST">

                            <div class="form-group">
                                <label class="col-md-12 control-label" for="textarea"></label>
                                <div class="col-md-12">
                                        <textarea id="editClientNoteMessage" name="editClientNoteMessage"
                                                  class="summernote" maxlength="2000"
                                                  required><?php echo $TIMELINE_MESSAGE; ?></textarea>
                                </div>
                            </div>

                            <div align="center">

                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="singlebutton"></label>
                                    <div class="col-md-4">
                                        <button class="btn btn-primary btn-block"><i
                                                class="fas fa-edit"></i> Edit
                                        </button>
                                    </div>
                                </div>

                        </form>

                        <a class="btn btn-danger btn-md deleteClientNote"
                           href="/app/php/clientNote.php?EXECUTE=1&CID=<?php echo $search; ?>&NID=<?php echo $timelineNotes['note_id']; ?>"><i
                                class="fas fa-trash"></i> Delete client note?</a>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
        </div>

        <script>
            document.querySelector('#editClientNoteForm<?php echo $i ?>').addEventListener('submit', function (e) {
                var form = this;
                e.preventDefault();
                swal({
                        title: "Edit client note?",
                        text: "Are you sure!",
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
                                title: 'Edit!',
                                text: 'Note edited!',
                                type: 'success'
                            }, function () {
                                form.submit();
                            });
                        } else {
                            swal("Cancelled", "Client note not edited", "error");
                        }
                    });
            });
        </script>

    <?php }
    }
    if (isset($hello_name) && $hello_name == 'Michael') { ?>
        <script type="text/javascript">
            var elems = document.getElementsByClassName('deleteClientNote');
            var confirmIt = function (e) {
                if (!confirm('Are you sure you want to delete this note?'))
                    e.preventDefault();
            };
            for (var i = 0, l = elems.length; i < l; i++) {
                elems[i].addEventListener('click', confirmIt, false);
            }
        </script>

        <?php

    }

    } else {
        echo "<br><br><div class=\"notice notice-warning\" role=\"alert\"><strong>Info!</strong> No data/information found (Client notes)</div>";
    }
    ?>
</table>
