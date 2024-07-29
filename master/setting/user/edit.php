<?php
if (isset($_POST["update"])) {

	$sql = "UPDATE ms_user SET Nama = '" . $_POST['nama'] . "'
							WHERE UserID = '" . $_POST['userid'] . "'	 ";
	$run = $sqlLib->update($sql);

	if ($run == "1") {
		$alert = '0';
		$note = "Proses update berhasil";
	} else {
		$alert = '1';
		$note = "Maaf, Proses update gagal";
	}
}

if ($_GET["userid"] != "") {
	$sql_user = "SELECT a.UserID, a.Password, a.Nama, a.Aktif, a.Admin, a.IDPerusahaan , b.NamaPerusahaan
					FROM ms_user a 
					LEFT JOIN ms_perusahaan b on b.IDPerusahaan = a.IDPerusahaan
					 WHERE a.UserID = '" . $_GET['userid'] . "' ";
	$data_user = $sqlLib->select($sql_user);
	$_POST['userid'] = $data_user[0]['UserID'];
	$_POST['nama']   = $data_user[0]['Nama'];
	$_POST['namaperusahaan']  = $data_user[0]['NamaPerusahaan'];
	$_POST['aktif']  = $data_user[0]['Aktif'];
}

?>

<div class="section-header">
	<h1><?php echo $_GET["p"] ?></h1>
</div>


<div class="row">
	<div class="col-12">
		<div class="card">
			<form method="post" id="form" autocomplete="off" enctype="multipart/form-data">
				<div class="card-header mb-0">
					<h4>Edit Data User</h4>
				</div>


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
						<label>UserID</label>
						<input type="text" name="userid" class="form-control" required="required" readonly="readonly" value="<?php echo $_POST["userid"] ?>">

					</div>
					<div class="form-group">
						<label>Nama</label>
						<input type="text" name="nama" class="form-control" required="required" value="<?php echo $_POST["nama"] ?>">
					</div>
					<div class="form-group">
						<label>Perusahaan</label>
						<input type="text" name="namaperusahaan" class="form-control" value="<?php echo $_POST["namaperusahaan"] ?>">
					</div>

					
					<div class="form-group">
						<label>Aktif</label>
						<select name="aktif" class="form-control" required="required">
							<option value="">Pilih Status</option>
							<option value="1" <?php if ($_POST['aktif'] == "1") {
													echo "selected";
												} ?>>Aktif</option>
							<option value="0" <?php if ($_POST['aktif'] == "0") {
													echo "selected";
												} ?>>Non Aktif</option>

						</select>
					</div>



				</div>
				<div class="card-footer text-right">
					<button type="reset" name="batal" class="btn btn-danger">Cancel</button>
					<input type="submit" class="btn btn-primary" name="update" Value="Update">
				</div>


			</form>
		</div>
	</div>
</div>