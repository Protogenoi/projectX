<?php
/** @noinspection PhpIncludeInspection */

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
 *  Composer - https://getcomposer.org/doc/
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
 *  Ideal Postcodes - https://ideal-postcodes.co.uk/documentation
 *  Chart.js - https://github.com/chartjs/Chart.js
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
    $agent = filter_input(INPUT_GET, 'agent', FILTER_SANITIZE_SPECIAL_CHARS);

    // SET VARIABLES

    $ashleighXfer = 0;
    $ashleighSale = 0;
    $ashleighCurPremium = 0;
    $ashleighOurPremium = 0;

    $richXfer = 0;
    $richSale = 0;
    $richCurPremium = 0;
    $richOurPremium = 0;

    $jessXfer = 0;
    $jessSale = 0;
    $jessCurPremium = 0;
    $jessOurPremium = 0;

    $mikeXfer = 0;
    $mikeSale = 0;
    $mikeCurPremium = 0;
    $mikeOurPremium = 0;

    $carysXfer = 0;
    $carysSale = 0;
    $carysCurPremium = 0;
    $carysOurPremium = 0;

    $davidXfer = 0;
    $davidSale = 0;
    $davidCurPremium = 0;
    $davidOurPremium = 0;

    $kyleXfer = 0;
    $kyleSale = 0;
    $kyleCurPremium = 0;
    $kyleOurPremium = 0;

    $jamesXfer = 0;
    $jamesSale = 0;
    $jamesCurPremium = 0;
    $jamesOurPremium = 0;

    $danXfer = 0;
    $danSale = 0;
    $danCurPremium = 0;
    $danOurPremium = 0;

    $jadeXfer = 0;
    $jadeSale = 0;
    $jadeCurPremium = 0;
    $jadeOurPremium = 0;

    $rossXfer = 0;
    $rossSale = 0;
    $rossCurPremium = 0;
    $rossOurPremium = 0;

    $richConversionRate = 0;
    $ashleighConversionRate = 0;
    $jessConversionRate = 0;
    $rossConversionRate = 0;
    $mikeConversionRate = 0;
    $carysConversionRate = 0;
    $davidConversionRate = 0;
    $kyleConversionRate = 0;
    $jamesConversionRate = 0;
    $danConversionRate = 0;
    $jadeConversionRate = 0;
    $totalConversionRate = 0;

    $totalCurPremium = 0;
    $totalOurPremium = 0;

    $saleOurPremium = 0;
    $saleCurrentPremium = 0;

    $file = "Agent Stats";
    $filename = $file . "_" . date("Y-m-d_H-i", time());
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $filename . '.csv');

    if ($EXECUTE == 1) {

        if (isset($dateFrom)) {

            // GET SALES

            $query = $pdo->prepare("SELECT 
    closer,
    agent,
    count(tracker_id) AS sale, SUM(our_premium) AS our_premium, SUM(current_premium) AS current_premium
FROM
    closer_trackers
WHERE
    DATE(date_added) BETWEEN :dateFrom AND  :dateTo
        AND agent =:agent
        AND sale = 'SALE'
        GROUP BY closer
ORDER BY closer DESC");
            $query->bindParam(':agent', $agent, PDO::PARAM_STR);
            $query->bindParam(':dateFrom', $dateFrom, PDO::PARAM_STR);
            $query->bindParam(':dateTo', $dateTo, PDO::PARAM_STR);
            $query->execute();
            $row = $query->fetchall(PDO::FETCH_ASSOC);
            $totalSales = 0;
            foreach ($row as $item):

                if (isset($item['closer'])) {

                    $saleOurPremium += $item['our_premium'];
                    $saleCurrentPremium += $item['current_premium'];

                    if ($item['closer'] == 'Ross') {

                        $rossSale += $item['sale'];

                    }
                    if ($item['closer'] == 'Dan') {

                        $danSale += $item['sale'];

                    }
                    if ($item['closer'] == 'Richard') {

                        $richSale += $item['sale'];

                    }
                    if ($item['closer'] == 'Jade') {

                        $jadeSale += $item['sale'];

                    }
                    if ($item['closer'] == 'James') {

                        $jamesSale += $item['sale'];

                    }
                    if ($item['closer'] == 'Kyle') {

                        $kyleSale += $item['sale'];

                    }
                    if ($item['closer'] == 'David' || $item['closer'] == 'David Spooner') {

                        $davidSale += $item['sale'];

                    }
                    if ($item['closer'] == 'Carys' || $item['closer'] == 'Carys Riley') {

                        $carysSale += $item['sale'];

                    }
                    if ($item['closer'] == 'Mike') {

                        $mikeSale += $item['sale'];

                    }

                    if ($item['closer'] == 'Ashleigh') {

                        $ashleighSale += $item['sale'];

                    }

                    if ($item['closer'] == 'Jess') {

                        $jessSale += $item['sale'];

                    }
                }

                $totalSales += $item['sale'];

            endforeach;

            // GET XFERS

            $query = $pdo->prepare("SELECT closer, agent, count(tracker_id) AS noSale, SUM(our_premium) AS our_premium, SUM(current_premium) AS current_premium
FROM
    closer_trackers
WHERE
    DATE(date_added) BETWEEN :dateFrom AND  :dateTo
        AND agent =:agent
        GROUP BY closer
ORDER BY closer DESC");
            $query->bindParam(':agent', $agent, PDO::PARAM_STR);
            $query->bindParam(':dateFrom', $dateFrom, PDO::PARAM_STR);
            $query->bindParam(':dateTo', $dateTo, PDO::PARAM_STR);
            $query->execute();
            $row = $query->fetchall(PDO::FETCH_ASSOC);
            $totalXfers = 0;
            foreach ($row as $item):

                if (isset($item['closer'])) {
                    if ($item['closer'] == 'Ross') {

                        $rossXfer += $item['noSale'];

                        if (is_numeric($item['current_premium'])) {
                            $rossCurPremium += $item['current_premium'];
                        }

                        if (is_numeric($item['our_premium'])) {
                            $rossOurPremium += $item['our_premium'];
                        }

                    }
                    if ($item['closer'] == 'Jade') {

                        $jadeXfer += $item['noSale'];

                        if (is_numeric($item['current_premium'])) {
                            $jadeCurPremium += $item['current_premium'];
                        }

                        if (is_numeric($item['our_premium'])) {
                            $jadeOurPremium += $item['our_premium'];
                        }

                    }
                    if ($item['closer'] == 'Dan') {

                        $danXfer += $item['noSale'];

                        if (is_numeric($item['current_premium'])) {
                            $danCurPremium += $item['current_premium'];
                        }

                        if (is_numeric($item['our_premium'])) {
                            $danOurPremium += $item['our_premium'];
                        }

                    }
                    if ($item['closer'] == 'Richard') {

                        $richXfer += $item['noSale'];

                        if (is_numeric($item['current_premium'])) {
                            $richCurPremium += $item['current_premium'];
                        }

                        if (is_numeric($item['our_premium'])) {
                            $richOurPremium += $item['our_premium'];
                        }

                    }
                    if ($item['closer'] == 'James') {

                        $jamesXfer += $item['noSale'];

                        if (is_numeric($item['current_premium'])) {
                            $jamesCurPremium += $item['current_premium'];
                        }

                        if (is_numeric($item['our_premium'])) {
                            $jamesOurPremium += $item['our_premium'];
                        }

                    }
                    if ($item['closer'] == 'Kyle') {

                        $kyleXfer += $item['noSale'];

                        if (is_numeric($item['current_premium'])) {
                            $kyleCurPremium += $item['current_premium'];
                        }

                        if (is_numeric($item['our_premium'])) {
                            $kyleOurPremium += $item['our_premium'];
                        }

                    }
                    if ($item['closer'] == 'David' || $item['closer'] == 'David Spooner') {

                        $davidXfer += $item['noSale'];

                        if (is_numeric($item['current_premium'])) {
                            $davidCurPremium += $item['current_premium'];
                        }

                        if (is_numeric($item['our_premium'])) {
                            $davidOurPremium += $item['our_premium'];
                        }

                    }
                    if ($item['closer'] == 'Carys' || $item['closer'] == 'Carys Riley') {

                        $carysXfer += $item['noSale'];

                        if (is_numeric($item['current_premium'])) {
                            $carysCurPremium += $item['current_premium'];
                        }

                        if (is_numeric($item['our_premium'])) {
                            $carysOurPremium += $item['our_premium'];
                        }

                    }
                    if ($item['closer'] == 'Mike') {

                        $mikeXfer += $item['noSale'];

                        if (is_numeric($item['current_premium'])) {
                            $mikeCurPremium += $item['current_premium'];
                        }

                        if (is_numeric($item['our_premium'])) {
                            $mikeOurPremium += $item['our_premium'];
                        }

                    }

                    if ($item['closer'] == 'Jess') {

                        $jessXfer += $item['noSale'];

                        if (is_numeric($item['current_premium'])) {
                            $jessCurPremium += $item['current_premium'];
                        }

                        if (is_numeric($item['our_premium'])) {
                            $jessOurPremium += $item['our_premium'];
                        }

                    }

                    if ($item['closer'] == 'Ashleigh') {

                        $ashleighXfer += $item['noSale'];

                        if (is_numeric($item['current_premium'])) {
                            $ashleighCurPremium += $item['current_premium'];
                        }

                        if (is_numeric($item['our_premium'])) {
                            $ashleighOurPremium += $item['our_premium'];
                        }

                    }
                }

                $totalXfers += $item['noSale'];

                if (is_numeric($item['current_premium'])) {
                    $totalCurPremium += $item['current_premium'];
                }

                if (is_numeric($item['our_premium'])) {
                    $totalOurPremium += $item['our_premium'];
                }

            endforeach;

            if ($totalSales > 0) {
                $totalConversionRate = $totalXfers / $totalSales;
            }

            $output = "Agent, Xfer, Sales, CR, Current Premium, Our Premium, Sales Current Premium, Sales Our Premium\n";

            $output .= $agent . "," . $totalXfers . "," . $totalSales . "," . number_format($totalConversionRate,
                    1) . "," . $totalCurPremium . "," . $totalOurPremium . "," . $saleCurrentPremium . "," . $saleOurPremium . "\n";

            echo $output . "\n";

            if ($richSale > 0) {
                $richConversionRate = $richXfer / $richSale;
            }
            if ($ashleighSale > 0) {
                $ashleighConversionRate = $ashleighXfer / $ashleighSale;
            }
            if ($jessSale > 0) {
                $jessConversionRate = $jessXfer / $jessSale;
            }
            if ($rossSale > 0) {
                $rossConversionRate = $rossXfer / $rossSale;
            }
            if ($mikeSale > 0) {
                $mikeConversionRate = $mikeXfer / $mikeSale;
            }
            if ($carysSale > 0) {
                $carysConversionRate = $carysXfer / $carysSale;
            }
            if ($davidSale > 0) {
                $davidConversionRate = $davidXfer / $davidSale;
            }
            if ($kyleSale > 0) {
                $kyleConversionRate = $kyleXfer / $kyleSale;
            }
            if ($jamesSale > 0) {
                $jamesConversionRate = $jamesXfer / $jamesSale;
            }
            if ($danSale > 0) {
                $danConversionRate = $danXfer / $danSale;
            }
            if ($jadeSale > 0) {
                $jadeConversionRate = $jadeXfer / $jadeSale;
            }

            $output = "Closer, Xfer, Sales, CR, Current Premium, Our Premium\n";


            if ($richXfer > 0) {
                $output .= "Richard," . $richXfer . "," . $richSale . "," . number_format($richConversionRate,
                        1) . "," . $richCurPremium . "," . $richOurPremium . "\n";
            }
            if ($ashleighXfer > 0) {
                $output .= "Ashleigh," . $ashleighXfer . "," . $ashleighSale . "," . number_format($ashleighConversionRate,
                        1) . "," . $ashleighCurPremium . "," . $ashleighOurPremium . "\n";
            }
            if ($jessXfer > 0) {
                $output .= "Jess," . $jessXfer . "," . $jessSale . "," . number_format($jessConversionRate,
                        1) . "," . $jessCurPremium . "," . $jessOurPremium . "\n";
            }
            if ($rossXfer > 0) {
                $output .= "Ross," . $rossXfer . "," . $rossSale . "," . number_format($rossConversionRate,
                        1) . "," . $rossCurPremium . "," . $rossOurPremium . "\n";
            }
            if ($mikeXfer > 0) {
                $output .= "Mike," . $mikeXfer . "," . $mikeSale . "," . number_format($mikeConversionRate,
                        1) . "," . $mikeCurPremium . "," . $mikeOurPremium . "\n";
            }
            if ($carysXfer > 0) {
                $output .= "Carys," . $carysXfer . "," . $carysSale . "," . number_format($carysConversionRate,
                        1) . "," . $carysCurPremium . "," . $carysOurPremium . "\n";
            }
            if ($davidXfer > 0) {
                $output .= "David," . $davidXfer . "," . $davidSale . "," . number_format($davidConversionRate,
                        1) . "," . $davidCurPremium . "," . $davidOurPremium . "\n";
            }
            if ($kyleXfer > 0) {
                $output .= "Kyle," . $kyleXfer . "," . $kyleSale . "," . number_format($kyleConversionRate,
                        1) . "," . $kyleCurPremium . "," . $kyleOurPremium . "\n";
            }
            if ($jamesXfer > 0) {
                $output .= "James," . $jamesXfer . "," . $jamesSale . "," . number_format($jamesConversionRate,
                        1) . "," . $jamesCurPremium . "," . $jamesOurPremium . "\n";
            }
            if ($danXfer > 0) {
                $output .= "Dan," . $danXfer . "," . $danSale . "," . number_format($danConversionRate,
                        1) . "," . $danCurPremium . "," . $danOurPremium . "\n";
            }
            if ($jadeXfer > 0) {
                $output .= "Jade," . $jadeXfer . "," . $jadeSale . "," . number_format($jadeConversionRate,
                        1) . "," . $jadeCurPremium . "," . $jadeOurPremium . "\n";
            }

            echo $output . "\n";

            $bob = 'Not Bob';
            if ($bob == 'Bob') {

                $query = $pdo->prepare("SELECT closer, current_premium, our_premium
FROM
    closer_trackers
WHERE
    DATE(date_added) BETWEEN :dateFrom AND  :dateTo
        AND agent =:agent
        ORDER BY closer DESC");
                $query->bindParam(':agent', $agent, PDO::PARAM_STR);
                $query->bindParam(':dateFrom', $dateFrom, PDO::PARAM_STR);
                $query->bindParam(':dateTo', $dateTo, PDO::PARAM_STR);
                $query->execute();
                $row = $query->fetchall(PDO::FETCH_ASSOC);

                $output = "Closer,Current Premium, Our Premium\n";

                foreach ($row as $item):

                    $closer = $item['closer'];
                    $curPremium = $item['current_premium'];
                    $ourPremium = $item['our_premium'];

                    $output .= "$closer," . $curPremium . "," . $ourPremium . "\n";

                endforeach;

                echo $output;

            }

        }

    }

    if ($EXECUTE == 2) {

        $query = $pdo->prepare("SELECT CONCAT(firstname, ' ', lastname) as agentName FROM employee_details WHERE employed='1' AND position='Life Lead Gen'");
        $query->execute();
        $agentArray = $query->fetchall(PDO::FETCH_COLUMN);

        if (isset($dateFrom)) {
            foreach ($agentArray as $agent):

                $ashleighXfer = 0;
                $ashleighSale = 0;
                $ashleighCurPremium = 0;
                $ashleighOurPremium = 0;

                $richXfer = 0;
                $richSale = 0;
                $richCurPremium = 0;
                $richOurPremium = 0;

                $jessXfer = 0;
                $jessSale = 0;
                $jessCurPremium = 0;
                $jessOurPremium = 0;

                $mikeXfer = 0;
                $mikeSale = 0;
                $mikeCurPremium = 0;
                $mikeOurPremium = 0;

                $carysXfer = 0;
                $carysSale = 0;
                $carysCurPremium = 0;
                $carysOurPremium = 0;

                $davidXfer = 0;
                $davidSale = 0;
                $davidCurPremium = 0;
                $davidOurPremium = 0;

                $kyleXfer = 0;
                $kyleSale = 0;
                $kyleCurPremium = 0;
                $kyleOurPremium = 0;

                $jamesXfer = 0;
                $jamesSale = 0;
                $jamesCurPremium = 0;
                $jamesOurPremium = 0;

                $danXfer = 0;
                $danSale = 0;
                $danCurPremium = 0;
                $danOurPremium = 0;

                $jadeXfer = 0;
                $jadeSale = 0;
                $jadeCurPremium = 0;
                $jadeOurPremium = 0;

                $rossXfer = 0;
                $rossSale = 0;
                $rossCurPremium = 0;
                $rossOurPremium = 0;

                $richConversionRate = 0;
                $ashleighConversionRate = 0;
                $jessConversionRate = 0;
                $rossConversionRate = 0;
                $mikeConversionRate = 0;
                $carysConversionRate = 0;
                $davidConversionRate = 0;
                $kyleConversionRate = 0;
                $jamesConversionRate = 0;
                $danConversionRate = 0;
                $jadeConversionRate = 0;
                $totalConversionRate = 0;

                $totalCurPremium = 0;
                $totalOurPremium = 0;

                $saleOurPremium = 0;

                // GET SALES

                $query = $pdo->prepare("SELECT 
    closer,
    agent,
    count(tracker_id) AS sale, SUM(our_premium) AS our_premium, SUM(current_premium) AS current_premium
FROM
    closer_trackers
WHERE
    DATE(date_added) BETWEEN :dateFrom AND  :dateTo
        AND agent =:agent
        AND sale = 'SALE'
        GROUP BY closer
ORDER BY closer DESC");
                $query->bindParam(':agent', $agent, PDO::PARAM_STR);
                $query->bindParam(':dateFrom', $dateFrom, PDO::PARAM_STR);
                $query->bindParam(':dateTo', $dateTo, PDO::PARAM_STR);
                $query->execute();
                $row = $query->fetchall(PDO::FETCH_ASSOC);
                $totalSales = 0;
                foreach ($row as $item):

                    if (isset($item['closer'])) {

                        $saleOurPremium += $item['our_premium'];
                        $saleCurrentPremium += $item['current_premium'];

                        if ($item['closer'] == 'Ross') {

                            $rossSale += $item['sale'];

                        }
                        if ($item['closer'] == 'Dan') {

                            $danSale += $item['sale'];

                        }
                        if ($item['closer'] == 'Richard') {

                            $richSale += $item['sale'];

                        }
                        if ($item['closer'] == 'Jade') {

                            $jadeSale += $item['sale'];

                        }
                        if ($item['closer'] == 'James') {

                            $jamesSale += $item['sale'];

                        }
                        if ($item['closer'] == 'Kyle') {

                            $kyleSale += $item['sale'];

                        }
                        if ($item['closer'] == 'David' || $item['closer'] == 'David Spooner') {

                            $davidSale += $item['sale'];

                        }
                        if ($item['closer'] == 'Carys' || $item['closer'] == 'Carys Riley') {

                            $carysSale += $item['sale'];

                        }
                        if ($item['closer'] == 'Mike') {

                            $mikeSale += $item['sale'];

                        }

                        if ($item['closer'] == 'Ashleigh') {

                            $ashleighSale += $item['sale'];

                        }

                        if ($item['closer'] == 'Jess') {

                            $jessSale += $item['sale'];

                        }
                    }

                    $totalSales += $item['sale'];

                endforeach;

                // GET XFERS

                $query = $pdo->prepare("SELECT closer, agent, count(tracker_id) AS noSale, SUM(our_premium) AS our_premium, SUM(current_premium) AS current_premium
FROM
    closer_trackers
WHERE
    DATE(date_added) BETWEEN :dateFrom AND  :dateTo
        AND agent =:agent
        GROUP BY closer
ORDER BY closer DESC");
                $query->bindParam(':agent', $agent, PDO::PARAM_STR);
                $query->bindParam(':dateFrom', $dateFrom, PDO::PARAM_STR);
                $query->bindParam(':dateTo', $dateTo, PDO::PARAM_STR);
                $query->execute();
                $row = $query->fetchall(PDO::FETCH_ASSOC);
                $totalXfers = 0;
                foreach ($row as $item):

                    if (isset($item['closer'])) {
                        if ($item['closer'] == 'Ross') {

                            $rossXfer += $item['noSale'];

                            if (is_numeric($item['current_premium'])) {
                                $rossCurPremium += $item['current_premium'];
                            }

                            if (is_numeric($item['our_premium'])) {
                                $rossOurPremium += $item['our_premium'];
                            }

                        }
                        if ($item['closer'] == 'Jade') {

                            $jadeXfer += $item['noSale'];

                            if (is_numeric($item['current_premium'])) {
                                $jadeCurPremium += $item['current_premium'];
                            }

                            if (is_numeric($item['our_premium'])) {
                                $jadeOurPremium += $item['our_premium'];
                            }

                        }
                        if ($item['closer'] == 'Dan') {

                            $danXfer += $item['noSale'];

                            if (is_numeric($item['current_premium'])) {
                                $danCurPremium += $item['current_premium'];
                            }

                            if (is_numeric($item['our_premium'])) {
                                $danOurPremium += $item['our_premium'];
                            }

                        }
                        if ($item['closer'] == 'Richard') {

                            $richXfer += $item['noSale'];

                            if (is_numeric($item['current_premium'])) {
                                $richCurPremium += $item['current_premium'];
                            }

                            if (is_numeric($item['our_premium'])) {
                                $richOurPremium += $item['our_premium'];
                            }

                        }
                        if ($item['closer'] == 'James') {

                            $jamesXfer += $item['noSale'];

                            if (is_numeric($item['current_premium'])) {
                                $jamesCurPremium += $item['current_premium'];
                            }

                            if (is_numeric($item['our_premium'])) {
                                $jamesOurPremium += $item['our_premium'];
                            }

                        }
                        if ($item['closer'] == 'Kyle') {

                            $kyleXfer += $item['noSale'];

                            if (is_numeric($item['current_premium'])) {
                                $kyleCurPremium += $item['current_premium'];
                            }

                            if (is_numeric($item['our_premium'])) {
                                $kyleOurPremium += $item['our_premium'];
                            }

                        }
                        if ($item['closer'] == 'David' || $item['closer'] == 'David Spooner') {

                            $davidXfer += $item['noSale'];

                            if (is_numeric($item['current_premium'])) {
                                $davidCurPremium += $item['current_premium'];
                            }

                            if (is_numeric($item['our_premium'])) {
                                $davidOurPremium += $item['our_premium'];
                            }

                        }
                        if ($item['closer'] == 'Carys' || $item['closer'] == 'Carys Riley') {

                            $carysXfer += $item['noSale'];

                            if (is_numeric($item['current_premium'])) {
                                $carysCurPremium += $item['current_premium'];
                            }

                            if (is_numeric($item['our_premium'])) {
                                $carysOurPremium += $item['our_premium'];
                            }

                        }
                        if ($item['closer'] == 'Mike') {

                            $mikeXfer += $item['noSale'];

                            if (is_numeric($item['current_premium'])) {
                                $mikeCurPremium += $item['current_premium'];
                            }

                            if (is_numeric($item['our_premium'])) {
                                $mikeOurPremium += $item['our_premium'];
                            }

                        }

                        if ($item['closer'] == 'Jess') {

                            $jessXfer += $item['noSale'];

                            if (is_numeric($item['current_premium'])) {
                                $jessCurPremium += $item['current_premium'];
                            }

                            if (is_numeric($item['our_premium'])) {
                                $jessOurPremium += $item['our_premium'];
                            }

                        }

                        if ($item['closer'] == 'Ashleigh') {

                            $ashleighXfer += $item['noSale'];

                            if (is_numeric($item['current_premium'])) {
                                $ashleighCurPremium += $item['current_premium'];
                            }

                            if (is_numeric($item['our_premium'])) {
                                $ashleighOurPremium += $item['our_premium'];
                            }

                        }
                    }

                    $totalXfers += $item['noSale'];

                    if (is_numeric($item['current_premium'])) {
                        $totalCurPremium += $item['current_premium'];
                    }

                    if (is_numeric($item['our_premium'])) {
                        $totalOurPremium += $item['our_premium'];
                    }

                endforeach;

                if ($totalSales > 0) {
                    $totalConversionRate = $totalXfers / $totalSales;
                }

                $output = "Agent, Xfer, Sales, CR, Current Premium, Our Premium, Sales Current Premium, Sales Our Premium\n";

                $output .= $agent . "," . $totalXfers . "," . $totalSales . "," . number_format($totalConversionRate,
                        1) . "," . $totalCurPremium . "," . $totalOurPremium . "," . $saleCurrentPremium . "," . $saleOurPremium . "\n";

                echo $output . "\n";

                if ($richSale > 0) {
                    $richConversionRate = $richXfer / $richSale;
                }
                if ($ashleighSale > 0) {
                    $ashleighConversionRate = $ashleighXfer / $ashleighSale;
                }
                if ($jessSale > 0) {
                    $jessConversionRate = $jessXfer / $jessSale;
                }
                if ($rossSale > 0) {
                    $rossConversionRate = $rossXfer / $rossSale;
                }
                if ($mikeSale > 0) {
                    $mikeConversionRate = $mikeXfer / $mikeSale;
                }
                if ($carysSale > 0) {
                    $carysConversionRate = $carysXfer / $carysSale;
                }
                if ($davidSale > 0) {
                    $davidConversionRate = $davidXfer / $davidSale;
                }
                if ($kyleSale > 0) {
                    $kyleConversionRate = $kyleXfer / $kyleSale;
                }
                if ($jamesSale > 0) {
                    $jamesConversionRate = $jamesXfer / $jamesSale;
                }
                if ($danSale > 0) {
                    $danConversionRate = $danXfer / $danSale;
                }
                if ($jadeSale > 0) {
                    $jadeConversionRate = $jadeXfer / $jadeSale;
                }

                $output = "Closer, Xfer, Sales, CR, Current Premium, Our Premium\n";


                if ($richXfer > 0) {
                    $output .= "Richard," . $richXfer . "," . $richSale . "," . number_format($richConversionRate,
                            1) . "," . $richCurPremium . "," . $richOurPremium . "\n";
                }
                if ($ashleighXfer > 0) {
                    $output .= "Ashleigh," . $ashleighXfer . "," . $ashleighSale . "," . number_format($ashleighConversionRate,
                            1) . "," . $ashleighCurPremium . "," . $ashleighOurPremium . "\n";
                }
                if ($jessXfer > 0) {
                    $output .= "Jess," . $jessXfer . "," . $jessSale . "," . number_format($jessConversionRate,
                            1) . "," . $jessCurPremium . "," . $jessOurPremium . "\n";
                }
                if ($rossXfer > 0) {
                    $output .= "Ross," . $rossXfer . "," . $rossSale . "," . number_format($rossConversionRate,
                            1) . "," . $rossCurPremium . "," . $rossOurPremium . "\n";
                }
                if ($mikeXfer > 0) {
                    $output .= "Mike," . $mikeXfer . "," . $mikeSale . "," . number_format($mikeConversionRate,
                            1) . "," . $mikeCurPremium . "," . $mikeOurPremium . "\n";
                }
                if ($carysXfer > 0) {
                    $output .= "Carys," . $carysXfer . "," . $carysSale . "," . number_format($carysConversionRate,
                            1) . "," . $carysCurPremium . "," . $carysOurPremium . "\n";
                }
                if ($davidXfer > 0) {
                    $output .= "David," . $davidXfer . "," . $davidSale . "," . number_format($davidConversionRate,
                            1) . "," . $davidCurPremium . "," . $davidOurPremium . "\n";
                }
                if ($kyleXfer > 0) {
                    $output .= "Kyle," . $kyleXfer . "," . $kyleSale . "," . number_format($kyleConversionRate,
                            1) . "," . $kyleCurPremium . "," . $kyleOurPremium . "\n";
                }
                if ($jamesXfer > 0) {
                    $output .= "James," . $jamesXfer . "," . $jamesSale . "," . number_format($jamesConversionRate,
                            1) . "," . $jamesCurPremium . "," . $jamesOurPremium . "\n";
                }
                if ($danXfer > 0) {
                    $output .= "Dan," . $danXfer . "," . $danSale . "," . number_format($danConversionRate,
                            1) . "," . $danCurPremium . "," . $danOurPremium . "\n";
                }
                if ($jadeXfer > 0) {
                    $output .= "Jade," . $jadeXfer . "," . $jadeSale . "," . number_format($jadeConversionRate,
                            1) . "," . $jadeCurPremium . "," . $jadeOurPremium . "\n";
                }

                echo $output . "\n";

            endforeach;

        }

    }

    if ($EXECUTE == 3) {

        // GET SALES

        $query = $pdo->prepare("SELECT 
    closer,
    agent,
    count(tracker_id) AS sale
FROM
    closer_trackers
WHERE
    date_added >= CURDATE()
        AND agent =:agent
        AND sale = 'SALE'
        GROUP BY closer
ORDER BY closer DESC");
        $query->bindParam(':agent', $agent, PDO::PARAM_STR);
        $query->execute();
        $row = $query->fetchall(PDO::FETCH_ASSOC);
        $totalSales = 0;
        foreach ($row as $item):

            if (isset($item['closer'])) {

                if ($item['closer'] == 'Ross') {

                    $rossSale += $item['sale'];

                }
                if ($item['closer'] == 'Dan') {

                    $danSale += $item['sale'];

                }
                if ($item['closer'] == 'Richard') {

                    $richSale += $item['sale'];

                }
                if ($item['closer'] == 'Jade') {

                    $jadeSale += $item['sale'];

                }
                if ($item['closer'] == 'James') {

                    $jamesSale += $item['sale'];

                }
                if ($item['closer'] == 'Kyle') {

                    $kyleSale += $item['sale'];

                }
                if ($item['closer'] == 'David' || $item['closer'] == 'David Spooner') {

                    $davidSale += $item['sale'];

                }
                if ($item['closer'] == 'Carys' || $item['closer'] == 'Carys Riley') {

                    $carysSale += $item['sale'];

                }
                if ($item['closer'] == 'Mike') {

                    $mikeSale += $item['sale'];

                }

                if ($item['closer'] == 'Ashleigh') {

                    $ashleighSale += $item['sale'];

                }

                if ($item['closer'] == 'Jess') {

                    $jessSale += $item['sale'];

                }
            }

            $totalSales += $item['sale'];

        endforeach;

        // GET XFERS

        $query = $pdo->prepare("SELECT closer, agent, count(tracker_id) AS noSale
FROM
    closer_trackers
WHERE
    date_added >= CURDATE()
        AND agent =:agent
        GROUP BY closer
ORDER BY closer DESC");
        $query->bindParam(':agent', $agent, PDO::PARAM_STR);
        $query->execute();
        $row = $query->fetchall(PDO::FETCH_ASSOC);
        $totalXfers = 0;
        foreach ($row as $item):

            if (isset($item['closer'])) {
                if ($item['closer'] == 'Ross') {

                    $rossXfer += $item['noSale'];

                }
                if ($item['closer'] == 'Jade') {

                    $jadeXfer += $item['noSale'];

                }
                if ($item['closer'] == 'Dan') {

                    $danXfer += $item['noSale'];

                }
                if ($item['closer'] == 'Richard') {

                    $richXfer += $item['noSale'];

                }
                if ($item['closer'] == 'James') {

                    $jamesXfer += $item['noSale'];

                }
                if ($item['closer'] == 'Kyle') {

                    $kyleXfer += $item['noSale'];

                }
                if ($item['closer'] == 'David' || $item['closer'] == 'David Spooner') {

                    $davidXfer += $item['noSale'];


                }
                if ($item['closer'] == 'Carys' || $item['closer'] == 'Carys Riley') {

                    $carysXfer += $item['noSale'];

                }
                if ($item['closer'] == 'Mike') {

                    $mikeXfer += $item['noSale'];

                }

                if ($item['closer'] == 'Jess') {

                    $jessXfer += $item['noSale'];

                }

                if ($item['closer'] == 'Ashleigh') {

                    $ashleighXfer += $item['noSale'];

                }
            }

            $totalXfers += $item['noSale'];

        endforeach;

        if ($totalSales > 0) {
            $totalConversionRate = $totalXfers / $totalSales;
        }

        $output = "Agent, Xfer, Sales, CR\n";

        $output .= $agent . "," . $totalXfers . "," . $totalSales . "," . number_format($totalConversionRate,
                1) . "\n";

        echo $output . "\n";

        if ($richSale > 0) {
            $richConversionRate = $richXfer / $richSale;
        }
        if ($ashleighSale > 0) {
            $ashleighConversionRate = $ashleighXfer / $ashleighSale;
        }
        if ($jessSale > 0) {
            $jessConversionRate = $jessXfer / $jessSale;
        }
        if ($rossSale > 0) {
            $rossConversionRate = $rossXfer / $rossSale;
        }
        if ($mikeSale > 0) {
            $mikeConversionRate = $mikeXfer / $mikeSale;
        }
        if ($carysSale > 0) {
            $carysConversionRate = $carysXfer / $carysSale;
        }
        if ($davidSale > 0) {
            $davidConversionRate = $davidXfer / $davidSale;
        }
        if ($kyleSale > 0) {
            $kyleConversionRate = $kyleXfer / $kyleSale;
        }
        if ($jamesSale > 0) {
            $jamesConversionRate = $jamesXfer / $jamesSale;
        }
        if ($danSale > 0) {
            $danConversionRate = $danXfer / $danSale;
        }
        if ($jadeSale > 0) {
            $jadeConversionRate = $jadeXfer / $jadeSale;
        }

        $output = "Closer, Xfer, Sales, CR\n";

        if ($richXfer > 0) {
            $output .= "Richard," . $richXfer . "," . $richSale . "," . number_format($richConversionRate,
                    1) . "\n";
        }
        if ($ashleighXfer > 0) {
            $output .= "Ashleigh," . $ashleighXfer . "," . $ashleighSale . "," . number_format($ashleighConversionRate,
                    1) . "\n";
        }
        if ($jessXfer > 0) {
            $output .= "Jess," . $jessXfer . "," . $jessSale . "," . number_format($jessConversionRate,
                    1) . "\n";
        }
        if ($rossXfer > 0) {
            $output .= "Ross," . $rossXfer . "," . $rossSale . "," . number_format($rossConversionRate,
                    1) . "\n";
        }
        if ($mikeXfer > 0) {
            $output .= "Mike," . $mikeXfer . "," . $mikeSale . "," . number_format($mikeConversionRate,
                    1) . "\n";
        }
        if ($carysXfer > 0) {
            $output .= "Carys," . $carysXfer . "," . $carysSale . "," . number_format($carysConversionRate,
                    1) . "\n";
        }
        if ($davidXfer > 0) {
            $output .= "David," . $davidXfer . "," . $davidSale . "," . number_format($davidConversionRate,
                    1) . "\n";
        }
        if ($kyleXfer > 0) {
            $output .= "Kyle," . $kyleXfer . "," . $kyleSale . "," . number_format($kyleConversionRate,
                    1) . "\n";
        }
        if ($jamesXfer > 0) {
            $output .= "James," . $jamesXfer . "," . $jamesSale . "," . number_format($jamesConversionRate,
                    1) . "\n";
        }
        if ($danXfer > 0) {
            $output .= "Dan," . $danXfer . "," . $danSale . "," . number_format($danConversionRate,
                    1) . "\n";
        }
        if ($jadeXfer > 0) {
            $output .= "Jade," . $jadeXfer . "," . $jadeSale . "," . number_format($jadeConversionRate,
                    1) . "\n";
        }

        echo $output . "\n";

    }

}
