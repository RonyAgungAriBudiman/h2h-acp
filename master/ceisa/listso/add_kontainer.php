<?php session_start();

include_once "../../../sqlLib.php";
$sqlLib = new sqlLib();

if ($_POST['seqbb'] == "") $_POST['seqbb'] = $_GET['seqbb'];

if (isset($_POST["simpan"])) {

    $sql = "INSERT INTO BC_KONTAINER_TMP (NomorKontiner, KodeUkuranKontainer, KodeJenisKontainer, KodeTipeKontainer) 
                    VALUES ('" . $_POST["nomorkontainer"] . "','" . $_POST["kodeukurankontainer"] . "', '" . $_POST["kodejeniskontainer"] . "',
                            '" . $_POST["kodetipekontainer"] . "')";
    $save = $sqlLib->insert($sql);
    if ($save == "1") {
        $alert = '0';
        $note = "Proses simpan berhasil!!";
    } else {
        $alert = '1';
        $note = "Proses simpan gagal!!";
    }
}

if (isset($_POST["delete"])) {
    $sql = "DELETE FROM BC_KONTAINER_TMP WHERE SeqKontainer = '" . $_POST['seqkontainer'] . "'";
    $run = $sqlLib->delete($sql);
    if ($run == "1") {
        $alert = '0';
        $note = "Proses delete berhasil!!";
        unset($_POST);
    } else {
        $alert = '1';
        $note = "Proses delete gagal!!";
    }
}

if (isset($_POST["update"])) {

    $sql = "UPDATE BC_KONTAINER_TMP 
            SET NomorKontiner = '" . $_POST["nomorkontainer"] . "',
                KodeUkuranKontainer= '" . $_POST["kodeukurankontainer"] . "',
                KodeJenisKontainer= '" . $_POST["kodejeniskontainer"] . "',
                KodeTipeKontainer= '" . $_POST["kodetipekontainer"] . "'
                WHERE SeqKontainer = '" . $_POST['seqkontainer'] . "'";
    $run = $sqlLib->update($sql);
    if ($run == "1") {
        $alert = '0';
        $note = "Proses update berhasil!!";
    } else {
        $alert = '1';
        $note = "Proses update gagal!!";
    }
}

if (isset($_POST["tutup"])) {
?>
    <script>
        window.opener.document.getElementById("form-transaksi").submit();
        window.close();
    </script>
<?php
}

