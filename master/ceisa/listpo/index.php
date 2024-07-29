<div class="section-header">
	<h1><?php echo acakacak("decode", $_GET["p"]) ?></h1>
</div>
<style>
	#parent {
		height: 600px;
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
	} ?>
</script>



<link rel="stylesheet" href="assets/css/jquery-ui.css" />
<!-- <script src="assets/js/jquery-1.12.4.js"></script>  -->
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
					<div class="col-sm-3">
						<input type="text" placeholder="No PO" name="nopo" id="nopo" value="<?php echo $_POST['nopo']; ?>" class="form-control">
					</div>



					<div class="col-sm-3 ml-3">
						<input type="submit" name="search" class="btn btn-primary" value="Search">

					</div>
				</div>
			</form>

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
				<div class="table-responsive">
					<div id="parent">
						<table id="fixTable" class="table">
							<thead>
								<tr>
									<th style="background-color:#133b5c; color:#FFF; padding: 2px;">No.</th>
									<th style="background-color:#133b5c; color:#FFF; padding: 2px;">No PO</th>
									<th style="background-color:#133b5c; color:#FFF; padding: 2px;">Tanggal</th>
									<th style="background-color:#133b5c; color:#FFF; padding: 2px;">Vendor</th>
									<th style="background-color:#133b5c; color:#FFF; padding: 2px;">Total Amount</th>
									<th style="background-color:#133b5c; color:#FFF; padding: 2px;"></th>
								</tr>

							</thead>
							<tbody>
								<?php

								$no = 1;
								$sql = "SELECT a.NoPO, a.TanggalPo, a.Vendor, a.Alamat, a.Subtotal, a.Tax2Amount, a.TotalAmount
                                        FROM ac_po a
                                        WHERE TanggalPo>='" . $_POST['dari'] . "' AND TanggalPo<='" . $_POST['sampai'] . "' ";
								if ($_POST['nopo'] != '') $sql .= " AND a.NoPO ='" . $_POST['nopo'] . "' ";
								$data = $sqlLib->select($sql);

								foreach ($data as $row) {
									//qty PO
									$sql_po = "SELECT Sum(Qty) as QtyPO FROM ac_po_detail WHERE NoPO = '" . $row['NoPO'] . "' ";
									$data_po = $sqlLib->select($sql_po);

									//qty PO
									$sql_aju = "SELECT Sum(Qty) as QtyAju FROM ms_dokumen_aju_detail WHERE NoPO = '" . $row['NoPO'] . "' ";
									$data_aju = $sqlLib->select($sql_aju);

									$sisa = $data_po[0]['QtyPO'] - $data_aju[0]['QtyAju'];

								?>
									<tr style="color:#000;">
										<td style="text-align: center;"><?php echo $no ?></td>
										<td style="text-align: center;"><?php echo trim($row['NoPO']) ?></td>
										<td style="text-align: center;"><?php echo date("d-M-Y", strtotime($row['TanggalPo'])); ?></td>
										<td style="text-align: left;"><?php echo trim($row['Vendor']) ?></td>
										<td style="text-align: right;"><?php echo number_format($row['TotalAmount']) ?></td>
										<td style="text-align: center;">
											<div class="dropdown d-inline mr-2">
						                      <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						                        <i class="fa fa-plus"></i> Dokumen
						                      </button>
						                      <div class="dropdown-menu">
						                        <a class="dropdown-item" href="index.php?m=<?php echo acakacak("encode", "ceisa/listpo") ?>&sm=<?php echo acakacak("encode", "add") ?>&p=<?php echo acakacak("encode", "Input Dokumen Aju") ?>&nopo=<?php echo trim($row['NoPO']) ?>">Input Dokumen</a>
						                        <a class="dropdown-item" href="index.php?m=<?php echo acakacak("encode", "ceisa/listpo") ?>&sm=<?php echo acakacak("encode", "upload") ?>&p=<?php echo acakacak("encode", "Upload Dokumen") ?>&nopo=<?php echo trim($row['NoPO']) ?>">Upload Dokumen</a>
						                      </div>
						                    </div>
						                    <!--
											<?php if ($sisa > 0) { ?>
												<a href="index.php?m=<?php echo acakacak("encode", "ceisa/listpo") ?>&sm=<?php echo acakacak("encode", "add") ?>&p=<?php echo acakacak("encode", "Input Dokumen Aju") ?>&nopo=<?php echo trim($row['NoPO']) ?>">
													<button type="button" name="tambah" class="btn btn-warning">Buat Dokumen</button>
												</a>

												<a href="index.php?m=<?php echo acakacak("encode", "ceisa/listpo") ?>&sm=<?php echo acakacak("encode", "upload") ?>&p=<?php echo acakacak("encode", "Upload Dokumen") ?>&nopo=<?php echo trim($row['NoPO']) ?>">
													<button type="button" name="upload" class="btn btn-info">Upload Dokumen</button>
												</a>
											<?php } else {
												echo "Dokumen Selesai";
											} ?> -->
										</td>
									</tr>
								<?php $no++;
								}


								?>


							</tbody>

						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<script>
	$(document).ready(function() {
		var ac_config = {
			source: "json/nopo.php",
			select: function(event, ui) {
				$("#nopo").val(ui.item.id);
				//$("#namabarang").val(ui.item.namabarang);
			},
			focus: function(event, ui) {
				$("#nopo").val(ui.item.id);
				//$("#namabarang").val(ui.item.namabarang);
			},
			minLength: 1
		};
		$("#nopo").autocomplete(ac_config);
	});
</script>