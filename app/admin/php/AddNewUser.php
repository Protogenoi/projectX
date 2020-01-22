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

include(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT',
        FILTER_SANITIZE_SPECIAL_CHARS) . "/classes/access_user/access_user_class.php");
$page_protect = new Access_user;
$page_protect->access_page(filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS), "", 10);
$hello_name = ($page_protect->user_full_name != "") ? $page_protect->user_full_name : $page_protect->user;

$USER_TRACKING = 0;

require_once(__DIR__ . '/../../../includes/user_tracking.php');
require_once(__DIR__ . '/../../../includes/adl_features.php');
require_once(__DIR__ . '/../../../includes/Access_Levels.php');
require_once(__DIR__ . '/../../../includes/adlfunctions.php');

require_once(__DIR__ . '/../../../includes/ADL_PDO_CON.php');

if ($ffanalytics == '1') {
    require_once(__DIR__ . '/../../../app/analyticstracking.php');
}

if (isset($fferror)) {
    if ($fferror == '0') {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }
}

$adduser = filter_input(INPUT_GET, 'adduser', FILTER_SANITIZE_NUMBER_INT);
$EXECUTE = filter_input(INPUT_GET, 'EXECUTE', FILTER_SANITIZE_NUMBER_INT);

if (isset($adduser)) {
    if ($adduser == '1') {

        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);
        $confirm = filter_input(INPUT_POST, 'confirm', FILTER_SANITIZE_SPECIAL_CHARS);

        $login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_SPECIAL_CHARS);
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
        $info = filter_input(INPUT_POST, 'info', FILTER_SANITIZE_SPECIAL_CHARS);
        $company = filter_input(INPUT_POST, 'COMPANY_ENTITY', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

        $msg = array(
            "Passwords dont match",
            "Email address already exists",
            "Login already exists",
            "Password too short",
            "Password must include at least one number",
            "Password must include at least one letter"
        );

        function validationcheck($password, $confirm, $email, $msg, $pdo, $login)
        {

            if ($password != $confirm) {

                $checklogin = $pdo->prepare("SELECT login FROM users WHERE login=:login");
                $checklogin->bindParam(':login', $login, PDO::PARAM_STR, 255);
                $checklogin->execute() or die(print_r($checklogin->errorInfo(), true));
                if ($checklogin->rowCount() >= 1) {

                    header('Location: ../Admindash.php?users=y&adduser=0&message=' . $msg[0] . ',' . $msg[2]);
                    die;

                }

                $checkemail = $pdo->prepare("SELECT email FROM users WHERE email=:email");
                $checkemail->bindParam(':email', $email, PDO::PARAM_STR, 255);
                $checkemail->execute() or die(print_r($checkemail->errorInfo(), true));
                if ($checkemail->rowCount() >= 1) {

                    header('Location: ../Admindash.php?users=y&adduser=0&message=' . $msg[0] . ',' . $msg[1]);
                    die;

                }

                header('Location: ../Admindash.php?users=y&adduser=0&message=' . $msg[0]);
                die;
            }

            if ($password == $confirm) {


                function passcheck_email($password, $msg)
                {

                    if (strlen($password) < 8) {

                        header('Location: ../Admindash.php?users=y&adduser=0&message=' . $msg[1] . ',' . $msg[3]);
                        die;
                    }

                    if (!preg_match("#[0-9]+#", $password)) {

                        header('Location: ../Admindash.php?users=y&adduser=0&message=' . $msg[1] . ',' . $msg[4]);
                        die;
                    }

                    if (!preg_match("#[a-zA-Z]+#", $password)) {
                        header('Location: ../Admindash.php?users=y&adduser=0&message=' . $msg[1] . ',' . $msg[5]);
                        die;
                    }


                }

                function passcheck_login($password, $msg)
                {

                    if (strlen($password) < 8) {

                        header('Location: ../Admindash.php?users=y&adduser=0&message=' . $msg[2] . ',' . $msg[3]);
                        die;
                    }

                    if (!preg_match("#[0-9]+#", $password)) {

                        header('Location: ../Admindash.php?users=y&adduser=0&message=' . $msg[2] . ',' . $msg[4]);
                        die;
                    }

                    if (!preg_match("#[a-zA-Z]+#", $password)) {
                        header('Location: ../Admindash.php?users=y&adduser=0&message=' . $msg[2] . ',' . $msg[5]);
                        die;
                    }


                }


                $checkemail = $pdo->prepare("SELECT email FROM users WHERE email=:email");
                $checkemail->bindParam(':email', $email, PDO::PARAM_STR, 255);
                $checkemail->execute() or die(print_r($checkemail->errorInfo(), true));
                if ($checkemail->rowCount() >= 1) {

                    passcheck_email($password, $msg);

                    header('Location: ../Admindash.php?users=y&adduser=0&message=' . $msg[1]);
                    die;

                }

                $checklogin = $pdo->prepare("SELECT login FROM users WHERE login=:login");
                $checklogin->bindParam(':login', $login, PDO::PARAM_STR, 255);
                $checklogin->execute() or die(print_r($checklogin->errorInfo(), true));
                if ($checklogin->rowCount() >= 1) {

                    passcheck_login($password, $msg);

                    header('Location: ../Admindash.php?users=y&adduser=0&message=' . $msg[2]);
                    die;

                }


            }

        }


        function adduser($pdo, $password, $login, $name, $info, $email, $company)
        {

            $options = [
                'cost' => 9,
            ];

            $HASH = password_hash($password, PASSWORD_BCRYPT, $options);

            $hasspassword = md5($password);

            $adduser = $pdo->prepare("INSERT INTO users set company=:company, login=:login, pw=:password, real_name=:name, extra_info=:info, email=:email, hash=:HASH");
            $adduser->bindParam(':login', $login, PDO::PARAM_STR, 255);
            $adduser->bindParam(':password', $hasspassword, PDO::PARAM_STR, 255);
            $adduser->bindParam(':HASH', $HASH, PDO::PARAM_STR);
            $adduser->bindParam(':company', $company, PDO::PARAM_STR);
            $adduser->bindParam(':name', $name, PDO::PARAM_STR, 255);
            $adduser->bindParam(':info', $info, PDO::PARAM_STR, 255);
            $adduser->bindParam(':email', $email, PDO::PARAM_STR, 255);
            $adduser->execute() or die(print_r($adduser->errorInfo(), true));

            header('Location: ../Admindash.php?users=y&adduser=1&user=' . $login);
            die;

        }


        validationcheck($password, $confirm, $email, $msg, $pdo, $login);
        adduser($pdo, $password, $login, $name, $info, $email, $company);

    }
}

