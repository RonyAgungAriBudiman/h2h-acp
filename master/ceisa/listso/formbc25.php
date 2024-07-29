<?php
$sql_dt = "SELECT a.NoSO, a.TanggalSo, a.Customer, a.Alamat, a.Subtotal, a.Tax2Amount, a.TotalAmount,
                    b.KdBarang, b.NamaBarang, b.Harga, b.Qty, b.ItemDiscPercent, b.ItemCashDiscount, b.Satuan, b.TotalHarga, b.SeqItem
            FROM ac_so a
            LEFT JOIN ac_so_detail b on b.NoSO = a.NoSO
            WHERE a.NoSO = '" . $_POST['noso'] . "' ";
$data_dt = $sqlLib->select($sql_dt);

$sql_pt = "SELECT a.NamaPerusahaan, a.Alamat, a.KodeJenisIdentitas, a.JenisIdentitas, a.NomorIdentitas,  a.NIB, 
            a.Nama, a.Jabatan, a.KodeJenisTpb, a.Kota, a.NomorIjinEntitas, 
            a.TanggalIjinEntitas, a.KodeKantor, a.KantorPabean , a.KodeStatus
FROM ms_perusahaan a
WHERE a.IDPerusahaan ='1' ";
$data_pt = $sqlLib->select($sql_pt);

$sql_urut = "SELECT MAX(Urut) as Urut FROM BC_HEADER 
                WHERE KodeDokumen = '25' AND  YEAR(TanggalPernyataan) = '" . date("Y") . "' ";
$data_urut = $sqlLib->select($sql_urut);
$urut = $data_urut[0]['Urut'] + 1;
$nomor = str_pad($urut, 6, '0', STR_PAD_LEFT);
$_POST["nomor"] = $nomor;
$_POST["urut"] = $urut;

//$data_pt[0]['KodeKantor'] . //, strtotime($_POST['tanggalaju'])
$_POST["nomoraju"] =  '0000' . $_POST['kodedokumenbc'] . '-' . substr($data_pt[0]['NomorIdentitas'], 0, 6) . '-' . date("Ymd") . '-' . $nomor;
$_POST['kodekantor'] = $data_pt[0]['KodeKantor'];

$_POST["nomoridentitaspengusaha"] = $data_pt[0]['NomorIdentitas'];
$_POST["kodejenisidentitaspengusaha"] = $data_pt[0]['KodeJenisIdentitas'];
$_POST["namaentitaspengusaha"] = $data_pt[0]['NamaPerusahaan'];
$_POST["alamatentitaspengusaha"] = $data_pt[0]['Alamat'];
$_POST["nomorijinentitaspengusaha"] = $data_pt[0]['NomorIjinEntitas'];
$_POST["tanggalijinentitaspengusaha"] = $data_pt[0]['TanggalIjinEntitas'];
$_POST["nibentitaspengusaha"] = $data_pt[0]['NIB'];

$_POST["alamatentitaspemilik"] = $data_pt[0]['Alamat'];
$_POST["kodejenisidentitaspemilik"] = $data_pt[0]['KodeJenisIdentitas'];
$_POST["namaentitaspemilik"] = $data_pt[0]['NamaPerusahaan'];
$_POST["nibentitaspemilik"] = $data_pt[0]['NIB'];
$_POST["nomoridentitaspemilik"] = $data_pt[0]['NomorIdentitas'];
$_POST["nomorijinentitaspemilik"] = $data_pt[0]['NomorIjinEntitas'];

$_POST["namaentitaspenerima"] = $data_dt[0]['Customer'];
$_POST["alamatentitaspenerima"] = $data_dt[0]['Alamat'];

?>



