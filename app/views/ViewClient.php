<?php

if (isset($newClientResponse['dob'])) {
    $dobAge = date("Y-m-d") - $newClientResponse['dob'];
}

if (isset($newClientResponse['dob2'])) {
    $dob2Age = date("Y-m-d") - $newClientResponse['dob2'];
}

?>

<div class="container">

    <div class="col-md-4">
        <h3><span class="label label-primary">Client Details</span></h3>

        <p>
        <div class="input-group">
            <input type="text" class="form-control" id="FullName" name="FullName"
                   value="<?php if (isset($newClientResponse['title'])) {
                       echo $newClientResponse['title'];
                   } ?> <?php if (isset($newClientResponse)) {
                       echo $newClientResponse['first_name'];
                   } ?> <?php if (isset($newClientResponse['last_name'])) {
                       echo $newClientResponse['last_name'];
                   } ?>"
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

        <?php if (!empty($newClientResponse['first_name2'])) { ?>

            <h3><span class="label label-primary">Client Details (2)</span></h3>

            <p>
            <div class="input-group">
                <input type="text" class="form-control" id="FullName2" name="FullName2"
                       value="<?php echo $newClientResponse['title2']; ?> <?php echo $newClientResponse['first_name2'] ?> <?php echo $newClientResponse['last_name2'] ?>"
                       readonly>
                <span class="input-group-btn">
                                        <a href="#" data-toggle="tooltip" data-placement="right" title="Client Name"><button
                                                type="button" class="btn btn-default"><span
                                                    class="glyphicon glyphicon-info-sign"></span></button></a>
                                </span>
            </div>
            </p>

            <p>
            <div class="input-group">
                <input type="text" class="form-control" id="dob2" name="dob2"
                       value="<?php echo $newClientResponse['dob2'] . " ($dob2Age)"; ?>" readonly>
                <span class="input-group-btn">
                                    <a href="#" data-toggle="tooltip" data-placement="right" title="Date of Birth"><button
                                            type="button" class="btn btn-default"><span
                                                class="glyphicon glyphicon-calendar"></span></button></a>

                                </span>
            </div>
            </p>
            <?php if (!empty($newClientResponse['email2'])) { ?>
                <p>
                <div class="input-group">
                    <input class="form-control" type="email" id="email2" name="email2"
                           value="<?php echo $newClientResponse['email2']; ?>" readonly>
                    <span class="input-group-btn">
                                        <a href="#" data-toggle="tooltip" data-placement="right" title="Send Email"><button
                                                type="button" data-toggle="modal" data-target="#email2pop"
                                                class="btn btn-success"><span
                                                    class="glyphicon glyphicon-envelope"></span></button></a>

                                    </span>
                </div>
                </p>

                <?php
            }
        }
        ?>

    </div>

    <div class="col-md-4">
        <h3><span class="label label-primary">Contact Details</span></h3>

        <p>
        <div class="input-group">
            <input class="form-control" type="tel" id="phone_number" name="phone_number"
                   value="<?php if (isset($newClientResponse['phone_number'])) {
                       echo $newClientResponse['phone_number'];
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

        <?php if (!empty($newClientResponse['alt_number'])) { ?>

            <p>
            <div class="input-group">
                <input class="form-control" type="tel" id="alt_number" name="alt_number"
                       value="<?php echo $newClientResponse['alt_number']; ?>" readonly>
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
