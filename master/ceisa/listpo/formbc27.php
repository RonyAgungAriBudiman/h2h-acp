<?php
$sql_pt = "SELECT a.NamaPerusahaan, a.Alamat, a.KodeJenisIdentitas, a.NomorIdentitas,  a.NIB, a.Nama, a.Jabatan, a.KodeJenisTpb, a.Kota, a.NomorIjinEntitas, 
                      a.TanggalIjinEntitas, a.KodeKantor, a.KantorPabean , a.KodeStatus, b.NamaKantor
			FROM ms_perusahaan a
			LEFT JOIN ms_kantor b on b.KodeKantor = a.KodeKantor
			WHERE a.IDPerusahaan ='1' ";
$data_pt = $sqlLib->select($sql_pt);

$sql_po = "SELECT a.NoPO, a.TanggalPo, a.Vendor, a.Alamat, a.Subtotal, a.Tax2Amount, a.TotalAmount,
            b.KdBarang, b.NamaBarang, b.Harga, b.Qty, b.ItemDiscPercent, b.ItemCost, b.Satuan, b.TotalHarga, b.SeqItem
                          FROM ac_po a
                          LEFT JOIN ac_po_detail b on b.NoPO = a.NoPO
                          WHERE a.NoPO = '" . $_POST["nopo"]  . "' ";
$data_po = $sqlLib->select($sql_po);
$_POST["alamatentitaspengirim"] = $data_po[0]['Alamat'];
$_POST["namaentitaspengirim"] = $data_po[0]['Vendor'];

$sql_urut = "SELECT MAX(Urut) as Urut FROM BC_HEADER 
                      WHERE KodeDokumen = '27' AND  YEAR(TanggalPernyataan) = '" . date("Y") . "' ";
$data_urut = $sqlLib->select($sql_urut);
$urut = $data_urut[0]['Urut'] + 1;
$nomor = str_pad($urut, 6, '0', STR_PAD_LEFT);
$_POST["nomor"] = $nomor;
$_POST["urut"] = $urut;
$_POST["nomoraju"] =  '0000' . $_POST['kodedokumenbc'] . '-' . substr($data_pt[0]['NomorIdentitas'], 0, 6) . '-' . date("Ymd") . '-' . $nomor;
$_POST['kodekantor'] = $data_pt[0]['KodeKantor'];
$_POST['namakantor'] = $data_pt[0]['NamaKantor'];
$_POST['kodejenistpb'] = $data_pt[0]['KodeJenisTpb'];
$_POST["kotattd"] = $data_pt[0]['Kota'];
$_POST["kodestatuspemilik"] = $data_pt[0]['KodeStatus'];

$_POST["nomoridentitaspengusaha"] = $data_pt[0]['NomorIdentitas'];
$_POST["kodejenisidentitaspengusaha"] = $data_pt[0]['KodeJenisIdentitas'];
$_POST["namaentitaspengusaha"] = $data_pt[0]['NamaPerusahaan'];
$_POST["alamatentitaspengusaha"] = $data_pt[0]['Alamat'];
$_POST["nomorijinentitaspengusaha"] = $data_pt[0]['NomorIjinEntitas'];
$_POST["tanggalijinentitaspengusaha"] = $data_pt[0]['TanggalIjinEntitas'];
$_POST["nibentitaspengusaha"] = $data_pt[0]['NIB'];
$_POST["kodestatuspengusaha"] = $data_pt[0]['KodeStatus'];

