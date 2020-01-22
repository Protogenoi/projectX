<table class="table table-hover">
    <thead>
    <tr>
        <th colspan='5'><h2><i class="fa fa-user-secret"></i> User Tracking</h2></th>
    </tr>
    <tr>
        <th>Date</th>
        <th>User</th>
        <th>URL</th>
        <th>IP</th>
    </tr>
    </thead>
    <?php foreach ($UserTrackingList as $UserTracking):

        $HISTORY_TRK_USER = $UserTracking['tracking_history_user'];
        $HISTORY_TRK_URL = $UserTracking['tracking_history_url'];
        $HISTORY_TRK_IP = $UserTracking['tracking_history_ip'];
        $HISTORY_TRK_DATE = $UserTracking['tracking_history_date'];

        ?>
        <form>
            <tr>

                <td><?php if (isset($HISTORY_TRK_DATE)) {
                        echo $HISTORY_TRK_DATE;
                    } ?></td>
                <td><?php if (isset($HISTORY_TRK_USER)) {
                        echo $HISTORY_TRK_USER;
                    } ?></td>
                <?php if (in_array($hello_name, $Level_10_Access, true)) { ?>
                    <td><?php if (isset($HISTORY_TRK_URL)) {
                            echo $HISTORY_TRK_URL;
                        } ?></td>
                <?php } else { ?>
                    <td><span class="label label-info">Hidden</span></td>
                <?php } ?>
                <td><h4><span class="label <?php if ($HISTORY_TRK_IP != '81.145.167.66') {
                            echo "label-danger";
                        } else {
                            echo "label-success";
                        } ?>"><?php if (isset($HISTORY_TRK_IP)) {
                                echo $HISTORY_TRK_IP;
                            } ?></span></h4></td>

            </tr>
        </form>


    <?php endforeach ?>
</table>
