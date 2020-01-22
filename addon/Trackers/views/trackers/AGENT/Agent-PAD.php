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

        $i = '0';

        foreach ($AgentPadList as $agentTracker):

            $i++;

            $TID = $agentTracker['tracker_id'];
            $agent = $agentTracker['agent'];
            $closer = $agentTracker['closer'];
            $client = $agentTracker['client'];
            $phone = $agentTracker['phone'];
            $currentPremium = $agentTracker['current_premium'];
            $ourPremium = $agentTracker['our_premium'];
            $comments = $agentTracker['comments'];
            $sale = $agentTracker['sale'];
            $insurer = $agentTracker['insurer'];
            $updatedDate = $agentTracker['updated_date'];
            $cbTime = $agentTracker['timeDeadline'];
            $cbDate = $agentTracker['dayDeadline'];

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
                    <a href="/addon/Trackers/Agent.php?EXECUTE=1&TID=<?php if (isset($TID)) {
                        echo $TID;
                    } ?>" class="btn btn-success btn-sm"><i class="fa fa-save"></i></a>
                </td>
            </tr>


        <?php endforeach ?>

    </table>
</div>
