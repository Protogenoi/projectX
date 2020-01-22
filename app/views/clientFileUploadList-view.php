<?php
/**
 * ------------------------------------------------------------------------
 *                               ADL CRM
 * ------------------------------------------------------------------------
 *
 * Copyright © 2019 ADL CRM All rights reserved.
 *
 * Unauthorised copying of this file, via any medium is strictly prohibited.
 * Unauthorised distribution of this file, via any medium is strictly prohibited.
 * Unauthorised modification of this code is strictly prohibited.
 *
 * Proprietary and confidential
 *
 * Written by michael <michael@adl-crm.uk>, 18/02/19 10:15
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
 *  Twilio - https://github.com/twilio
 *  SendGrid - https://github.com/sendgrid
 */

?>

<div class="list-group">

    <?php

    if (isset($ffClientLetters) && $ffClientLetters == 1 || isset($ffclientemails) && $ffclientemails == 1) { ?>

        <span
            class="label label-primary"><?php echo $newClientResponse['title']; ?> <?php echo $newClientResponse['last_name']; ?>
                                Letters/Emails</span>

    <?php if (isset($ffClientLetters) && $ffClientLetters == 1) { ?>

        <a class="list-group-item"
           href="/addon/Life/Letters/PostPackLetter.php?clientone=1&search=<?php echo $search; ?>"
           target="_blank">
            <i class="far fa-file-pdf" aria-hidden="true"></i> &nbsp; Post Pack Letter</a>

        <a class="list-group-item"
           href="/addon/Life/Letters/TrustLetter.php?clientone=1&search=<?php echo $search; ?>" target="_blank">
            <i class="far fa-file-pdf" aria-hidden="true"></i> &nbsp; Trust Letter</a>

        <a class="list-group-item"
           href="/addon/Life/Letters/FreePostTrustLetter.php?clientone=1&search=<?php echo $search; ?>"
           target="_blank">
            <i class="far fa-file-pdf" aria-hidden="true"></i> &nbsp; Freepost Trust Letter</a>

        <a class="list-group-item"
           href="/addon/Life/Letters/ReinstateLetter.php?clientone=1&search=<?php echo $search; ?>"
           target="_blank">
            <i class="far fa-file-pdf" aria-hidden="true"></i> &nbsp; Reinstate Letter</a>

    <?php }

    if (isset($ffclientemails) & $ffclientemails == 1) { ?>

        <a class="list-group-item confirmation"
           href="/addon/Life/Emails/SendAnyQueriesCallUs.php?search=<?php echo $search; ?>&email=<?php echo $clientonemail; ?>&recipient=<?php echo $newClientResponse['title']; ?> <?php echo $newClientResponse['first_name']; ?> <?php echo $newClientResponse['last_name']; ?>">
            <i class="far fa-envelope" aria-hidden="true"></i> &nbsp; Any Queries Call Us</a>

        <a class="list-group-item confirmation"
           href="/addon/Life/Emails/MyAccountDetailsEmail.php?search=<?php echo $search; ?>&email=<?php echo $clientonemail; ?>&recipient=<?php echo $newClientResponse['title']; ?> <?php echo $newClientResponse['first_name']; ?> <?php echo $newClientResponse['last_name']; ?>">
            <i class="far fa-envelope" aria-hidden="true"></i> &nbsp; My Account Details Email</a>

        <?php

    if (isset($ffkeyfactsemail) && $ffkeyfactsemail == '1') { ?>
        <a class="list-group-item confirmation"
           href="/addon/Life/Emails/SendKeyFacts.php?search=<?php echo $search; ?>&email=<?php echo $clientonemail; ?>&recipient=<?php echo $newClientResponse['title']; ?> <?php echo $newClientResponse['first_name']; ?> <?php echo $newClientResponse['last_name']; ?>">
            <i class="far fa-envelope" aria-hidden="true"></i> &nbsp; Closer Keyfacts Email</a>
    <?php }
    }
    if (!empty($newClientResponse['first_name2'])) { ?>

        <!-- CLIENT TWO -->

        <span
            class="label label-primary"><?php echo $newClientResponse['title2']; ?> <?php echo $newClientResponse['last_name2']; ?>
                                Letters/Emails</span>

        <?php if (isset($ffClientLetters) && $ffClientLetters == 1) { ?>

        <a class="list-group-item"
           href="/addon/Life/Letters/PostPackLetter.php?clienttwo=1&search=<?php echo $search; ?>"
           target="_blank">
            <i class="far fa-file-pdf" aria-hidden="true"></i> &nbsp; Post Pack Letter</a>

        <a class="list-group-item"
           href="/addon/Life/Letters/TrustLetter.php?clienttwo=1&search=<?php echo $search; ?>" target="_blank">
            <i class="far fa-file-pdf" aria-hidden="true"></i> &nbsp; Trust Letter</a>

        <a class="list-group-item"
           href="/addon/Life/Letters/FreePostTrustLetter.php?clienttwo=1&search=<?php echo $search; ?>"
           target="_blank">
            <i class="far fa-file-pdf" aria-hidden="true"></i> &nbsp; Freepost Trust Letter</a>

        <a class="list-group-item"
           href="/addon/Life/Letters/ReinstateLetter.php?clienttwo=1&search=<?php echo $search; ?>"
           target="_blank">
            <i class="far fa-file-pdf" aria-hidden="true"></i> &nbsp; Reinstate Letter</a>

    <?php }

    if (isset($ffclientemails) & $ffclientemails == 1) { ?>

        <a class="list-group-item confirmation"
           href="/addon/Life/Emails/SendAnyQueriesCallUs.php?search=<?php echo $search; ?>&email=<?php
           if (!empty($clienttwomail)) {
               echo $clienttwomail;
           } else {
               echo $clientonemail;
           }
           ?>&recipient=<?php echo $newClientResponse['title2']; ?> <?php echo $newClientResponse['first_name2']; ?> <?php echo $newClientResponse['last_name2']; ?>">
            <i class="far fa-envelope" aria-hidden="true"></i> &nbsp; Any Queries Call Us</a>

        <a class="list-group-item confirmation"
           href="/addon/Life/Emails/MyAccountDetailsEmail.php?search=<?php echo $search; ?>&email=<?php
           if (!empty($clienttwomail)) {
               echo $clienttwomail;
           } else {
               echo $clientonemail;
           }
           ?>&recipient=<?php echo $newClientResponse['title2']; ?> <?php echo $newClientResponse['first_name2']; ?> <?php echo $newClientResponse['last_name2']; ?>"><i
                class="far fa-envelope" aria-hidden="true"></i> &nbsp; My Account Details
            Email</a>
        <?php

    if (isset($ffkeyfactsemail) && $ffkeyfactsemail == 1) { ?>
        <a class="list-group-item confirmation"
           href="/addon/Life/Emails/SendKeyFacts.php?search=<?php echo $search; ?>&email=<?php
           if (!empty($clienttwomail)) {
               echo $clienttwomail;
           } else {
               echo $clientonemail;
           }
           ?>&recipient=<?php echo $newClientResponse['title2']; ?> <?php echo $newClientResponse['first_name2']; ?> <?php echo $newClientResponse['last_name2']; ?>">
            <i class="far fa-envelope" aria-hidden="true"></i> &nbsp; Closer Keyfacts Email</a>
    <?php }
    }

    if (isset($ffClientLetters) && $ffClientLetters == 1) {

        ?>

        <!-- JOINT -->

        <span class="label label-primary">Joint Letters/Emails</span>

        <a class="list-group-item"
           href="/addon/Life/Letters/PostPackLetter.php?joint=1&search=<?php echo $search; ?>" target="_blank">
            <i class="far fa-file-pdf" aria-hidden="true"></i> &nbsp; Joint Post Pack Letter</a>

        <a class="list-group-item"
           href="/addon/Life/Letters/TrustLetter.php?joint=1&search=<?php echo $search; ?>" target="_blank">
            <i class="far fa-file-pdf" aria-hidden="true"></i> &nbsp; Joint Trust Letter</a>

        <a class="list-group-item"
           href="/addon/Life/Letters/FreePostTrustLetter.php?joint=1&search=<?php echo $search; ?>"
           target="_blank">
            <i class="far fa-file-pdf" aria-hidden="true"></i> &nbsp; Joint Freepost Trust
            Letter</a>

        <a class="list-group-item"
           href="/addon/Life/Letters/ReinstateLetter.php?joint=1&search=<?php echo $search; ?>" target="_blank">
            <i class="far fa-file-pdf" aria-hidden="true"></i> &nbsp; Joint Reinstate Letter</a>

    <?php }
    }

    if (isset($ffclientemails) & $ffclientemails == 1) { ?>

        <script type="text/javascript">
            var elems = document.getElementsByClassName('confirmation');
            var confirmIt = function (e) {
                if (!confirm('Are you sure you want to send this email? The email will be immediately sent.'))
                    e.preventDefault();
            };
            for (var i = 0, l = elems.length; i < l; i++) {
                elems[i].addEventListener('click', confirmIt, false);
            }
        </script>

    <?php }
    }

    if (isset($ffaudits) && $ffaudits == 1) {
        if (!empty($closeraudit) || !empty($leadaudit)) {
            ?>

            <span class="label label-primary">Audit Reports</span>

            <?php
        }

        if (isset($HAS_LV_CLOSE_AUDIT) && $HAS_LV_CLOSE_AUDIT == 1) { ?>

            <a class="list-group-item"
               href="/addon/audits/LV/view_call_audit.php?EXECUTE=VIEW&AUDITID=<?php echo $LV_closeraudit; ?>"
               target="_blank"><i class="fa fa-folder-open fa-fw" aria-hidden="true"></i> &nbsp; LV
                Closer Audit</a>

        <?php }
        if (isset($HAS_ZURICH_CLOSE_AUDIT) && $HAS_ZURICH_CLOSE_AUDIT == 1) { ?>

            <a class="list-group-item"
               href="/addon/audits/Zurich/view_call_audit.php?AUDITID=<?php echo $ZURICH_closeraudit; ?>"
               target="_blank"><i class="fa fa-folder-open fa-fw" aria-hidden="true"></i> &nbsp;
                Zurich
                Closer Audit</a>

        <?php }
        if (isset($HAS_LV_LEAD_AUDIT) && $HAS_LV_LEAD_AUDIT == 1 && empty($HAS_NEW_LEAD_AUDIT)) { ?>

            <a class="list-group-item"
               href="/addon/audits/LandG/View.php?EXECUTE=1&AID=<?php echo $LV_leadaudit; ?>"
               target="_blank"><i class="fa fa-folder-open fa-fw" aria-hidden="true"></i> &nbsp; LV
                Lead
                Audit</a>

        <?php }
        if (isset($HAS_NEW_LV_CLOSE_AUDIT) && $HAS_NEW_LV_CLOSE_AUDIT == 1) { ?>

            <a class="list-group-item"
               href="/addon/audits/LV/view_call_audit.php?AUDITID=<?php echo $LV_NEW_closeraudit; ?>"
               target="_blank"><i class="fa fa-folder-open fa-fw" aria-hidden="true"></i> &nbsp; LV
                Closer Audit</a>

        <?php }
        if (isset($HAS_NEW_VIT_CLOSE_AUDIT) && $HAS_NEW_VIT_CLOSE_AUDIT == 1) { ?>

            <a class="list-group-item"
               href="/addon/audits/Vitality/view_call_audit.php?AUDITID=<?php echo $VIT_NEW_closeraudit; ?>"
               target="_blank"><i class="fa fa-folder-open fa-fw" aria-hidden="true"></i> &nbsp;
                Vitality Closer Audit</a>

        <?php }
        if (isset($HAS_NEW_AEG_CLOSE_AUDIT) && $HAS_NEW_AEG_CLOSE_AUDIT == 1) { ?>

            <a class="list-group-item"
               href="/addon/audits/Aegon/view_call_audit.php?AUDITID=<?php echo $AEG_NEW_closeraudit; ?>"
               target="_blank"><i class="fa fa-folder-open fa-fw" aria-hidden="true"></i> &nbsp;
                Aegon
                Closer Audit</a>

        <?php }
        if (isset($HAS_NEW_RL_CLOSE_AUDIT) && $HAS_NEW_RL_CLOSE_AUDIT == 1) { ?>

            <a class="list-group-item"
               href="/addon/audits/RoyalLondon/view_call_audit.php?AUDITID=<?php echo $RL_NEW_closeraudit; ?>"
               target="_blank"><i class="fa fa-folder-open fa-fw" aria-hidden="true"></i> &nbsp;
                Royal
                London Closer Audit</a>

        <?php }
        if (isset($HAS_AVI_CLOSE_AUDIT) && $HAS_AVI_CLOSE_AUDIT == 1) { ?>

            <a class="list-group-item"
               href="/addon/audits/Aviva/View.php?EXECUTE=VIEW&AUDITID=<?php echo $AVI_closeraudit; ?>"
               target="_blank"><i class="fa fa-folder-open fa-fw" aria-hidden="true"></i> &nbsp;
                Aviva
                Closer Audit</a>

        <?php }
        if (isset($HAS_VIT_CLOSE_AUDIT) && $HAS_VIT_CLOSE_AUDIT == 1) { ?>

            <a class="list-group-item"
               href="/addon/audits/Vitality/View.php?EXECUTE=VIEW&AUDITID=<?php echo $VIT_closeraudit; ?>"
               target="_blank"><i class="fa fa-folder-open fa-fw" aria-hidden="true"></i> &nbsp; Old
                Vitality Closer Audit</a>

        <?php }
        if (isset($HAS_NEW_LEAD_AUDIT) && $HAS_NEW_LEAD_AUDIT == 1) { ?>

            <a class="list-group-item"
               href="/addon/audits/Agent/view_call_audit.php?EXECUTE=1&AUDITID=<?php echo $NEW_LEAD_AUDIT_ID; ?>"
               target="_blank"><i class="fa fa-folder-open fa-fw" aria-hidden="true"></i> &nbsp;
                Lead
                Audit</a>

        <?php } elseif (isset($HAS_VIT_LEAD_AUDIT) && $HAS_VIT_LEAD_AUDIT == 1) { ?>

            <a class="list-group-item"
               href="/addon/audits/LandG/View.php?EXECUTE=1&AID=<?php echo $VIT_leadaudit; ?>"
               target="_blank"><i class="fa fa-folder-open fa-fw" aria-hidden="true"></i> &nbsp;
                Vitality Lead Audit</a>

        <?php }
        if (isset($HAS_RL_CLOSE_AUDIT) && $HAS_RL_CLOSE_AUDIT == 1) { ?>

            <a class="list-group-item"
               href="/addon/audits/RoyalLondon/View.php?EXECUTE=VIEW&AUDITID=<?php echo $RL_closeraudit; ?>"
               target="_blank"><i class="fa fa-folder-open fa-fw" aria-hidden="true"></i> &nbsp;
                Royal
                London Closer Audit</a>

        <?php } elseif (isset($HAS_RL_LEAD_AUDIT) && $HAS_RL_LEAD_AUDIT == 1 && empty($HAS_NEW_LEAD_AUDIT)) { ?>

            <a class="list-group-item"
               href="/addon/audits/LandG/View.php?EXECUTE=1&AID=<?php echo $RL_leadaudit; ?>"
               target="_blank"><i class="fa fa-folder-open fa-fw" aria-hidden="true"></i> &nbsp;
                Royal
                London Lead Audit (OLD)</a>

        <?php }
        if (isset($HAS_WOL_CLOSE_AUDIT) && $HAS_WOL_CLOSE_AUDIT == 1) { ?>

            <a class="list-group-item"
               href="/addon/audits/WOL/View.php?query=View&WOLID=<?php echo $WOL_closeraudit; ?>"
               target="_blank"><i class="fa fa-folder-open fa-fw" aria-hidden="true"></i> &nbsp; One
                Family Closer Audit</a>

        <?php }
        if (isset($HAS_WOL_LEAD_AUDIT) && $HAS_WOL_LEAD_AUDIT == 1 && empty($HAS_NEW_LEAD_AUDIT)) { ?>

            <a class="list-group-item"
               href="/addon/audits/LandG/View.php?EXECUTE=1&AID=<?php echo $WOL_leadaudit; ?>"
               target="_blank"><i class="fa fa-folder-open fa-fw" aria-hidden="true"></i> &nbsp; One
                Family Lead Audit</a>

        <?php }
        if (isset($HAS_AVI_POL) && $HAS_AVI_POL == 1) {
            $AVIVA_AUDIT_QRY = $pdo->prepare("SELECT 
    aviva_audit_id
FROM
    aviva_audit
WHERE
    aviva_audit_policy = (SELECT 
            application_number
        FROM
            client_policy
        WHERE
            client_id = :CID
                AND insurer = 'Aviva'
        LIMIT 1) GROUP BY aviva_audit_id");
            $AVIVA_AUDIT_QRY->bindParam(':CID', $search, PDO::PARAM_INT);
            $AVIVA_AUDIT_QRY->execute();
            $AVIVA_AUDIT_ROW = $AVIVA_AUDIT_QRY->fetch(PDO::FETCH_ASSOC);
            if ($AVIVA_AUDIT_QRY->rowCount() > 0) {
                $AVIVA_AUDIT_ID = $AVIVA_AUDIT_ROW['aviva_audit_id']; ?>

                <a class="list-group-item"
                   href="/audits/Aviva/View.php?EXECUTE=VIEW&AUDITID=<?php echo $AVIVA_AUDIT_ID; ?>"
                   target="_blank"><i class="fa fa-folder-open fa-fw" aria-hidden="true"></i> &nbsp;
                    Aviva Closer Audit</a>
            <?php }
        }
    }
    $queryup = $pdo->prepare("SELECT file, uploadtype FROM tbl_uploads WHERE file like :file");
    $queryup->bindParam(':file', $likesearch, PDO::PARAM_INT);
    $queryup->execute();
    if ($queryup->rowCount() > 0) {
        ?>

        <span class="label label-primary">Uploads</span>

        <?php
        while ($row = $queryup->fetch(PDO::FETCH_ASSOC)) {

            $file = $row['file'];
            $uploadtype = $row['uploadtype'];

            switch ($uploadtype):
                case "RLpolicy":
                    $uploadtype = "Royal London Policy";
                    $typeimage = "fa-file-pdf";
                    break;
                case "RLkeyfacts":
                    $uploadtype = "Royal London Keyfacts";
                    $typeimage = "fa-file-pdf";
                    break;
                case "HSBCpolicy":
                    $uploadtype = "HSBC Policy";
                    $typeimage = "fa-file-pdf";
                    break;
                case "HSBCkeyfacts":
                    $uploadtype = "HSBC Keyfacts";
                    $typeimage = "fa-file-pdf";
                    break;
                case "LGPolicy Summary";
                    $uploadtype = "L&G Policy Summary";
                    $typeimage = "fa-file-pdf";
                    break;
                case "Avivakeyfacts":
                    $uploadtype = "Aviva Keyfacts";
                    $typeimage = "fa-file-pdf";
                    break;
                case "Avivapolicy":
                    $uploadtype = "Aviva Policy";
                    $typeimage = "fa-file-pdf";
                    break;
                case "LVkeyfacts":
                    $uploadtype = "LV Keyfacts";
                    $typeimage = "fa-file-pdf";
                    break;
                case "LVpolicy":
                    $uploadtype = "LV Policy";
                    $typeimage = "fa-file-pdf";
                    break;
                case "Vitalitykeyfacts":
                    $uploadtype = "Vitality Keyfacts";
                    $typeimage = "fa-file-pdf";
                    break;
                case "NFkeyfacts":
                    $uploadtype = "National Friendly Keyfacts";
                    $typeimage = "fa-file-pdf";
                    break;
                case "NFpolicy":
                    $uploadtype = "National Friendly Policy";
                    $typeimage = "fa-file-pdf";
                    break;
                case "Vitalitypolicy":
                    $uploadtype = "Vitality Policy";
                    $typeimage = "fa-file-pdf";
                    break;
                case "WOLkeyfacts":
                    $uploadtype = "One Family Keyfacts";
                    $typeimage = "fa-file-pdf";
                    break;
                case "WOLpolicy":
                    $uploadtype = "One Family Policy";
                    $typeimage = "fa-file-pdf";
                    break;
                case "SWkeyfacts":
                    $uploadtype = "SW Keyfacts";
                    $typeimage = "fa-file-pdf";
                    break;
                case "SWpolicy":
                    $uploadtype = "SW Policy";
                    $typeimage = "fa-file-pdf";
                    break;
                case "Zurichkeyfacts":
                    $uploadtype = "Zurich Keyfacts";
                    $typeimage = "fa-file-pdf";
                    break;
                case "Zurichpolicy":
                    $uploadtype = "Zurich Policy";
                    $typeimage = "fa-file-pdf";
                    break;
                case "LGkeyfacts":
                    $uploadtype = "L&G Keyfacts";
                    $typeimage = "fa-file-pdf";
                    break;
                case "LGpolicy":
                    $uploadtype = "L&G APP";
                    $typeimage = "fa-file-pdf";
                    break;
                case "lifenotes":
                    $uploadtype = "Notes";
                    $typeimage = "fa-file-text";
                    break;
                case "LifeCloserAudit":
                    $uploadtype = "Closer Audit";
                    $typeimage = "fa-folder-open";
                    break;
                case "LifeLeadAudit":
                    $uploadtype = "Life Audit";
                    $typeimage = "fa-folder-open";
                    break;
                case "Happy Call":
                case "Recording":
                case "Closer Call Recording":
                case "Closer and Agent Call Recording":
                case "Agent Call Recording":
                case "Admin Call Recording":
                    $typeimage = "fa-headphones";
                    break;
                case "Other":
                case "Old Other":
                    $typeimage = "fa-folder-open";
                    break;
                case "Dealsheet":
                    $typeimage = "fa-file-pdf";
                    break;
                default:
                    $typeimage = $uploadtype;
            endswitch;

            if ($row['uploadtype'] == 'Avivapolicy' || $row['uploadtype'] == 'Avivakeyfacts') {
                if (file_exists(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT',
                        FILTER_SANITIZE_SPECIAL_CHARS) . "/uploads/$file")) {
                    ?>
                    <a class="list-group-item" href="/uploads/<?php echo $file; ?>" target="_blank"><i
                            class="fa <?php echo $typeimage; ?> fa-fw" aria-hidden="true"></i>
                        &nbsp; <?php echo "$uploadtype | $file"; ?></a>
                <?php } else { ?>
                    <a class="list-group-item"
                       href="/uploads/life/<?php echo $search; ?>/<?php echo $file; ?>"
                       target="_blank"><i
                            class="fa <?php echo $typeimage; ?> fa-fw" aria-hidden="true"></i>
                        &nbsp; <?php echo "$uploadtype | $file"; ?></a>
                    <?php
                }
            }

            if ($row['uploadtype'] == 'LVpolicy' || $row['uploadtype'] == 'LVkeyfacts') {
                if (file_exists(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT',
                        FILTER_SANITIZE_SPECIAL_CHARS) . "/uploads/life/$search/$file")) {
                    ?>
                    <a class="list-group-item"
                       href="/uploads/life/<?php echo $search; ?>/<?php echo $file; ?>"
                       target="_blank"><i
                            class="fa <?php echo $typeimage; ?> fa-fw" aria-hidden="true"></i>
                        &nbsp; <?php echo "$uploadtype | $file"; ?></a>
                    <?php
                }
            }

            if ($row['uploadtype'] == 'HSBCpolicy' || $row['uploadtype'] == 'HSBCkeyfacts') {
                if (file_exists(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT',
                        FILTER_SANITIZE_SPECIAL_CHARS) . "/uploads/life/$search/$file")) {
                    ?>
                    <a class="list-group-item"
                       href="/uploads/life/<?php echo $search; ?>/<?php echo $file; ?>"
                       target="_blank"><i
                            class="fa <?php echo $typeimage; ?> fa-fw" aria-hidden="true"></i>
                        &nbsp; <?php echo "$uploadtype | $file"; ?></a>
                    <?php
                }
            }

            if ($row['uploadtype'] == 'RLpolicy' || $row['uploadtype'] == 'RLkeyfacts') {
                if (file_exists(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT',
                        FILTER_SANITIZE_SPECIAL_CHARS) . "/uploads/life/$search/$file")) {
                    ?>
                    <a class="list-group-item"
                       href="/uploads/life/<?php echo $search; ?>/<?php echo $file; ?>"
                       target="_blank"><i
                            class="fa <?php echo $typeimage; ?> fa-fw" aria-hidden="true"></i>
                        <?php echo "$uploadtype | $file"; ?></a>
                    <?php
                }
            }

            if ($row['uploadtype'] == 'Vitalitypolicy' || $row['uploadtype'] == 'Vitalitykeyfacts') {
                if (file_exists(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT',
                        FILTER_SANITIZE_SPECIAL_CHARS) . "/uploads/$file")) {
                    ?>
                    <a class="list-group-item" href="/uploads/<?php echo $file; ?>" target="_blank"><i
                            class="fa <?php echo $typeimage; ?> fa-fw" aria-hidden="true"></i>
                        &nbsp; <?php echo "$uploadtype | $file"; ?></a>
                <?php } else { ?>
                    <a class="list-group-item"
                       href="/uploads/life/<?php echo $search; ?>/<?php echo $file; ?>"
                       target="_blank"><i
                            class="fa <?php echo $typeimage; ?> fa-fw" aria-hidden="true"></i>
                        &nbsp; <?php echo "$uploadtype | $file"; ?></a>
                    <?php
                }
            }
            if ($row['uploadtype'] == 'WOLpolicy' || $row['uploadtype'] == 'WOLkeyfacts') {
                if (file_exists(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT',
                        FILTER_SANITIZE_SPECIAL_CHARS) . "/uploads/$file")) {
                    ?>
                    <a class="list-group-item" href="/uploads/<?php echo $file; ?>" target="_blank"><i
                            class="fa <?php echo $typeimage; ?> fa-fw" aria-hidden="true"></i>
                        &nbsp; <?php echo "$uploadtype | $file"; ?></a>
                <?php } else { ?>
                    <a class="list-group-item"
                       href="/uploads/life/<?php echo $search; ?>/<?php echo $file; ?>"
                       target="_blank"><i
                            class="fa <?php echo $typeimage; ?> fa-fw" aria-hidden="true"></i>
                        &nbsp; <?php echo "$uploadtype | $file"; ?></a>
                    <?php
                }
            }

            if ($row['uploadtype'] == 'NFpolicy' || $row['uploadtype'] == 'NFkeyfacts') {
                if (file_exists(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT',
                        FILTER_SANITIZE_SPECIAL_CHARS) . "/uploads/life/$search/$file")) {
                    ?>
                    <a class="list-group-item"
                       href="/uploads/life/<?php echo $search . "/" . $file; ?>"
                       target="_blank"><i
                            class="fa <?php echo $typeimage; ?> fa-fw" aria-hidden="true"></i>
                        &nbsp; <?php echo "$uploadtype | $file"; ?></a>
                <?php }
            }

            if ($row['uploadtype'] == 'Zurichpolicy' || $row['uploadtype'] == 'Zurichkeyfacts') {
                if (file_exists(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT',
                        FILTER_SANITIZE_SPECIAL_CHARS) . "/uploads/$file")) {
                    ?>
                    <a class="list-group-item" href="/uploads/<?php echo $file; ?>" target="_blank"><i
                            class="fa <?php echo $typeimage; ?> fa-fw" aria-hidden="true"></i>
                        &nbsp; <?php echo "$uploadtype | $file"; ?></a>
                <?php } else { ?>
                    <a class="list-group-item"
                       href="/uploads/life/<?php echo $search; ?>/<?php echo $file; ?>"
                       target="_blank"><i
                            class="fa <?php echo $typeimage; ?> fa-fw" aria-hidden="true"></i>
                        &nbsp; <?php echo "$uploadtype | $file"; ?></a>
                    <?php
                }
            }
            if ($row['uploadtype'] == 'SWpolicy' || $row['uploadtype'] == 'SWkeyfacts') {
                if (file_exists(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT',
                        FILTER_SANITIZE_SPECIAL_CHARS) . "/uploads/$file")) {
                    ?>
                    <a class="list-group-item" href="/uploads/<?php echo $file; ?>" target="_blank"><i
                            class="fa <?php echo $typeimage; ?> fa-fw" aria-hidden="true"></i>
                        &nbsp; <?php echo "$uploadtype | $file"; ?></a>
                <?php } else { ?>
                    <a class="list-group-item"
                       href="/uploads/life/<?php echo $search; ?>/<?php echo $file; ?>"
                       target="_blank"><i
                            class="fa <?php echo $typeimage; ?> fa-fw" aria-hidden="true"></i>
                        &nbsp; <?php echo "$uploadtype | $file"; ?></a>
                    <?php
                }
            }
            if ($row['uploadtype'] == 'Other') {
                ?>
                <a class="list-group-item"
                   href="/uploads/life/<?php echo $search; ?>/<?php echo $file; ?>" target="_blank"><i
                        class="fa <?php echo $typeimage; ?> fa-fw" aria-hidden="true"></i>
                    &nbsp; <?php echo "$uploadtype | $file"; ?></a>
                <?php
            }
            if ($row['uploadtype'] == 'Old Other') {
                ?>
                <a class="list-group-item" href="/uploads/<?php echo $file; ?>" target="_blank"><i
                        class="fa <?php echo $typeimage; ?> fa-fw" aria-hidden="true"></i>
                    &nbsp; <?php echo "$uploadtype | $file"; ?></a>
                <?php
            }
            if ($row['uploadtype'] == 'RECORDING' || $row['uploadtype'] == 'Closer Call Recording' || $row['uploadtype'] == 'Agent Call Recording' || $row['uploadtype'] == 'Admin Call Recording' || $row['uploadtype'] == 'Closer and Agent Call Recording') {
                if (file_exists(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT',
                        FILTER_SANITIZE_SPECIAL_CHARS) . "/uploads/$file")) { ?>
                    <a class="list-group-item" href="/uploads/<?php echo $file; ?>" target="_blank"><i
                            class="fa <?php echo $typeimage; ?> fa-fw" aria-hidden="true"></i>
                        &nbsp; <?php echo "$uploadtype | $file"; ?></a>
                <?php } else { ?>
                    <a class="list-group-item">
                        <figure>
                            <figcaption><i class="fa <?php echo $typeimage; ?> fa-fw"
                                           aria-hidden="true"></i> <?php echo "$uploadtype | $file"; ?>
                            </figcaption>
                            <br>
                            <audio
                                controls
                                src="/uploads/life/<?php echo $search; ?>/<?php echo $file; ?>">
                                Your browser does not support the
                                <code>audio</code> element.
                            </audio>
                        </figure>
                    </a>
                    <?php
                }
            }
            if ($row['uploadtype'] == 'lifenotes') {
                if (file_exists(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT',
                        FILTER_SANITIZE_SPECIAL_CHARS) . "/uploads/$file")) {
                    ?>
                    <a class="list-group-item" href="/uploads/<?php echo $file; ?>" target="_blank"><i
                            class="fa <?php echo $typeimage; ?> fa-fw" aria-hidden="true"></i>
                        &nbsp; <?php echo "$uploadtype | $file"; ?></a>
                <?php } else { ?>
                    <a class="list-group-item"
                       href="/uploads/life/<?php echo $search; ?>/<?php echo $file; ?>"
                       target="_blank"><i
                            class="fa <?php echo $typeimage; ?> fa-fw" aria-hidden="true"></i>
                        &nbsp; <?php echo "$uploadtype | $file"; ?></a>
                    <?php
                }
            }
            if ($row['uploadtype'] == 'Dealsheet') {
                if (file_exists(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT',
                        FILTER_SANITIZE_SPECIAL_CHARS) . "/uploads/$file")) {
                    ?>
                    <a class="list-group-item" href="/uploads/<?php echo $file; ?>" target="_blank"><i
                            class="fa <?php echo $typeimage; ?> fa-fw" aria-hidden="true"></i>
                        &nbsp; <?php echo "$uploadtype | $file"; ?></a>
                <?php } else { ?>
                    <a class="list-group-item"
                       href="/uploads/life/<?php echo $search; ?>/<?php echo $file; ?>"
                       target="_blank"><i
                            class="fa <?php echo $typeimage; ?> fa-fw" aria-hidden="true"></i>
                        &nbsp; <?php echo "$uploadtype | $file"; ?></a>
                    <?php
                }
            }
            if ($row['uploadtype'] == 'LGkeyfacts') {
                if (file_exists(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT',
                        FILTER_SANITIZE_SPECIAL_CHARS) . "/uploads/$file")) {
                    ?>
                    <a class="list-group-item" href="/uploads/<?php echo $file; ?>" target="_blank"><i
                            class="fa <?php echo $typeimage; ?> fa-fw" aria-hidden="true"></i>
                        &nbsp; <?php echo "$uploadtype | $file"; ?></a>
                <?php } else { ?>
                    <a class="list-group-item"
                       href="/uploads/life/<?php echo $search; ?>/<?php echo $file; ?>"
                       target="_blank"><i
                            class="fa <?php echo $typeimage; ?> fa-fw" aria-hidden="true"></i>
                        &nbsp; <?php echo "$uploadtype | $file"; ?></a>
                    <?php
                }
            }
            if ($row['uploadtype'] == 'LGPolicy Summary') {
                if (file_exists(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT',
                        FILTER_SANITIZE_SPECIAL_CHARS) . "/uploads/$file")) {
                    ?>
                    <a class="list-group-item" href="/uploads/<?php echo $file; ?>" target="_blank"><i
                            class="fa <?php echo $typeimage; ?> fa-fw" aria-hidden="true"></i>
                        &nbsp; <?php echo "$uploadtype | $file"; ?></a>
                <?php } else { ?>
                    <a class="list-group-item"
                       href="/uploads/life/<?php echo $search; ?>/<?php echo $file; ?>"
                       target="_blank"><i
                            class="fa <?php echo $typeimage; ?> fa-fw" aria-hidden="true"></i>
                        &nbsp; <?php echo "$uploadtype | $file"; ?></a>
                    <?php
                }
            }
            if ($row['uploadtype'] == 'LGpolicy') {
                if (!file_exists(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT',
                        FILTER_SANITIZE_SPECIAL_CHARS) . "/uploads/$file")) {
                    ?>
                    <a class="list-group-item"
                       href="/uploads/life/<?php echo $search; ?>/<?php echo $file; ?>"
                       target="_blank"><i
                            class="fa <?php echo $typeimage; ?> fa-fw" aria-hidden="true"></i>
                        &nbsp; <?php echo "$uploadtype | $file"; ?></a>
                <?php } else { ?>
                    <a class="list-group-item" href="/uploads/<?php echo $file; ?>" target="_blank"><i
                            class="fa <?php echo $typeimage; ?> fa-fw" aria-hidden="true"></i>
                        &nbsp; <?php echo "$uploadtype | $file"; ?></a>
                    <?php
                }
            }
            if ($row['uploadtype'] == 'L&G APP') {
                if (!file_exists(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT',
                        FILTER_SANITIZE_SPECIAL_CHARS) . "/uploads/$file")) {
                    ?>
                    <a class="list-group-item"
                       href="/uploads/life/<?php echo $search; ?>/<?php echo $file; ?>"
                       target="_blank"><i
                            class="fa <?php echo $typeimage; ?> fa-fw" aria-hidden="true"></i>
                        &nbsp; <?php echo "$uploadtype | $file"; ?></a>
                <?php } else { ?>
                    <a class="list-group-item" href="/uploads/<?php echo $file; ?>" target="_blank"><i
                            class="fa <?php echo $typeimage; ?> fa-fw" aria-hidden="true"></i>
                        &nbsp; <?php echo "$uploadtype | $file"; ?></a>
                    <?php
                }
            }
            if ($row['uploadtype'] == 'LifeCloserAudit') {
                if (file_exists(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT',
                        FILTER_SANITIZE_SPECIAL_CHARS) . "/uploads/$file")) {
                    ?>
                    <a class="list-group-item" href="/uploads/<?php echo $file; ?>" target="_blank"><i
                            class="fa <?php echo $typeimage; ?> fa-fw" aria-hidden="true"></i>
                        &nbsp; <?php echo "$uploadtype | $file"; ?></a>
                <?php } else { ?>
                    <a class="list-group-item"
                       href="/uploads/life/<?php echo $search; ?>/<?php echo $file; ?>"
                       target="_blank"><i
                            class="fa <?php echo $typeimage; ?> fa-fw" aria-hidden="true"></i>
                        &nbsp; <?php echo "$uploadtype | $file"; ?></a>
                    <?php
                }
            }
            if ($row['uploadtype'] == 'LifeLeadAudit') {
                if (file_exists(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT',
                        FILTER_SANITIZE_SPECIAL_CHARS) . "/uploads/$file")) {
                    ?>
                    <a class="list-group-item" href="/uploads/<?php echo $file; ?>" target="_blank"><i
                            class="fa <?php echo $typeimage; ?> fa-fw" aria-hidden="true"></i>
                        &nbsp; <?php echo "$uploadtype | $file"; ?></a>
                <?php } else { ?>
                    <a class="list-group-item"
                       href="/uploads/life/<?php echo $search; ?>/<?php echo $file; ?>"
                       target="_blank"><i
                            class="fa <?php echo $typeimage; ?> fa-fw" aria-hidden="true"></i>
                        &nbsp; <?php echo "$uploadtype | $file"; ?></a>
                    <?php
                }
            }
            if ($row['uploadtype'] == 'Recording') {
                if (file_exists(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT',
                        FILTER_SANITIZE_SPECIAL_CHARS) . "/uploads/$file")) {
                    ?>
                    <a class="list-group-item" href="/uploads/<?php echo $file; ?>" target="_blank"><i
                            class="fa <?php echo $typeimage; ?> fa-fw" aria-hidden="true"></i>
                        &nbsp; <?php echo "$uploadtype | $file"; ?></a>
                <?php } else { ?>
                    <a class="list-group-item"
                       href="/uploads/life/<?php echo $search; ?>/<?php echo $file; ?>"
                       target="_blank"><i
                            class="fa <?php echo $typeimage; ?> fa-fw" aria-hidden="true"></i>
                        &nbsp; <?php echo "$uploadtype | $file"; ?></a>
                    <?php
                }
            }
            if ($row['uploadtype'] == 'Happy Call') {
                if (file_exists(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT',
                        FILTER_SANITIZE_SPECIAL_CHARS) . "/uploads/$file")) {
                    ?>
                    <a class="list-group-item" href="/uploads/<?php echo $file; ?>" target="_blank"><i
                            class="fa <?php echo $typeimage; ?> fa-fw" aria-hidden="true"></i>
                        &nbsp; <?php echo "$uploadtype | $file"; ?></a>
                <?php } else { ?>
                    <a class="list-group-item"
                       href="/uploads/life/<?php echo $search; ?>/<?php echo $file; ?>"
                       target="_blank"><i
                            class="fa <?php echo $typeimage; ?> fa-fw" aria-hidden="true"></i>
                        &nbsp; <?php echo "$uploadtype | $file"; ?></a>
                    <?php
                }
            }
        }
    }
    ?>
</div>
