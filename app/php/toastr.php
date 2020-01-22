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
 * Written by michael <michael@adl-crm.uk>, 04/02/19 13:56
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

?>

    <script type="text/javascript" src="/resources/lib/toastr/js/toastr.min.js"></script>

<?php

// Pad notifications

$padResponse = filter_input(INPUT_GET, 'padResponse', FILTER_SANITIZE_SPECIAL_CHARS);

if (isset($padResponse)) {

    $padStatus = filter_input(INPUT_GET, 'padStatus', FILTER_SANITIZE_SPECIAL_CHARS);
    $padMessage = filter_input(INPUT_GET, 'padMessage', FILTER_SANITIZE_SPECIAL_CHARS);

    ?>

    <script>$(function () {
            $(document).ready(function () {
                toastr.<?php if ($padResponse == 1 || $padResponse == 3) {
                    echo "success";
                } else {
                    echo "error";
                } ?>("<?php if ($padResponse == 1 || $padResponse == 3) {
                    echo "Policy status $padStatus";
                } else {
                    echo "Policy not added to Pad";
                } ?>", "<?php if ($padResponse == 1) {
                    echo "Pad Updated";
                } elseif ($padResponse == 3) {
                    echo $padMessage;
                } else {
                    echo "Error!";
                }?>", {
                    "closeButton": false,
                    "debug": false,
                    "newestOnTop": true,
                    "progressBar": true,
                    "positionClass": "toast-top-right",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showDuration": "300",
                    "hideDuration": "1000",
                    "timeOut": "2000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                });
            });
        });</script> <?php

}

$toastrResponse = filter_input(INPUT_GET, 'toastrResponse', FILTER_SANITIZE_SPECIAL_CHARS);
$toastrMessage = filter_input(INPUT_GET, 'toastrMessage', FILTER_SANITIZE_SPECIAL_CHARS);
$toastrTitle = filter_input(INPUT_GET, 'toastrTitle', FILTER_SANITIZE_SPECIAL_CHARS);

if (isset($toastrResponse)) {

    if ($toastrResponse == 1) {
        $progressBar = 'true';
        $timeOut = 2000;
    } else {
        $progressBar = 'false';
        $timeOut = 0;
    }

    ?>

    <script>$(function () {
            $(document).ready(function () {
                toastr.<?php if ($toastrResponse == 1) {
                    echo "success";
                } else {
                    echo "error";
                } ?>("<?php if ($toastrResponse == 1) {
                    echo $toastrMessage;
                } else {
                    echo $toastrTitle;
                } ?>", "<?php if ($toastrResponse == 1) {
                    echo $toastrTitle;
                } else {
                    echo "Error!";
                }?>", {
                    "closeButton": false,
                    "debug": false,
                    "newestOnTop": true,
                    "progressBar": <?php echo $progressBar; ?>,
                    "positionClass": "toast-top-right",
                    "preventDuplicates": true,
                    "onclick": null,
                    "showDuration": "300",
                    "hideDuration": "1000",
                    "timeOut": "<?php echo $timeOut; ?>",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                });
            });
        });</script> <?php


}
