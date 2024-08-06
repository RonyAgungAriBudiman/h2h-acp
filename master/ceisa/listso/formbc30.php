<?php
$sql_dt = "SELECT a.NoSO, a.TanggalSo, a.Customer, a.Alamat, a.Subtotal, a.Tax2Amount, a.TotalAmount,
                    b.KdBarang, b.NamaBarang, b.Harga, b.Qty, b.ItemDiscPercent, b.ItemCashDiscount, b.Satuan, b.TotalHarga, b.SeqItem
            FROM ac_so a
            LEFT JOIN ac_so_detail b on b.NoSO = a.NoSO
            WHERE a.NoSO = '" . $_POST['noso'] . "' ";
$data_dt = $sqlLib->select($sql_dt);
//echo $data_dt;

$sql_pt = "SELECT a.NamaPerusahaan, a.Alamat, a.KodeJenisIdentitas, a.JenisIdentitas, a.NomorIdentitas,  a.NIB, 
            a.Nama, a.Jabatan, a.KodeJenisTpb, a.Kota, a.NomorIjinEntitas, 
            a.TanggalIjinEntitas, a.KodeKantor, a.KantorPabean , a.KodeStatus
FROM ms_perusahaan a
WHERE a.IDPerusahaan ='1' ";
$data_pt = $sqlLib->select($sql_pt);

$sql_urut = "SELECT MAX(Urut) as Urut FROM BC_HEADER 
                WHERE KodeDokumen = '30' AND  YEAR(TanggalPernyataan) = '" . date("Y") . "' ";
$data_urut = $sqlLib->select($sql_urut);
$urut = $data_urut[0]['Urut'] + 1;
$nomor = str_pad($urut, 6, '0', STR_PAD_LEFT);
$_POST["nomor"] = $nomor;
$_POST["urut"] = $urut;

//$data_pt[0]['KodeKantor'] . //, strtotime($_POST['tanggalaju'])
$_POST["nomoraju"] =  '0000' . $_POST['kodedokumenbc'] . '-' . substr($data_pt[0]['NomorIdentitas'], 0, 6) . '-' . date("Ymd") . '-' . $nomor;

$_POST["kodekantor"] = $data_pt[0]['KodeKantor'];

$_POST["kodejenisidentitaseksportir"] = $data_pt[0]['KodeJenisIdentitas'];
$_POST["jenisidentitaseksportir"] = $data_pt[0]['JenisIdentitas'];
$_POST["nomoridentitaseksportir"] = $data_pt[0]['NomorIdentitas'];
$_POST["namaeksportir"] = $data_pt[0]['NamaPerusahaan'];
$_POST["alamateksportir"] = $data_pt[0]['Alamat'];
$_POST["nibeksportir"] = $data_pt[0]['NIB'];
$_POST["nomorijinentitaseksportir"] = $data_pt[0]['NomorIjinEntitas'];
$_POST["tanggalijinentitaseksportir"] = $data_pt[0]['TanggalIjinEntitas'];
$_POST["kodestatuseksportir"] = $data_pt[0]['KodeStatus'];

$_POST["namapenerima"] = $data_dt[0]['Customer'];
$_POST["alamatpenerima"] = $data_dt[0]['Alamat'];

$_POST["namapembeli"] = $data_dt[0]['Customer'];
$_POST["alamatpembeli"] = $data_dt[0]['Alamat'];

