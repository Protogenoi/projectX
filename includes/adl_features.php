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

require_once(__DIR__ . '/ADL_PDO_CON.php');

$query = $pdo->prepare("SELECT clientLetters, compliance, ews, financials, trackers, dealsheets, employee, post_code, pba, error, twitter, gmaps, analytics, callbacks, dialler, intemails, clientemails, keyfactsemail, genemail, recemail, sms, calendar, audits, life, home, pension FROM adl_features LIMIT 1");
$query->execute() or die(print_r($query->errorInfo(), true));
$checkFeatures = $query->fetch(PDO::FETCH_ASSOC);

$ffdialler = $checkFeatures['dialler'];
$ffintemails = $checkFeatures['intemails'];
$ffclientemails = $checkFeatures['clientemails'];
$ffkeyfactsemail = $checkFeatures['keyfactsemail'];
$ffgenemail = $checkFeatures['genemail'];
$ffrecemail = $checkFeatures['recemail'];
$ffsms = $checkFeatures['sms'];
$ffcalendar = $checkFeatures['calendar'];
$ffaudits = $checkFeatures['audits'];
$fflife = $checkFeatures['life'];
$ffhome = $checkFeatures['home'];
$ffpensions = $checkFeatures['pension'];
$ffcallbacks = $checkFeatures['callbacks'];
$ffanalytics = $checkFeatures['analytics'];
$ffgmaps = $checkFeatures['gmaps'];
$fftwitter = $checkFeatures['twitter'];
$fferror = $checkFeatures['error'];
$ffpba = $checkFeatures['pba'];
$ffpost_code = $checkFeatures['post_code'];
$ffemployee = $checkFeatures['employee'];
$ffdealsheets = $checkFeatures['dealsheets'];
$fftrackers = $checkFeatures['trackers'];
$ffews = $checkFeatures['ews'];
$fffinancials = $checkFeatures['financials'];
$ffcompliance = $checkFeatures['compliance'];
$ffClientLetters = $checkFeatures['clientLetters'];