if (isset($EXECUTE)) {
    if ($EXECUTE == '1') {

        $USER_USERNAME = filter_input(INPUT_POST, 'USER_USERNAME', FILTER_SANITIZE_SPECIAL_CHARS);
        $USER_LOGIN = filter_input(INPUT_POST, 'USER_LOGIN', FILTER_SANITIZE_SPECIAL_CHARS);
        $USER_PW = filter_input(INPUT_POST, 'USER_PW', FILTER_SANITIZE_SPECIAL_CHARS);
        $USER_ACCESS_LEVEL = filter_input(INPUT_POST, 'USER_ACCESS_LEVEL', FILTER_SANITIZE_SPECIAL_CHARS);
        $USER_ACTIVE = filter_input(INPUT_POST, 'USER_ACTIVE', FILTER_SANITIZE_SPECIAL_CHARS);
        $USER_COMPANY = filter_input(INPUT_POST, 'USER_COMPANY', FILTER_SANITIZE_SPECIAL_CHARS);
        $USER_ID = filter_input(INPUT_GET, 'USER_ID', FILTER_SANITIZE_NUMBER_INT);


        if ($USER_ACTIVE == 'n') {
            $USER_ACCESS_LEVEL = 0;
        }

        function updateuser(
            $pdo,
            $USER_USERNAME,
            $USER_LOGIN,
            $USER_PW,
            $USER_ACCESS_LEVEL,
            $USER_ID,
            $USER_ACTIVE,
            $USER_COMPANY
        ) {

            $PASS_CHECK = $pdo->prepare("SELECT id from users WHERE id=:UID AND pw=:PASS");
            $PASS_CHECK->bindParam(':UID', $USER_ID, PDO::PARAM_INT);
            $PASS_CHECK->bindParam(':PASS', $USER_PW, PDO::PARAM_STR);
            $PASS_CHECK->execute();

            $hasspassword = md5($USER_PW);

            $options = [
                'cost' => 9,
            ];

            $HASH = password_hash($USER_PW, PASSWORD_BCRYPT, $options);

            if ($PASS_CHECK->rowCount() >= 1) {

                $NO_PASS_QRY = $pdo->prepare("UPDATE users set company=:COMPANY, login=:LOGIN, real_name=:NAME, access_level=:ACCESS, active=:ACTIVE WHERE id=:UID");
                $NO_PASS_QRY->bindParam(':LOGIN', $USER_LOGIN, PDO::PARAM_STR, 255);
                $NO_PASS_QRY->bindParam(':NAME', $USER_USERNAME, PDO::PARAM_STR, 255);
                $NO_PASS_QRY->bindParam(':COMPANY', $USER_COMPANY, PDO::PARAM_STR);
                $NO_PASS_QRY->bindParam(':ACCESS', $USER_ACCESS_LEVEL, PDO::PARAM_STR);
                $NO_PASS_QRY->bindParam(':UID', $USER_ID, PDO::PARAM_INT);
                $NO_PASS_QRY->bindParam(':ACTIVE', $USER_ACTIVE, PDO::PARAM_STR);
                $NO_PASS_QRY->execute() or die(print_r($NO_PASS_QRY->errorInfo(), true));

            } else {

                $PASS_QRY = $pdo->prepare("UPDATE users set company=:COMPANY, login=:LOGIN, hash=:HASH, pw=:PASS, real_name=:NAME, access_level=:ACCESS, active=:ACTIVE WHERE id=:UID");
                $PASS_QRY->bindParam(':LOGIN', $USER_LOGIN, PDO::PARAM_STR, 255);
                $PASS_QRY->bindParam(':PASS', $hasspassword, PDO::PARAM_STR, 255);
                $PASS_QRY->bindParam(':HASH', $HASH, PDO::PARAM_STR);
                $PASS_QRY->bindParam(':COMPANY', $USER_COMPANY, PDO::PARAM_STR);
                $PASS_QRY->bindParam(':NAME', $USER_USERNAME, PDO::PARAM_STR, 255);
                $PASS_QRY->bindParam(':ACCESS', $USER_ACCESS_LEVEL, PDO::PARAM_STR);
                $PASS_QRY->bindParam(':UID', $USER_ID, PDO::PARAM_INT);
                $PASS_QRY->bindParam(':ACTIVE', $USER_ACTIVE, PDO::PARAM_STR);
                $PASS_QRY->execute() or die(print_r($PASS_QRY->errorInfo(), true));

            }

            header('Location: ../Admindash.php?users=y&adduser=2&user=' . $USER_LOGIN);
            die;

        }

        updateuser($pdo, $USER_USERNAME, $USER_LOGIN, $USER_PW, $USER_ACCESS_LEVEL, $USER_ID, $USER_ACTIVE,
            $USER_COMPANY);
    }

}

