<div class="container">

    <table id="tracker" class="table table-striped">
        <thead>
        <tr>
            <th>Row</th>
            <th>Closer</th>
            <th>Agent</th>
            <th>Client</th>
            <th>Phone</th>
            <th>Comments</th>
        </tr>
        </thead>

        <?php $i = '0';
        foreach ($CloserPadList as $TRACKER_EDIT_result): ?>

            <?php
            $i++;
            $TRK_EDIT_agent = $TRACKER_EDIT_result['agent'];
            $TRK_EDIT_closer = $TRACKER_EDIT_result['closer'];
            $TRK_EDIT_client = $TRACKER_EDIT_result['client'];
            $TRK_EDIT_phone = $TRACKER_EDIT_result['phone'];
            $TRK_EDIT_comments = $TRACKER_EDIT_result['comments'];
            ?>
            <tr>
                <td><?php if (isset($i)) {
                        echo $i;
                    } ?></td>
                <td><?php if (isset($TRK_EDIT_closer)) {
                        echo $TRK_EDIT_closer;
                    } ?> </td>
                <td><?php if (isset($TRK_EDIT_agent)) {
                        echo $TRK_EDIT_agent;
                    } ?></td>
                <td><?php if (isset($TRK_EDIT_client)) {
                        echo $TRK_EDIT_client;
                    } ?></td>
                <td><?php if (isset($TRK_EDIT_phone)) {
                        echo $TRK_EDIT_phone;
                    } ?></td>
                <td><?php if (isset($TRK_EDIT_comments)) {
                        echo $TRK_EDIT_comments;
                    } ?></td>
            </tr>

        <?php endforeach ?>
    </table>

</div>