<div class="col-sm-12">
	<?php if ($alert == "") { ?>
		<div class="form-group">
			<div class="alert alert-light alert-dismissible show fade">
				<div class="alert-body">
					<button class="close" data-dismiss="alert"><span>&times;</span></button>
					<?php echo "Pastikan semua data terisi!!, jika kosong silahakan isi dengan - atau 0." ?>
				</div>
			</div>
		</div>
	<?php } ?>
	<div id="accordion">
		<div class="accordion">
			<div class="accordion-header" role="button" data-toggle="collapse" data-target="#panel-body-1" aria-expanded="true">
				<h4>Header</h4>
			</div>
			<div class="accordion-body collapse" id="panel-body-1" data-parent="#accordion">
				<div class="row">
					<div class="col-12 col-md-4 col-lg-4">
						<div class="card shadow p-1 mb-2 bg-white rounded">
							<div class="card-header" style="border-bottom:solid 0.5px #31708f;">
								<h4>Pengajuan</h4>
							</div>
							<div class="card-body">
								<div class="col-sm-12">
									<label>Nomor Pengajuan</label>
									<input type="text" name="nomoraju" class="form-control" readonly="readonly" value="<?php echo $_POST["nomoraju"] ?>">
									<input type="hidden" name="nomor" class="form-control" readonly="readonly" value="<?php echo $_POST["nomor"] ?>">
									<input type="hidden" name="urut" class="form-control" readonly="readonly" value="<?php echo $_POST["urut"] ?>">
									<input type="hidden" name="kodestatuspemilik" class="form-control" readonly="readonly" value="<?php echo $_POST["kodestatuspemilik"] ?>">
								</div>
							</div>
						</div>
					</div>

					<div class="col-12 col-md-4 col-lg-4">
						<div class="card shadow p-1 mb-2 bg-white rounded">
							<div class="card-header" style="border-bottom:solid 0.5px #31708f;">
								<h4>Kantor Pabean</h4>
							</div>
							<div class="card-body">
								<div class="col-sm-12 mt-3">
									<label>Kantor Pabean</label>
									<select class="form-control" name="kodekantor" required="required">
										<option value="">-Pilih-</option>
										<?php
										$sql_kp = "SELECT a.KodeKantor, a.NamaKantor
					                                FROM ms_kantor a
					                                WHERE a.KodeKantor !='' ";
										$data_kp = $sqlLib->select($sql_kp);
										foreach ($data_kp as $row_kp) {
										?>
											<option value="<?php echo $row_kp['KodeKantor'] ?>" <?php if ($_POST['kodekantor'] == $row_kp['KodeKantor']) {
																									echo "selected";
																								} ?>><?php echo '(' . $row_kp['KodeKantor'] . ') ' . $row_kp['NamaKantor'] ?></option>
										<?php
										}
										?>

									</select>
								</div>
							</div>
						</div>
					</div>

					<div class="col-12 col-md-4 col-lg-4">
						<div class="card shadow p-1 mb-2 bg-white rounded">
							<div class="card-header" style="border-bottom:solid 0.5px #31708f;">
								<h4>Keterangan Lain</h4>
							</div>
							<div class="card-body">
								<div class="col-sm-12">
									<label>Jenis TPB</label>
									<select class="form-control" name="kodejenistpb" required="required">
										<option value="">-Pilih-</option>
										<option value="1" <?php if ($_POST['kodejenistpb'] == "1") {
																echo "selected";
															} ?>>Kawasan Berikat</option>
										<option value="2" <?php if ($_POST['kodejenistpb'] == "2") {
																echo "selected";
															} ?>>Gudang Berikat</option>
										<option value="3" <?php if ($_POST['kodejenistpb'] == "3") {
																echo "selected";
															} ?>>TPPB</option>
										<option value="4" <?php if ($_POST['kodejenistpb'] == "4") {
																echo "selected";
															} ?>>TBB</option>
										<option value="5" <?php if ($_POST['kodejenistpb'] == "5") {
																echo "selected";
															} ?>>TLB</option>
										<option value="6" <?php if ($_POST['kodejenistpb'] == "6") {
																echo "selected";
															} ?>>KDUB</option>
										<option value="7" <?php if ($_POST['kodejenistpb'] == "7") {
																echo "selected";
															} ?>>PLB</option>
										<option value="8" <?php if ($_POST['kodejenistpb'] == "8") {
																echo "selected";
															} ?>>LAINNYA</option>

									</select>
								</div>
								<div class="col-sm-12 mt-3">
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
								<div class="col-sm-12 mt-3">
									<label>Cara Bayar</label>
									<select class="form-control" name="kodecarabayar" required="required">
										<option value="">-Pilih-</option>
										<?php
										$sql_cb = "SELECT a.KodeCaraBayar, a.NamaCaraBayar
                                                    FROM ms_cara_bayar a
                                                    WHERE a.KodeCaraBayar !='' ";
										$data_cb = $sqlLib->select($sql_cb);
										foreach ($data_cb as $row_cb) {
										?>
											<option value="<?php echo $row_cb['KodeCaraBayar'] ?>" <?php if ($_POST['kodecarabayar'] == $row_cb['KodeCaraBayar']) {
																										echo "selected";
																									} ?>><?php echo '(' . $row_cb['KodeCaraBayar'] . ') ' . $row_cb['NamaCaraBayar'] ?></option>
										<?php
										}
										?>
									</select>
								</div>
								<div class="col-sm-12 mt-3">
									<label>Lokasi Bayar</label>
									<select name="kodelokasibayar" class="form-control" required="required">
										<option value="">-Pilih-</option>
										<option value="1" <?php if ($_POST['kodelokasibayar'] == "1") {
																echo "selected";
															} ?>>(1) BANK</option>
										<option value="2" <?php if ($_POST['kodelokasibayar'] == "2") {
																echo "selected";
															} ?>>(2) POS</option>
										<option value="3" <?php if ($_POST['kodelokasibayar'] == "3") {
																echo "selected";
															} ?>>(3) KANTOR PABEAN</option>
										<option value="4" <?php if ($_POST['kodelokasibayar'] == "4") {
																echo "selected";
															} ?>>(4) NTPN</option>

									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="accordion">
			<div class="accordion-header" role="button" data-toggle="collapse" data-target="#panel-body-2">
				<h4>Entitas</h4>
			</div>
			<div class="accordion-body collapse" id="panel-body-2" data-parent="#accordion">
				<div class="row">
					<div class="col-12 col-md-4 col-lg-4">
						<div class="card shadow p-1 mb-2 bg-white rounded">
							<div class="card-header" style="border-bottom:solid 0.5px #31708f;">
								<h4>Pengusaha TPB / Pengusaha Kena Pajak</h4>
							</div>
							<div class="card-body">
								<div class="form-group row">
									<div class="col-sm-12">
										<label>NPWP</label>
										<input type="text" name="nomoridentitaspengusaha" class="form-control" readonly="readonly" value="<?php echo $_POST["nomoridentitaspengusaha"] ?>">
										<input type="hidden" name="kodejenisidentitaspengusaha" class="form-control" readonly="readonly" value="<?php echo $_POST["kodejenisidentitaspengusaha"] ?>">
									</div>
									<div class="col-sm-12 mt-3">
										<label>Nama</label>
										<input type="text" name="namaentitaspengusaha" class="form-control" readonly="readonly" value="<?php echo $_POST["namaentitaspengusaha"] ?>">
									</div>
									<div class="col-sm-12 mt-3">
										<label>Alamat</label>
										<textarea class="form-control" style="height: 100px;" id="alamatentitaspengusaha" name="alamatentitaspengusaha"><?php echo $_POST["alamatentitaspengusaha"] ?></textarea>
									</div>
									<div class="col-sm-6 mt-3">
										<label>Nomor Izin TPB</label>
										<input type="text" name="nomorijinentitaspengusaha" class="form-control" readonly="readonly" value="<?php echo $_POST["nomorijinentitaspengusaha"] ?>">
									</div>
									<div class="col-sm-6 mt-3">
										<label>Tanggal Izin TPB</label>
										<input type="text" name="tanggalijinentitaspengusaha" class="form-control" readonly="readonly" value="<?php echo $_POST["tanggalijinentitaspengusaha"] ?>">
									</div>
									<div class="col-sm-12 mt-3">
										<label>NIB</label>
										<input type="text" name="nibentitaspengusaha" class="form-control" readonly="readonly" value="<?php echo $_POST["nibentitaspengusaha"] ?>">
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-md-4 col-lg-4">
						<div class="card shadow p-1 mb-2 bg-white rounded">
							<div class="card-header" style="border-bottom:solid 0.5px #31708f;">
								<h4>Pemilik Barang</h4>
							</div>
							<div class="card-body">
								<div class="form-group row">
									<div class="col-sm-12">
										<label>NPWP</label>
										<input type="text" name="nomoridentitaspemilik" class="form-control" readonly="readonly" value="<?php echo $_POST["nomoridentitaspemilik"] ?>">
										<input type="hidden" name="kodejenisidentitaspemilik" class="form-control" readonly="readonly" value="<?php echo $_POST["kodejenisidentitaspemilik"] ?>">
										<input type="hidden" name="nibentitaspemilik" class="form-control" readonly="readonly" value="<?php echo $_POST["nibentitaspemilik"] ?>">
									</div>
									<div class="col-sm-12 mt-3">
										<label>Nama</label>
										<input type="text" name="namaentitaspemilik" class="form-control" readonly="readonly" value="<?php echo $_POST["namaentitaspemilik"] ?>">
									</div>
									<div class="col-sm-12 mt-3">
										<label>Alamat</label>
										<textarea class="form-control" style="height: 100px;" id="alamatentitaspemilik" name="alamatentitaspemilik"><?php echo $_POST["alamatentitaspemilik"] ?></textarea>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-md-4 col-lg-4">
						<div class="card shadow p-1 mb-2 bg-white rounded">
							<div class="card-header" style="border-bottom:solid 0.5px #31708f;">
								<h4>Penerima Barang</h4>
							</div>
							<div class="card-body">
								<div class="form-group row">
									<div class="col-sm-6">
										<label>NPWP</label>
										<input type="text" name="nomoridentitaspenerima" required="required" class="form-control" value="<?php echo $_POST["nomoridentitaspenerima"] ?>">
									</div>
									<div class="col-sm-6">
										<label>Jenis Identitas</label>
										<select class="form-control" name="kodejenisidentitaspenerima" required="required">
											<option value="">-Pilih-</option>
											<option value="0" <?php if ($_POST['kodejenisidentitaspenerima'] == "0") {
																	echo "selected";
																} ?>>NPWP 12 Digit</option>
											<option value="1" <?php if ($_POST['kodejenisidentitaspenerima'] == "1") {
																	echo "selected";
																} ?>>NPWP 10 Digit</option>
											<option value="2" <?php if ($_POST['kodejenisidentitaspenerima'] == "2") {
																	echo "selected";
																} ?>>Paspor</option>
											<option value="3" <?php if ($_POST['kodejenisidentitaspenerima'] == "3") {
																	echo "selected";
																} ?>>KTP</option>
											<option value="4" <?php if ($_POST['kodejenisidentitaspenerima'] == "4") {
																	echo "selected";
																} ?>>Lainnya</option>
											<option value="5" <?php if ($_POST['kodejenisidentitaspenerima'] == "5") {
																	echo "selected";
																} ?>>NPWP 15 Digit</option>
										</select>
									</div>

									<div class="col-sm-12 mt-3">
										<label>Nama</label>
										<input type="text" name="namaentitaspenerima" class="form-control" readonly="readonly" value="<?php echo $_POST["namaentitaspenerima"] ?>">
									</div>

									<div class="col-sm-12 mt-3">
										<label>Alamat</label>
										<textarea class="form-control" style="height: 100px;" id="alamatentitaspenerima" name="alamatentitaspenerima"><?php echo $_POST["alamatentitaspenerima"] ?></textarea>
									</div>
									<div class="col-sm-6 mt-3">
										<label>Nomor Izin TPB</label>
										<input type="text" name="nomorijinentitaspenerima" class="form-control" value="<?php echo $_POST["nomorijinentitaspenerima"] ?>">
									</div>
									<div class="col-sm-6 mt-3">
										<label>Tanggal Izin TPB</label>
										<input type="text" name="tanggalijinentitaspenerima" value="<?php echo $_POST["tanggalijinentitaspenerima"] ?>" class="form-control datepicker" readonly="readonly">

									</div>
									<div class="col-sm-12 mt-3">
										<label>Status Penerima</label>
										<select class="form-control" name="kodestatuspenerima" required="required">
											<option value="">-Pilih-</option>
											<option value="1" <?php if ($_POST['kodestatuspenerima'] == "1") {
																	echo "selected";
																} ?>>Koperasi</option>
											<option value="2" <?php if ($_POST['kodestatuspenerima'] == "2") {
																	echo "selected";
																} ?>>PMDN (Migas)</option>
											<option value="3" <?php if ($_POST['kodestatuspenerima'] == "3") {
																	echo "selected";
																} ?>>PMDN (Non Migas)</option>
											<option value="4" <?php if ($_POST['kodestatuspenerima'] == "4") {
																	echo "selected";
																} ?>>PMA (Migas)</option>
											<option value="5" <?php if ($_POST['kodestatuspenerima'] == "5") {
																	echo "selected";
																} ?>>PMA (Non Migas)</option>
											<option value="8" <?php if ($_POST['kodestatuspenerima'] == "8") {
																	echo "selected";
																} ?>>Perorangan</option>
											<option value="9" <?php if ($_POST['kodestatuspenerima'] == "9") {
																	echo "selected";
																} ?>>Usaha Kecil, Mikro, dan Menengah</option>
											<option value="10" <?php if ($_POST['kodestatuspenerima'] == "10") {
																	echo "selected";
																} ?>>Lainnya</option>
										</select>
									</div>
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>

		<div class="accordion">
			<div class="accordion-header" role="button" data-toggle="collapse" data-target="#panel-body-3">
				<h4>Dokumen</h4>
			</div>
			<div class="accordion-body collapse" id="panel-body-3" data-parent="#accordion">
				<div class="row">
					<div class="col-12">
						<div class="card shadow p-1 mb-2 bg-white rounded">
							<div class="card-header" style="border-bottom:solid 0.5px #31708f;">
								<h4>Dokumen</h4>
								<a href="javascript:void(0);" onclick="popup('nometer', 'master/ceisa/listso/add_dokumen.php', '1100', '500')">
									<button class="btn btn-primary" type="button" style="border-radius: 0;"><i class="fa fa-plus"></i> Dokumen </button>

								</a>
							</div>
							<div class="card-body">
								<div class="form-group row">
									<div class="col-sm-12">
										<table class="table table-hover">
											<thead>
												<tr>
													<th scope="col">No</th>
													<th scope="col">Nama Dokumen</th>
													<th scope="col">Nomor Dokumen</th>
													<th scope="col">Tanggal Dokumen</th>
													<th scope="col">Edit</th>
												</tr>
											</thead>
											<tbody>
												<?php
												$dokno = 1;
												$sql_dok = "SELECT a.SeqDokTmp, a.KodeDokumen,b.NamaDokumen, a.NomorDokumen, a.TanggalDokumen,
																	a.KodeFasilitas,a.KodeIjin,a.RecUser, a.RecDate
															FROM BC_DOKUMEN_TMP a
															LEFT JOIN ms_kode_dokumen b on b.KodeDokumen = a.KodeDokumen ";
												$data_dok = $sqlLib->select($sql_dok);
												foreach ($data_dok as $row_dok) {
												?>
													<tr>
														<td><?php echo $dokno; ?></td>
														<td><?php echo '(' . $row_dok['KodeDokumen'] . ')' . $row_dok['NamaDokumen'] ?></td>
														<td><?php echo $row_dok['NomorDokumen'] ?></td>
														<td><?php echo date("d-M-Y", strtotime($row_dok['TanggalDokumen'])) ?></td>
														<td>
															<input type="hidden" class="form-control" name="seqdoktmp<?php echo $dokno ?>" value="<?php echo $row_dok['SeqDokTmp'] ?>">
															<input type="hidden" class="form-control" name="namadokumen<?php echo $dokno ?>" value="<?php echo $row_dok['NamaDokumen'] ?>">
															<input type="hidden" class="form-control" name="kodedokumen<?php echo $dokno ?>" value="<?php echo $row_dok['KodeDokumen'] ?>">
															<input type="hidden" class="form-control" name="nomordokumen<?php echo $dokno ?>" value="<?php echo $row_dok['NomorDokumen'] ?>">
															<input type="hidden" class="form-control datepicker" name="tanggaldokumen<?php echo $dokno ?>" value="<?php echo $row_dok['TanggalDokumen'] ?>">
															<a href="javascript:void(0);" onclick="popup('nometer', 'master/ceisa/listso/add_dokumen.php?seq=<?php echo $row_dok['SeqDokTmp'] ?>', '1100', '500')">
																<button class="btn btn-success" type="button" style="border-radius: 0;"><i class="fa fa-edit"></i> Dokumen </button>
														</td>
													</tr>
												<?php

												}
												$dokno++;
												?>
												<input type="hidden" name="jmldok" id="jmldok" value="<?php echo $dokno; ?>">
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="accordion">
			<div class="accordion-header" role="button" data-toggle="collapse" data-target="#panel-body-4">
				<h4>Pengangkut</h4>
			</div>
			<div class="accordion-body collapse" id="panel-body-4" data-parent="#accordion">
				<div class="row">
					<div class="col-12">
						<div class="card shadow p-1 mb-2 bg-white rounded">
							<div class="card-header" style="border-bottom:solid 0.5px #31708f;">
								<h4>Pengangkut</h4>
							</div>
							<div class="card-body">
								<div class="form-group row">
									<div class="col-sm-12">
										<label>Cara Pengangkutan</label>
										<select class="form-control" name="kodecaraangkut" required="required">
											<option value="">-Pilih-</option>
											<option value="1" <?php if ($_POST['kodecaraangkut'] == "1") {
																	echo "selected";
																} ?>>Laut</option>
											<option value="2" <?php if ($_POST['kodecaraangkut'] == "2") {
																	echo "selected";
																} ?>>Kereta Api</option>
											<option value="3" <?php if ($_POST['kodecaraangkut'] == "3") {
																	echo "selected";
																} ?>>Darat</option>
											<option value="4" <?php if ($_POST['kodecaraangkut'] == "4") {
																	echo "selected";
																} ?>>Udara</option>
											<option value="5" <?php if ($_POST['kodecaraangkut'] == "5") {
																	echo "selected";
																} ?>>Pos</option>
											<option value="6" <?php if ($_POST['kodecaraangkut'] == "6") {
																	echo "selected";
																} ?>>Multimoda</option>
											<option value="7" <?php if ($_POST['kodecaraangkut'] == "7") {
																	echo "selected";
																} ?>>Instalasi/Pipa</option>
											<option value="8" <?php if ($_POST['kodecaraangkut'] == "8") {
																	echo "selected";
																} ?>>Perairan</option>
											<option value="9" <?php if ($_POST['kodecaraangkut'] == "9") {
																	echo "selected";
																} ?>>Lainnya</option>
										</select>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="accordion">
			<div class="accordion-header" role="button" data-toggle="collapse" data-target="#panel-body-5">
				<h4>Kemasan Dan Peti Kemas</h4>
			</div>
			<div class="accordion-body collapse" id="panel-body-5" data-parent="#accordion">
				<div class="row">
					<div class="col-6">
						<div class="card shadow p-1 mb-2 bg-white rounded">
							<div class="card-header" style="border-bottom:solid 0.5px #31708f;">
								<h4>Kemasan</h4>
							</div>
							<div class="card-body">
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

							</div>
						</div>
					</div>
					<div class="col-6">
						<div class="card shadow p-1 mb-2 bg-white rounded">
							<div class="card-header" style="border-bottom:solid 0.5px #31708f;">
								<h4>Peti Kemas</h4>
							</div>
							<div class="card-body">
								<div class="form-group row">
									<label class="col-sm-4 col-form-label">Nomor Kontainer</label>
									<div class="col-sm-8">
										<input type="text" name="nomorkontainer" id="nomorkontainer" class="form-control" value="<?php echo $_POST["nomorkontainer"] ?>">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 col-form-label">Ukuran Kontainer</label>
									<div class="col-sm-8">
										<select class="form-control" name="kodeukurankontainer" required="required">
											<option value="">-Pilih-</option>
											<option value="20" <?php if ($_POST['kodeukurankontainer'] == "20") {
																	echo "selected";
																} ?>>20 FEET</option>
											<option value="40" <?php if ($_POST['kodeukurankontainer'] == "40") {
																	echo "selected";
																} ?>>40 FEET</option>
											<option value="45" <?php if ($_POST['kodeukurankontainer'] == "45") {
																	echo "selected";
																} ?>>45 FEET</option>
											<option value="60" <?php if ($_POST['kodeukurankontainer'] == "60") {
																	echo "selected";
																} ?>>60 FEET</option>

										</select>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 col-form-label">Jenis Kontainer</label>
									<div class="col-sm-8">
										<select class="form-control" name="kodejeniskontainer" required="required">
											<option value="">-Pilih-</option>
											<option value="4" <?php if ($_POST['kodejeniskontainer'] == "4") {
																	echo "selected";
																} ?>>EMPTY</option>
											<option value="8" <?php if ($_POST['kodejeniskontainer'] == "8") {
																	echo "selected";
																} ?>>FCL</option>
											<option value="7" <?php if ($_POST['kodejeniskontainer'] == "7") {
																	echo "selected";
																} ?>>LCL</option>

										</select>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 col-form-label">Tipe Kontainer</label>
									<div class="col-sm-8">
										<select class="form-control" name="kodetipekontainer" required="required">
											<option value="">-Pilih-</option>
											<option value="1" <?php if ($_POST['kodetipekontainer'] == "1") {
																	echo "selected";
																} ?>>General / Dry Cargo</option>
											<option value="2" <?php if ($_POST['kodetipekontainer'] == "2") {
																	echo "selected";
																} ?>>Tunne Type</option>
											<option value="3" <?php if ($_POST['kodetipekontainer'] == "3") {
																	echo "selected";
																} ?>>Open Top Steel</option>
											<option value="4" <?php if ($_POST['kodetipekontainer'] == "4") {
																	echo "selected";
																} ?>>Flat Rack</option>
											<option value="5" <?php if ($_POST['kodetipekontainer'] == "5") {
																	echo "selected";
																} ?>>Reefer/Refregete</option>
											<option value="6" <?php if ($_POST['kodetipekontainer'] == "6") {
																	echo "selected";
																} ?>>Barge Container</option>
											<option value="7" <?php if ($_POST['kodetipekontainer'] == "7") {
																	echo "selected";
																} ?>>Bulk Container</option>
											<option value="8" <?php if ($_POST['kodetipekontainer'] == "8") {
																	echo "selected";
																} ?>>Isotank</option>
											<option value="99" <?php if ($_POST['kodetipekontainer'] == "99") {
																	echo "selected";
																} ?>>Lain-lain</option>


										</select>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="accordion">
			<div class="accordion-header" role="button" data-toggle="collapse" data-target="#panel-body-6">
				<h4>Transaksi</h4>
			</div>
			<div class="accordion-body collapse" id="panel-body-6" data-parent="#accordion">
				<div class="row">
					<div class="col-12 col-md-4 col-lg-4">
						<div class="card shadow p-1 mb-2 bg-white rounded">
							<div class="card-header" style="border-bottom:solid 0.5px #31708f;">
								<h4>Harga</h4>
							</div>
							<div class="card-body">
								<div class="form-group row">
									<div class="col-sm-12">
										<label>Valuta</label>
										<input type="text" name="namavaluta" id="namavaluta" class="form-control" required="required" value="<?php echo $_POST["namavaluta"] ?>">
										<input type="hidden" name="kodevaluta" id="kodevaluta" class="form-control" value="<?php echo $_POST["kodevaluta"] ?>">
									</div>
									<div class="col-sm-12 mt-3">
										<label>NDPBM</label>
										<input type="text" name="ndpbm" class="form-control" required="required" value="<?php echo $_POST["ndpbm"] ?>">
									</div>
									<div class="col-sm-12 mt-3">
										<label>Nilai CIF</label>
										<input type="text" name="cif" class="form-control" value="<?php echo $_POST["cif"] ?>">
									</div>
									<div class="col-sm-12 mt-3">
										<label>Nilai Pabean</label>
										<input type="text" name="nilaibarang" class="form-control" value="<?php echo $_POST["nilaibarang"] ?>">
									</div>
									<div class="col-sm-12 mt-3">
										<label>Harga Penyerahan/Harga Jual/Harga Barang</label>
										<input type="text" name="hargapenyerahan" class="form-control" value="<?php echo $_POST["hargapenyerahan"] ?>">
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-md-4 col-lg-4">
						<div class="card shadow p-1 mb-2 bg-white rounded">
							<div class="card-header" style="border-bottom:solid 0.5px #31708f;">
								<h4>Harga Lainnya</h4>
							</div>
							<div class="card-body">
								<div class="form-group row">
									<div class="col-sm-12">
										<label>Uang Muka</label>
										<input type="text" name="uangmuka" class="form-control" value="<?php echo $_POST["uangmuka"] ?>">
									</div>
									<div class="col-sm-12 mt-3">
										<label>Diskon</label>
										<input type="text" name="diskon" class="form-control" value="<?php echo $_POST["diskon"] ?>">
									</div>
									<div class="col-sm-12 mt-3">
										<label>Dasar Pengenaan Pajak</label>
										<input type="text" name="dasarpengenaanpajak" class="form-control" value="<?php echo $_POST["dasarpengenaanpajak"] ?>">
									</div>

								</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-md-4 col-lg-4">
						<div class="card shadow p-1 mb-2 bg-white rounded">
							<div class="card-header" style="border-bottom:solid 0.5px #31708f;">
								<h4>Berat</h4>
							</div>
							<div class="card-body">
								<div class="form-group row">
									<div class="col-sm-12">
										<label>Berat Kotor</label>
										<input type="text" name="bruto" class="form-control" value="<?php echo $_POST["bruto"] ?>">
									</div>
									<div class="col-sm-12 mt-3">
										<label>Berat Bersih</label>
										<input type="text" name="netto" class="form-control" value="<?php echo $_POST["netto"] ?>">
									</div>

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="accordion">
			<div class="accordion-header" role="button" data-toggle="collapse" data-target="#panel-body-7">
				<h4>Barang</h4>
			</div>
			<div class="accordion-body collapse" id="panel-body-7" data-parent="#accordion">
				<?php
				$no = 1;
				foreach ($data_dt as $row) { ?>
					<script>
						$(document).ready(function() {
							var ac_config = {
								source: "json/kemasan.php",
								select: function(event, ui) {
									$("#kodejeniskemasan<?php echo $no ?>").val(ui.item.id);
									$("#jeniskemasan<?php echo $no ?>").val(ui.item.jeniskemasan);
								},
								focus: function(event, ui) {
									$("#kodejeniskemasan<?php echo $no ?>").val(ui.item.id);
									$("#jeniskemasan<?php echo $no ?>").val(ui.item.jeniskemasan);
								},
								minLength: 1
							};
							$("#jeniskemasan<?php echo $no ?>").autocomplete(ac_config);
						});
						$(document).ready(function() {
							var ac_config = {
								source: "json/negara.php",
								select: function(event, ui) {
									$("#kodenegara<?php echo $no ?>").val(ui.item.id);
									$("#namanegara<?php echo $no ?>").val(ui.item.namanegara);
								},
								focus: function(event, ui) {
									$("#kodenegara<?php echo $no ?>").val(ui.item.id);
									$("#namanegara<?php echo $no ?>").val(ui.item.namanegara);
								},
								minLength: 1
							};
							$("#namanegara<?php echo $no ?>").autocomplete(ac_config);
						});
						$(document).ready(function() {
							var ac_config = {
								source: "json/daerah.php",
								select: function(event, ui) {
									$("#kodedaerah<?php echo $no ?>").val(ui.item.id);
									$("#namadaerah<?php echo $no ?>").val(ui.item.namadaerah);
								},
								focus: function(event, ui) {
									$("#kodedaerah<?php echo $no ?>").val(ui.item.id);
									$("#namadaerah<?php echo $no ?>").val(ui.item.namadaerah);
								},
								minLength: 1
							};
							$("#namadaerah<?php echo $no ?>").autocomplete(ac_config);
						});
						$(document).ready(function() {
							var ac_config = {
								source: "json/satuan.php",
								select: function(event, ui) {
									$("#kodesatuanbarang<?php echo $no ?>").val(ui.item.id);
									$("#namasatuanbarang<?php echo $no ?>").val(ui.item.namasatuanbarang);
								},
								focus: function(event, ui) {
									$("#kodesatuanbarang<?php echo $no ?>").val(ui.item.id);
									$("#namasatuanbarang<?php echo $no ?>").val(ui.item.namasatuanbarang);
								},
								minLength: 1
							};
							$("#namasatuanbarang<?php echo $no ?>").autocomplete(ac_config);
						});
					</script>
					<div class="row">
						<div class="col-12">
							<div class="card mb-0" style="border-bottom:solid 0.5px #31708f;">
								<div class="form-group row mt-1 mb-1">
									<div class="col-sm-2  ml-3">
										<?php echo $row['NamaBarang'] ?> (<?php echo $row['Qty'] ?>)
									</div>
									<div class="col-sm-2  ml-3">
										<button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample<?php echo $row['SeqItem'] ?>" aria-expanded="false" aria-controls="collapseExample<?php echo $row['SeqItem'] ?>">
											Detail
										</button>
									</div>
								</div>
								<div class="collapse" id="collapseExample<?php echo $row['SeqItem'] ?>">
									
									<div class="row">
										<div class="col-12">
											<div class="card shadow p-1 mb-2 bg-white rounded">
												<div class="card-header" style="border-bottom:solid 0.5px #31708f;">
													<h4>Tarif Barang</h4>
													<a href="javascript:void(0);" onclick="popup('nometer', 'master/ceisa/listso/add_pungutan.php?seqitem=<?php echo $row['SeqItem']; ?>', '700', '500')">
														<button class="btn btn-primary" type="button" style="border-radius: 0;"><i class="fa fa-plus"></i> Tarif </button> </a>
												</div>
												<div class="card-body">
													<div class="form-group row">
														<div class="col-sm-12">
															<table class="table table-hover">
																<thead>
																	<tr>
																		<th scope="col">No</th>
																		<th scope="col">Pungutan</th>
																		<th scope="col">Jenis Tarif</th>
																		<th scope="col">Tarif</th>
																		<th scope="col">Fasilitas</th>
																		<th scope="col">Edit</th>
																	</tr>
																</thead>
																<tbody>
																	<?php
																	$tarifno = 1;
																	$sql_tarif = "SELECT a.SeqBT, a.KodePungutan,
																					CASE WHEN a.KodeTarif='1' THEN 'Advalorum' 
																					WHEN a.KodeTarif='2' THEN 'Spesifik' ELSE '' END as KodeTarif,a.Tarif, 
																					CASE WHEN a.KodeFasilitas ='1' THEN 'Dibayar' 
																					WHEN a.KodeFasilitas ='2' THEN 'Ditanggung Pemerintah' 
																					WHEN a.KodeFasilitas ='3' THEN 'Ditangguhkan' 
																					WHEN a.KodeFasilitas ='4' THEN 'Berkala' 
																					WHEN a.KodeFasilitas ='5' THEN 'Dibebaskan' 
																					WHEN a.KodeFasilitas ='6' THEN 'Tidak dipungut' 
																					WHEN a.KodeFasilitas ='7' THEN 'Sudah dilunasi' 
																					WHEN a.KodeFasilitas ='8' THEN 'Dijaminkan' 
																					WHEN a.KodeFasilitas ='9' THEN 'Ditunda'  ELSE '' END as KodeFasilitas
																					FROM BC_BARANG_TARIF_TMP a
																					WHERE a.SeqItem = '" . $row['SeqItem'] . "' ";
																	$data_datif = $sqlLib->select($sql_tarif);
																	foreach ($data_datif as $row_tarif) {
																	?>
																		<tr>
																			<td><?php echo $tarifno; ?></td>
																			<td><?php echo $row_tarif['KodePungutan'] ?></td>
																			<td><?php echo $row_tarif['KodeTarif'] ?></td>
																			<td><?php echo $row_tarif['Tarif'] ?></td>
																			<td><?php echo $row_tarif['KodeFasilitas'] ?></td>
																			<td>
																				<input type="hidden" class="form-control" name="seqbt<?php echo $tarifno ?>" value="<?php echo $row_tarif['SeqBT'] ?>">
																				<input type="hidden" class="form-control" name="kodepungutan<?php echo $tarifno ?>" value="<?php echo $row_tarif['KodePungutan'] ?>">
																				<input type="hidden" class="form-control" name="kodetarif<?php echo $tarifno ?>" value="<?php echo $row_tarif['KodeTarif'] ?>">
																				<input type="hidden" class="form-control" name="tarif<?php echo $tarifno ?>" value="<?php echo $row_tarif['Tarif'] ?>">
																				<input type="hidden" class="form-control" name="kodefasilitastarif<?php echo $tarifno ?>" value="<?php echo $row_tarif['KodeFasilitas'] ?>">
																				<a href="javascript:void(0);" onclick="popup('nometer', 'master/ceisa/listso/add_pungutan.php?seq=<?php echo $row_tarif['SeqBT'] ?>', '1100', '500')">
																					<button class="btn btn-success" type="button" style="border-radius: 0;"><i class="fa fa-edit"></i> Dokumen </button>
																			</td>
																		</tr>
																	<?php

																	}
																	$tarifno++;
																	?>
																	<input type="hidden" name="jmltarif" id="jmltarif" value="<?php echo ($tarifno - 1); ?>">
																</tbody>
															</table>
														</div>

													</div>
												</div>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-12">
											<div class="card shadow p-1 mb-2 bg-white rounded">
												<div class="card-header" style="border-bottom:solid 0.5px #31708f;">
													<h4>Bahan Baku Import</h4>
													<a href="javascript:void(0);" onclick="popup('nometer', 'master/ceisa/listso/add_bb_import.php?seqitem=<?php echo $row['SeqItem']; ?>', '1100', '600')">
														<button class="btn btn-primary" type="button" style="border-radius: 0;"><i class="fa fa-plus"></i> Bahan Baku Import </button> </a>
												</div>
												<div class="card-body">
													<div class="form-group row">
														<div class="col-sm-12">
															<table class="table table-hover">
																<thead>
																	<tr>
																		<th scope="col">No</th>
																		<th scope="col">Nomor HS</th>
																		<th scope="col">Kode Barang</th>
																		<th scope="col">Uraian</th>
																		<th scope="col">Edit</th>
																		<th scope="col">Tarif</th>
																	</tr>
																</thead>
																<tbody>
																	<?php
																	$imporno = 1;
																	$sql_imp = "SELECT a.*
																					FROM BC_BAHAN_BAKU_TMP a
																					WHERE a.SeqItem = '" . $row['SeqItem'] . "' AND a.KodeAsalBahanBaku ='0' ";
																	$data_imp = $sqlLib->select($sql_imp);
																	foreach ($data_imp as $row_imp) {
																	?>
																		<tr>
																			<td><?php echo $imporno; ?></td>
																			<td><?php echo $row_imp['Hs'] ?></td>
																			<td><?php echo $row_imp['KodeBarang'] ?></td>
																			<td><?php echo $row_imp['Uraian'] ?></td>
																			<td>
																				<input type="hidden" name="seqbb<?php echo $imporno ?>" value="<?php echo $row_imp['SeqBB'] ?>">
																				<input type="hidden" name="kodeasalbahanbaku<?php echo $imporno ?>" value="<?php echo $row_imp['KodeAsalBahanBaku'] ?>">
																				<input type="hidden" name="hs<?php echo $imporno ?>" value="<?php echo $row_imp['Hs'] ?>">
																				<input type="hidden" name="kodebarang<?php echo $imporno ?>" value="<?php echo $row_imp['KodeBarang'] ?>">
																				<input type="hidden" name="uraian<?php echo $imporno ?>" value="<?php echo $row_imp['Uraian'] ?>">
																				<input type="hidden" name="merek<?php echo $imporno ?>" value="<?php echo $row_imp['Merek'] ?>">
																				<input type="hidden" name="tipe<?php echo $imporno ?>" value="<?php echo $row_imp['Tipe'] ?>">
																				<input type="hidden" name="ukuran<?php echo $imporno ?>" value="<?php echo $row_imp['Ukuran'] ?>">
																				<input type="hidden" name="spesifikasilain<?php echo $imporno ?>" value="<?php echo $row_imp['SpesifikasiLain'] ?>">
																				<input type="hidden" name="kodesatuan<?php echo $imporno ?>" value="<?php echo $row_imp['KodeSatuan'] ?>">
																				<input type="hidden" name="jumlahsatuan<?php echo $imporno ?>" value="<?php echo $row_imp['JumlahSatuan'] ?>">
																				<input type="hidden" name="kodedokumenasal<?php echo $imporno ?>" value="<?php echo $row_imp['KodeDokumenAsal'] ?>">
																				<input type="hidden" name="kodekantorasal<?php echo $imporno ?>" value="<?php echo $row_imp['KodeKantorAsal'] ?>">
																				<input type="hidden" name="nomordaftarasal<?php echo $imporno ?>" value="<?php echo $row_imp['NomorDaftarAsal'] ?>">
																				<input type="hidden" name="tanggaldaftarasal<?php echo $imporno ?>" value="<?php echo $row_imp['TanggalDaftarAsal'] ?>">
																				<input type="hidden" name="nomorajuasal<?php echo $imporno ?>" value="<?php echo $row_imp['NomorAjuAsal'] ?>">
																				<input type="hidden" name="cif<?php echo $imporno ?>" value="<?php echo $row_imp['Cif'] ?>">
																				<input type="hidden" name="cifrupiah<?php echo $imporno ?>" value="<?php echo $row_imp['CifRupiah'] ?>">
																				<input type="hidden" name="ndpbm<?php echo $imporno ?>" value="<?php echo $row_imp['Ndpbm'] ?>">
																				<input type="hidden" name="hargapenyerahan<?php echo $imporno ?>" value="<?php echo $row_imp['HargaPenyerahan'] ?>">

																				<a href="javascript:void(0);" onclick="popup('nometer', 'master/ceisa/listso/add_bb_import.php?seqbb=<?php echo $row_imp['SeqBB'] ?>', '1100', '500')">
																					<button class="btn btn-success" type="button" style="border-radius: 0;"><i class="fa fa-edit"></i> Dokumen </button>
																				</a>
																			</td>
																			<td>
																				<a href="javascript:void(0);" onclick="popup('nometer', 'master/ceisa/listso/add_bb_import_tarif.php?seqbb=<?php echo $row_imp['SeqBB'] ?>', '1100', '700')">
																					<button class="btn btn-primary" type="button" style="border-radius: 0;"><i class="fa fa-plus"></i> Tarif </button>
																				</a>
																			</td>
																		</tr>
																	<?php
																		$imporno++;
																	}
																	?>
																	<input type="hidden" name="jmlimpor" id="jmlimpor" value="<?php echo ($imporno - 1); ?>">
															</table>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-12">
											<div class="card shadow p-1 mb-2 bg-white rounded">
												<div class="card-header" style="border-bottom:solid 0.5px #31708f;">
													<h4>Bahan Baku Lokal</h4>
													<a href="javascript:void(0);" onclick="popup('nometer', 'master/ceisa/listso/add_bb_lokal.php?seqitem=<?php echo $row['SeqItem']; ?>', '1100', '600')">
														<button class="btn btn-primary" type="button" style="border-radius: 0;"><i class="fa fa-plus"></i> Bahan Baku Lokal </button> </a>
												</div>
												<div class="card-body">
													<div class="form-group row">
														<div class="col-sm-12">
															<table class="table table-hover">
																<thead>
																	<tr>
																		<th scope="col">No</th>
																		<th scope="col">Nomor HS</th>
																		<th scope="col">Kode Barang</th>
																		<th scope="col">Uraian</th>
																		<th scope="col">Edit</th>
																		<th scope="col">Tarif</th>
																	</tr>
																</thead>
																<tbody>
																	<?php
																	$lokalno = 1;
																	$sql_lok = "SELECT a.*
																					FROM BC_BAHAN_BAKU_TMP a
																					WHERE a.SeqItem = '" . $row['SeqItem'] . "' AND a.KodeAsalBahanBaku ='1' ";
																	$data_lok = $sqlLib->select($sql_lok);
																	foreach ($data_lok as $row_lok) {
																	?>
																		<tr>
																			<td><?php echo $lokalno; ?></td>
																			<td><?php echo $row_lok['Hs'] ?></td>
																			<td><?php echo $row_lok['KodeBarang'] ?></td>
																			<td><?php echo $row_lok['Uraian'] ?></td>
																			<td>
																				<input type="hidden" name="seqbb<?php echo $lokalno ?>" value="<?php echo $row_lok['SeqBB'] ?>">
																				<input type="hidden" name="kodeasalbahanbaku<?php echo $lokalno ?>" value="<?php echo $row_lok['KodeAsalBahanBaku'] ?>">
																				<input type="hidden" name="hs<?php echo $lokalno ?>" value="<?php echo $row_lok['Hs'] ?>">
																				<input type="hidden" name="kodebarang<?php echo $lokalno ?>" value="<?php echo $row_lok['KodeBarang'] ?>">
																				<input type="hidden" name="uraian<?php echo $lokalno ?>" value="<?php echo $row_lok['Uraian'] ?>">
																				<input type="hidden" name="merek<?php echo $lokalno ?>" value="<?php echo $row_lok['Merek'] ?>">
																				<input type="hidden" name="tipe<?php echo $lokalno ?>" value="<?php echo $row_lok['Tipe'] ?>">
																				<input type="hidden" name="ukuran<?php echo $lokalno ?>" value="<?php echo $row_lok['Ukuran'] ?>">
																				<input type="hidden" name="spesifikasilain<?php echo $lokalno ?>" value="<?php echo $row_lok['SpesifikasiLain'] ?>">
																				<input type="hidden" name="kodesatuan<?php echo $lokalno ?>" value="<?php echo $row_lok['KodeSatuan'] ?>">
																				<input type="hidden" name="jumlahsatuan<?php echo $lokalno ?>" value="<?php echo $row_lok['JumlahSatuan'] ?>">
																				<input type="hidden" name="kodedokumenasal<?php echo $lokalno ?>" value="<?php echo $row_lok['KodeDokumenAsal'] ?>">
																				<input type="hidden" name="kodekantorasal<?php echo $lokalno ?>" value="<?php echo $row_lok['KodeKantorAsal'] ?>">
																				<input type="hidden" name="nomordaftarasal<?php echo $lokalno ?>" value="<?php echo $row_lok['NomorDaftarAsal'] ?>">
																				<input type="hidden" name="tanggaldaftarasal<?php echo $lokalno ?>" value="<?php echo $row_lok['TanggalDaftarAsal'] ?>">
																				<input type="hidden" name="nomorajuasal<?php echo $lokalno ?>" value="<?php echo $row_lok['NomorAjuAsal'] ?>">
																				<input type="hidden" name="cif<?php echo $lokalno ?>" value="<?php echo $row_lok['Cif'] ?>">
																				<input type="hidden" name="cifrupiah<?php echo $lokalno ?>" value="<?php echo $row_lok['CifRupiah'] ?>">
																				<input type="hidden" name="ndpbm<?php echo $lokalno ?>" value="<?php echo $row_lok['Ndpbm'] ?>">
																				<input type="hidden" name="hargapenyerahan<?php echo $lokalno ?>" value="<?php echo $row_lok['HargaPenyerahan'] ?>">
																				<a href="javascript:void(0);" onclick="popup('nometer', 'master/ceisa/listso/add_bb_lokal.php?seqbb=<?php echo $row_lok['SeqBB'] ?>', '1100', '500')">
																					<button class="btn btn-success" type="button" style="border-radius: 0;"><i class="fa fa-edit"></i> Dokumen </button>
																				</a>
																			</td>
																			<td>
																				<a href="javascript:void(0);" onclick="popup('nometer', 'master/ceisa/listso/add_bb_lokal_tarif.php?seqbb=<?php echo $row_lok['SeqBB'] ?>', '1100', '700')">
																					<button class="btn btn-primary" type="button" style="border-radius: 0;"><i class="fa fa-plus"></i> Tarif </button>
																				</a>
																			</td>
																		</tr>
																	<?php
																		$lokalno++;
																	}
																	?>
																	<input type="hidden" name="jmllokal" id="jmllokal" value="<?php echo ($lokalno - 1); ?>">
															</table>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-12 col-md-4 col-lg-4">
											<div class="card shadow p-1 mb-2 bg-white rounded">
												<div class="card-body">
													<div class="form-group row">
														<div class="col-sm-12">
															<label>HS Number</label>
															<input type="text" name="hsnumber<?php echo $no ?>" id="hsnumber<?php echo $no ?>" class="form-control">
														</div>
														<div class="col-sm-12 mt-3">
															<label>Kode Barang</label>
															<input type="text" name="kdbarang<?php echo $no ?>" id="kdbarang<?php echo $no ?>" class="form-control" readonly="readonly" value="<?php echo $row['KdBarang'] ?>">
														</div>
														<div class="col-sm-12 mt-3">
															<label>Uraian Barang</label>
															<input type="text" name="namabarang<?php echo $no ?>" id="namabarang<?php echo $no ?>" class="form-control" readonly="readonly" value="<?php echo $row['NamaBarang'] ?>">
														</div>
														<div class="col-sm-12">
															<label>Merk</label>
															<input type="text" name="merk<?php echo $no ?>" id="merk<?php echo $no ?>" class="form-control">
														</div>
														<div class="col-sm-12">
															<label>Tipe</label>
															<input type="text" name="tipe<?php echo $no ?>" id="tipe<?php echo $no ?>" class="form-control">
														</div>
														<div class="col-sm-12">
															<label>Ukuran</label>
															<input type="text" name="ukuran<?php echo $no ?>" id="ukuran<?php echo $no ?>" class="form-control">
														</div>
														<div class="col-sm-12">
															<label>Spesifikasi Lain</label>
															<input type="text" name="spesifikasilain<?php echo $no ?>" id="spesifikasilain<?php echo $no ?>" class="form-control">
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="col-12 col-md-4 col-lg-4">
											<div class="card shadow p-1 mb-2 bg-white rounded">
												<div class="card-body">
													<div class="form-group row">
														<div class="col-sm-12">
															<label>Penggunaan</label>
															<select class="form-control" name="penggunaan<?php echo $no ?>" required="required">
																<option value="">-Pilih-</option>
																<option value="0" <?php if ($_POST['penggunaan'] == "0") {
																						echo "selected";
																					} ?>>0 - BARANG BERHUBUNGAN LANGSUNG</option>
																<option value="1" <?php if ($_POST['penggunaan'] == "1") {
																						echo "selected";
																					} ?>>1 - TIDAK BERHUBUNGAN LANGSUNG</option>
																<option value="2" <?php if ($_POST['penggunaan'] == "2") {
																						echo "selected";
																					} ?>>2 - BARANG KONSUMSI</option>
																<option value="3" <?php if ($_POST['penggunaan'] == "3") {
																						echo "selected";
																					} ?>>3 - BARANG HASIL OLAHAN</option>
																<option value="4" <?php if ($_POST['penggunaan'] == "4") {
																						echo "selected";
																					} ?>>4 - BARANG LAINNYA</option>
															</select>
														</div>
														<div class="col-sm-12 mt-3">
															<label>Kategori Barang</label>
															<select class="form-control" name="kategoribarang<?php echo $no ?>" required="required">
																<option value="">-Pilih-</option>
																<option value="1" <?php if ($_POST['kategoribarang'] == "1") {
																						echo "selected";
																					} ?>>1 - HASIL PRODUKSI</option>
																<option value="2" <?php if ($_POST['kategoribarang'] == "2") {
																						echo "selected";
																					} ?>>2 - BAHAN BAKU</option>
																<option value="3" <?php if ($_POST['kategoribarang'] == "3") {
																						echo "selected";
																					} ?>>3 - BARANG MODAL</option>
																<option value="4" <?php if ($_POST['kategoribarang'] == "4") {
																						echo "selected";
																					} ?>>4 - PERALATAN KANTOR</option>
																<option value="5" <?php if ($_POST['kategoribarang'] == "5") {
																						echo "selected";
																					} ?>>5 - SISA DARI PROSES PRODUKSI/LIMBAH (WASTE/SCRAP) DAN/ATAU SISA ATAU BEKAS PENGEMAS</option>
																<option value="6" <?php if ($_POST['kategoribarang'] == "6") {
																						echo "selected";
																					} ?>>6 - BARANG YANG DITIMBUN UNTUK DIJUAL</option>
																<option value="7" <?php if ($_POST['kategoribarang'] == "7") {
																						echo "selected";
																					} ?>>7 - BARANG YANG DIPAMERKAN UNTUK DIJUAL</option>
																<option value="8" <?php if ($_POST['kategoribarang'] == "8") {
																						echo "selected";
																					} ?>>8 - BARANG LAINNYA</option>
																<option value="8" <?php if ($_POST['kategoribarang'] == "8") {
																						echo "selected";
																					} ?>>8 - BARANG UNTUK KEPERLUAN PENANGANAN COVID19</option>
															</select>
														</div>
														<div class="col-sm-12 mt-3">
															<label>Kondisi Barang</label>
															<select class="form-control" name="kondisibarang<?php echo $no ?>" required="required">
																<option value="">-Pilih-</option>
																<option value="1" <?php if ($_POST['kategoribarang'] == "1") {
																						echo "selected";
																					} ?>>1 - TIDAK RUSAK</option>
																<option value="2" <?php if ($_POST['kategoribarang'] == "2") {
																						echo "selected";
																					} ?>>2 - RUSAK</option>
															</select>
														</div>
														<div class="col-sm-12 mt-3">
															<label>Jangka Waktu</label> <br>
															<input type="checkbox" name="flag4tahun" value="1"> > 4 Tahun
														</div>
														<div class="col-sm-12 mt-3">
															<label>Cara Perhitungan</label>
															<select class="form-control" name="kodeperhitungan<?php echo $no ?>" required="required">
																<option value="">-Pilih-</option>
																<option value="0" <?php if ($_POST['kodeperhitungan'] == "0") {
																						echo "selected";
																					} ?>>0 - HARGA PEMASUKAN</option>
																<option value="1" <?php if ($_POST['kodeperhitungan'] == "1") {
																						echo "selected";
																					} ?>>1 - HARGA PENYERAHAN</option>
															</select>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="col-12 col-md-4 col-lg-4">
											<div class="card shadow p-1 mb-2 bg-white rounded">
												<div class="card-body">
													<div class="form-group row">
														<div class="col-sm-6">
															<label>Satuan</label>
															<input type="text" name="jumlahsatuan<?php echo $no ?>" class="form-control" required="required" placeholder="0.00">
														</div>
														<div class="col-sm-6">
															<label>&nbsp;</label>
															<input type="text" name="namasatuanbarang<?php echo $no ?>" id="namasatuanbarang<?php echo $no ?>" class="form-control">
															<input type="hidden" name="kodesatuanbarang<?php echo $no ?>" id="kodesatuanbarang<?php echo $no ?>" class="form-control">
														</div>

														<div class="col-sm-6 mt-3">
															<label>Kemasan</label>
															<input type="text" name="jumlahkemasan<?php echo $no ?>" class="form-control" placeholder="0">
														</div>
														<div class="col-sm-6 mt-3">
															<label>&nbsp;</label>
															<input type="text" name="jeniskemasan<?php echo $no ?>" id="jeniskemasan<?php echo $no ?>" class="form-control">
															<input type="hidden" name="kodejeniskemasan<?php echo $no ?>" id="kodejeniskemasan<?php echo $no ?>" class="form-control">
														</div>
														<div class="col-sm-12 mt-3">
															<label>Berat Bersih (Kg)</label>
															<input type="text" name="netto_dt<?php echo $no ?>" class="form-control" placeholder="0.00">
														</div>
														<div class="col-sm-12 mt-3">
															<label>Nilai CIF</label>
															<input type="text" name="cif<?php echo $no ?>" class="form-control" placeholder="0.00">
														</div>
														<div class="col-sm-12 mt-3">
															<label>Nilai Pabean</label>
															<input type="text" name="nilaipabean<?php echo $no ?>" class="form-control" placeholder="0.00">
														</div>
														<div class="col-sm-12 mt-3">
															<label>Harga Penyerahan/Harga Jual</label>
															<input type="text" name="hargapenyerahan<?php echo $no ?>" class="form-control" placeholder="0.00">
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>

								</div>

							</div>
						</div>
					</div>

				<?php $no++;
				} ?>
				<input type="hidden" name="jmlrow" id="jmlrow" value="<?php echo $no-1 ?>">
			</div>
		</div>

		<div class="accordion">
			<div class="accordion-header" role="button" data-toggle="collapse" data-target="#panel-body-9">
				<h4>Pernyataan</h4>
			</div>
			<div class="accordion-body collapse" id="panel-body-9" data-parent="#accordion">
				<div class="row">
					<div class="col-12 col-md-6 col-lg-6">
						<div class="card shadow p-1 mb-2 bg-white rounded">
							<div class="card-header" style="border-bottom:solid 0.5px #31708f;">
								<h4>Tempat dan Tanggal</h4>
							</div>
							<div class="card-body">
								<div class="col-sm-12">
									<label>Tempat</label>
									<input type="text" name="kotapernyataan" class="form-control" required="required" value="<?php echo $_POST["kotapernyataan"] ?>">
								</div>
								<div class="col-sm-12">
									<label>Tanggal</label>
									<input type="text" name="tanggalpernyataan" value="<?php echo $_POST['tanggalpernyataan']; ?>" class="form-control datepicker" readonly="readonly">
								</div>
							</div>
						</div>
					</div>

					<div class="col-12 col-md-6 col-lg-6">
						<div class="card shadow p-1 mb-2 bg-white rounded">
							<div class="card-header" style="border-bottom:solid 0.5px #31708f;">
								<h4>Nama Dan Jabatan</h4>
							</div>
							<div class="card-body">
								<div class="col-sm-12">
									<label>Nama</label>
									<input type="text" name="namapernyataan" class="form-control" required="required" value="<?php echo $_POST["namapernyataan"] ?>">
								</div>
								<div class="col-sm-12">
									<label>Jabatan</label>
									<input type="text" name="jabatanpernyataan" class="form-control" required="required" value="<?php echo $_POST["jabatanpernyataan"] ?>">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>


	</div>
</div>