function access_page($refer = "", $qs = "", $level = DEFAULT_ACCESS_LEVEL)
{
    $refer_qs = $refer;
    $refer_qs .= ($qs != "") ? "?" . $qs : "";
    if (!$this->check_user()) {
        $_SESSION['referer'] = $refer_qs;
        header("Location: " . $this->login_page);
        exit;
    }
    if ($this->get_access_level() < $level) {
        header("Location: " . $this->deny_access_page);
        exit;
    }
}

function get_user_info($pdo, $USER_LOGIN, $USER_PW)
{

    $hasspassword = md5($USER_PW);

    $options = [
        'cost' => 9,
    ];

    $HASH = password_hash($USER_PW, PASSWORD_BCRYPT, $options);

    $INFO_QRY = $pdo->prepare("SELECT real_name, extra_info, email, id FROM users WHERE login=:LOGIN, pw=:PASS, hash=:HASH");
    $INFO_QRY->bindParam(':LOGIN', $USER_LOGIN, PDO::PARAM_STR, 255);
    $INFO_QRY->bindParam(':PASS', $hasspassword, PDO::PARAM_STR, 255);
    $INFO_QRY->bindParam(':HASH', $HASH, PDO::PARAM_STR);
    $INFO_QRY->execute() or die(print_r($INFO_QRY->errorInfo(), true));
    $INFO_RESULT = $INFO_QRY->fetch(PDO::FETCH_ASSOC);

    $this->id = $INFO_RESULT['id'];
    $this->user_full_name = $INFO_RESULT['real_name'];
    $this->user_info = $INFO_RESULT['extra_info'];
    $this->user_email = $INFO_RESULT['email'];

}
