<?php

?>

<div class="section-header">
    <h1><?php echo acakacak("decode", $_GET["p"]) ?> </h1>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <form method="post" id="form" autocomplete="off" enctype="multipart/form-data">
                <div class="card-header">

                    <h4>Data Authority Role </h4>
                    <a href="index.php?m=<?php echo acakacak("encode", "setting/auth") ?>&sm=<?php echo acakacak("encode", "add") ?>&p=<?php echo acakacak("decode", $_GET["p"]) ?>">
                        <button type="button" class="btn btn-info" style="margin-right: 10px;"><i class="fa fa-plus"> </i> Role</button></a>


                </div>
            </form>
            <div class="card-body">
                <?php
                if ($alert == "0") {
                ?> <div class="form-group">
                        <div class="alert alert-success alert-dismissible show fade">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                <?php echo $note ?>
                            </div>
                        </div>
                    </div><?php } else if ($alert == "1") { ?>
                    <div class="form-group">
                        <div class="alert alert-danger alert-dismissible show fade">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                <?php echo $note ?>
                            </div>
                        </div>
                    </div><?php
                        } ?>
                <div class="table-responsive">
                    <table class="table table-striped" id="table-1">
                        <thead>
                            <tr>
                                <th class="text-center">
                                    #
                                </th>
                                <th>Company</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            $no = 1;
                            $sql = "SELECT a.RoleName, a.RoleID, b.Company 
                                        FROM ms_role a
                                        LEFT JOIN ms_company b on b.CompanyID = a.CompanyID
                                        WHERE Company !='' ";


                            if ($_SESSION["admin"] < 9) $sql .= " AND a.CompanyID = '" .  $_SESSION["companyid"] . "' ";
                            //echo $sql;
                            $data = $sqlLib->select($sql);
                            foreach ($data as $row) {

                            ?>
                                <tr>
                                    <td><?php echo $no ?></td>
                                    <td><?php echo $row['Company'] ?></td>
                                    <td><?php echo $row['RoleName'] ?></td>

                                    <td>
                                        <form method="post">


                                            <a href="index.php?m=<?php echo acakacak("encode", "setting/auth") ?>&sm=<?php echo acakacak("encode", "add") ?>&p=<?php echo acakacak("decode", $_GET["p"]) ?>&roleid=<?php echo $row["RoleID"] ?>">
                                                <button type="button" class="btn btn-success"><i class="fa fa-edit"> </i> Edit</button>
                                            </a>
                                        </form>
                                    </td>
                                </tr>
                            <?php $no++;
                            } ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>