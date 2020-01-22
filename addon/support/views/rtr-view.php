<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-ticket-alt"></i> RTR To Do</h3>
    </div>
    <div class="panel-body">

        <table class="table table-condensed">
            <thead>
            <tr>
                <th>ID</th>
                <th>Task</th>
                <th>Category</th>
                <th>Assigned</th>
                <th>Activity</th>
                <th>Status</th>
                <th colspan="3">Options</th>
            </tr>
            </thead>
            <tbody>

            <?php

            if ($rtrSupportTickets != 'error') {

                foreach ($rtrSupportTickets as $rows):

                    if ($rows['updatedDate'] != null) {

                        $supportDate = $rows['updatedDate'];
                        $supportName = $rows['updatedBy'];

                    } else {

                        $supportDate = $rows['addedDate'];
                        $supportName = $rows['addedBy'];

                    }

                    $getStatusColour = new \ADL\supportTickets($pdo);
                    $getStatusColour->setTicketStatus($rows['status']);
                    $statusColour = $getStatusColour->getStatusColour();

                    ?>
                    <tr>
                        <td><?php echo $rows['id']; ?></td>
                        <td><?php echo $rows['task']; ?></td>
                        <td><?php echo $rows['category']; ?></td>
                        <td><?php echo $rows['assigned']; ?></td>
                        <td><?php echo $supportName; ?> <span id="myRtrText<?php echo $rows['id']; ?>"
                                                              style="font-size: x-small; font-style: italic;"></span>
                        </td>

                        <?php

                        if ($rows['addedDate'] > $rows['updatedDate']) {
                            $date = $rows['addedDate'];
                        } else {
                            $date = $rows['updatedDate'];
                        }

                        ?>

                        <script>
                            var time = moment('<?php echo $date; ?>', 'YYYY-MM-DD hh:mm:s').fromNow();
                            document.getElementById("myRtrText<?php echo $rows['id']; ?>").innerHTML = "(" + time + ")";
                        </script>
                        <td>
                                        <span
                                            class="label label-<?php echo $statusColour; ?>"><?php echo $rows['status']; ?></span>
                        </td>
                        <td><a class="btn btn-xs btn-info"
                               href="/addon/support/main.php?EXECUTE=1&ticketID=<?php echo $rows['id']; ?>"">View</a>
                        </td>
                        <td><a class="btn btn-xs btn-warning"
                               href="/addon/support/main.php?EXECUTE=3&ticketID=<?php echo $rows['id']; ?>"">Upload</a>
                        </td>
                        <td><a class="btn btn-xs btn-success"
                               href="/addon/support/main.php?EXECUTE=4&ticketID=<?php echo $rows['id']; ?>"">Credentials</a>
                        </td>
                    </tr>
                <?php

                endforeach;

            }

            ?>

            </tbody>
        </table>

    </div>
</div>
