<?php session_start();

include_once "../../../sqlLib.php";
$sqlLib = new sqlLib();


if (isset($_POST["tutup"])) {
    ?>
        <script>
            window.opener.document.getElementById("form-transaksi").submit();
            window.close();
        </script>
    <?php
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
            <form method="post" id="form" autocomplete="off" enctype="multipart/form-data">
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
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Jumlah</label>
                        <div class="col-sm-8">
                            <input type="text" name="jumlahkemasan" class="form-control" value="<?php echo $_POST["jumlahkemasan"] ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Jenis Kemasan</label>
                        <div class="col-sm-8">
                            <input type="text" name="jeniskemasan" id="jeniskemasan" class="form-control" required="required" value="<?php echo $_POST["jeniskemasan"] ?>">
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
                </div>
            </form>
        </div>
    <div>
</div>                
                            