$_POST["kodejenisidentitaspemilik"] = $data_pt[0]['KodeJenisIdentitas'];
$_POST["jenisidentitaspemilik"] = $data_pt[0]['JenisIdentitas'];
$_POST["nomoridentitaspemilik"] = $data_pt[0]['NomorIdentitas'];
$_POST["namapemilik"] = $data_pt[0]['NamaPerusahaan'];
$_POST["alamatpemilik"] = $data_pt[0]['Alamat'];
$_POST["nibpemilik"] = $data_pt[0]['NIB'];
$_POST["nomorijinentitaspemilik"] = $data_pt[0]['NomorIjinEntitas'];
$_POST["tanggalijinentitaspemilik"] = $data_pt[0]['TanggalIjinEntitas'];
$_POST["kodestatuspemilik"] = $data_pt[0]['KodeStatus'];
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
                    <div class="col-12">
                        <div class="card shadow p-1 mb-2 bg-white rounded">
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Nomor Pengajuan</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="nomoraju" class="form-control" readonly="readonly" value="<?php echo $_POST["nomoraju"] ?>">
                                        <input type="hidden" name="nomor" class="form-control" readonly="readonly" value="<?php echo $_POST["nomor"] ?>">
                                        <input type="hidden" name="urut" class="form-control" readonly="readonly" value="<?php echo $_POST["urut"] ?>">
                                        <input type="hidden" name="kodekantor" class="form-control" readonly="readonly" value="<?php echo $_POST["kodekantor"] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Kantor Pabean Muat Asal</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="namakantormuat" id="namakantormuat" class="form-control" value="<?php echo $_POST["namakantormuat"] ?>">
                                        <input type="hidden" name="kodekantormuat" id="kodekantormuat" class="form-control" value="<?php echo $_POST["kodekantormuat"] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Pelabuhan Muat Eksport</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="namapelekspor" id="namapelekspor" class="form-control" value="<?php echo $_POST["namapelekspor"] ?>">
                                        <input type="hidden" name="kodepelekspor" id="kodepelekspor" class="form-control" value="<?php echo $_POST["kodepelekspor"] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Kantor Pabean Muat Eksport</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="namakantorekspor" id="namakantorekspor" class="form-control" value="<?php echo $_POST["namakantorekspor"] ?>">
                                        <input type="hidden" name="kodekantorekspor" id="kodekantorekspor" class="form-control" value="<?php echo $_POST["kodekantorekspor"] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Jenis Ekspor</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" name="kodejenisekspor" required="required">
                                            <option value="">-Pilih-</option>
                                            <?php
                                            $sql_je = "SELECT a.KodeJenisEkspor, a.NamaJenisEkspor
                                                    FROM ms_jenis_ekspor a
                                                    WHERE a.KodeJenisEkspor !='' ";
                                            $data_je = $sqlLib->select($sql_je);
                                            foreach ($data_je as $row_je) {
                                            ?>
                                                <option value="<?php echo $row_je['KodeJenisEkspor'] ?>" <?php if ($_POST['kodejenisekspor'] == $row_je['KodeJenisEkspor']) {
                                                                                                                echo "selected";
                                                                                                            } ?>><?php echo '(' . $row_je['KodeJenisEkspor'] . ') ' . $row_je['NamaJenisEkspor'] ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Kategori Ekspor</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" name="kodekategoriekspor" required="required">
                                            <option value="">-Pilih-</option>
                                            <?php
                                            $sql_ke = "SELECT a.KodeKategoriEkspor, a.NamaKategoriEkspor
                                                    FROM ms_kategori_ekspor a
                                                    WHERE a.KodeKategoriEkspor !='' ";
                                            $data_ke = $sqlLib->select($sql_ke);
                                            foreach ($data_ke as $row_ke) {
                                            ?>
                                                <option value="<?php echo $row_ke['KodeKategoriEkspor'] ?>" <?php if ($_POST['kodekategoriekspor'] == $row_ke['KodeKategoriEkspor']) {
                                                                                                                echo "selected";
                                                                                                            } ?>><?php echo '(' . $row_ke['KodeKategoriEkspor'] . ') ' . $row_ke['NamaKategoriEkspor'] ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Cara Dagang</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" name="kodecaradagang" required="required">
                                            <option value="">-Pilih-</option>
                                            <?php
                                            $sql_cd = "SELECT a.KodeCaraDagang, a.NamaCaraDagang
                                                    FROM ms_cara_dagang a
                                                    WHERE a.KodeCaraDagang !='' ";
                                            $data_cd = $sqlLib->select($sql_cd);
                                            foreach ($data_cd as $row_cd) {
                                            ?>
                                                <option value="<?php echo $row_cd['KodeCaraDagang'] ?>" <?php if ($_POST['kodecaradagang'] == $row_cd['KodeCaraDagang']) {
                                                                                                            echo "selected";
                                                                                                        } ?>><?php echo '(' . $row_cd['KodeCaraDagang'] . ') ' . $row_cd['NamaCaraDagang'] ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Cara Bayar</label>
                                    <div class="col-sm-8">
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
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Komoditi</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" name="flagmigas" required="required">
                                            <option value="">-Pilih-</option>
                                            <option value="1" <?php if ($_POST['flagmigas'] == "1") {
                                                                    echo "selected";
                                                                } ?>>Migas</option>
                                            <option value="2" <?php if ($_POST['flagmigas'] == "2") {
                                                                    echo "selected";
                                                                } ?>>Non Migas</option>

                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Curah</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" name="flagcurah" required="required">
                                            <option value="">-Pilih-</option>
                                            <option value="1" <?php if ($_POST['flagcurah'] == "1") {
                                                                    echo "selected";
                                                                } ?>>Curah</option>
                                            <option value="2" <?php if ($_POST['flagcurah'] == "2") {
                                                                    echo "selected";
                                                                } ?>>Non Curah</option>

                                        </select>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>


                </div>
            </div>
            <div class="accordion-header" role="button" data-toggle="collapse" data-target="#panel-body-2">
                <h4>Entitas</h4>
            </div>
            <div class="accordion-body collapse" id="panel-body-2" data-parent="#accordion">
                <div class="row">
                    <div class="col-6">
                        <div class="card shadow p-1 mb-2 bg-white rounded">
                            <div class="card-body">
                                <div class="form-group row" style="border-bottom:solid 0.5px #31708f;">
                                    <label class="col-sm-12 col-form-label">Eksportir</label>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Nomor Identitas</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="jenisidentitaseksportir" class="form-control" readonly="readonly" value="<?php echo $_POST["jenisidentitaseksportir"] ?>">
                                        <input type="hidden" name="kodejenisidentitaseksportir" class="form-control" readonly="readonly" value="<?php echo $_POST["kodejenisidentitaseksportir"] ?>">
                                        <input type="hidden" name="nibeksportir" class="form-control" readonly="readonly" value="<?php echo $_POST["nibeksportir"] ?>">
                                        <input type="hidden" name="nomorijinentitaseksportir" class="form-control" readonly="readonly" value="<?php echo $_POST["nomorijinentitaseksportir"] ?>">
                                        <input type="hidden" name="tanggalijinentitaseksportir" class="form-control" readonly="readonly" value="<?php echo $_POST["tanggalijinentitaseksportir"] ?>">
                                        <input type="hidden" name="kodestatuseksportir" class="form-control" readonly="readonly" value="<?php echo $_POST["kodestatuseksportir"] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">&nbsp;</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="nomoridentitaseksportir" class="form-control" readonly="readonly" value="<?php echo $_POST["nomoridentitaseksportir"] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Nama</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="namaeksportir" class="form-control" readonly="readonly" value="<?php echo $_POST["namaeksportir"] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Alamat</label>
                                    <div class="col-sm-8">
                                        <textarea class="form-control" style="height: 100px;" id="alamateksportir" name="alamateksportir" readonly="readonly"><?php echo $_POST["alamateksportir"] ?></textarea>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card shadow p-1 mb-2 bg-white rounded">
                            <div class="card-body">
                                <div class="form-group row" style="border-bottom:solid 0.5px #31708f;">
                                    <label class="col-sm-12 col-form-label">Penerima</label>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Nama</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="namapenerima" id="namapenerima" class="form-control" value="<?php echo $_POST["namapenerima"] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Alamat</label>
                                    <div class="col-sm-8">
                                        <textarea class="form-control" style="height: 100px;" id="alamatpenerima" name="alamatpenerima"><?php echo $_POST["alamatpenerima"] ?></textarea>

                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Negara</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="namanegarapenerima" id="namanegarapenerima" class="form-control" value="<?php echo $_POST["namanegarapenerima"] ?>">
                                        <input type="hidden" name="kodenegarapenerima" id="kodenegarapenerima" class="form-control" value="<?php echo $_POST["kodenegarapenerima"] ?>">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="card shadow p-1 mb-2 bg-white rounded">
                            <div class="card-body">
                                <div class="form-group row" style="border-bottom:solid 0.5px #31708f;">
                                    <label class="col-sm-12 col-form-label">Pembeli</label>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Nama</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="namapembeli" id="namapembeli" class="form-control" value="<?php echo $_POST["namapembeli"] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Alamat</label>
                                    <div class="col-sm-8">
                                        <textarea class="form-control" style="height: 100px;" id="alamatpembeli" name="alamatpembeli"><?php echo $_POST["alamatpembeli"] ?></textarea>

                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Negara</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="namanegarapembeli" id="namanegarapembeli" class="form-control" value="<?php echo $_POST["namanegarapembeli"] ?>">
                                        <input type="hidden" name="kodenegarapembeli" id="kodenegarapembeli" class="form-control" value="<?php echo $_POST["kodenegarapembeli"] ?>">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="card shadow p-1 mb-2 bg-white rounded">
                            <div class="card-body">
                                <div class="form-group row" style="border-bottom:solid 0.5px #31708f;">
                                    <label class="col-sm-12 col-form-label">Pemilik</label>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Nomor Identitas</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="jenisidentitaspemilik" class="form-control" readonly="readonly" value="<?php echo $_POST["jenisidentitaspemilik"] ?>">
                                        <input type="hidden" name="kodejenisidentitaspemilik" class="form-control" readonly="readonly" value="<?php echo $_POST["kodejenisidentitaspemilik"] ?>">
                                        <input type="hidden" name="nibpemilik" class="form-control" readonly="readonly" value="<?php echo $_POST["nibpemilik"] ?>">
                                        <input type="hidden" name="nomorijinentitaspemilik" class="form-control" readonly="readonly" value="<?php echo $_POST["nomorijinentitaspemilik"] ?>">
                                        <input type="hidden" name="tanggalijinentitaspemilik" class="form-control" readonly="readonly" value="<?php echo $_POST["tanggalijinentitaspemilik"] ?>">
                                        <input type="hidden" name="kodestatuspemilik" class="form-control" readonly="readonly" value="<?php echo $_POST["kodestatuspemilik"] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">&nbsp;</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="nomoridentitaspemilik" class="form-control" readonly="readonly" value="<?php echo $_POST["nomoridentitaspemilik"] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Nama</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="namapemilik" class="form-control" readonly="readonly" value="<?php echo $_POST["namapemilik"] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Alamat</label>
                                    <div class="col-sm-8">
                                        <textarea class="form-control" style="height: 100px;" id="alamatpemilik" name="alamatpemilik" readonly="readonly"><?php echo $_POST["alamatpemilik"] ?></textarea>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
            <div class="accordion-header" role="button" data-toggle="collapse" data-target="#panel-body-4">
                <h4>Pengangkut</h4>
            </div>
            <div class="accordion-body collapse" id="panel-body-4" data-parent="#accordion">
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow p-1 mb-2 bg-white rounded">
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Tempat Penimbunan</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="namatps" id="namatps" class="form-control" value="<?php echo $_POST["namatps"] ?>">
                                        <input type="hidden" name="kodetps" id="kodetps" class="form-control" value="<?php echo $_POST["kodetps"] ?>">
                                    </div>
                                    <label class="col-sm-2 col-form-label">Lokasi Pemeriksaan</label>
                                    <div class="col-sm-4">
                                        <select class="form-control" name="kodelokasi" required="required">
                                            <option value="">-Pilih-</option>
                                            <option value="1" <?php if ($_POST['kodelokasi'] == "1") {
                                                                    echo "selected";
                                                                } ?>>KP Tempat Pemuatan</option>
                                            <option value="2" <?php if ($_POST['kodelokasi'] == "2") {
                                                                    echo "selected";
                                                                } ?>>Gudang Eksportir</option>
                                            <option value="3" <?php if ($_POST['kodelokasi'] == "3") {
                                                                    echo "selected";
                                                                } ?>>Tempat Lain yang diizinkan</option>
                                            <option value="4" <?php if ($_POST['kodelokasi'] == "4") {
                                                                    echo "selected";
                                                                } ?>>TPS</option>
                                            <option value="5" <?php if ($_POST['kodelokasi'] == "5") {
                                                                    echo "selected";
                                                                } ?>>TPP</option>
                                            <option value="6" <?php if ($_POST['kodelokasi'] == "6") {
                                                                    echo "selected";
                                                                } ?>>TPB</option>

                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Pelabuhan Muat Asal</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="namapelmuat" id="namapelmuat" class="form-control" value="<?php echo $_POST["namapelmuat"] ?>">
                                        <input type="hidden" name="kodepelmuat" id="kodepelmuat" class="form-control" value="<?php echo $_POST["kodepelmuat"] ?>">
                                    </div>
                                    <label class="col-sm-2 col-form-label">Tanggal Periksa</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="tanggalperiksa" class="form-control datepicker" value="<?php echo $_POST["tanggalperiksa"] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Pelabuhan Bongkar</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="namapelbongkar" id="namapelbongkar" class="form-control" value="<?php echo $_POST["namapelbongkar"] ?>">
                                        <input type="hidden" name="kodepelbongkar" id="kodepelbongkar" class="form-control" value="<?php echo $_POST["kodepelbongkar"] ?>">
                                    </div>
                                    <label class="col-sm-2 col-form-label">Kantor Periksa</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="namakantorperiksa" id="namakantorperiksa" class="form-control" value="<?php echo $_POST["namakantorperiksa"] ?>">
                                        <input type="hidden" name="kodekantorperiksa" id="kodekantorperiksa" class="form-control" value="<?php echo $_POST["kodekantorperiksa"] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Pelabuhan Tujuan</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="namapeltujuan" id="namapeltujuan" class="form-control" value="<?php echo $_POST["namapeltujuan"] ?>">
                                        <input type="hidden" name="kodepeltujuan" id="kodepeltujuan" class="form-control" value="<?php echo $_POST["kodepeltujuan"] ?>">
                                    </div>
                                    <label class="col-sm-2 col-form-label">Negara Tujuan Ekspor</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="namanegaratujuan" id="namanegaratujuan" class="form-control" value="<?php echo $_POST["namanegaratujuan"] ?>">
                                        <input type="hidden" name="kodenegaratujuan" id="kodenegaratujuan" class="form-control" value="<?php echo $_POST["kodenegaratujuan"] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Tanggal Perkiraan Ekspor</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="tanggalekspor" class="form-control datepicker" value="<?php echo $_POST["tanggalekspor"] ?>">
                                    </div>

                                </div>

                                <div class="form-group row" style="border-bottom:solid 0.5px #31708f;">
                                    <label class="col-sm-12 col-form-label">Sarana Pengangkut</label>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Nama Sarana Pengangkut</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="namapengangkut" class="form-control" value="<?php echo $_POST["namapengangkut"] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Cara Pengangkutan</label>
                                    <div class="col-sm-8">
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
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Nomor Voy / Flight</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="nomorpengangkut" class="form-control" value="<?php echo $_POST["nomorpengangkut"] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Bendera</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="bendera" id="bendera" class="form-control" required="required" value="<?php echo $_POST["bendera"] ?>">
                                        <input type="hidden" name="kodebendera" id="kodebendera" class="form-control" value="<?php echo $_POST["kodebendera"] ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion-header" role="button" data-toggle="collapse" data-target="#panel-body-5">
                <h4>Kemasan Dan Peti Kemas</h4>
            </div>
            <div class="accordion-body collapse" id="panel-body-5" data-parent="#accordion">
                <div class="row">
                    <div class="col-6">
                        <div class="card shadow p-1 mb-2 bg-white rounded">
                            <div class="card-body">
                                <div class="form-group row" style="border-bottom:solid 0.5px #31708f;">
                                    <label class="col-sm-12 col-form-label">Kemasan</label>
                                </div>
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
                            <div class="card-body">
                                <div class="form-group row" style="border-bottom:solid 0.5px #31708f;">
                                    <label class="col-sm-6 col-form-label">Peti Kemas</label>
                                    <div class="col-sm-6 mb-2" style="float: right;">
                                        <a href="javascript:void(0);" onclick="popup('nometer', 'master/ceisa/listso/add_kontainer.php', '1100', '500')">
                                            <button class="btn btn-primary" type="button" style="border-radius: 0;"><i class="fa fa-plus"></i> Kontainer </button>
                                        </a>
                                    </div>
                                </div>
                                <div class="form-group row">
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
                                                    <input type="hidden" name="seqkontainer<?php echo $nokontainer ?>" value="<?php echo $row_kon['SeqKontainer'] ?>">
                                                    <input type="hidden" name="nomorkontiner<?php echo $nokontainer ?>" value="<?php echo $row_kon['NomorKontiner'] ?>">
                                                    <input type="hidden" name="kodeukurankontainer<?php echo $nokontainer ?>" value="<?php echo $row_kon['KodeUkuranKontainer'] ?>">
                                                    <input type="hidden" name="kodejeniskontainer<?php echo $nokontainer ?>" value="<?php echo $row_kon['KodeJenisKontainer'] ?>">
                                                    <input type="hidden" name="kodetipekontainer<?php echo $nokontainer ?>" value="<?php echo $row_kon['KodeTipeKontainer'] ?>">
                                                </tr>
                                            <?php
                                            $nokontainer++;
                                            }
                                            
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
            <div class="accordion-header" role="button" data-toggle="collapse" data-target="#panel-body-6">
                <h4>Transaksi</h4>
            </div>
            <div class="accordion-body collapse" id="panel-body-6" data-parent="#accordion">
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow p-1 mb-2 bg-white rounded">
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Valuta</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="namavaluta" id="namavaluta" class="form-control" required="required" value="<?php echo $_POST["namavaluta"] ?>">
                                        <input type="hidden" name="kodevaluta" id="kodevaluta" class="form-control" value="<?php echo $_POST["kodevaluta"] ?>">
                                    </div>
                                    <label class="col-sm-2 col-form-label">Berat Kotor (KGM)</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="bruto" class="form-control" value="<?php echo $_POST["bruto"] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">NDPMB</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="ndpbm" class="form-control" value="<?php echo $_POST["ndpbm"] ?>">
                                    </div>
                                    <label class="col-sm-2 col-form-label">Berat Bersih (KGM)</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="netto" class="form-control" value="<?php echo $_POST["netto"] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Cara Penyerahan </label>
                                    <div class="col-sm-4">
                                        <select class="form-control" name="kodeincoterm" required="required">
                                            <option value="">-Pilih-</option>
                                            <?php
                                            $sql_inc = "SELECT KodeIncoterm, NamaIncoterm FROM ms_incoterm 
                                                        WHERE NamaIncoterm !='' ";
                                            $data_inc = $sqlLib->select($sql_inc);
                                            foreach ($data_inc as $row) { ?>
                                                <option value="<?php echo $row['KodeIncoterm'] ?>" <?php if ($_POST['kodeincoterm'] == $row['KodeIncoterm']) {
                                                                                                        echo "selected";
                                                                                                    } ?>>
                                                    (<?php echo $row['KodeIncoterm'] ?>) <?php echo $row['NamaIncoterm'] ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <label class="col-sm-2 col-form-label">Nilai Maklon</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="nilaimaklon" class="form-control" value="<?php echo $_POST["jumlahkemasan"] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Nilai Ekspor FOB</label>
                                    <div class="col-sm-4">
                                        <?php $_POST["fob"] = $data_dt[0]['TotalAmount']; ?>
                                        <input type="text" name="fob" class="form-control" value="<?php echo $_POST["fob"] ?>">
                                    </div>
                                    <label class="col-sm-2 col-form-label">Nilai Bea Keluar</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="nilaibeakeluar" class="form-control" value="<?php echo $_POST["nilaibeakeluar"] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Freight</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="freight" class="form-control" value="<?php echo $_POST["freight"] ?>">
                                    </div>
                                    <label class="col-sm-2 col-form-label">
                                        <input type="checkbox" name="chkpph" value="1"> PPh</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="ppn" class="form-control" value="<?php echo $_POST["ppn"] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Asuransi</label>
                                    <div class="col-sm-2">
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
                                    <div class="col-sm-2">
                                        <input type="text" name="asuransi" class="form-control" value="<?php echo $_POST["asuransi"] ?>">
                                    </div>
                                    <label class="col-sm-2 col-form-label">Nilai Pungutan Sawit</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="nilaipungutansawit" class="form-control" value="<?php echo $_POST["nilaipungutansawit"] ?>">
                                    </div>
                                </div>

                                <div class="form-group row" style="border-bottom:solid 0.5px #31708f;">
                                    <label class="col-sm-12 col-form-label">Bank Devisa</label>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Nama Bank</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="namabank" id="namabank" class="form-control" required="required" value="<?php echo $_POST["namabank"] ?>">
                                        <input type="hidden" name="kodebank" id="kodebank" class="form-control" value="<?php echo $_POST["kodebank"] ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion-header" role="button" data-toggle="collapse" data-target="#panel-body-7">
                <h4>Barang</h4>
            </div>
            <div class="accordion-body collapse" id="panel-body-7" data-parent="#accordion">
                <?php
                $no = 1;
                foreach ($data_dt as $row) {
                    $_POST["jumlahsatuan"] = $row['Qty']; 
                    $_POST["fob"] = $row['TotalHarga']; ?>
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
                                    <div class="col-sm-4  ml-3">
                                        <?php echo $row['NamaBarang'] ?> (<?php echo $row['Qty'] . ' ' . $row['Satuan'] . ' ' . $row['Harga'] ?>)
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
                                                <div class="card-body">
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label">HS Number</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" name="hsnumber<?php echo $no ?>" id="hsnumber<?php echo $no ?>" class="form-control">
                                                        </div>
                                                        <label class="col-sm-2 col-form-label">Satuan</label>
                                                        <div class="col-sm-2">
                                                            <input type="text" name="jumlahsatuan<?php echo $no ?>" class="form-control" value="<?php echo $_POST["jumlahsatuan"] ?>" placeholder="0.00">
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <input type="text" name="namasatuanbarang<?php echo $no ?>" id="namasatuanbarang<?php echo $no ?>" class="form-control">
                                                            <input type="hidden" name="kodesatuanbarang<?php echo $no ?>" id="kodesatuanbarang<?php echo $no ?>" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label">Uraian</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" name="uraian<?php echo $no ?>" id="uraian<?php echo $no ?>" value="<?php echo $row['NamaBarang'] ?>" class="form-control">
                                                            <input type="hidden" name="kodebarang<?php echo $no ?>" id="kodebarang<?php echo $no ?>" value="<?php echo $row['KdBarang'] ?>" class="form-control">
                                                        </div>
                                                        <label class="col-sm-2 col-form-label">Kemasan</label>
                                                        <div class="col-sm-2">
                                                            <input type="text" name="jumlahkemasan<?php echo $no ?>" class="form-control" placeholder="0">
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <input type="text" name="jeniskemasan<?php echo $no ?>" id="jeniskemasan<?php echo $no ?>" class="form-control">
                                                            <input type="hidden" name="kodejeniskemasan<?php echo $no ?>" id="kodejeniskemasan<?php echo $no ?>" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label">Merk</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" name="merk<?php echo $no ?>" id="merk<?php echo $no ?>" class="form-control">
                                                        </div>
                                                        <label class="col-sm-2 col-form-label">Harga Fob</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" name="fob<?php echo $no ?>" class="form-control" value="<?php echo $_POST["fob"]; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label">Tipe</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" name="tipe<?php echo $no ?>" id="tipe<?php echo $no ?>" class="form-control">
                                                        </div>
                                                        <label class="col-sm-2 col-form-label">Volume</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" name="volume_dt<?php echo $no ?>" class="form-control" placeholder="0.00">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label">Ukuran</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" name="ukuran<?php echo $no ?>" id="ukuran<?php echo $no ?>" class="form-control">
                                                        </div>
                                                        <label class="col-sm-2 col-form-label">Berat Bersih (Kg)</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" name="netto_dt<?php echo $no ?>" class="form-control" placeholder="0.00">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label">Negara Asal Barang</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" name="namanegara<?php echo $no ?>" id="namanegara<?php echo $no ?>" class="form-control">
                                                            <input type="hidden" name="kodenegara<?php echo $no ?>" id="kodenegara<?php echo $no ?>" class="form-control">
                                                        </div>
                                                        <label class="col-sm-2 col-form-label">Harga Satuan FOB</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" name="hargasatuan<?php echo $no ?>" class="form-control" value="<?php echo $row['Harga'] ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label">Daerah Asal Barang</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" name="namadaerah<?php echo $no ?>" id="namadaerah<?php echo $no ?>" class="form-control">
                                                            <input type="hidden" name="kodedaerah<?php echo $no ?>" id="kodedaerah<?php echo $no ?>" class="form-control">
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
                <?php
                    $no++;
                } ?>
                <input type="hidden" name="jmlrow" id="jmlrow" value="<?php echo $no - 1; ?>">
            </div>
            <div class="accordion-header" role="button" data-toggle="collapse" data-target="#panel-body-8">
                <h4>Kesiapan Barang</h4>
            </div>
            <div class="accordion-body collapse" id="panel-body-8" data-parent="#accordion">
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow p-1 mb-2 bg-white rounded">
                            <div class="card-body">
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
            </div>

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