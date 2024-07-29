<div class="section-header">
	<h1><?php echo acakacak("decode", $_GET["p"]) ?></h1>
</div>
<?php
if ($_POST['tanggal'] == "") {

	$_POST['tanggal'] = date("Y-m-d");
}

if ($_POST['upload']) {
	if($_POST['kodedokumen'] == "30"){
		include "upload_30.php";
		if($alert == "0"){
			//barang siap periksa
            $waktuperiksa = $_POST["tanggalperiksa"] . ' ' . $_POST["waktuperiksa"];
            $sql_sp = "INSERT INTO BC_BARANG_SIAP_PERIKSA (
                NomorAju, Seri, KodeJenisBarang, KodeJenisGudang, NamaPic,
                Alamat, NomorTelpPic, Lokasi, TanggalPkb, WaktuPeriksa,
                RecUser) VALUES (
                '" . $nomoraju . "', '1', '" . $_POST["kodejenisbarang"] . "', '" . $_POST["kodejenisgudang"] . "','" . $_POST["namapic"] . "',
                '" . $_POST["alamatpic"] . "', '" . $_POST["telppic"] . "','" . $_POST["lokasisiapperiksa"] . "', '" . $_POST["tanggalpkb"] . "',
                '" . $waktuperiksa . "','" . $_SESSION["nama"] . "')";
            $save_sp = $sqlLib->insert($sql_sp);
            if ($save_sp == "1") {
                $alert = '0';
                $note = "Proses simpan berhasil!!";
            } else {
                $sql_bdv = "DELETE FROM BC_BANK_DEVISA WHERE NomorAju = '" . $nomoraju . "'";
                $data_bdv = $sqlLib->delete($sql_bdv);
                $sql_brg = "DELETE FROM BC_BARANG WHERE NomorAju = '" . $nomoraju . "'";
                $data_brg = $sqlLib->delete($sql_brg);
                $sql_ner = "DELETE FROM BC_KONTAINER WHERE NomorAju = '" . $nomoraju . "'";
                $data_ner = $sqlLib->delete($sql_ner);
                $sql_kem = "DELETE FROM BC_KEMASAN WHERE NomorAju = '" . $nomoraju . "'";
                $data_kem = $sqlLib->delete($sql_kem);
                $sql_kut = "DELETE FROM BC_PENGANGKUT WHERE NomorAju = '" . $nomoraju . "'";
                $data_kut = $sqlLib->delete($sql_kut);
                $sql_dok = "DELETE FROM BC_DOKUMEN WHERE NomorAju = '" . $nomoraju . "'";
                $data_dok = $sqlLib->delete($sql_dok);
                $sql_ent = "DELETE FROM BC_ENTITAS WHERE NomorAju = '" . $nomoraju . "'";
                $data_ent = $sqlLib->delete($sql_ent);
                $sql_hdr = "DELETE FROM BC_HEADER WHERE NomorAju = '" . $nomoraju . "'";
                $data_hdr = $sqlLib->delete($sql_hdr);

                $alert = '1';
                $note = "Proses simpan kesiapan barang gagal!!";
            }
		}
	}elseif ($_POST['kodedokumen'] == "25") {
		include "upload_25.php";
	}

}

