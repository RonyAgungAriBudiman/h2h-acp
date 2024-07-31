<?php session_start();

include_once "../../../sqlLib.php";
$sqlLib = new sqlLib();

if (isset($_POST["simpan"])) {

    $sql = "INSERT INTO BC_KEMASAN_TMP (KodeKemasan, JumlahKemasan, Merek) 
            VALUES ('" . $_POST["kodejeniskemasan"] . "', '" . $_POST["jumlahkemasan"] . "', '" . $_POST["merkkemasan"] . "')";
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
    $sql = "DELETE FROM BC_KEMASAN_TMP WHERE SeqKemasan = '" . $_POST['seqkemasan'] . "'";
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

    $sql = "UPDATE BC_KEMASAN_TMP 
            SET KodeKemasan = '" . $_POST["kodejeniskemasan"] . "',
                JumlahKemasan= '" . $_POST["jumlahkemasan"] . "',
                Merek= '" . $_POST["merkkemasan"] . "'
                WHERE SeqKemasan = '" . $_POST['seqkemasan'] . "'";
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
    $sql_tarif = "SELECT a.SeqKemasan, a.KodeKemasan, a.JumlahKemasan, a.Merek, b.JenisKemasan
                    FROM BC_KEMASAN_TMP a
                    LEFT JOIN ms_kemasan b on b.KodeJenisKemasan = a.KodeKemasan
                    WHERE SeqKemasan = '" . $_POST["seqkemasan"] . "' ";
    $data_datif = $sqlLib->select($sql_tarif);
    $_POST["kodejeniskemasan"] = $data_datif[0]['KodeKemasan'];
    $_POST["jeniskemasan"] = $data_datif[0]['JenisKemasan'];
    $_POST["jumlahkemasan"] = $data_datif[0]['JumlahKemasan'];
    $_POST["merkkemasan"] = $data_datif[0]['Merek'];
    $_POST["seqkontainer"] = $data_datif[0]['SeqKontainer'];
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Kemasan</title>
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
            
                <div class="card-body">
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
                        </div>
                        <?php 
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
                    <form method="post" id="form" autocomplete="off" enctype="multipart/form-data">    
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Jumlah</label>
                            <div class="col-sm-8">
                                <input type="text" name="jumlahkemasan" class="form-control" value="<?php echo $_POST["jumlahkemasan"] ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Jenis Kemasan</label>
                            <div class="col-sm-8">
                                <input type="text" name="jeniskemasan" id="jeniskemasan" class="form-control" value="<?php echo $_POST["jeniskemasan"] ?>">
                                <input type="hidden" name="kodejeniskemasan" id="kodejeniskemasan" class="form-control" value="<?php echo $_POST["kodejeniskemasan"] ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Merk Kemasan</label>
                            <div class="col-sm-8">
                                <input type="text" name="merkkemasan" class="form-control" value="<?php echo $_POST["merkkemasan"] ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-6">
                                <?php
                                if ($_POST['seqkemasan'] != '') {
                                ?>
                                    <input type="hidden" name="seqkemasan" Value="<?php echo $_POST["seqkemasan"]; ?>">

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
                                        <th scope="col">Jumlah Kemasan</th>
                                        <th scope="col">Jenis Kemasan</th>
                                        <th scope="col">Merk Kemasan</th>
                                        <th scope="col">Edit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $no = 1;
                                    $sql_kon = "SELECT a.SeqKemasan, a.KodeKemasan, a.JumlahKemasan, a.Merek,
                                                    b.JenisKemasan
                                                    FROM BC_KEMASAN_TMP a 
                                                    LEFT JOIN ms_kemasan b on b.KodeJenisKemasan = a.KodeKemasan";
                                    $data_kon = $sqlLib->select($sql_kon);
                                    foreach ($data_kon as $row_kon) {
                                        ?>
                                            <tr>
                                                <td><?php echo $no; ?></td>
                                                <td><?php echo $row_kon['JumlahKemasan'] ?></td>
                                                <td><?php echo $row_kon['JenisKemasan'] ?></td>
                                                <td><?php echo $row_kon['Merek'] ?></td>
                                                <td>
                                                    <form method="post" id="form" autocomplete="off" enctype="multipart/form-data">
                                                        <input type="hidden" class="form-control" name="seqkemasan" value="<?php echo $row_kon['SeqKemasan'] ?>">
                                                        <input type="submit" class="btn btn-success" name="edit" Value="Edit">
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php
            
                                        }
                                        $no++;
                                        ?>
                                        <input type="hidden" name="jmlkemasan" id="jmlkemasan" value="<?php echo ($no - 1); ?>">
                               
                                </tbody>
                            </table>
                        </div>
                    </div>            
                </div>
        </div>
    <div>
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
            source: "../../../json/kemasan.php",
            select: function(event, ui) {
                $("#kodejeniskemasan").val(ui.item.id);
                $("#jeniskemasan").val(ui.item.jeniskemasan);
            },
            focus: function(event, ui) {
                $("#kodejeniskemasan").val(ui.item.id);
                $("#jeniskemasan").val(ui.item.jeniskemasan);
            },
            minLength: 1
        };
        $("#jeniskemasan").autocomplete(ac_config);
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
                            