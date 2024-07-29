<div class="section-header">
	<h1><?php echo acakacak("decode", $_GET["p"]) ?></h1>
</div>

<style>
	#parent {
		height: 500px;
	}

	th {
		background-color: #133b5c;
		color: rgb(241, 245, 179);

		text-align: center;
		font-weight: normal;
		font-size: 14px;
		outline: 0.7px solid black;
		border: 1.5px solid black;

	}

	td {
		border-bottom: 1.5px solid black;
		font-size: 12px;

	}
</style>

<script>
	$(document).ready(function() {
		$("#fixTable").tableHeadFixer();
		$("#fixTable").tableHeadFixer({
			'foot': true,
			'head': false
		});
	});;
	<?php if ($_POST['dari'] == "") {
		$_POST['dari'] = date("Y-m-01");
		$_POST['sampai'] = date("Y-m-d");
	}		?>
</script>

<link rel="stylesheet" href="assets/css/jquery-ui.css" />
<!-- <script src="assets/js/jquery-1.12.4.js"></script> -->
<script src="assets/js/jquery-ui.js"></script>

<div class="row">
	<div class="col-12">
		<div class="card">
			<form method="post" id="form" autocomplete="off" enctype="multipart/form-data">
				<div class="form-group row mt-3 mb-0">
					<div class="col-sm-2  ml-3">
						<div class="input-group">
							<div class="input-group-prepend">
								<div class="input-group-text">
									<i class="fas fa-calendar"></i>
								</div>
							</div>
							<input type="text" name="dari" value="<?php echo $_POST['dari']; ?>" class="form-control datepicker" readonly="readonly">
						</div>
					</div>
					<div class="col-sm-2 ">
						<div class="input-group">
							<div class="input-group-prepend">
								<div class="input-group-text">
									<i class="fas fa-calendar"></i>
								</div>
							</div>
							<input type="text" name="sampai" value="<?php echo $_POST['sampai']; ?>" class="form-control datepicker" readonly="readonly">
						</div>
					</div>

					<div class="col-sm-2">
						<input type="text" name="namabarang" id="namabarang" value="<?php echo $_POST['namabarang']; ?>" class="form-control">
						<input type="hidden" name="kodebarang" id="kodebarang" value="<?php echo $_POST['kodebarang']; ?>" class="form-control">
					</div>

					<div class="col-sm-3 ml-3">
						<input type="submit" name="search" class="btn btn-primary" value="Search">

						<a href="print/LapBahanBaku.php?dari=<?php echo $_POST['dari'] ?>&sampai=<?php echo $_POST['sampai'] ?>&kodebarang=<?php echo $_POST['kodebarang'] ?>&namabarang=<?php echo $_POST['namabarang'] ?>" target="_blank">
							<input type="button" class="btn btn-warning" name="cetak" Value="Cetak">
						</a>

						<a href="download/excel/LapBahanBaku.php?dari=<?php echo $_POST['dari']; ?>&sampai=<?php echo $_POST['sampai']; ?>&kodebarang=<?php echo $_POST['kodebarang'] ?>&namabarang=<?php echo $_POST['namabarang'] ?>" target="_blank">
							<input type="button" name="export" class="btn btn-success" value="Export"></a>


					</div>
				</div>
			</form>

			<div class="card-body">

				<div id="parent">
					<table id="fixTable" class="table">
						<thead>
							<tr>
								<th style="background-color:#133b5c; color:#FFF;">No</th>
								<th style="background-color:#133b5c; color:#FFF;">Kode Barang</th>
								<th style="background-color:#133b5c; color:#FFF;">Nama Barang</th>
								<th style="background-color:#133b5c; color:#FFF;">Satuan</th>
								<th style="background-color:#133b5c; color:#FFF;">Saldo Awal</th>
								<th style="background-color:#133b5c; color:#FFF;">Pemasukan</th>
								<th style="background-color:#133b5c; color:#FFF;">Pengeluaran</th>
								<th style="background-color:#133b5c; color:#FFF;">Penyesuaian</th>
								<th style="background-color:#133b5c; color:#FFF;">Saldo Akhir</th>
								<th style="background-color:#133b5c; color:#FFF;">Stock Opname</th>
								<th style="background-color:#133b5c; color:#FFF;">Selisih</th>
								<th style="background-color:#133b5c; color:#FFF;">Keterangan</th>
							</tr>

						</thead>
						<tbody>
							<?php
							//a.itemCategory ='Bahan Baku' AND
							$no = 1;
							$sql = "SELECT itemNo, itemName, unit1Name, itemCategory,
										sum(awal) as awal, sum(masuk) as masuk, sum(keluar) as keluar,
										sum(adjustment) as adjustment, 
										sum(awal + masuk - keluar + adjustment) as akhir, 		
										sum(so) as so, 				
										sum(so) - sum(awal + masuk - keluar + adjustment)  as selisih 
									FROM 
									(
									SELECT a.itemNo, a.itemName, a.unit1Name, a.itemCategory, 
										sum(a.awal) as awal, sum(0) as masuk, sum(0) as keluar, 
											sum(0) as adjustment, 
											sum(0) as akhir, 		
											sum(0) as so, 				
											sum(0) as selisih 
										FROM ac_stock a
									WHERE a.itemCategory ='Bahan Baku' AND  a.tanggal ='" . $_POST['dari'] . "' AND a.lokasiGudang='Utama'
									Group By a.itemNo, a.itemName, a.unit1Name, a.itemCategory , a.lokasiGudang 
									UNION ALL

									SELECT a.itemNo, a.itemName, a.unit1Name, a.itemCategory,
										sum(0) as awal, sum(a.masuk) as masuk, sum(a.keluar) as keluar,	
										sum(a.adjustment) as adjustment, 
										sum(a.awal + a.masuk - a.keluar + a.adjustment) as akhir, 		
										sum(a.so) as so, 				
										sum(a.so) - sum(a.awal + a.masuk - a.keluar + a.adjustment) as selisih 
										FROM ac_stock a
									WHERE a.itemCategory ='Bahan Baku' AND a.lokasiGudang='Utama' AND a.tanggal>='" . $_POST['dari'] . "' AND a.tanggal<='" . $_POST['sampai'] . "' AND a.lokasiGudang='Utama'
									Group By a.itemNo, a.itemName, a.unit1Name, a.itemCategory , a.lokasiGudang 
									) as tbl WHERE tbl.itemNo!='' ";
							if ($_POST['namabarang'] != '') $sql .= " AND tbl.itemNo='" . $_POST['kodebarang'] . "'";
							$sql .= " Group By itemNo, itemName, unit1Name, itemCategory ";
							$data = $sqlLib->select($sql);
							foreach ($data as $row) {
								$t_awal += $row['awal'];
								$t_masuk += $row['masuk'];
								$t_keluar += $row['keluar'];
								$t_adjustment += $row['adjustment'];
								$t_akhir += $row['akhir'];
								$t_so += $row['so'];
								$t_selisih += $row['selisih'];
							?>
								<tr style="color:#000;">
									<td style="text-align: center;"><?php echo $no ?></td>
									<td style="text-align: center;"><?php echo $row['itemNo'] ?></td>
									<td><?php echo $row['itemName'] ?></td>
									<td style="text-align: center;"><?php echo $row['unit1Name'] ?></td>
									<td style="text-align: right;"><?php echo number_format($row['awal']) ?></td>
									<td style="text-align: right;"><?php echo number_format($row['masuk']) ?></td>
									<td style="text-align: right;"><?php echo number_format($row['keluar']) ?></td>
									<td style="text-align: right;"><?php echo number_format($row['adjustment']) ?></td>
									<td style="text-align: right;"><?php echo number_format($row['akhir']) ?></td>
									<td style="text-align: right;"><?php echo number_format($row['so']) ?></td>
									<td style="text-align: right;"><?php echo number_format($row['selisih']) ?></td>
									<td></td>
								</tr>
							<?php $no++;
							}


							?>


						</tbody>
						<tfoot>
							<tr style="background-color:#133b5c; color:#FFF; font-weight: bold;">
								<td style="text-align: center;" colspan="4">Grand Total</td>
								<td style="text-align: right;"><?php echo number_format($t_awal) ?></td>
								<td style="text-align: right;"><?php echo number_format($t_masuk) ?></td>
								<td style="text-align: right;"><?php echo number_format($t_keluar) ?></td>
								<td style="text-align: right;"><?php echo number_format($t_adjustment) ?></td>
								<td style="text-align: right;"><?php echo number_format($t_akhir) ?></td>
								<td style="text-align: right;"><?php echo number_format($t_so) ?></td>
								<td style="text-align: right;"><?php echo number_format($t_selisih) ?></td>
								<td>&nbsp;</td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>


<script>
	$(document).ready(function() {
		var ac_config = {
			source: "json/barang_stock.php",
			select: function(event, ui) {
				$("#kodebarang").val(ui.item.id);
				$("#namabarang").val(ui.item.namabarang);
			},
			focus: function(event, ui) {
				$("#kodebarang").val(ui.item.id);
				$("#namabarang").val(ui.item.namabarang);
			},
			minLength: 1
		};
		$("#namabarang").autocomplete(ac_config);
	});
</script>