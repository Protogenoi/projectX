<?php

if (isset($newClientResponse['dob'])) {
    $dobAge = date("Y-m-d") - $newClientResponse['dob'];
}

?>

<div class="container">

    <div class="col-md-4">
        <h3><span class="label label-primary">Client Details</span></h3>

        <p>
        <div class="input-group">
            <input type="text" class="form-control" id="FullName" name="FullName"
                   value="<?php if (isset($newClientResponse['clientName'])) {
                       echo $newClientResponse['clientName'];
                   } ?> "
                   readonly>
            <span class="input-group-btn">
                                    <a href="#" data-toggle="tooltip" data-placement="right" title="Client Name"><button
                                            type="button" class="btn btn-default"><span
                                                class="glyphicon glyphicon-info-sign"></span></button></a> </span>
        </div>
        </p>

        <p>
        <div class="input-group">
            <input type="text" class="form-control" id="dob" name="dob"
                   value="<?php if (isset($newClientResponse['dob'])) {
                       echo $newClientResponse['dob'] . " ($dobAge)";
                   } ?>" readonly>
            <span class="input-group-btn">
                                <a href="#" data-toggle="tooltip" data-placement="right" title="Date of Birth"><button
                                        type="button" class="btn btn-default"><span
                                            class="glyphicon glyphicon-calendar"></span></button></a>

                            </span>
        </div>
        </p>

        <?php if (!empty($newClientResponse['email'])) { ?>

            <p>
            <div class="input-group">
                <input class="form-control" type="email" id="email" name="email"
                       value="<?php echo $newClientResponse['email'] ?>" readonly>
                <span class="input-group-btn">
                                    <a href="#" data-toggle="tooltip" data-placement="right" title="Send Email"><button
                                            type="button" data-toggle="modal" data-target="#email1pop"
                                            class="btn btn-success"><span
                                                class="glyphicon glyphicon-envelope"></span></button></a>

                                </span>
            </div>
            </p>

        <?php } ?>


        <br>

    </div>

    <div class="col-md-4">

    </div>

    <div class="col-md-4">
        <h3><span class="label label-primary">Contact Details</span></h3>

        <p>
        <div class="input-group">
            <input class="form-control" type="tel" id="phoneNumber" name="phoneNumber"
                   value="<?php if (isset($newClientResponse['phoneNumber'])) {
                       echo $newClientResponse['phoneNumber'];
                   } ?>" <?php if (isset($NUMBER_BAD) && $NUMBER_BAD == '1') {
                echo "style='background:red'";
            } ?> readonly>
            <span class="input-group-btn">
                                <button type="button" data-toggle="modal" data-target="#smsModal"
                                        class="btn btn-success"><span
                                        class="glyphicon glyphicon-earphone"></span></button>

                            </span>
        </div>
        </p>

        <?php if (!empty($newClientResponse['altNumber'])) { ?>

            <p>
            <div class="input-group">
                <input class="form-control" type="tel" id="altNumber" name="altNumber"
                       value="<?php echo $newClientResponse['altNumber']; ?>" readonly>
                <span class="input-group-btn">
                                    <a href="#" data-toggle="tooltip" data-placement="right" title="Call/SMS"><button
                                            type="button" data-toggle="modal" data-target="#smsModalalt"
                                            class="btn btn-success"><span
                                                class="glyphicon glyphicon-earphone"></span></button></a>

                                </span>
            </div>
            </p>

        <?php } ?>

        <div class="input-group">
            <input class="form-control" type="text" id="address1" name="address1"
                   value="<?php echo $newClientResponse['address1']; ?>" readonly>
            <span class="input-group-btn">
                                <a href="#" data-toggle="tooltip" data-placement="right" title="Address Line 1"><button
                                        type="button" class="btn btn-default"><span
                                            class="glyphicon glyphicon-home"></span></button></a>

                            </span>
        </div>
        </p>

        <?php if (!empty($newClientResponse['address2'])) { ?>

            <p>
            <div class="input-group">
                <input class="form-control" type="text" id="address2" name="address2"
                       value="<?php echo $newClientResponse['address2']; ?>" readonly>
                <span class="input-group-btn">
                                    <a href="#" data-toggle="tooltip" data-placement="right" title="Address Line 2"><button
                                            type="button" class="btn btn-default"><span
                                                class="glyphicon glyphicon-list-alt"></span></button></a>

                                </span>
            </div>
            </p>

            <?php
        }
        if (!empty($newClientResponse['address3'])) {
            ?>

            <p>
            <div class="input-group">
                <input class="form-control" type="text" id="address3" name="address3"
                       value="<?php echo $newClientResponse['address3']; ?>" readonly>
                <span class="input-group-btn">
                                    <a href="#" data-toggle="tooltip" data-placement="right" title="Address Line 3"><button
                                            type="button" class="btn btn-default"><span
                                                class="glyphicon glyphicon-list-alt"></span></button></a>

                                </span>
            </div>
            </p>

        <?php } ?>

        <p>
        <div class="input-group">
            <input class="form-control" type="text" id="town" name="town"
                   value="<?php echo $newClientResponse['town']; ?>" readonly>
            <span class="input-group-btn">
                                <a href="#" data-toggle="tooltip" data-placement="right" title="Postal Town"><button
                                        type="button" class="btn btn-default"><span
                                            class="glyphicon glyphicon-list-alt"></span></button></a>

                            </span>
        </div>
        </p>

        <p>
        <div class="input-group">
            <input class="form-control" type="text" id="post_code" name="post_code"
                   value="<?php echo $newClientResponse['post_code'] ?>" readonly>
            <span class="input-group-btn">
                                <button class="btn btn-default"><i class="fa fa-search"></i></button>
        </div>
        </p>
        <br>

    </div>
    <br>
    <br>
    <br>
    <br>
</div>
