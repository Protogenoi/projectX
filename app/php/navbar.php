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

require_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_SPECIAL_CHARS) . '/app/core/doc_root.php';

require_once(BASE_URL . '/classes/access_user/access_user_class.php');
$page_protect = new Access_user;
$page_protect->access_page(filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS), "", 1);
$hello_name = ($page_protect->user_full_name != "") ? $page_protect->user_full_name : $page_protect->user;

require_once(BASE_URL . '/includes/adl_features.php');
require_once(BASE_URL . '/includes/ADL_PDO_CON.php');
require_once(BASE_URL . '/includes/Access_Levels.php');

$query = $pdo->prepare("SELECT status FROM supportTickets WHERE assigned =:hello AND status !='Closed'");
$query->bindParam(':hello', $hello_name, PDO::PARAM_STR);
$query->execute();

if ($query->rowCount() > 0) {

    $UPLOAD_COUNT = 0;

    while ($result = $query->fetch(PDO::FETCH_ASSOC)) {

        $UPLOAD_COUNT++;

    }
} else {
    $UPLOAD_COUNT = 0;
}

$TOTAL_NOTIFICATIONS = $UPLOAD_COUNT;

if (in_array($hello_name, $Level_3_Access, true)) {

    if (isset($TOTAL_NOTIFICATIONS) && $TOTAL_NOTIFICATIONS > 0) { ?>
        <ul class="nav navbar-nav navbar-right">
            <li class='dropdown'>
                <a data-toggle='dropdown' class='dropdown-toggle' href='#'><span class="badge alert-info"><i
                            class="fa fa-exclamation"></i> <strong> <?php if (isset($TOTAL_NOTIFICATIONS) && $TOTAL_NOTIFICATIONS > 0) {
                                echo "$TOTAL_NOTIFICATIONS";
                            } ?></strong></span></a>
                <ul role='menu' class='dropdown-menu'>
                    <?php

                    if (isset($UPLOAD_COUNT) && $UPLOAD_COUNT > 0) { ?>
                        <li>
                            <div class="notice notice-danger" role="alert"><strong><i class="fa fa-list-alt"></i> To Do:</strong>
                                <a href="/addon/support/main.php">You have <?php echo $UPLOAD_COUNT; ?> To Do's
                                    open!</a></div>
                        </li>
                        <?php

                    }

                    ?>
                </ul>
            </li>

            <li><a href="/CRMmain.php?action=log_out"><i class="fa fa-sign-out-alt"></i> Logout</a></li>
        </ul>

    <?php }
}
