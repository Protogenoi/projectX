<ul class="nav nav-pills">
    <li class="active"><a data-toggle="pill" href="#home">Client</a></li>
    <li><a data-toggle="pill" href="#menu4">Timeline <span class="badge alert-warning">

                        <?php
                        $database = new Database();
                        $database->query("SELECT count(id) AS badge FROM potentialClientNote WHERE client_id =:CID");
                        $database->bind(':CID', $search);
                        $row = $database->single();
                        echo htmlentities($row['badge']);
                        ?>
                    </span></a>
    </li>


    <?php if (in_array($hello_name, $Level_10_Access, true)) { ?>
        <li><a data-toggle="pill" href="#TRACKING">Tracking</a></li>
    <?php } ?>

    <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Settings <span class="caret"></span></a>
        <ul class="dropdown-menu">
            <div class="list-group">
                <li><a class="list-group-item"
                       href="/addon/Trackers/editClient.php?EXECUTE=1&CID=<?php echo $search ?>"><i
                            class="fa fa-user-check fa-fw"></i> &nbsp; Edit Client</a></li>
                <?php if (in_array($hello_name, $Level_3_Access, true)) { ?>
                    <li><a class="list-group-item"
                           href="/app/AddClient.php?EXECUTE=1&CID=<?php echo $search ?>"><i
                                class="fa fa-user-check fa-fw"></i> &nbsp; Convert Client</a></li>
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

</ul>
