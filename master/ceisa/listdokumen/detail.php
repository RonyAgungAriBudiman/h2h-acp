
<div class="section-header">
    <h1><?php echo acakacak("decode", $_GET["p"]) ?></h1>
</div>
<?php 
$sql_aju = "SELECT a.NoPO,a.NomorAju,a.DokumenBC,a.Urut,a.AsalData,a.Asuransi,a.Bruto,a.KodeJenisTpb,a.HargaPenyerahan,a.JabatanTtd,a.KodeKantor,a.KodeTujuanPengiriman,a.KotaTtd,a.NamaTtd,a.Netto,a.Seri,
					a.TanggalAju,a.tanggalTtd,a.Volume,a.BiayaTambahan,a.BiayaPengurang,a.Vd,a.UangMuka,a.NilaiJasa,a.AlamatEntitasPengusaha,a.KodeEntitasPengusaha,a.KodeJenisIdentitasPengusaha,
					a.NamaEntitasPengusaha,a.NibEntitasPengusaha,a.NomorIdentitasPengusaha,a.NomorIjinEntitasPengusaha,a.SeriEntitasPengusaha,a.TanggalIjinEntitasPengusaha,a.AlamatEntitasPemilik,
					a.KodeEntitasPemilik,a.KodeJenisApiPemilik,a.KodeJenisIdentitasPemilik,a.NamaEntitasPemilik,a.NibEntitasPemilik,a.NomorIdentitasPemilik,a.SeriEntitasPemilik,a.AlamatEntitasPengirim,
					a.KodeEntitasPengirim,a.KodeJenisApiPengirim,a.KodeJenisIdentitasPengirim,a.NamaEntitasPengirim,a.NibEntitasPengirim,a.NomorIdentitasPengirim,a.SeriEntitasPengirim,a.NamaPengangkut,
					a.NomorPengangkut,a.SeriPengangkut,a.JumlahKemasan,a.KodeJenisKemasan,a.SeriKemasan,a.KodeFasilitasTarif,a.KodeJenisPungutan,a.TarifPPN,a.NilaiPungutan,a.Recuser,a.Recdate,a.KirimCeisa,
					b.KdBarang,b.Satuan,b.NamaBarang,b.HsNumber,b.Harga,b.Qty,b.Total,b.Bruto as BrutoDt,b.Netto as NettoDt,b.Volume as VolumeDt,b.SeqItem
			FROM ms_dokumen_aju a 
			LEFT JOIN ms_dokumen_aju_detail b on b.NomorAju = a.NomorAju
			WHERE a.NomorAju = '".$_GET["nomoraju"]."' ";
$data_aju = $sqlLib->select($sql_aju);
$_POST['kodedokumenbc'] = $data_aju[0]['DokumenBC'];
$_POST['nomoraju'] = $data_aju[0]['NomorAju'];
$_POST['nopo'] = $data_aju[0]['NoPO'];
$_POST["namaentitaspengirim"] = $data_aju[0]['NamaEntitasPengirim'];
$_POST["alamatentitaspengirim"] = $data_aju[0]['AlamatEntitasPengirim'];
$_POST["kodejenisidentitaspengirim"] = $data_aju[0]['KodeJenisIdentitasPengirim'];
$_POST["nomoridentitaspengirim"] = $data_aju[0]['NomorIdentitasPengirim'];
$_POST["nibentitaspengirim"] = $data_aju[0]['NibEntitasPengirim'];
$_POST["namapengangkut"] = $data_aju[0]['NamaPengangkut'];
$_POST["nomorpengangkut"] = $data_aju[0]['NomorPengangkut'];
$_POST["kodetujuanpengiriman"] = $data_aju[0]['KodeTujuanPengiriman'];
$_POST["volume"] = $data_aju[0]['Volume'];
$_POST["uangmuka"] = $data_aju[0]['UangMuka'];
$_POST["nilaijasa"] = $data_aju[0]['NilaiJasa'];
$_POST["jumlahkemasan"] = $data_aju[0]['JumlahKemasan'];
$_POST["kodejeniskemasan"] = $data_aju[0]['KodeJenisKemasan'];
$_POST["kodefasilitastarif"] = $data_aju[0]['KodeFasilitasTarif'];
$_POST["tarifppn"] = $data_aju[0]['TarifPPN'];
$_POST["nilaipungutan"] = $data_aju[0]['NilaiPungutan'];
$_POST["subbruto"] = $data_aju[0]['Bruto'];
$_POST["subnetto"] = $data_aju[0]['Netto'];
$_POST["subtotal"] = $data_aju[0]['HargaPenyerahan'];

