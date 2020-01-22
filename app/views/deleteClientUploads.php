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

<table class="table table-hover">
    <thead>
    <tr>
        <th colspan="4"><h3><span class="label label-info">Client Uploads</span></h3><label></label></th>
    </tr>
    <tr>
        <td>Date</td>
        <td>File Name</td>
        <td>File Type</td>
        <td></td>
        <td></td>
    </tr>

    <?php

    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $i++;
    $fileLocation = $row['file'];
    ?>

    <tr>
        <td><?php echo $row['added_date'] ?></td>
        <td><?php echo $row['file'] ?></td>
        <td><?php echo $row['uploadtype'] ?></td>
        <td><a href="<?php
            if (file_exists(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT',
                    FILTER_SANITIZE_SPECIAL_CHARS) . "/uploads/$fileLocation")) {
                echo "/uploads/$fileLocation";
            } else {
                echo "/uploads/life/$search/$fileLocation";
            }
            ?>" target="_blank">
                <button type="button" class="btn btn-info btn-xs"><span
                        class="glyphicon glyphicon-search"></span></button>
            </a></td>
        <td>
            <form action="/app/php/deleteClientUpload.php?EXECUTE=1" id="DELETE_FILE_FORM<?php echo $i ?>"
                  method="POST" name="DELETE_FILE_FORM">

                <input type="hidden" name="UID" value="<?php echo $row['id']; ?>">
                <input type="hidden" name="fileName" value="<?php echo $fileLocation; ?>">
                <input type="hidden" name="CID" value="<?php echo $search; ?>">

                <button type="submit" class="btn btn-danger btn-xs"><span
                        class="glyphicon glyphicon-remove"></span></button>
            </form>

            <script>
                document.querySelector('#DELETE_FILE_FORM<?php echo $i ?>').addEventListener('submit', function (e) {
                    var form = this;
                    e.preventDefault();
                    swal({
                            title: "Delete file?",
                            text: "File cannot be recovered if deleted!",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: '#DD6B55',
                            confirmButtonText: 'Yes, I am sure!',
                            cancelButtonText: "No, cancel it!",
                            closeOnConfirm: false,
                            closeOnCancel: false
                        },
                        function (isConfirm) {
                            if (isConfirm) {
                                swal({
                                    title: 'Deleted!',
                                    text: 'File deleted!',
                                    type: 'success'
                                }, function () {
                                    form.submit();
                                });
                            } else {
                                swal("Cancelled", "No files were deleted", "error");
                            }
                        });
                });
            </script>

            <?php
            } ?>

        </td>
    </tr>
    </thead>

</table>
