<?php

/** @noinspection PhpIncludeInspection */
/** @noinspection ALL */

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
 *  toastr - https://github.com/CodeSeven/toastr
 */

require_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_SPECIAL_CHARS) . '/app/core/doc_root.php';
require_once(BASE_URL . '/classes/access_user/access_user_class.php');
$page_protect = new Access_user;
$page_protect->access_page(filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS), "", 3);
$hello_name = ($page_protect->user_full_name != "") ? $page_protect->user_full_name : $page_protect->user;

$USER_TRACKING = 1;

require_once(BASE_URL . '/includes/adl_features.php');
require_once(BASE_URL . '/includes/Access_Levels.php');
require_once(BASE_URL . '/includes/adlfunctions.php');
require_once(BASE_URL . '/classes/database_class.php');

if (isset($ffanalytics) && $ffanalytics == 1) {
    require_once(BASE_URL . '/app/analyticstracking.php');
}

if (isset($fferror) && $fferror == 1) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

$PID = filter_input(INPUT_GET, 'policyID', FILTER_SANITIZE_NUMBER_INT);
$editNotes = filter_input(INPUT_GET, 'editNotes', FILTER_SANITIZE_NUMBER_INT);

$padSelect = filter_input(INPUT_GET, 'pad', FILTER_SANITIZE_NUMBER_INT);
$clientPolicyID = filter_input(INPUT_GET, 'PID', FILTER_SANITIZE_NUMBER_INT);
$padPolicyNumber = filter_input(INPUT_GET, 'padPolicy', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$padInsurer = filter_input(INPUT_GET, 'padInsurer', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_NUMBER_INT);

if (isset($search)) {
    $likesearch = "$search-%";
    $tracking_search = "%search=$search%";
}

if (isset($search) && $search < 0 || empty($search)) {
    header('Location: /../../CRMmain.php?noCID');
    die;
}
$ACCESS_ALLOW = array("Michael");
if ($search == '135329' && !(in_array($hello_name, $ACCESS_ALLOW))) {
    header('Location: ../app/SearchClients.php?ClientDeleted');
}
require_once(BASE_URL . '/classes/database_class.php');
require_once(BASE_URL . '/class/login/login.php');

$CHECK_USER_LOGIN = new UserActions($hello_name, "NoToken");
$CHECK_USER_LOGIN->CheckAccessLevel();
$USER_ACCESS_LEVEL = $CHECK_USER_LOGIN->CheckAccessLevel();
$ACCESS_LEVEL = $USER_ACCESS_LEVEL['ACCESS_LEVEL'];

if ($ACCESS_LEVEL < 3) {
    header('Location: /../../index.php?AccessDenied&USER=' . $hello_name . '&COMPANY=' . $COMPANY_ENTITY);
    die;
}

require_once(BASE_URL . '/includes/ADL_PDO_CON.php');
require_once(BASE_URL . '/class/clientNote.php');
require_once(BASE_URL . '/class/client.php');
require_once(BASE_URL . '/class/earlyWarningSystem.php');
require_once(BASE_URL . '/class/padStats.php');

$newClient = new ADL\client($pdo);
$newClient->setClientID($search);
$newClient->setCompanyEntity($COMPANY_ENTITY);
$newClientResponse = $newClient->getSingleClient();

if ($newClientResponse == 'error') {
    header('Location: /../../CRMmain.php?error');
}

if (isset($newClientResponse['company'])) {
    $WHICH_COMPANY = $newClientResponse['company'];
}
if (isset($newClientResponse['owner'])) {
    $WHICH_OWNER = $newClientResponse['owner'];
}
if (isset($newClientResponse['date_added'])) {
    $client_date_added = $newClientResponse['date_added'];
}
if (isset($newClientResponse['email'])) {
    $clientonemail = $newClientResponse['email'];
}
if (isset($newClientResponse['email2'])) {
    $clienttwomail = $newClientResponse['email2'];
}
if (isset($newClientResponse['first_name'])) {
    $clientonefull = $newClientResponse['first_name'] . " " . $newClientResponse['last_name'];
}
if (isset($newClientResponse['first_name2'])) {
    $clienttwofull = $newClientResponse['first_name2'] . " " . $newClientResponse['last_name2'];
}
if (isset($newClientResponse['dealsheet_id'])) {
    $dealsheet_id = $newClientResponse['dealsheet_id'];
}
if (isset($newClientResponse['phone_number'])) {
    $PHONE_NUMBER = $newClientResponse['phone_number'];
}
if (isset($newClientResponse['alt_number'])) {
    $ALT_PHONE_NUMBER = $newClientResponse['alt_number'];
}
if (isset($newClientResponse['renew_life_cid'])) {
    $RENEW_LIFE_CID = $newClientResponse['renew_life_cid'];
}

$NEW_COMPANY_ARRAY = [
    "Vitality",
    "One Family",
    "Royal London",
    "Aviva",
    "Legal and General",
    "Zurich",
    "Scottish Widows",
    "LV",
    "Aegon",
    "HSBC"
];

require_once(BASE_URL . '/addon/Life/php/insurerCheck.php');

$ADL_PAGE_TITLE = "Client";
require_once(BASE_URL . '/app/core/head.php');
?>
<link rel="stylesheet" href="/resources/lib/sweet-alert/sweet-alert.min.css"/>
<link rel="stylesheet" href="/resources/lib/jquery-ui-1.11.4/jquery-ui.min.css"/>
<link rel="stylesheet" type="text/css" href="/resources/lib/clockpicker-gh-pages/dist/jquery-clockpicker.min.css">
<link rel="stylesheet" type="text/css" href="/resources/lib/clockpicker-gh-pages/assets/css/github.min.css">
<link rel="stylesheet" href="/resources/lib/summernote-master/dist/summernote.css">
<style>
    .label-purple {
        background-color: #8e44ad;
    }

    .clockpicker-popover {
        z-index: 999999;
    }

    .clockpicker {
        z-index: 999999;
    }

    .ui-datepicker {
        z-index: 1151 !important;
    }
</style>
</head>
<body>
<?php require_once(BASE_URL . '/includes/navbar.php'); ?>
<br>
<div class="container">

    <?php require_once(BASE_URL . '/includes/user_tracking.php');

    if (isset($bday) && $bday == 1) {

        require_once(BASE_URL . '/resources/lib/bday/baloons.html');

    }

    require_once(BASE_URL . '/app/views/clientNav-view.php');

    ?>

    <div class="tab-content">
        <div id="home" class="tab-pane fade in active">
            <?php
            require_once(__DIR__ . '/php/Notifications.php');
            require_once(__DIR__ . '/views/ViewClient.php');
            ?>

            <div class="container">
                <div style="text-align: center;">
                    <div class="btn-group">

                        <?php

                        if (isset($padSelect) && $padSelect == 1) {

                            if ($padInsurer == 'Aegon') {

                                require_once(BASE_URL . '/addon/Life/PAD/models/client/aegonClientPad-model.php');

                            } else {

                                require_once(BASE_URL . '/addon/Life/PAD/models/client/clientPad-model.php');

                            }

                            $clientPadModel = new clientPad($pdo);
                            $clientPadModelList = $clientPadModel->getclientPad($search, $clientPolicyID);


                            $query = $pdo->prepare("SELECT client_id FROM pad_stats WHERE client_id=:CID AND policyNumber=:POLNUM");
                            $query->bindParam(':CID', $search, PDO::PARAM_INT);
                            $query->bindParam(':POLNUM', $padPolicyNumber, PDO::PARAM_STR);
                            $query->execute();
                            if ($query->rowCount() <= 0) {


                                require_once(BASE_URL . '/addon/Life/PAD/views/client/clientPad-view.php');

                            } elseif ($query->rowCount() > 0) {

                                require_once(BASE_URL . '/addon/Life/PAD/views/client/clientUpdatePad-view.php');

                            }

                        }

                        $query = $pdo->prepare("SELECT client_id FROM pad_stats WHERE client_id=:CID");
                        $query->bindParam(':CID', $search, PDO::PARAM_INT);
                        $query->execute();
                        if ($query->rowCount() <= 0) {

                            if ($WHICH_COMPANY == 'Aegon') {


                                require_once(BASE_URL . '/addon/Life/PAD/models/client/clientAegonToPad-model.php');
                                $clientAegonToPad = new clientAegonToPad($pdo);
                                $clientToPadModelList = $clientAegonToPad->getclientAegonToPad($search);
                                require_once(BASE_URL . '/addon/Life/PAD/views/client/clientToPad-view.php');

                            } else {

                                require_once(BASE_URL . '/addon/Life/PAD/models/client/clientToPad-model.php');
                                $clientToPadModel = new clientToPad($pdo);
                                $clientToPadModelList = $clientToPadModel->getclientToPad($search);
                                require_once(BASE_URL . '/addon/Life/PAD/views/client/clientToPad-view.php');

                            }

                        }

                        if ($query->rowCount() > 0) {

                            if ($WHICH_COMPANY == 'Aegon') {

                                require_once(BASE_URL . '/addon/Life/PAD/models/client/clientAegonUpdateToPad-model.php');
                                $clientAegonUpdateToPad = new clientAegonUpdateToPad($pdo);
                                $padUpdateResult = $clientAegonUpdateToPad->getclientAegonUpdateToPad($search);
                                require_once(BASE_URL . '/addon/Life/PAD/views/client/clientUpdateToPad-view.php');

                            } else {

                                require_once(BASE_URL . '/addon/Life/PAD/models/client/clientUpdateToPad-model.php');
                                $clientPadModel = new clientUpdateToPad($pdo);
                                $padUpdateResult = $clientPadModel->getclientUpdateToPad($search);
                                require_once(BASE_URL . '/addon/Life/PAD/views/client/clientUpdateToPad-view.php');

                            }

                        }

                        $getEwsStatsSingle = new \ADL\earlyWarningSystem($pdo);
                        $getEwsStatsSingle->setCompanyEntity($COMPANY_ENTITY);
                        $getEwsStatsSingle->setCID($search);
                        $getEwsStatsResponse = $getEwsStatsSingle->getSingleEwsStats();

                        if (is_array($getEwsStatsResponse)) {

                            require_once(BASE_URL . '/app/views/updateEwsStatsModal.php');

                        } else {

                            require_once(BASE_URL . '/app/views/addEwsStats.php');

                        }

                        if (empty($dealsheet_id)) {
                            $Dealquery = $pdo->prepare("SELECT file FROM tbl_uploads WHERE file like :CID and uploadtype ='Dealsheet'");
                            $Dealquery->bindParam(':CID', $likesearch, PDO::PARAM_INT);
                            $Dealquery->execute();
                            while ($timelineNotes = $Dealquery->fetch(PDO::FETCH_ASSOC)) {
                                $DSFILE = $timelineNotes['file'];
                                if (file_exists(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT',
                                        FILTER_SANITIZE_SPECIAL_CHARS) . "/uploads/$DSFILE")) {
                                    ?>
                                    <a href="/uploads/<?php echo $DSFILE; ?>" target="_blank"
                                       class="btn btn-default"><span class="glyphicon glyphicon-file"></span>
                                        Dealsheet</a>
                                <?php }
                                if (file_exists(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT',
                                        FILTER_SANITIZE_SPECIAL_CHARS) . "/uploads/life/$search/$DSFILE")) { ?>
                                    <a href="/uploads/life/<?php echo $search; ?>/<?php echo $DSFILE; ?>"
                                       target="_blank" class="btn btn-default"><span
                                            class="glyphicon glyphicon-file"></span> Dealsheet</a>
                                    <?php
                                }
                            }
                        } else {
                            ?>
                            <a href="/addon/Life/LifeDealSheet.php?REF=<?php echo $dealsheet_id; ?>&query=CompletedADL"
                               target="_blank" class="btn btn-default"><span
                                    class="glyphicon glyphicon-file"></span>
                                ADL Dealsheet</a>

                            <?php
                        }
                        if (isset($LANG_POL) && $LANG_POL == 1) {
                            require_once(BASE_URL . '/addon/Life/models/LandG/OLP_Summary-model.php');
                            $OLP_SUM = new OLP_SUMModal($pdo);
                            $OLP_SUMList = $OLP_SUM->getOLP_SUM($search);
                            require_once(BASE_URL . '/addon/Life/views/LandG/OLP_Summary-view.php');
                            require_once(BASE_URL . '/addon/Life/models/LandG/Summary-model.php');
                            $LG_SUM = new LG_SUMModal($pdo);
                            $LG_SUMList = $LG_SUM->getLG_SUM($search);
                            require_once(BASE_URL . '/addon/Life/views/LandG/Summary-view.php');
                            require_once(BASE_URL . '/addon/Life/models/LandG/Policy-model.php');
                            $LG_POL_SUM = new LG_POL_SUMModal($pdo);
                            $LG_POL_SUMList = $LG_POL_SUM->getLG_POL_SUM($likesearch);
                            require_once(BASE_URL . '/addon/Life/views/LandG/Policy-view.php');
                            require_once(BASE_URL . '/addon/Life/models/LandG/Keyfacts-model.php');
                            $LG_KF = new LG_KFModal($pdo);
                            $LG_KFList = $LG_KF->getLG_KF($likesearch);
                            require_once(BASE_URL . '/addon/Life/views/LandG/Keyfacts-view.php');
                        }
                        if (isset($HAS_NEW_VIT_POL) && $HAS_NEW_VIT_POL == 1) {
                            require_once(BASE_URL . '/addon/Life/models/Insurers/Vitality/Policy-model.php');
                            $VIT_POL = new VIT_NEW_POL_Modal($pdo);
                            $VIT_POLList = $VIT_POL->getVIT_POL($likesearch);
                            require_once(BASE_URL . '/addon/Life/views/Insurers/Vitality/Policy-view.php');
                            require_once(BASE_URL . '/addon/Life/models/Insurers/Vitality/Keyfacts-model.php');
                            $VI_KF = new VI_NEW_KFModal($pdo);
                            $VI_KFList = $VI_KF->getVI_KF($likesearch);
                            require_once(BASE_URL . '/addon/Life/views/Insurers/Vitality/Keyfacts-view.php');
                        }
                        if (isset($HAS_AEG_POL) && $HAS_AEG_POL == 1) {
                            require_once(BASE_URL . '/addon/Life/models/Insurers/Aegon/Policy-model.php');
                            $AEG_POL = new AEG_POL_Modal($pdo);
                            $AEG_POLList = $AEG_POL->getAEG_POL($likesearch);
                            require_once(BASE_URL . '/addon/Life/views/Insurers/Aegon/Policy-view.php');
                            require_once(BASE_URL . '/addon/Life/models/Insurers/Aegon/Keyfacts-model.php');
                            $AEG_KF = new AEG_KFModal($pdo);
                            $AEG_KFList = $AEG_KF->getAEG_KF($likesearch);
                            require_once(BASE_URL . '/addon/Life/views/Insurers/Aegon/Keyfacts-view.php');
                        }
                        if (isset($HAS_LV_POL) && $HAS_LV_POL == 1) {
                            require_once(BASE_URL . '/addon/Life/models/LV/Policy-model.php');
                            $LV_POL = new LV_POL_Modal($pdo);
                            $LV_POLList = $LV_POL->getLV_POL($likesearch);
                            require_once(BASE_URL . '/addon/Life/views/LV/Policy-view.php');
                            require_once(BASE_URL . '/addon/Life/models/LV/Keyfacts-model.php');
                            $LV_KF = new LV_KFModal($pdo);
                            $LV_KFList = $LV_KF->getLV_KF($likesearch);
                            require_once(BASE_URL . '/addon/Life/views/LV/Keyfacts-view.php');
                            require_once(BASE_URL . '/addon/Life/models/LV/dash-model.php');
                            $LV_DASH = new LV_DASH_Modal($pdo);
                            $LV_DASHList = $LV_DASH->getLV_DASH($search);
                            require_once(BASE_URL . '/addon/Life/views/LV/dash-view.php');
                        }
                        if (isset($HAS_NATIONAL_FRIENDLY_POL) && $HAS_NATIONAL_FRIENDLY_POL == 1) {
                            require_once(BASE_URL . '/addon/Life/models/nationalFriendly/Policy-model.php');
                            $nationalFriendly_POL = new NationalFriendly_POL_Modal($pdo);
                            $nationalFriendly_POLList = $nationalFriendly_POL->getNationalFriendly_POL($search);
                            require_once(BASE_URL . '/addon/Life/views/nationalFriendly/Policy-view.php');
                            require_once(BASE_URL . '/addon/Life/models/nationalFriendly/Keyfacts-model.php');
                            $nationalFriendly_KF = new NationalFriendly_KFModal($pdo);
                            $nationalFriendly_KFList = $nationalFriendly_KF->getNationalFriendly_KF($search);
                            require_once(BASE_URL . '/addon/Life/views/nationalFriendly/Keyfacts-view.php');

                        }

                        if (isset($HAS_EXETER_POL) && $HAS_EXETER_POL == 1) {
                            require_once(BASE_URL . '/addon/Life/models/theExeter/Policy-model.php');
                            $EXETER_POL = new EXETER_POL_Modal($pdo);
                            $EXETER_POLList = $EXETER_POL->getEXETER_POL($likesearch);
                            require_once(BASE_URL . '/addon/Life/views/theExeter/Policy-view.php');

                            require_once(BASE_URL . '/addon/Life/models/theExeter/Keyfacts-model.php');
                            $EXETER_KF = new EXETER_KFModal($pdo);
                            $EXETER_KFList = $EXETER_KF->getEXETER_KF($likesearch);
                            require_once(BASE_URL . '/addon/Life/views/theExeter/Keyfacts-view.php');

                        }

                        if (isset($HAS_HSBC_POL) && $HAS_HSBC_POL == 1) {
                            require_once(BASE_URL . '/addon/Life/models/HSBC/Policy-model.php');
                            $HSBC_POL = new HSBC_POL_Modal($pdo);
                            $HSBC_POLList = $HSBC_POL->getHSBC_POL($likesearch);
                            require_once(BASE_URL . '/addon/Life/views/HSBC/Policy-view.php');

                            require_once(BASE_URL . '/addon/Life/models/HSBC/Keyfacts-model.php');
                            $HSBC_KF = new HSBC_KFModal($pdo);
                            $HSBC_KFList = $HSBC_KF->getHSBC_KF($likesearch);
                            require_once(BASE_URL . '/addon/Life/views/HSBC/Keyfacts-view.php');

                        }

                        if (isset($HAS_AVI_POL) && $HAS_AVI_POL == 1) {
                            require_once(BASE_URL . '/addon/Life/models/Aviva/Policy-model.php');
                            $AVI_POL = new AVI_POL_Modal($pdo);
                            $AVI_POLList = $AVI_POL->getAVI_POL($likesearch);
                            require_once(BASE_URL . '/addon/Life/views/Aviva/Policy-view.php');
                            require_once(BASE_URL . '/addon/Life/models/Aviva/Keyfacts-model.php');
                            $AVI_KF = new AVI_KFModal($pdo);
                            $AVI_KFList = $AVI_KF->getAVI_KF($likesearch);
                            require_once(BASE_URL . '/addon/Life/views/Aviva/Keyfacts-view.php');
                        }
                        if (isset($HAS_ZURICH_POL) && $HAS_ZURICH_POL == 1) {
                            require_once(BASE_URL . '/addon/Life/models/Zurich/Policy-model.php');
                            $ZURICH_POL = new ZURICH_POL_Modal($pdo);
                            $ZURICH_POLList = $ZURICH_POL->getZURICH_POL($likesearch);
                            require_once(BASE_URL . '/addon/Life/views/Zurich/Policy-view.php');
                            require_once(BASE_URL . '/addon/Life/models/Zurich/Keyfacts-model.php');
                            $ZURICH_KF = new ZURICH_KFModal($pdo);
                            $ZURICH_KFList = $ZURICH_KF->getZURICH_KF($likesearch);
                            require_once(BASE_URL . '/addon/Life/views/Zurich/Keyfacts-view.php');
                        }
                        if (isset($HAS_SCOTTISH_WIDOWS_POL) && $HAS_SCOTTISH_WIDOWS_POL == 1) {
                            require_once(BASE_URL . '/addon/Life/models/ScottishWidows/Policy-model.php');
                            $SW_POL = new SW_POL_Modal($pdo);
                            $SW_POLList = $SW_POL->getSW_POL($likesearch);
                            require_once(BASE_URL . '/addon/Life/views/ScottishWidows/Policy-view.php');
                            require_once(BASE_URL . '/addon/Life/models/ScottishWidows/Keyfacts-model.php');
                            $SW_KF = new SW_KFModal($pdo);
                            $SW_KFList = $SW_KF->getSW_KF($likesearch);
                            require_once(BASE_URL . '/addon/Life/views/ScottishWidows/Keyfacts-view.php');
                        }
                        if (isset($RENEW_LIFE_CID) && $RENEW_LIFE_CID > 0) {
                            if (file_exists(BASE_URL . '/addon/RENEW_LIFE_CRM/profiles/' . $RENEW_LIFE_CID . '.html')) { ?>

                                <a href="/addon/RENEW_LIFE_CRM/profiles/<?php echo $RENEW_LIFE_CID; ?>.html"
                                   target="_blank" class="btn btn-default"><i class="far fa-user"></i> Project X
                                    ADL
                                    Profile</a>

                            <?php }
                        }
                        if (isset($HAS_RL_POL) && $HAS_RL_POL == 1) {
                            $LGquery = $pdo->prepare("SELECT file FROM tbl_uploads WHERE file like :CID and uploadtype ='RLpolicy'");
                            $LGquery->bindParam(':CID', $likesearch, PDO::PARAM_STR);
                            $LGquery->execute();
                            while ($timelineNotes = $LGquery->fetch(PDO::FETCH_ASSOC)) {
                                $LGPOLFILE = $timelineNotes['file'];
                                if (file_exists(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT',
                                        FILTER_SANITIZE_SPECIAL_CHARS) . "/uploads/$LGPOLFILE")) {
                                    ?>
                                    <a href="/uploads/<?php echo $LGPOLFILE; ?>" target="_blank"
                                       class="btn btn-default"><i class="far fa-file-pdf"></i> Royal London
                                        Policy</a>
                                <?php } else { ?>
                                    <a href="/uploads/life/<?php echo $search; ?>/<?php echo $LGPOLFILE; ?>"
                                       target="_blank" class="btn btn-default"><i class="far fa-file-pdf"></i> Royal
                                        London Policy</a>
                                    <?php
                                }
                            }
                            $LGKeyfactsquery = $pdo->prepare("SELECT file FROM tbl_uploads WHERE file like :CID and uploadtype ='RLkeyfacts'");
                            $LGKeyfactsquery->bindParam(':CID', $likesearch, PDO::PARAM_STR);
                            $LGKeyfactsquery->execute();
                            while ($timelineNotes = $LGKeyfactsquery->fetch(PDO::FETCH_ASSOC)) {
                                $LGFILE = $timelineNotes['file'];
                                if (file_exists(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT',
                                        FILTER_SANITIZE_SPECIAL_CHARS) . "/uploads/$LGFILE")) {
                                    ?>
                                    <a href="/uploads/<?php echo $LGFILE; ?>" target="_blank"
                                       class="btn btn-default"><i
                                            class="far fa-file-pdf"></i> Royal London Keyfacts</a>
                                <?php } else { ?>
                                    <a href="/uploads/life/<?php echo $search; ?>/<?php echo $LGFILE; ?>"
                                       target="_blank" class="btn btn-default"><i class="far fa-file-pdf"></i> Royal
                                        London Keyfacts</a>
                                    <?php
                                }
                            }
                        }
                        if (isset($HAS_WOL_POL) && $HAS_WOL_POL == 1) {
                            $WOLquery = $pdo->prepare("SELECT file FROM tbl_uploads WHERE file like :CID and uploadtype ='WOLpolicy'");
                            $WOLquery->bindParam(':CID', $likesearch, PDO::PARAM_STR);
                            $WOLquery->execute();
                            while ($timelineNotes = $WOLquery->fetch(PDO::FETCH_ASSOC)) {
                                $WOLPOLFILE = $timelineNotes['file'];
                                if (file_exists(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT',
                                        FILTER_SANITIZE_SPECIAL_CHARS) . "/uploads/$WOLPOLFILE")) {
                                    ?>
                                    <a href="/uploads/<?php echo $WOLPOLFILE; ?>" target="_blank"
                                       class="btn btn-default"><i class="far fa-file-pdf"></i> WOL Policy</a>
                                <?php } else { ?>
                                    <a href="/uploads/life/<?php echo $search; ?>/<?php echo $WOLPOLFILE; ?>"
                                       target="_blank" class="btn btn-default"><i class="far fa-file-pdf"></i> WOL
                                        Policy</a>
                                    <?php
                                }
                            }
                            $WOLKeyfactsquery = $pdo->prepare("SELECT file FROM tbl_uploads WHERE file like :CID and uploadtype ='WOLkeyfacts'");
                            $WOLKeyfactsquery->bindParam(':CID', $likesearch, PDO::PARAM_STR);
                            $WOLKeyfactsquery->execute();
                            while ($timelineNotes = $WOLKeyfactsquery->fetch(PDO::FETCH_ASSOC)) {
                                $WOLFILE = $timelineNotes['file'];
                                if (file_exists(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT',
                                        FILTER_SANITIZE_SPECIAL_CHARS) . "/uploads/$WOLFILE")) {
                                    ?>
                                    <a href="/uploads/<?php echo $WOLFILE; ?>" target="_blank"
                                       class="btn btn-default"><i class="far fa-file-pdf"></i> WOL Keyfacts</a>
                                <?php } else { ?>
                                    <a href="/uploads/life/<?php echo $search; ?>/<?php echo $WOLFILE; ?>"
                                       target="_blank" class="btn btn-default"><i class="far fa-file-pdf"></i> WOL
                                        Keyfacts</a>
                                    <?php
                                }
                            }
                        }
                        ?>

                    </div>
                </div>
                <br>

                <?php
                if (isset($HAS_HOME_AGEAS_POL) && $HAS_HOME_AGEAS_POL == 1) {
                    require_once(BASE_URL . '/addon/Home/models/HOMEPoliciesModel.php');
                    $HOMEPolicies = new HOMEPoliciesModal($pdo);
                    $HOMEPoliciesList = $HOMEPolicies->getHOMEPolicies($search);
                    require_once(BASE_URL . '/addon/Home/views/HOME-Policies.php');
                }
                if (isset($HAS_NEW_LG_POL) && $HAS_NEW_LG_POL == 1) {
                    require_once(BASE_URL . '/addon/Life/models/LGPoliciesModel.php');
                    $LGPolicies = new LGPoliciesModal($pdo);
                    $LGPoliciesList = $LGPolicies->getLGPolicies($search);
                    require_once(BASE_URL . '/addon/Life/views/LG-Policies.php');
                }
                if (isset($HAS_VIT_POL) && $HAS_VIT_POL == 1) {
                    require_once(BASE_URL . '/addon/Life/models/Vitality/Policies-modal.php');
                    $VITALITYPolicies = new VITALITYPoliciesModal($pdo);
                    $VITALITYPoliciesList = $VITALITYPolicies->getVITALITYPolicies($search);
                    require_once(BASE_URL . '/addon/Life/views/Vitality/Policies-view.php');
                }
                if (isset($HAS_NEW_VIT_POL) && $HAS_NEW_VIT_POL == 1) {
                    require_once(BASE_URL . '/addon/Life/models/Insurers/Vitality/Policies-modal.php');
                    $VITALITYPolicies = new VITALITY_NEW_PoliciesModal($pdo);
                    $VITALITYPoliciesList = $VITALITYPolicies->getVITALITYPolicies($search);
                    require_once(BASE_URL . '/addon/Life/views/Insurers/Vitality/Policies-view.php');
                }
                if (isset($HAS_AEG_POL) && $HAS_AEG_POL == 1) {
                    require_once(BASE_URL . '/addon/Life/models/Insurers/Aegon/Policies-modal.php');
                    $AEGONPolicies = new AEGON_PoliciesModal($pdo);
                    $AEGONPoliciesList = $AEGONPolicies->getAEGONPolicies($search);
                    require_once(BASE_URL . '/addon/Life/views/Insurers/Aegon/Policies-view.php');
                }
                if (isset($HAS_LV_POL) && $HAS_LV_POL == 1) {
                    require_once(BASE_URL . '/addon/Life/models/LV/Policies-modal.php');
                    $LVPolicies = new LVPoliciesModal($pdo);
                    $LVPoliciesList = $LVPolicies->getLVPolicies($search);
                    require_once(BASE_URL . '/addon/Life/views/LV/Policies-view.php');
                }

                if (isset($HAS_NATIONAL_FRIENDLY_POL) && $HAS_NATIONAL_FRIENDLY_POL == 1) {
                    require_once(BASE_URL . '/addon/Life/models/nationalFriendly/Policies-modal.php');
                    $NationalFriendlyPolicies = new NationalFriendlyPoliciesModal($pdo);
                    $NationalFriendlyPoliciesList = $NationalFriendlyPolicies->getNationalFriendlyPolicies($search);
                    require_once(BASE_URL . '/addon/Life/views/nationalFriendly/Policies-view.php');
                }
                if (isset($HAS_HSBC_POL) && $HAS_HSBC_POL == 1) {
                    require_once(BASE_URL . '/addon/Life/models/HSBC/Policies-modal.php');
                    $HSBCPolicies = new HSBCPoliciesModal($pdo);
                    $HSBCPoliciesList = $HSBCPolicies->getHSBCPolicies($search);
                    require_once(BASE_URL . '/addon/Life/views/HSBC/Policies-view.php');
                }
                if (isset($HAS_EXETER_POL) && $HAS_EXETER_POL == 1) {
                    require_once(BASE_URL . '/addon/Life/models/theExeter/Policies-modal.php');
                    $EXETERPolicies = new EXETERPoliciesModal($pdo);
                    $EXETERPoliciesList = $EXETERPolicies->getEXETERPolicies($search);
                    require_once(BASE_URL . '/addon/Life/views/theExeter/Policies-view.php');
                }
                if (isset($HAS_WOL_POL) && $HAS_WOL_POL == 1) {
                    require_once(BASE_URL . '/addon/Life/models/WOL/Policies-modal.php');
                    $WOLPolicies = new WOLPoliciesModal($pdo);
                    $WOLPoliciesList = $WOLPolicies->getWOLPolicies($search);
                    require_once(BASE_URL . '/addon/Life/views/WOL/Policies-view.php');
                }
                if (isset($HAS_RL_POL) && $HAS_RL_POL == 1) {
                    require_once(BASE_URL . '/addon/Life/models/RoyalLondon/Policies-modal.php');
                    $RLPolicies = new RLPoliciesModal($pdo);
                    $RLPoliciesList = $RLPolicies->getRLPolicies($search);
                    require_once(BASE_URL . '/addon/Life/views/RoyalLondon/Policies-view.php');
                }
                if (isset($HAS_AVI_POL) && $HAS_AVI_POL == 1) {
                    require_once(BASE_URL . '/addon/Life/models/Aviva/aviva_policies-modal.php');
                    $AvivaPolicies = new AvivaPoliciesModal($pdo);
                    $AvivaPoliciesList = $AvivaPolicies->getAvivaPolicies($search);
                    require_once(BASE_URL . '/addon/Life/views/Aviva/aviva_policies-view.php');
                }
                if (isset($HAS_ZURICH_POL) && $HAS_ZURICH_POL == 1) {
                    require_once(BASE_URL . '/addon/Life/models/Zurich-pol-model.php');
                    $ZurichPolicies = new ZurichPoliciesModal($pdo);
                    $ZurichPoliciesList = $ZurichPolicies->getZurichPolicies($search);
                    require_once(BASE_URL . '/addon/Life/views/Zurich-pol-view.php');
                }
                if (isset($HAS_SCOTTISH_WIDOWS_POL) && $HAS_SCOTTISH_WIDOWS_POL == 1) {
                    require_once(BASE_URL . '/addon/Life/models/SW-pol-model.php');
                    $SWPolicies = new SWPoliciesModal($pdo);
                    $SWPoliciesList = $SWPolicies->getSWPolicies($search);
                    require_once(BASE_URL . '/addon/Life/views/SW-pol-view.php');
                }

                ?>

            </div>
        </div>

        <div id="menu1" class="tab-pane fade">
            <br>

            <?php
            if ($ffcallbacks == '1') {
                $query = $pdo->prepare("SELECT CONCAT(callback_time, ' - ', callback_date) AS calltimeid from scheduled_callbacks WHERE client_id =:CID");
                $query->bindParam(':CID', $search, PDO::PARAM_INT);
                $query->execute();
                $pullcall = $query->fetch(PDO::FETCH_ASSOC);
                $calltimeid = $pullcall['calltimeid'];
                echo "<button type=\"button\" class=\"btn btn-default btn-block\" data-toggle=\"modal\" data-target=\"#schcallback\"><i class=\"fa fa-calendar-check-o\"></i> Schedule callback ($calltimeid)</button>";
            }
            ?>
        </div>

        <div id="smsModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><i class='far fa-comment-dots'></i> Send Message</h4>
                    </div>
                    <div class="modal-body">
                        <?php if ($ffsms == '1') { ?>
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#smsTab" data-toggle="tab"><i class="fa fa-mobile"></i> SMS</a>
                                </li>
                                <li><a href="#whatsAppTab" data-toggle="tab"><i class="fab fa-whatsapp"></i>
                                        WhatsApp</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="smsTab">

                                    <?php
                                    $CHECK_NUM = strlen($newClientResponse['phone_number']);
                                    if ($CHECK_NUM > 11) {
                                        $CHK_NUM = '0';
                                    }
                                    if ($CHECK_NUM <= 10) {
                                        $CHK_NUM = '0';
                                    }
                                    if ($CHECK_NUM == 11) {
                                        $CHK_NUM = '1';
                                    }
                                    if ($CHK_NUM == '0') {
                                        ?>

                                        <div class="notice notice-danger" role="alert"><strong><i
                                                    class="fa fa-exclamation-circle fa-lg"></i> Invalid
                                                Number:</strong>
                                            Please check that the phone number is correct and is in the correct
                                            format
                                            (i.e.
                                            07401434619).
                                        </div>

                                    <?php }
                                    if ($CHK_NUM == '1') {
                                        ?>
                                        <form class="AddClient">
                                            <p>
                                                <label for="phone_number">Contact Number:</label>
                                                <input class="form-control" type="tel" id="phone_number"
                                                       name="phone_number"
                                                       value="<?php echo $newClientResponse['phone_number'] ?>"
                                                       readonly>
                                            </p>
                                        </form>

                                        <form class="AddClient" method="POST" action="<?php if ($CHK_NUM == '0') {
                                            echo "#";
                                        }
                                        if ($CHK_NUM == '1') {
                                            echo "/addon/Life/SMS/Send.php";
                                        } ?>">

                                            <?php

                                            $address = $newClientResponse['address1'] . " " . $newClientResponse['post_code'];
                                            $email = $newClientResponse['email'];

                                            ?>

                                            <input type="hidden" name="search" value="<?php echo $search; ?>">
                                            <input type="hidden" name="address" value="<?php echo $address; ?>">
                                            <input type="hidden" name="email" value="<?php echo $email; ?>">
                                            <div class="form-group">

                                                <label for="selectsms">Message:</label>
                                                <select class="form-control" name="selectopt" id="selectopt"
                                                        required>
                                                    <option value="">Select message...</option>

                                                    <?php

                                                    $SMSquery = $pdo->prepare("SELECT title from sms_templates WHERE company=:COMPANY");
                                                    $SMSquery->bindParam(':COMPANY', $COMPANY_ENTITY,
                                                        PDO::PARAM_STR);
                                                    $SMSquery->execute();
                                                    if ($SMSquery->rowCount() > 0) {
                                                        while ($smstitles = $SMSquery->fetch(PDO::FETCH_ASSOC)) {
                                                            $smstitle = $smstitles['title'];
                                                            echo "<option value='$smstitle'>$smstitle</option>";
                                                        }
                                                    }
                                                    ?>

                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="SMS_INSURER">Insurer:</label>
                                                <select class="form-control" name="SMS_INSURER" id="SMS_INSURER"
                                                        required>
                                                    <option value="">Select insurer...</option>
                                                    <?php if (isset($royalLondonActive) && $royalLondonActive == 1) { ?>
                                                        <option value="Royal London">Royal London</option>
                                                        <?php

                                                    }

                                                    if (isset($lvActive) && $lvActive == 1) { ?>
                                                        <option value="LV">LV</option>
                                                        <?php

                                                    }

                                                    if (isset($oneFamilyActive) && $oneFamilyActive == 1) { ?>
                                                        <option value="One Family">One Family</option>
                                                        <?php

                                                    }

                                                    if (isset($aegonActive) && $aegonActive == 1) { ?>
                                                        <option value="Aegon">Aegon</option>
                                                        <?php

                                                    }

                                                    if (isset($hsbcActive) && $hsbcActive == 1) { ?>
                                                        <option value="HSBC">HSBC</option>
                                                    <?php } ?>

                                                </select>
                                            </div>

                                            <div id="General_Contact" class="SELECTED_SMS well"
                                                 style="display:none">
                                                [CLIENT_NAME] Its Very Important We Speak To You Regarding Your Life
                                                Insurance
                                                Policy. Please Contact [COMPANY NAME] On [COMPANY TEL].
                                            </div>
                                            <div id="Bounced_DD" class="SELECTED_SMS well" style="display:none">
                                                Hi FIRSTNAME,
                                                LASTNAME].
                                                We have been notified that there is an ongoing issue with your
                                                [INSURER]direct
                                                debit.
                                                It is very important we speak to you as you run the risk of your
                                                life
                                                insurance
                                                policy being cancelled and your cover will stop.
                                                Please contact us ASAP on [COMPANY_TEL] or alternatively please
                                                reply to
                                                this
                                                message with a preferred contact time for a callback.
                                                Kind regards,
                                                [USERNAME]
                                                [COMPANY_NAME].
                                            </div>
                                            <div id="Cancelled_DD" class="SELECTED_SMS well" style="display:none">
                                                Hi FIRSTNAME,
                                                LASTNAME].
                                                We have been notified that there is an ongoing issue with your
                                                [INSURER]direct
                                                debit.
                                                It is very important we speak to you as you run the risk of your
                                                life
                                                insurance
                                                policy being cancelled and your cover will stop.
                                                Please contact us ASAP on [COMPANY_TEL] or alternatively please
                                                reply to
                                                this
                                                message with a preferred contact time for a callback.
                                                Kind regards,
                                                [USERNAME]
                                                [COMPANY_NAME].
                                            </div>
                                            <div id="CFO" class="SELECTED_SMS well" style="display:none">Hi
                                                [FIRSTNAME,
                                                LASTNAME].
                                                We have been notified that you have cancelled your [INSURER] life
                                                insurance.
                                                It is very important we speak to you as you run the risk of no
                                                longer
                                                being
                                                covered.
                                                Please contact us ASAP on [COMPANY_TEL] or alternatively please
                                                reply to
                                                this
                                                message with a preferred contact time for a callback.
                                                Kind regards,
                                                [USERNAME]
                                                [COMPANY_NAME].
                                            </div>
                                            <div id="Lapsed" class="SELECTED_SMS well" style="display:none">Hi
                                                [FIRSTNAME,
                                                LASTNAME].
                                                We have been notified that you have cancelled your [INSURER] life
                                                insurance.
                                                It is very important we speak to you as you run the risk of no
                                                longer
                                                being
                                                covered.
                                                Please contact us ASAP on [COMPANY_TEL] or alternatively please
                                                reply to
                                                this
                                                message with a preferred contact time for a callback.
                                                Kind regards,
                                                [USERNAME]
                                                [COMPANY_NAME].
                                            </div>
                                            <div id="CYD" class="SELECTED_SMS well" style="display:none">Your Check
                                                Your
                                                Details
                                                Form Is Outstanding For Your Life Insurance Policy. Please Ensure
                                                This
                                                Is
                                                Completed To [INSURER] via My Account As Soon As Possible. Any
                                                Queries
                                                Please
                                                Contact [COMPANY_NAME] On [COMPANY_TEL].
                                            </div>
                                            <div id="CYD_DD" class="SELECTED_SMS well" style="display:none">Your
                                                [INSURER] Check
                                                Your Details Form Is Still Outstanding. You Will Have Noticed Your
                                                First
                                                Direct
                                                Debit Has Been Collected Or Will Be Shortly. Please Ensure Your
                                                Check
                                                Your
                                                Details Is Completed Online via My Account. Any Queries Please
                                                Contact
                                                [COMPANY_NAME] On [COMPANY_TEL]
                                            </div>
                                            <div id="CYD_POST" class="SELECTED_SMS well" style="display:none">Your
                                                Check
                                                Your
                                                Details form is outstanding for your Life Insurance policy. Please
                                                sign
                                                and
                                                return the form in the freepost envelope as soon as possible. Any
                                                queries please
                                                contact [COMPANY_NAME] on [COMPANY_TEL]
                                            </div>
                                            <div id="Direct_Debit" class="SELECTED_SMS well" style="display:none">
                                                Your
                                                direct
                                                debit with [INSURER] is due to be taken shortly if
                                                it hasn’t been taken already. Any further direct debits will be
                                                taken on
                                                your
                                                preferred collection date. If you have any issues please don’t
                                                hesitate
                                                to
                                                contact us on [COMPANY_TEL]. Many thanks [COMPANY_NAME].
                                            </div>
                                            <div id="EWS_Bounced" class="SELECTED_SMS well" style="display:none">
                                                Your
                                                bank has
                                                told us that they cannot pay your life insurance premium with
                                                [INSURER]
                                                by
                                                direct debit. To restart your direct debit or update your bank
                                                details,
                                                please
                                                call us on [COMPANY_TEL]. Yours Sincerely, Customer Services,
                                                [COMPANY_NAME].
                                            </div>
                                            <div id="EWS_DD_Cancelled" class="SELECTED_SMS well"
                                                 style="display:none">
                                                Your bank
                                                has told us the direct debit instruction for your life insurance
                                                with
                                                [INSURER]
                                                has been cancelled, so it cannot be used to collect future premiums.
                                                To
                                                restart
                                                your direct debit or update your bank details, please call us on
                                                [COMPANY_TEL].
                                            </div>
                                            <div id="For_any_queries_call_us" class="SELECTED_SMS well"
                                                 style="display:none">
                                                Regarding your life insurance policy with us, should you have any
                                                questions or
                                                queries please do not hesitate too contact us on [COMPANY_TEL] or
                                                via
                                                email
                                                [COMPANY_EMAIL].
                                            </div>
                                            <div id="Incomplete_Trust" class="SELECTED_SMS well"
                                                 style="display:none">We
                                                can see
                                                that you have not yet completed your trust forms. If you have any
                                                questions
                                                please contact our [COMPANY_NAME] customer care team on
                                                [COMPANY_TEL].
                                            </div>
                                            <div id="Welcome" class="SELECTED_SMS well" style="display:none">Your
                                                Policy
                                                Has
                                                Been Submitted With [INSURER]. All Correspondence Will Follow
                                                Shortly.
                                                Any
                                                Queries Please Contact [COMPANY_NAME] On [COMPANY_TEL].
                                            </div>
                                            <div id="No_Answer_Happy_Call" class="SELECTED_SMS well"
                                                 style="display:none">We’ve
                                                tried contacting you for a follow up call regarding your life
                                                insurance
                                                policy
                                                with [INSURER]. Should you have any questions or queries please do
                                                not
                                                hesitate
                                                to contact us on [COMPANY_TEL] or [COMPANY_EMAIL]
                                            </div>


                                            <input type="hidden" id="FullName" name="FullName"
                                                   value="<?php echo $newClientResponse['first_name']; ?> <?php echo $newClientResponse['last_name']; ?>">
                                            <input type="hidden" id="phone_number" name="phone_number"
                                                   value="<?php echo $newClientResponse['phone_number']; ?>">

                                            <div style="text-align: center;">
                                                <button type='submit' class='btn btn-success'><i
                                                        class='fa fa-mobile'></i> SEND
                                                    TEMPLATE SMS
                                                </button>
                                            </div>

                                        </form>
                                        <br>
                                        <?php if (in_array($hello_name, $Level_8_Access, true)) { ?>

                                            <form class="AddClient" method="POST"
                                                  action="<?php if ($CHK_NUM == '0') {
                                                      echo "#";
                                                  }
                                                  if ($CHK_NUM == '1') {
                                                      echo "SMS/CusSend.php?EXECUTE=1";
                                                  } ?>">

                                                <input type="hidden" name="search" value="<?php echo $search; ?>">
                                                <div class="form-group">
                                                    <label for="message">Custom MSG:</label>
                                                    <textarea class="form-control" name="message"
                                                              required></textarea>
                                                </div>

                                                <input type="hidden" id="FullName" name="FullName"
                                                       value="<?php echo $newClientResponse['first_name']; ?> <?php echo $newClientResponse['last_name']; ?>">
                                                <input type="hidden" id="phone_number" name="phone_number"
                                                       value="<?php echo $newClientResponse['phone_number']; ?>">
                                                <div style="text-align: center;">
                                                    <button type='submit' class='btn btn-primary'><i
                                                            class='fa fa-mobile'></i>
                                                        SEND CUSTOM SMS
                                                    </button>
                                                </div>

                                            </form>

                                            <?php
                                        }
                                    } ?>

                                </div>
                                <div class="tab-pane" id="whatsAppTab">

                                    <?php
                                    $CHECK_NUM = strlen($newClientResponse['phone_number']);
                                    if ($CHECK_NUM > 11) {
                                        $CHK_NUM = '0';
                                    }
                                    if ($CHECK_NUM <= 10) {
                                        $CHK_NUM = '0';
                                    }
                                    if ($CHECK_NUM == 11) {
                                        $CHK_NUM = '1';
                                    }
                                    if ($CHK_NUM == '0') {
                                        ?>

                                        <div class="notice notice-danger" role="alert"><strong><i
                                                    class="fa fa-exclamation-circle fa-lg"></i> Invalid
                                                Number:</strong>
                                            Please check that the phone number is correct and is in the correct
                                            format
                                            (i.e.
                                            07401434619).
                                        </div>

                                    <?php }
                                    if ($CHK_NUM == '1') {
                                        ?>
                                        <form class="AddClient">
                                            <p>
                                                <label for="phone_number">Contact Number:</label>
                                                <input class="form-control" type="tel" id="phone_number"
                                                       name="phone_number"
                                                       value="<?php echo $newClientResponse['phone_number'] ?>"
                                                       readonly>
                                            </p>
                                        </form>


                                        <br>


                                        <form class="AddClient" method="POST" action="<?php if ($CHK_NUM == '0') {
                                            echo "#";
                                        }
                                        if (in_array($hello_name, $Level_10_Access, true)) {
                                            if ($CHK_NUM == '1') {
                                                echo "/addon/whatsApp/php/send.php?EXECUTE=1&CID=$search";
                                            }
                                        } ?>">

                                            <div class="form-group">
                                                <label for="message">Custom WhatsApp:</label>
                                                <textarea class="form-control" name="whatsAppMessage"
                                                          required></textarea>
                                            </div>

                                            <div style="text-align: center;">
                                                <button type='submit' class='btn btn-primary'><i
                                                        class='fa fa-mobile'></i>
                                                    Send WhatsApp message
                                                </button>
                                            </div>

                                        </form>

                                        <?php
                                    } ?>

                                </div>
                            </div>

                            <br>


                        <?php } else {
                            ?>

                            <div class="alert alert-info"><strong>Info!</strong> SMS feature not enabled.</div>
                        <?php } ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($ALT_PHONE_NUMBER)) { ?>

            <div id="smsModalalt" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title"><i class='far fa-comment-dots'></i> Send SMS</h4>
                        </div>
                        <div class="modal-body">

                            <?php
                            if ($ffsms == '1') {
                                ?>
                                <br>

                                <?php
                                $CHECK_NUM_ALT = strlen($newClientResponse['alt_number']);
                                if ($CHECK_NUM_ALT > 11) {
                                    $CHK_NUM_ALT = '0';
                                }
                                if ($CHECK_NUM_ALT <= 10) {
                                    $CHK_NUM_ALT = '0';
                                }
                                if ($CHECK_NUM_ALT == 11) {
                                    $CHK_NUM_ALT = '1';
                                }
                                if ($CHK_NUM_ALT == '0') {
                                    ?>

                                    <div class="notice notice-danger" role="alert"><strong><i
                                                class="fa fa-exclamation-circle fa-lg"></i> Invalid
                                            Number:</strong>
                                        Please check that the phone number is correct and is in the correct format
                                        (i.e.
                                        07401434619)
                                    </div>


                                <?php }
                                if ($CHK_NUM_ALT == '1') { ?>
                                    <form class="AddClient">
                                        <p>
                                            <label for="phone_number">Contact Number:</label>
                                            <input class="form-control" type="tel" id="phone_number"
                                                   name="phone_number"
                                                   value="<?php echo $newClientResponse['alt_number'] ?>" readonly>
                                        </p>
                                    </form>


                                    <form class="AddClient" method="POST" action="<?php if ($CHK_NUM == '0') {
                                        echo "#";
                                    }
                                    if ($CHK_NUM == '1') {
                                        echo "/addon/Life/SMS/Send.php";
                                    } ?>">

                                        <input type="hidden" name="search" value="<?php echo $search; ?>">
                                        <div class="form-group">

                                            <label for="selectsms">Message:</label>
                                            <select class="form-control" name="selectopt" id="selectopt" required>
                                                <option value="">Select message...</option>

                                                <?php
                                                if (isset($WHICH_COMPANY)) {
                                                    if ($WHICH_COMPANY == 'Legal and General') {
                                                        $SMS_INSURER = 'Legal and General';
                                                    } elseif ($WHICH_COMPANY == 'One Family') {
                                                        $SMS_INSURER = 'One Family';
                                                    } elseif ($WHICH_COMPANY == 'Aviva') {
                                                        $SMS_INSURER = 'Aviva';
                                                    } elseif ($WHICH_COMPANY == 'Vitality') {
                                                        $SMS_INSURER = 'Vitality';
                                                    } elseif ($WHICH_COMPANY == 'Scottish Widows') {
                                                        $SMS_INSURER = 'Scottish Widows';
                                                    } elseif ($WHICH_COMPANY == 'Zurich') {
                                                        $SMS_INSURER = 'Zurich';
                                                    } elseif ($WHICH_COMPANY == 'LV') {
                                                        $SMS_INSURER = 'LV';
                                                    } elseif ($WHICH_COMPANY == 'Aegon') {
                                                        $SMS_INSURER = 'Aegon';
                                                    } elseif ($WHICH_COMPANY == 'Royal London') {
                                                        $SMS_INSURER = 'Royal London';
                                                    }
                                                }
                                                $SMSquery = $pdo->prepare("SELECT title from sms_templates WHERE insurer =:insurer AND company=:COMPANY OR insurer='NA' AND company=:COMPANY2");
                                                $SMSquery->bindParam(':insurer', $SMS_INSURER, PDO::PARAM_STR);
                                                $SMSquery->bindParam(':COMPANY', $WHICH_COMPANY,
                                                    PDO::PARAM_STR);
                                                $SMSquery->bindParam(':COMPANY2', $WHICH_COMPANY,
                                                    PDO::PARAM_STR);
                                                $SMSquery->execute();
                                                if ($SMSquery->rowCount() > 0) {
                                                    while ($smstitles = $SMSquery->fetch(PDO::FETCH_ASSOC)) {
                                                        $smstitle = $smstitles['title'];
                                                        echo "<option value='$smstitle'>$smstitle</option>";
                                                    }
                                                }
                                                ?>

                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="SMS_INSURER">Insurer:</label>
                                            <select class="form-control" name="SMS_INSURER" id="SMS_INSURER"
                                                    required>
                                                <option value="">Select insurer...</option>
                                                <option value="Royal London">Royal London</option>
                                                <option value="LV">LV</option>
                                                <option value="One Family">One Family</option>
                                                <option value="Aegon">Aegon</option>
                                            </select>
                                        </div>

                                        <input type="hidden" id="FullName" name="FullName"
                                               value="<?php echo $newClientResponse['first_name2']; ?> <?php echo $newClientResponse['last_name2']; ?>">
                                        <input type="hidden" id="phone_number" name="phone_number"
                                               value="<?php echo $newClientResponse['alt_number']; ?>">

                                        <div style="text-align: center;">
                                            <button type='submit' class='btn btn-success'><i
                                                    class='fa fa-mobile'></i>
                                                SEND TEMPLATE SMS
                                            </button>
                                        </div>

                                    </form>

                                <?php }
                            } else { ?>

                                <div class="alert alert-info"><strong>Info!</strong> SMS feature not enabled.</div>
                            <?php } ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

        <?php } ?>

        <!-- START TAB 3 -->
        <div id="menu2" class="tab-pane fade">

            <?php
            $fileuploaded = filter_input(INPUT_GET, 'fileuploaded', FILTER_SANITIZE_SPECIAL_CHARS);
            if (isset($fileuploaded)) {
                $uploadtypeuploaded = filter_input(INPUT_GET, 'fileupname', FILTER_SANITIZE_SPECIAL_CHARS);
                print("<div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa fa-upload fa-lg\"></i> Success:</strong> $uploadtypeuploaded uploaded!</div>");
            }
            $fileuploadedfail = filter_input(INPUT_GET, 'fileuploadedfail', FILTER_SANITIZE_SPECIAL_CHARS);
            if (isset($fileuploadedfail)) {
                $uploadtypeuploaded = filter_input(INPUT_GET, 'fileupname', FILTER_SANITIZE_SPECIAL_CHARS);
                print("<div class=\"notice notice-danger\" role=\"alert\"><strong><i class=\"fa fa-exclamation-triangle fa-lg\"></i> Error:</strong> $uploadtypeuploaded <b>upload failed!</b></div>");
            }
            $UPLOAD = filter_input(INPUT_GET, 'UPLOAD', FILTER_SANITIZE_SPECIAL_CHARS);
            if (isset($UPLOAD)) {
                if ($UPLOAD == 'MAX') {
                    echo "<div class=\"notice notice-danger\" role=\"alert\"><strong><i class=\"fa fa-exclamation-triangle fa-lg\"></i> The filesize of the upload is too big!</strong></div>";
                }
            }
            ?>
            <div class="container">

                <?php

                require_once(BASE_URL . '/app/views/clientFileUpload-view.php');
                require_once(BASE_URL . '/app/views/clientFileUploadList-view.php');

                ?>

                <?php if (in_array($hello_name, $Level_10_Access, true) || $hello_name == 'Tina Dennis') {

                    $query = $pdo->prepare("SELECT file, uploadtype, id, added_date FROM tbl_uploads WHERE file like :file");
                    $query->bindParam(':file', $likesearch, PDO::PARAM_STR, 150);
                    $query->execute();
                    $i = 0;
                    if ($query->rowCount() > 0) {

                        require_once(BASE_URL . '/app/views/deleteClientUploads.php');

                    }

                }

                ?>

            </div>
        </div>

        <div id="PADTAB" class="tab-pane fade">
            <div class="container">

                <?php

                $getPadStats = new ADL\padStats($pdo);
                $getPadStats->setCID($search);
                $padStatsResponse = $getPadStats->getPadStatsByClientID();
                require_once(__DIR__ . '/../addon/Life/PAD/views/client/clientTabPad-view.php');

                ?>

            </div>
        </div>

        <?php if (in_array($hello_name, $Level_9_Access, true)) { ?>

            <div id="TRACKING" class="tab-pane fade">
                <div class="container">

                    <?php
                    require_once(__DIR__ . '/models/client/UserTrackingModel.php');
                    $UserTracking = new UserTrackingModal($pdo);
                    $UserTrackingList = $UserTracking->getUserTracking($search);
                    require_once(__DIR__ . '/views/client/UserTracking.php');
                    ?>
                </div>
            </div>

        <?php }

        if (isset($fffinancials) && $fffinancials == 1) {
            if (in_array($hello_name, $Level_10_Access, true)) { ?>

                <div id="menu3" class="tab-pane fade">
                    <div class="container">

                        <?php
                        require_once(BASE_URL . '/addon/Life/models/financials/renew_life/renew_life_financials-model.php');
                        $RENEW_LIFE_FIN = new RENEW_LIFE_FINModel($pdo);
                        $RENEW_LIFE_FINList = $RENEW_LIFE_FIN->getRENEW_LIFE_FIN($search);
                        require_once(BASE_URL . '/addon/Life/views/financials/renew_life/renew_life_financials-view.php');
                        if (isset($HAS_NEW_LG_POL) && $HAS_NEW_LG_POL == 1) {
                            require_once(BASE_URL . '/addon/Life/models/financials/transactions/LGModel.php');
                            $LGtrans = new LGtransModel($pdo);
                            $LGtransList = $LGtrans->getLGtrans($search);
                            require_once(BASE_URL . '/addon/Life/views/financials/transactions/lg-view.php');
                        }
                        if (isset($HAS_VIT_POL) && $HAS_VIT_POL == 1) {
                            require_once(BASE_URL . '/addon/Life/models/financials/Vitality/Financial-model.php');
                            $VITtrans = new VITtransModel($pdo);
                            $VITtransList = $VITtrans->getVITtrans($search);
                            require_once(BASE_URL . '/addon/Life/views/financials/Vitality/Financial-view.php');
                        }
                        if (isset($HAS_NEW_VIT_POL) && $HAS_NEW_VIT_POL == 1) {
                            require_once(BASE_URL . '/addon/Life/models/financials/Vitality/vitality_financial-model.php');
                            $VIT_NEW_TRAN = new VIT_NEW_TRANModel($pdo);
                            $VIT_NEW_TRANList = $VIT_NEW_TRAN->getVIT_NEW_TRAN($search);
                            require_once(BASE_URL . '/addon/Life/views/financials/Vitality/vitality_financial_view.php');
                        }
                        if (isset($HAS_RL_POL) && $HAS_RL_POL == 1) {
                            require_once(BASE_URL . '/addon/Life/models/financials/RoyalLondon/Financial-model.php');
                            $RLtrans = new RLtransModel($pdo);
                            $RLtransList = $RLtrans->getRLtrans($search);
                            require_once(BASE_URL . '/addon/Life/views/financials/RoyalLondon/Financial-view.php');
                        }
                        if (isset($HAS_LV_POL) && $HAS_LV_POL == 1) {
                            require_once(BASE_URL . '/addon/Life/models/financials/LV/Financial-model.php');
                            $LVtrans = new LVtransModel($pdo);
                            $LVtransList = $LVtrans->getLVtrans($search);
                            require_once(BASE_URL . '/addon/Life/views/financials/LV/Financial-view.php');
                        }
                        if (isset($HAS_AVI_POL) && $HAS_AVI_POL == 1) {
                            require_once(BASE_URL . '/addon/Life/models/financials/Aviva/aviva_financial-model.php');
                            $AVIVA_trans = new AVIVA_transModel($pdo);
                            $AVIVA_transList = $AVIVA_trans->getAVIVA_trans($search);
                            require_once(BASE_URL . '/addon/Life/views/financials/Aviva/aviva_financial-view.php');
                        }
                        if (isset($HAS_WOL_POL) && $HAS_WOL_POL == 1) {
                            require_once(BASE_URL . '/addon/Life/models/financials/WOL/Financial-model.php');
                            $WOLtrans = new WOLtransModel($pdo);
                            $WOLtransList = $WOLtrans->getWOLtrans($search);
                            require_once(BASE_URL . '/addon/Life/views/financials/WOL/Financial-view.php');
                        }
                        ?>
                    </div>
                </div>
                <?php

            }

        }

        $database->query("SELECT adl_workflows_id FROM adl_workflows WHERE adl_workflows_client_id_fk=:CID");
        $database->bind(':CID', $search);
        $database->execute();
        if ($database->rowCount() >= 1) {
            ?>

        <?php } ?>


        <div id="menu4" class="tab-pane fade">

            <?php
            // Check if client has the new adl work flows
            $database->query("SELECT adl_workflows_id FROM adl_workflows WHERE adl_workflows_client_id_fk=:CID");
            $database->bind(':CID', $search);
            $database->execute();
            if ($database->rowCount() >= 1) {
                $WORKFLOW_TASKS = 1;
            } else {
                $WORKFLOW_TASKS = 0;
            }
            if (isset($WORKFLOW_TASKS) && $WORKFLOW_TASKS == 1) {


                $database->query("SELECT adl_workflows_id FROM adl_workflows WHERE adl_workflows_client_id_fk=:CID");
                $database->bind(':CID', $search);
                $database->execute();
                if ($database->rowCount() >= 1) {

                    $FIVE_DAY_CHK = $pdo->prepare("SELECT adl_workflows_id FROM adl_workflows WHERE adl_workflows_client_id_fk=:CID AND adl_workflows_name='5 day'");
                    $FIVE_DAY_CHK->bindParam(':CID', $search, PDO::PARAM_INT);
                    $FIVE_DAY_CHK->execute();
                    if ($FIVE_DAY_CHK->rowCount() > 0) {

                        $WORKFLOW_TASK_NAME = '5 day';

                        require_once(BASE_URL . '/addon/Workflows/modals/Client/fiveDayWorkflowModal.php');
                        $LifeWorkflows = new FiveDayWorkflow($pdo);
                        $LifeWorkflowsList = $LifeWorkflows->getFiveDayWorkflow($search);
                        require(BASE_URL . '/addon/Workflows/views/Client/workflowsView.php');
                    }

                    /* $FIVE_DAY_CHK = $pdo->prepare("SELECT adl_workflows_id FROM adl_workflows WHERE adl_workflows_client_id_fk=:CID AND adl_workflows_name='18 day'");
                 $FIVE_DAY_CHK->bindParam(':CID', $search, PDO::PARAM_INT);
                 $FIVE_DAY_CHK->execute();
                 if ($FIVE_DAY_CHK->rowCount() > 0) {

                     $WORKFLOW_TASK_NAME = '18 day';

                     require_once(BASE_URL . '/addon/Workflows/modals/Client/eighteenDayWorkflowModal.php');
                     $LifeWorkflows = new eighteenDayWorkflow($pdo);
                     $LifeWorkflowsList = $LifeWorkflows->getEighteenDayWorkflow($search);
                     require(BASE_URL . '/addon/Workflows/views/Client/workflowsView.php');
                 }*/

                }

            } ?>

            <div class='container'>
                <div class="row">
                    <form method="post" id="clientnotessubtab" action="/app/php/AddNotes.php?EXECUTE=1"
                          class="form-horizontal">
                        <legend><h3><span class="label label-info">Add notes</span></h3></legend>
                        <input type="hidden" name="CID" value="<?php echo $search ?>">

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="client_name"></label>
                            <div class="col-md-4">
                                <select id="selectbasic" name="client_name" class="form-control" required>
                                    <option
                                        value="<?php echo $newClientResponse['title']; ?> <?php echo $newClientResponse['first_name']; ?> <?php echo $newClientResponse['last_name']; ?>"><?php echo "$newClientResponse[first_name] $newClientResponse[last_name]"; ?></option>
                                    <?php if (!empty($newClientResponse['title2'])) { ?>
                                        <option
                                            value="<?php echo $newClientResponse['title2']; ?> <?php echo $newClientResponse['first_name2']; ?> <?php echo $newClientResponse['last_name2']; ?>"><?php echo "$newClientResponse[first_name2] $newClientResponse[last_name2]"; ?></option>
                                    <?php } ?>
                                    <option value="Compliant">Log Compliant</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12 control-label" for="textarea"></label>
                            <div class="col-md-12">
                                <textarea id="notes" name="notes" class="summernote" maxlength="2000"
                                          required></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="singlebutton"></label>
                            <div class="col-md-4">
                                <button class="btn btn-primary btn-block"><i class="fas fa-edit"></i> Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <?php

            $getTimelineNotes = new ADL\clientNote($search, $pdo);
            $timelineNotesTimelineNotes = $getTimelineNotes->allClientNote();
            require(BASE_URL . '/app/views/clientTimeline.php');

            ?>

        </div>

    </div>
    <?php if ($ffclientemails == '1') { ?>
        <!-- START EMAIL BPOP2 -->
        <div id="email2pop" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">
                            Email: <?php echo $newClientResponse['title2']; ?> <?php echo $newClientResponse['last_name2']; ?>
                            <i>(<?php echo $newClientResponse['email2']; ?>)</i></h4>
                    </div>
                    <div class="modal-body">

                        <form class="AddClient" method="post" action="<?php
                        if (in_array($WHICH_COMPANY, $NEW_COMPANY_ARRAY, true)) {
                            echo "Emails/";
                        } ?>ViewClientEmail.php?life=y" enctype="multipart/form-data">

                            <input type="hidden" name="search" value="<?php echo $search; ?>">
                            <input type="hidden" name="recipient"
                                   value="<?php echo $newClientResponse['title2']; ?> <?php echo $newClientResponse['last_name2']; ?>"
                                   readonly>
                            <input type="hidden" name="email" value="<?php echo $newClientResponse['email2']; ?>"
                                   readonly>

                            <p>
                                <label for="subject">Subject</label>
                                <input name="subject" id="subject" placeholder="Subject/Title" type="text" required/>
                            </p>
                            <p>
                                <textarea name="message" id="message" class="summernote"
                                          placeholder="Message"></textarea><br/>
                                <label for="attachment1">Attachment:</label>
                                <input type="file" name="fileToUpload" id="fileToUpload"><br>
                                <label for="attachment2">Attachment 2:</label>
                                <input type="file" name="fileToUpload2" id="fileToUpload2"><br>
                                <label for="attachment3">Attachment 3:</label>
                                <input type="file" name="fileToUpload3" id="fileToUpload3"><br>
                                <label for="attachment4">Attachment 4:</label>
                                <input type="file" name="fileToUpload4" id="fileToUpload4"><br>
                                <label for="attachment5">Attachment 5:</label>
                                <input type="file" name="fileToUpload5" id="fileToUpload5"><br>
                                <label for="attachment6">Attachment 6:</label>
                                <input type="file" name="fileToUpload6" id="fileToUpload6">
                            </p>
                            <br>
                            <br>
                            <button type="submit" class="btn btn-warning "><span
                                    class="glyphicon glyphicon-envelope"></span> Send Email
                            </button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal"><span
                                class="glyphicon glyphicon-remove-sign"></span>Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- START EMAIL BPOP -->
        <div id="email1pop" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">
                            Email: <?php echo $newClientResponse['title']; ?> <?php echo $newClientResponse['last_name']; ?>
                            <i>(<?php echo $newClientResponse['email']; ?>
                                )</i></h4>
                    </div>
                    <div class="modal-body">

                        <div class="col-md-12">

                            <form class="AddClient" method="post" action="/addon/sendGrid/php/sendEmail.php?EXECUTE=2"
                                  enctype="multipart/form-data">

                                <input type="hidden" name="search" value="<?php echo $search; ?>">
                                <input type="hidden" name="recipient"
                                       value="<?php echo $newClientResponse['title']; ?> <?php echo $newClientResponse['last_name']; ?>">
                                <input type="hidden" name="email" value="<?php echo $newClientResponse['email']; ?>">

                                <div class="form-group">
                                    <label for="message">Email Templates</label>
                                    <select name="message" id="message" class="form-control" required>
                                        <option value="">Select...</option>
                                        <option value="Life insurance quotation">Life insurance quotation</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="insurer">Insurer:</label>
                                    <select class="form-control" name="insurer" id="insurer"
                                            required>
                                        <option value="">Select insurer...</option>
                                        <?php if (isset($royalLondonActive) && $royalLondonActive == 1) { ?>
                                            <option value="Royal London">Royal London</option>
                                            <?php

                                        }

                                        if (isset($lvActive) && $lvActive == 1) { ?>
                                            <option value="LV">LV</option>
                                            <?php

                                        }

                                        if (isset($oneFamilyActive) && $oneFamilyActive == 1) { ?>
                                            <option value="One Family">One Family</option>
                                            <?php

                                        }

                                        if (isset($aegonActive) && $aegonActive == 1) { ?>
                                            <option value="Aegon">Aegon</option>
                                            <?php

                                        }

                                        if (isset($hsbcActive) && $hsbcActive == 1) { ?>
                                            <option value="HSBC">HSBC</option>
                                        <?php } ?>

                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="attachment1">Attachment:</label>
                                    <input type="file" name="fileToUpload" id="fileToUpload" class="form-control"
                                           required>
                                </div>

                                <br>
                                <br>
                                <div style="text-align: center;">
                                    <button type="submit" class="btn btn-primary "><span
                                            class="glyphicon glyphicon-envelope"></span> Send Email Template
                                    </button>
                                </div>
                            </form>

                        </div>

                        <form class="AddClient" method="post" action="/addon/sendGrid/php/sendEmail.php?EXECUTE=1"
                              enctype="multipart/form-data" novalidate>

                            <input type="hidden" name="search" value="<?php echo $search; ?>">
                            <input type="hidden" name="recipient"
                                   value="<?php echo $newClientResponse['title']; ?> <?php echo $newClientResponse['last_name']; ?>"
                                   readonly>
                            <input type="hidden" name="email" value="<?php echo $newClientResponse['email']; ?>"
                                   readonly>

                            <p>
                                <label for="subject">Subject</label>
                                <input name="subject" id="subject" placeholder="Subject/Title" type="text"
                                       class="form-control" required/>
                            </p>

                            <p>

                                <textarea name="message" id="message" class="summernote"
                                          placeholder="Message"></textarea><br/>
                                <label for="attachment1">Attachment:</label>
                                <input type="file" name="fileToUpload" id="fileToUpload"><br>
                                <label for="attachment2">Attachment 2:</label>
                                <input type="file" name="fileToUpload2" id="fileToUpload2"><br>
                                <label for="attachment3">Attachment 3:</label>
                                <input type="file" name="fileToUpload3" id="fileToUpload3"><br>
                                <label for="attachment4">Attachment 4:</label>
                                <input type="file" name="fileToUpload4" id="fileToUpload4"><br>
                                <label for="attachment5">Attachment 5:</label>
                                <input type="file" name="fileToUpload5" id="fileToUpload5"><br>
                                <label for="attachment6">Attachment 6:</label>
                                <input type="file" name="fileToUpload6" id="fileToUpload6">
                            </p>
                            <br>
                            <br>
                            <button type="submit" class="btn btn-warning "><span
                                    class="glyphicon glyphicon-envelope"></span> Send Email
                            </button>
                        </form>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal"><span
                                class="glyphicon glyphicon-remove-sign"></span>Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php }

    if ($ffcallbacks == 1) {

        require_once(BASE_URL . '/app/views/callbackModal.php');

    }

    ?>

    <script type="text/javascript" src="/resources/lib/clockpicker-gh-pages/assets/js/jquery.min.js"></script>
    <script type="text/javascript"
            src="/resources/lib/clockpicker-gh-pages/dist/jquery-clockpicker.min.js"></script>
    <script type="text/javascript">
        $('.clockpicker').clockpicker({
            placement: 'top',
            align: 'left',
            donetext: 'Done'
        });
        $('.subTimeclockpicker').clockpicker({
            placement: 'bottom',
            align: 'left',
            donetext: 'Done'
        })
            .find('input').change(function () {
        });

    </script>
    <script type="text/javascript"
            src="/resources/lib/clockpicker-gh-pages/assets/js/highlight.min.js"></script>

    <script>
        document.querySelector('#clientnotessubtab').addEventListener('submit', function (e) {
            const form = this;
            e.preventDefault();
            swal({
                    title: "Submit notes?",
                    text: "Confirm to send notes!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: 'Yes, I am sure!',
                    cancelButtonText: "No, cancel it!",
                    closeOnConfirm: false,
                    closeOnCancel: false
                },
                function (isConfirm) {
                    if (isConfirm) {
                        swal({
                            title: 'Notes submitted!',
                            text: 'Notes saved!',
                            type: 'success'
                        }, function () {
                            form.submit();
                        });
                    } else {
                        swal("Cancelled", "No changes were made", "error");
                    }
                });
        });
    </script>
    <script type="text/javascript" src="/resources/lib/jquery/jquery-3.0.0.min.js"></script>
    <script type="text/javascript" src="/resources/lib/jquery-ui-1.11.4/jquery-ui.min.js"></script>
    <script>
        $(function () {
            $("#callback_date").datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true
            });
        });
        $(function () {
            $("#subDate").datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true
            });
        });
        $(function () {
            $("#payDate").datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true
            });
        });
        $(function () {
            $("#expectedPayDate").datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true
            });
        });
        $(function () {
            $("#rfPayDate").datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true
            });
        });
        $(function () {
            $("#saleDate").datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true
            });
        });
        $(function () {
            $("#ewsDate").datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true
            });
        });
        $(function () {
            $("#cbDate").datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true
            });
        });
        $(function () {
            $("#saleDateEws").datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true
            });
        });
        $(function () {
            $("#padDeadline").datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true
            });
        });
        $(function () {
            $("#padDeadlineEws").datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true
            });
        });
        $(function () {
            $("#cbDate").datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true
            });
        });


        $("#CLICKTOHIDEDEALSHEET").click(function () {
            $("#HIDEDEALSHEET").fadeOut("slow", function () {
            });
        });
        $("#CLICKTOHIDECLOSERKF").click(function () {
            $("#HIDECLOSERKF").fadeOut("slow", function () {
            });
        });
        $("#CLICKTOHIDELGKEY").click(function () {
            $("#HIDELGKEY").fadeOut("slow", function () {
            });
        });
        $("#CLICKTOHIDELGAPP").click(function () {
            $("#HIDELGAPP").fadeOut("slow", function () {
            });
        });
        $("#CLICKTOHIDEDUPEPOL").click(function () {
            $("#HIDEDUPEPOL").fadeOut("slow", function () {
            });
        });
        $("#CLICKTOHIDENEWPOLICY").click(function () {
            $("#HIDENEWPOLICY").fadeOut("slow", function () {
            });
        });
        $("#CLICKTOHIDECLOSER").click(function () {
            $("#HIDECLOSER").fadeOut("slow", function () {
            });
        });
        $("#CLICKTOHIDELEADID").click(function () {
            $("#HIDELEADID").fadeOut("slow", function () {
            });
        });
        $("#CLICKTOHIDELEAD").click(function () {
            $("#HIDELEAD").fadeOut("slow", function () {
            });
        });
        $("#CLICKTOHIDEGLEAD").click(function () {
            $("#HIDEGLEAD").fadeOut("slow", function () {
            });
        });
        $("#CLICKTOHIDEGCLOSER").click(function () {
            $("#HIDEGCLOSER").fadeOut("slow", function () {
            });
        });
        $(document).ready(function () {
            $("#SHOW_ALERTS").hide("fast", function () {
                // Animation complete
            });
        });
        $("#SHOW_ALERTS").click(function () {
            $("#HIDELGAPP,#HIDELEADID,#HIDELGKEY,#HIDECLOSERKF,#HIDEDEALSHEET,#HIDEDUPEPOL,#HIDENEWPOLICY,#HIDELEAD,#HIDECLOSER,#HIDEGLEAD,#HIDEGCLOSER,#SHOW_ALERTS").fadeIn("slow", function () {
                // Animation complete
            });
            $("#HIDE_ALERTS").fadeIn("slow", function () {
                // Animation complete
            });
            $("#SHOW_ALERTS").fadeOut("slow", function () {
                // Animation complete
            });
        });
        $("#HIDE_ALERTS").click(function () {
            $("#HIDELGAPP,#HIDELEADID,#HIDELGKEY,#HIDECLOSERKF,#HIDEDEALSHEET,#HIDEDUPEPOL,#HIDENEWPOLICY,#HIDELEAD,#HIDECLOSER,#HIDEGLEAD,#HIDEGCLOSER,#HIDE_ALERTS").fadeOut("slow", function () {
                // Animation complete
            });
            $("#SHOW_ALERTS").fadeIn("slow", function () {
                // Animation complete
            });
        });
    </script>
    <script src="/resources/templates/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
    <script src="/resources/lib/sweet-alert/sweet-alert.min.js"></script>
    <script type="text/javascript" src="/resources/lib/summernote-master/dist/summernote.js"></script>
    <script src="/resources/lib/js-webshim/minified/polyfiller.js"></script>
    <script>
        webshims.setOptions('forms-ext', {
            replaceUI: 'auto',
            types: 'number'
        });
        webshims.polyfill('forms forms-ext');
    </script>
    <script type="text/javascript">
        $(function () {
            $('.summernote').summernote({
                height: 200
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            if (window.location.href.split('#').length > 1) {
                $tab_to_nav_to = window.location.href.split('#')[1];
                if ($(".nav-pills > li > a[href='#" + $tab_to_nav_to + "']").length) {
                    $(".nav-pills > li > a[href='#" + $tab_to_nav_to + "']")[0].click();
                }
            }
        });
    </script>
    <script>
        $(function () {
            $('#selectopt').change(function () {
                $('.SELECTED_SMS').hide();
                $('#' + $(this).val()).show();
            });
        });
    </script>
    <?php require_once(BASE_URL . '/app/Holidays.php'); ?>
    <?php require_once(BASE_URL . '/app/php/toastr.php'); ?>

    <div class="padLiveResults">

    </div>

    <div class="ewsLiveResults">

    </div>

    <?php if (in_array($hello_name, $Manager_Access)) { ?>

        <div class="closerTrackerLiveResults">

        </div>

    <?php } ?>


    <script>
        function refresh_div() {
            jQuery.ajax({
                url: '/addon/Life/PAD/php/padLiveResults.php',
                type: 'POST',
                success: function (results) {
                    jQuery(".padLiveResults").html(results);
                }
            });
        }

        t = setInterval(refresh_div, 10000);


        function refresh_ews_div() {
            jQuery.ajax({
                url: '/addon/Life/PAD/php/ewsLiveResults.php',
                type: 'POST',
                success: function (results) {
                    jQuery(".ewsLiveResults").html(results);
                }
            });
        }

        t = setInterval(refresh_ews_div, 10000);
    </script>
    <?php if (in_array($hello_name, $Manager_Access)) { ?>
        <script>
            function refresh_div() {
                jQuery.ajax({
                    url: '/addon/Trackers/php/closerTrackersLiveResults.php',
                    type: 'POST',
                    success: function (results) {
                        jQuery(".closerTrackerLiveResults").html(results);
                    }
                });
            }

            t = setInterval(refresh_div, 10000);
        </script>
    <?php } ?>
</body>
</html>
