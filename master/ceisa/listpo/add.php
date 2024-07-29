<?php
if ($_POST['simpan']) {

    if ($_POST['kodedokumenbc'] == "40") {

        include "simpan40.php";
    }

    if ($_POST['kodedokumenbc'] == "23") {
        include "simpan23.php";
    }
    
    if ($_POST['kodedokumenbc'] == "262") {
        include "simpan262.php";
    }
}



if ($_GET['nopo'] != "") {
    $sql = "SELECT a.NoPO, a.TanggalPo, a.Vendor, a.Alamat, a.Subtotal, a.Tax2Amount, a.TotalAmount,
                            b.KdBarang, b.NamaBarang, b.Harga, b.Qty, b.ItemDiscPercent, b.ItemCost, b.Satuan, b.TotalHarga, b.SeqItem
                        FROM ac_po a
                        LEFT JOIN ac_po_detail b on b.NoPO = a.NoPO
                        WHERE a.NoPO = '" . $_GET['nopo'] . "' ";
    $data = $sqlLib->select($sql);
    $_POST["nopo"] = $data[0]['NoPO'];
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
                                <option value="40" <?php if ($_POST['kodedokumenbc'] == "40") {
                                                        echo "selected";
                                                    } ?>>BC 40</option>
                                <option value="23" <?php if ($_POST['kodedokumenbc'] == "23") {
                                                        echo "selected";
                                                    } ?>>BC 23</option>
                                <option value="262" <?php if ($_POST['kodedokumenbc'] == "262") {
                                                        echo "selected";
                                                    } ?>>BC 262</option>   
                                <option value="27" <?php if ($_POST['kodedokumenbc'] == "27") {
                                                        echo "selected";
                                                    } ?>>BC 27</option>                    

                            </select>
                        </div>

                        <div class="col-sm-3">
                            <label>Nomor PO </label>
                            <input type="text" name="nopo" class="form-control" readonly="readonly" value="<?php echo $_POST["nopo"] ?>">
                        </div>


                    </div>

                    <div class="form-group row">
                        <?php
                        if ($_POST['kodedokumenbc'] == "40") {
                            include "formbc40.php";
                        } else if ($_POST['kodedokumenbc'] == "23") {
                            include "formbc23.php";
                        } else if ($_POST['kodedokumenbc'] == "262") {
                            include "formbc262.php";
                        } else if ($_POST['kodedokumenbc'] == "27") {
                            include "formbc27.php";
                        }
                        ?>
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

<script>
    function inqty(urut) {
        var sisa = document.getElementById("sisa" + urut).value;
        var qty = document.getElementById("qtyaju" + urut).value;
        var harga = document.getElementById("harga" + urut).value;
        if (parseFloat(qty) > parseFloat(sisa)) {
            alert("Qty Aju lebih besar dari sisa PO"+ sisa + qty);
        } else {
            //harga = harga.replace(re, '');
            var total = qty * harga;
            //total = number_format(total);
            document.getElementById("total" + urut).value = total;
            subtotal();
            subbruto();
            subnetto();
            subvolume();
        }

    }

    function subtotal() {
        var jmlrow = document.getElementById("jmlrow").value;
        var subtotal = 0;
        for (var i = 1; i <= jmlrow; i++) {
            var total = document.getElementById("total" + i).value;
            //total = total.replace(re, '');
            if (total == '') total = 0;
            subtotal += parseFloat(total);
        }

        document.getElementById("subtotal").value = subtotal; //number_format(subtotal, 0, ".", ",");

    }

    function subbruto() {
        var jmlrow = document.getElementById("jmlrow").value;
        var subbruto = 0;
        for (var i = 1; i <= jmlrow; i++) {
            var bruto = document.getElementById("bruto" + i).value;
            //total = total.replace(re, '');
            if (bruto == '') bruto = 0;
            subbruto += parseFloat(bruto);
        }

        document.getElementById("subbruto").value = subbruto; //number_format(subtotal, 0, ".", ",");

    }

    function subnetto() {
        var jmlrow = document.getElementById("jmlrow").value;
        var subnetto = 0;
        for (var i = 1; i <= jmlrow; i++) {
            var netto = document.getElementById("netto" + i).value;
            //total = total.replace(re, '');
            if (netto == '') netto = 0;
            subnetto += parseFloat(netto);
        }

        document.getElementById("subnetto").value = subnetto; //number_format(subtotal, 0, ".", ",");

    }

    function subvolume() {
        var jmlrow = document.getElementById("jmlrow").value;
        var subvolume = 0;
        for (var i = 1; i <= jmlrow; i++) {
            var volume = document.getElementById("volume" + i).value;
            //total = total.replace(re, '');
            if (volume == '') volume = 0;
            subvolume += parseFloat(volume);
        }

        document.getElementById("subvolume").value = subvolume; //number_format(subtotal, 0, ".", ",");

    }
</script>

<script>
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
            source: "json/pelbongkar.php",
            select: function(event, ui) {
                $("#kodepelbongkar").val(ui.item.id);
                $("#pelbongkar").val(ui.item.pelbongkar);
            },
            focus: function(event, ui) {
                $("#kodepelbongkar").val(ui.item.id);
                $("#pelbongkar").val(ui.item.pelbongkar);
            },
            minLength: 1
        };
        $("#pelbongkar").autocomplete(ac_config);
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
            source: "json/pelmuat.php",
            select: function(event, ui) {
                $("#kodepelmuat").val(ui.item.id);
                $("#pelmuat").val(ui.item.pelmuat);
            },
            focus: function(event, ui) {
                $("#kodepelmuat").val(ui.item.id);
                $("#pelmuat").val(ui.item.pelmuat);
            },
            minLength: 1
        };
        $("#pelmuat").autocomplete(ac_config);
    });

    $(document).ready(function() {
        var ac_config = {
            source: "json/kantor.php",
            select: function(event, ui) {
                $("#kodekantorbongkar").val(ui.item.id);
                $("#kantorbongkar").val(ui.item.namakantor);
            },
            focus: function(event, ui) {
                $("#kodekantorbongkar").val(ui.item.id);
                $("#kantorbongkar").val(ui.item.namakantor);
            },
            minLength: 1
        };
        $("#kantorbongkar").autocomplete(ac_config);
    });

    $(document).ready(function() {
        var ac_config = {
            source: "json/kantor.php",
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

    $(document).ready(function() {
        var ac_config = {
            source: "json/kantor.php",
            select: function(event, ui) {
                $("#kodekantortujuan").val(ui.item.id);
                $("#namakantortujuan").val(ui.item.namakantor);
            },
            focus: function(event, ui) {
                $("#kodekantortujuan").val(ui.item.id);
                $("#namakantortujuan").val(ui.item.namakantor);
            },
            minLength: 1
        };
        $("#namakantortujuan").autocomplete(ac_config);
    });

    $(document).ready(function() {
        var ac_config = {
            source: "json/negara.php",
            select: function(event, ui) {
                $("#kodenegara").val(ui.item.id);
                $("#namanegara").val(ui.item.namanegara);
            },
            focus: function(event, ui) {
                $("#kodenegara").val(ui.item.id);
                $("#namanegara").val(ui.item.namanegara);
            },
            minLength: 1
        };
        $("#namanegara").autocomplete(ac_config);
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
</script>