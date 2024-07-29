<?php

//if ($_SESSION["admin"] < 9) $_POST["companyid"] = $_SESSION["companyid"];


if ($_POST['simpan']) {

    $cekbox   = $_POST["cekbox"];
    $role     = $_POST["role"];
    $roleid   = date("ymdhis");

    //cek role name
    $sql_role = "SELECT RoleName FROM ms_role WHERE CompanyID = '" . $_POST["companyid"] . "'  ";
    $data_role = $sqlLib->select($sql_role);
    if (count($data_role) > 0) {

        $alert = '1';
        $note = "Maaf, Role Name sudah ada";
    } else {

        $sukses = false;
        for ($i = 1; $i <= $cekbox; $i++) {
            $cb = $_POST['chk' . $i];
            if ($cb != "") {

                $navid      = $_POST["navid" . $i];
                $nav1      = $_POST["nav1" . $i];

                $sql_save = "INSERT INTO tr_role (RoleID, NavID, Nav1) VALUES ('" . $roleid . "','" . $navid . "' ,'" . $nav1 . "' )";
                $run      = $sqlLib->insert($sql_save);
            }
        }
        $sukses = true;
        if ($sukses) {
            $sql_save = "INSERT INTO ms_role (RoleID, RoleName, CompanyID) VALUES ('" . $roleid . "','" . $role . "' ,'" . $_POST["companyid"] . "' )";
            $run_ms      = $sqlLib->insert($sql_save);
            if ($run_ms == "1") {
                $alert = '0';
                $note = "Proses simpan berhasil";
            } else {
                $sql_del_tr = "DELETE FROM tr_role WHERE RoleID ='" . $roleid . "' ";
                $del_tr     = $sqlLib->delete($sql_del_tr);

                $sql_del_ms = "DELETE FROM ms_role WHERE RoleID ='" . $roleid . "' ";
                $del_ms     = $sqlLib->delete($sql_del_ms);

                $alert = '1';
                $note = "Maaf, Proses simpan gagal";
            }
        }
    }
}

if (isset($_POST["update"])) {

    //delete tr
    $sql_del_tr = "DELETE FROM tr_role WHERE RoleID ='" . $_POST["roleid"] . "' ";
    $del_tr     = $sqlLib->delete($sql_del_tr);

    $cekbox   = $_POST["cekbox"];
    $sukses = false;
    for ($i = 1; $i <= $cekbox; $i++) {
        $cb = $_POST['chk' . $i];
        if ($cb != "") {

            $navid      = $_POST["navid" . $i];
            $nav1      = $_POST["nav1" . $i];

            $sql_save = "INSERT INTO tr_role (RoleID, NavID, Nav1) VALUES ('" . $_POST["roleid"] . "','" . $navid . "' ,'" . $nav1 . "' )";
            $run      = $sqlLib->insert($sql_save);
        }
    }
    $sukses = true;
    if ($sukses) {
        $sql_save = "UPDATE ms_role SET RoleName = '" . $_POST["role"] . "',
                                         CompanyID = '" . $_POST["companyid"] . "'
                                         WHERE RoleID = '" . $_POST["roleid"] . "' ";
        $run_ms      = $sqlLib->update($sql_save);
        if ($run_ms == "1") {
            $alert = '0';
            $note = "Proses update berhasil";
        } else {
            $alert = '1';
            $note = "Maaf, Proses update gagal";
        }
    }
}


if ($_GET['roleid'] !== '') {
    $sql_role = "SELECT a.* FROM ms_role a 
					 WHERE a.RoleID = '" . $_GET['roleid'] . "' ";
    $data_role = $sqlLib->select($sql_role);
    $_POST['companyid'] = $data_role[0]['CompanyID'];
    $_POST['role']   = $data_role[0]['RoleName'];
    $_POST['roleid']   = $data_role[0]['RoleID'];
}
?>

<div class="section-header">
    <h1><?php echo $_GET["p"] ?></h1>
</div>


