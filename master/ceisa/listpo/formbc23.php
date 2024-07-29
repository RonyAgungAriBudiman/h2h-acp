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

$sql_urut = "SELECT MAX(Urut) as Urut FROM BC_HEADER 
                      WHERE KodeDokumen = '23' AND  YEAR(TanggalPernyataan) = '" . date("Y") . "' ";
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
                                    <input type="hidden" name="kodepelabuhanbongkar" id="kodepelbongkar" class="form-control" readonly="readonly" value="<?php echo $_POST["kodepelabuhanbongkar"] ?>">
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
                                        <textarea class="form-control" style="height: 100px;" id="alamatentitaspengirim" name="alamatentitaspengirim"><?php echo $_POST["alamatentitaspengirim"] ?></textarea>
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
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <?php
                                                for ($i = 1; $i <= 5; $i++) {
                                                ?>

                                                    <script>
                                                        $(document).ready(function() {
                                                            var ac_config = {
                                                                source: "json/dokumen.php",
                                                                select: function(event, ui) {
                                                                    $("#kodedokumen<?php echo $i ?>").val(ui.item.id);
                                                                    $("#namadokumen<?php echo $i ?>").val(ui.item.namadokumen);
                                                                },
                                                                focus: function(event, ui) {
                                                                    $("#kodedokumen<?php echo $i ?>").val(ui.item.id);
                                                                    $("#namadokumen<?php echo $i ?>").val(ui.item.namadokumen);
                                                                },
                                                                minLength: 1
                                                            };
                                                            $("#namadokumen<?php echo $i ?>").autocomplete(ac_config);
                                                        });
                                                    </script>

                                                    <tr>
                                                        <td><?php echo $i; ?></td>
                                                        <td><input type="text" class="form-control" name="namadokumen<?php echo $i ?>" id="namadokumen<?php echo $i ?>">
                                                            <input type="hidden" class="form-control" name="kodedokumen<?php echo $i ?>" id="kodedokumen<?php echo $i ?>">
                                                        </td>
                                                        <td><input type="text" class="form-control" name="nomordokumen<?php echo $i ?>" id="nomordokumen<?php echo $i ?>"></td>
                                                        <td><input type="text" class="form-control datepicker" name="tanggaldokumen<?php echo $i ?>" id="tanggaldokumen<?php echo $i ?>"></td>
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                                <input type="hidden" name="jmldok" id="jmldok" value="<?php echo $i; ?>">
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
                                        <input type="text" name="tanggalbc11" class="form-control datepicker" readonly="readonly" required="required" value="<?php echo $_POST["tanggalbc11"] ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label>Nomor Pos</label>
                                        <input type="text" name="nomorpos" class="form-control" required="required" value="<?php echo $_POST["nomorpos"] ?>">
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Subpos BC 1.1</label>
                                        <input type="text" name="nomorsubpos" class="form-control" required="required" value="<?php echo $_POST["nomorsubpos"] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label>Tanggal Tiba</label>
                                        <input type="text" name="tanggaltiba" class="form-control datepicker" readonly="readonly" required="required" value="<?php echo $_POST["tanggaltiba"] ?>">
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
                                        <input type="hidden" name="kodepelabuhanmuat" id="kodepelmuat" class="form-control" required="required" value="<?php echo $_POST["kodepelabuhanmuat"] ?>">
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
                                    <div class="col-sm-6 mt-3">
                                        <label>&nbsp;</label>
                                        <input type="text" name="incoterm" class="form-control" value="<?php echo $_POST["incoterm"] ?>">
                                    </div>
                                    <div class="col-sm-12 mt-3">
                                        <label>Nilai CIF</label>
                                        <input type="text" name="cif" class="form-control" value="<?php echo $_POST["cif"] ?>">
                                    </div>
                                    <div class="col-sm-12 mt-3">
                                        <label>Nilai Pabean</label>
                                        <input type="text" name="nilaibarang" class="form-control" value="<?php echo $_POST["nilaibarang"] ?>">
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
                                        <input type="text" name="fob" class="form-control" value="<?php echo $_POST["fob"] ?>">
                                    </div>
                                    <div class="col-sm-12 mt-3">
                                        <label>Freight</label>
                                        <input type="text" name="freight" class="form-control" value="<?php echo $_POST["freight"] ?>">
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
                                        <input type="text" name="asuransi" class="form-control" value="<?php echo $_POST["asuransi"] ?>">
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
                                        <div class="col-12 col-md-3 col-lg-3">
                                            <div class="card shadow p-1 mb-2 bg-white rounded">
                                                <div class="card-header" style="border-bottom:solid 0.5px #31708f;">
                                                    <h4>Jenis</h4>
                                                </div>
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
                                                        <div class="col-sm-12 mt-3">
                                                            <label>Kategori Barang</label>
                                                            <select name="kodekategoribarang<?php echo $no ?>" id="kodekategoribarang<?php echo $no ?>" class="form-control">
                                                                <option value="">-Pilih-</option>
                                                                <?php
                                                                $sql_kb = "SELECT KodeKategoriBarang, NamaKategoriBarang 
                                                                            FROM ms_kategori_barang WHERE KodeDokumen ='23' AND NamaKategoriBarang !='' ";
                                                                $data_kb = $sqlLib->select($sql_kb);
                                                                foreach ($data_kb as $row_kb) {
                                                                ?><option value="<?php echo $row_kb['KodeKategoriBarang'] ?>"><?php echo $row_kb['NamaKategoriBarang'] ?></option><?php
                                                                                                                                                                                }
                                                                                                                                                                                    ?>

                                                            </select>
                                                        </div>
                                                        <div class="col-sm-12 mt-3">
                                                            <label>Asal Negara</label>
                                                            <input type="text" name="namanegara<?php echo $no ?>" id="namanegara<?php echo $no ?>" class="form-control">
                                                            <input type="hidden" name="kodenegara<?php echo $no ?>" id="kodenegara<?php echo $no ?>" class="form-control">
                                                        </div>
                                                        <div class="col-sm-12 mt-3">
                                                            <label>Cif Rupiah</label>
                                                            <input type="text" name="cifrp<?php echo $no ?>" id="cifrp<?php echo $no ?>" class="form-control">
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6 col-lg-6">
                                            <div class="card shadow p-1 mb-2 bg-white rounded">
                                                <div class="card-header" style="border-bottom:solid 0.5px #31708f;">
                                                    <h4>Harga</h4>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group row">
                                                        <div class="col-sm-6">
                                                            <label>Asuransi</label>
                                                            <input type="text" name="asuransi<?php echo $no ?>" id="asuransi<?php echo $no ?>" class="form-control">
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <label>Cif</label>
                                                            <input type="text" name="cif<?php echo $no ?>" id="cif<?php echo $no ?>" class="form-control">
                                                        </div>
                                                        <div class="col-sm-6 mt-3">
                                                            <label>Diskon</label>
                                                            <input type="text" name="diskon<?php echo $no ?>" id="diskon<?php echo $no ?>" class="form-control">
                                                        </div>
                                                        <div class="col-sm-6 mt-3">
                                                            <label>Fob</label>
                                                            <input type="text" name="fob<?php echo $no ?>" id="fob<?php echo $no ?>" class="form-control">
                                                        </div>
                                                        <div class="col-sm-6 mt-3">
                                                            <label>Freight</label>
                                                            <input type="text" name="freight<?php echo $no ?>" id="freight<?php echo $no ?>" class="form-control">
                                                        </div>
                                                        <div class="col-sm-6 mt-3">
                                                            <label>Harga Ekspor</label>
                                                            <input type="text" name="hargaekspor<?php echo $no ?>" id="hargaekspor<?php echo $no ?>" class="form-control">
                                                        </div>
                                                        <div class="col-sm-6 mt-3">
                                                            <label>Harga Satuan</label>
                                                            <input type="text" name="harga<?php echo $no ?>" id="harga<?php echo $no ?>" readonly class="form-control" value="<?php echo $row['Harga'] ?>">
                                                        </div>
                                                        <div class="col-sm-6 mt-3">
                                                            <label>Perhitungan</label>
                                                            <select class="form-control" name="kodeperhitungan<?php echo $no ?>" required="required">
                                                                <option value="">-Pilih-</option>
                                                                <option value="0" <?php if ($_POST['kodeperhitungan'] == "0") {
                                                                                        echo "selected";
                                                                                    } ?>>Harga Pemasukan</option>
                                                                <option value="1" <?php if ($_POST['kodeperhitungan'] == "1") {
                                                                                        echo "selected";
                                                                                    } ?>>Harga Pengeluaran</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-6 mt-3">
                                                            <label>Nilai Barang</label>
                                                            <input type="text" name="nilaibarang<?php echo $no ?>" id="nilaibarang<?php echo $no ?>" class="form-control">
                                                        </div>
                                                        <div class="col-sm-6 mt-3">
                                                            <label>Biaya Tambahan</label>
                                                            <input type="text" name="nilaitambah<?php echo $no ?>" id="nilaitambah<?php echo $no ?>" class="form-control">
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-3 col-lg-3">
                                            <div class="card shadow p-1 mb-2 bg-white rounded">
                                                <div class="card-header" style="border-bottom:solid 0.5px #31708f;">
                                                    <h4>Jumlah dan Berat</h4>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group row">
                                                        <div class="col-sm-12">
                                                            <label>Isi Kemasan</label>
                                                            <input type="text" class="form-control" name="isikemasan<?php echo $no ?>" id="isikemasan<?php echo $no ?>">
                                                        </div>
                                                        <div class="col-sm-12 mt-3">
                                                            <label>Jumlah Kemasan</label>
                                                            <input type="text" class="form-control" name="jumlahkemasan<?php echo $no ?>" id="jumlahkemasan<?php echo $no ?>">
                                                        </div>
                                                        <div class="col-sm-12 mt-3">
                                                            <label>Jumlah Satuan</label>
                                                            <input type="text" class="form-control" name="jumlahsatuan<?php echo $no ?>" id="jumlahsatuan<?php echo $no ?>">
                                                            <input type="hidden" name="sisa<?php echo $no ?>" id="sisa<?php echo $no ?>" value="<?php echo $sisa ?>">
                                                        </div>
                                                        <div class="col-sm-12 mt-3">
                                                            <label>Jenis Kemasan</label>
                                                            <input type="text" class="form-control" name="jeniskemasan<?php echo $no ?>" id="jeniskemasan<?php echo $no ?>">
                                                            <input type="hidden" class="form-control" name="kodejeniskemasan<?php echo $no ?>" id="kodejeniskemasan<?php echo $no ?>">
                                                        </div>
                                                        <div class="col-sm-12 mt-3">
                                                            <label>Satuan Barang</label>
                                                            <input type="text" class="form-control" name="namasatuanbarang<?php echo $no ?>" id="namasatuanbarang<?php echo $no ?>">
                                                            <input type="hidden" class="form-control" name="kodesatuanbarang<?php echo $no ?>" id="kodesatuanbarang<?php echo $no ?>">
                                                        </div>
                                                        <div class="col-sm-12 mt-3">
                                                            <label>Berat Bersih (Netto)</label>
                                                            <input type="text" class="form-control" name="netto<?php echo $no ?>" id="netto<?php echo $no ?>">
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--
                                        <div class="col-12 col-md-3 col-lg-3">
                                            <div class="card shadow p-1 mb-2 bg-white rounded">
                                                <div class="card-header" style="border-bottom:solid 0.5px #31708f;">
                                                    <h4>Tarif</h4>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group row">
                                                        <div class="col-sm-12">
                                                            <label>JenisTarif</label>
                                                            <select class="form-control" name="kodejenitarif" required="required">
                                                                <option value="">-Pilih-</option>
                                                                <option value="1" <?php if ($_POST['kodejenitarif'] == "1") {
                                                                                        echo "selected";
                                                                                    } ?>>Advalorum</option>
                                                                <option value="2" <?php if ($_POST['kodejenitarif'] == "2") {
                                                                                        echo "selected";
                                                                                    } ?>>Spesifik</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-12 mt-3">
                                                            <label>Fasilitas Tarif</label>
                                                            <select name="kodefasilitastarif" class="form-control" required="required">
                                                                <option value="">-Pilih-</option>
                                                                <option value="1" <?php if ($_POST['kodefasilitastarif'] == "1") {
                                                                                        echo "selected";
                                                                                    } ?>>(1) Dibayar</option>
                                                                <option value="2" <?php if ($_POST['kodefasilitastarif'] == "2") {
                                                                                        echo "selected";
                                                                                    } ?>>(2) Ditanggung Pemerintah</option>
                                                                <option value="3" <?php if ($_POST['kodefasilitastarif'] == "3") {
                                                                                        echo "selected";
                                                                                    } ?>>(3) Ditangguhkan</option>
                                                                <option value="4" <?php if ($_POST['kodefasilitastarif'] == "4") {
                                                                                        echo "selected";
                                                                                    } ?>>(4) Berkala</option>
                                                                <option value="5" <?php if ($_POST['kodefasilitastarif'] == "5") {
                                                                                        echo "selected";
                                                                                    } ?>>(5) Dibebaskan</option>
                                                                <option value="6" <?php if ($_POST['kodefasilitastarif'] == "6") {
                                                                                        echo "selected";
                                                                                    } ?>>(6) Tidak dipungut</option>
                                                                <option value="7" <?php if ($_POST['kodefasilitastarif'] == "7") {
                                                                                        echo "selected";
                                                                                    } ?>>(7) Sudah dilunasi</option>
                                                                <option value="8" <?php if ($_POST['kodefasilitastarif'] == "8") {
                                                                                        echo "selected";
                                                                                    } ?>>(8) Dijaminkan</option>
                                                                <option value="9" <?php if ($_POST['kodefasilitastarif'] == "9") {
                                                                                        echo "selected";
                                                                                    } ?>>(9) Ditunda</option>

                                                            </select>
                                                            <input type="hidden" class="form-control" name="kodejenispungutan<?php echo $no ?>" id="kodeJenisPungutan<?php echo $no ?>" Value="BM">
                                                        </div>
                                                        <div class="col-sm-12 mt-3">
                                                            <label>Nilai Bayar</label>
                                                            <input type="text" class="form-control" name="nilaibayar<?php echo $no ?>" id="nilaibayar<?php echo $no ?>">
                                                        </div>
                                                        <div class="col-sm-12 mt-3">
                                                            <label>Nilai Fasilitas</label>
                                                            <input type="text" class="form-control" name="nilaifasilitas<?php echo $no ?>" id="nilaifasilitas<?php echo $no ?>">
                                                        </div>
                                                        <div class="col-sm-12 mt-3">
                                                            <label>Nilai Sudah Dilunasi</label>
                                                            <input type="text" class="form-control" name="nilaifasilitas<?php echo $no ?>" id="nilaifasilitas<?php echo $no ?>">
                                                        </div>
                                                        <div class="col-sm-12 mt-3">
                                                            <label>Tarif</label>
                                                            <input type="text" class="form-control" name="tarif<?php echo $no ?>" id="tarif<?php echo $no ?>">
                                                        </div>
                                                        <div class="col-sm-12 mt-3">
                                                            <label>Tarif Fasilitas</label>
                                                            <input type="text" class="form-control" name="tariffasilitas<?php echo $no ?>" id="tariffasilitas<?php echo $no ?>">
                                                        </div>


                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        -->
                                        <input type="hidden" name="total<?php echo $no ?>" id="total<?php echo $no ?>">
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card shadow p-1 mb-2 bg-white rounded">
                                                <div class="card-header" style="border-bottom:solid 0.5px #31708f;">
                                                    <h4>Tarif Barang</h4>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group row">
                                                        <table class="table table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">No</th>
                                                                    <th scope="col">Pungutan</th>
                                                                    <th scope="col">Jenis Tarif</th>
                                                                    <th scope="col">Tarif</th>
                                                                    <th scope="col">Fasilitas</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>1</td>
                                                                    <td>
                                                                        <select name="kodepungutan1" class="form-control">
                                                                            <option value="BM">BM</option>
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <select name="kodetarif1" class="form-control">
                                                                            <option value="">-Pilih-</option>
                                                                            <option value="1">Advalorum</option>
                                                                            <option value="2">Spesifik</option>
                                                                        </select>
                                                                    </td>

                                                                    <td><input type="text" class="form-control" name="tarif1"></td>
                                                                    <td>
                                                                        <select name="kodefasilitastarif1" class="form-control">
                                                                            <option value="">-Pilih-</option>
                                                                            <option value="1">(1) Dibayar</option>
                                                                            <option value="2">(2) Ditanggung Pemerintah</option>
                                                                            <option value="3">(3) Ditangguhkan</option>
                                                                            <option value="4">(4) Berkala</option>
                                                                            <option value="5">(5) Dibebaskan</option>
                                                                            <option value="6">(6) Tidak dipungut</option>
                                                                            <option value="7">(7) Sudah dilunasi</option>
                                                                            <option value="8">(8) Dijaminkan</option>
                                                                            <option value="9">(9) Ditunda</option>
                                                                        </select>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>2</td>
                                                                    <td>
                                                                        <select name="kodepungutan2" class="form-control">
                                                                            <option value="PPH">PPH</option>
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <select name="kodetarif2" class="form-control">
                                                                            <option value="">-Pilih-</option>
                                                                            <option value="1">Advalorum</option>
                                                                            <option value="2">Spesifik</option>
                                                                        </select>
                                                                    </td>

                                                                    <td><input type="text" class="form-control" name="tarif2"></td>
                                                                    <td>
                                                                        <select name="kodefasilitastarif2" class="form-control">
                                                                            <option value="">-Pilih-</option>
                                                                            <option value="1">(1) Dibayar</option>
                                                                            <option value="2">(2) Ditanggung Pemerintah</option>
                                                                            <option value="3">(3) Ditangguhkan</option>
                                                                            <option value="4">(4) Berkala</option>
                                                                            <option value="5">(5) Dibebaskan</option>
                                                                            <option value="6">(6) Tidak dipungut</option>
                                                                            <option value="7">(7) Sudah dilunasi</option>
                                                                            <option value="8">(8) Dijaminkan</option>
                                                                            <option value="9">(9) Ditunda</option>
                                                                        </select>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>3</td>
                                                                    <td>
                                                                        <select name="kodepungutan3" class="form-control">
                                                                            <option value="PPN">PPN</option>
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <select name="kodetarif3" class="form-control">
                                                                            <option value="">-Pilih-</option>
                                                                            <option value="1">Advalorum</option>
                                                                            <option value="2">Spesifik</option>
                                                                        </select>
                                                                    </td>

                                                                    <td><input type="text" class="form-control" name="tarif3"></td>
                                                                    <td>
                                                                        <select name="kodefasilitastarif3" class="form-control">
                                                                            <option value="">-Pilih-</option>
                                                                            <option value="1">(1) Dibayar</option>
                                                                            <option value="2">(2) Ditanggung Pemerintah</option>
                                                                            <option value="3">(3) Ditangguhkan</option>
                                                                            <option value="4">(4) Berkala</option>
                                                                            <option value="5">(5) Dibebaskan</option>
                                                                            <option value="6">(6) Tidak dipungut</option>
                                                                            <option value="7">(7) Sudah dilunasi</option>
                                                                            <option value="8">(8) Dijaminkan</option>
                                                                            <option value="9">(9) Ditunda</option>
                                                                        </select>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>4</td>
                                                                    <td>
                                                                        <select name="kodepungutan4" class="form-control">
                                                                            <option value="PPNBM">PPNBM</option>
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <select name="kodetarif4" class="form-control">
                                                                            <option value="">-Pilih-</option>
                                                                            <option value="1">Advalorum</option>
                                                                            <option value="2">Spesifik</option>
                                                                        </select>
                                                                    </td>

                                                                    <td><input type="text" class="form-control" name="tarif4"></td>
                                                                    <td>
                                                                        <select name="kodefasilitastarif4" class="form-control">
                                                                            <option value="">-Pilih-</option>
                                                                            <option value="1">(1) Dibayar</option>
                                                                            <option value="2">(2) Ditanggung Pemerintah</option>
                                                                            <option value="3">(3) Ditangguhkan</option>
                                                                            <option value="4">(4) Berkala</option>
                                                                            <option value="5">(5) Dibebaskan</option>
                                                                            <option value="6">(6) Tidak dipungut</option>
                                                                            <option value="7">(7) Sudah dilunasi</option>
                                                                            <option value="8">(8) Dijaminkan</option>
                                                                            <option value="9">(9) Ditunda</option>
                                                                        </select>
                                                                    </td>
                                                                </tr>

                                                                <input type="hidden" name="jmltarif" id="jmltarif" value="4">
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
                    </div>
                <?php
                    $no++;
                } ?>

                <input type="hidden" name="jmlrow" id="jmlrow" value="<?php echo $no - 1; ?>">
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