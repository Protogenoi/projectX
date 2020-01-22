<?php
require_once(__DIR__ . '/../../classes/access_user/access_user_class.php');
$page_protect = new Access_user;
$page_protect->access_page(filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS), "", 1);
$hello_name = ($page_protect->user_full_name != "") ? $page_protect->user_full_name : $page_protect->user;


require_once(__DIR__ . '/../../includes/adl_features.php');
require_once(__DIR__ . '/../../includes/ADL_PDO_CON.php');
require_once(__DIR__ . '/../../resources/lib/twilio-php-master/Twilio/autoload.php');

use Twilio\Rest\Client;

$SMS_QRY = $pdo->prepare("SELECT twilio_account_sid, AES_DECRYPT(twilio_account_token, UNHEX(:key)) AS twilio_account_token FROM twilio_account");
$SMS_QRY->bindParam(':key', $EN_KEY, PDO::PARAM_STR, 500);
$SMS_QRY->execute() or die(print_r($INSERT->errorInfo(), true));
$SMS_RESULT = $SMS_QRY->fetch(PDO::FETCH_ASSOC);

$SID = $SMS_RESULT['twilio_account_sid'];
$TOKEN = $SMS_RESULT['twilio_account_token'];

if (isset($fferror)) {
    if ($fferror == '1') {

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

    }

}

$query = filter_input(INPUT_GET, 'query', FILTER_SANITIZE_SPECIAL_CHARS);

