<?php session_start();

include_once "../../../sqlLib.php";
$sqlLib = new sqlLib();

if($_POST['seqbb']=="") $_POST['seqbb'] = $_GET['seqbb'];

if (isset($_POST["simpan"])) {

    $nilaibayar =0;
    $sql1 = "SELECT HargaPenyerahan FROM  BC_BAHAN_BAKU_TMP WHERE SeqBB ='".$_POST['seqbb']."'   ";
    $data1 =$sqlLib->select($sql1);
    $nilaibayar = ($data1[0]['HargaPenyerahan'] * $_POST["tarif"])/100;
    $sql = "INSERT INTO BC_BAHAN_BAKU_TARIF_TMP (SeqBB,KodePungutan, KodeTarif, Tarif, KodeFasilitas, NilaiBayar, TarifFasilitas) 
                    VALUES ('" . $_POST["seqbb"] . "','" . $_POST["kodepungutan"] . "', '" . $_POST["kodetarif"] . "','" . $_POST["tarif"] . "', 
                            '" . $_POST["kodefasilitastarif"] . "','" . $nilaibayar . "','100')";
    $save = $sqlLib->insert($sql);
    if($save=="1"){
        $alert = '0';
        $note = "Proses simpan berhasil!!";
    }
    else{
        $alert = '1';
        $note = "Proses simpan gagal!!";
    }

}

if (isset($_POST["delete"])) {
    $sql = "DELETE FROM BC_BAHAN_BAKU_TARIF_TMP WHERE SeqBBT = '" . $_POST['seqbbt'] . "'";
    $run = $sqlLib->delete($sql);
    if ($run == "1") {
        $alert = '0';
        $note = "Proses delete berhasil!!";
    
    } else {
        $alert = '1';
        $note = "Proses delete gagal!!";
    }
}

if (isset($_POST["update"])) {

    $nilaibayar =0;
    $sql1 = "SELECT HargaPenyerahan FROM  BC_BAHAN_BAKU_TMP WHERE SeqBB ='".$_POST['seqbb']."'   ";
    $data1 =$sqlLib->select($sql1);
    $nilaibayar = ($data1[0]['HargaPenyerahan'] * $_POST["tarif"])/100;

    $sql = "UPDATE BC_BAHAN_BAKU_TARIF_TMP 
            SET KodePungutan = '" . $_POST["kodepungutan"] . "',
                KodeTarif= '" . $_POST["kodetarif"] . "',
                Tarif= '" . $_POST["tarif"] . "',
                KodeFasilitas= '" . $_POST["kodefasilitastarif"] . "',
                NilaiBayar = '" . $nilaibayar . "'
                WHERE SeqBBT = '" . $_POST['seqbbt'] . "'";
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
            window.close();
        </script>
    <?php
}

