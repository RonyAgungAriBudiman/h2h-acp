<?php session_start();

include_once "../../../sqlLib.php";
$sqlLib = new sqlLib();
if (isset($_POST["simpan"])) {
    $cifrupiah = $_POST["cif"] * $_POST["ndpbm"];
    $sql = "INSERT INTO BC_BAHAN_BAKU_TMP (SeqItem,KodeAsalBahanBaku,HS,KodeBarang,Uraian,Cif,NDPBM,CifRupiah,HargaPenyerahan,
                JumlahSatuan,KodeDokumenAsal,KodeKantorAsal,KodeSatuan,Merek,NomorAjuAsal,NomorDaftarAsal,TanggalDaftarAsal,SpesifikasiLain,Tipe,Ukuran) 
            VALUES ('" . $_POST["seqitem"] . "','1', '" . $_POST["hs"] . "', '" . $_POST["kodebarang"] . "', '" . $_POST["uraian"] . "',
            '" . $_POST["cif"] . "','" . $_POST["ndpbm"] . "','" . $cifrupiah . "','" . $_POST["hargapenyerahan"] . "','" . $_POST["jumlahsatuan"] . "',
            '" . $_POST["kodedokumenasal"] . "','" . $_POST["kodekantoasal"] . "','" . $_POST["kodesatuan"] . "','" . $_POST["merek"] . "',
            '" . $_POST["nomorajuasal"] . "','" . $_POST["daftarnomorasal"] . "','" . $_POST["tanggaldaftarasal"] . "','" . $_POST["spesifikasilain"] . "',
            '" . $_POST["tipe"] . "','" . $_POST["ukuran"] . "')";
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
    $sql = "DELETE FROM BC_BAHAN_BAKU_TMP WHERE SeqBB = '" . $_POST['seqbb'] . "'";
    $run = $sqlLib->delete($sql);
    if ($run == "1") {
        $sql2 = "DELETE FROM BC_BAHAN_BAKU_TARIF_TMP WHERE SeqBB = '" . $_POST['seqbb'] . "'";
        $run2 = $sqlLib->delete($sql2);
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

    $cifrupiah = $_POST["cif"] * $_POST["ndpbm"];
    $sql = "UPDATE BC_BAHAN_BAKU_TMP 
			SET KodeBarang = '" . $_POST["kodebarang"] . "',
				Hs= '" . $_POST["hs"] . "',
				Uraian= '" . $_POST["uraian"] . "',
                Cif= '".$_POST['cif']."',
                Ndpbm= '".$_POST['ndpbm']."',
                CifRupiah= '".$cifrupiah."',
                HargaPenyerahan= '".$_POST['hargapenyerahan']."',

                JumlahSatuan= '".$_POST['jumlahsatuan']."',
                KodeDokumenAsal= '".$_POST['kodedokumenasal']."',
                KodeKantorAsal= '".$_POST['kodekantorasal']."',
                KodeSatuan= '".$_POST['kodesatuan']."',
                Merek= '".$_POST['merek']."',
                NomorAjuAsal= '".$_POST['nomorajuasal']."',
                NomorDaftarAsal= '".$_POST['nomordaftarasal']."',
                TanggalDaftarAsal= '".$_POST['tanggaldaftarasal']."',
                SpesifikasiLain= '".$_POST['spesifikasilain']."',
                Tipe= '".$_POST['tipe']."',
                Ukuran= '".$_POST['ukuran']."'
				WHERE SeqBB = '" . $_POST['seqbb'] . "'";
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

if ($_GET['seqbb'] != '') {
    $sql = "SELECT TOP 1 a.*, c.NamaKantor, b.NamaSatuanBarang
            FROM BC_BAHAN_BAKU_TMP a
            LEFT JOIN ms_satuan_barang b on b.KodeSatuanBarang = a.KodeSatuan
            LEFT JOIN ms_kantor c on c.KodeKantor = a.KodeKantorAsal
			WHERE a.SeqBB ='" . $_GET['seqbb'] . "' ";
    $data = $sqlLib->select($sql);
    $_POST["hs"] = $data[0]['Hs'];
    $_POST["kodebarang"] = $data[0]['KodeBarang'];
    $_POST["uraian"] = $data[0]['Uraian'];
    $_POST["seqbb"] = $data[0]['SeqBB'];
    $_POST["cif"] = $data[0]['Cif'];
    $_POST["ndpbm"] = $data[0]['Ndpbm'];
    $_POST["hargapenyerahan"] = $data[0]['HargaPenyerahan'];
    $_POST["jumlahsatuan"] = $data[0]['JumlahSatuan'];
    $_POST["kodedokumenasal"] = $data[0]['KodeDokumenAsal'];
    $_POST["kodekantorasal"] = $data[0]['KodeKantorAsal'];
    $_POST["kodesatuan"] = $data[0]['KodeSatuan'];
    $_POST["merek"] = $data[0]['Merek'];
    $_POST["nomorajuasal"] = $data[0]['NomorAjuAsal'];
    $_POST["nomordaftarasal"] = $data[0]['NomorDaftarAsal'];
    $_POST["tanggaldaftarasal"] = $data[0]['TanggalDaftarAsal'];
    $_POST["spesifikasilain"] = $data[0]['SpesifikasiLain'];
    $_POST["tipe"] = $data[0]['Tipe'];
    $_POST["ukuran"] = $data[0]['Ukuran'];
    $_POST['namakantor']=$data[0]['NamaKantor'];
    $_POST['namasatuan']=$data[0]['NamaSatuanBarang'];

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
                            <label>Nomot HS</label>
                            <input type="text" name="hs" id="hs" class="form-control" required="required" value="<?php echo $_POST["hs"] ?>">
                        </div>
                        <div class="col-sm-4">
                            <label>Cif</label>
                            <input type="text" name="cif" id="cif" class="form-control" required="required" value="<?php echo $_POST["cif"] ?>" placeholder="0">
                        </div>
                        <div class="col-sm-4">
                            <label>Nomor Aju Asal</label>
                            <input type="text" name="nomorajuasal" id="nomorajuasal" class="form-control" required="required" value="<?php echo $_POST["nomorajuasal"] ?>">
                        </div>

                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label>Kode Barang</label>
                            <input type="text" name="kodebarang" id="kodebarang" class="form-control" required="required" value="<?php echo $_POST["kodebarang"] ?>">
                        </div>
                        <div class="col-sm-4">
                            <label>NDPBM</label>
                            <input type="text" name="ndpbm" id="ndpbm" class="form-control"  required="required" value="<?php echo $_POST["ndpbm"] ?>" placeholder="0">
                        </div>
                        <div class="col-sm-4">
                            <label>Kode Dokumen Asal</label>
                            <select name="kodedokumenasal" class="form-control" required="required">
                                <option value=""></option>
                                <?php 
                                 $sql = "SELECT KodeDokumen FROM ms_kode_dokumen WHERE NamaDokumen like 'BC%' AND KodeDokumen in('23','40')  ";
                                 $data=$sqlLib->select($sql);
                                 foreach ($data as $row) {
                                          ?>
                                            <option value="<?php echo $row['KodeDokumen'] ?>" <?php if($_POST['kodedokumenasal']==$row['KodeDokumen']) { echo "selected";} ?>><?php echo $row['KodeDokumen'] ?></option>
                                          <?php
                                      }     
                                ?>    
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label>Uraian</label>
                            <input type="text" name="uraian" id="uraian" class="form-control" required="required" value="<?php echo $_POST["uraian"] ?>">
                        </div>
                        <div class="col-sm-4">
                            <label>Harga Penyerahan</label>
                            <input type="text" name="hargapenyerahan" id="hargapenyerahan" class="form-control" required="required" value="<?php echo $_POST["hargapenyerahan"] ?>" placeholder="0">
                        </div>
                        <div class="col-sm-4">
                            <label>Kode Kantor Asal</label>
                            <input type="text" name="namakantor" id="namakantor" class="form-control" required="required" value="<?php echo $_POST["namakantor"] ?>">
                            <input type="hidden" name="kodekantorasal" id="kodekantor" class="form-control" required="required" value="<?php echo $_POST["kodekantorasal"] ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label>Jumlah Satuan</label>
                            <input type="text" name="jumlahsatuan" id="jumlahsatuan" class="form-control" required="required" value="<?php echo $_POST["jumlahsatuan"] ?>" placeholder="0">
                        </div>
                        
                        <div class="col-sm-4">
                            <label>Merek</label>
                            <input type="text" name="merek" id="merek" class="form-control" required="required" value="<?php echo $_POST["merek"] ?>">
                        </div>
                        <div class="col-sm-4">
                            <label>Nomor Daftar Asal</label>
                            <input type="text" name="nomordaftarasal" id="nomordaftarasal" class="form-control" required="required" value="<?php echo $_POST["nomordaftarasal"] ?>">

                        </div>
                        
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label>Satuan</label>
                            <input type="text" name="namasatuan" id="namasatuan" class="form-control" required="required" value="<?php echo $_POST["namasatuan"] ?>">
                            <input type="hidden" name="kodesatuan" id="kodesatuan" class="form-control" required="required" value="<?php echo $_POST["kodesatuan"] ?>">
                        </div>
                        <div class="col-sm-4">
                            <label>Spesifikasi Lain</label>
                            <input type="text" name="spesifikasilain" id="spesifikasilain" class="form-control" required="required" value="<?php echo $_POST["spesifikasilain"] ?>">
                        </div>
                        
                        <div class="col-sm-4">
                            <label>Tanggal Daftar Asal</label>
                            <input type="text" name="tanggaldaftarasal" id="tanggaldaftarasal" class="form-control datepicker" required="required" value="<?php echo $_POST["tanggaldaftarasal"] ?>">
                        </div>
                        
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label>Tipe</label>
                            <input type="text" name="tipe" id="tipe" class="form-control" required="required" value="<?php echo $_POST["tipe"] ?>">

                        </div>
                        <div class="col-sm-4">
                            <label>Ukuran</label>
                            <input type="text" name="ukuran" id="ukuran" class="form-control" required="required" value="<?php echo $_POST["ukuran"] ?>">
                        </div>

                    </div>

                </div>
                <div class="card-footer text-right">
                    <?php
                    if ($_GET['seqbb'] != '') {
                    ?>
                        <input type="hidden" name="seqbb" Value="<?php echo $_POST["seqbb"]; ?>">
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