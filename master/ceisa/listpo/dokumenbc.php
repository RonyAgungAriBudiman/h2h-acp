<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);

session_start();
include_once "../../../sqlLib.php";
$sqlLib = new sqlLib();


$sql2 = "SELECT a.NoPO, a.TanggalPo, a.Vendor, a.Alamat, a.Subtotal, a.Tax2Amount, a.TotalAmount,
                            b.KdBarang, b.NamaBarang, b.Harga, b.Qty, b.ItemDiscPercent, b.ItemCost, b.Satuan, b.TotalHarga
                        FROM ac_po a
                        LEFT JOIN ac_po_detail b on b.NoPO = a.NoPO
                        WHERE a.NoPO = '" . $_POST["nopo"]  . "' ";
$data2 = $sqlLib->select($sql2);
$_POST["alamatentitaspengirim"] = $data2[0]['Alamat'];
$_POST["namaentitaspengirim"] = $data2[0]['Vendor'];

$sql3 = "SELECT a.KodeJenisKemasan, a.JenisKemasan
                        FROM ms_kemasan a
                        WHERE a.JenisKemasan != '' ";
$data3 = $sqlLib->select($sql3);

if ($_POST['kodedokumenbc'] == "40") { ?>
    <!-- <div class="form-group row"> -->
    <div class="col-sm-3">
        <label>Vendor</label>
        <input type="text" name="namaentitaspengirim" readonly="readonly" class="form-control" value="<?php echo $_POST["namaentitaspengirim"] ?>">
    </div>
    <div class="col-sm-6">
        <label>Alamat Vendor</label>
        <input type="text" name="alamatentitaspengirim" readonly="readonly" class="form-control" value="<?php echo $_POST["alamatentitaspengirim"] ?>">
    </div>
    <div class="col-sm-3">
        <label>Jenis Identitas Vendor</label>
        <select class="form-control" name="kodejenisidentitaspengirim" required="required">
            <option value="">-Pilih-</option>
            <option value="0" <?php if ($_POST['kodejenisidentitaspengirim'] == "0") {
                                    echo "selected";
                                } ?>>NPWP 12 Digit</option>
            <option value="1" <?php if ($_POST['kodejenisidentitaspengirim'] == "1") {
                                    echo "selected";
                                } ?>>NPWP 10 Digit</option>
            <option value="2" <?php if ($_POST['kodejenisidentitaspengirim'] == "2") {
                                    echo "selected";
                                } ?>>Paspor</option>
            <option value="3" <?php if ($_POST['kodejenisidentitaspengirim'] == "3") {
                                    echo "selected";
                                } ?>>KTP</option>
            <option value="4" <?php if ($_POST['kodejenisidentitaspengirim'] == "4") {
                                    echo "selected";
                                } ?>>Lainnya</option>
            <option value="5" <?php if ($_POST['kodejenisidentitaspengirim'] == "5") {
                                    echo "selected";
                                } ?>>NPWP 15 Digit</option>
        </select>
    </div>
    <div class="col-sm-3 mt-3">
        <label>Nomor Identitas</label>
        <input type="text" name="nomoridentitaspengirim" required="required" class="form-control" value="<?php echo $_POST["nomoridentitaspengirim"] ?>">
    </div>

    <div class="col-sm-3 mt-3">
        <label>NIB Vendor</label>
        <input type="text" name="nibentitaspengirim" required="required" class="form-control" value="<?php echo $_POST["nibentitaspengirim"] ?>">
    </div>
    <div class="col-sm-3 mt-3">
        <label>Sarana Pengangkut</label>
        <input type="text" name="namapengangkut" required="required" class="form-control" value="<?php echo $_POST["namapengangkut"] ?>">
    </div>
    <div class="col-sm-3 mt-3">
        <label>No Polisi</label>
        <input type="text" name="nomorpengangkut" required="required" class="form-control" value="<?php echo $_POST["nomorpengangkut"] ?>">
    </div>
    <div class="col-sm-3 mt-3">
        <label>Tujuan Pengiriman</label>
        <select name="kodetujuanpengiriman" class="form-control" required="required">
            <option value="">-Pilih-</option>
            <option value="1" <?php if ($_POST['kodetujuanpengiriman'] == "1") {
                                    echo "selected";
                                } ?>>(1) Penyerahan BKP</option>
            <option value="2" <?php if ($_POST['kodetujuanpengiriman'] == "2") {
                                    echo "selected";
                                } ?>>(2) Penyerahan JKP</option>
            <option value="3" <?php if ($_POST['kodetujuanpengiriman'] == "3") {
                                    echo "selected";
                                } ?>>(3) Retur</option>
            <option value="4" <?php if ($_POST['kodetujuanpengiriman'] == "4") {
                                    echo "selected";
                                } ?>>(4) Non Penyerahan</option>
            <option value="5" <?php if ($_POST['kodetujuanpengiriman'] == "5") {
                                    echo "selected";
                                } ?>>(5) Lainnya</option>

        </select>
    </div>
    <div class="col-sm-3 mt-3">
        <label>Volume</label>
        <input type="text" name="volume" required="required" class="form-control" value="<?php echo $_POST["volume"] ?>">
    </div>
    <div class="col-sm-3 mt-3">
        <label>Uang Muka</label>
        <input type="text" name="uangmuka" required="required" class="form-control" value="<?php echo $_POST["uangmuka"] ?>">
    </div>
    <div class="col-sm-3 mt-3">
        <label>Nilai Jasa</label>
        <input type="text" name="nilaijasa" required="required" class="form-control" value="<?php echo $_POST["nilaijasa"] ?>">
    </div>

    <div class="col-sm-3 mt-3">
        <label>Jumlah Kemasan</label>
        <input type="text" name="jumlahkemasan" required="required" class="form-control" value="<?php echo $_POST["jumlahkemasan"] ?>">
    </div>
    <div class="col-sm-3 mt-3">
        <label>Jenis Kemasan</label>
        <select class="form-control" name="kodejeniskemasan" required="required">
            <option value="">-Pilih-</option>
            <?php
            foreach ($data3 as $row) {
            ?>
                <option value="<?php echo $row['KodeJenisKemasan'] ?>" <?php if ($_POST['kodejeniskemasan'] == $row['KodeJenisKemasan']) {
                                                                            echo "selected";
                                                                        } ?>><?php echo '(' . $row['KodeJenisKemasan'] . ') ' . $row['JenisKemasan'] ?></option>
            <?php
            }
            ?>

        </select>
    </div>
    <div class="col-sm-2 mt-3">
        <label>Fasilitas Tarif</label>
        <select name="kodefasilitastarif" class="form-control" required="required">
            <option value="">-Pilih-</option>
            <option value="3" <?php if ($_POST['kodefasilitastarif'] == "3") {
                                    echo "selected";
                                } ?>>(3) Ditangguhkan</option>
            <option value="5" <?php if ($_POST['kodefasilitastarif'] == "5") {
                                    echo "selected";
                                } ?>>(5) Dibebaskan</option>
            <option value="6" <?php if ($_POST['kodefasilitastarif'] == "6") {
                                    echo "selected";
                                } ?>>(6) Tidak dipungut</option>
            <option value="7" <?php if ($_POST['kodefasilitastarif'] == "7") {
                                    echo "selected";
                                } ?>>(7) Sudah dilunasi</option>

        </select>
    </div>
    <div class="col-sm-2 mt-3">
        <label>Tarif PPN</label>
        <input type="text" name="tarifppn" required="required" class="form-control" value="<?php echo $_POST["tarifppn"] ?>">
    </div>
    <div class="col-sm-2 mt-3">
        <label>Nilai Pungutan</label>
        <input type="text" name="nilaipungutan" required="required" class="form-control" value="<?php echo $_POST["nilaipungutan"] ?>">
    </div>
<?php }

