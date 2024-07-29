<?php
if ($_POST['idperusahaan'] == "") {

    $sql_cp = "SELECT IDPerusahaan, NamaPerusahaan, Alamat, KodeJenisIdentitas,JenisIdentitas,NomorIdentitas,
                      NIB, NoTelp, Nama, Jabatan, KodeJenisTpb, KantorPabean, Kota, NomorIjinEntitas, TanggalIjinEntitas, KodeKantor, KodeStatus 
				FROM ms_perusahaan
				WHERE IDPerusahaan ='1'	";
    $data_cp = $sqlLib->select($sql_cp);
    $_POST['idperusahaan'] = $data_cp[0]['IDPerusahaan'];
    $_POST['nama_perusahaan'] = $data_cp[0]['NamaPerusahaan'];
    $_POST['alamat'] = $data_cp[0]['Alamat'];
    $_POST['kodejenisidentitas'] = $data_cp[0]['KodeJenisIdentitas'];
    $_POST['jenisidentitas'] = $data_cp[0]['JenisIdentitas'];
    $_POST['nomoridentitas'] = $data_cp[0]['NomorIdentitas'];
    $_POST['nib'] = $data_cp[0]['NIB'];
    $_POST['no_telp'] = $data_cp[0]['NoTelp'];
    $_POST['nama'] = $data_cp[0]['Nama'];
    $_POST['jabatan'] = $data_cp[0]['Jabatan'];
    $_POST['kodejenistpb'] = $data_cp[0]['KodeJenisTpb'];
    $_POST['kantorpabean'] = $data_cp[0]['KantorPabean'];
    $_POST['kota'] = $data_cp[0]['Kota'];
    $_POST['nomorijinentitas'] = $data_cp[0]['NomorIjinEntitas'];
    $_POST['tanggalijinentitas'] = $data_cp[0]['TanggalIjinEntitas'];
    $_POST['kodekantor'] = $data_cp[0]['KodeKantor'];
    $_POST['kodestatuspengusaha'] = $data_cp[0]['KodeStatus'];
}
if (isset($_POST['update'])) {
    if ($_POST['kodejenisidentitas'] == "0") {
        $_POST['jenisidentitas'] = "NPWP 12 Digit";
    } else if ($_POST['kodejenisidentitas'] == "1") {
        $_POST['jenisidentitas'] = "NPWP 10 Digit";
    } else if ($_POST['kodejenisidentitas'] == "2") {
        $_POST['jenisidentitas'] = "Paspor";
    } else if ($_POST['kodejenisidentitas'] == "3") {
        $_POST['jenisidentitas'] = "KTP";
    } else if ($_POST['kodejenisidentitas'] == "4") {
        $_POST['jenisidentitas'] = "Lainnya";
    } else if ($_POST['kodejenisidentitas'] == "5") {
        $_POST['jenisidentitas'] = "NPWP 15 Digit";
    }

    $sql1 = "SELECT a.KodeKantor, a.NamaKantor
                FROM ms_kantor a
                WHERE a.KodeKantor ='".$_POST['kodekantor']."' ";
    $data1 = $sqlLib->select($sql1);
    $_POST['kantorpabean'] = $data1[0]['NamaKantor'];

    $sql_up = "UPDATE ms_perusahaan 
				SET NamaPerusahaan ='" . $_POST['nama_perusahaan'] . "',
					Alamat ='" . $_POST['alamat'] . "',
					KodeJenisIdentitas ='" . $_POST['kodejenisidentitas'] . "',
					JenisIdentitas ='" . $_POST['jenisidentitas'] . "',
					NomorIdentitas ='" . $_POST['nomoridentitas'] . "',
					NIB ='" . $_POST['nib'] . "',	
					NoTelp ='" . $_POST['no_telp'] . "',   
                    Nama ='" . $_POST['nama'] . "',    
                    Jabatan ='" . $_POST['jabatan'] . "',    
                    KodeJenisTpb ='" . $_POST['kodejenistpb'] . "', 
                    KodeStatus ='" . $_POST['kodestatuspengusaha'] . "',    
                    KodeKantor ='" . $_POST['kodekantor'] . "',    
                    KantorPabean ='" . $_POST['kantorpabean'] . "',    
                    Kota ='" . $_POST['kota'] . "',    
                    NomorIjinEntitas ='" . $_POST['nomorijinentitas'] . "',    
                    TanggalIjinEntitas ='" . $_POST['tanggalijinentitas'] . "'
				WHERE IDPerusahaan = '" . $_POST['idperusahaan'] . "'	";
    $run_up = $sqlLib->update($sql_up);
    if ($run_up == "1") {
        $alert = '0';
        $note = "Proses update berhasil!!";
    } else {
        $alert = '1';
        $note = "Proses update gagal!!";
    }
}

