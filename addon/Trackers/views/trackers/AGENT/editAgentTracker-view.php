<?php

foreach ($editTrackerList as $rows):


    $TID = $rows['tracker_id'];
    $agent = $rows['agent'];
    $closer = $rows['closer'];
    $client = $rows['client'];
    $phone = $rows['phone'];
    $currentPremium = $rows['current_premium'];
    $ourPremium = $rows['our_premium'];
    $comments = $rows['comments'];
    $sale = $rows['sale'];
    $insurer = $rows['insurer'];
    $updatedDate = $rows['updated_date'];
    $cbTime = $rows['timeDeadline'];
    $cbDate = $rows['dayDeadline'];

endforeach;

?>
<div class="container">
    <table id="tracker" class="table table-hover">
        <thead>
        <tr>
            <th>Agent</th>
            <th>Client</th>
            <th>Phone</th>
            <th>Current Premium</th>
            <th>Our Premium</th>
            <th>Notes</th>
            <th>Insurer</th>
            <th>DISPO</th>
            <th></th>
        </tr>
        </thead>

        <form method="POST" action="/addon/Trackers/php/Trackers.php?EXECUTE=2&TYPE=AGENT<?php if (isset($TID)) {
            echo "&tracker_id=$TID";
        } ?>">

            <input type="hidden" value="<?php echo $hello_name; ?>" name="closer">

            <td><input size="12" class="form-control" type="text" name="agent_name"
                       id="agent_name" value="<?php if (isset($agent)) {
                    echo $agent;
                } ?>"></td>
            <td><input size="12" class="form-control" type="text" name="client"
                       value="<?php if (isset($client)) {
                           echo $client;
                       } ?>"></td>
            <td><input size="12" class="form-control" type="text" name="phone"
                       value="<?php if (isset($phone)) {
                           echo $phone;
                       } ?>"></td>
            <td><input size="7" class="form-control" type="text" name="current_premium"
                       value="<?php if (isset($currentPremium)) {
                           echo $currentPremium;
                       } ?>"></td>
            <td><input size="7" class="form-control" type="text" name="our_premium"
                       value="<?php if (isset($ourPremium)) {
                           echo $ourPremium;
                       } ?>"></td>
            <td><input type="text" class="form-control" name="comments"
                       value="<?php if (isset($comments)) {
                           echo $comments;
                       } ?>"></td>
            <td><select name="INSURER" class="form-control" required>
                    <option value="NA">N/A</option>
                    <option value="Aegon" <?php if (isset($insurer) && $insurer == "Aegon") {
                        echo "selected";
                    } ?> >Aegon
                    </option>
                    <option value="Royal London" <?php if (isset($insurer) && $insurer == "Royal London") {
                        echo "selected";
                    } ?> >Royal London
                    </option>
                    <option value="LV" <?php if (isset($insurer) && $insurer == "LV") {
                        echo "selected";
                    } ?> >LV
                    </option>
                    <option value="Aviva" <?php if (isset($insurer) && $insurer == "Aviva") {
                        echo "selected";
                    } ?> >Aviva
                    </option>
                    <option value="One Family" <?php if (isset($insurer) && $insurer == "One Family") {
                        echo "selected";
                    } ?> >One Family
                    </option>
                    <option value="National Friendly" <?php if (isset($insurer) && $insurer == "National Friendly") {
                        echo "selected";
                    } ?> >National Friendly
                    </option>
                </select></td>
            <td><select name="sale" class="form-control" required>
                    <option value="">DISPO</option>
                    <option <?php if (isset($sale)) {
                        if ($sale == 'SALE') {
                            echo "selected";
                        }
                    } ?> value="SALE">Sale
                    </option>
                    <option <?php if (isset($sale)) {
                        if ($sale == 'QUN') {
                            echo "selected";
                        }
                    } ?> value="QUN">Underwritten
                    </option>
                    <option <?php if (isset($sale)) {
                        if ($sale == 'QQQ') {
                            echo "selected";
                        }
                    } ?> value="QQQ">Quoted
                    </option>
                    <option <?php if (isset($sale)) {
                        if ($sale == 'QNQ') {
                            echo "selected";
                        }
                    } ?> value="QNQ">No Quote
                    </option>
                    <option <?php if (isset($sale)) {
                        if ($sale == 'QML') {
                            echo "selected";
                        }
                    } ?> value="QML">Quote Mortgage Lead
                    </option>
                    <option <?php if (isset($sale)) {
                        if ($sale == 'QDE') {
                            echo "selected";
                        }
                    } ?> value="QDE">Decline
                    </option>
                    <option <?php if (isset($sale)) {
                        if ($sale == 'QCBK') {
                            echo "selected";
                        }
                    } ?> value="QCBK">Quoted Callback
                    </option>
                    <option <?php if (isset($sale)) {
                        if ($sale == 'NoCard') {
                            echo "selected";
                        }
                    } ?> value="NoCard">No Card
                    </option>
                    <option <?php if (isset($sale)) {
                        if ($sale == 'DIDNO') {
                            echo "selected";
                        }
                    } ?> value="DIDNO">Quote Not Beaten
                    </option>
                    <option <?php if (isset($sale)) {
                        if ($sale == 'DETRA') {
                            echo "selected";
                        }
                    } ?> value="DETRA">Declined but passed to upsale
                    </option>
                    <option <?php if (isset($sale)) {
                        if ($sale == 'Hangup on XFER') {
                            echo "selected";
                        }
                    } ?> value="Hangup on XFER">Hangup on XFER
                    </option>
                    <option <?php if (isset($sale)) {
                        if ($sale == 'Thought we were an insurer') {
                            echo "selected";
                        }
                    } ?> value="Thought we were an insurer">Thought we were an insurer
                    </option>
                    <option <?php if (isset($sale)) {
                        if ($sale == 'Other') {
                            echo "selected";
                        }
                    } ?> value="Other">Other
                    </option>
                </select>
            </td>

            <td>
                <button type="submit" class="btn btn-warning btn-sm"><i
                        class="fa fa-save"></i> UPDATE
                </button>
            </td>
            <td><a href="/addon/Trackers/Closers.php?EXECUTE=1" class="btn btn-danger btn-sm"><i
                        class="fa fa-ban"></i> CANCEL</a></td>

            <tr>
                <th>Callback Time</th>
                <th>Callback Date</th>
                <th>Callback</th>
            </tr>

            <td>
                <div class='input-group date clockpicker'><input
                        type='text'
                        class="form-control"
                        id="cbTime"
                        name="cbTime"
                        placeholder="24 Hour Format"
                        value="<?php if (isset($cbTime)) {
                            echo $cbTime;
                        } ?>"
                    /> <span class="input-group-addon">
                                                                            <span
                                                                                class="glyphicon glyphicon-time"></span>
                                                                        </span></div>
            </td>
            <td><input type="text" id="cbDate" name="cbDate" class="form-control"
                       value="<?php if (isset($cbDate)) {
                           echo $cbDate;
                       } ?>"></td>
            <td><select id="sendNotification"
                        name="sendNotification"
                        class="form-control">
                    <option value="No" selected>Delete call back</option>
                    <option value="Yes" <?php if (isset($cbDate)) {
                        echo "selected";
                    } ?>>Yes
                    <option value="Complete">Call back complete
                    </option>
                </select></td>
        </form>
    </table>
</div>