if (isset($_POST["edit"])) {
    $sql_tarif = "SELECT SeqKontainer, NomorKontiner, KodeUkuranKontainer, KodeJenisKontainer, KodeTipeKontainer
                    FROM BC_KONTAINER_TMP
                    WHERE SeqKontainer = '" . $_POST["seqkontainer"] . "' ";
    $data_datif = $sqlLib->select($sql_tarif);
    $_POST["nomorkontainer"] = $data_datif[0]['NomorKontiner'];
    $_POST["kodeukurankontainer"] = $data_datif[0]['KodeUkuranKontainer'];
    $_POST["kodejeniskontainer"] = $data_datif[0]['KodeJenisKontainer'];
    $_POST["kodetipekontainer"] = $data_datif[0]['KodeTipeKontainer'];
    $_POST["seqkontainer"] = $data_datif[0]['SeqKontainer'];
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Dokumen</title>
    <!-- General CSS Files -->
    <link rel="stylesheet" href="../../../assets/modules/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../../assets/modules/fontawesome/css/all.min.css">

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="../../../assets/modules/bootstrap-daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="../../../assets/modules/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">
    <link rel="stylesheet" href="../../../assets/modules/select2/dist/css/select2.min.css">
    <link rel="stylesheet" href="../../../assets/modules/jquery-selectric/selectric.css">
    <link rel="stylesheet" href="../../../assets/modules/bootstrap-timepicker/css/bootstrap-timepicker.min.css">
    <link rel="stylesheet" href="../../../assets/modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.css">

    <!-- Template CSS -->
    <link rel="stylesheet" href="../../../assets/css/style.css">
    <link rel="stylesheet" href="../../../assets/css/components.css">
</head>


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
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Nomor Kontainer</label>
                        <div class="col-sm-8">
                            <input type="text" name="nomorkontainer" id="nomorkontainer" class="form-control" value="<?php echo $_POST["nomorkontainer"] ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Ukuran Kontainer</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="kodeukurankontainer">
                                <option value="">-Pilih-</option>
                                <?php
                                $sql_uk = "SELECT KodeUkuranKontainer, UkuranKontainer FROM ms_ukuran_kontainer WHERE UkuranKontainer !='' ";
                                $data_uk = $sqlLib->select($sql_uk);
                                foreach ($data_uk as $row_uk) { ?>
                                    <option value="<?php echo $row_uk['KodeUkuranKontainer'] ?>" <?php if ($_POST['kodeukurankontainer'] == $row_uk['KodeUkuranKontainer']) {
                                                                                                        echo "selected";
                                                                                                    } ?>><?php echo $row_uk['UkuranKontainer'] ?> </option><?php } ?>

                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Jenis Kontainer</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="kodejeniskontainer">
                                <option value="">-Pilih-</option>
                                <?php
                                $sql_uk = "SELECT KodeJenisKontainer, JenisKontainer FROM ms_jenis_kontainer WHERE JenisKontainer !='' ";
                                $data_uk = $sqlLib->select($sql_uk);
                                foreach ($data_uk as $row_uk) { ?>
                                    <option value="<?php echo $row_uk['KodeJenisKontainer'] ?>" <?php if ($_POST['kodejeniskontainer'] == $row_uk['KodeJenisKontainer']) {
                                                                                                    echo "selected";
                                                                                                } ?>><?php echo $row_uk['JenisKontainer'] ?> </option><?php } ?>

                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Tipe Kontainer</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="kodetipekontainer">
                                <option value="">-Pilih-</option>
                                <?php
                                $sql_uk = "SELECT KodeTipeKontainer, TipeKontainer FROM ms_tipe_kontainer WHERE TipeKontainer !='' ";
                                $data_uk = $sqlLib->select($sql_uk);
                                foreach ($data_uk as $row_uk) { ?>
                                    <option value="<?php echo $row_uk['KodeTipeKontainer'] ?>" <?php if ($_POST['kodetipekontainer'] == $row_uk['KodeTipeKontainer']) {
                                                                                                    echo "selected";
                                                                                                } ?>><?php echo $row_uk['TipeKontainer'] ?> </option><?php } ?>




                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <?php
                            if ($_POST['seqkontainer'] != '') {
                            ?>
                                <input type="hidden" name="seqkontainer" Value="<?php echo $_POST["seqkontainer"]; ?>">

                                <input type="submit" class="btn btn-success" name="update" Value="Update">
                                <input type="submit" class="btn btn-danger" name="delete" Value="Delete">
                                <input type="submit" class="btn btn-info" name="tutup" Value="Tutup">
                            <?php

                            } else {
                            ?>
                                <input type="submit" class="btn btn-primary" name="simpan" Value="Simpan">
                                <input type="submit" class="btn btn-info" name="tutup" Value="Tutup">
                            <?php
                            }
                            ?>
                        </div>
                        <div class="col-sm-6">
                            &nbsp;
                        </div>
                    </div>
            </form>
            <div class="form-group row">
                <div class="col-sm-12">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Nomor Kontainer</th>
                                <th scope="col">Ukuran Kontainer</th>
                                <th scope="col">Jenis Kontainer</th>
                                <th scope="col">Tipe Kontainer</th>
                                <th scope="col">Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $sql_kon = "SELECT a.SeqKontainer, a.NomorKontiner, a.KodeUkuranKontainer, a.KodeJenisKontainer, a.KodeTipeKontainer,
                                            b.UkuranKontainer, c.JenisKontainer, d.TipeKontainer
                                            FROM BC_KONTAINER_TMP a 
                                            LEFT JOIN ms_ukuran_kontainer b on b.KodeUkuranKontainer = a.KodeUkuranKontainer
                                            LEFT JOIN ms_jenis_kontainer c on c.KodeJenisKontainer = a.KodeJenisKontainer
                                            LEFT JOIN ms_tipe_kontainer d on d.KodeTipeKontainer = a.KodeTipeKontainer";
                            $data_kon = $sqlLib->select($sql_kon);
                            foreach ($data_kon as $row_kon) {
                            ?>
                                <tr>
                                    <td><?php echo $no; ?></td>
                                    <td><?php echo $row_kon['NomorKontiner'] ?></td>
                                    <td><?php echo $row_kon['UkuranKontainer'] ?></td>
                                    <td><?php echo $row_kon['JenisKontainer'] ?></td>
                                    <td><?php echo $row_kon['TipeKontainer'] ?></td>
                                    <td>
                                        <form method="post" id="form" autocomplete="off" enctype="multipart/form-data">
                                            <input type="hidden" class="form-control" name="seqkontainer" value="<?php echo $row_kon['SeqKontainer'] ?>">
                                            <input type="submit" class="btn btn-success" name="edit" Value="Edit">
                                        </form>
                                    </td>
                                </tr>
                            <?php

                            }
                            $no++;
                            ?>
                            <input type="hidden" name="jmltarif" id="jmltarif" value="<?php echo ($no - 1); ?>">
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>
</div>
</div>


<!--AUTO COMPLETE-->
<link rel="stylesheet" href="../../../assets/css/jquery-ui.css" />
<script src="../../../assets/js/jquery-1.12.4.js"></script>
<script src="../../../assets/js/jquery-ui.js"></script>

<!-- General JS Scripts 
  <script src="../../../assets/modules/jquery.min.js"></script>-->
<script src="../../../assets/modules/popper.js"></script>
<script src="../../../assets/modules/tooltip.js"></script>
<script src="../../../assets/modules/bootstrap/js/bootstrap.min.js"></script>
<script src="../../../assets/modules/nicescroll/jquery.nicescroll.min.js"></script>
<script src="../../../assets/modules/moment.min.js"></script>
<script src="../../../assets/js/stisla.js"></script>

<!-- JS Libraies
  <script src="../../../assets/modules/cleave-js/dist/cleave.min.js"></script>
  <script src="../../../assets/modules/cleave-js/dist/addons/cleave-phone.us.js"></script>
  <script src="../../../assets/modules/jquery-pwstrength/jquery.pwstrength.min.js"></script> -->
<script src="../../../assets/modules/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="../../../assets/modules/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
<script src="../../../assets/modules/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
<script src="../../../assets/modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>
<script src="../../../assets/modules/select2/dist/js/select2.full.min.js"></script>
<script src="../../../assets/modules/jquery-selectric/jquery.selectric.min.js"></script>

<!-- Page Specific JS File 
  <script src="../../../assets/js/page/forms-advanced-forms.js"></script>-->

<!-- Template JS File -->
<script src="../../../assets/js/scripts.js"></script>
<script src="../../../assets/js/custom.js"></script>

<script>
    $(document).ready(function() {
        var ac_config = {
            source: "../../../json/satuan.php",
            select: function(event, ui) {
                $("#kodesatuan").val(ui.item.id);
                $("#namasatuan").val(ui.item.namasatuan);
            },
            focus: function(event, ui) {
                $("#kodesatuan").val(ui.item.id);
                $("#namasatuan").val(ui.item.namasatuan);
            },
            minLength: 1
        };
        $("#namasatuan").autocomplete(ac_config);
    });

    $(document).ready(function() {
        var ac_config = {
            source: "../../../json/kantor.php",
            select: function(event, ui) {
                $("#kodekantor").val(ui.item.id);
                $("#namakantor").val(ui.item.namakantor);
            },
            focus: function(event, ui) {
                $("#kodekantor").val(ui.item.id);
                $("#namakantor").val(ui.item.namakantor);
            },
            minLength: 1
        };
        $("#namakantor").autocomplete(ac_config);
    });
</script>

<body>
</body>

</html>