if (isset($_POST['simpan'])) {
    if ($_POST['kodejenisidentitas'] == "0") {
        $_POST['jenisidentitas'] == "NPWP 12 Digit";
    } else if ($_POST['kodejenisidentitas'] == "1") {
        $_POST['jenisidentitas'] == "NPWP 10 Digit";
    } else if ($_POST['kodejenisidentitas'] == "2") {
        $_POST['jenisidentitas'] == "Paspor";
    } else if ($_POST['kodejenisidentitas'] == "3") {
        $_POST['jenisidentitas'] == "KTP";
    } else if ($_POST['kodejenisidentitas'] == "4") {
        $_POST['jenisidentitas'] == "Lainnya";
    } else if ($_POST['kodejenisidentitas'] == "5") {
        $_POST['jenisidentitas'] == "NPWP 15 Digit";
    }
    $sql_in = "INSERT INTO NamaPerusahaan (NamaPerusahaan, Alamat, KodeJenisIdentitas,JenisIdentitas,NomorIdentitas,
                 NIB, NoTelp, Nama, Jabatan, KodeJenisTpb, KantorPabean, Kota, 
                        NomorIjinEntitas, TanggalIjinEntitas)
				VALUES ('" . $_POST['nama_perusahaan'] . "','" . $_POST['alamat'] . "','" . $_POST['kodejenisidentitas'] . "',
                        '" . $_POST['jenisidentitas'] . "','" . $_POST['nomoridentitas'] . "','" . $_POST['nib'] . "','" . $_POST['no_telp'] . "',
                        '" . $_POST['nama'] . "','" . $_POST['jabatan'] . "','" . $_POST['kodejenistpb'] . "','" . $_POST['kantorpabean'] . "','" . $_POST['kota'] . "',
                        '" . $_POST['nomorijinentitas'] . "','" . $_POST['tanggalijinentitas'] . "') ";
    $run_in = $sqlLib->insert($sql_in);
    if ($run_in == "1") {
        $alert = '0';
        $note = "Proses simpan berhasil!!";
    } else {
        $alert = '1';
        $note = "Proses simpan gagal!!";
    }
}
?>

<div class="section-header">
    <h1><?php echo acakacak("decode", $_GET["p"]) ?> </h1>
