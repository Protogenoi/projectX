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

class UserActions
{

    public $isAlive = true;
    public $hello_name;
    public $token;

    function __construct($hello_name, $TOKEN)
    {

        $this->hello_name = $hello_name;
        $this->token = $TOKEN;

    }


    function UpdateToken()
    {

        //$token = bin2hex(random_bytes(16)); PHP7

        $token = bin2hex(openssl_random_pseudo_bytes(16));

        $database = new Database();
        $database->beginTransaction();

        $database->query("UPDATE users SET token=:TOKEN WHERE login=:USER");
        $database->bind(':USER', $this->hello_name);
        $database->bind(':TOKEN', $token);
        $database->execute();

        $database->endTransaction();

    }

    function SelectToken()
    {

        $database = new Database();
        $database->beginTransaction();

        $database->query("SELECT token from users WHERE login=:USER");
        $database->bind(':USER', $this->hello_name);
        $database->execute();
        $row = $database->single();

        if ($database->rowCount() >= 1) {

            $this->TOKEN_SELECTED = $row['token'];

            $OUT['TOKEN_SELECT'] = $this->TOKEN_SELECTED;

        } else {

            $OUT['TOKEN_SELECT'] = "NoToken";

        }

        $database->endTransaction();

        return $OUT;


    }

    function CheckToken()
    {

        $database = new Database();
        $database->beginTransaction();

        $database->query("SELECT id from users WHERE token=:TOKEN AND login=:USER");
        $database->bind(':USER', $this->hello_name);
        $database->bind(':TOKEN', $this->token);
        $database->execute();

        $database->endTransaction();

        if ($database->rowCount() >= 1) {
            $OUT['TOKEN_CHECK'] = 'Good';
        } else {
            $OUT['TOKEN_CHECK'] = 'Bad';
        }

        return $OUT;

    }

    /**
     * @return int
     */
    public function CheckAccessLevel()
    {

        $database = new Database();
        $database->beginTransaction();

        $database->query("SELECT access_level FROM users WHERE login=:USER");
        $database->bind(':USER', $this->hello_name);
        $database->execute();
        $row = $database->single();

        $database->endTransaction();

        if ($database->rowCount() >= 1) {
            $this->USER_ACCESS_LEVEL = $row['access_level'];
            $OUT['ACCESS_LEVEL'] = $this->USER_ACCESS_LEVEL;
        } else {
            $OUT['ACCESS_LEVEL'] = 0;
        }

        return $OUT;

    }

}
