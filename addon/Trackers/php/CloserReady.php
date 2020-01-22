<?php
require_once(__DIR__ . '/../../../classes/access_user/access_user_class.php');
$page_protect = new Access_user;
$page_protect->access_page(filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS), "", 1);
$hello_name = ($page_protect->user_full_name != "") ? $page_protect->user_full_name : $page_protect->user;

$USER_TRACKING = 0;

require_once(__DIR__ . '/../../../includes/user_tracking.php');
require_once(__DIR__ . '/../../../includes/time.php');

if (isset($FORCE_LOGOUT) && $FORCE_LOGOUT == 1) {
    $page_protect->log_out();
}

require_once(__DIR__ . '/../../../includes/adl_features.php');
require_once(__DIR__ . '/../../../includes/Access_Levels.php');
require_once(__DIR__ . '/../../../includes/adlfunctions.php');
require_once(__DIR__ . '/../../../includes/ADL_PDO_CON.php');

if ($ffanalytics == '1') {
    require_once(__DIR__ . '/../../../app/analyticstracking.php');
}

if (isset($fferror)) {
    if ($fferror == '1') {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }
}


require_once(__DIR__ . '/../../../classes/database_class.php');
require_once(__DIR__ . '/../../../class/login/login.php');

$CHECK_USER_LOGIN = new UserActions($hello_name, "NoToken");

$CHECK_USER_LOGIN->SelectToken();
$CHECK_USER_LOGIN->CheckAccessLevel();

$OUT = $CHECK_USER_LOGIN->SelectToken();

if (isset($OUT['TOKEN_SELECT']) && $OUT['TOKEN_SELECT'] != 'NoToken') {

    $TOKEN = $OUT['TOKEN_SELECT'];

}

$USER_ACCESS_LEVEL = $CHECK_USER_LOGIN->CheckAccessLevel();

$ACCESS_LEVEL = $USER_ACCESS_LEVEL['ACCESS_LEVEL'];

if ($ACCESS_LEVEL < 1) {

    header('Location: /../../../../index.php?AccessDenied&USER=' . $hello_name . '&COMPANY=' . $COMPANY_ENTITY);
    die;

}

if (isset($fftrackers) && $fftrackers == '0') {
    header('Location: /../../../../CRMmain.php?Feature=NotEnabled');
    die;
}

$EXECUTE = filter_input(INPUT_GET, 'EXECUTE', FILTER_SANITIZE_NUMBER_INT);
$SEND_LEAD = filter_input(INPUT_POST, 'SEND_LEAD', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

if (isset($EXECUTE)) {
    if ($EXECUTE == '1') {

        $database = new Database();
        $database->beginTransaction();

        $database->query("SELECT closer FROM dealsheet_call WHERE closer=:CLOSER");
        $database->bind(':CLOSER', $hello_name);
        $database->execute();

        if ($database->rowCount() >= 1) {

            $database->query("DELETE FROM dealsheet_call WHERE closer=:CLOSER");
            $database->bind(':CLOSER', $hello_name);
            $database->execute();

        } else {

            $database->query("INSERT INTO dealsheet_call set agent=:agent, closer=:CLOSER");
            $database->bind(':CLOSER', $hello_name);
            $database->bind(':agent', $SEND_LEAD);
            $database->execute();

        }

        $database->endTransaction();
    }


}

header('Location: ../Tracker.php?query=CloserTrackers');
die;
?>
