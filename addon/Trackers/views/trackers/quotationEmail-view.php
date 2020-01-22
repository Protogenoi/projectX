<div id="quotationEmail" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Quotation Email</h4>
            </div>
            <div class="modal-body">

                <div class="col-md-12">

                    <form class="AddClient"
                          method="post"
                          action="/addon/sendGrid/php/sendCloserEmail.php?EXECUTE=2&cbClient=2"
                          enctype="multipart/form-data">

                        <div class="form-group">
                            <label for="recipient">Client name:</label>
                            <input type="text" name="recipient" id="recipient" class="form-control"
                                   placeholder="Displayed on client email" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="message">Email Templates</label>
                            <select name="message" id="message" class="form-control" required>
                                <option value="">Select...</option>
                                <option value="Life insurance quotation">Life insurance quotation</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="insurer">Insurer:</label>
                            <select class="form-control" name="insurer" id="insurer"
                                    required>
                                <option value="">Select insurer...</option>
                                <option value="Royal London">Royal London</option>
                                <option value="LV">LV</option>
                                <option value="One Family">One Family</option>
                                <option value="Aegon">Aegon</option>
                                <option value="HSBC">HSBC</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="attachment1">Attachment:</label>
                            <input type="file" name="fileToUpload" id="fileToUpload" class="form-control"
                                   required>
                        </div>

                        <br>
                        <br>
                        <div style="text-align: center;">
                            <button type="submit" class="btn btn-primary "><span
                                    class="glyphicon glyphicon-envelope"></span> Send Email Template
                            </button>
                        </div>
                    </form>

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal"><span
                        class="glyphicon glyphicon-remove-sign"></span>Close
                </button>
            </div>
        </div>
    </div>
</div>
