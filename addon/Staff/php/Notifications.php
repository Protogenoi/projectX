<?php
/*
 * ------------------------------------------------------------------------
 *                               ADL CRM
 * ------------------------------------------------------------------------
 *
 * Copyright © 2017 ADL CRM All rights reserved.
 *
 * Unauthorised copying of this file, via any medium is strictly prohibited.
 * Unauthorised distribution of this file, via any medium is strictly prohibited.
 * Unauthorised modification of this code is strictly prohibited.
 *
 * Proprietary and confidential
 *
 * Written by Michael Owen <michael@adl-crm.uk>, 2017
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
 *
*/

$RETURN = filter_input(INPUT_GET, 'RETURN', FILTER_SANITIZE_SPECIAL_CHARS);

if (isset($RETURN)) {
    if ($RETURN == 'ClientEdit') {

        echo "<div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa fa-pencil fa-lg\"></i> Success:</strong> Client details updated!</div>";

    }
    if ($RETURN == 'ClientAdded') {

        echo "<div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa fa-user-plus fa-lg\"></i> Success:</strong> Client added!</div>";

    }

    if ($RETURN == 'HOLBOOKED') {

        echo "<div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa fa-calendar-check-o fa-lg\"></i> Success:</strong> Holiday Booked!</div>";

    }
    if ($RETURN == 'ALREADYBOOKED') {

        echo "<div class=\"notice notice-warning\" role=\"alert\"><strong><i class=\"fa fa-calendar-times-o fa-lg\"></i> Warning:</strong> Days already allocated!</div>";

    }
    if ($RETURN == 'AgentAdded') {

        echo "<div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa fa-user-plus fa-lg\"></i> Success:</strong> Agent added to RAG!</div>";

    }

    if ($RETURN == 'ASSETADDED') {

        echo "<div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa  fa-check-circle-o fa-lg\"></i> Success:</strong> Asset added to inventory!</div>";

    }

    if ($RETURN == 'ASSETDETAILSADDED') {

        echo "<div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa  fa-check-circle-o fa-lg\"></i> Success:</strong> Asset details added!</div>";

    }

    if ($RETURN == 'RAGUPDATED') {

        echo "<div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa fa-check-circle-o fa-lg\"></i> Success:</strong> RAG updated!</div>";

    }


    if ($RETURN == 'ClientHired') {

        echo "<div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa fa-user-plus fa-lg\"></i> Success:</strong> Employee rehired!</div>";

    }

    if ($RETURN == 'ClientFired') {

        echo "<div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa fa-check-circle-o fa-lg\"></i> Success:</strong> Employee fired!</div>";

    }

    if ($RETURN == 'ClientNote') {

        echo "<div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa fa-check-circle-o fa-lg\"></i> Success:</strong> Employee note added!</div>";

    }

    if ($RETURN == 'AppAdded') {

        echo "<div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa fa-calendar-check-o fa-lg\"></i> Success:</strong> Appointment booked!</div>";

    }
    if ($RETURN == 'AppEdited') {

        echo "<div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa fa-calendar-check-o fa-lg\"></i> Success:</strong> Appointment has been re-booked!</div>";

    }
    if ($RETURN == 'AppStatus') {

        echo "<div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa fa-check-circle-o  fa-lg\"></i> Success:</strong> Appointment status has been updated!</div>";

    }

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

}
