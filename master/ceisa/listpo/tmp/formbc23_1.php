<?php
$sql_pt = "SELECT a.NamaPerusahaan, a.Alamat, a.KodeJenisIdentitas, a.NomorIdentitas,  a.NIB, a.Nama, a.Jabatan, a.KodeJenisTpb, a.Kota, a.NomorIjinEntitas, 
                      a.TanggalIjinEntitas, a.KodeKantor, a.KantorPabean , a.KodeStatus
                  FROM ms_perusahaan a
                  WHERE a.IDPerusahaan ='1' ";
$data_pt = $sqlLib->select($sql_pt);

$sql_po = "SELECT a.NoPO, a.TanggalPo, a.Vendor, a.Alamat, a.Subtotal, a.Tax2Amount, a.TotalAmount,
                              b.KdBarang, b.NamaBarang, b.Harga, b.Qty, b.ItemDiscPercent, b.ItemCost, b.Satuan, b.TotalHarga
                          FROM ac_po a
                          LEFT JOIN ac_po_detail b on b.NoPO = a.NoPO
                          WHERE a.NoPO = '" . $_POST["nopo"]  . "' ";
$data_po = $sqlLib->select($sql_po);
$_POST["alamatentitaspengirim"] = $data_po[0]['Alamat'];
$_POST["namaentitaspengirim"] = $data_po[0]['Vendor'];

$sql_urut = "SELECT MAX(Urut) as Urut FROM ms_dokumen_aju 
                    WHERE DokumenBC = '23' AND  YEAR(TanggalAju) = '" . date("Y") . "' ";
$data_urut = $sqlLib->select($sql_urut);
$urut = $data_urut[0]['Urut'] + 1;
$nomor = str_pad($urut, 6, '0', STR_PAD_LEFT);
$_POST["nomor"] = $nomor;
$_POST["urut"] = $urut;
//$data_pt[0]['KodeKantor'] . //, strtotime($_POST['tanggalaju'])
$_POST["nomoraju"] =  '0000' . $_POST['kodedokumenbc'] . '-' . substr($data_pt[0]['NomorIdentitas'], 0, 6) . '-' . date("Ymd") . '-' . $nomor;
$_POST['kodekantor'] = $data_pt[0]['KodeKantor'];
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

