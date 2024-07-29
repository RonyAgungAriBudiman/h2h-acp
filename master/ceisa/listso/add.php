<?php
if ($_POST['simpan']) {

    if ($_POST['kodedokumenbc'] == "30") {

        include "simpan30.php";
    }

    if ($_POST['kodedokumenbc'] == "25") {
        include "simpan25.php";
    }
}



if ($_GET['noso'] != "") {
    $sql = "SELECT a.NoSO, a.TanggalSo, a.Customer, a.Alamat, a.Subtotal, a.Tax2Amount, a.TotalAmount,
                                    b.KdBarang, b.NamaBarang, b.Harga, b.Qty, b.ItemDiscPercent, b.ItemCashDiscount, b.Satuan, b.TotalHarga, b.SeqItem
                            FROM ac_so a
                            LEFT JOIN ac_so_detail b on b.NoSO = a.NoSO
                            WHERE a.NoSO = '" . $_GET['noso'] . "' ";
    $data = $sqlLib->select($sql);
    $_POST["noso"] = $data[0]['NoSO'];
}

?>

<link rel="stylesheet" href="assets/css/jquery-ui.css" />
<!-- <script src="assets/js/jquery-1.12.4.js"></script>  -->
<script src="assets/js/jquery-ui.js"></script>
<div class="section-header">
    <h1><?php echo acakacak("decode", $_GET["p"]) ?></h1>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <form method="post" id="form-transaksi" autocomplete="off" enctype="multipart/form-data">
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

                        <div class="col-sm-3">
                            <label>Dokumen BC</label>
                            <select name="kodedokumenbc" class="form-control" required="required" onchange="submit();"> <!--atributbc()  onclick="selected();"-->
                                <option value="">Pilih Dokumen</option>
                                <option value="30" <?php if ($_POST['kodedokumenbc'] == "30") {
                                                        echo "selected";
                                                    } ?>>BC 30</option>
                                <option value="25" <?php if ($_POST['kodedokumenbc'] == "25") {
                                                            echo "selected";
                                                        } ?>>BC 25</option>

                            </select>
                        </div>

                        <div class="col-sm-3">
                            <label>Nomor SO </label>
                            <input type="text" name="noso" class="form-control" readonly="readonly" value="<?php echo $_POST["noso"] ?>">
                        </div>


                    </div>

                    <div class="form-group row">
                        <?php
                        if ($_POST['kodedokumenbc'] == "30") {
                            include "formbc30.php";
                        } else if ($_POST['kodedokumenbc'] == "25") {
                            include "formbc25.php";
                        }
                        ?>
                    </div>

                </div>
                <?php
                if ($_POST['kodedokumenbc'] != "") { ?>
                    <div class="card-footer text-right">
                        <button type="reset" name="batal" class="btn btn-danger">Batal</button>
                        <input type="submit" class="btn btn-primary" name="simpan" Value="Simpan">
                    </div>
                <?php } ?>


            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var ac_config = {
            source: "json/negara.php",
            select: function(event, ui) {
                $("#kodenegarapenerima").val(ui.item.id);
                $("#namanegarapenerima").val(ui.item.namanegara);
            },
            focus: function(event, ui) {
                $("#kodenegarapenerima").val(ui.item.id);
                $("#namanegarapenerima").val(ui.item.namanegara);
            },
            minLength: 1
        };
        $("#namanegarapenerima").autocomplete(ac_config);
    });

    $(document).ready(function() {
        var ac_config = {
            source: "json/negara.php",
            select: function(event, ui) {
                $("#kodenegarapembeli").val(ui.item.id);
                $("#namanegarapembeli").val(ui.item.namanegara);
            },
            focus: function(event, ui) {
                $("#kodenegarapembeli").val(ui.item.id);
                $("#namanegarapembeli").val(ui.item.namanegara);
            },
            minLength: 1
        };
        $("#namanegarapembeli").autocomplete(ac_config);
    });
    $(document).ready(function() {
        var ac_config = {
            source: "json/negara.php",
            select: function(event, ui) {
                $("#kodenegaratujuan").val(ui.item.id);
                $("#namanegaratujuan").val(ui.item.namanegara);
            },
            focus: function(event, ui) {
                $("#kodenegaratujuan").val(ui.item.id);
                $("#namanegaratujuan").val(ui.item.namanegara);
            },
            minLength: 1
        };
        $("#namanegaratujuan").autocomplete(ac_config);
    });

    $(document).ready(function() {
        var ac_config = {
            source: "json/negara.php",
            select: function(event, ui) {
                $("#kodebendera").val(ui.item.id);
                $("#bendera").val(ui.item.namanegara);
            },
            focus: function(event, ui) {
                $("#kodebendera").val(ui.item.id);
                $("#bendera").val(ui.item.namanegara);
            },
            minLength: 1
        };
        $("#bendera").autocomplete(ac_config);
    });

    $(document).ready(function() {
        var ac_config = {
            source: "json/kemasan.php",
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
            source: "json/valuta.php",
            select: function(event, ui) {
                $("#kodevaluta").val(ui.item.id);
                $("#namavaluta").val(ui.item.namavaluta);
            },
            focus: function(event, ui) {
                $("#kodevaluta").val(ui.item.id);
                $("#namavaluta").val(ui.item.namavaluta);
            },
            minLength: 1
        };
        $("#namavaluta").autocomplete(ac_config);
    });

    $(document).ready(function() {
        var ac_config = {
            source: "json/bank.php",
            select: function(event, ui) {
                $("#kodebank").val(ui.item.id);
                $("#namabank").val(ui.item.namabank);
            },
            focus: function(event, ui) {
                $("#kodebank").val(ui.item.id);
                $("#namabank").val(ui.item.namabank);
            },
            minLength: 1
        };
        $("#namabank").autocomplete(ac_config);
    });

    $(document).ready(function() {
        var ac_config = {
            source: "json/kantor.php",
            select: function(event, ui) {
                $("#kodekantormuat").val(ui.item.id);
                $("#namakantormuat").val(ui.item.namakantor);
            },
            focus: function(event, ui) {
                $("#kodekantormuat").val(ui.item.id);
                $("#namakantormuat").val(ui.item.namakantor);
            },
            minLength: 1
        };
        $("#namakantormuat").autocomplete(ac_config);
    });
    $(document).ready(function() {
        var ac_config = {
            source: "json/kantor.php",
            select: function(event, ui) {
                $("#kodekantorekspor").val(ui.item.id);
                $("#namakantorekspor").val(ui.item.namakantor);
            },
            focus: function(event, ui) {
                $("#kodekantorekspor").val(ui.item.id);
                $("#namakantorekspor").val(ui.item.namakantor);
            },
            minLength: 1
        };
        $("#namakantorekspor").autocomplete(ac_config);
    });
    $(document).ready(function() {
        var ac_config = {
            source: "json/kantor.php",
            select: function(event, ui) {
                $("#kodekantorperiksa").val(ui.item.id);
                $("#namakantorperiksa").val(ui.item.namakantor);
            },
            focus: function(event, ui) {
                $("#kodekantorperiksa").val(ui.item.id);
                $("#namakantorperiksa").val(ui.item.namakantor);
            },
            minLength: 1
        };
        $("#namakantorperiksa").autocomplete(ac_config);
    });
    $(document).ready(function() {
        var ac_config = {
            source: "json/pelbongkar.php",
            select: function(event, ui) {
                $("#kodepelekspor").val(ui.item.id);
                $("#namapelekspor").val(ui.item.namapelbongkar);
            },
            focus: function(event, ui) {
                $("#kodepelekspor").val(ui.item.id);
                $("#namapelekspor").val(ui.item.namapelbongkar);
            },
            minLength: 1
        };
        $("#namapelekspor").autocomplete(ac_config);
    });
    $(document).ready(function() {
        var ac_config = {
            source: "json/tps.php",
            select: function(event, ui) {
                $("#kodetps").val(ui.item.id);
                $("#namatps").val(ui.item.namatps);
            },
            focus: function(event, ui) {
                $("#kodetps").val(ui.item.id);
                $("#namatps").val(ui.item.namatps);
            },
            minLength: 1
        };
        $("#namatps").autocomplete(ac_config);
    });
    $(document).ready(function() {
        var ac_config = {
            source: "json/pelbongkar.php",
            select: function(event, ui) {
                $("#kodepelmuat").val(ui.item.id);
                $("#namapelmuat").val(ui.item.namapelbongkar);
            },
            focus: function(event, ui) {
                $("#kodepelmuat").val(ui.item.id);
                $("#namapelmuat").val(ui.item.namapelbongkar);
            },
            minLength: 1
        };
        $("#namapelmuat").autocomplete(ac_config);
    });
    $(document).ready(function() {
        var ac_config = {
            source: "json/pelmuat.php",
            select: function(event, ui) {
                $("#kodepelbongkar").val(ui.item.id);
                $("#namapelbongkar").val(ui.item.namapelmuat);
            },
            focus: function(event, ui) {
                $("#kodepelbongkar").val(ui.item.id);
                $("#namapelbongkar").val(ui.item.namapelmuat);
            },
            minLength: 1
        };
        $("#namapelbongkar").autocomplete(ac_config);
    });
    $(document).ready(function() {
        var ac_config = {
            source: "json/pelmuat.php",
            select: function(event, ui) {
                $("#kodepeltujuan").val(ui.item.id);
                $("#namapeltujuan").val(ui.item.namapelmuat);
            },
            focus: function(event, ui) {
                $("#kodepeltujuan").val(ui.item.id);
                $("#namapeltujuan").val(ui.item.namapelmuat);
            },
            minLength: 1
        };
        $("#namapeltujuan").autocomplete(ac_config);
    });
</script>