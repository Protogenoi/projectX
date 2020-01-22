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

?>

<?php foreach ($AllOvervievList as $AllOverviev): ?>

    <?php

    $LG_Audit = $AllOverviev['LG_Audit'];
    $Lead_Audit = $AllOverviev['Lead_Audit'];
    $Aviva_Audit = $AllOverviev['Aviva_Audit'];
    $WOL_Audit = $AllOverviev['WOL_Audit'];

    $Client_Views = $AllOverviev['Client_Views'];
    $Add_Client = $AllOverviev['Add_Client'];
    $Edit_Client = $AllOverviev['Edit_Client'];
    $Edit_Policy = $AllOverviev['Edit_Policy'];
    $Client_Notes = $AllOverviev['Client_Notes'];
    $Add_Policy = $AllOverviev['Add_Policy'];

    $Email_Client = $AllOverviev['Email_Client'];
    $Sent_SMS = $AllOverviev['Sent_SMS'];
    $View_Policy = $AllOverviev['View_Policy'];
    $Uploads = $AllOverviev['Uploads'];
    $Advanced_Client_Search = $AllOverviev['Advanced_Client_Search'];

    $Basic_Client_Search = $AllOverviev['Basic_Client_Search'];
    $Advanced_Policy_Search = $AllOverviev['Advanced_Policy_Search'];
    $Keyfacts_Email = $AllOverviev['Keyfacts_Email'];
    $Tracker_Added = $AllOverviev['Tracker_Added'];

    $Not_Found = $AllOverviev['Not_Found'];
    $Control_Panel = $AllOverviev['Control_Panel'];
    $Delete_Client = $AllOverviev['Delete_Client'];

    $Delete_Upload = $AllOverviev['Delete_Upload'];
    $Delete_Policy = $AllOverviev['Delete_Policy'];
    $Financials = $AllOverviev['Financials'];
    $EWS = $AllOverviev['EWS'];
    $Export = $AllOverviev['Export'];


    echo "
        <div class='col-xs-12'>
<div class='row'>
        <div class='col-xs-2'><center><strong>Client Views</strong><br>$Client_Views</center></div>
        <div class='col-xs-2'><center><strong>Client Adds</strong><br> $Add_Client</center></div>
        <div class='col-xs-2'><center><strong>Client Edits</strong><br> $Edit_Client</center></div>
        <div class='col-xs-2'><center><strong>Policy Edits</strong><br> $Edit_Policy</center></div>
        <div class='col-xs-2'><center><strong>Client Notes</strong><br> $Client_Notes</center></div> 
        <div class='col-xs-2'><center><strong>Added Policy</strong><br> $Add_Policy</center></div>
            <div class='col-xs-2'><center><strong>Client Uploads</strong><br> $Uploads</center></div> 
        <div class='col-xs-2'><center><strong>Client Emails</strong><br> $Email_Client</center></div> 
        <div class='col-xs-2'><center><strong>Client SMS</strong><br> $Sent_SMS</center></div> 
        <div class='col-xs-2'><center><strong>View Policy</strong><br> $View_Policy</center></div> 
        <div class='col-xs-2'><center><strong>Advanced Search</strong><br> $Advanced_Client_Search</center></div> 
        <div class='col-xs-2'><center><strong>Basic Search</strong><br> $Basic_Client_Search</center></div>
        <div class='col-xs-2'><center><strong>Policy Search</strong><br> $Advanced_Policy_Search</center></div>
            </div>
        </div>    
          <br><br><br>
<div class='col-xs-12'>
<div class='row'>
        <div class='col-xs-2'><center><strong>LG Audits</strong><br>$LG_Audit</center></div>
        <div class='col-xs-2'><center><strong>Lead Audits</strong><br>$Lead_Audit</center></div>
        <div class='col-xs-2'><center><strong>Aviva Audits</strong><br>$Aviva_Audit</center></div>
        <div class='col-xs-2'><center><strong>WOL Audits</strong><br>$WOL_Audit</center></div>
            </div>
        </div>
        
        <div class='col-xs-12'>
<div class='row'>
        <div class='col-xs-2'><center><strong>Tracker Added</strong><br>$Tracker_Added</center></div>
        <div class='col-xs-2'><center><strong>Keyfacts Email</strong><br>$Keyfacts_Email</center></div>
            </div>
        </div>
        <br><br><br>
<div class='col-xs-12'>
<div class='row'>
        <div class='col-xs-2'><center><strong>Control Panel</strong><br>$Control_Panel</center></div>
        <div class='col-xs-2'><center><strong>EWS</strong><br>$EWS</center></div>
        <div class='col-xs-2'><center><strong>Export</strong><br>$Export</center></div>
        <div class='col-xs-2'><center><strong>Delete Client</strong><br>$Delete_Client</center></div>
        <div class='col-xs-2'><center><strong>Delete Policy</strong><br>$Delete_Policy</center></div>
        <div class='col-xs-2'><center><strong>Delete Upload</strong><br>$Delete_Upload</center></div>
        <div class='col-xs-2'><center><strong>404</strong><br>$Not_Found</center></div>
            </div>
        </div> 
                   ";


    ?>

<?php endforeach ?>
