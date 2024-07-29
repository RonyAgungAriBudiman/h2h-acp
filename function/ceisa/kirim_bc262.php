<?php
function kirimbc262($username, $access_token, $nomor_aju, $sqlLib)
{
  $curl = curl_init();

  //https://apisdev-gw.beacukai.go.id/openapi/document  
  //https://nlehub-dev.kemenkeu.go.id/openapi/document

  $sql_header = "SELECT Asuransi, BiayaTambahan, BiayaPengurang, Bruto, Cif,  Freight, HargaPenyerahan,
                        JabatanPernyataan, KodeDokumen, KodeKantor,  KodeTujuanPemasukan, KodeValuta, KotaPernyataan,  NamaPernyataan,
                        Ndpbm, Netto, NilaiBarang, NomorAju, TanggalPernyataan,NoPo,Urut,RecUser
                FROM BC_HEADER WHERE NomorAju ='" . $nomor_aju . "' ";
  $data_header = $sqlLib->select($sql_header);
  $noaju = str_replace("-", "", $data_header[0]['NomorAju']);

  $sql_entitas_3 = "SELECT NomorAju,Seri,KodeEntitas,KodeJenisIdentitas,NomorIdentitas,NamaEntitas,AlamatEntitas,NibEntitas,
                            KodeJenisApi,KodeStatus,NomorIjinEntitas,TanggalIjinEntitas,KodeNegara,NiperEntitas
                    FROM BC_ENTITAS WHERE NomorAju ='" . $nomor_aju . "' AND KodeEntitas ='3' ";
  $data_entitas_3 = $sqlLib->select($sql_entitas_3);

  $sql_entitas_7 = "SELECT NomorAju,Seri,KodeEntitas,KodeJenisIdentitas,NomorIdentitas,NamaEntitas,AlamatEntitas,NibEntitas,
                            KodeJenisApi,KodeStatus,NomorIjinEntitas,TanggalIjinEntitas,KodeNegara,NiperEntitas
                    FROM BC_ENTITAS WHERE NomorAju ='" . $nomor_aju . "' AND KodeEntitas ='7' ";
  $data_entitas_7 = $sqlLib->select($sql_entitas_7);

  $sql_entitas_9 = "SELECT NomorAju,Seri,KodeEntitas,KodeJenisIdentitas,NomorIdentitas,NamaEntitas,AlamatEntitas,NibEntitas,
                            KodeJenisApi,KodeStatus,NomorIjinEntitas,TanggalIjinEntitas,KodeNegara,NiperEntitas
                    FROM BC_ENTITAS WHERE NomorAju ='" . $nomor_aju . "' AND KodeEntitas ='9' ";
  $data_entitas_9 = $sqlLib->select($sql_entitas_9);

  $sql_kemasan = "SELECT NomorAju, Seri, KodeKemasan, JumlahKemasan, Merek
                    FROM BC_KEMASAN WHERE NomorAju ='" . $nomor_aju . "' ";
  $data_kemasan = $sqlLib->select($sql_kemasan);

  $sql_kontainer = "SELECT NomorAju, Seri, NomorKontiner, KodeUkuranKontainer, KodeJenisKontainer, KodeTipeKontainer
                    FROM BC_KONTAINER WHERE NomorAju ='" . $nomor_aju . "' ";
  $data_kontainer = $sqlLib->select($sql_kontainer);

  $sql_pengangkut = "SELECT NomorAju,Seri,KodeCaraAngkut,NamaPengangkut,NomorPengangkut,KodeBendera,CallSign,FlagAngkutPlb
                      FROM BC_PENGANGKUT WHERE NomorAju ='" . $nomor_aju . "' ";
  $data_pengangkut = $sqlLib->select($sql_pengangkut);

  $sql_pungutan = "SELECT NomorAju, KodeFasilitasTarif, KodeJenisPungutan, NilaiPungutan, NpwpBilling
                      FROM BC_PUNGUTAN WHERE NomorAju ='" . $nomor_aju . "' ";
  $data_pungutan = $sqlLib->select($sql_pungutan);

  $sql_jaminan = "SELECT NomorAju,KodeKantor,KodeJaminan, NomorJaminan, TanggalJaminan, NilaiJaminan, Penjamin, TanggalJatuhTempo, NomorBpj, TanggalBpj,RecUser,RecDate
                      FROM BC_JAMINAN WHERE NomorAju ='" . $nomor_aju . "' ";
  $data_jaminan = $sqlLib->select($sql_jaminan);
  $idjaminan = date("YmdHis", strtotime($data_jaminan[0]['RecDate']));

  // Membuat array kosong untuk dokumen
  $array_dokumen = array();
  $sql = "SELECT a.KodeDokumen, a.TanggalDokumen, a.NomorDokumen, a.Seri 
          FROM BC_DOKUMEN a
          WHERE a.NomorAju = '" . $nomor_aju . "' 
          ORDER BY CASE WHEN a.KodeDokumen ='380' THEN '1' ELSE '2' END ASC ";
  $data_dok = $sqlLib->select($sql);
  foreach ($data_dok as $dok) {
    // Membuat array untuk setiap dokumen
    $dokumen_array = array(
      "kodeDokumen" => $dok['KodeDokumen'],
      "nomorDokumen" => $dok['NomorDokumen'],
      "tanggalDokumen" => $dok['TanggalDokumen'],
      "seriDokumen" => intval($dok['Seri'])
    );
    // Menambahkan data dokumen ke dalam array dokumen utama
    $array_dokumen[] = $dokumen_array;
  }
  // Mengonversi array menjadi JSON
  $json_data_dokumen = json_encode($array_dokumen, JSON_PRETTY_PRINT);

  // Membuat array kosong untuk barang
  $array_barang = array();
  $sql = "SELECT  a.NomorAju, a.SeriBarang, a.Hs, a.KodeBarang, a.Uraian, a.Merek, a.Tipe, a.Ukuran, a.SpesifikasiLain, a.KodeSatuan, a.JumlahSatuan, a.KodeKemasan, a.JumlahKemasan, a.KodeDokumenAsal,
                  a.KodeKantorAsal, a.NomorDaftarAsal, a.TanggalDaftarAsal, a.NomorAjuAsal, a.SeriBarangAsal, a.Netto, a.Bruto, a.Volume, a.SaldoAwal, a.SaldoAkhir, a.JumlahRealisasi, a.Cif, a.CifRupiah, 
                  a.Ndpbm, a.Fob, a.Asuransi, a.Freight, a.NilaiTambah, a.Diskon, a.HargaPenyerahan, a.HargaPerolehan, a.HargaSatuan, a.HargaEkspor, a.HargaPatokan, a.NilaiBarang, a.NilaiJasa, 
                  a.NilaiDanaSawit, a.NilaiDevisa, a.PersentaseImpor, a.KodeAsalBarang, a.KodeDaerahAsal, a.KodeGunaBarang, a.KodeJenisNilai, a.JatuhTempoRoyalti, a.KodeKategoriBarang,
                  a.KodeKondisiBarang, a.KodeNegaraAsal, a.KodePerhitungan, a.PernyataanLartas, a.Flag4Tahun, a.SeriIzin, a.TahunPembuatan, a.KapasitasSilinder, a.KodeBkc, a.KodeKomoditiBkc,
                  a.KodeSubKomoditiBkc, a.FlagTis, a.IsiPerKemasan, a.JumlahDilekatkan, a.JumlahPitaCukai, a.HjeCukai, a.TarifCukai,'262' as KodeDokumen, isnull(c.SeriDokumen,'0') as SeriDokumen,
                  isnull(c.SeriIzin,'0') as SeriIzin  
          FROM BC_BARANG a
          LEFT JOIN BC_BARANG_DOKUMEN c on c.NomorAju = a.NomorAju AND a.SeriBarang = a.SeriBarang
          WHERE a.NomorAju = '" . $nomor_aju . "' AND a.SeriBarang ='1'  Order By a.SeriBarang Asc ";
  $data_barang = $sqlLib->select($sql);
  foreach ($data_barang as $barang) {
    $uraian = preg_replace("/[^a-zA-Z0-9()\s]/", "", $barang['Uraian']);

    //bahan baku
    $array_bahan_baku = array();
    $sql_bahan_baku = "SELECT a.NomorAju, a.SeriBarang, a.SeriBahanBaku, a.KodeAsalBahanBaku, a.Hs, a.KodeBarang, a.Uraian, a.Merek, a.Tipe, a.Ukuran, a.SpesifikasiLain, a.KodeSatuan, a.JumlahSatuan, 
                                a.KodeKemasan, a.JumlahKemasan, a.KodeDokumenAsal, a.KodeKantorAsal, a.NomorDaftarAsal, a.TanggalDaftarAsal, a.NomorAjuAsal, a.SeriBarangAsal, a.Netto, a.Bruto, a.Volume, 
                                a.Cif, a.CifRupiah, a.Ndpbm, a.HargaPenyerahan, a.HargaPerolehan, a.NilaiJasa, a.SeriIzin, a.Valuta, a.KodeBkc, a.KodeKomoditiBkc, a.KodeSubKomoditiBkc, a.FlagTis, 
                                a.IsiPerKemasan, a.JumlahDilekatkan, a.JumlahPitaCukai, a.HjeCukai, a.TarifCukai
                    FROM BC_BAHAN_BAKU a 
                    WHERE a.NomorAju = '" . $nomor_aju . "' AND a.SeriBarang = '" . $barang['SeriBarang'] . "' 
                    ORDER BY a.SeriBahanBaku Asc ";
    $data_bahan_baku = $sqlLib->select($sql_bahan_baku);
    foreach ($data_bahan_baku as $bahan_baku) {

      //bahan baku tarif
      $array_bb_tarif = array();
      $sql_bb_tarif = "SELECT a.NomorAju, a.SeriBarang, a.SeriBahanBaku, a.KodeAsalBahanBaku, a.KodePungutan, a.KodeTarif, a.Tarif, a.KodeFasilitas, a.TarifFasilitas, a.NilaiBayar, a.NilaiFasilitas,
                                a.NilaiSudahDilunasi, a.KodeSatuan, a.JumlahSatuan, a.FlagBmtSementara, a.KodeKomoditiCukai, a.KodeSubKomoditiCukai, a.FlagTis, a.FlagPelekatan, a.KodeKemasan,
                                a.JumlahKemasan
                        FROM BC_BAHAN_BAKU_TARIF a 
                        WHERE a.NomorAju = '" . $nomor_aju . "' AND a.SeriBarang = '" . $barang['SeriBarang'] . "' AND a.SeriBahanBaku = '" . $bahan_baku['SeriBahanBaku'] . "' 
                        ORDER BY a.SeriBahanBaku Asc ";
      $data_bb_tarif = $sqlLib->select($sql_bb_tarif);
      foreach ($data_bb_tarif as $bb_tarif) {
        $bb_tarif_array = array(

          "jumlahKemasan" => $bb_tarif['JumlahKemasan'],
          "jumlahSatuan" => $bb_tarif['JumlahSatuan'],
          "kodeAsalBahanBaku" => $bb_tarif['KodeAsalBahanBaku'],
          "kodeFasilitasTarif" => $bb_tarif['KodeFasilitas'],
          "kodeJenisPungutan" => $bb_tarif['KodePungutan'],
          "kodeJenisTarif" => $bb_tarif['KodeTarif'],
          "kodeSatuanBarang" => $bb_tarif['KodeSatuan'],
          "nilaiBayar" => $bb_tarif['NilaiBayar'],
          "nilaiFasilitas" => $bb_tarif['NilaiFasilitas'],
          "nilaiSudahDilunasi" => $bb_tarif['NilaiSudahDilunasi'],
          "seriBahanBaku" => $bb_tarif['SeriBahanBaku'],
          "tarif" => $bb_tarif['Tarif'],
          "tarifFasilitas" => $bb_tarif['TarifFasilitas']
        );

        // Menambahkan data barang ke dalam array barang utama
        $array_bb_tarif[] = $bb_tarif_array;
      }

      $uraian_bb = preg_replace("/[^a-zA-Z0-9()\s]/", "", $bahan_baku['Uraian']);
      $bahan_baku_array = array(

        "cif" => $bahan_baku['Cif'],
        "cifRupiah" => $bahan_baku['CifRupiah'],
        "hargaPenyerahan" => $bahan_baku['HargaPenyerahan'],
        "hargaPerolehan" => $bahan_baku['HargaPerolehan'],
        "jumlahSatuan" => $bahan_baku['JumlahSatuan'],
        "kodeSatuanBarang" => $bahan_baku['KodeSatuan'],
        "kodeAsalBahanBaku" => $bahan_baku['KodeAsalBahanBaku'],
        "kodeBarang" => $bahan_baku['KodeBarang'],
        "kodeDokAsal" => $bahan_baku['KodeDokumenAsal'],
        "kodeDokumen" => $barang['KodeDokumen'],
        "kodeKantor" => $bahan_baku['KodeKantorAsal'],
        "merkBarang" => $bahan_baku['Merek'],
        "ndpbm" => $bahan_baku['Ndpbm'],
        "netto" => $bahan_baku['Netto'],
        "nilaiJasa" => $bahan_baku['NilaiJasa'],
        "nomorAjuDokAsal" => $bahan_baku['NomorAjuAsal'],
        "nomorDaftarDokAsal" => $bahan_baku['NomorDaftarAsal'],
        "posTarif" => $bahan_baku['Hs'],
        "seriBahanBaku"  => $bahan_baku['SeriBahanBaku'],
        "seriBarang" => $bahan_baku['SeriBarang'],
        "seriBarangDokAsal" => $bahan_baku['SeriBarangAsal'],
        "seriIjin" => $bahan_baku['SeriIzin'],
        "spesifikasiLainBarang"  => $bahan_baku['SpesifikasiLain'],
        "tanggalDaftarDokAsal" => $bahan_baku['TanggalDaftarAsal'],
        "tipeBarang" => $bahan_baku['Tipe'],
        "ukuranBarang"  => $bahan_baku['Ukuran'],
        "uraianBarang" => $uraian_bb,
        "bahanBakuTarif" => $array_bb_tarif
      );

      // Menambahkan data barang ke dalam array barang utama
      $array_bahan_baku[] = $bahan_baku_array;
    }

    // Membuat array untuk setiap barang
    $barang_array = array(
      "cif" => $barang['Cif'],
      "cifRupiah" => $barang['CifRupiah'],
      "hargaEkspor" => $barang['HargaEkspor'],
      "isiPerKemasan" => $barang['IsiPerKemasan'],
      "hargaPenyerahan" => $barang['HargaPenyerahan'],
      "hargaPerolehan" => $barang['HargaPerolehan'],
      "jumlahKemasan" => intval($barang['JumlahKemasan']),
      "jumlahSatuan" => floatval(number_format($barang['JumlahSatuan'], 2)),
      "kodeAsalBahanBaku" => "1",
      "kodeAsalBarang" => $barang['KodeAsalBarang'],
      "kodeBarang" => $barang['KodeBarang'],
      "kodeDokumen" => $barang['KodeDokumen'],
      "kodeJenisKemasan" => $barang['KodeKemasan'],
      "kodeNegaraAsal" => $barang['KodeNegaraAsal'],
      "kodeSatuanBarang" => $barang['KodeSatuan'],
      "merk" => $barang['Merek'],
      "ndpbm" => $barang['Ndpbm'],
      "netto" => $barang['Netto'],
      "nilaiBarang" => $barang['NilaiBarang'],
      "nilaiJasa" => $barang['NilaiJasa'],
      "posTarif" => $barang['Hs'],
      "seriBarang" => intval($barang['SeriBarang']),
      "spesifikasiLain" => $barang['SpesifikasiLain'],
      "tipe" => $barang['Tipe'],
      "uangMuka" => 0,
      "ukuran" => $barang['Ukuran'],
      "uraian" => $uraian,
      "bahanBaku" =>  $array_bahan_baku

    );

    // Menambahkan data barang ke dalam array barang utama
    $array_barang[] = $barang_array;
  }
  // Mengonversi array menjadi JSON
  $json_data_barang = json_encode($array_barang, JSON_PRETTY_PRINT);

  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://apisdev-gw.beacukai.go.id/openapi/document', //'http://www.example.com/'
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => '{ 
        "asalData": "S",
        "asuransi":' . number_format($data_header[0]['Asuransi'], 2) . ',
        "biayaTambahan":' . $data_header[0]['BiayaTambahan'] . ',
        "biayaPengurang":' . $data_header[0]['BiayaPengurang'] . ',
        "bruto": ' . $data_header[0]['Bruto'] . ',
        "cif":' . $data_header[0]['Cif'] . ',
        "disclaimer":"1",
        "freight":' . $data_header[0]['Freight'] . ',
        "hargaPenyerahan": ' . $data_header[0]['HargaPenyerahan'] . ',  
        "jabatanTtd": "' . $data_header[0]['JabatanPernyataan'] . '", 
        "kodeDokumen":"262",
        "kodeKantor": "' . $data_header[0]['KodeKantor'] . '",
        "kodeTujuanPemasukan":"' . $data_header[0]['KodeTujuanPemasukan'] . '",
        "kodeValuta":"' . $data_header[0]['KodeValuta'] . '",
        "kotaTtd": "' . $data_header[0]['KotaPernyataan'] . '",
        "namaTtd": "' . $data_header[0]['NamaPernyataan'] . '",
        "ndpbm":' . $data_header[0]['Ndpbm'] . ',
        "netto": ' . $data_header[0]['Netto'] . ',
        "nik":"-",
        "nilaiBarang": ' . $data_header[0]['NilaiBarang'] . ',
        "nomorAju": "' . $noaju . '",
        "seri":1,
        "tanggalAju": "' . $data_header[0]['TanggalPernyataan'] . '",
        "tanggalTtd": "' . $data_header[0]['TanggalPernyataan'] . '",     
        "entitas":[
                    {
                      "alamatEntitas":"' . $data_entitas_3[0]['Alamat'] . '",
                      "kodeEntitas": "3",
                      "kodeJenisApi": "' . $data_entitas_3[0]['KodeJenisApi'] . '",
                      "kodeStatus":"' . $data_entitas_3[0]['KodeStatus'] . '",
                      "kodeJenisIdentitas": "' . $data_entitas_3[0]['KodeJenisIdentitas'] . '",
                      "namaEntitas": "' . $data_entitas_3[0]['NamaEntitas'] . '",
                      "nibEntitas": "' . $data_entitas_3[0]['NibEntitas'] . '",
                      "nomorIdentitas": "' . $data_entitas_3[0]['NomorIdentitas'] . '",
                      "nomorIjinEntitas": "' . $data_entitas_3[0]['NomorIjinEntitas'] . '",
                      "seriEntitas": 1,
                      "tanggalIjinEntitas": "' . $data_entitas_3[0]['TanggalIjinEntitas'] . '"
                    },
                    {
                      "alamatEntitas":"' . $data_entitas_7[0]['Alamat'] . '",
                      "kodeEntitas": "7",
                      "kodeJenisIdentitas": "' . $data_entitas_7[0]['KodeJenisIdentitas'] . '",
                      "kodeStatus": "' . $data_entitas_7[0]['KodeStatus'] . '",
                      "namaEntitas": "' . $data_entitas_7[0]['NamaEntitas'] . '",
                      "nomorIdentitas": "' . $data_entitas_7[0]['NomorIdentitas'] . '",
                      "nomorIjinEntitas": "' . $data_entitas_7[0]['NomorIjinEntitas'] . '",
                      "seriEntitas": 2
                    },
                    {
                      "alamatEntitas":"' . $data_entitas_9[0]['Alamat'] . '",
                      "kodeEntitas":"9",
                      "kodeJenisIdentitas":"' . $data_entitas_9[0]['KodeJenisIdentitas'] . '",
                      "kodeStatus":"' . $data_entitas_9[0]['KodeStatus'] . '",
                      "namaEntitas":"' . $data_entitas_9[0]['NamaEntitas'] . '",
                      "nomorIdentitas":"' . $data_entitas_9[0]['NomorIdentitas'] . '",
                      "seriEntitas":3
                    }
                  ],
        "kemasan":[
                    {
                      "jumlahKemasan": ' . $data_kemasan[0]['JumlahKemasan'] . ',
                      "kodeJenisKemasan": "' . $data_kemasan[0]['KodeKemasan'] . '",
                      "merkKemasan":"' . $data_kemasan[0]['Merek'] . '",
                      "seriKemasan": ' . $data_kemasan[0]['Seri'] . '
                      
                    }
                  ],
        "kontainer":[
                      {
                        "kodeJenisKontainer": "' . $data_kontainer[0]['KodeJenisKontainer'] . '",
                        "kodeTipeKontainer": "' . $data_kontainer[0]['KodeTipeKontainer'] . '",
                        "kodeUkuranKontainer": "' . $data_kontainer[0]['KodeUkuranKontainer'] . '",
                        "nomorKontainer": "' . $data_kontainer[0]['NomorKontiner'] . '"                        
                      }
                    ],
        "pengangkut": [
                        {
                          "idPengangkut": "' . $data_pengangkut[0]['Seri'] . '",
                          "kodeCaraAngkut": "' . $data_pengangkut[0]['KodeCaraAngkut'] . '",
                          "seriPengangkut": ' . $data_pengangkut[0]['Seri'] . ' 
                        }
                    ],
        "dokumen":' . json_encode($array_dokumen, JSON_PRETTY_PRINT) . ',


        "jaminan":[
                    {
                    "idJaminan":"' . $idjaminan . '",
                    "kodeJenisJaminan":"' . $data_jaminan[0]['KodeJaminan'] . '",
                    "nilaiJaminan":' . $data_jaminan[0]['NilaiJaminan'] . ',
                    "nomorBpj":"' . $data_jaminan[0]['NomorBpj'] . '",
                    "nomorJaminan":"' . $data_jaminan[0]['NomorJaminan'] . '",
                    "penjamin":"' . $data_jaminan[0]['Penjamin'] . '",
                    "tanggalBpj":"' . $data_jaminan[0]['TanggalBpj'] . '",
                    "tanggalJaminan":"' . $data_jaminan[0]['TanggalJaminan'] . '",
                    "tanggalJatuhTempo":"' . $data_jaminan[0]['TanggalJatuhTempo'] . '"
                    }                 
        ] ,    
        "pungutan" : [
                        {
                          "idPungutan":"' . $data_pungutan[0]['Seri'] . '",
                          "kodeFasilitasTarif":"' . $data_pungutan[0]['KodeFasilitasTarif'] . '",
                          "kodeJenisPungutan":"' . $data_pungutan[0]['KodeJenisPungutan'] . '",
                          "nilaiPungutan":' . $data_pungutan[0]['NilaiPungutan'] . '
                        }
                    ],
        "uangMuka":0,
        "vd":0 ,
        "barang":' . json_encode($array_barang, JSON_PRETTY_PRINT) . '      


  }',
    CURLOPT_HTTPHEADER => array(
      'Content-Type: application/json',
      'Authorization: Bearer ' . $access_token
    ),
  ));

  $response = curl_exec($curl);
  //echo $response;

  curl_close($curl);
  $result = json_decode($response);
  $status = $result->status;


  echo "<pre>";
  print_r($result);
  echo "</pre>";
  return $result;
}