$_POST["alamatentitaspemilik"] = $data_pt[0]['Alamat'];
$_POST["kodejenisidentitaspemilik"] = $data_pt[0]['KodeJenisIdentitas'];
$_POST["namaentitaspemilik"] = $data_pt[0]['NamaPerusahaan'];
$_POST["nibentitaspemilik"] = $data_pt[0]['NIB'];
$_POST["nomoridentitaspemilik"] = $data_pt[0]['NomorIdentitas'];
$_POST["nomorijinentitaspemilik"] = $data_pt[0]['NomorIjinEntitas'];
$_POST["tanggalijinentitaspemilik"] = $data_pt[0]['TanggalIjinEntitas'];

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
                                <div class="col-sm-12">
                                    <label>Pelabuhan Bongkar</label>
                                    <input type="text" name="pelbongkar" id="pelbongkar" class="form-control" value="<?php echo $_POST["pelbongkar"] ?>">
                                    <input type="hidden" name="kodepelbongkar" id="kodepelbongkar" class="form-control" readonly="readonly" value="<?php echo $_POST["kodepelbongkar"] ?>">
                                </div>
                                <div class="col-sm-12 mt-3">
                                    <label>Kantor Pabean Bongkar</label>
                                    <input type="text" name="kantorbongkar" id="kantorbongkar" class="form-control" value="<?php echo $_POST["kantorbongkar"] ?>">
                                    <input type="hidden" name="kodekantorbongkar" id="kodekantorbongkar" class="form-control" value="<?php echo $_POST["kodekantorbongkar"] ?>">
                                </div>
                                <div class="col-sm-12 mt-3">
                                    <label>Kantor Pabean Pengawas</label>
                                    <input type="text" name="namakantor" id="namakantor" class="form-control" value="<?php echo $_POST["namakantor"] ?>">
                                    <input type="hidden" name="kodekantor" id="kodekantor" class="form-control" value="<?php echo $_POST["kodekantor"] ?>">
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
                                    <label>Tujuan</label>
                                    <select class="form-control" name="kodetujuantpb" required="required">
                                        <option value="">-Pilih-</option>
                                        <option value="1" <?php if ($_POST['kodetujuantpb'] == "1") {
                                                                echo "selected";
                                                            } ?>>Kawasan Berikat</option>
                                        <option value="2" <?php if ($_POST['kodetujuantpb'] == "2") {
                                                                echo "selected";
                                                            } ?>>Gudang Berikat</option>
                                        <option value="3" <?php if ($_POST['kodetujuantpb'] == "3") {
                                                                echo "selected";
                                                            } ?>>TPPB</option>
                                        <option value="4" <?php if ($_POST['kodetujuantpb'] == "4") {
                                                                echo "selected";
                                                            } ?>>TBB</option>
                                        <option value="5" <?php if ($_POST['kodetujuantpb'] == "5") {
                                                                echo "selected";
                                                            } ?>>TLB</option>
                                        <option value="6" <?php if ($_POST['kodetujuantpb'] == "6") {
                                                                echo "selected";
                                                            } ?>>KDUB</option>
                                        <option value="7" <?php if ($_POST['kodetujuantpb'] == "7") {
                                                                echo "selected";
                                                            } ?>>PLB</option>
                                        <option value="8" <?php if ($_POST['kodetujuantpb'] == "8") {
                                                                echo "selected";
                                                            } ?>>LAINNYA</option>

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
                                <h4>Importir / Pengusaha TPB</h4>
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
                                        <textarea class="form-control" rows="5" id="alamatentitaspengusaha" name="alamatentitaspengusaha"><?php echo $_POST["alamatentitaspengusaha"] ?></textarea>
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
                                <h4>Pemasok</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-sm-12 mt-3">
                                        <label>Nama</label>
                                        <input type="text" name="namaentitaspengirim" class="form-control" readonly="readonly" value="<?php echo $_POST["namaentitaspengirim"] ?>">
                                    </div>
                                    <div class="col-sm-12 mt-3">
                                        <label>Alamat</label>
                                        <textarea class="form-control" rows="5" id="alamatentitaspengirim" name="alamatentitaspengirim"><?php echo $_POST["alamatentitaspengirim"] ?></textarea>
                                    </div>
                                    <div class="col-sm-12 mt-3">
                                        <label>Negara</label>
                                        <input type="text" name="namanegara" id="namanegara" class="form-control" value="<?php echo $_POST["namanegara"] ?>">
                                        <input type="hidden" name="kodenegara" id="kodenegara" class="form-control" value="<?php echo $_POST["kodenegara"] ?>">
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
                                        <input type="hidden" name="nomorijinentitaspemilik" class="form-control" readonly="readonly" value="<?php echo $_POST["nomorijinentitaspemilik"] ?>">
                                        <input type="hidden" name="tanggalijinentitaspemilik" class="form-control" readonly="readonly" value="<?php echo $_POST["tanggalijinentitaspemilik"] ?>">
                                    </div>
                                    <div class="col-sm-12 mt-3">
                                        <label>Nama</label>
                                        <input type="text" name="namaentitaspemilik" class="form-control" readonly="readonly" value="<?php echo $_POST["namaentitaspemilik"] ?>">
                                    </div>
                                    <div class="col-sm-12 mt-3">
                                        <label>Alamat</label>
                                        <textarea class="form-control" rows="5" id="alamatentitaspemilik" name="alamatentitaspemilik"><?php echo $_POST["alamatentitaspemilik"] ?></textarea>
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
                    <div class="col-12 col-md-4 col-lg-4">
                        <div class="card shadow p-1 mb-2 bg-white rounded">
                            <div class="card-header" style="border-bottom:solid 0.5px #31708f;">
                                <h4>Invoice</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label>Jenis Dokumen</label>
                                        <input type="text" name="jenisdokumen1" id="jenisdokumen1" readonly="readonly" class="form-control" value="Invoice">
                                        <input type="hidden" name="kodedokumen1" id="kodedokumen1" class="form-control" value="380">
                                    </div>
                                    <div class="col-sm-12 mt-3">
                                        <label>Nomor Dokumen</label>
                                        <input type="text" name="nomordokumen1" class="form-control" value="<?php echo $_POST["nomordokumen1"] ?>">
                                    </div>
                                    <div class="col-sm-12 mt-3">
                                        <label>Tanggal Dokumen</label>
                                        <input type="text" name="tgldokumen1" class="form-control datepicker" readonly="readonly"  value="<?php echo $_POST["tgldokumen1"] ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 col-lg-4">
                        <div class="card shadow p-1 mb-2 bg-white rounded">
                            <div class="card-header" style="border-bottom:solid 0.5px #31708f;">
                                <h4>BL/AWB</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label>Jenis Dokumen</label>
                                        <select class="form-control" name="kodedokumen2" required="required">
                                            <option value="">-Pilih-</option>
                                            <option value="705" <?php if ($_POST['kodedokumen2'] == "705") {
                                                                    echo "selected";
                                                                } ?>>B/L</option>
                                            <option value="740" <?php if ($_POST['kodedokumen2'] == "740") {
                                                                    echo "selected";
                                                                } ?>>AWB</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-12 mt-3">
                                        <label>Nomor Dokumen</label>
                                        <input type="text" name="nomordokumen2" class="form-control"  value="<?php echo $_POST["nomordokumen2"] ?>">
                                    </div>
                                    <div class="col-sm-12 mt-3">
                                        <label>Tanggal Dokumen</label>
                                        <input type="text" name="tgldokumen2" class="form-control datepicker" readonly="readonly"  value="<?php echo $_POST["tgldokumen2"] ?>">
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
                    <div class="col-12 col-md-4 col-lg-4">
                        <div class="card shadow p-1 mb-2 bg-white rounded">
                            <div class="card-header" style="border-bottom:solid 0.5px #31708f;">
                                <h4>BC 1.1</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label>Nomor BC 1.1</label>
                                        <input type="text" name="nomorbc11" class="form-control" required="required" value="<?php echo $_POST["nomorbc11"] ?>">
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Tanggal BC 1.1</label>
                                        <input type="text" name="tglbc11" class="form-control datepicker" readonly="readonly" required="required" value="<?php echo $_POST["tglbc11"] ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label>Nomor Pos</label>
                                        <input type="text" name="posbc11" class="form-control" required="required" value="<?php echo $_POST["posbc11"] ?>">
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Subpos BC 1.1</label>
                                        <input type="text" name="subposbc11" class="form-control" required="required" value="<?php echo $_POST["subposBc11"] ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 col-lg-4">
                        <div class="card shadow p-1 mb-2 bg-white rounded">
                            <div class="card-header" style="border-bottom:solid 0.5px #31708f;">
                                <h4>Pengangkutan</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label>Cara Pengangkutan</label>
                                        <select class="form-control" name="kodeCaraAngkut" required="required">
                                            <option value="">-Pilih-</option>
                                            <option value="1" <?php if ($_POST['kodeCaraAngkut'] == "1") {
                                                                    echo "selected";
                                                                } ?>>Laut</option>
                                            <option value="2" <?php if ($_POST['kodeCaraAngkut'] == "2") {
                                                                    echo "selected";
                                                                } ?>>Kereta Api</option>
                                            <option value="3" <?php if ($_POST['kodeCaraAngkut'] == "3") {
                                                                    echo "selected";
                                                                } ?>>Darat</option>
                                            <option value="4" <?php if ($_POST['kodeCaraAngkut'] == "4") {
                                                                    echo "selected";
                                                                } ?>>Udara</option>
                                            <option value="5" <?php if ($_POST['kodeCaraAngkut'] == "5") {
                                                                    echo "selected";
                                                                } ?>>Pos</option>
                                            <option value="6" <?php if ($_POST['kodeCaraAngkut'] == "6") {
                                                                    echo "selected";
                                                                } ?>>Multimoda</option>
                                            <option value="7" <?php if ($_POST['kodeCaraAngkut'] == "7") {
                                                                    echo "selected";
                                                                } ?>>Instalasi/Pipa</option>
                                            <option value="8" <?php if ($_POST['kodeCaraAngkut'] == "8") {
                                                                    echo "selected";
                                                                } ?>>Perairan</option>
                                            <option value="9" <?php if ($_POST['kodeCaraAngkut'] == "9") {
                                                                    echo "selected";
                                                                } ?>>Lainnya</option>                    
                                        </select>
                                    </div>
                                    <div class="col-sm-12 mt-3">
                                        <label>Nama Sarana Angkut</label>
                                        <input type="text" name="namapengangkut" class="form-control" required="required" value="<?php echo $_POST["namapengangkut"] ?>">
                                    </div>
                                    <div class="col-sm-12 mt-3">
                                        <label>Nomor Voy / Flight</label>
                                        <input type="text" name="nomorpengangkut" class="form-control" required="required" value="<?php echo $_POST["nomorpengangkut"] ?>">
                                    </div>
                                    <div class="col-sm-12 mt-3">
                                        <label>Bendera</label>
                                        <input type="text" name="bendera" id="bendera" class="form-control" required="required" value="<?php echo $_POST["bendera"] ?>">
                                        <input type="hidden" name="kodebendera" id="kodebendera" class="form-control" required="required" value="<?php echo $_POST["kodebendera"] ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 col-lg-4">
                        <div class="card shadow p-1 mb-2 bg-white rounded">
                            <div class="card-header" style="border-bottom:solid 0.5px #31708f;">
                                <h4>Pelabuhan dan Tempat Penimbunan</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label>Pelabuhan Muat</label>
                                        <input type="text" name="pelmuat" id="pelmuat" class="form-control" required="required" value="<?php echo $_POST["pelmuat"] ?>">
                                        <input type="hidden" name="kodepelmuat" id="kodepelmuat" class="form-control" required="required" value="<?php echo $_POST["kodepelmuat"] ?>">
                                    </div> 
                                    <div class="col-sm-12">
                                        <label>Tempat Penimbunan</label>
                                        <input type="text" name="namatps" id="namatps" class="form-control" required="required" value="<?php echo $_POST["namatps"] ?>">
                                        <input type="hidden" name="kodetps" id="kodetps" class="form-control" required="required" value="<?php echo $_POST["kodetps"] ?>">
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
                <h4>Kemasan</h4>
            </div>
            <div class="accordion-body collapse" id="panel-body-5" data-parent="#accordion">
                <div class="row">
                    <div class="col-12 col-md-4 col-lg-4">
                        <div class="card shadow p-1 mb-2 bg-white rounded">
                            <div class="card-header" style="border-bottom:solid 0.5px #31708f;">
                                <h4>Kemasan</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label>Jumlah Kemasan</label>
                                        <input type="text" name="jumlahkemasan" class="form-control" required="required" value="<?php echo $_POST["jumlahkemasan"] ?>">
                                    </div>
                                    <div class="col-sm-12 mt-3">
                                        <label>Jenis Kemasan</label>
                                        <input type="text" name="jeniskemasan" id="jeniskemasan" class="form-control" required="required" value="<?php echo $_POST["jeniskemasan"] ?>">
                                        <input type="hidden" name="kodejeniskemasan" id="kodejeniskemasan" class="form-control" value="<?php echo $_POST["kodejeniskemasan"] ?>">
                                    </div>
                                    <div class="col-sm-12 mt-3">
                                        <label>Merk Kemasan</label>
                                        <input type="text" name="merkkemasan" class="form-control" required="required" value="<?php echo $_POST["merkkemasan"] ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 col-lg-4">
                        <div class="card shadow p-1 mb-2 bg-white rounded">
                            <div class="card-header" style="border-bottom:solid 0.5px #31708f;">
                                <h4>Kontainer</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label>Jumlah Kontainer</label>
                                        <input type="text" name="jumlahkontainer" class="form-control" required="required" value="<?php echo $_POST["jumlahkontainer"] ?>">
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
                                    <div class="col-sm-6 mt-3">
                                        <label>Harga Barang</label>
                                        <select class="form-control" name="kodeincoterm" required="required">
                                            <option value="">-Pilih-</option>
                                            <?php 
                                            $sql_inc = "SELECT KodeIncoterm, NamaIncoterm FROM ms_incoterm 
                                                        WHERE NamaIncoterm !='' "; 
                                            $data_inc= $sqlLib->select($sql_inc);  
                                            foreach ($data_inc as $row) { ?>
                                                <option value="<?php echo $row['KodeIncoterm'] ?>" 
                                                    <?php if ($_POST['kodeincoterm'] == $row['KodeIncoterm'] ) { echo "selected";} ?>>
                                                    (<?php echo $row['KodeIncoterm'] ?>) <?php echo $row['NamaIncoterm'] ?>
                                                </option>              
                                                    <?php } ?>                   
                                        </select>
                                    </div>
                                    <div class="col-sm-6 mt-3">
                                        <label>&nbsp;</label>
                                        <input type="text" name="incoterm" class="form-control"  value="<?php echo $_POST["incoterm"] ?>">
                                    </div>
                                    <div class="col-sm-12 mt-3">
                                        <label>Nilai CIF</label>
                                        <input type="text" name="cif" class="form-control"  value="<?php echo $_POST["cif"] ?>">
                                    </div>
                                    <div class="col-sm-12 mt-3">
                                        <label>Nilai Pabean</label>
                                        <input type="text" name="nilaibarang" class="form-control"  value="<?php echo $_POST["nilaibarang"] ?>">
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
                                        <label>Biaya Penambahan</label>
                                        <input type="text" name="biayatambahan" class="form-control" value="<?php echo $_POST["biayatambahan"] ?>">
                                    </div>
                                    <div class="col-sm-12 mt-3">
                                        <label>Biaya Penambahan</label>
                                        <input type="text" name="biayapengurang" class="form-control" value="<?php echo $_POST["biayapengurang"] ?>">
                                    </div>
                                    <div class="col-sm-12 mt-3">
                                        <label>FOB</label>
                                        <input type="text" name="fob" class="form-control"  value="<?php echo $_POST["fob"] ?>">
                                    </div>
                                    <div class="col-sm-12 mt-3">
                                        <label>Freight</label>
                                        <input type="text" name="freight" class="form-control"  value="<?php echo $_POST["freight"] ?>">
                                    </div>
                                    <div class="col-sm-6 mt-3">
                                        <label>Asuransi</label>
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
                                    <div class="col-sm-6 mt-3">
                                        <label>&nbsp;</label>
                                        <input type="text" name="asuransi" class="form-control"  value="<?php echo $_POST["asuransi"] ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 col-lg-4">
                        <div class="card shadow p-1 mb-2 bg-white rounded">
                            <div class="card-header" style="border-bottom:solid 0.5px #31708f;">
                                <h4>Keterangan Pajak</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label>Jasa Kena Pajak</label>
                                        <select class="form-control" name="kodekenapajak" required="required">
                                            <option value="">-Pilih-</option>
                                            <option value="1" <?php if ($_POST['kodekenapajak'] == "1") {
                                                                    echo "selected";
                                                                } ?>>PEMBELIAN BKP</option>
                                            <option value="2" <?php if ($_POST['kodekenapajak'] == "2") {
                                                                    echo "selected";
                                                                } ?>>PENERIMA JASA BKP</option>
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
            <div class="accordion-header" role="button" data-toggle="collapse" data-target="#panel-body-7">
                <h4>Barang</h4>
            </div>
            <div class="accordion-body collapse" id="panel-body-7" data-parent="#accordion">
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow p-1 mb-2 bg-white rounded">
                            <div class="card-header" style="border-bottom:solid 0.5px #31708f;">
                                <h4>Barang</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Nama Barang</th>
                                                    <th scope="col">Sisa PO</th>
                                                    <th scope="col">HS Number</th>
                                                    <th scope="col">Bruto</th>
                                                    <th scope="col">Netto</th>
                                                    <th scope="col">Volume</th>
                                                    <th scope="col">Qty Aju</th>
                                                    <th scope="col">Total Harga</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $no = 1;
                                                $sql_dt = "SELECT a.NoPO, a.TanggalPo, a.Vendor, a.Alamat, a.Subtotal, a.Tax2Amount, a.TotalAmount,
                                                                  b.KdBarang, b.NamaBarang, b.Harga, b.Qty, b.ItemDiscPercent, b.ItemCost, b.Satuan, b.TotalHarga, b.SeqItem
                                                              FROM ac_po a
                                                              LEFT JOIN ac_po_detail b on b.NoPO = a.NoPO
                                                              WHERE a.NoPO = '" . $_POST['nopo'] . "' ";
                                                $data_dt = $sqlLib->select($sql_dt);
                                                foreach ($data_dt as $row) {
                                                    $sql_aju = "SELECT SUM(a.Qty) as QtyAju
                                                            FROM ms_dokumen_aju_detail a
                                                            WHERE a.NoPO='" . $row['NoPO'] . "' AND a.KdBarang ='" . $row['KdBarang'] . "' AND a.SeqItem ='" . $row['SeqItem'] . "'";
                                                    $data_aju = $sqlLib->select($sql_aju);

                                                    $sisa = $row['Qty'] - $data_aju[0]['QtyAju'];
                                                ?>
                                                    <tr>
                                                        <td><?php echo $row['NamaBarang'] ?></td>
                                                        <input type="hidden" name="harga<?php echo $no ?>" id="harga<?php echo $no ?>" value="<?php echo $row['Harga'] ?>">
                                                        <input type="hidden" name="kdbarang<?php echo $no ?>" id="kdbarang<?php echo $no ?>" value="<?php echo $row['KdBarang'] ?>">
                                                        <input type="hidden" name="namabarang<?php echo $no ?>" id="namabarang<?php echo $no ?>" value="<?php echo $row['NamaBarang'] ?>">
                                                        <input type="hidden" name="satuan<?php echo $no ?>" id="satuan<?php echo $no ?>" value="<?php echo $row['Satuan'] ?>">
                                                        <input type="hidden" name="seqitem<?php echo $no ?>" id="seqitem<?php echo $no ?>" value="<?php echo $row['SeqItem'] ?>">

                                                        </td>
                                                        <td><?php echo $sisa ?>
                                                            <input type="hidden" name="sisa<?php echo $no ?>" id="sisa<?php echo $no ?>" value="<?php echo $sisa ?>">
                                                        </td>
                                                        <td><input type="text" name="hsnumber<?php echo $no ?>" id="hsnumber<?php echo $no ?>" size="10" <?php if ($sisa < 1) {
                                                                                                                                                                echo "disabled";
                                                                                                                                                            } ?>></td>
                                                        <td><input type="text" name="bruto<?php echo $no ?>" id="bruto<?php echo $no ?>" size="10" <?php if ($sisa < 1) {
                                                                                                                                                        echo "disabled";
                                                                                                                                                    } ?>></td>
                                                        <td><input type="text" name="netto<?php echo $no ?>" id="netto<?php echo $no ?>" size="10" <?php if ($sisa < 1) {
                                                                                                                                                        echo "disabled";
                                                                                                                                                    } ?>></td>
                                                        <td><input type="text" name="volume<?php echo $no ?>" id="volume<?php echo $no ?>" size="10" <?php if ($sisa < 1) {
                                                                                                                                                            echo "disabled";
                                                                                                                                                        } ?>></td>
                                                        <td><input type="text" name="qtyaju<?php echo $no ?>" id="qtyaju<?php echo $no ?>" size="10" onchange="inqty('<?php echo $no ?>');" <?php if ($sisa < 1) {
                                                                                                                                                                                                echo "disabled";
                                                                                                                                                                                            } ?>></td>
                                                        <td><input type="text" name="total<?php echo $no ?>" id="total<?php echo $no ?>" size="10" <?php if ($sisa < 1) {
                                                                                                                                                        echo "disabled";
                                                                                                                                                    } ?>></td>
                                                    </tr>
                                                <?php
                                                    $no++;
                                                }
                                                ?>
                                                <input type="hidden" name="jmlrow" id="jmlrow" value="<?php echo $no - 1 ?>">
                                            </tbody>
                                            <footer>
                                                <tr>
                                                    <th scope="col">&nbsp;</th>
                                                    <th scope="col">&nbsp;</th>
                                                    <th scope="col">&nbsp;</th>
                                                    <th scope="col"><input type="text" name="subbruto" id="subbruto" size="10" value="<?php echo number_format($_POST["subbruto"]) ?>" readonly="readonly" /></th>
                                                    <th scope="col"><input type="text" name="subnetto" id="subnetto" size="10" value="<?php echo number_format($_POST["subnetto"]) ?>" readonly="readonly" /></th>
                                                    <th scope="col"><input type="text" name="subvolume" id="subvolume" size="10" value="<?php echo number_format($_POST["subvolume"]) ?>" readonly="readonly" /></th>
                                                    <th scope="col">&nbsp;</th>
                                                    <th scope="col"><input type="text" name="subtotal" id="subtotal" size="10" value="<?php echo number_format($_POST["subtotal"]) ?>" readonly="readonly" /></th>
                                                </tr>
                                            </footer>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--
        <div class="accordion">
            <div class="accordion-header" role="button" data-toggle="collapse" data-target="#panel-body-8">
                <h4>Pungutan</h4>
            </div>
            <div class="accordion-body collapse" id="panel-body-8" data-parent="#accordion">
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow p-1 mb-2 bg-white rounded">
                            <div class="card-header" style="border-bottom:solid 0.5px #31708f;">
                                <h4>Pungutan</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-sm-12">
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
                                    <div class="col-sm-12 mt-3">
                                        <label>Tarif PPN</label>
                                        <input type="text" name="tarifppn" required="required" class="form-control" value="<?php echo $_POST["tarifppn"] ?>">
                                    </div>
                                    <div class="col-sm-12 mt-3">
                                        <label>Nilai Pungutan</label>
                                        <input type="text" name="nilaipungutan" required="required" class="form-control" value="<?php echo $_POST["nilaipungutan"] ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        --> 
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
                                    <input type="text" name="kotattd" class="form-control" required="required" value="<?php echo $_POST["kotattd"] ?>">
                                </div>
                                <div class="col-sm-12">
                                    <label>Tanggal</label>
                                    <input type="text" name="tanggalaju" value="<?php echo $_POST['tanggalaju']; ?>" class="form-control datepicker" readonly="readonly">
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
                                    <input type="text" name="namattd" class="form-control" required="required" value="<?php echo $_POST["namattd"] ?>">
                                </div>
                                <div class="col-sm-12">
                                    <label>Jabatan</label>
                                    <input type="text" name="jabatanttd" class="form-control" required="required" value="<?php echo $_POST["jabatanttd"] ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
       
    </div>
</div>