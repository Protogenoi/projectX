<?php
/**
 * ------------------------------------------------------------------------
 *                               ADL CRM
 * ------------------------------------------------------------------------
 *
 * Copyright © 2018 ADL CRM All rights reserved.
 *
 * Unauthorised copying of this file, via any medium is strictly prohibited.
 * Unauthorised distribution of this file, via any medium is strictly prohibited.
 * Unauthorised modification of this code is strictly prohibited.
 *
 * Proprietary and confidential
 *
 * Written by Michael Owen <michael@adl-crm.uk>, 2018
 *
 * ADL CRM makes use of the following third party open sourced software/tools:
 *  DataTables - https://github.com/DataTables/DataTables
 *  EasyAutocomplete - https://github.com/pawelczak/EasyAutocomplete
 *  PHPMailer - https://github.com/PHPMailer/PHPMailer
 *  ClockPicker - https://github.com/weareoutman/clockpicker
 *  fpdf17 - http://www.fpdf.org
 *  summernote - https://github.com/summernote/summernote
 *  Font Awesome - https://github.com/FortAwesome/Font-Awesome
 *  Bootstrap - https://github.com/twbs/bootstrap
 *  jQuery UI - https://github.com/jquery/jquery-ui
 *  Google Dev Tools - https://developers.google.com
 *  Twitter API - https://developer.twitter.com
 *  Webshim - https://github.com/aFarkas/webshim/releases/latest
 *
 */

use ADL\client;
use ADL\clientNote;

require_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_SPECIAL_CHARS) . '/app/core/doc_root.php';

require_once(BASE_URL . '/classes/access_user/access_user_class.php');
$page_protect = new Access_user;
$page_protect->access_page(filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS), "", 2);
$hello_name = ($page_protect->user_full_name != "") ? $page_protect->user_full_name : $page_protect->user;

$USER_TRACKING = 0;

require_once(BASE_URL . '/includes/adl_features.php');

require_once(BASE_URL . '/includes/time.php');

if (isset($FORCE_LOGOUT) && $FORCE_LOGOUT == 1) {
    $page_protect->log_out();
}

require_once(BASE_URL . '/includes/user_tracking.php');
require_once(BASE_URL . '/includes/Access_Levels.php');

require_once(BASE_URL . '/includes/ADL_PDO_CON.php');
require_once(BASE_URL . '/classes/database_class.php');

if ($ffanalytics == '1') {
    require_once(BASE_URL . '/app/analyticstracking.php');
}

if (isset($fferror)) {
    if ($fferror == 1) {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }
}

$EXECUTE = filter_input(INPUT_GET, 'EXECUTE', FILTER_SANITIZE_SPECIAL_CHARS);

