<?php
require_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_SPECIAL_CHARS) . '/app/core/doc_root.php';
require_once(BASE_URL . '/includes/ADL_PDO_CON.php');

$closer = filter_input(INPUT_GET, 'closer', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$nowTime = date("H:i:s");

if (isset($closer)) {

    $query = $pdo->prepare("SELECT client_id, clientName, closer ,comments, sale, timeDeadline, dayDeadline FROM closer_trackers JOIN potential_clients on potential_clients.phoneNumber = closer_trackers.phone WHERE dayDeadline <= CURDATE() AND timeDeadline <= :timeDeadline AND closer=:closer");
    $query->bindParam(':timeDeadline', $nowTime, PDO::PARAM_STR);
    $query->bindParam(':closer', $closer, PDO::PARAM_STR);

} else {

    $query = $pdo->prepare("SELECT client_id, clientName, closer ,comments, sale, timeDeadline, dayDeadline FROM closer_trackers JOIN potential_clients on potential_clients.phoneNumber = closer_trackers.phone WHERE dayDeadline <= CURDATE() AND timeDeadline <= :timeDeadline");
    $query->bindParam(':timeDeadline', $nowTime, PDO::PARAM_STR);

}

$query->execute();
if ($query->rowCount() >= 1) {

    while ($closerTrackers = $query->fetch(PDO::FETCH_ASSOC)) {

        $padCustomer = $closerTrackers['clientName'];
        $padStatus = $closerTrackers['sale'];
        $padCID = $closerTrackers['client_id'];
        $padComments = $closerTrackers['comments'];
        $padTimeDeadline = $closerTrackers['timeDeadline'];
        $closerTrackerCloser = $closerTrackers['closer'];

        ?>

        <script>$(function () {
                $(document).ready(function () {
                    toastr.info("<?php echo "<a href='/addon/Trackers/client.php?search=$padCID'>$padCustomer | Closer: $closerTrackerCloser | Status: $padStatus<br>Call today before: $padTimeDeadline</a>"; ?>", "Closer Tracker Alerts!!!", {
                        "closeButton": true,
                        "debug": false,
                        "newestOnTop": true,
                        "progressBar": false,
                        "positionClass": "toast-top-full-width",
                        "preventDuplicates": true,
                        "onclick": null,
                        "showDuration": "9000",
                        "hideDuration": "9000",
                        "timeOut": "9000",
                        "extendedTimeOut": "9000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    });
                });
            });</script>

    <?php }
}