if (isset($query)) {
    $csv_mimetypes = array(
        'text/csv',
        'text/plain',
        'application/csv',
        'text/comma-separated-values',
        'application/excel',
        'application/vnd.ms-excel',
        'application/vnd.msexcel',
        'text/anytext',
        'application/octet-stream',
        'application/txt',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    );

    if (!in_array($_FILES['file']['type'], $csv_mimetypes)) {

        header('Location: ../../addon/Life/Financials/upload_financials.php?uploaded=0&Reason=FileType');
        die;

    }

    if ($query == 'Home') {
        $uploadtype = "Home Financials";
        $date = date("y-m-d-G:i:s");

        $file = $date . "-" . $hello_name . "-" . $_FILES['file']['name'];
        $file_loc = $_FILES['file']['tmp_name'];
        $file_size = $_FILES['file']['size'];
        $file_type = $_FILES['file']['type'];
        $folder = "../FinUploads/Home/";

        $new_size = $file_size / 1024;
        $new_file_name = strtolower($file);
        $final_file = str_replace("'", "", $new_file_name);

        if (move_uploaded_file($file_loc, $folder . $final_file)) {

            try {

                $query = $pdo->prepare("INSERT INTO tbl_uploads set file=:file, type=:type, size=:size, uploadtype=:uploadtype");
                $query->bindParam(':file', $final_file, PDO::PARAM_STR);
                $query->bindParam(':type', $file_type, PDO::PARAM_STR);
                $query->bindParam(':size', $new_size, PDO::PARAM_STR);
                $query->bindParam(':uploadtype', $uploadtype, PDO::PARAM_STR);
                $query->execute();

            } catch (PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();

            }

            header('Location: ../../addon/Life/Financials/upload_financials.php?uploaded=1&query=Home');
            die;

        }

        header('Location: ../../addon/Life/Financials/upload_financials.php?uploaded=0&query=Home');
        die;

    }

    if ($query == 'Vitality') {

        $uploadtype = "Vitality Financials";
        $date = date("y-m-d-G:i:s");

        $file = $date . "-" . $hello_name . "-" . $_FILES['file']['name'];
        $file_loc = $_FILES['file']['tmp_name'];
        $file_size = $_FILES['file']['size'];
        $file_type = $_FILES['file']['type'];
        $folder = "../FinUploads/Vitality/";

        $new_size = $file_size / 1024;
        $new_file_name = strtolower($file);
        $final_file = str_replace("'", "", $new_file_name);

        if (move_uploaded_file($file_loc, $folder . $final_file)) {

            try {

                $query = $pdo->prepare("INSERT INTO tbl_uploads set file=:file, type=:type, size=:size, uploadtype=:uploadtype");
                $query->bindParam(':file', $final_file, PDO::PARAM_STR);
                $query->bindParam(':type', $file_type, PDO::PARAM_STR);
                $query->bindParam(':size', $new_size, PDO::PARAM_STR);
                $query->bindParam(':uploadtype', $uploadtype, PDO::PARAM_STR);
                $query->execute();

            } catch (PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();

            }

            header('Location: ../../addon/Life/Financials/upload_financials.php?uploaded=1&query=Vitality');
            die;

        }

        header('Location: ../../addon/Life/Financials/upload_financials.php?uploaded=0&query=Vitality');
        die;

    }

    if ($query == 'LV') {

        $uploadtype = "LV Financials";
        $date = date("y-m-d-G:i:s");

        $file = $date . "-" . $hello_name . "-" . $_FILES['file']['name'];
        $file_loc = $_FILES['file']['tmp_name'];
        $file_size = $_FILES['file']['size'];
        $file_type = $_FILES['file']['type'];
        $folder = "../FinUploads/LV/";

        $new_size = $file_size / 1024;
        $new_file_name = strtolower($file);
        $final_file = str_replace("'", "", $new_file_name);

        if (move_uploaded_file($file_loc, $folder . $final_file)) {

            try {

                $query = $pdo->prepare("INSERT INTO tbl_uploads set file=:file, type=:type, size=:size, uploadtype=:uploadtype");
                $query->bindParam(':file', $final_file, PDO::PARAM_STR);
                $query->bindParam(':type', $file_type, PDO::PARAM_STR);
                $query->bindParam(':size', $new_size, PDO::PARAM_STR);
                $query->bindParam(':uploadtype', $uploadtype, PDO::PARAM_STR);
                $query->execute();

            } catch (PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();

            }

            header('Location: ../../addon/Life/Financials/upload_financials.php?uploaded=1&query=LV');
            die;

        }

        header('Location: ../../addon/Life/Financials/upload_financials.php?uploaded=0&query=LV');
        die;

    }

    if ($query == 'Life') {

        $uploadtype = "Financial Comms";
        $date = date("y-m-d-G:i:s");

        $file = $date . "-" . $hello_name . "-" . $_FILES['file']['name'];
        $file_loc = $_FILES['file']['tmp_name'];
        $file_size = $_FILES['file']['size'];
        $file_type = $_FILES['file']['type'];
        $folder = "../FinUploads/LANDG/";

        $new_size = $file_size / 1024;
        $new_file_name = strtolower($file);
        $final_file = str_replace("'", "", $new_file_name);

        if (move_uploaded_file($file_loc, $folder . $final_file)) {

            try {

                $query = $pdo->prepare("INSERT INTO tbl_uploads set file=:file, type=:type, size=:size, uploadtype=:uploadtype");
                $query->bindParam(':file', $final_file, PDO::PARAM_STR);
                $query->bindParam(':type', $file_type, PDO::PARAM_STR);
                $query->bindParam(':size', $new_size, PDO::PARAM_STR);
                $query->bindParam(':uploadtype', $uploadtype, PDO::PARAM_STR);
                $query->execute();

            } catch (PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();

            }


            if ($ffsms == '1') {


                $client = new Client($SID, $TOKEN);

                $MOB_ARRAY = array("07401434619", "07917886451", "07890567225", "07712251335");
                $MOB_MSG = "ADL Legal and General Raw COMMS Uploaded by $hello_name. Ready for upload!";

                foreach ($MOB_ARRAY as $MESS_TO) {

                    $client->messages->create(
                        "$MESS_TO",
                        array(
                            'from' => '+441792720348',
                            'body' => "$MOB_MSG"
                        )
                    );

                }
            }

            header('Location: ../../addon/Life/Financials/upload_financials.php?uploaded=1&query=Life');
            die;

        }

        header('Location: ../../addon/Life/Financials/upload_financials.php?uploaded=0&query=Life');
        die;

    }


    if ($query == 'Aviva') {

        $uploadtype = "Aviva Financials";
        $date = date("y-m-d-G:i:s");

        $file = $date . "-" . $hello_name . "-" . $_FILES['file']['name'];
        $file_loc = $_FILES['file']['tmp_name'];
        $file_size = $_FILES['file']['size'];
        $file_type = $_FILES['file']['type'];
        $folder = "../FinUploads/Aviva/";

        $new_size = $file_size / 1024;
        $new_file_name = strtolower($file);
        $final_file = str_replace("'", "", $new_file_name);

        if (move_uploaded_file($file_loc, $folder . $final_file)) {

            try {

                $query = $pdo->prepare("INSERT INTO tbl_uploads set file=:file, type=:type, size=:size, uploadtype=:uploadtype");
                $query->bindParam(':file', $final_file, PDO::PARAM_STR);
                $query->bindParam(':type', $file_type, PDO::PARAM_STR);
                $query->bindParam(':size', $new_size, PDO::PARAM_STR);
                $query->bindParam(':uploadtype', $uploadtype, PDO::PARAM_STR);
                $query->execute();

            } catch (PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();

            }

            header('Location: ../../addon/Life/Financials/upload_financials.php?uploaded=1&query=Aviva');
            die;

        }

        header('Location: ../../addon/Life/Financials/upload_financials.php?uploaded=0&query=Aviva');
        die;

    }

    if ($query == 'WOL') {

        $uploadtype = "WOL Financials";
        $date = date("y-m-d-G:i:s");

        $file = $date . "-" . $hello_name . "-" . $_FILES['file']['name'];
        $file_loc = $_FILES['file']['tmp_name'];
        $file_size = $_FILES['file']['size'];
        $file_type = $_FILES['file']['type'];
        $folder = "../FinUploads/WOL/";

        $new_size = $file_size / 1024;
        $new_file_name = strtolower($file);
        $final_file = str_replace("'", "", $new_file_name);

        if (move_uploaded_file($file_loc, $folder . $final_file)) {

            try {

                $query = $pdo->prepare("INSERT INTO tbl_uploads set file=:file, type=:type, size=:size, uploadtype=:uploadtype");
                $query->bindParam(':file', $final_file, PDO::PARAM_STR);
                $query->bindParam(':type', $file_type, PDO::PARAM_STR);
                $query->bindParam(':size', $new_size, PDO::PARAM_STR);
                $query->bindParam(':uploadtype', $uploadtype, PDO::PARAM_STR);
                $query->execute();

            } catch (PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();

            }

            header('Location: ../../addon/Life/Financials/upload_financials.php?uploaded=1&query=WOL');
            die;

        }

        header('Location: ../../addon/Life/Financials/upload_financials.php?uploaded=0&query=WOL');
        die;

    }

    if ($query == 'RoyalLondon') {

        $uploadtype = "Royal London Financials";
        $date = date("y-m-d-G:i:s");

        $file = $date . "-" . $hello_name . "-" . $_FILES['file']['name'];
        $file_loc = $_FILES['file']['tmp_name'];
        $file_size = $_FILES['file']['size'];
        $file_type = $_FILES['file']['type'];
        $folder = "../FinUploads/RoyalLondon/";

        $new_size = $file_size / 1024;
        $new_file_name = strtolower($file);
        $final_file = str_replace("'", "", $new_file_name);

        if (move_uploaded_file($file_loc, $folder . $final_file)) {

            try {

                $query = $pdo->prepare("INSERT INTO tbl_uploads set file=:file, type=:type, size=:size, uploadtype=:uploadtype");
                $query->bindParam(':file', $final_file, PDO::PARAM_STR);
                $query->bindParam(':type', $file_type, PDO::PARAM_STR);
                $query->bindParam(':size', $new_size, PDO::PARAM_STR);
                $query->bindParam(':uploadtype', $uploadtype, PDO::PARAM_STR);
                $query->execute();

            } catch (PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();

            }

            header('Location: ../../addon/Life/Financials/upload_financials.php?uploaded=1&query=RoyalLondon');
            die;

        }

        header('Location: ../../addon/Life/Financials/upload_financials.php?uploaded=0&query=RoyalLondon');
        die;

    }

}


header('Location: ../../CRMmain.php?AccessDenied');
die;