</div>

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
                            <label>Nama Perusahaan</label>
                            <input type="text" name="nama_perusahaan" class="form-control" required="required" value="<?php echo $_POST["nama_perusahaan"] ?>">
                            <input type="hidden" name="idperusahaan" class="form-control" required="required" value="<?php echo $_POST["idperusahaan"] ?>">
                        </div>
                        <div class="col-sm-4">
                            <label>NIB</label>
                            <input type="text" name="nib" class="form-control" required="required" value="<?php echo $_POST["nib"] ?>">
                        </div>
                        <div class="col-sm-4">
                            <label>Jenis Identitas</label>
                            <select class="form-control" name="kodejenisidentitas" required="required">
                                <option value="">-Pilih-</option>
                                <option value="0" <?php if ($_POST['kodejenisidentitas'] == "0") {
                                                        echo "selected";
                                                    } ?>>NPWP 12 Digit</option>
                                <option value="1" <?php if ($_POST['kodejenisidentitas'] == "1") {
                                                        echo "selected";
                                                    } ?>>NPWP 10 Digit</option>
                                <option value="2" <?php if ($_POST['kodejenisidentitas'] == "2") {
                                                        echo "selected";
                                                    } ?>>Paspor</option>
                                <option value="3" <?php if ($_POST['kodejenisidentitas'] == "3") {
                                                        echo "selected";
                                                    } ?>>KTP</option>
                                <option value="4" <?php if ($_POST['kodejenisidentitas'] == "4") {
                                                        echo "selected";
                                                    } ?>>Lainnya</option>
                                <option value="5" <?php if ($_POST['kodejenisidentitas'] == "5") {
                                                        echo "selected";
                                                    } ?>>NPWP 15 Digit</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        
                        <div class="col-sm-4">
                            <label>Nomor Identitas</label>
                            <input type="text" name="nomoridentitas" class="form-control" required="required" value="<?php echo $_POST["nomoridentitas"] ?>">
                        </div>
                        <div class="col-sm-4">
                            <label>Nomor Ijin Entitas</label>
                            <input type="text" name="nomorijinentitas" class="form-control" required="required" value="<?php echo $_POST["nomorijinentitas"] ?>">
                        </div>
                        <div class="col-sm-4">
                            <label>Tanggal Ijin Entitas</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-calendar"></i>
                                    </div>
                                </div>
                                <input type="text" name="tanggalijinentitas" class="form-control datepicker" required="required" value="<?php echo $_POST["tanggalijinentitas"] ?>">
                            </div>
                        </div>
                        
                        
                    </div>


                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label>Kantor Pabean</label>
                            <select class="form-control" name="kodekantor" required="required">
                                <option value="">-Pilih-</option>
                                <?php 
                                $sql3 = "SELECT a.KodeKantor, a.NamaKantor
                                                        FROM ms_kantor a
                                                        WHERE a.KodeKantor != '' ";
                                $data3 = $sqlLib->select($sql3);
                                foreach ($data3 as $row) {
                                   ?>
                                   <option value="<?php echo $row['KodeKantor'] ?>" <?php if ($_POST['kodekantor'] == $row['KodeKantor']) {
                                                        echo "selected";
                                                    } ?>><?php echo '('.$row['KodeKantor'].') '.$row['NamaKantor'] ?></option>
                                   <?php
                                }
                                ?>
                                
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label>Jenis TPB</label>
                            <select class="form-control" name="kodejenistpb" required="required">
                                <option value=""></option>
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
                                <option value="8" <?php if ($_POST['kodejenistpb'] == "8") {
                                                        echo "selected";
                                                    } ?>>LAINNYA</option>                                        
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label>Status Pengusaha</label>
                            <select class="form-control" name="kodestatuspengusaha" required="required">
                                <option value="">-Pilih-</option>
                                <option value="1" <?php if ($_POST['kodestatuspengusaha'] == "1") {
                                                        echo "selected";
                                                    } ?>>Koperasi</option>
                                <option value="2" <?php if ($_POST['kodestatuspengusaha'] == "2") {
                                                        echo "selected";
                                                    } ?>>PMDN (Migas)</option>
                                <option value="3" <?php if ($_POST['kodestatuspengusaha'] == "3") {
                                                        echo "selected";
                                                    } ?>>PMDN (Non Migas)</option>
                                <option value="4" <?php if ($_POST['kodestatuspengusaha'] == "4") {
                                                        echo "selected";
                                                    } ?>>PMA (Migas)</option>
                                <option value="5" <?php if ($_POST['kodestatuspengusaha'] == "5") {
                                                        echo "selected";
                                                    } ?>>PMA (Non Migas)</option> 
                                <option value="8" <?php if ($_POST['kodestatuspengusaha'] == "8") {
                                                        echo "selected";
                                                    } ?>>Perorangan</option>                                        
                            </select>
                        </div>
                        
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label>Nama</label>
                            <input type="text" name="nama" class="form-control" required="required" value="<?php echo $_POST["nama"] ?>">
                        </div>
                        <div class="col-sm-4">
                            <label>Jabatan</label>
                            <input type="text" name="jabatan" class="form-control" required="required" value="<?php echo $_POST["jabatan"] ?>">
                        </div>
                        
                        
                        <div class="col-sm-4">
                            <label>Kota</label>
                            <input type="text" name="kota" class="form-control" required="required" value="<?php echo $_POST["kota"] ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label>Alamat</label>
                            <textarea class="form-control" required="" rows="10" style="height: 100px;" name="alamat"><?php echo $_POST["alamat"] ?></textarea>
                        </div>
                        <div class="col-sm-4">
                            <label>No Telp</label>
                            <input type="text" name="no_telp" class="form-control" required="required" value="<?php echo $_POST["no_telp"] ?>">
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <?php
                    if ($_POST["idperusahaan"] == "") { ?>
                        <input type="submit" class="btn btn-primary" name="simpan" Value="Simpan"><?php
                                                                                                } else { ?>
                        <input type="submit" class="btn btn-primary" name="update" Value="Update"><?php
                                                                                                } ?>

                </div>


            </form>
        </div>
    </div>
</div>