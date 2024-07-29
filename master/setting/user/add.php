<?php
if ($_POST['simpan']) {
    $sql_user = "SELECT UserID FROM ms_user WHERE UserID = '" . $_POST['userid'] . "' ";
    $data_user = $sqlLib->select($sql_user);
    if (Count($data_user) > 0) {
        $alert = 1;
        $note  = "Userid sudah digunakan!!";
    } else {

        $userid = $_POST['userid'];
        $password = substr(md5($userid), 0, 10);
        $sql = "INSERT INTO ms_user (UserID, Password, Nama, Aktif, Admin, idperusahaan)
                                VALUES ('" . $_POST['userid'] . "', '" . $password . "', '" . $_POST['nama'] . "','1', '0',  '" . $_POST['idperusahaan'] . "' ) ";
        $run = $sqlLib->insert($sql);

        if ($run == "1") {
            $alert = '0';
            $note = "Proses berhasil!!";
        } else {
            $alert = '1';
            $note = "Proses gagal!!";
        }
    }
}

?>

<div class="section-header">
    <h1><?php echo acakacak("decode", $_GET["p"]) ?></h1>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <form method="post" id="form" autocomplete="off" enctype="multipart/form-data">
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
                    <div class="form-group">
                        <label>UserID</label>
                        <input type="text" name="userid" class="form-control" required="required" value="<?php echo $_POST["userid"] ?>">

                    </div>
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" name="nama" class="form-control" required="required" value="<?php echo $_POST["nama"] ?>">

                    </div>
                    <div class="form-group">
                        <label>Perusahaan</label>
                        <select class="form-control" name="idperusahaan">
                                <?php
                                $sql_cp = "SELECT IDPerusahaan, NamaPerusahaan FROM ms_perusahaan WHERE IDPerusahaan!=''";
                                $data_cp = $sqlLib->select($sql_cp);
                                foreach ($data_cp as $row) { ?>
                                    <option value="<?php echo $row['IDPerusahaan'] ?>" 
                                        <?php if ($_POST['idperusahaan'] == $row['IDPerusahaan']) { echo "selected";} ?>><?php echo $row['NamaPerusahaan'] ?>
                                    </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="reset" name="batal" class="btn btn-danger">Batal</button>
                    <input type="submit" class="btn btn-primary" name="simpan" Value="Simpan">
                </div>


            </form>
        </div>
    </div>
</div>