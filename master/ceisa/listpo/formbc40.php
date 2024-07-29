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
                      WHERE KodeDokumen = '40' AND  YEAR(TanggalPernyataan) = '" . date("Y") . "' ";
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
                <h4>Pengirim Barang / Penjual Barang Kena Pajak / Penerima Jasa Kena Pajak</h4>
              </div>
              <div class="card-body">
                <div class="form-group row">
                  <div class="col-sm-12">
                    <label>NPWP</label>
                    <input type="text" name="nomoridentitaspengirim" required="required" class="form-control" value="<?php echo $_POST["nomoridentitaspengirim"] ?>">
                  </div>
                  <div class="col-sm-12 mt-3">
                    <label>Jenis Identitas</label>
                    <select class="form-control" name="kodejenisidentitaspengirim" required="required">
                      <option value="">-Pilih-</option>
                      <option value="0" <?php if ($_POST['kodejenisidentitaspengirim'] == "0") {
                                          echo "selected";
                                        } ?>>NPWP 12 Digit</option>
                      <option value="1" <?php if ($_POST['kodejenisidentitaspengirim'] == "1") {
                                          echo "selected";
                                        } ?>>NPWP 10 Digit</option>
                      <option value="2" <?php if ($_POST['kodejenisidentitaspengirim'] == "2") {
                                          echo "selected";
                                        } ?>>Paspor</option>
                      <option value="3" <?php if ($_POST['kodejenisidentitaspengirim'] == "3") {
                                          echo "selected";
                                        } ?>>KTP</option>
                      <option value="4" <?php if ($_POST['kodejenisidentitaspengirim'] == "4") {
                                          echo "selected";
                                        } ?>>Lainnya</option>
                      <option value="5" <?php if ($_POST['kodejenisidentitaspengirim'] == "5") {
                                          echo "selected";
                                        } ?>>NPWP 15 Digit</option>
                    </select>
                  </div>
                  <div class="col-sm-6 mt-3">
                    <label>Status</label>
                    <select class="form-control" name="kodestatuspengirim" required="required">
                      <option value="">-Pilih-</option>
                      <option value="1" <?php if ($_POST['kodestatuspengirim'] == "1") {
                                          echo "selected";
                                        } ?>>Koperasi</option>
                      <option value="2" <?php if ($_POST['kodestatuspengirim'] == "2") {
                                          echo "selected";
                                        } ?>>PMDN (Migas)</option>
                      <option value="3" <?php if ($_POST['kodestatuspengirim'] == "3") {
                                          echo "selected";
                                        } ?>>PMDN (Non Migas)</option>
                      <option value="4" <?php if ($_POST['kodestatuspengirim'] == "4") {
                                          echo "selected";
                                        } ?>>PMA (Migas)</option>
                      <option value="5" <?php if ($_POST['kodestatuspengirim'] == "5") {
                                          echo "selected";
                                        } ?>>PMA (Non Migas)</option>
                      <option value="8" <?php if ($_POST['kodestatuspengirim'] == "8") {
                                          echo "selected";
                                        } ?>>Perorangan</option>
                    </select>
                  </div>
                  <div class="col-sm-6 mt-3">
                    <label>NIB</label>
                    <input type="text" name="nibentitaspengirim" class="form-control" value="<?php echo $_POST["nibentitaspengirim"] ?>">
                  </div>
                  <div class="col-sm-12 mt-3">
                    <label>Nama</label>
                    <input type="text" name="namaentitaspengirim" class="form-control" readonly="readonly" value="<?php echo $_POST["namaentitaspengirim"] ?>">
                  </div>
                  <div class="col-sm-12 mt-3">
                    <label>Alamat</label>
                    <textarea class="form-control" style="height: 100px;" id="alamatentitaspengirim" name="alamatentitaspengirim"><?php echo $_POST["alamatentitaspengirim"] ?></textarea>
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
                  </div>
                  <div class="col-sm-12 mt-3">
                    <label>Nama</label>
                    <input type="text" name="namaentitaspemilik" class="form-control" readonly="readonly" value="<?php echo $_POST["namaentitaspemilik"] ?>">
                  </div>
                  <div class="col-sm-12 mt-3">
                    <label>Alamat</label>
                    <textarea class="form-control" style="height: 100px;" id="alamatentitaspemilik" name="alamatentitaspemilik"><?php echo $_POST["alamatentitaspemilik"] ?></textarea>
                  </div>
                  <div class="col-sm-12 mt-3">
                    <label>Nomor Izin TPB</label>
                    <input type="text" name="nomorijinentitaspemilik" class="form-control" readonly="readonly" value="<?php echo $_POST["nomorijinentitaspemilik"] ?>">
                  </div>

                  <div class="col-sm-12 mt-3">
                    <label>NIB</label>
                    <input type="text" name="nibentitaspemilik" class="form-control" readonly="readonly" value="<?php echo $_POST["nibentitaspemilik"] ?>">
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
                  <div class="col-sm-12 mt-3">
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
        <h4>Kemasan</h4>
      </div>
      <div class="accordion-body collapse" id="panel-body-5" data-parent="#accordion">
        <div class="row">
          <div class="col-12">
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
          <div class="col-12">
            <div class="card shadow p-1 mb-2 bg-white rounded">
              <div class="card-header" style="border-bottom:solid 0.5px #31708f;">
                <h4>Harga</h4>
              </div>
              <div class="card-body">
                <div class="form-group row">
                  <div class="col-sm-12">
                    <label>Nilai Jasa</label>
                    <input type="text" name="nilaijasa" class="form-control" required="required" value="<?php echo $_POST["nilaijasa"] ?>">
                  </div>
                  <div class="col-sm-12 mt-3">
                    <label>Nilai Uang Muka</label>
                    <input type="text" name="uangmuka" class="form-control" required="required" value="<?php echo $_POST["uangmuka"] ?>">
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
                    <table class="table table-responsive">
                      <thead>
                        <tr>
                          <th>Nama Barang</th>
                          <th>Sisa PO</th>
                          <th>Satuan</th>
                          <th>HS Number</th>
                          <th>Bruto</th>
                          <th>Netto</th>
                          <th>Volume</th>
                          <th>Qty Aju</th>
                          <th>Total Harga</th>
                          <th>Kemasan</th>
                          <th>Jumlah Kemasan</th>
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
                          <script>
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
                            $(document).ready(function() {
                              var ac_config = {
                                source: "json/kemasan.php",
                                select: function(event, ui) {
                                  $("#kodekemasan<?php echo $no ?>").val(ui.item.id);
                                  $("#jeniskemasan<?php echo $no ?>").val(ui.item.jeniskemasan);
                                },
                                focus: function(event, ui) {
                                  $("#kodekemasan<?php echo $no ?>").val(ui.item.id);
                                  $("#jeniskemasan<?php echo $no ?>").val(ui.item.jeniskemasan);
                                },
                                minLength: 1
                              };
                              $("#jeniskemasan<?php echo $no ?>").autocomplete(ac_config);
                            });
                          </script>
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
                            <td><input type="text" name="namasatuanbarang<?php echo $no ?>" id="namasatuanbarang<?php echo $no ?>" size="10" <?php if ($sisa < 1) {
                                                                                                                                                echo "disabled";
                                                                                                                                              } ?>>
                              <input type="hidden" name="kodesatuanbarang<?php echo $no ?>" id="kodesatuanbarang<?php echo $no ?>" size="10" <?php if ($sisa < 1) {
                                                                                                                                                echo "disabled";
                                                                                                                                              } ?>>
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
                            <td><input type="text" name="jeniskemasan<?php echo $no ?>" id="jeniskemasan<?php echo $no ?>" size="10" <?php if ($sisa < 1) {
                                                                                                                                        echo "disabled";
                                                                                                                                      } ?>>
                              <input type="hidden" name="kodekemasan<?php echo $no ?>" id="kodekemasan<?php echo $no ?>" size="10" <?php if ($sisa < 1) {
                                                                                                                                      echo "disabled";
                                                                                                                                    } ?>>
                            </td>
                            <td><input type="text" name="jumlahkemasan<?php echo $no ?>" id="jumlahkemasan<?php echo $no ?>" size="10" <?php if ($sisa < 1) {
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

                </div>
              </div>
            </div>
          </div>
        </div>
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