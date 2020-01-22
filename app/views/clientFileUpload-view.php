<?php
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
 */

?>

<form action="/addon/Life/php/upload.php?EXECUTE=1&CID=<?php echo $search; ?>" method="POST"
      enctype="multipart/form-data">
    <label for="file"><input type="file" name="file[]" multiple/></label>
    <label for="uploadtype">
        <div class="form-group">
            <select style="width: 170px" class="form-control" name="uploadtype" required>
                <option value="">Select...</option>
                <option value="Closer Call Recording">Closer Call Recording</option>
                <option value="Agent Call Recording">Agent Call Recording</option>
                <option value="Dealsheet">Life Dealsheet</option>
                <option disabled>──────────</option>
                <option value="Admin Call Recording">Admin Call Recording</option>
                <option value="Recording">Call Recording</option>
                <option value="LifeCloserAudit">Closer Audit</option>
                <option value="LifeLeadAudit">Lead Audit</option>
                <option disabled>──────────</option>
                <option value="Aegonpolicy">Aegon App</option>
                <option value="Aegonkeyfacts">Aegon Keyfacts</option>
                <option disabled>──────────</option>
                <option value="RLpolicy">Royal London App</option>
                <option value="RLkeyfacts">Royal London Keyfacts</option>
                <option disabled>──────────</option>
                <option value="NFPolicy Summary">National Friendly Policy Summary</option>
                <option value="NFpolicy">National Friendly App</option>
                <option value="NFkeyfacts">National Friendly Keyfacts</option>
                <option disabled>──────────</option>
                <option value="HSBCpolicy Summary">HSBC Policy Summary</option>
                <option value="HSBCpolicy">HSBC App</option>
                <option value="HSBCkeyfacts">HSBC Keyfacts</option>
                <option disabled>──────────</option>
                <option value="LVpolicy">LV App</option>
                <option value="LVkeyfacts">LV Keyfacts</option>
                <option disabled>──────────</option>
                <option value="WOLpolicy">One Family App</option>
                <option value="WOLkeyfacts">One Family Keyfacts</option>
                <option disabled>──────────</option>
                <option value="Avivapolicy">Aviva App</option>
                <option value="Avivakeyfacts">Aviva Keyfacts</option>
                <option disabled>──────────</option>
                <option value="Zurichpolicy">Zurich App</option>
                <option value="Zurichkeyfacts">Zurich Keyfacts</option>
                <option disabled>──────────</option>
                <option value="SWpolicy">Scottish Widows App</option>
                <option value="SWkeyfacts">Scottish Widows Keyfacts</option>
                <option disabled>──────────</option>
                <option value="lifenotes">Notes</option>
                <option value="Other">Other</option>
                <option disabled>──────────</option>
            </select>
        </div>
    </label>

    <button type="submit" class="btn btn-success" name="btn-upload"><i class="fa fa-upload"></i>
        Upload file(s)
    </button>
</form>
<br/><br/>
