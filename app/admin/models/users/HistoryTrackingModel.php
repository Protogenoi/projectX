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

class HistoryTrackingModal
{

    protected $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getHistoryTracking($TRACKING_USER, $TRACKING_DATE, $TRACKING_DATE_TO)
    {

        $stmt = $this->pdo->prepare("SELECT 
tracking_history_user
    tracking_history_user,
    tracking_history_url,
    INET6_NTOA(tracking_history_ip) AS tracking_history_ip,
    tracking_history_date
FROM
   tracking_history
WHERE
    DATE(tracking_history_date) BETWEEN :DATE AND :DATETO
AND
    tracking_history_user=:USER
    ORDER BY
        tracking_history_date");
        $stmt->bindParam(':USER', $TRACKING_USER, PDO::PARAM_STR);
        $stmt->bindParam(':DATE', $TRACKING_DATE, PDO::PARAM_STR);
        $stmt->bindParam(':DATETO', $TRACKING_DATE_TO, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}

?>