<div class="row">
    <div class="col-12">
        <form method="post">
            <div class="card">
                <div class="card-body mb-0">
                    <?php
                    if ($alert == "0") {
                    ?>
                        <div class="form-group">
                            <div class="alert alert-success alert-dismissible show fade">
                                <div class="alert-body">
                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    <?php echo $note ?>
                                </div>
                            </div>
                        </div><?php
                            } else if ($alert == "1") { ?>
                        <div class="form-group">
                            <div class="alert alert-danger alert-dismissible show fade">
                                <div class="alert-body">
                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    <?php echo $note ?>
                                </div>
                            </div>
                        </div><?php
                            } ?>

                    <div class="form-row">
                        <div class="form-group col-6 mb-0">
                            <label>Role Name</label>
                            <input type="text" name="role" class="form-control" required="required" value="<?php echo $_POST["role"] ?>">
                            <input type="hidden" name="roleid" class="form-control" value="<?php echo $_POST["roleid"] ?>">
                        </div>
                        <div class="form-group col-6 mb-0">
                            <?php
                            if ($_SESSION["admin"] < 9) {
                            ?><input type="hidden" name="companyid" class="form-control" value="<?php echo $_SESSION["companyid"] ?>"><?php
                                                                                                                                    } else {
                                                                                                                                        ?>
                                <label>Company</label>
                                <select class="form-control" name="companyid">
                                    <option value="">Pilih Company</option>
                                    <?php
                                                                                                                                        $sql_user = "SELECT Company, CompanyID  FROM ms_company WHERE Company!='' AND  Aktif='1' ";
                                                                                                                                        $data_user = $sqlLib->select($sql_user);

                                                                                                                                        foreach ($data_user as $row) { ?>
                                        <option value="<?php echo $row['CompanyID'] ?>" <?php if ($_POST['companyid'] == $row['CompanyID']) {
                                                                                                                                                echo "selected";
                                                                                                                                            } ?>><?php echo $row['Company'] ?></option>
                                    <?php } ?>
                                </select>
                            <?php
                                                                                                                                    }
                            ?>

                        </div>

                    </div>
                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Module</th>
                                    <th scope="col">Menu</th>
                                    <th scope="col">Aktif</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $sql_1 = "SELECT a.NavID, a.Nav1, a.Nav2 
                                FROM ms_nav a 
                                WHERE a.Nav1 !='' ";
                                if ($_SESSION["admin"] < 9) $sql_1 .= " AND a.NavID != '2' AND a.NavID !='7' ";
                                $data_1 = $sqlLib->select($sql_1);
                                foreach ($data_1 as $row) {

                                    $sql_2 = "SELECT a.* FROM tr_role a 
                                                WHERE a.RoleID = '" . $_GET['roleid'] . "' AND  a.NavID = '" . $row['NavID'] . "' ";
                                    $data_2 = $sqlLib->select($sql_2);

                                ?>

                                    <tr>
                                        <th scope="row"><?php echo $no ?></th>
                                        <td><?php echo $row['Nav1'] ?></td>
                                        <td><?php echo $row['Nav2'] ?></td>
                                        <td><input type="checkbox" name="chk<?php echo $no ?>" <?php if ($data_2[0]['NavID'] != "") {
                                                                                                    echo "checked";
                                                                                                } ?>>
                                            <input type="hidden" name="navid<?php echo $no ?>" value="<?php echo $row['NavID'] ?>">
                                            <input type="hidden" name="nav1<?php echo $no ?>" value="<?php echo $row['Nav1'] ?>">
                                        </td>
                                    </tr>

                                <?php
                                    $no++;
                                }
                                ?>
                                <input type="hidden" name="cekbox" value="<?php echo $no; ?>">

                            </tbody>

                        </table>
                    </div>

                </div>
                <div class="card-footer">
                    <?php if ($_GET['roleid'] != "") { ?>
                        <input type="submit" class="btn btn-primary" name="update" Value="Update">
                    <?php } else { ?>
                        <input type="submit" class="btn btn-primary" name="simpan" Value="Simpan">
                    <?php } ?>
                </div>
            </div>
        </form>
    </div>
</div>