$_POST["nomoridentitaspemilik"] = $data_pt[0]['NomorIdentitas'];
$_POST["kodejenisidentitaspemilik"] = $data_pt[0]['KodeJenisIdentitas'];
$_POST["namaentitaspemilik"] = $data_pt[0]['NamaPerusahaan'];
$_POST["alamatentitaspemilik"] = $data_pt[0]['Alamat'];
$_POST["kodestatuspemilik"] = $data_pt[0]['KodeStatus']
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
					<div class="col-12 col-md-6 col-lg-6">
						<div class="card shadow p-1 mb-2 bg-white rounded">
							<div class="card-header" style="border-bottom:solid 0.5px #31708f;">
								<h4>Pengajuan Asal</h4>
							</div>
							<div class="card-body">
								<div class="col-sm-12">
									<label>Nomor Pengajuan</label>
									<input type="text" name="nomoraju" class="form-control" readonly="readonly" value="<?php echo $_POST["nomoraju"] ?>">
									<input type="hidden" name="nomor" class="form-control" readonly="readonly" value="<?php echo $_POST["nomor"] ?>">
									<input type="hidden" name="urut" class="form-control" readonly="readonly" value="<?php echo $_POST["urut"] ?>">
								</div>
								<div class="col-sm-12 mt-3">
									<label>Kantor Pabean Asal</label>
									<input type="text" name="namakantor" id="namakantor" class="form-control" value="<?php echo $_POST["namakantor"] ?>">
									<input type="hidden" name="kodekantor" id="kodekantor" class="form-control" value="<?php echo $_POST["kodekantor"] ?>">
								</div>
								<div class="col-sm-12 mt-3">
									<label>Jenis TPB Asal</label>
									<select class="form-control" name="kodejenistpb" required="required">
										<option value="">-Pilih-</option>
										<?php
										$sql_tpb = "SELECT a.KodeJenisTpb, a.JenisTpb
					                                FROM ms_tpb a
					                                WHERE a.JenisTpb !='' ";
										$data_tpb = $sqlLib->select($sql_tpb);
										foreach ($data_tpb as $row_tpb) {
										?>
											<option value="<?php echo $row_tpb['KodeJenisTpb'] ?>" 
												<?php if ($_POST['kodejenistpb'] == $row_tpb['KodeJenisTpb']) {echo "selected";} ?>>
												<?php echo '(' . $row_tpb['KodeJenisTpb'] . ') ' . $row_tpb['JenisTpb'] ?></option>
											<?php
										}
										?>

									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-md-6 col-lg-6">
						<div class="card shadow p-1 mb-2 bg-white rounded">
							<div class="card-header" style="border-bottom:solid 0.5px #31708f;">
								<h4>Pengajuan Tujuan</h4>
							</div>
							<div class="card-body">
								<div class="col-sm-12">
									<label>Kantor Pabean Tujuan</label>
									<input type="text" name="namakantortujuan" id="namakantortujuan" class="form-control" value="<?php echo $_POST["namakantortujuan"] ?>">
									<input type="hidden" name="kodekantortujuan" id="kodekantortujuan" class="form-control" value="<?php echo $_POST["kodekantortujuan"] ?>">
								</div>
								<div class="col-sm-12 mt-3">
									<label>Jenis TPB Tujuan</label>
									<select class="form-control" name="kodejenistpbtujuan" required="required">
										<option value="">-Pilih-</option>
										<?php
										$sql_tpb = "SELECT a.KodeJenisTpb, a.JenisTpb
					                                FROM ms_tpb a
					                                WHERE a.JenisTpb !='' ";
										$data_tpb = $sqlLib->select($sql_tpb);
										foreach ($data_tpb as $row_tpb) {
										?>
											<option value="<?php echo $row_tpb['KodeJenisTpb'] ?>" 
												<?php if ($_POST['kodejenistpbtujuan'] == $row_tpb['KodeJenisTpb']) {echo "selected";} ?>>
												<?php echo '(' . $row_tpb['KodeJenisTpb'] . ') ' . $row_tpb['JenisTpb'] ?></option>
											<?php
										}
										?>

									</select>
								</div>
								<div class="col-sm-12 mt-3">
									<label>Tujuan Pengiriman</label>
									<select class="form-control" name="kodetujuanpengiriman" required="required">
										<option value="">-Pilih-</option>
										<?php
										$sql_kp = "SELECT a.KodeTujuanPengiriman, a.NamaTujuanPengiriman
					                                FROM ms_tujuan_pengiriman a
					                                WHERE a.NamaTujuanPengiriman !='' AND a.KodeDokumen ='27' ";
										$data_kp = $sqlLib->select($sql_kp);
										foreach ($data_kp as $row_kp) {
										?>
											<option value="<?php echo $row_kp['KodeTujuanPengiriman'] ?>" 
												<?php if ($_POST['kodetujuanpengiriman'] == $row_kp['KodeTujuanPengiriman']) {echo "selected";} ?>>
												<?php echo '(' . $row_kp['KodeTujuanPengiriman'] . ') ' . $row_kp['NamaTujuanPengiriman'] ?></option>
										<?php
										}
										?>

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
								<h4>TPB Asal Barang / Pengusaha Kena Pajak</h4>
							</div>
							<div class="card-body">
								<div class="form-group row">
									<div class="col-sm-12">
										<label>NPWP</label>
										<input type="text" name="nomoridentitaspengusaha" class="form-control" readonly="readonly" value="<?php echo $_POST["nomoridentitaspengusaha"] ?>">
										<input type="hidden" name="kodejenisidentitaspengusaha" class="form-control" readonly="readonly" value="<?php echo $_POST["kodejenisidentitaspengusaha"] ?>">
										<input type="hidden" name="kodestatuspengusaha" class="form-control" readonly="readonly" value="<?php echo $_POST["kodestatuspengusaha"] ?>">
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
								<h4>TPB Tujuan Barang / Penerima Barang</h4>
							</div>
							<div class="card-body">
								<div class="form-group row">
									<div class="col-sm-12">
										<label>NPWP</label>
										<input type="text" name="nomoridentitaspenerima" required="required" class="form-control" value="<?php echo $_POST["nomoridentitaspenerima"] ?>">
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
										<input type="text" name="nomorijinentitaspenerima" class="form-control" readonly="readonly" value="<?php echo $_POST["nomorijinentitaspenerima"] ?>">
									</div>
									<div class="col-sm-6 mt-3">
										<label>Tanggal Izin TPB</label>
										<input type="text" name="tanggalijinentitaspenerima" class="form-control" readonly="readonly" value="<?php echo $_POST["tanggalijinentitaspenerima"] ?>">
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
										<input type="hidden" name="kodestatuspemilik" class="form-control" readonly="readonly" value="<?php echo $_POST["kodestatuspemilik"] ?>">
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
					                    <label>Jenis Sarana Pengangkut</label>
					                    <input type="text" name="namapengangkut" class="form-control" required="required" value="<?php echo $_POST["namapengangkut"] ?>">
					                </div>
					                <div class="col-sm-12 mt-3">
					                    <label>Nomor Sarana Pengangkut</label>
					                    <input type="text" name="nomorpengangkut" class="form-control" required="required" value="<?php echo $_POST["nomorpengangkut"] ?>">
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
				<h4>Kemasan dan Peti Kemas</h4>
			</div>
			<div class="accordion-body collapse" id="panel-body-5" data-parent="#accordion">
				<div class="row">
					<div class="col-6">
						<div class="card shadow p-1 mb-2 bg-white rounded">
							<div class="card-header" style="border-bottom:solid 0.5px #31708f;">
								<h4>Kemasan</h4>
								<div class="col-sm-6 mb-2" style="float: right;">
									<a href="javascript:void(0);" onclick="popup('nometer', 'master/ceisa/listpo/add_kemasan.php', '1100', '500')">
										<button class="btn btn-primary" type="button" style="border-radius: 0;"><i class="fa fa-plus"></i> Kemasan</button>
									</a>
                                </div>
							</div>
							<div class="card-body">
								<table class="table table-hover">
									<thead>
										<tr>
											<th scope="col">No</th>
											<th scope="col">Jumlah Kemasan</th>
											<th scope="col">Jenis Kemasan</th>
											<th scope="col">Merek</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$nokemasan = 1;
										$sql_kon = "SELECT a.SeqKemasan, a.KodeKemasan, a.JumlahKemasan, a.Merek, b.JenisKemasan
													FROM BC_KEMASAN_TMP a 
													LEFT JOIN ms_kemasan b on b.KodeJenisKemasan = a.KodeKemasan";
										$data_kon = $sqlLib->select($sql_kon);
										foreach ($data_kon as $row_kon) {
										?>
											<tr>
												<td><?php echo $nokemasan; ?></td>
												<td><?php echo $row_kon['JumlahKemasan'] ?></td>
												<td><?php echo $row_kon['JenisKemasan'] ?></td>
												<td><?php echo $row_kon['Merek'] ?></td>
												<input type="hidden" name="seqkemasan<?php echo $dokno ?>" value="<?php echo $row_kon['SeqKemasan'] ?>">
												<input type="hidden" name="kodejeniskemasan<?php echo $dokno ?>" value="<?php echo $row_kon['KodeKemasan'] ?>">
												<input type="hidden" name="jumlahkemasan<?php echo $dokno ?>" value="<?php echo $row_kon['JumlahKemasan'] ?>">
												<input type="hidden" name="merek<?php echo $dokno ?>" value="<?php echo $row_kon['Merek'] ?>">
											</tr>
										<?php

										}
										$nokemasan++;
										?>
										<input type="hidden" name="jmlkemasan" id="jmlkemasan" value="<?php echo ($nokemasan - 1); ?>">
									</tbody>
								</table>

							</div>
						</div>
					</div>
					<div class="col-6">
						<div class="card shadow p-1 mb-2 bg-white rounded">
							<div class="card-header" style="border-bottom:solid 0.5px #31708f;">
								<h4>Peti Kemas</h4>
								<div class="col-sm-6 mb-2" style="float: right;">
                                        <a href="javascript:void(0);" onclick="popup('nometer', 'master/ceisa/listso/add_kontainer.php', '1100', '500')">
                                            <button class="btn btn-primary" type="button" style="border-radius: 0;"><i class="fa fa-plus"></i> Kontainer </button>
                                        </a>
                                </div>
							</div>
							<div class="card-body">
								<table class="table table-hover">
									<thead>
										<tr>
											<th scope="col">No</th>
											<th scope="col">Nomor Kontainer</th>
											<th scope="col">Ukuran Kontainer</th>
											<th scope="col">Jenis Kontainer</th>
											<th scope="col">Tipe Kontainer</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$nokontainer = 1;
										$sql_kon = "SELECT a.SeqKontainer, a.NomorKontiner, a.KodeUkuranKontainer, a.KodeJenisKontainer, a.KodeTipeKontainer,
										b.UkuranKontainer, c.JenisKontainer, d.TipeKontainer
										FROM BC_KONTAINER_TMP a 
										LEFT JOIN ms_ukuran_kontainer b on b.KodeUkuranKontainer = a.KodeUkuranKontainer
										LEFT JOIN ms_jenis_kontainer c on c.KodeJenisKontainer = a.KodeJenisKontainer
										LEFT JOIN ms_tipe_kontainer d on d.KodeTipeKontainer = a.KodeTipeKontainer";
										$data_kon = $sqlLib->select($sql_kon);
										foreach ($data_kon as $row_kon) {
										?>
											<tr>
												<td><?php echo $nokontainer; ?></td>
												<td><?php echo $row_kon['NomorKontiner'] ?></td>
												<td><?php echo $row_kon['UkuranKontainer'] ?></td>
												<td><?php echo $row_kon['JenisKontainer'] ?></td>
												<td><?php echo $row_kon['TipeKontainer'] ?></td>
												<input type="hidden" name="seqkontainer<?php echo $dokno ?>" value="<?php echo $row_kon['SeqKontainer'] ?>">
												<input type="hidden" name="nomorkontiner<?php echo $dokno ?>" value="<?php echo $row_kon['NomorKontiner'] ?>">
												<input type="hidden" name="kodeukurankontainer<?php echo $dokno ?>" value="<?php echo $row_kon['KodeUkuranKontainer'] ?>">
												<input type="hidden" name="kodejeniskontainer<?php echo $dokno ?>" value="<?php echo $row_kon['KodeJenisKontainer'] ?>">
												<input type="hidden" name="kodetipekontainer<?php echo $dokno ?>" value="<?php echo $row_kon['KodeTipeKontainer'] ?>">
											</tr>
										<?php

										}
										$nokontainer++;
										?>
										<input type="hidden" name="jmlkontainer" id="jmlkontainer" value="<?php echo ($nokontainer - 1); ?>">
									</tbody>
                                </table>
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
										<input type="text" name="cif" class="form-control" required="required" value="<?php echo $_POST["cif"] ?>" placeholder='0'>
									</div>
									<div class="col-sm-12 mt-3">
										<label>Nilai Pabean</label>
										<input type="text" name="nilaipabean" class="form-control" required="required" value="<?php echo $_POST["nilaipabean"] ?>" placeholder='0'>
									</div>
									<div class="col-sm-12 mt-3">
										<?php $_POST["hargapenyerahan"] = $data_po[0]['TotalAmount'];?>
										<label>Harga Penyerahan/Harga Jual/Harga Barang</label>
										<input type="text" name="hargapenyerahan" class="form-control" required="required" value="<?php echo number_format($_POST["hargapenyerahan"]) ?>" placeholder='0'>
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
								<div class="col-sm-12">
									<label>Nilai Jasa</label>
									<input type="text" name="nilaijasa" class="form-control" required="required" value="<?php echo $_POST["nilaijasa"] ?>">
								</div>
								<div class="col-sm-12 mt-3">
									<label>Nilai Uang Muka</label>
									<input type="text" name="nilaiuangmuka" class="form-control" required="required" value="<?php echo $_POST["nilaiuangmuka"] ?>">
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
										<label>Volume (M3)</label>
										<input type="text" name="bruto" class="form-control" required="required" value="<?php echo $_POST["volume"] ?>" placeholder='0'>
									</div>
									<div class="col-sm-12 mt-3">
										<label>Berat Kotor (KGM)</label>
										<input type="text" name="bruto" class="form-control" required="required" value="<?php echo $_POST["bruto"] ?>" placeholder='0'>
									</div>
									<div class="col-sm-12 mt-3">
										<label>Berat Bersih (KGM)</label>
										<input type="text" name="netto" class="form-control" required="required" value="<?php echo $_POST["netto"] ?>" placeholder='0'>
									</div>

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="accordion">
			<div class="accordion-header" role="button" data-toggle="collapse" data-target="#panel-body-8">
				<h4>Barang</h4>
			</div>
			<div class="accordion-body collapse" id="panel-body-8" data-parent="#accordion">
				<?php
				$no = 1;
				foreach ($data_po as $row) { ?>
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
										<?php echo $row['NamaBarang'] ?> (<?php echo $row['Qty'] ?> <?php echo $row['Satuan'] ?>)
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
													<h4>Bahan Baku</h4>
													<a href="javascript:void(0);" onclick="popup('nometer', 'master/ceisa/listpo/add_bb_27.php?seqitem=<?php echo $row['SeqItem']; ?>', '1100', '600')">
														<button class="btn btn-primary" type="button" style="border-radius: 0;"><i class="fa fa-plus"></i> Bahan Baku</button> </a>
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
																					WHERE a.SeqItem = '" . $row['SeqItem'] . "'";
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

																				<a href="javascript:void(0);" onclick="popup('nometer', 'master/ceisa/listpo/add_bb_27.php?seqbb=<?php echo $row_imp['SeqBB'] ?>', '1100', '500')">
																					<button class="btn btn-success" type="button" style="border-radius: 0;"><i class="fa fa-edit"></i> Dokumen </button>
																				</a>
																			</td>
																			<td>
																				<?php if ($row_imp['KodeAsalBahanBaku'] == "0") { ?>
																					<a href="javascript:void(0);" onclick="popup('nometer', 'master/ceisa/listso/add_bb_import_tarif.php?seqbb=<?php echo $row_imp['SeqBB'] ?>', '1100', '700')">
																						<button class="btn btn-primary" type="button" style="border-radius: 0;"><i class="fa fa-plus"></i> Tarif </button>
																					</a>
																				<?php } else { ?>
																					<a href="javascript:void(0);" onclick="popup('nometer', 'master/ceisa/listso/add_bb_lokal_tarif.php?seqbb=<?php echo $row_imp['SeqBB'] ?>', '1100', '700')">
																						<button class="btn btn-primary" type="button" style="border-radius: 0;"><i class="fa fa-plus"></i> Tarif </button>
																					</a>
																				<?php } ?>
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
														<div class="col-sm-6">
															<label>Satuan</label>
															<input type="text" name="jumlahsatuan<?php echo $no ?>" value="<?php echo $row['Qty'] ?>" class="form-control" required="required" placeholder="0.00">
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
													</div>
												</div>
											</div>
										</div>
										<div class="col-12 col-md-4 col-lg-4">
											<div class="card shadow p-1 mb-2 bg-white rounded">
												<div class="card-body">
													
													<div class="form-group row">
														<div class="col-sm-12">
															<label>Nilai CIF</label>
															<input type="text" name="cif<?php echo $no ?>" class="form-control" placeholder="0.00">
														</div>
													</div>
													<div class="form-group row">
														<div class="col-sm-12">
															<label>CIF Rupiah</label>
															<input type="text" name="cifrupiah<?php echo $no ?>" class="form-control" placeholder="0.00">
														</div>
													</div>
													<div class="form-group row">
														<div class="col-sm-12">
															<label>Harga Penyerahan/Harga Jual</label>
															<input type="text" name="hargapenyerahan<?php echo $no ?>" value="<?php echo $row['Harga']?>" class="form-control" placeholder="0.00">
														</div>
													</div>
													<div class="form-group row">
														<div class="col-sm-12">
															<label>Harga Perolehan</label>
															<input type="text" name="hargaperolehan<?php echo $no ?>" class="form-control" placeholder="0.00">
														</div>
													</div>
													<div class="form-group row">
														<div class="col-sm-12">
															<label>Nilai Jasa</label>
															<input type="text" name="nilaijasa<?php echo $no ?>" class="form-control" placeholder="0.00">
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
				<input type="hidden" name="jmlrow" id="jmlrow" value="<?php echo $no - 1 ?>">
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