<?php
$sql3 = "SELECT a.KodeJenisKemasan, a.JenisKemasan
                        FROM ms_kemasan a
                        WHERE a.JenisKemasan != '' ";
$data3 = $sqlLib->select($sql3);
?>

<div class="form-group row"> 
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
        <input type="text" name="namapengangkut"  required="required" class="form-control" value="<?php echo $_POST["namapengangkut"] ?>">
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
                                } ?>><?php echo '('.$row['KodeJenisKemasan'].') '.$row['JenisKemasan'] ?></option>
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
</div>    