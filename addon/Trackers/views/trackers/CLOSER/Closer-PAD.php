<div class="container">
    <table id="tracker" class="table table-condensed">
        <thead>
        <tr>
            <th>#</th>
            <th>Date</th>
            <th>Closer</th>
            <th>Agent</th>
            <th>Client</th>
            <th>Phone</th>
            <th>Premium</th>
            <th>Our Premium</th>
            <th>Comments</th>
            <th>Insurer</th>
            <th>DISPO</th>
            <th></th>
        </tr>
        </thead>

        <?php

        $i = 0;

        foreach ($CloserPadList as $TRACKER_EDIT_result):

            $i++;

            $TID = $TRACKER_EDIT_result['tracker_id'];
            $agent = $TRACKER_EDIT_result['agent'];
            $closer = $TRACKER_EDIT_result['closer'];
            $client = $TRACKER_EDIT_result['client'];
            $phone = $TRACKER_EDIT_result['phone'];
            $currentPremium = $TRACKER_EDIT_result['current_premium'];
            $ourPremium = $TRACKER_EDIT_result['our_premium'];
            $comments = $TRACKER_EDIT_result['comments'];
            $sale = $TRACKER_EDIT_result['sale'];
            $insurer = $TRACKER_EDIT_result['insurer'];
            $updatedDate = $TRACKER_EDIT_result['updated_date'];
            $cbTime = $TRACKER_EDIT_result['timeDeadline'];
            $cbDate = $TRACKER_EDIT_result['dayDeadline'];

            switch ($sale):
                case "QCBK":
                    $TRK_sale = "Quoted Callback";
                    $TRK_BG = "#cc99ff";
                    break;
                case "SALE":
                    $TRK_sale = "SALE";
                    $TRK_BG = "#00ff00";
                    break;
                case "QQQ":
                    $TRK_sale = "Quoted";
                    $TRK_BG = "#66ccff";
                    break;
                case "NoCard":
                    $TRK_sale = "No Card Details";
                    $TRK_BG = "##cc0000";
                    break;
                case "QUN":
                    $TRK_sale = "Underwritten";
                    $TRK_BG = "#ff0066";
                    break;
                case "QNQ":
                    $TRK_sale = "No Quote";
                    $TRK_BG = "#ffcc00";
                    break;
                case "DIDNO":
                    $TRK_sale = "Quote Not Beaten";
                    $TRK_BG = "#ff6600";
                    break;
                case "QML":
                    $TRK_sale = "Quote Mortgage Lead";
                    $TRK_BG = "#669900";
                    break;
                case "QDE":
                    $TRK_sale = "Decline";
                    $TRK_BG = "#FF0000";
                    break;
                case "Thought we were an insurer":
                    $TRK_sale = "Insurer";
                    $TRK_BG = "#ff33cc";
                    break;
                case "Info all wrong":
                    $TRK_sale = "Info all wrong";
                    $TRK_BG = "#ff33cc";
                    break;
                case "Hangup on XFER":
                    $TRK_sale = "Hang Up";
                    $TRK_BG = "#ff33cc";
                    break;
                default:
                    $TRK_BG = "#ffffff";
            endswitch;

            if ($hello_name == 'Keith' && $sale == 'SALE') {
            } else {

                ?>
                <tr <?php if ($TRK_sale == 'SALE') {
                    echo "class='success' style='font-weight:bold'";
                } elseif ($TRK_sale == 'Quoted') {
                    echo "class='info'";
                } ?> >
                    <td>
                        <?php if (isset($i)) {
                            echo $i;
                        } ?>
                    </td>
                    <td><?php if (isset($updatedDate)) {
                            echo $updatedDate;
                        } ?></td>

                    <td><?php if (isset($closer)) {
                            echo $closer;
                        } ?>
                    </td>
                    <td><?php if (isset($agent)) {
                            echo $agent;
                        } ?>

                    </td>
                    <td><?php if (isset($client)) {
                            echo $client;
                        } ?></td>
                    <td><?php if (isset($phone)) {
                            echo $phone;
                        } ?></td>
                    <td><?php if (isset($currentPremium)) {
                            echo $currentPremium;
                        } ?></td>
                    <td><?php if (isset($ourPremium)) {
                            echo $ourPremium;
                        } ?></td>
                    <td><?php if (isset($comments)) {
                            echo $comments;
                        } ?></td>
                    <td><?php if (isset($insurer)) {
                            echo $insurer;
                        } ?>
                    </td>
                    <td style="background-color:<?php echo $TRK_BG; ?>;">
                        <?php if (isset($sale)) {
                            echo $TRK_sale;

                        } ?>
                    </td>

                    <td>
                        <a href="/addon/Trackers/Closers.php?EXECUTE=1&TID=<?php if (isset($TID)) {
                            echo $TID;
                        } ?>" class="btn btn-success btn-sm"><i class="fa fa-save"></i></a>
                    </td>
                </tr>

                <?php if (isset($cbDate)) { ?>

                    <tr <?php if ($TRK_sale == 'SALE') {
                        echo "class='success' style='font-weight:bold'";
                    } elseif ($TRK_sale == 'QQQ') {
                        echo "class='info'";
                    } ?> >
                        <th>Callback Time</th>
                        <th>Callback Date</th>
                        <th>Callback</th>
                    </tr>

                    <td>
                        <?php if (isset($cbTime)) {
                            echo $cbTime;
                        } else {
                            echo 'Not set';
                        } ?>
                    </td>
                    <td><?php if (isset($cbDate)) {
                            echo $cbDate;
                        } else {
                            echo 'Not set';
                        } ?></td>
                    <td><?php if (isset($cbDate)) {
                            echo $cbDate;
                        } else {
                            echo 'Not set';
                        } ?></td>

                    <?php
                }
            }

        endforeach; ?>
    </table>
</div>
