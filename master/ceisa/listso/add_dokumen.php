<?php session_start();

include_once "../../../sqlLib.php";
$sqlLib = new sqlLib();

if (isset($_POST["simpan"])) {

	$sql_dokumen = "INSERT INTO BC_DOKUMEN_TMP (
					NomorAju,Seri,KodeDokumen,NomorDokumen,TanggalDokumen,KodeFasilitas,KodeIjin,RecUser) VALUES (
					'', '', '" . $_POST["kodedokumen"] . "', '" . $_POST["nomordokumen"] . "', '" . date("Y-m-d", strtotime($_POST["tanggaldokumen"])) . "', '', '', '" . $_SESSION["nama"] . "')";
	$save_dokumen = $sqlLib->insert($sql_dokumen);
	if ($save_dokumen == "1") {

?>
		<script>
			//window.opener.document.getElementById("kategoriid").value = '<?php echo $kategoriid ?>';
			window.opener.document.getElementById("form-transaksi").submit();
			window.close();
		</script>
	<?php
	} else {
		$alert = '1';
		$note = "Proses simpan gagal!!";
	}
}

if (isset($_POST["delete"])) {
	$sql = "DELETE FROM BC_DOKUMEN_TMP WHERE SeqDokTmp = '" . $_POST['seq'] . "'";
	$run = $sqlLib->delete($sql);
	if ($run == "1") {
	?>
		<script>
			//window.opener.document.getElementById("kategoriid").value = '<?php echo $kategoriid ?>';
			window.opener.document.getElementById("form-transaksi").submit();
			window.close();
		</script>
	<?php
	} else {
		$alert = '1';
		$note = "Proses delete gagal!!";
	}
}

if (isset($_POST["update"])) {
	$sql = "UPDATE BC_DOKUMEN_TMP 
			SET KodeDokumen = '" . $_POST["kodedokumen"] . "',
				NomorDokumen= '" . $_POST["nomordokumen"] . "',
				TanggalDokumen ='" . date("Y-m-d", strtotime($_POST["tanggaldokumen"])) . "'
				WHERE SeqDokTmp = '" . $_POST['seq'] . "'";
	$run = $sqlLib->update($sql);
	if ($run == "1") {
	?>
		<script>
			//window.opener.document.getElementById("kategoriid").value = '<?php echo $kategoriid ?>';
			//window.opener.document.getElementById("panel-body-3").value = 'panel-body-3';
			window.opener.document.getElementById("form-transaksi").submit();
			window.close();
		</script>
<?php
	} else {
		$alert = '1';
		$note = "Proses update gagal!!";
	}
}


if ($_GET['seq'] != '') {
	$sql = "SELECT a.*, b.NamaDokumen FROM BC_DOKUMEN_TMP a
			LEFT JOIN ms_kode_dokumen b on b.KodeDokumen = a.KodeDokumen
			WHERE a.SeqDokTmp ='" . $_GET['seq'] . "' ";
	$data = $sqlLib->select($sql);
	$_POST["namadokumen"] = $data[0]['NamaDokumen'];
	$_POST["kodedokumen"] = $data[0]['KodeDokumen'];
	$_POST["nomordokumen"] = $data[0]['NomorDokumen'];
	$_POST["tanggaldokumen"] = $data[0]['TanggalDokumen'];
	$_POST["seq"] = $data[0]['SeqDokTmp'];
}

?>
<!DOCTYPE html>
<html>

<head>
	<title>Dokumen</title>
	<!-- General CSS Files -->
	<link rel="stylesheet" href="../../../assets/modules/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="../../../assets/modules/fontawesome/css/all.min.css">

	<!-- CSS Libraries -->
	<link rel="stylesheet" href="../../../assets/modules/bootstrap-daterangepicker/daterangepicker.css">
	<link rel="stylesheet" href="../../../assets/modules/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">
	<link rel="stylesheet" href="../../../assets/modules/select2/dist/css/select2.min.css">
	<link rel="stylesheet" href="../../../assets/modules/jquery-selectric/selectric.css">
	<link rel="stylesheet" href="../../../assets/modules/bootstrap-timepicker/css/bootstrap-timepicker.min.css">
	<link rel="stylesheet" href="../../../assets/modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.css">

	<!-- Template CSS -->
	<link rel="stylesheet" href="../../../assets/css/style.css">
	<link rel="stylesheet" href="../../../assets/css/components.css">
