<?php
include('../../includes/adl_features.php');
if (isset($fferror)) {
    if ($fferror == '1') {

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

    }

}

$EXECUTE = filter_input(INPUT_GET, 'EXECUTE', FILTER_SANITIZE_SPECIAL_CHARS);
$Body = filter_input(INPUT_POST, 'Body', FILTER_SANITIZE_SPECIAL_CHARS);
$From = filter_input(INPUT_POST, 'From', FILTER_SANITIZE_SPECIAL_CHARS);

if (isset($EXECUTE)) {
    if ($EXECUTE == '1') {
        if (isset($Body)) {
            include('../../classes/database_class.php');

            $database = new Database();

            $CALLID = preg_replace('~^[0\D]++44|\D++~', '0', $From);

            $database->query("SELECT client_id FROM client_details WHERE phone_number =:CALLID");
            $database->bind(':CALLID', $CALLID);
            $database->execute();
            $data2 = $database->single();

            if (isset($data2['client_id'])) {
                $CID = $data2['client_id'];
            }

            if ($database->rowCount() >= 1) {

                $NEW_MESSAGE = "Client ($CALLID) SMS Reply: '$Body'";

                $INSERT = $pdo->prepare("INSERT INTO client_note set client_id=:CID, client_name='ADL Alert', sent_by='ADL', note_type='Client SMS Reply', message=:CALLERID");
                $INSERT->bindParam(':CID', $CID, PDO::PARAM_INT);
                $INSERT->bindParam(':CALLERID', $NEW_MESSAGE, PDO::PARAM_STR);
                $INSERT->execute();

                $SMS_INSERT = $pdo->prepare("INSERT INTO sms_inbound set sms_inbound_client_id=:CID, sms_inbound_phone=:PHONE, sms_inbound_type='Client SMS Reply', sms_inbound_msg=:MSG");
                $SMS_INSERT->bindParam(':CID', $CID, PDO::PARAM_INT);
                $SMS_INSERT->bindParam(':MSG', $NEW_MESSAGE, PDO::PARAM_STR);
                $SMS_INSERT->bindParam(':PHONE', $CALLID, PDO::PARAM_STR);
                $SMS_INSERT->execute();

            } else {

                $UNKNOWN_NUMBER = "UNKNOWN NUMBER | $NEW_MESSAGE";

                $SMS_INSERT = $pdo->prepare("INSERT INTO sms_inbound set sms_inbound_client_id=:CID, sms_inbound_phone=:PHONE, sms_inbound_type='Client SMS Reply', sms_inbound_msg=:MSG");
                $SMS_INSERT->bindParam(':CID', $CID, PDO::PARAM_INT);
                $SMS_INSERT->bindParam(':MSG', $UNKNOWN_NUMBER, PDO::PARAM_STR);
                $SMS_INSERT->bindParam(':PHONE', $CALLID, PDO::PARAM_STR);
                $SMS_INSERT->execute();

            }

        }

    }

}
?>
