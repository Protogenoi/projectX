<button class="btn btn-danger btn-sm btn-block" data-toggle="modal" data-target="#KeyFactsModal"><strong>
        <i class="fas fa-envelope"></i> KEYFACTS EMAIL NOT SENT!
    </strong>
</button>

<div id="KeyFactsModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Keyfacts Email Tracker</h4>
            </div>
            <div class="modal-body">
                <div class='notice notice-info' role='alert' id='HIDEGLEAD'><strong><i
                                class='fa fa-exclamation fa-lg'></i>
                        Info:</strong> <b>Sometimes the email may of been added wrong onto ADL. Make sure the email
                        address
                        is correct on the dealsheet and on the insurers portal.</b></div>

                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th colspan='12'>Keyfacts not sent!</th>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <th>Client</th>
                        <th>Email</th>
                    </tr>
                    </thead>
                    <?php foreach ($MissingKFEmailList as $MISSING_KF_VARS): ?>


                        <?php
                        $EMAIL = $MISSING_KF_VARS['email'];
                        $SUB_DATE = $MISSING_KF_VARS['submitted_date'];
                        $NAME = $MISSING_KF_VARS['NAME'];

                        ?>


                        <tr>
                            <td><?php if (isset($SUB_DATE)) {
                                    echo $SUB_DATE;
                                } ?></td>
                            <td><?php if (isset($NAME)) {
                                    echo $NAME;
                                } ?></td>
                            <td><?php if (isset($EMAIL)) {
                                    echo $EMAIL;
                                } ?></td>
                        </tr>


                    <?php endforeach ?>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