</head>


<div class="row">
	<div class="col-12">
		<div class="card">
			<form method="post" id="form" autocomplete="off" enctype="multipart/form-data">
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
						<div class="col-sm-4">
							<label>Nama Dokumen</label>
							<input type="text" name="namadokumen" id="namadokumen" class="form-control" required="required" value="<?php echo $_POST["namadokumen"] ?>">
							<input type="hidden" name="kodedokumen" id="kodedokumen" class="form-control" required="required" value="<?php echo $_POST["kodedokumen"] ?>">
						</div>
						<div class="col-sm-4">
							<label>Nomor Dokumen</label>
							<input type="text" name="nomordokumen" class="form-control" required="required" value="<?php echo $_POST["nomordokumen"] ?>">
						</div>
						<div class="col-sm-4">
							<label>Tanggal Dokumen</label>
							<div class="input-group">
								<div class="input-group-prepend">
									<div class="input-group-text">
										<i class="fas fa-calendar"></i>
									</div>
								</div>
								<input type="text" name="tanggaldokumen" class="form-control datepicker" required="required" value="<?php echo $_POST["tanggaldokumen"] ?>">
							</div>

						</div>
					</div>

				</div>
				<div class="card-footer text-right">
					<?php
					if ($_GET['seq'] != '') {
					?>
						<input type="hidden" name="seq" Value="<?php echo $_POST["seq"]; ?>">
						<input type="submit" class="btn btn-success" name="update" Value="Update">
						<input type="submit" class="btn btn-danger" name="delete" Value="Delete">
					<?php

					} else {
					?>
						<input type="submit" class="btn btn-primary" name="simpan" Value="Simpan">
					<?php
					}
					?>
				</div>


			</form>
		</div>
	</div>
</div>


<!--AUTO COMPLETE-->
<link rel="stylesheet" href="../../../assets/css/jquery-ui.css" />
<script src="../../../assets/js/jquery-1.12.4.js"></script>
<script src="../../../assets/js/jquery-ui.js"></script>

<!-- General JS Scripts 
  <script src="../../../assets/modules/jquery.min.js"></script>-->
<script src="../../../assets/modules/popper.js"></script>
<script src="../../../assets/modules/tooltip.js"></script>
<script src="../../../assets/modules/bootstrap/js/bootstrap.min.js"></script>
<script src="../../../assets/modules/nicescroll/jquery.nicescroll.min.js"></script>
<script src="../../../assets/modules/moment.min.js"></script>
<script src="../../../assets/js/stisla.js"></script>

<!-- JS Libraies
  <script src="../../../assets/modules/cleave-js/dist/cleave.min.js"></script>
  <script src="../../../assets/modules/cleave-js/dist/addons/cleave-phone.us.js"></script>
  <script src="../../../assets/modules/jquery-pwstrength/jquery.pwstrength.min.js"></script> -->
<script src="../../../assets/modules/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="../../../assets/modules/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
<script src="../../../assets/modules/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
<script src="../../../assets/modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>
<script src="../../../assets/modules/select2/dist/js/select2.full.min.js"></script>
<script src="../../../assets/modules/jquery-selectric/jquery.selectric.min.js"></script>

<!-- Page Specific JS File 
  <script src="../../../assets/js/page/forms-advanced-forms.js"></script>-->

<!-- Template JS File -->
<script src="../../../assets/js/scripts.js"></script>
<script src="../../../assets/js/custom.js"></script>

<script>
	$(document).ready(function() {
		var ac_config = {
			source: "../../../json/dokumen.php",
			select: function(event, ui) {
				$("#kodedokumen").val(ui.item.id);
				$("#namadokumen").val(ui.item.namadokumen);
			},
			focus: function(event, ui) {
				$("#kodedokumen").val(ui.item.id);
				$("#namadokumen").val(ui.item.namadokumen);
			},
			minLength: 1
		};
		$("#namadokumen").autocomplete(ac_config);
	});
</script>

<body>
</body>

</html>