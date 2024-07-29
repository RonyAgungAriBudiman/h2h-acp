<?php
if ($_POST['simpan']) {

    if ($_POST['kodedokumenbc'] == "40") {

        $_POST["seri"] = "1";
        $_POST["nomoraju"] =  '0000' . $_POST['kodedokumenbc'] . '' . substr($_POST['nomoridentitaspengusaha'], 0, 6) . '' . date("Ymd", strtotime($_POST['tanggalaju'])) . '' . $_POST['nomor'];

        $_POST["bruto"] = $_POST["subbruto"];
        $_POST["netto"] = $_POST["subnetto"];
        $_POST["volume"] = $_POST["subvolume"];
        $_POST["hargapenyerahan"] = $_POST["subtotal"];

        $_POST["kodeentitaspengusaha"] = "3";
        $_POST["serientitaspengusaha"] = "1";

        $_POST["kodeentitaspemilik"] = "7";
        $_POST["kodejenisapipemilik"] = "2";
        $_POST["serientitaspemilik"] = "2";

        $_POST["kodeentitaspengirim"] = "9";
        $_POST["kodejenisapipengirim"] = "2";
        $_POST["serientitaspengirim"] = "3";

        $_POST["seripengangkut"] = "1";

        $_POST["serikemasan"] = "1";
        $_POST["kodejenispungutan"] = "PPN";

        $jmlrow = $_POST["jmlrow"];

        $sql = "INSERT INTO ms_dokumen_aju (NoPO, NomorAju, DokumenBC, Urut, 
                         AsalData, Bruto, KodeJenisTpb, HargaPenyerahan, JabatanTtd, KodeKantor,
                         KodeTujuanPengiriman, KotaTtd, NamaTtd, Netto, Seri, TanggalAju, tanggalTtd, Volume, UangMuka, NilaiJasa,
                         AlamatEntitasPengusaha, KodeEntitasPengusaha, KodeJenisIdentitasPengusaha,NamaEntitasPengusaha,NibEntitasPengusaha,NomorIdentitasPengusaha,NomorIjinEntitasPengusaha,
                         SeriEntitasPengusaha, TanggalIjinEntitasPengusaha,
                         AlamatEntitasPemilik, KodeEntitasPemilik, KodeJenisApiPemilik, KodeJenisIdentitasPemilik, NamaEntitasPemilik, NibEntitasPemilik, NomorIdentitasPemilik,
                         KodeStatusPemilik, SeriEntitasPemilik,
                         AlamatEntitasPengirim, KodeEntitasPengirim, KodeJenisApiPengirim, KodeJenisIdentitasPengirim, NamaEntitasPengirim, NibEntitasPengirim,
                         NomorIdentitasPengirim,KodeStatusPengirim, SeriEntitasPengirim,
                         NamaPengangkut, NomorPengangkut, SeriPengangkut, 
                         JumlahKemasan, KodeJenisKemasan, SeriKemasan,
                         KodeFasilitasTarif, KodeJenisPungutan,TarifPPN, NilaiPungutan, Recuser) 
             VALUES ('" . $_POST['nopo'] . "','" . $_POST["nomoraju"] . "','" . $_POST["kodedokumenbc"] . "','" . $_POST['urut'] . "',
                    'S','" . $_POST["bruto"] . "','" . $_POST["kodejenistpb"] . "','" . $_POST["hargapenyerahan"] . "','" . $_POST["jabatanttd"] . "','" . $_POST["kodekantor"] . "',
                    '" . $_POST["kodetujuanpengiriman"] . "','" . $_POST["kotattd"] . "','" . $_POST["namattd"] . "','" . $_POST["netto"] . "','" . $_POST["seri"] . "'
                    ,'" . $_POST["tanggalaju"] . "','" . $_POST["tanggalaju"] . "','" . $_POST["volume"] . "','" . $_POST["uangmuka"] . "','" . $_POST["nilaijasa"] . "'
                    ,'" . $_POST["alamatentitaspengusaha"] . "','" . $_POST["kodeentitaspengusaha"] . "','" . $_POST["kodejenisidentitaspengusaha"] . "','" . $_POST["namaentitaspengusaha"] . "'
                    ,'" . $_POST["nibentitaspengusaha"] . "','" . $_POST["nomoridentitaspengusaha"] . "','" . $_POST["nomorijinentitaspengusaha"] . "','" . $_POST["serientitaspengusaha"] . "'
                    ,'" . $_POST["tanggalijinentitaspengusaha"] . "'
                    ,'" . $_POST["alamatentitaspemilik"] . "','" . $_POST["kodeentitaspemilik"] . "','" . $_POST["kodejenisapipemilik"] . "','" . $_POST["kodejenisidentitaspemilik"] . "'
                    ,'" . $_POST["namaentitaspemilik"] . "','" . $_POST["nibentitaspemilik"] . "','" . $_POST["nomoridentitaspemilik"] . "','" . $_POST["kodestatuspemilik"] . "',
                    '" . $_POST["serientitaspemilik"] . "'
                    ,'" . $_POST["alamatentitaspengirim"] . "','" . $_POST["kodeentitaspengirim"] . "','" . $_POST["kodejenisapipengirim"] . "','" . $_POST["kodejenisidentitaspengirim"] . "',
                    '" . $_POST["namaentitaspengirim"] . "','" . $_POST["nibentitaspengirim"] . "','" . $_POST["nomoridentitaspengirim"] . "','" . $_POST["kodestatuspengirim"] . "',
                    '" . $_POST["serientitaspengirim"] . "',
                    '" . $_POST["namapengangkut"] . "','" . $_POST["nomorpengangkut"] . "','" . $_POST["seripengangkut"] . "',
                    '" . $_POST["jumlahkemasan"] . "','" . $_POST["kodejeniskemasan"] . "','" . $_POST["serikemasan"] . "'
                    ,'" . $_POST["kodefasilitastarif"] . "','" . $_POST["kodejenispungutan"] . "','" . $_POST["tarifppn"] . "','" . $_POST["nilaipungutan"] . "','" . $_SESSION["nama"] . "')";

        $run = $sqlLib->insert($sql);
        if ($run == "1") {
            for ($i = 1; $i <= $jmlrow; $i++) {

                $kdbarang = $_POST["kdbarang" . $i];
                $namabarang = $_POST["namabarang" . $i];
                $satuan = $_POST["satuan" . $i];
                $hsnumber = $_POST["hsnumber" . $i];
                $harga = $_POST["harga" . $i];
                $qtyaju = $_POST["qtyaju" . $i];
                $total = $_POST["total" . $i];
                $nilaifasilitas = ($total * $_POST["tarifppn"]) / 100;
                $bruto = $_POST["bruto" . $i];
                $netto = $_POST["netto" . $i];
                $volume = $_POST["volume" . $i];
                $seqitem = $_POST["seqitem" . $i];
                if ($qtyaju > 0) {
                    $sql_dt = "INSERT INTO ms_dokumen_aju_detail(NoPO,NomorAju,DokumenBC,KdBarang,NamaBarang,Satuan,HsNumber,Harga,Qty,Total,NilaiFasilitas,Bruto,Netto,Volume,SeqItem,Recuser)
                            VALUES( '" . $_POST['nopo']  . "','" . $_POST["nomoraju"] . "','" . $_POST["kodedokumenbc"] . "','" . $kdbarang . "','" . $namabarang . "','" . $satuan . "',
                                    '" . $hsnumber . "','" . $harga . "','" . $qtyaju . "','" . $total . "','" . $nilaifasilitas . "','" . $bruto . "','" . $netto . "','" . $volume . "','" . $seqitem . "',
                                    '" . $_SESSION["nama"] . "')";
                    $run_dt =  $sqlLib->insert($sql_dt);
                }
            }

            //cek data detail
            $sql_isi = "SELECT NomorAju FROM ms_dokumen_aju_detail 
                        WHERE NoPO = '" . $_POST['nopo'] . "' AND NomorAju = '" . $_POST["nomoraju"] . "' AND DokumenBC = '" . $_POST["kodedokumenbc"] . "' ";
            $data_isi = $sqlLib->select($sql_isi);
            if (count($data_isi) > 0) {
                $alert = '0';
                $note = "Proses simpan berhasil!!";
                unset($_POST);
            } else {
                $sql_del = "DELETE FROM ms_dokumen_aju 
                            WHERE NoPO = '" . $_POST['nopo'] . "' AND NomorAju = '" . $_POST["nomoraju"] . "' AND DokumenBC = '" . $_POST["kodedokumenbc"] . "'  ";
                $run_del = $sqlLib->delete($sql_del);

                $alert = '1';
                $note = "Proses simpan gagal!!";
            }
        } else {
            $alert = '1';
            $note = "Proses simpan header gagal!!";
        }
    }

    if ($_POST['kodedokumenbc'] == "23") {
        //, strtotime($_POST['tanggalaju'])
        $_POST["seri"] = "1";
        $_POST["nomoraju"] =  '0000' . $_POST['kodedokumenbc'] . '' . substr($_POST['nomoridentitaspengusaha'], 0, 6) . '' . date("Ymd", strtotime($_POST['tanggalaju'])) . '' . $_POST['nomor'];

        $_POST["bruto"] = $_POST["subbruto"];
        $_POST["netto"] = $_POST["subnetto"];
        $_POST["volume"] = $_POST["subvolume"];
        $_POST["hargapenyerahan"] = $_POST["subtotal"];

        $_POST["kodeentitaspengusaha"] = "3";
        $_POST["serientitaspengusaha"] = "1";

        $_POST["kodeentitaspengirim"] = "5";
        $_POST["serientitaspengirim"] = "2";

        $_POST["kodeentitaspemilik"] = "7";
        $_POST["kodejenisapipemilik"] = "2";
        $_POST["serientitaspemilik"] = "3";



        $_POST["kodepeltransit"] = "";
        $_POST["serikemasan"] = "1";
        $jmlrow = $_POST["jmlrow"];

        $sql = "INSERT INTO ms_dokumen_aju (NoPO, NomorAju, DokumenBC, Urut, asalData,Seri, 
                    kodePelBongkar,kodeKantorBongkar,kodeKantor, kodeTujuanTpb,
                    AlamatEntitasPengusaha, KodeEntitasPengusaha, KodeJenisIdentitasPengusaha,NamaEntitasPengusaha,NibEntitasPengusaha,NomorIdentitasPengusaha,NomorIjinEntitasPengusaha,
                    SeriEntitasPengusaha, TanggalIjinEntitasPengusaha,
                    AlamatEntitasPengirim, KodeEntitasPengirim, kodeNegara, NamaEntitasPengirim, SeriEntitasPengirim,
                    AlamatEntitasPemilik, KodeEntitasPemilik, KodeJenisApiPemilik, KodeJenisIdentitasPemilik, KodeStatusPemilik, NamaEntitasPemilik, NomorIdentitasPemilik, NomorIjinEntitasPemilik, 
                    TanggalIjinEntitasPemilik, SeriEntitasPemilik,
                    NomorBc11, TglBc11, PosBc11, SubPosBc11, KodeCaraAngkut, SeriPengangkut, NamaPengangkut, NomorPengangkut, KodeBendera, KodePelMuat, KodeTps,
                    JumlahKemasan, KodeJenisKemasan, MerkKemasan, SeriKemasan, JumlahKontainer,
                    KodeValuta, Ndpbm, KodeIncoterm, Cif, NilaiBarang, BiayaTambahan, BiayaPengurang, Fob, Freight, KodeAsuransi, Asuransi, KodeKenaPajak,
                    Bruto, Netto, Volume, HargaPenyerahan,
                    KotaTtd, TanggalAju, TanggalTtd,  NamaTtd, JabatanTtd,
                    Recuser) 
             VALUES ('" . $_POST['nopo'] . "','" . $_POST["nomoraju"] . "','" . $_POST["kodedokumenbc"] . "','" . $_POST['urut'] . "','S','" . $_POST['seri'] . "',
                    '" . $_POST["kodepelbongkar"] . "','" . $_POST["kodekantorbongkar"] . "','" . $_POST["kodekantor"] . "', '" . $_POST["kodetujuantpb"] . "',
                    '" . $_POST["alamatentitaspengusaha"] . "','" . $_POST["kodeentitaspengusaha"] . "','" . $_POST["kodejenisidentitaspengusaha"] . "',
                    '" . $_POST["namaentitaspengusaha"] . "','" . $_POST["nibentitaspengusaha"] . "','" . $_POST["nomoridentitaspengusaha"] . "',
                    '" . $_POST["nomorijinentitaspengusaha"] . "','" . $_POST["serientitaspengusaha"] . "','" . $_POST["tanggalijinentitaspengusaha"] . "',
                    '" . $_POST["alamatentitaspengirim"] . "','" . $_POST["kodeentitaspengirim"] . "', '" . $_POST["kodenegara"] . "','" . $_POST["namaentitaspengirim"] . "', 
                    '" . $_POST["serientitaspengirim"] . "',
                    '" . $_POST["alamatentitaspemilik"] . "','" . $_POST["kodeentitaspemilik"] . "','" . $_POST["kodejenisapipemilik"] . "','" . $_POST["kodejenisidentitaspemilik"] . "',
                    '" . $_POST["kodestatuspemilik"] . "','" . $_POST["namaentitaspemilik"] . "','" . $_POST["nomoridentitaspemilik"] . "','" . $_POST["nomorijinentitaspemilik"] . "',
                    '" . $_POST["tanggalijinentitaspemilik"] . "','" . $_POST["serientitaspemilik"] . "',
                    '" . $_POST["nomorbc11"] . "','" . $_POST["tglbc11"] . "','" . $_POST["posbc11"] . "','" . $_POST["subposbc11"] . "','" . $_POST["kodecaraangkut"] . "', '1',
                    '" . $_POST["namapengangkut"] . "','" . $_POST["nomorpengangkut"] . "','" . $_POST["kodebendera"] . "','" . $_POST["kodepelmuat"] . "','" . $_POST["kodetps"] . "',
                    '" . $_POST["jumlahkemasan"] . "','" . $_POST["kodejeniskemasan"] . "','" . $_POST["merkkemasan"] . "','" . $_POST["serikemasan"] . "','" . $_POST["jumlahkontainer"] . "',
                    '" . $_POST["kodevaluta"] . "','" . $_POST["ndpbm"] . "','" . $_POST["kodeincoterm"] . "','" . $_POST["cif"] . "','" . $_POST["nilaibarang"] . "', '" . $_POST["biayatambahan"] . "',
                    '" . $_POST["biayapengurang"] . "','" . $_POST["fob"] . "','" . $_POST["freight"] . "','" . $_POST["kodeasuransi"] . "','" . $_POST["asuransi"] . "','" . $_POST["kodekenapajak"] . "',
                    '" . $_POST["bruto"] . "','" . $_POST["netto"] . "','" . $_POST["volume"] . "','" . $_POST["hargapenyerahan"] . "',
                    '" . $_POST["kotattd"] . "','" . $_POST["tanggalaju"] . "', '" . $_POST["tanggalaju"] . "','" . $_POST["namattd"] . "','" . $_POST["jabatanttd"] . "',
                    '" . $_SESSION["nama"] . "' )";

        $run = $sqlLib->insert($sql);

        if ($run == "1") {
            for ($i = 1; $i <= $jmlrow; $i++) {
                
                $hsnumber = $_POST["hsnumber" . $i];
                $kdbarang = $_POST["kdbarang" . $i];
                $namabarang = $_POST["namabarang" . $i];
                $kodekategoribarang = $_POST["kodekategoribarang".$i];
                $kodenegara = $_POST["kodenegara".$i];
                $ndpbm = $_POST["ndpbm".$i];
                $cifrp = $_POST["cifrp".$i];
                $hargaperolehan = $_POST["hargaperolehan".$i];
                $kodeasalbahanbaku = $_POST["kodeasalbahanbaku".$i];

                $asuransi = $_POST["asuransi".$i];
                $cif = $_POST["cif".$i];
                $diskon = $_POST["diskon".$i];
                $fob = $_POST["fob".$i];
                $freight = $_POST["freight".$i];
                $hargaekspor = $_POST["hargaekspor".$i];
                $harga = $_POST["harga" . $i];
                $kodeperhitungan = $_POST["kodeperhitungan" . $i];
                $nilaibarang = $_POST["nilaibarang" . $i];

                $satuan = $_POST["satuan" . $i];
                $qtyaju = $_POST["qtyaju" . $i];
                $total = $_POST["total" . $i];
                $nilaifasilitas = 0; //($total * $_POST["tarifppn"]) / 100;
                $bruto = $_POST["bruto" . $i];
                $netto = $_POST["netto" . $i];
                $volume = $_POST["volume" . $i];
                $seqitem = $_POST["seqitem" . $i];
                if ($qtyaju > 0) {
                    $sql_dt = "INSERT INTO ms_dokumen_aju_detail(NoPO,NomorAju,DokumenBC,
                                HsNumber, KdBarang, NamaBarang, KodeKategoriBarang, KodeNegara, Ndpbm, CifRp, HargaPerolehan, KodeAsalBahanBaku, 
                                Asuransi, Cif, Diskon, Fob, Freight, HargaEkspor, Harga, KodePerhitungan, NlaiBarang,

                   Satuan,HsNumber,Qty,Total,NilaiFasilitas,Bruto,Netto,Volume,SeqItem,Recuser)
                            VALUES( '" . $_POST['nopo']  . "','" . $_POST["nomoraju"] . "','" . $_POST["kodedokumenbc"] . "','" . $kdbarang . "','" . $namabarang . "','" . $satuan . "',
                                    '" . $hsnumber . "','" . $harga . "','" . $qtyaju . "','" . $total . "','" . $nilaifasilitas . "','" . $bruto . "','" . $netto . "','" . $volume . "','" . $seqitem . "',
                                    '" . $_SESSION["nama"] . "')";
                    $run_dt =  $sqlLib->insert($sql_dt);
                }
            }

            //save dokumen
            for ($a = 1; $a <= 2; $a++) {

                $kodedokumen = $_POST["kodedokumen" . $a];
                $nomordokumen = $_POST["nomordokumen" . $a];
                $tgldokumen = $_POST["tgldokumen" . $a];
                if ($kodedokumen == "380") {
                    $jenisdokumen = "Invoice";
                } elseif ($kodedokumen == "705") {
                    $jenisdokumen = "B/L";
                } elseif ($kodedokumen == "740") {
                    $jenisdokumen = "AWB";
                }

                if ($nomordokumen != "") {
                    $sql_dok = "INSERT INTO ms_dokumen_pendukung(NomorAju, KodeDokumen, NomorDokumen, JenisDokumen, TanggalDokumen, SeriDokumen)
                            VALUES( '" . $_POST["nomoraju"] . "','" . $kodedokumen . "','" . $nomordokumen . "','" . $jenisdokumen . "','" . $tgldokumen . "','" . $a . "')";
                    $run_dok =  $sqlLib->insert($sql_dok);
                }
            }

            //cek data detail
            $sql_isi = "SELECT NomorAju FROM ms_dokumen_aju_detail 
                        WHERE NoPO = '" . $_POST['nopo'] . "' AND NomorAju = '" . $_POST["nomoraju"] . "' AND DokumenBC = '" . $_POST["kodedokumenbc"] . "' ";
            $data_isi = $sqlLib->select($sql_isi);
            if (count($data_isi) > 0) {
                $alert = '0';
                $note = "Proses simpan berhasil!!";
                unset($_POST);
            } else {
                $sql_del = "DELETE FROM ms_dokumen_aju 
                            WHERE NoPO = '" . $_POST['nopo'] . "' AND NomorAju = '" . $_POST["nomoraju"] . "' AND DokumenBC = '" . $_POST["kodedokumenbc"] . "'  ";
                $run_del = $sqlLib->delete($sql_del);

                $sql_del2 = "DELETE FROM ms_dokumen_pendukung 
                            WHERE NomorAju = '" . $_POST["nomoraju"] . "' ";
                $run_del2 = $sqlLib->delete($sql_del2);

                $alert = '1';
                $note = "Proses simpan gagal!!";
            }
        } else {
            $alert = '1';
            $note = "Proses simpan header gagal!!";
        }
    }
}


/*
    
                         jumlahKontainer,  
                       kodePelTransit,   kodeTutupPu,     nik,   
                        tanggalTiba,                     
*/
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
        if (qty > sisa) {
            alert("Qty Aju lebih besar dari sisa PO");
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