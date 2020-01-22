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

$EXECUTE = filter_input(INPUT_GET, 'EXECUTE', FILTER_SANITIZE_NUMBER_INT);
$USER = filter_input(INPUT_GET, 'USER', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$TOKEN = filter_input(INPUT_GET, 'TOKEN', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

if (isset($USER) && $TOKEN) {

    require_once(__DIR__ . '/../../classes/database_class.php');
    require_once(__DIR__ . '/../../class/login/login.php');

    $CHECK_USER_TOKEN = new UserActions($USER, $TOKEN);
    $CHECK_USER_TOKEN->CheckToken();
    $OUT = $CHECK_USER_TOKEN->CheckToken();

    if (isset($OUT['TOKEN_CHECK']) && $OUT['TOKEN_CHECK'] == 'Bad') {
        echo "BAD";
    }

    if (isset($OUT['TOKEN_CHECK']) && $OUT['TOKEN_CHECK'] == 'Good') {

        $hello_name = $USER;
        require_once(__DIR__ . '/../../includes/Access_Levels.php');

        if (isset($EXECUTE)) {
            if ($EXECUTE == '1') {
                require_once(__DIR__ . '/../../includes/Access_Levels.php');

                $query = $pdo->prepare("SELECT 
    CONCAT(firstname, ' ', lastname) AS full_name
FROM
    employee_details
WHERE
   company =:COMPANY
        AND employed = '1'
    OR
   company =:COMPANYS
        AND employed = '1'    
ORDER BY full_name");
                $query->bindParam(':COMPANY', $COMPANY_ENTITY, PDO::PARAM_STR);
                $query->bindParam(':COMPANYS', $COMPANY_ENTITY_LEAD_GENS, PDO::PARAM_STR);
                $query->execute() or die(print_r($query->errorInfo(), true));
                json_encode($results = $query->fetchAll(PDO::FETCH_ASSOC));

                echo json_encode($results);

            }

        }

    }

} else {

    header('Location: ../../../CRMmain.php');
    die;

}
