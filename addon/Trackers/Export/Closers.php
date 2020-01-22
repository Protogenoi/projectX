<?php
/*
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

require_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_SPECIAL_CHARS) . '/app/core/doc_root.php';

require_once(BASE_URL . '/classes/access_user/access_user_class.php');
$page_protect = new Access_user;
$page_protect->access_page(filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS), "", 1);
$hello_name = ($page_protect->user_full_name != "") ? $page_protect->user_full_name : $page_protect->user;

require_once(BASE_URL . '/includes/adl_features.php');
require_once(BASE_URL . '/includes/Access_Levels.php');
require_once(BASE_URL . '/includes/ADL_PDO_CON.php');

$EXECUTE = filter_input(INPUT_GET, 'EXECUTE', FILTER_SANITIZE_SPECIAL_CHARS);

if (isset($EXECUTE)) {

    $dateFrom = filter_input(INPUT_GET, 'dateFrom', FILTER_SANITIZE_SPECIAL_CHARS);
    $dateTo = filter_input(INPUT_GET, 'dateTo', FILTER_SANITIZE_SPECIAL_CHARS);

    $file = "TRACKER";
    $filename = $file . "_" . date("Y-m-d_H-i", time());
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $filename . '.csv');

    if ($EXECUTE == 1) {
        if (isset($dateFrom)) {

            $output = "Date, closer, agent, client, phone, current_premium, our_premium, sale, comments\n";
            $query = $pdo->prepare("SELECT 
    date_updated,
    closer,
    agent,
    client,
    phone,
    current_premium,
    our_premium,
    comments,
    sale,
    insurer
FROM
    closer_trackers
WHERE
    DATE(date_added) BETWEEN :dateFrom AND :dateTo
ORDER BY date_added DESC");
            $query->bindParam(':dateFrom', $dateFrom, PDO::PARAM_STR);
            $query->bindParam(':dateTo', $dateTo, PDO::PARAM_STR);

        } else {

            $output = "Date, closer, agent, client, phone, current_premium, our_premium, sale, comments, insurer\n";
            $query = $pdo->prepare("SELECT 
    date_updated,
    closer,
    agent,
    client,
    phone,
    current_premium,
    our_premium,
    comments,
    insurer,
    sale
FROM
    closer_trackers
WHERE
    DATE(date_added) >= CURDATE()
ORDER BY date_added DESC");

        }
        $query->execute();
        $list = $query->fetchAll();
        foreach ($list as $rs) {

            $dateFrom = filter_var($rs['date_updated'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $closer = filter_var($rs['closer'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $agent = filter_var($rs['agent'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $client = filter_var($rs['client'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $phone = filter_var($rs['phone'], FILTER_SANITIZE_NUMBER_INT);
            $current_premium = $rs['current_premium'];
            $our_premium = $rs['our_premium'];
            $comments = filter_var($rs['comments'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $comments = trim(preg_replace('/ +/', ' ',
                preg_replace('/[^A-Za-z0-9 ]/', ' ', urldecode(html_entity_decode(strip_tags($comments))))));
            $INSURER = filter_var($rs['insurer'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $sale = filter_var($rs['sale'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $output .= $dateFrom . "," . $closer . "," . $agent . "," . $client . "," . $phone . "," . $current_premium . "," . $our_premium . "," . $sale . "," . $comments . "," . $INSURER . "\n";

        }
        echo $output;
        exit;

    }

    if ($EXECUTE == 2) {

        $output = "Date, closer, client, sale, comments\n";
        $query = $pdo->prepare("SELECT 
    date_updated,
    closer,
       client,
    comments,
    sale
FROM
    closer_trackers
WHERE
    DATE(date_added) BETWEEN :dateFrom AND :dateTo
ORDER BY closer , sale");
        $query->bindParam(':dateFrom', $dateFrom, PDO::PARAM_STR);
        $query->bindParam(':dateTo', $dateTo, PDO::PARAM_STR);
        $query->execute();
        $list = $query->fetchAll();

        foreach ($list as $rs) {

            $dateFrom = filter_var($rs['date_updated'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $closer = filter_var($rs['closer'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $comments = filter_var($rs['comments'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $comments = trim(preg_replace('/ +/', ' ',
                preg_replace('/[^A-Za-z0-9 ]/', ' ', urldecode(html_entity_decode(strip_tags($comments))))));
            $sale = filter_var($rs['sale'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $output .= $dateFrom . "," . $closer . "," . $rs['client'] . "," . $sale . "," . $comments . "\n";

        }
        echo $output;
        exit;

    }

    if ($EXECUTE == 3) {

        $output = "Date, closer, agent, sale, comments\n";
        $query = $pdo->prepare("SELECT 
    date_updated,
    closer,
    agent,
    comments,
    sale
FROM
    closer_trackers
WHERE
    DATE(date_added) >= CURDATE()
ORDER BY date_added DESC");
        $query->execute();
        $list = $query->fetchAll();
        foreach ($list as $rs) {

            $dateFrom = filter_var($rs['date_updated'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $closer = filter_var($rs['closer'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $agent = filter_var($rs['agent'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $comments = filter_var($rs['comments'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $comments = trim(preg_replace('/ +/', ' ',
                preg_replace('/[^A-Za-z0-9 ]/', ' ', urldecode(html_entity_decode(strip_tags($comments))))));
            $sale = filter_var($rs['sale'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $output .= $dateFrom . "," . $closer . "," . $agent . "," . $sale . "," . $comments . "\n";

        }
        echo $output;
        exit;

    }

}