?>

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
                            <select name="kodedokumenbc" class="form-control" required="required" disabled="disabled" onchange="atributbcx();">
                                <option value="">Pilih Dokumen</option>
                                <option value="40" <?php if ($_POST['kodedokumenbc'] == "40") {
                                                        echo "selected";
                                                    } ?>>BC 40</option>

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

                    <?php 
                    	if($_POST['kodedokumenbc'] == "40"){
                    		include "bc40.php";
                    	}
                    ?>
                    
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
                                    foreach ($data_aju as $row) {	

                                    	//qty PO
                                    	$sql_po = "SELECT Qty FROM ac_po_detail WHERE SeqItem = '".$row['SeqItem']."' ";
                                    	$data_po= $sqlLib->select($sql_po);

                                    	//qty PO
                                    	$sql_aju = "SELECT Sum(Qty) as QtyAju FROM ms_dokumen_aju_detail WHERE SeqItem = '".$row['SeqItem']."' ";
                                    	$data_aju= $sqlLib->select($sql_aju);

                                    	$sisa = $data_po[0]['Qty'] - $data_aju[0]['QtyAju'];
                                    	$sisa_all = $data_po[0]['Qty'] - $data_aju[0]['QtyAju']+ $row['Qty'];

                                        // $sql_aju = "SELECT SUM(a.Qty) as QtyAju
                                        //             FROM ms_dokumen_aju_detail a
                                        //             WHERE a.NoPO='".$row['NoPO']."' AND a.KdBarang ='".$row['KdBarang']."' AND a.SeqItem ='".$row['SeqItem']."'";
                                        // $data_aju=$sqlLib->select($sql_aju);

                                        // $sisa = $row['Qty'] - $data_aju[0]['QtyAju'];



                                    ?>
                                        <tr>
                                            <th scope="row"><?php echo $no ?></th>
                                            <td><?php echo $row['KdBarang'] ?><?php echo $row['SeqItem'] ?></td>
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
                                                <input type="hidden" name="sisa<?php echo $no ?>" id="sisa<?php echo $no ?>" value="<?php echo $sisa_all ?>">
                                            </td>
                                            <td><input type="text" name="hsnumber<?php echo $no ?>" id="hsnumber<?php echo $no ?>" size="10" value="<?php echo $row['HsNumber'] ?>"
                                            	 <?php if($sisa<1){ echo "disabled";} ?> ></td>
                                            <td><input type="text" name="bruto<?php echo $no ?>" id="bruto<?php echo $no ?>" size="10" value="<?php echo $row['BrutoDt'] ?>"
                                            	 <?php if($sisa<1){ echo "disabled";} ?> ></td>
                                            <td><input type="text" name="netto<?php echo $no ?>" id="netto<?php echo $no ?>" size="10" value="<?php echo $row['NettoDt'] ?>" 
                                            	<?php if($sisa<1){ echo "disabled";} ?> ></td>
                                            <td><input type="text" name="qtyaju<?php echo $no ?>" id="qtyaju<?php echo $no ?>" size="10" value="<?php echo $row['Qty'] ?>" 
                                            	onchange="inqty('<?php echo $no ?>');" <?php if($sisa<1){ echo "disabled";} ?>  ></td>
                                            <td><input type="text" name="total<?php echo $no ?>" id="total<?php echo $no ?>" size="10" value="<?php echo $row['Total'] ?>"
                                            	<?php if($sisa<1){ echo "disabled";} ?> ></td>
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
                    <!-- <button type="reset" name="batal" class="btn btn-danger">Batal</button>
                    <input type="submit" class="btn btn-primary" name="simpan" Value="Simpan"> -->
                </div>


            </form>
        </div>
    </div>
</div>

<script>
    function atributbc() {
        var formData = new FormData(document.getElementById("form-transaksi"));
        $.ajax({
            url: 'master/ceisa/listpo/bc40.php',
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
            alert("Qty Aju lebih besar dari sisa PO, Qty PO tersedia "+ sisa);
            document.getElementById("qtyaju"+ urut).value = 0;
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