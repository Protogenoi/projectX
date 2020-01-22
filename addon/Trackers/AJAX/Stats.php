<?php
$EXECUTE = filter_input(INPUT_GET, 'EXECUTE', FILTER_SANITIZE_SPECIAL_CHARS);

if (isset($EXECUTE) && $EXECUTE == '1') {

    require_once(__DIR__ . '/../../../includes/ADL_PDO_CON.php');
    require_once(__DIR__ . '/../models/CLOSERS/WARNING.php');
    $TRACKER_WARNING = new TRACKER_WARNINGModal($pdo);
    $TRACKER_WARNINGList = $TRACKER_WARNING->getTRACKER_WARNING();
    require_once(__DIR__ . '/../views/CLOSERS/WARNING.php');

}
?>