if ($_GET['noso'] != "") {
	$sql = "SELECT a.NoSO, a.TanggalSo, a.Customer, a.Alamat, a.Subtotal, a.Tax2Amount, a.TotalAmount,
                            b.KdBarang, b.NamaBarang, b.Harga, b.Qty, b.ItemDiscPercent, b.ItemCashDiscount, b.Satuan, b.TotalHarga
                        FROM ac_so a
                        LEFT JOIN ac_so_detail b on b.NoSO = a.NoSO
                        WHERE a.NoSO = '" . $_GET['noso'] . "' ";
	$data = $sqlLib->select($sql);
	$_POST["noso"] = $data[0]['NoSO'];
}
?>
<div class="row">
	<div class="col-12">
		<div class="card">
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
				<div class="form-group">
					<a href="download/excel/format_upload.php" target="_blank">
						<button class="btn btn-info"><i class="fa fa-download"></i> Format</button>
					</a>
					<?php echo $notes; ?>
				</div>

				<form method="post" id="form-transaksi" autocomplete="off" enctype="multipart/form-data">
					<div class="form-group">
						<label>Tanggal</label>
						<input type="text" name="tanggal" class="form-control datepicker" readonly="readonly" value="<?php echo $_POST["tanggal"] ?>">

					</div>
					<div class="form-group">
						<label>Nomor SO </label>
						<input type="text" name="noso" class="form-control" readonly="readonly" value="<?php echo $_POST["noso"] ?>">

					</div>
					<div class="form-group">
						<label>Dokumen BC</label>
						<select name="kodedokumen" class="form-control" required="required" onchange="submit();"> <!--atributbc()  onclick="selected();"-->
							<option value="">Pilih Dokumen</option>
							<option value="30" <?php if ($_POST['kodedokumen'] == "30") {
													echo "selected";
												} ?>>BC 30</option>
							<option value="25" <?php if ($_POST['kodedokumen'] == "25") {
													echo "selected";
												} ?>>BC 25</option>

						</select>
					</div>

					<?php 
					if ($_POST['kodedokumen'] == "30") { ?>
						<div class="row">
		                    <div class="col-12">
		                        <div class="card shadow p-1 mb-2 bg-white rounded">
		                            <div class="card-body">
		                            	<div class="form-group row" style="border-bottom:solid 0.5px #31708f;">
		                                    <label class="col-sm-12 col-form-label">Kesiapan Barang Ekspor</label>
		                                </div>
		                                <div class="form-group row">
		                                    <label class="col-sm-2 col-form-label">Jenis Barang</label>
		                                    <div class="col-sm-4">
		                                        <select class="form-control" name="kodejenisbarang" required="required">
		                                            <option value="">-Pilih-</option>
		                                            <option value="1" <?php if ($_POST['kodejenisbarang'] == "1") {
		                                                                    echo "selected";
		                                                                } ?>>Barang Ekspor Gabungan</option>
		                                            <option value="2" <?php if ($_POST['kodejenisbarang'] == "2") {
		                                                                    echo "selected";
		                                                                } ?>>Bahan/Barang Asal Impor Fasilitas</option>
		                                        </select>
		                                    </div>
		                                    <label class="col-sm-2 col-form-label">Lokasi</label>
		                                    <div class="col-sm-4">
		                                        <input type="text" name="lokasisiapperiksa" class="form-control" value="<?php echo $_POST["lokasisiapperiksa"] ?>">
		                                    </div>
		                                </div>
		                                <div class="form-group row">
		                                    <label class="col-sm-2 col-form-label">Jenis Gudang</label>
		                                    <div class="col-sm-4">
		                                        <select class="form-control" name="kodejenisgudang" required="required">
		                                            <option value="">-Pilih-</option>
		                                            <option value="1" <?php if ($_POST['kodejenisgudang'] == "1") {
		                                                                    echo "selected";
		                                                                } ?>>Gudang Veem</option>
		                                            <option value="2" <?php if ($_POST['kodejenisgudang'] == "2") {
		                                                                    echo "selected";
		                                                                } ?>>Gudang Pabrik</option>
		                                            <option value="3" <?php if ($_POST['kodejenisgudang'] == "3") {
		                                                                    echo "selected";
		                                                                } ?>>Gudang Konsolidasi</option>
		                                            <option value="4" <?php if ($_POST['kodejenisgudang'] == "4") {
		                                                                    echo "selected";
		                                                                } ?>>Lainnya</option>
		                                        </select>
		                                    </div>
		                                    <label class="col-sm-2 col-form-label">Tanggal PKB</label>
		                                    <div class="col-sm-4">
		                                        <input type="text" name="tanggalpkb" class="form-control datepicker" value="<?php echo $_POST["tanggalpkb"] ?>">
		                                    </div>
		                                </div>

		                                <div class="form-group row">
		                                    <label class="col-sm-2 col-form-label">Nama PIC</label>
		                                    <div class="col-sm-4">
		                                        <input type="text" name="namapic" class="form-control" value="<?php echo $_POST["namapic"] ?>">
		                                    </div>
		                                    <label class="col-sm-2 col-form-label">Waktu Periksa</label>
		                                    <div class="col-sm-2">
		                                        <input type="text" name="tanggalperiksa" class="form-control datepicker" value="<?php echo $_POST["tanggalperiksa"] ?>">
		                                    </div>
		                                    <div class="col-sm-2">
		                                        <input type="text" name="waktuperiksa" class="form-control" value="<?php echo $_POST["waktuperiksa"] ?>" placeholder="00:00">
		                                    </div>
		                                </div>
		                                <div class="form-group row">
		                                    <label class="col-sm-2 col-form-label">Alamat</label>
		                                    <div class="col-sm-4">
		                                        <input type="text" name="alamatpic" class="form-control" value="<?php echo $_POST["alamatpic"] ?>">
		                                    </div>
		                                </div>
		                                <div class="form-group row">
		                                    <label class="col-sm-2 col-form-label">No Telp</label>
		                                    <div class="col-sm-4">
		                                        <input type="text" name="telppic" class="form-control" value="<?php echo $_POST["telppic"] ?>">
		                                    </div>
		                                </div>

		                            </div>
		                        </div>
		                    </div>
		                </div>

					<?php } ?>
					<div class="form-group">
						<label>File</label>
						<input type="file" name="file" class="form-control" required="required" />

					</div>
			</div>
			<div class="card-footer text-right">
				<button type="reset" name="batal" class="btn btn-danger">Batal</button>
				<input type="submit" class="btn btn-primary" name="upload" Value="Upload">

			</div>


			</form>
		</div>
	</div>
</div>