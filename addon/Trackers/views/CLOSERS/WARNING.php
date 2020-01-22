<?php foreach ($TRACKER_WARNINGList as $TRACKER_WARNINGList_RESULTS): ?>

    <?php
    $TRACKER_WARNING_TOTAL = $TRACKER_WARNINGList_RESULTS['Total'];

    $TRACKER_WARNING_Sales = $TRACKER_WARNINGList_RESULTS['Sales'];
    $TRACKER_WARNING_NoCard = $TRACKER_WARNINGList_RESULTS['NoCard'];
    $TRACKER_WARNING_QDE = $TRACKER_WARNINGList_RESULTS['QDE'];
    $TRACKER_WARNING_QUN = $TRACKER_WARNINGList_RESULTS['QUN'];
    $TRACKER_WARNING_QNQ = $TRACKER_WARNINGList_RESULTS['QNQ'];
    $TRACKER_WARNING_DIDNO = $TRACKER_WARNINGList_RESULTS['DIDNO'];
    $TRACKER_WARNING_QCBK = $TRACKER_WARNINGList_RESULTS['QCBK'];
    $TRACKER_WARNING_QQQ = $TRACKER_WARNINGList_RESULTS['QQQ'];
    $TRACKER_WARNING_Other = $TRACKER_WARNINGList_RESULTS['Other'];
    $TRACKER_WARNING_Hangup = $TRACKER_WARNINGList_RESULTS['Hangup'];
    $TRACKER_WARNING_insurer = $TRACKER_WARNINGList_RESULTS['insurer'];
    $TRACKER_WARNING_QML = $TRACKER_WARNINGList_RESULTS['QML'];

    if ($TRACKER_WARNING_Sales > 0) {

        $TRACKER_SALES_PERCENT = $TRACKER_WARNING_Sales / $TRACKER_WARNING_TOTAL * 100;
        $TRACKER_SALES_FORMAT = number_format((float)$TRACKER_SALES_PERCENT, 2, '.', '');

    } else {
        $TRACKER_SALES_FORMAT = 0;
    }

    if ($TRACKER_WARNING_QDE > 0) {

        $TRACKER_QDE_PERCENT = $TRACKER_WARNING_QDE / $TRACKER_WARNING_TOTAL * 100;
        $TRACKER_QDE_FORMAT = number_format((float)$TRACKER_QDE_PERCENT, 2, '.', '');

    } else {
        $TRACKER_QDE_FORMAT = 0;
    }

    if ($TRACKER_WARNING_QUN > 0) {

        $TRACKER_QUN_PERCENT = $TRACKER_WARNING_QUN / $TRACKER_WARNING_TOTAL * 100;
        $TRACKER_QUN_FORMAT = number_format((float)$TRACKER_QUN_PERCENT, 2, '.', '');

    } else {
        $TRACKER_QUN_FORMAT = 0;
    }

    if ($TRACKER_WARNING_QNQ > 0) {

        $TRACKER_QNQ_PERCENT = $TRACKER_WARNING_QNQ / $TRACKER_WARNING_TOTAL * 100;
        $TRACKER_QNQ_FORMAT = number_format((float)$TRACKER_QNQ_PERCENT, 2, '.', '');

    } else {
        $TRACKER_QNQ_FORMAT = 0;
    }

    if ($TRACKER_WARNING_DIDNO > 0) {

        $TRACKER_DIDNO_PERCENT = $TRACKER_WARNING_DIDNO / $TRACKER_WARNING_TOTAL * 100;
        $TRACKER_DIDNO_FORMAT = number_format((float)$TRACKER_DIDNO_PERCENT, 2, '.', '');

    } else {
        $TRACKER_DIDNO_FORMAT = 0;
    }

    if ($TRACKER_WARNING_QCBK > 0) {

        $TRACKER_QCBK_PERCENT = $TRACKER_WARNING_QCBK / $TRACKER_WARNING_TOTAL * 100;
        $TRACKER_QCBK_FORMAT = number_format((float)$TRACKER_QCBK_PERCENT, 2, '.', '');

    } else {
        $TRACKER_QCBK_FORMAT = 0;
    }

    if ($TRACKER_WARNING_QQQ > 0) {

        $TRACKER_QQQ_PERCENT = $TRACKER_WARNING_QQQ / $TRACKER_WARNING_TOTAL * 100;
        $TRACKER_QQQ_FORMAT = number_format((float)$TRACKER_QQQ_PERCENT, 2, '.', '');

    } else {
        $TRACKER_QQQ_FORMAT = 0;
    }

    if ($TRACKER_WARNING_Other > 0) {

        $TRACKER_OTHER_PERCENT = $TRACKER_WARNING_Other / $TRACKER_WARNING_TOTAL * 100;
        $TRACKER_OTHER_FORMAT = number_format((float)$TRACKER_OTHER_PERCENT, 2, '.', '');

    } else {
        $TRACKER_OTHER_FORMAT = 0;
    }

    if ($TRACKER_WARNING_Hangup > 0) {

        $TRACKER_HANGUP_PERCENT = $TRACKER_WARNING_Hangup / $TRACKER_WARNING_TOTAL * 100;
        $TRACKER_HANGUP_FORMAT = number_format((float)$TRACKER_HANGUP_PERCENT, 2, '.', '');

    } else {
        $TRACKER_HANGUP_FORMAT = 0;
    }

    if ($TRACKER_WARNING_insurer > 0) {

        $TRACKER_INSURER_PERCENT = $TRACKER_WARNING_insurer / $TRACKER_WARNING_TOTAL * 100;
        $TRACKER_INSURER_FORMAT = number_format((float)$TRACKER_INSURER_PERCENT, 2, '.', '');

    } else {
        $TRACKER_INSURER_FORMAT = 0;
    }

    if ($TRACKER_WARNING_QML > 0) {

        $TRACKER_QML_PERCENT = $TRACKER_WARNING_QML / $TRACKER_WARNING_TOTAL * 100;
        $TRACKER_QML_FORMAT = number_format((float)$TRACKER_QML_PERCENT, 2, '.', '');

    } else {
        $TRACKER_QML_FORMAT = 0;
    }

    $TRACKER_SUM_TOTAL = $TRACKER_QNQ_FORMAT + $TRACKER_HANGUP_FORMAT + $TRACKER_INSURER_FORMAT;
    $TRACKER_SUM_FORMAT = number_format((float)$TRACKER_SUM_TOTAL, 2, '.', '');


    ?>
    <div class="col-sm-12">
        <div class="col-sm-2">
        </div>

        <div class="col-sm-6">
            <table class="table">
                <tr>
                    <th colspan="11"><?php if (isset($CLOSER_NAME)) {
                            echo $CLOSER_NAME;
                        } ?> Tracker Stats <?php echo $Today_TIME = date("h:i:s"); ?></th>
                </tr>
                <tr>
                    <th>Total</th>
                    <th>No Quote</th>
                    <th>HANGUP</th>
                    <th>INSURER</th>
                </tr>

                <tr>
                    <td><?php if (isset($TRACKER_SUM_FORMAT)) {
                            echo "$TRACKER_SUM_FORMAT%";
                        } else {
                            echo "0%";
                        } ?></td>
                    <td><?php if (isset($TRACKER_QNQ_FORMAT)) {
                            echo "$TRACKER_QNQ_FORMAT%";
                        } else {
                            echo "0%";
                        } ?></td>
                    <td><?php if (isset($TRACKER_HANGUP_FORMAT)) {
                            echo "$TRACKER_HANGUP_FORMAT%";
                        } else {
                            echo "0%";
                        } ?></td>
                    <td><?php if (isset($TRACKER_INSURER_FORMAT)) {
                            echo "$TRACKER_INSURER_FORMAT%";
                        } else {
                            echo "0%";
                        } ?></td>
                </tr>
            </table>
        </div>

        <div class="col-sm-2"></div>
    </div>
<?php endforeach ?>
