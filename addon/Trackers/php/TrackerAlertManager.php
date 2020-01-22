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

$QUERY = filter_input(INPUT_GET, 'query', FILTER_SANITIZE_NUMBER_INT);

if (isset($QUERY)) {

    switch ($hello_name) {

        case "724";
            $real_name = 'Chloe John';
            break;
        case "1034";
            $real_name = 'Adam Arrigan';
            break;
        case "Michael";
            $real_name = 'Michael';
            break;
        case "Roxy";
            $real_name = 'Roxy';
            break;
        case "carys";
            $real_name = 'Carys Riley';
            break;
        case "Abbiek";
            $real_name = 'Abbie Kenyon';
            break;
        case "511";
            $real_name = 'Kyle Barnett';
            break;
        case "519";
            $real_name = 'Ricky Derrick';
            break;
        case "103";
            $real_name = 'Sarah Wallace';
            break;
        case "212";
            $real_name = 'Natham James';
            break;
        case "104";
            $real_name = 'Richard Michaels';
            break;
        case "188";
            $real_name = 'Gavin Fulford';
            break;
        case "555";
            $real_name = 'James Adams';
            break;
        case "1009";
            $real_name = 'Matthew Jasper';
            break;
        case "1185";
            $real_name = 'Rhys Morris';
            break;
        default;
            $real_name = $hello_name;

    }

    if ($QUERY == '1') {

        $database = new Database();
        $database->beginTransaction();

        $database->query("SELECT agent FROM dealsheet_call WHERE agent=:agent");
        $database->bind(':agent', $real_name);
        $database->execute();

        if ($database->rowCount() >= 1) {

            $database->query("DELETE FROM dealsheet_call WHERE agent=:agent");
            $database->bind(':agent', $real_name);
            $database->execute();


        } else {

            $database->query("INSERT INTO dealsheet_call set agent=:agent");
            $database->bind(':agent', $real_name);
            $database->execute();

        }

        $database->endTransaction();
    }


}

?>
