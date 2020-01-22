<ul class="nav nav-pills">
    <li class="active"><a data-toggle="pill" href="#home">Client</a></li>
    <li><a data-toggle="pill" href="#menu4">Timeline <span class="badge alert-warning">

                        <?php
                        $database = new Database();
                        $database->query("SELECT count(note_id) AS badge FROM client_note WHERE client_id =:CID");
                        $database->bind(':CID', $search);
                        $row = $database->single();
                        echo htmlentities($row['badge']);
                        ?>
                    </span></a>
    </li>
    <?php if ($ffcallbacks == 1) { ?>
        <li><a data-toggle='modal' data-target='#CK_MODAL'>Callbacks</a></li>
    <?php } ?>
    <li><a data-toggle="pill" href="#menu2">Files & Uploads <span class="badge alert-warning">

                        <?php
                        $database->query("SELECT count(id) AS badge FROM tbl_uploads WHERE file LIKE :CID");
                        $database->bind(':CID', $likesearch);
                        $filesuploaded = $database->single();
                        echo htmlentities($filesuploaded['badge']);
                        ?>
                    </span></a>
    </li>
    <?php if (in_array($hello_name, $Level_10_Access, true)) {
        if (isset($ffinancials) && $ffinancials == 1) { ?>
            <li><a data-toggle="pill" href="#menu3">Financial</a></li>
        <?php }
    }
    if (in_array($hello_name, $Level_9_Access, true)) { ?>
        <li><a data-toggle="pill" href="#TRACKING">Tracking</a></li>
    <?php } ?>

    <li><a data-toggle="pill" href="#PADTAB">Pad</a></li>

    <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Add Policy <span class="caret"></span></a>
        <ul class="dropdown-menu">
            <?php

            if (in_array($hello_name, $Level_3_Access, true)) {

                foreach ($insurerAdlLinks as $ADLInsurer) {

                    if ($ADLInsurer['Active'] == 'Yes') {

                        ?>
                        <li><a class="list-group-item"
                               href="<?php echo $ADLInsurer['Link']; ?>"><?php echo $ADLInsurer['Insurer']; ?></a>
                        </li>
                        <?php

                    }
                }

                if (isset($HAS_NEW_VIT_POL) && $HAS_NEW_VIT_POL = 1) { ?>
                    <li><a class="list-group-item"
                           href="/addon/Life/Insurers/Vitality/add_income_benefit.php?EXECUTE=1&CID=<?php echo $search; ?>">Vitality
                            Income Benefit</a></li>
                <?php }
            } ?>

        </ul>
    </li>

    <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Settings <span class="caret"></span></a>
        <ul class="dropdown-menu">
            <div class="list-group">
                <?php if (in_array($hello_name, $Level_3_Access, true)) { ?>
                    <li><a class="list-group-item"
                           href="/addon/Life/EditClient.php?search=<?php echo $search ?>&life"><i
                                class="far far fa-edit fa-fw"></i> &nbsp; Edit Client</a></li>
                <?php }
                if (in_array($hello_name, $Level_10_Access, true) && $hello_name == 'Michael') { ?>
                    <li><a class="list-group-item"
                           href="/app/Client.php?search=<?php echo $search ?>&editNotes=1"><i
                                class="fa fa-history fa-fw"></i> &nbsp; Edit Timeline</a></li>
                <?php } ?>
                <?php if (in_array($hello_name, $Level_10_Access, true)) { ?>
                    <li><a class="list-group-item"
                           href="/app/admin/deleteclient.php?search=<?php echo $search ?>&life"><i
                                class="fa fa-trash fa-fw"></i> &nbsp; Delete Client</a></li>
                <?php } ?>

            </div>
        </ul>
    </li>
    <li id='SHOW_ALERTS'><a data-toggle="pill"><i class='fa fa-eye-slash fa-fw'></i> Show Alerts</a></li>
    <li id='HIDE_ALERTS'><a data-toggle="pill"><i class='fa fa-eye-slash fa-fw'></i> Hide Alerts</a></li>

</ul>
