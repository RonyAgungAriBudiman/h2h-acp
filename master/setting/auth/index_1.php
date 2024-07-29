<?php
if (isset($_POST['simpan'])) {

    $cekbox     = $_POST["cekbox"];
    $sql_del    = "DELETE FROM ms_privilege WHERE UserID ='" . $_POST['userid'] . "'  AND Nav1 ='" . $_POST['module'] . "'  ";
    $run_del    = $sqlLib->delete($sql_del);

    $sukses = false;
    for ($i = 1; $i <= $cekbox; $i++) {
        $cb = $_POST['chk' . $i];
        if ($cb != "") {

            $navid      = $_POST["navid" . $i];

            $sql_save = "INSERT INTO ms_privilege (NavID, UserID, Nav1) VALUES ('" . $navid . "','" . $_POST['userid'] . "' ,'" . $_POST['module'] . "' )";
            $run      = $sqlLib->insert($sql_save);
        }
    }
    $sukses = true;

    if ($sukses) {
        $alert = '0';
        $note = "Proses simpan berhasil";
    } else {
        $alert = '1';
        $note = "Maaf, Proses simpan gagal";
    }
}
?>
<div class="section-header">
    <h1><?php echo acakacak("decode", $_GET["p"]) ?></h1>
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
                            <label>User ID</label>
                            <select class="form-control" name="userid" onchange="submit();">
                                <option value="">Pilih User ID</option>
                                <?php
                                $sql_user = "SELECT UserID FROM ms_user";
                                $data_user = $sqlLib->select($sql_user);
                                foreach ($data_user as $row) { ?>
                                    <option value="<?php echo $row['UserID'] ?>" <?php if ($_POST['userid'] == $row['UserID']) {
                                                                                        echo "selected";
                                                                                    } ?>><?php echo $row['UserID'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group col-6 mb-0">
                            <label>Modul</label>
                            <select class="form-control" name="nav" onchange="submit();">
                                <option value="">Pilih Modul</option>
                                <?php
                                $sql_modul = "SELECT DISTINCT Nav1 FROM ms_nav";
                                $data_modul = $sqlLib->select($sql_modul);
                                foreach ($data_modul as $nav) { ?>
                                    <option value="<?php echo $nav['Nav1'] ?>" <?php if ($_POST['nav'] == $nav['Nav1']) {
                                                                                    echo "selected";
                                                                                } ?>><?php echo $nav['Nav1'] ?></option>
                                <?php } ?>
                            </select>
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
                                $sql_1 = "SELECT a.NavID, a.Nav1, a.Nav2 FROM ms_nav a WHERE a.Nav1 ='" . $_POST['nav'] . "' ";
                                $data_1 = $sqlLib->select($sql_1);
                                foreach ($data_1 as $row) {
                                    $sql_2 = "SELECT a.NavID, a.UserID FROM ms_privilege a WHERE a.NavID = '" . $row['NavID'] . "' AND a.UserID = '" . $_POST['userid'] . "' ";
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
                                        </td>
                                    </tr>

                                <?php
                                    $no++;
                                }
                                ?>
                                <input type="hidden" name="cekbox" value="<?php echo $no; ?>">
                                <input type="hidden" name="module" value="<?php echo $_POST['nav']; ?>">
                                <input type="hidden" name="user" value="<?php echo $_POST['userid']; ?>">
                            </tbody>

                        </table>
                    </div>

                </div>
                <div class="card-footer">
                    <input type="submit" class="btn btn-primary" name="simpan" Value="Simpan">
                </div>
            </div>
        </form>
    </div>
</div>