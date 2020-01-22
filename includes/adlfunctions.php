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

require_once(__DIR__ . '/../includes/adl_features.php');
require_once(__DIR__ . '/../includes/ADL_PDO_CON.php');

function email_sent_catch()
{
	$emailsent = filter_input(INPUT_GET, 'emailsent', FILTER_SANITIZE_SPECIAL_CHARS);

	if (isset($emailsent)) {

		$emailaddress = filter_input(INPUT_GET, 'emailto', FILTER_SANITIZE_EMAIL);

		print("<div class=\"notice notice-success\" role=\"alert\"><strong><i class=\"fa fa-envelope fa-lg\"></i> Success:</strong> Email sent to <b>$emailaddress</b> !</div>");

	}
} //End of function definition

function food_upload()
{

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		if (isset($_FILES['upload'])) {
			$allowed = array('image/jpeg', 'image/JPG', 'image/jpg');
			if (in_array($_FILES['upload']['type'], $allowed)) {
				if (move_uploaded_file($_FILES['upload']['tmp_name'], "../img/food/{$_FILES['upload']['name']}")) {
					echo '<p><em><div class=\"notice notice-success\" role=\"alert\"><strong>Success!</strong> Image uploaded</div></em></p>';
				}
			} else {
				echo '<p class="error">Please upload a JPEG or PNG image.</p>';
			}
		}
		if ($_FILES['uploads']['error'] > 0) {
			echo '<p class="uploaderror">The file could not be uploaded because: <strong>';
			switch ($_FILES['upload']['error']) {
				case 1:
					print 'The file exceeds the upload_max_filesize setting in php.ini.';
					break;
				case 2:
					print 'The files exceeds the MAX_FILE_SIZE setting in the HTML form.';
					break;
				case 3:
					print 'The file was only partially uploaded.';
					break;
				case 4:
					print 'No file was uploaded.';
					break;
				case 6;
					print 'No temporary folder was available.';
					break;
				case 7:
					print 'Unable to write to the disk.';
					break;
				case 8:
					print 'File upload stopped.';
					break;
				default;
					print 'A system error occured.';
					break;
			}
			print '</strong></p>';
		}

		if (file_exists($_FILES['upload']['tmp_name']) && is_file($_FILES['upload']['tmp_name'])) {
			unlink($_FILES['upload']['tmp_name']);
		}
	}

}

//End of function definition

function cat_upload()
{

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		if (isset($_FILES['upload'])) {
			$allowed = array('image/jpeg', 'image/JPG', 'image/jpg');
			if (in_array($_FILES['upload']['type'], $allowed)) {
				if (move_uploaded_file($_FILES['upload']['tmp_name'], "../img/aww/{$_FILES['upload']['name']}")) {
					echo '<p><em><div class=\"notice notice-success\" role=\"alert\"><strong>Success!</strong> Image uploaded</div></em></p>';
				}
			} else {
				echo '<p class="error">Please upload a JPEG or PNG image.</p>';
			}
		}
		if ($_FILES['uploads']['error'] > 0) {
			echo '<p class="uploaderror">The file could not be uploaded because: <strong>';
			switch ($_FILES['upload']['error']) {
				case 1:
					print 'The file exceeds the upload_max_filesize setting in php.ini.';
					break;
				case 2:
					print 'The files exceeds the MAX_FILE_SIZE setting in the HTML form.';
					break;
				case 3:
					print 'The file was only partially uploaded.';
					break;
				case 4:
					print 'No file was uploaded.';
					break;
				case 6;
					print 'No temporary folder was available.';
					break;
				case 7:
					print 'Unable to write to the disk.';
					break;
				case 8:
					print 'File upload stopped.';
					break;
				default;
					print 'A system error occured.';
					break;
			}
			print '</strong></p>';
		}

		if (file_exists($_FILES['upload']['tmp_name']) && is_file($_FILES['upload']['tmp_name'])) {
			unlink($_FILES['upload']['tmp_name']);
		}
	}

} //End of function definition

function adl_version()
{
	echo '<div class="row">
        <div class="col-sm-12">
            <footer>
            	<p><i><b>ADL</b> release v1.0.4-1.6</p>
            </footer>
        </div>
    </div>';
} //End of function definition

function logged_hostnameip()
{

	$HTTP_REFERER = filter_input(INPUT_SERVER, 'HTTP_REFERER', FILTER_SANITIZE_SPECIAL_CHARS);
	$REMOTE_ADDR = filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_SANITIZE_SPECIAL_CHARS);
	$HOSTNAME = gethostbyaddr($REMOTE_ADDR);

	date_default_timezone_set("Europe/London");
	echo '<center><p>Info logged<br>';
	echo $HTTP_REFERER;
	echo "<br>";
	echo $REMOTE_ADDR;
	echo '<br>';
	echo $HOSTNAME;
	echo '<br>';
	echo date('m/d/y h:i a', time());
	echo '</p></center>';
} //End of function definition

?>