if (isset($EXECUTE)) {
    if ($EXECUTE == 1) {

        $CID = filter_input(INPUT_POST, 'CID', FILTER_SANITIZE_NUMBER_INT);

        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
        $clientName = filter_input(INPUT_POST, 'clientName', FILTER_SANITIZE_SPECIAL_CHARS);
        $dob = filter_input(INPUT_POST, 'dob', FILTER_SANITIZE_SPECIAL_CHARS);

        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $phoneNumber = filter_input(INPUT_POST, 'phoneNumber', FILTER_SANITIZE_SPECIAL_CHARS);
        $altNumber = filter_input(INPUT_POST, 'altNumber', FILTER_SANITIZE_SPECIAL_CHARS);

        $address1 = filter_input(INPUT_POST, 'address1', FILTER_SANITIZE_SPECIAL_CHARS);
        $address2 = filter_input(INPUT_POST, 'address2', FILTER_SANITIZE_SPECIAL_CHARS);
        $address3 = filter_input(INPUT_POST, 'address3', FILTER_SANITIZE_SPECIAL_CHARS);
        $town = filter_input(INPUT_POST, 'town', FILTER_SANITIZE_SPECIAL_CHARS);
        $post_code = filter_input(INPUT_POST, 'post_code', FILTER_SANITIZE_SPECIAL_CHARS);

        $title2 = filter_input(INPUT_POST, 'title2', FILTER_SANITIZE_SPECIAL_CHARS);
        $clientName2 = filter_input(INPUT_POST, 'clientName2', FILTER_SANITIZE_SPECIAL_CHARS);
        $dob2 = filter_input(INPUT_POST, 'dob2', FILTER_SANITIZE_SPECIAL_CHARS);

        $company = filter_input(INPUT_POST, 'company', FILTER_SANITIZE_SPECIAL_CHARS);
        $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_SPECIAL_CHARS);

        require_once(BASE_URL . '/class/clientNote.php');
        require_once(BASE_URL . '/class/client.php');

        $newClient = new ADL\client($pdo);
        $newClient->setClientID($CID);
        $newClient->setCompanyEntity($COMPANY_ENTITY);
        $newClient->setClientName($clientName);
        $newClient->setDob($dob);
        $newClient->setTitle($title);
        $newClient->setEmail($email);
        $newClient->setClientName2($clientName2);
        $newClient->setDob2($dob2);
        $newClient->setTitle2($title2);
        $newClient->setAddress1($address1);
        $newClient->setAddress2($address2);
        $newClient->setAddress3($address3);
        $newClient->setTown($town);
        $newClient->setPostCode($post_code);
        $newClient->setAdlUser($hello_name);
        $newClient->setPhoneNumber($phoneNumber);
        $newClient->setAltNumber($altNumber);
        $newClient->setCompany($company);
        $newClient->setStatus($status);

        $newClientResponse = $newClient->updatePotentialClient();

        if (isset($newClientResponse) && $newClientResponse = 'success') {

            require_once(BASE_URL . '/class/clientNote.php');

            $query = $pdo->prepare("SELECT tracker_id FROM closer_trackers WHERE phone = :phone");
            $query->bindParam(':phone', $phoneNumber, PDO::PARAM_STR);
            $query->execute();
            $EID_RESULT = $query->fetch(PDO::FETCH_ASSOC);

            $TID = $EID_RESULT['tracker_id'];

            if (isset($TID) && is_numeric($TID)) {

                $UPDATE = $pdo->prepare("UPDATE closer_trackers set sale=:sale, insurer=:INSURER, client=:client, phone=:phone WHERE tracker_id=:id");
                $UPDATE->bindParam(':id', $TID, PDO::PARAM_INT);
                $UPDATE->bindParam(':INSURER', $company, PDO::PARAM_STR);
                $UPDATE->bindParam(':client', $clientName, PDO::PARAM_STR);
                $UPDATE->bindParam(':phone', $phoneNumber, PDO::PARAM_STR);
                $UPDATE->bindParam(':sale', $status, PDO::PARAM_STR);
                $UPDATE->execute();

            }

            $toastrTitle = "Client Details Updated";

            $clientNoteMessage = "Details updated";
            $clientNoteNoteType = $toastrTitle;
            $clientNoteAddedBy = $hello_name;
            $clientNoteReference = "ADL Alert";

            $addTimelineNotes = new ADL\clientNote($CID, $pdo);
            $result = $addTimelineNotes->addPotentialClientNote($clientNoteMessage,
                $clientNoteNoteType,
                $clientNoteAddedBy,
                $clientNoteReference);

            $changereason = $clientNoteMessage;


            header('Location: /../../../../../addon/Trackers/client.php?toastrResponse=1&toastrMessage=' . $changereason . '&toastrTitle=' . $toastrTitle . '.&search=' . $CID);
            die;

        } else {

            $toastrTitle = 'Client details not updated!';
            $toastrMessage = 'Error!';

            header('Location: /../../../../../addon/Trackers/client.php?toastrResponse=2&toastrMessage=' . $toastrMessage . '&toastrTitle=' . $toastrTitle . '.&search=' . $CID);
            die;

        }

    }
}

header('Location: /../../../../../CRMmain.php?error=1');
die;
