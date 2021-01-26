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

require_once(__DIR__ . '/../includes/ADL_PDO_CON.php');

$TIMELOCK_ACCESS = ["Michael", "Matt", "Nick"];


$allowedIPAccess = [
    '81.145.167.66',
    '80.229.0.67'
];

$anyIPAccess = [
    "Michael",
    "Matt",
    "Paul"
];

$COM_LVL_10_ACCESS = ["Bob Jones"];

$ALL_ACCESS = ['Matt', 'Nick', 'Michael'];

$COMPANY_ENTITY_ID = "2";
$COMPANY_ENTITY = 'Project X';
$COMPANY_ENTITY_LEAD_GENS = "Project X";

$Level_10_Access = ["Michael", "Matt", "Nick"];

$Level_9_Access = [
    "Michael",
    "Matt",
    "Nick"
];

$Level_8_Access = [
    "Michael",
    "Matt",
    "Nick"
];

$Level_4_Access = [
    'Michael',
    "Matt",
    "Nick"
];

$Level_3_Access = [
    "Michael",
    "Matt",
    "Charles",
    "Paul",
    "Nick",
    "Andrew",
    "Leigh"
];

$Level_2_Access = [
    "Michael",
    "Matt",
    "Charles",
    "Paul",
    "Nick",
    "Andrew",
    "Leigh"
];

$Level_1_Access = [
    "Michael",
    "Matt",
    "Charles",
    "Paul",
    "Nick",
    "Andrew",
    "Leigh"
];

$Agent_Access = ["Agent"];
$Closer_Access = ["Bob Jones"];
$Manager_Access = ["Bob Jones"];
$assistantManagerAccess = ['Bob Jones'];
$EWS_SEARCH_ACCESS = ["Bob Jones"];
$QA_Access = ["Bob Jones"];
