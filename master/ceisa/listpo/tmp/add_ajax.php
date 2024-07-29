<?php
if ($_POST['simpan']) {

    $sql_pt = "SELECT NamaPerusahaan, Alamat,KodeJenisIdentitas, NomorIdentitas,  NIB, Nama, Jabatan, KodeJenisTpb, Kota, NomorIjinEntitas, TanggalIjinEntitas, KodeKantor 
                FROM ms_perusahaan
                WHERE IDPerusahaan ='1' ";
    $data_pt = $sqlLib->select($sql_pt);
    $_POST["kodejenistpb"] = $data_pt[0]['KodeJenisTpb'];
    $_POST["jabatanttd"] = $data_pt[0]['Jabatan'];
    $_POST["kodekantor"] = $data_pt[0]['KodeKantor'] . '00';
    $_POST["kotattd"] = $data_pt[0]['Kota'];
    $_POST["namattd"] = $data_pt[0]['Nama'];

    if ($_POST['kodedokumenbc'] == "40") {
        $sql_urut = "SELECT MAX(Urut) as Urut FROM ms_dokumen_aju 
                        WHERE DokumenBC = '40' AND  YEAR(TanggalAju) = '" . date("Y") . "' ";
        $data_urut = $sqlLib->select($sql_urut);
        $urut = $data_urut[0]['Urut'] + 1;
        $nomor = str_pad($urut, 6, '0', STR_PAD_LEFT);
        //$data_pt[0]['KodeKantor'] .
        $_POST["nomoraju"] =  '0000' . $_POST['kodedokumenbc'] . '' . substr($data_pt[0]['NomorIdentitas'], 0, 6) . '' . date("Ymd", strtotime($_POST['tanggalaju'])) . '' . $nomor;        
        $_POST["seri"] = "1";

        $_POST["bruto"] = $_POST["subbruto"];
        $_POST["netto"] = $_POST["subnetto"];
        $_POST["hargapenyerahan"] = $_POST["subtotal"];

        $_POST["alamatentitaspengusaha"] = $data_pt[0]['Alamat'];
        $_POST["kodeentitaspengusaha"] = "3";
        $_POST["kodejenisidentitaspengusaha"] = $data_pt[0]['KodeJenisIdentitas'];
        $_POST["namaentitaspengusaha"] = $data_pt[0]['NamaPerusahaan'];
        $_POST["nibentitaspengusaha"] = $data_pt[0]['NIB'];
        $_POST["nomoridentitaspengusaha"] = $data_pt[0]['NomorIdentitas'];
        $_POST["nomorijinentitaspengusaha"] = $data_pt[0]['NomorIjinEntitas'];
        $_POST["serientitaspengusaha"] = "1";
        $_POST["tanggalijinentitaspengusaha"] = $data_pt[0]['TanggalIjinEntitas'];

        $_POST["alamatentitaspemilik"] = $data_pt[0]['Alamat'];
        $_POST["kodeentitaspemilik"] = "7";
        $_POST["kodejenisapipemilik"] = "2";
        $_POST["kodejenisidentitaspemilik"] = $data_pt[0]['KodeJenisIdentitas'];
        $_POST["namaentitaspemilik"] = $data_pt[0]['NamaPerusahaan'];
        $_POST["nibentitaspemilik"] = $data_pt[0]['NIB'];
        $_POST["nomoridentitaspemilik"] = $data_pt[0]['NomorIdentitas'];
        $_POST["nomorijinentitaspemilik"] = $data_pt[0]['NomorIjinEntitas'];
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
                         AlamatEntitasPemilik, KodeEntitasPemilik, KodeJenisApiPemilik, KodeJenisIdentitasPemilik, NamaEntitasPemilik, NibEntitasPemilik, NomorIdentitasPemilik, SeriEntitasPemilik,
                         AlamatEntitasPengirim, KodeEntitasPengirim, KodeJenisApiPengirim, KodeJenisIdentitasPengirim, NamaEntitasPengirim, NibEntitasPengirim, NomorIdentitasPengirim,SeriEntitasPengirim,
                         NamaPengangkut, NomorPengangkut, SeriPengangkut, 
                         JumlahKemasan, KodeJenisKemasan, SeriKemasan,
                         KodeFasilitasTarif, KodeJenisPungutan,TarifPPN, NilaiPungutan, Recuser) 
             VALUES ('" . $_POST['nopo'] . "','" . $_POST["nomoraju"] . "','" . $_POST["kodedokumenbc"] . "','" . $urut . "',
                    'S','" . $_POST["bruto"] . "','" . $_POST["kodejenistpb"] . "','" . $_POST["hargapenyerahan"] . "','" . $_POST["jabatanttd"] . "','" . $_POST["kodekantor"] . "',
                    '" . $_POST["kodetujuanpengiriman"] . "','" . $_POST["kotattd"] . "','" . $_POST["namattd"] . "','" . $_POST["netto"] . "','" . $_POST["seri"] . "'
                    ,'" . $_POST["tanggalaju"] . "','" . $_POST["tanggalaju"] . "','" . $_POST["volume"] . "','" . $_POST["uangmuka"] . "','" . $_POST["nilaijasa"] . "'
                    ,'" . $_POST["alamatentitaspengusaha"] . "','" . $_POST["kodeentitaspengusaha"] . "','" . $_POST["kodejenisidentitaspengusaha"] . "','" . $_POST["namaentitaspengusaha"] . "'
                    ,'" . $_POST["nibentitaspengusaha"] . "','" . $_POST["nomoridentitaspengusaha"] . "','" . $_POST["nomorijinentitaspengusaha"] . "','" . $_POST["serientitaspengusaha"] . "'
                    ,'" . $_POST["tanggalijinentitaspengusaha"] . "'
                    ,'" . $_POST["alamatentitaspemilik"] . "','" . $_POST["kodeentitaspemilik"] . "','" . $_POST["kodejenisapipemilik"] . "','" . $_POST["kodejenisidentitaspemilik"] . "'
                    ,'" . $_POST["namaentitaspemilik"] . "','" . $_POST["nibentitaspemilik"] . "','" . $_POST["nomoridentitaspemilik"] . "','" . $_POST["serientitaspemilik"] . "'
                    ,'" . $_POST["alamatentitaspengirim"] . "','" . $_POST["kodeentitaspengirim"] . "','" . $_POST["kodejenisapipengirim"] . "','" . $_POST["kodejenisidentitaspengirim"] . "',
                    '" . $_POST["namaentitaspengirim"] . "','" . $_POST["nibentitaspengirim"] . "','" . $_POST["nomoridentitaspengirim"] . "','" . $_POST["serientitaspengirim"] . "',
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
                $bruto = $_POST["bruto" . $i];
                $netto = $_POST["netto" . $i];
                $volume = $_POST["volume" . $i];
                $seqitem = $_POST["seqitem" . $i];
                if ($qtyaju > 0) {
                    $sql_dt = "INSERT INTO ms_dokumen_aju_detail(NoPO,NomorAju,DokumenBC,KdBarang,NamaBarang,Satuan,HsNumber,Harga,Qty,Total,Bruto,Netto,Volume,SeqItem,Recuser)
                            VALUES( '" . $_POST['nopo']  . "','" . $_POST["nomoraju"] . "','" . $_POST["kodedokumenbc"] . "','" . $kdbarang . "','" . $namabarang . "','" . $satuan . "',
                                    '" . $hsnumber . "','" . $harga . "','" . $qtyaju . "','" . $total . "','" . $bruto . "','" . $netto . "','" . $volume . "','" . $seqitem . "',
                                    '" . $_SESSION["nama"] . "')";
                    $run_dt =  $sqlLib->insert($sql_dt);
                }
            }

            //cek data detail
            $sql_isi = "SELECT NomorAju FROM ms_dokumen_aju_detail 
                        WHERE NoPO = '" . $_POST['nopo'] . "' AND NomorAju = '" . $_POST["nomoraju"] . "' AND DokumenBC = '" . $_POST["kodedokumenbc"] . "' ";
            $data_isi= $sqlLib->select($sql_isi);
            if(count($data_isi)>0){
                $alert = '0';
                $note = "Proses simpan berhasil!!";
            }
            else{
                $sql_del ="DELETE FROM ms_dokumen_aju 
                            WHERE NoPO = '" . $_POST['nopo'] . "' AND NomorAju = '" . $_POST["nomoraju"] . "' AND DokumenBC = '" . $_POST["kodedokumenbc"] . "'  ";
                $run_del = $sqlLib->delete($sql_del);

                $alert = '1';
                $note = "Proses simpan gagal!!";
            }
                
        }   
        else {
                $alert = '1';
                $note = "Proses simpan header gagal!!";
        } 
        
    }

    if ($_POST['kodedokumenbc'] == "23") {
        $sql_urut = "SELECT MAX(Urut) as Urut FROM ms_dokumen_aju 
                        WHERE DokumenBC = '23' AND  YEAR(TanggalAju) = '" . date("Y") . "' ";
        $data_urut = $sqlLib->select($sql_urut);
        $urut = $data_urut[0]['Urut'] + 1;
        $nomor = str_pad($urut, 6, '0', STR_PAD_LEFT);

        $sql = "INSERT INTO ms_dokumen_aju (NoPO, NomorAju, DokumenBC, Urut, 
                        asalData, asuransi, bruto, cif, fob, freight, hargaPenyerahan, 
                        jabatanTtd, jumlahKontainer, kodeAsuransi, kodeIncoterm, kodeKantor, 
                        kodeKantorBongkar, kodePelBongkar,
                        kodePelMuat, kodePelTransit, kodeTps, kodeTujuanTpb, kodeTutupPu, kodeValuta, kotaTtd, namaTtd, ndpbm, netto, nik, nilaiBarang, nomorAju, nomorBc11, posBc11, seri, subposBc11, 
                        tanggalBc11, tanggalTiba, tanggalTtd, biayaTambahan, biayaPengurang, barang, entitas, kemasan, dokumen, pengangkut, Recuser) 
             VALUES ('" . $_POST['nopo'] . "','" . $_POST["nomoraju"] . "','" . $_POST["kodedokumenbc"] . "','" . $urut . "',
                    'S','" . $_POST["asuransi"] . "','" . $_POST["bruto"] . "','" . $_POST["cif"] . "','" . $_POST["fob"] . "','" . $_POST["freight"] . "','" . $_POST["hargapenyerahan"] . "',
                    '" . $_POST["jabatanttd"] . "','" . $_POST["jumlahkontainer"] . "','" . $_POST["kodeasuransi"] . "','" . $_POST["kodeincoterm"] . "','" . $_POST["kodekantor"] . "',
                    '" . $_POST["kodekantorbongkar"] . "','" . $_POST["kodepelbongkar"] . "','" . $_POST["kodepelmuat"] . "','" . $_POST["kodepeltransit"] . "','" . $_POST["kodetps"] . "',
                    '" . $_POST["kodetujuantpb"] . "','" . $_POST["kodetutuppu"] . "','" . $_POST["kodevaluta"] . "','" . $_POST["kotattd"] . "','" . $_POST["namattd"] . "','" . $_POST["ndpbm"] . "',
                    '" . $_POST["netto"] . "',

                    
                    '" . $_POST["kodetujuanpengiriman"] . "','" . $_POST["seri"] . "'
                    ,'" . $_POST["tanggalaju"] . "','" . $_POST["tanggalaju"] . "','" . $_POST["volume"] . "','" . $_POST["uangmuka"] . "','" . $_POST["nilaijasa"] . "'
                    ,'" . $_POST["alamatentitaspengusaha"] . "','" . $_POST["kodeentitaspengusaha"] . "','" . $_POST["kodejenisidentitaspengusaha"] . "','" . $_POST["namaentitaspengusaha"] . "'
                    ,'" . $_POST["nibentitaspengusaha"] . "','" . $_POST["nomoridentitaspengusaha"] . "','" . $_POST["nomorijinentitaspengusaha"] . "','" . $_POST["serientitaspengusaha"] . "'
                    ,'" . $_POST["tanggalijinentitaspengusaha"] . "'
                    ,'" . $_POST["alamatentitaspemilik"] . "','" . $_POST["kodeentitaspemilik"] . "','" . $_POST["kodejenisapipemilik"] . "','" . $_POST["kodejenisidentitaspemilik"] . "'
                    ,'" . $_POST["namaentitaspemilik"] . "','" . $_POST["nibentitaspemilik"] . "','" . $_POST["nomoridentitaspemilik"] . "','" . $_POST["serientitaspemilik"] . "'
                    ,'" . $_POST["alamatentitaspengirim"] . "','" . $_POST["kodeentitaspengirim"] . "','" . $_POST["kodejenisapipengirim"] . "','" . $_POST["kodejenisidentitaspengirim"] . "',
                    '" . $_POST["namaentitaspengirim"] . "','" . $_POST["nibentitaspengirim"] . "','" . $_POST["nomoridentitaspengirim"] . "','" . $_POST["serientitaspengirim"] . "',
                    '" . $_POST["namapengangkut"] . "','" . $_POST["nomorpengangkut"] . "','" . $_POST["seripengangkut"] . "',
                    '" . $_POST["jumlahkemasan"] . "','" . $_POST["kodejeniskemasan"] . "','" . $_POST["serikemasan"] . "'
                    ,'" . $_POST["kodefasilitastarif"] . "','" . $_POST["kodejenispungutan"] . "','" . $_POST["tarifppn"] . "','" . $_POST["nilaipungutan"] . "','" . $_SESSION["nama"] . "')";

        $run = $sqlLib->insert($sql);
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
                             <select name="kodedokumenbc" class="form-control" required="required" onchange="atributbc();" onclick="selected();"> <!--atributbc() -->
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
                            <label>Nomor Aju</label>
                            <input type="text" name="nomoraju" class="form-control" readonly="readonly" placeholder="auto numbering" value="<?php echo $_POST["nomoraju"] ?>">
                        </div>
                        <div class="col-sm-3">
                            <label>Tanggal Aju</label>
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-calendar"></i>
                                </div>
                                <input type="text" name="tanggalaju" value="<?php echo $_POST['tanggalaju']; ?>" class="form-control datepicker" readonly="readonly">
                            </div>

                        </div>
                        <div class="col-sm-3">
                            <label>Nomor PO </label>
                            <input type="text" name="nopo" class="form-control" readonly="readonly" value="<?php echo $_POST["nopo"] ?>">
                        </div>
                                                
                    </div>
                    <!-- <?php 
                    if($_POST['kodedokumenbc']!=""){
                        include "dokumenbc.php";
                    }
                    ?> -->

                    <div class="form-group row" id="data-atribut">
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-12">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Kode Barang</th>
                                        <th scope="col">Nama Barang</th>
                                        <th scope="col">Qty PO</th>
                                        <th scope="col">Harga</th>
                                        <th scope="col">Sisa</th>
                                        <th scope="col">HS Number</th>
                                        <th scope="col">Bruto</th>
                                        <th scope="col">Netto</th>
                                        <th scope="col">Qty Aju</th>
                                        <th scope="col">Total Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    $sql_dt = "SELECT a.NoPO, a.TanggalPo, a.Vendor, a.Alamat, a.Subtotal, a.Tax2Amount, a.TotalAmount,
                                                        b.KdBarang, b.NamaBarang, b.Harga, b.Qty, b.ItemDiscPercent, b.ItemCost, b.Satuan, b.TotalHarga, b.SeqItem
                                                    FROM ac_po a
                                                    LEFT JOIN ac_po_detail b on b.NoPO = a.NoPO
                                                    WHERE a.NoPO = '" . $_POST['nopo'] . "' ";
                                    $data_dt = $sqlLib->select($sql_dt);
                                    foreach ($data_dt as $row) {
                                        $sql_aju = "SELECT SUM(a.Qty) as QtyAju
                                                    FROM ms_dokumen_aju_detail a
                                                    WHERE a.NoPO='".$row['NoPO']."' AND a.KdBarang ='".$row['KdBarang']."' AND a.SeqItem ='".$row['SeqItem']."'";
                                        $data_aju=$sqlLib->select($sql_aju);

                                        $sisa = $row['Qty'] - $data_aju[0]['QtyAju'];



                                    ?>
                                        <tr>
                                            <th scope="row"><?php echo $no ?></th>
                                            <td><?php echo $row['KdBarang'] ?></td>
                                            <td><?php echo $row['NamaBarang'] ?></td>
                                            <td><?php echo $row['Qty'] ?> <?php echo $row['Satuan'] ?></td>
                                            <td><?php echo number_format($row['Harga']) ?>
                                                <input type="hidden" name="harga<?php echo $no ?>" id="harga<?php echo $no ?>" value="<?php echo $row['Harga'] ?>">
                                                <input type="hidden" name="kdbarang<?php echo $no ?>" id="kdbarang<?php echo $no ?>" value="<?php echo $row['KdBarang'] ?>">
                                                <input type="hidden" name="namabarang<?php echo $no ?>" id="namabarang<?php echo $no ?>" value="<?php echo $row['NamaBarang'] ?>">
                                                <input type="hidden" name="satuan<?php echo $no ?>" id="satuan<?php echo $no ?>" value="<?php echo $row['Satuan'] ?>">
                                                <input type="hidden" name="seqitem<?php echo $no ?>" id="seqitem<?php echo $no ?>" value="<?php echo $row['SeqItem'] ?>">

                                            </td>
                                            <td><?php echo $sisa ?>
                                                <input type="hidden" name="sisa<?php echo $no ?>" id="sisa<?php echo $no ?>" value="<?php echo $sisa ?>">
                                            </td>
                                            <td><input type="text" name="hsnumber<?php echo $no ?>" id="hsnumber<?php echo $no ?>" size="10" <?php if($sisa<1){ echo "disabled";} ?> ></td>
                                            <td><input type="text" name="bruto<?php echo $no ?>" id="bruto<?php echo $no ?>" size="10" <?php if($sisa<1){ echo "disabled";} ?> ></td>
                                            <td><input type="text" name="netto<?php echo $no ?>" id="netto<?php echo $no ?>" size="10" <?php if($sisa<1){ echo "disabled";} ?> ></td>
                                            <td><input type="text" name="qtyaju<?php echo $no ?>" id="qtyaju<?php echo $no ?>" size="10" onchange="inqty('<?php echo $no ?>');" 
                                                <?php if($sisa<1){ echo "disabled";} ?>  ></td>
                                            <td><input type="text" name="total<?php echo $no ?>" id="total<?php echo $no ?>" size="10" <?php if($sisa<1){ echo "disabled";} ?> ></td>
                                        </tr>
                                    <?php
                                        $no++;
                                    }
                                    ?>
                                    <input type="hidden" name="jmlrow" id="jmlrow" value="<?php echo $no-1 ?>">    
                                </tbody>
                                <footer>
                                    <tr>
                                        <th scope="col">&nbsp;</th>
                                        <th scope="col">&nbsp;</th>
                                        <th scope="col">&nbsp;</th>
                                        <th scope="col">&nbsp;</th>
                                        <th scope="col">&nbsp;</th>
                                        <th scope="col">&nbsp;</th>
                                        <th scope="col">&nbsp;</th>
                                        <th scope="col"><input type="text" name="subbruto" id="subbruto" size="10" value="<?php echo number_format($_POST["subbruto"]) ?>" readonly="readonly" /></th>
                                        <th scope="col"><input type="text" name="subnetto" id="subnetto" size="10" value="<?php echo number_format($_POST["subnetto"]) ?>" readonly="readonly" /></th>
                                        <th scope="col">&nbsp;</th>
                                        <th scope="col"><input type="text" name="subtotal" id="subtotal" size="10" value="<?php echo number_format($_POST["subtotal"]) ?>" readonly="readonly" /></th>
                                    </tr>
                                </footer>
                            </table>
                        </div>
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
    function atributbc() {
        var formData = new FormData(document.getElementById("form-transaksi"));
        $.ajax({
            url: 'master/ceisa/listpo/dokumenbc.php',
            type: 'POST',
            data: formData,
            contentType: false,
            enctype: 'multipart/form-data',
            processData: false,
            beforeSend: function() {
                document.getElementById("data-atribut").innerHTML = "<div style='padding:20px; text-align:center'>Loading...</div>";
            },
            complete: function() {
                //document.getElementById("loading"+id).style.display = "none";
            },
            success: function(result) {

                document.getElementById("data-atribut").innerHTML = result;

            }
        });

    }

    function inqty(urut) {
        var sisa = document.getElementById("sisa" + urut).value;
        var qty = document.getElementById("qtyaju" + urut).value;
        var harga = document.getElementById("harga" + urut).value;
        if(qty>sisa){
            alert("Qty Aju lebih besar dari sisa PO");
        }
        else{
            //harga = harga.replace(re, '');
            var total = qty * harga;
            //total = number_format(total);
            document.getElementById("total" + urut).value = total;
            subtotal();
            subbruto();
            subnetto();
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

        document.getElementById("subtotal").value = subtotal;//number_format(subtotal, 0, ".", ",");
        
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

        document.getElementById("subbruto").value = subbruto;//number_format(subtotal, 0, ".", ",");
        
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

        document.getElementById("subnetto").value = subnetto;//number_format(subtotal, 0, ".", ",");
        
    }
</script>