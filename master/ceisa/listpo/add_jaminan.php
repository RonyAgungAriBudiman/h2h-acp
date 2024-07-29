<?php session_start();

include_once "../../../sqlLib.php";
$sqlLib = new sqlLib();
if (isset($_POST["simpan"])) {
    $sql = "INSERT INTO BC_JAMINAN_TMP (KodeJaminan, NomorJaminan, TanggalJaminan, NilaiJaminan, Penjamin, TanggalJatuhTempo, NomorBpj, TanggalBpj) 
            VALUES ('" . $_POST["kodejenisjaminan"] . "','" . $_POST["nomorjaminan"] . "','" . $_POST["tanggaljaminan"] . "', '" . $_POST["nilaijaminan"] . "',
                    '" . $_POST["penjamin"] . "','" . $_POST["tanggaljatuhtempo"] . "','" . $_POST["nomorbpj"] . "','" . $_POST["tanggalbpj"] . "')";
    $save = $sqlLib->insert($sql);
    if ($save == "1") {
?>
        <script>
            //window.opener.document.getElementById("kategoriid").value = '<?php echo $kategoriid ?>';
            window.opener.document.getElementById("form-transaksi").submit();
            window.close();
        </script>
    <?php
    } else {
        $alert = '1';
        $note = "Proses simpan gagal!!";
    }
}
if (isset($_POST["delete"])) {
    $sql = "DELETE FROM BC_JAMINAN_TMP WHERE SeqJ = '" . $_POST['seqj'] . "'";
    $run = $sqlLib->delete($sql);

    if ($run == "1") {
    ?>
        <script>
            //window.opener.document.getElementById("kategoriid").value = '<?php echo $kategoriid ?>';
            window.opener.document.getElementById("form-transaksi").submit();
            window.close();
        </script>
    <?php
    } else {
        $alert = '1';
        $note = "Proses delete gagal!!";
    }
}
if (isset($_POST["update"])) {

    $sql = "UPDATE BC_JAMINAN_TMP 
			SET KodeJaminan = '" . $_POST["kodejenisjaminan"] . "',
				NomorJaminan= '" . $_POST["nomorjaminan"] . "',
				TanggalJaminan= '" . $_POST["tanggaljaminan"] . "',
                NilaiJaminan= '" . $_POST['nilaijaminan'] . "',
                Penjamin= '" . $_POST['penjamin'] . "',
                TanggalJatuhTempo= '" . $_POST['tanggaljatuhtempo'] . "',
                NomorBpj= '" . $_POST['nomorbpj'] . "',
                TanggalBpj= '" . $_POST['tanggalbpj'] . "'
				WHERE SeqJ = '" . $_POST['seqj'] . "'";
    $run = $sqlLib->update($sql);
    if ($run == "1") {
    ?>
        <script>
            //window.opener.document.getElementById("kategoriid").value = '<?php echo $kategoriid ?>';
            //window.opener.document.getElementById("panel-body-3").value = 'panel-body-3';
            window.opener.document.getElementById("form-transaksi").submit();
            window.close();
        </script>
<?php
    } else {
        $alert = '1';
        $note = "Proses update gagal!!";
    }
}
if ($_GET['seqj'] != '') {
    $sql = "SELECT TOP 1 SeqJ, KodeJaminan, NomorJaminan, TanggalJaminan, NilaiJaminan, Penjamin, TanggalJatuhTempo, NomorBpj, TanggalBpj
            FROM BC_JAMINAN_TMP 
			WHERE SeqJ ='" . $_GET['seqj'] . "' ";
    $data = $sqlLib->select($sql);
    $_POST["kodejenisjaminan"] = $data[0]['KodeJaminan'];
    $_POST["nomorjaminan"] = $data[0]['NomorJaminan'];
    $_POST["tanggaljaminan"] = $data[0]['TanggalJaminan'];
    $_POST["nilaijaminan"] = $data[0]['NilaiJaminan'];
    $_POST["penjamin"] = $data[0]['Penjamin'];
    $_POST["tanggaljatuhtempo"] = $data[0]['TanggalJatuhTempo'];
    $_POST["nomorbpj"] = $data[0]['NomorBpj'];
    $_POST["tanggalbpj"] = $data[0]['TanggalBpj'];
    $_POST["seqj"] = $data[0]['SeqJ'];
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
                        <div class="col-sm-4">
                            <label>Jenis Jaminan</label>
                            <select name="kodejenisjaminan" class="form-control" required="required">
                                <option value=""></option>
                                <?php
                                $sql = "SELECT KodeJenisJaminan, NamaJenisJaminan
                                 		 FROM ms_jenis_jaminan WHERE KodeJenisJaminan !='' ";
                                $data = $sqlLib->select($sql);
                                foreach ($data as $row) { ?>
                                    <option value="<?php echo $row['KodeJenisJaminan'] ?>" <?php if ($_POST['kodejenisjaminan'] == $row['KodeJenisJaminan']) {
                                                                                                echo "selected";
                                                                                            } ?>>
                                        <?php echo '(' . $row['KodeJenisJaminan'] . ') ' . $row['NamaJenisJaminan'] ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label>Tanggal jatuh Tempo</label>
                            <input type="text" name="tanggaljatuhtempo" id="tanggaljatuhtempo" class="form-control datepicker" required="required" value="<?php echo $_POST["tanggaljatuhtempo"] ?>">
                        </div>
                        <div class="col-sm-4">
                            <label>Nomor Bukti Penerimaan Jaminan</label>
                            <input type="text" name="nomorbpj" id="nomorbpj" class="form-control" required="required" value="<?php echo $_POST["nomorbpj"] ?>">
                        </div>


                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label>Nomor Jaminan</label>
                            <input type="text" name="nomorjaminan" id="nomorjaminan" class="form-control" required="required" value="<?php echo $_POST["nomorjaminan"] ?>">
                        </div>
                        <div class="col-sm-4">
                            <label>Penjamin</label>
                            <input type="text" name="penjamin" id="penjamin" class="form-control" required="required" value="<?php echo $_POST["penjamin"] ?>">
                        </div>
                        <div class="col-sm-4">
                            <label>Tanggal Bukti Penerimaan Jaminan</label>
                            <input type="text" name="tanggalbpj" id="tanggalbpj" class="form-control datepicker" required="required" value="<?php echo $_POST["tanggalbpj"] ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label>Tanggal Jaminan</label>
                            <input type="text" name="tanggaljaminan" id="tanggaljaminan" class="form-control datepicker" required="required" value="<?php echo $_POST["tanggaljaminan"] ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label>Nilai Jaminan</label>
                            <input type="text" name="nilaijaminan" id="nilaijaminan" class="form-control" required="required" value="<?php echo $_POST["nilaijaminan"] ?>" placeholder="0">
                        </div>
                    </div>



                </div>
                <div class="card-footer text-right">
                    <?php
                    if ($_GET['seqj'] != '') {
                    ?>
                        <input type="hidden" name="seqj" Value="<?php echo $_POST["seqj"]; ?>">
                        <input type="submit" class="btn btn-success" name="update" Value="Update">
                        <input type="submit" class="btn btn-danger" name="delete" Value="Delete">
                    <?php

                    } else {
                    ?>
                        <input type="submit" class="btn btn-primary" name="simpan" Value="Simpan">
                        <input type="hidden" name="seqitem" Value="<?php echo $_GET["seqitem"]; ?>">
                    <?php
                    }
                    ?>
                </div>


            </form>
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