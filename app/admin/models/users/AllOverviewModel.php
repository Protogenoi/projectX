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

class AllOvervievModal
{

    protected $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllOverviev()
    {

        $stmt = $this->pdo->prepare("SELECT 
    tracking_history_user,
    COUNT(IF(tracking_history_url LIKE '%auditor_menu.php?RETURN=ADDED%',
        1,
        NULL)) AS LG_Audit,
    COUNT(IF(tracking_history_url LIKE '%lead_gen_reports.php?audit=y%',
        1,
        NULL)) AS Lead_Audit,
    COUNT(IF(tracking_history_url LIKE '%Aviva/Menu.php?RETURN=ADDED%',
        1,
        NULL)) AS Aviva_Audit,
     COUNT(IF(tracking_history_url LIKE '%WOL/Menu.php?query=WOL%',
        1,
        NULL)) AS WOL_Audit,       
    COUNT(IF(tracking_history_url LIKE '%ViewClient.php?search=%',
        1,
        NULL)) AS Client_Views,
    COUNT(IF(tracking_history_url LIKE '%AddClient.php',
        1,
        NULL)) AS Add_Client,
     COUNT(IF(tracking_history_url LIKE '%ViewClient.php?clientedited=y%',
        1,
        NULL)) AS Edit_Client, 
     COUNT(IF(tracking_history_url LIKE '%ViewClient.php?policyedited=y%',
        1,
        NULL)) AS Edit_Policy,          
     COUNT(IF(tracking_history_url LIKE '%ViewClient.php?clientnotesadded%',
        1,
        NULL)) AS Client_Notes,             
            COUNT(IF(tracking_history_url LIKE '%ViewClient.php?policyadded=y%',
        1,
        NULL)) AS Add_Policy,     
        COUNT(IF(tracking_history_url LIKE '%ViewClient.php?email%',
        1,
        NULL)) AS Email_Client,   
         COUNT(IF(tracking_history_url LIKE '%ViewClient.php?smssent=y%',
        1,
        NULL)) AS Sent_SMS,          
    COUNT(IF(tracking_history_url LIKE '%ViewPolicy.php?policyid=%',
        1,
        NULL)) AS View_Policy,
    COUNT(IF(tracking_history_url LIKE '%life_upload.php?life=%',
        1,
        NULL)) AS Uploads,
    COUNT(IF(tracking_history_url LIKE '%SearchClients.php',
        1,
        NULL)) AS Advanced_Client_Search,
    COUNT(IF(tracking_history_url LIKE '%Search.php',
        1,
        NULL)) AS Basic_Client_Search,
    COUNT(IF(tracking_history_url LIKE '%SearchPolicies.php',
        1,
        NULL)) AS Advanced_Policy_Search,
    COUNT(IF(tracking_history_url LIKE '%KeyFactsEmail.php?emailsent%',
        1,
        NULL)) AS Keyfacts_Email,    
    COUNT(IF(tracking_history_url LIKE '%LifeDealSheet.php?query=CloserTrackers&%',
        1,
        NULL)) AS Tracker_Added,           
    COUNT(IF(tracking_history_url LIKE '%404.php',
        1,
        NULL)) AS Not_Found,
    COUNT(IF(tracking_history_url LIKE '%Admindash.php%',
        1,
        NULL)) AS Control_Panel,
    COUNT(IF(tracking_history_url LIKE '%deleteclient.php%',
        1,
        NULL)) AS Delete_Client,
    COUNT(IF(tracking_history_url LIKE '%ViewClient.php?DeleteUpload=1%',
        1,
        NULL)) AS Delete_Upload,        
    COUNT(IF(tracking_history_url LIKE '%ViewClient.php?deletedpolicy=y%',
        1,
        NULL)) AS Delete_Policy,        
            COUNT(IF(tracking_history_url LIKE '%Financials.php%',
        1,
        NULL)) AS Financials,
             COUNT(IF(tracking_history_url LIKE '%EWS.php%',
        1,
        NULL)) AS EWS,    
             COUNT(IF(tracking_history_url LIKE '%Staff/ViewEmployee.php%',
        1,
        NULL)) AS View_Staff,            
    COUNT(IF(tracking_history_url LIKE '%Export.php',
        1,
        NULL)) AS Export     
FROM
    tracking_history");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}

?>