if ($_POST['kodedokumenbc'] == "23") {
    $sql_inco = "SELECT a.KodeIncoterm, a.NamaIncoterm
                        FROM ms_incoterm a
                        WHERE a.NamaIncoterm != '' ";
    $data_inco = $sqlLib->select($sql_inco);

    $sql_bongkar = "SELECT a.KodeKantor, a.NamaKantor
                        FROM ms_kantor a
                        WHERE a.NamaKantor != '' ";
    $data_bongkar = $sqlLib->select($sql_bongkar);

    $sql_pelmuat = "SELECT a.KodePelMuat, a.NamaPelMuat
                         FROM ms_pelmuat a
                         WHERE a.NamaPelMuat != '' ";
    $data_pelmuat = $sqlLib->select($sql_pelmuat);

    $sql_pelbongkar = "SELECT a.KodePelBongkar, a.NamaPelBongkar
                         FROM ms_pelbongkar a
                         WHERE a.NamaPelBongkar != '' ";
    $data_pelbongkar = $sqlLib->select($sql_pelbongkar);

    $sql_valuta = "SELECT a.KodeValuta, a.NamaValuta
                         FROM ms_valuta a
                         WHERE a.NamaValuta != '' ";
    $data_valuta = $sqlLib->select($sql_valuta);
?>
    <div class="col-sm-3">
        <label>Vendor</label>
        <input type="text" name="namaentitaspengirim" readonly="readonly" class="form-control" value="<?php echo $_POST["namaentitaspengirim"] ?>">
    </div>
    <div class="col-sm-6">
        <label>Alamat Vendor</label>
        <input type="text" name="alamatentitaspengirim" readonly="readonly" class="form-control" value="<?php echo $_POST["alamatentitaspengirim"] ?>">
    </div>
    <div class="col-sm-3">
        <label>Asuransi LN/DN</label>
        <input type="text" name="asuransi" required="required" class="form-control" value="<?php echo $_POST["asuransi"] ?>">
    </div>
    <div class="col-sm-3 mt-3">
        <label>Cif</label>
        <input type="text" name="cif" required="required" class="form-control" value="<?php echo $_POST["cif"] ?>">
    </div>
    <div class="col-sm-3 mt-3">
        <label>Fob</label>
        <input type="text" name="fob" required="required" class="form-control" value="<?php echo $_POST["fob"] ?>">
    </div>
    <div class="col-sm-3 mt-3">
        <label>Freight</label>
        <input type="text" name="freight" required="required" class="form-control" value="<?php echo $_POST["freight"] ?>">
    </div>
    <div class="col-sm-3 mt-3">
        <label>Jumlah Kontainer</label>
        <input type="text" name="jumlahkontainer" required="required" class="form-control" value="<?php echo $_POST["jumlahkontainer"] ?>">
    </div>
    <div class="col-sm-3 mt-3">
        <label>Jenis Asuransi</label>
        <select class="form-control" name="kodeasuransi" required="required">
            <option value="">-Pilih-</option>
            <option value="LN" <?php if ($_POST['kodeasuransi'] == "LN") {
                                    echo "selected";
                                } ?>>Luar Negeri</option>
            <option value="DN" <?php if ($_POST['kodeasuransi'] == "DN") {
                                    echo "selected";
                                } ?>>Dalam Negeri</option>
        </select>
    </div>
    <div class="col-sm-3 mt-3">
        <label>Incoterm</label>
        <select class="form-control" name="kodeincoterm" required="required">
            <option value="">-Pilih-</option>
            <?php
            foreach ($data_inco as $row) {
            ?>
                <option value="<?php echo $row['KodeIncoterm'] ?>" <?php if ($_POST['kodeincoterm'] == $row['KodeIncoterm']) {
                                                                        echo "selected";
                                                                    } ?>><?php echo '(' . $row['KodeIncoterm'] . ') ' . $row['NamaIncoterm'] ?></option>
            <?php
            }
            ?>
        </select>
    </div>
    <div class="col-sm-3 mt-3">
        <label>Kantor Pabean Bongkar</label>
        <select class="form-control" name="kodekantorbongkar" required="required">
            <option value="">-Pilih-</option>
            <?php
            foreach ($data_bongkar as $row) {
            ?>
                <option value="<?php echo $row['KodeKantor'] ?>" <?php if ($_POST['kodekantorbongkar'] == $row['KodeKantor']) {
                                                                        echo "selected";
                                                                    } ?>><?php echo '(' . $row['KodeKantor'] . ') ' . $row['NamaKantor'] ?></option>
            <?php
            }
            ?>
        </select>
    </div>
    <div class="col-sm-3 mt-3">
        <label>Pelabuhan Muat</label>
        <select class="form-control" name="kodepelmuat" required="required">
            <option value="">-Pilih-</option>
            <?php
            foreach ($data_pelmuat as $row) {
            ?>
                <option value="<?php echo $row['KodePelMuat'] ?>" <?php if ($_POST['kodepelmuat'] == $row['KodePelMuat']) {
                                                                        echo "selected";
                                                                    } ?>><?php echo '(' . $row['KodePelMuat'] . ') ' . $row['NamaPelMuat'] ?></option>
            <?php
            }
            ?>
        </select>
    </div>


    <div class="col-sm-3 mt-3">
        <label>Pelabuhan Transit</label>
        <select class="form-control" name="kodepeltransit" required="required">
            <option value="">-Pilih-</option>
            <?php
            foreach ($data_pelmuat as $row) {
            ?>
                <option value="<?php echo $row['KodePelMuat'] ?>" <?php if ($_POST['kodepeltransit'] == $row['KodePelMuat']) {
                                                                        echo "selected";
                                                                    } ?>><?php echo '(' . $row['KodePelMuat'] . ') ' . $row['NamaPelMuat'] ?></option>
            <?php
            }
            ?>
        </select>
    </div>
    <div class="col-sm-3 mt-3">
        <label>Pelabuhan Bongkar</label>
        <select class="form-control" name="kodepelbongkar" required="required">
            <option value="">-Pilih-</option>
            <?php
            foreach ($data_pelbongkar as $row) {
            ?>
                <option value="<?php echo $row['KodePelBongkar'] ?>" <?php if ($_POST['kodepelbongkar'] == $row['KodePelBongkar']) {
                                                                            echo "selected";
                                                                        } ?>><?php echo '(' . $row['KodePelBongkar'] . ') ' . $row['NamaPelBongkar'] ?></option>
            <?php
            }
            ?>
        </select>
    </div>

    <div class="col-sm-3 mt-3">
        <label>Tempat Penimbunan Sementara</label>
        <select class="form-control" name="kodetps" required="required">
            <option value="">-Pilih-</option>

        </select>
    </div>

    <div class="col-sm-3 mt-3">
        <label>Tujuan TPB</label>
        <select class="form-control" name="kodetujuantpb" required="required">
            <option value="">-Pilih-</option>
            <option value="1" <?php if ($_POST['kodetujuantpb'] == "1") {
                                    echo "selected";
                                } ?>>Kawasan Berikat</option>
            <option value="2" <?php if ($_POST['kodetujuantpb'] == "2") {
                                    echo "selected";
                                } ?>>Gudang Berikat</option>
            <option value="3" <?php if ($_POST['kodetujuantpb'] == "3") {
                                    echo "selected";
                                } ?>>TPPB</option>
            <option value="4" <?php if ($_POST['kodetujuantpb'] == "4") {
                                    echo "selected";
                                } ?>>TBB</option>
            <option value="5" <?php if ($_POST['kodetujuantpb'] == "5") {
                                    echo "selected";
                                } ?>>TLB</option>
            <option value="6" <?php if ($_POST['kodetujuantpb'] == "6") {
                                    echo "selected";
                                } ?>>KDUB</option>
            <option value="7" <?php if ($_POST['kodetujuantpb'] == "7") {
                                    echo "selected";
                                } ?>>PLB</option>
            <option value="8" <?php if ($_POST['kodetujuantpb'] == "8") {
                                    echo "selected";
                                } ?>>LAINNYA</option>

        </select>
    </div>

    <div class="col-sm-3 mt-3">
        <label>Tutup PU</label>
        <select class="form-control" name="kodetutuppu" required="required">
            <option value="">-Pilih-</option>
            <option value="11" <?php if ($_POST['kodetutuppu'] == "11") {
                                    echo "selected";
                                } ?>>BC 1.1</option>
            <option value="12" <?php if ($_POST['kodetutuppu'] == "12") {
                                    echo "selected";
                                } ?>>BC 1.2</option>
            <option value="14" <?php if ($_POST['kodetutuppu'] == "14") {
                                    echo "selected";
                                } ?>>BC 1.4</option>

        </select>
    </div>

    <div class="col-sm-3 mt-3">
        <label>Valuta</label>
        <select class="form-control" name="kodevaluta" required="required">
            <option value="">-Pilih-</option>
            <?php
            foreach ($data_valuta as $row) {
            ?>
                <option value="<?php echo $row['KodeValuta'] ?>" <?php if ($_POST['kodevaluta'] == $row['KodeValuta']) {
                                                                        echo "selected";
                                                                    } ?>><?php echo '(' . $row['KodeValuta'] . ') ' . $row['NamaValuta'] ?></option>
            <?php
            }
            ?>
        </select>
    </div>
    <div class="col-sm-3 mt-3">
        <label>NDPBM</label>
        <input type="text" name="ndpbm" required="required" class="form-control" value="<?php echo $_POST["ndpbm"] ?>">
    </div>

<?php } ?>