if (isset($_POST["edit"])) {


    $sql_tarif = "SELECT a.SeqBBT, a.SeqBB, a.KodePungutan,
                    CASE WHEN a.KodeTarif='1' THEN 'Advalorum' 
                    WHEN a.KodeTarif='2' THEN 'Spesifik' ELSE '' END as KodeTarif,a.Tarif, 
                    CASE WHEN a.KodeFasilitas ='1' THEN 'Dibayar' 
                    WHEN a.KodeFasilitas ='2' THEN 'Ditanggung Pemerintah' 
                    WHEN a.KodeFasilitas ='3' THEN 'Ditangguhkan' 
                    WHEN a.KodeFasilitas ='4' THEN 'Berkala' 
                    WHEN a.KodeFasilitas ='5' THEN 'Dibebaskan' 
                    WHEN a.KodeFasilitas ='6' THEN 'Tidak dipungut' 
                    WHEN a.KodeFasilitas ='7' THEN 'Sudah dilunasi' 
                    WHEN a.KodeFasilitas ='8' THEN 'Dijaminkan' 
                    WHEN a.KodeFasilitas ='9' THEN 'Ditunda'  ELSE '' END as KodeFasilitas
                    FROM BC_BAHAN_BAKU_TARIF_TMP a
                    WHERE a.SeqBBT = '" . $_POST["seqbbt"] . "' ";
    $data_datif = $sqlLib->select($sql_tarif);
    $_POST["kodepungutan"] = $data_datif[0]['KodePungutan'];
    $_POST["kodetarif"] = $data_datif[0]['KodeTarif'];
    $_POST["tarif"] = $data_datif[0]['Tarif'];
    $_POST["kodefasilitastarif"] = $data_datif[0]['KodeFasilitas'];
    $_POST["seqbbt"] = $data_datif[0]['SeqBBT'];
    $_POST["seqbb"] = $data_datif[0]['SeqBB'];

    
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
                        <div class="col-sm-6">
                            <label>Pungutan</label>
                            <select name="kodepungutan" class="form-control">
                                <option value="">Pilih</option>
                                <option value="PPNLOKAL" <?php if ($_POST['kodepungutan'] == "PPNLOKAL") {
                                                        echo "selected";
                                                    } ?>>PPNLOKAL</option>                 
                            </select>
                        </div>
                        <div class="col-sm-6">
                            &nbsp;
                        </div>
                        

                    </div>
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <label>Jenis Tarif</label>
                            <select name="kodetarif" class="form-control">
                                <option value="">-Pilih-</option>
                                <option value="1" <?php if ($_POST['kodetarif'] == "Advalorum") {
                                                        echo "selected";
                                                    } ?>>Advalorum</option>
                                <option value="2" <?php if ($_POST['kodetarif'] == "Spesifik") {
                                                        echo "selected";
                                                    } ?>>Spesifik</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            &nbsp;
                        </div>
                        
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <label>Tarif</label>
                            <input type="text" class="form-control" name="tarif" value="<?php echo $_POST['tarif'] ?>">
                        </div> 
                        <div class="col-sm-6">
                            &nbsp;
                        </div>
                        
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <label>Fasilitas</label>
                            <select name="kodefasilitastarif" class="form-control">
                                <option value="">-Pilih-</option>
                                <option value="1" <?php if ($_POST['kodefasilitastarif'] == "Dibayar") {
                                                        echo "selected";
                                                    } ?>>(1) Dibayar</option>
                                <option value="2" <?php if ($_POST['kodefasilitastarif'] == "Ditanggung Pemerintah") {
                                                        echo "selected";
                                                    } ?>>(2) Ditanggung Pemerintah</option>
                                <option value="3" <?php if ($_POST['kodefasilitastarif'] == "Ditangguhkan") {
                                                        echo "selected";
                                                    } ?>>(3) Ditangguhkan</option>
                                <option value="4" <?php if ($_POST['kodefasilitastarif'] == "Berkala") {
                                                        echo "selected";
                                                    } ?>>(4) Berkala</option>
                                <option value="5" <?php if ($_POST['kodefasilitastarif'] == "Dibebaskan") {
                                                        echo "selected";
                                                    } ?>>(5) Dibebaskan</option>
                                <option value="6" <?php if ($_POST['kodefasilitastarif'] == "Tidak dipungut") {
                                                        echo "selected";
                                                    } ?>>(6) Tidak dipungut</option>
                                <option value="7" <?php if ($_POST['kodefasilitastarif'] == "Sudah dilunasi") {
                                                        echo "selected";
                                                    } ?>>(7) Sudah dilunasi</option>
                                <option value="8" <?php if ($_POST['kodefasilitastarif'] == "Dijaminkan") {
                                                        echo "selected";
                                                    } ?>>(8) Dijaminkan</option>
                                <option value="9" <?php if ($_POST['kodefasilitastarif'] == "Ditunda") {
                                                        echo "selected";
                                                    } ?>>(9) Ditunda</option>
                            </select>
                        </div>
                        
                        <div class="col-sm-6">
                            &nbsp;
                        </div>
                        
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <?php
                                if ($_POST['seqbbt'] != '') {
                                ?>
                                    <input type="hidden" name="seqbb" Value="<?php echo $_POST["seqbb"]; ?>">
                                    <input type="hidden" name="seqbbt" Value="<?php echo $_POST["seqbbt"]; ?>">

                                    <input type="submit" class="btn btn-success" name="update" Value="Update">
                                    <input type="submit" class="btn btn-danger" name="delete" Value="Delete">
                                    <input type="submit" class="btn btn-info" name="tutup" Value="Tutup">
                                <?php

                                } else {
                                ?>
                                    <input type="submit" class="btn btn-primary" name="simpan" Value="Simpan">
                                    <input type="hidden" name="seqbb" Value="<?php echo $_GET["seqbb"]; ?>">

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
                                        <th scope="col">Pungutan</th>
                                        <th scope="col">Jenis Tarif</th>
                                        <th scope="col">Tarif</th>
                                        <th scope="col">Fasilitas</th>
                                        <th scope="col">Nilai Bayar</th>
                                        <th scope="col">Edit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $tarifno = 1;
                                    $sql_tarif = "SELECT a.SeqBBT, a.SeqBB, a.KodePungutan, a.NilaiBayar,
                                                    CASE WHEN a.KodeTarif='1' THEN 'Advalorum' 
                                                    WHEN a.KodeTarif='2' THEN 'Spesifik' ELSE '' END as KodeTarif,a.Tarif, 
                                                    CASE WHEN a.KodeFasilitas ='1' THEN 'Dibayar' 
                                                    WHEN a.KodeFasilitas ='2' THEN 'Ditanggung Pemerintah' 
                                                    WHEN a.KodeFasilitas ='3' THEN 'Ditangguhkan' 
                                                    WHEN a.KodeFasilitas ='4' THEN 'Berkala' 
                                                    WHEN a.KodeFasilitas ='5' THEN 'Dibebaskan' 
                                                    WHEN a.KodeFasilitas ='6' THEN 'Tidak dipungut' 
                                                    WHEN a.KodeFasilitas ='7' THEN 'Sudah dilunasi' 
                                                    WHEN a.KodeFasilitas ='8' THEN 'Dijaminkan' 
                                                    WHEN a.KodeFasilitas ='9' THEN 'Ditunda'  ELSE '' END as KodeFasilitas
                                                    FROM BC_BAHAN_BAKU_TARIF_TMP a
                                                    WHERE a.SeqBB = '" . $_POST["seqbb"] . "' ";
                                    $data_datif = $sqlLib->select($sql_tarif);
                                    foreach ($data_datif as $row_tarif) {
                                    ?>
                                        <tr>
                                            <td><?php echo $tarifno; ?></td>
                                            <td><?php echo $row_tarif['KodePungutan'] ?></td>
                                            <td><?php echo $row_tarif['KodeTarif'] ?></td>
                                            <td><?php echo $row_tarif['Tarif'] ?></td>
                                            <td><?php echo $row_tarif['KodeFasilitas'] ?></td>
                                            <td><?php echo number_format($row_tarif['NilaiBayar']) ?></td>
                                            <td>
                                                <form method="post" id="form" autocomplete="off" enctype="multipart/form-data">
                                                    <input type="hidden" class="form-control" name="seqbbt" value="<?php echo $row_tarif['SeqBBT'] ?>">
                                                    <input type="submit" class="btn btn-success" name="edit" Value="Edit">
                                                </form>
                                            </td>
                                        </tr>
                                    <?php

                                    }
                                    $tarifno++;
                                    ?>
                                    <input type="hidden" name="jmltarif" id="jmltarif" value="<?php echo ($tarifno - 1); ?>">
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