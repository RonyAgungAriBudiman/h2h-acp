<?php
function kirimbc30($username, $access_token, $nomor_aju, $sqlLib)
{
  $curl = curl_init();

  //https://apisdev-gw.beacukai.go.id/openapi/document  
  //https://nlehub-dev.kemenkeu.go.id/openapi/document

  $sql_header = "SELECT NomorAju,KodeDokumen,Asuransi,Bruto,FlagCurah,FlagMigas,
                        Fob,Freight,JabatanPernyataan,KodeAsuransi,KodeCaraDagang,KodeCaraBayar,KodeJenisEkspor,
                        KodeKantor,KodeKantorEkspor,KodeKantorMuat,KodeKategoriEkspor,KodeLokasi,
                        KodeKantorPeriksa, KodeCaraDagang, KodeNegaraTujuan, TanggalEkspor, KodeIncoterm,
                        KodePelabuhanBongkar,KodePelabuhanEkspor,KodePelabuhanMuat,KodePelabuhanTujuan,KodeValuta,
                        KotaPernyataan,NamaPernyataan,Ndpbm,Netto,NilaiMaklon,TanggalPeriksa,KodeTps,
                        TanggalPernyataan
                FROM BC_HEADER WHERE NomorAju ='" . $nomor_aju . "' ";
  $data_header = $sqlLib->select($sql_header);
  $noaju = str_replace("-", "", $data_header[0]['NomorAju']);

  $sql_entitas_2 = "SELECT NomorAju,Seri,KodeEntitas,KodeJenisIdentitas,NomorIdentitas,NamaEntitas,AlamatEntitas,NibEntitas,
                            KodeJenisApi,KodeStatus,NomorIjinEntitas,TanggalIjinEntitas,KodeNegara,NiperEntitas
                    FROM BC_ENTITAS WHERE NomorAju ='" . $nomor_aju . "' AND KodeEntitas ='2' ";
  $data_entitas_2 = $sqlLib->select($sql_entitas_2);

  $sql_entitas_7 = "SELECT NomorAju,Seri,KodeEntitas,KodeJenisIdentitas,NomorIdentitas,NamaEntitas,AlamatEntitas,NibEntitas,KodeJenisApi,KodeStatus,NomorIjinEntitas,TanggalIjinEntitas,KodeNegara,
                              NiperEntitas
                    FROM BC_ENTITAS WHERE NomorAju ='" . $nomor_aju . "' AND KodeEntitas ='7' ";
  $data_entitas_7 = $sqlLib->select($sql_entitas_7);

  $sql_entitas_8 = "SELECT NomorAju,Seri,KodeEntitas,KodeJenisIdentitas,NomorIdentitas,NamaEntitas,AlamatEntitas,NibEntitas,KodeJenisApi,KodeStatus,NomorIjinEntitas,TanggalIjinEntitas,KodeNegara,
                              NiperEntitas
                    FROM BC_ENTITAS WHERE NomorAju ='" . $nomor_aju . "' AND KodeEntitas ='8' ";
  $data_entitas_8 = $sqlLib->select($sql_entitas_8);

  $sql_entitas_6 = "SELECT NomorAju,Seri,KodeEntitas,KodeJenisIdentitas,NomorIdentitas,NamaEntitas,AlamatEntitas,NibEntitas,KodeJenisApi,KodeStatus,NomorIjinEntitas,TanggalIjinEntitas,KodeNegara,
                              NiperEntitas
                    FROM BC_ENTITAS WHERE NomorAju ='" . $nomor_aju . "' AND KodeEntitas ='6' ";
  $data_entitas_6 = $sqlLib->select($sql_entitas_6);

  $sql_kemasan = "SELECT NomorAju, Seri, KodeKemasan, JumlahKemasan, Merek
                    FROM BC_KEMASAN WHERE NomorAju ='" . $nomor_aju . "' ";
  $data_kemasan = $sqlLib->select($sql_kemasan);

  $sql_kontainer = "SELECT NomorAju, Seri, NomorKontiner, KodeUkuranKontainer, KodeJenisKontainer, KodeTipeKontainer
                    FROM BC_KONTAINER WHERE NomorAju ='" . $nomor_aju . "' ";
  $data_kontainer = $sqlLib->select($sql_kontainer);

  $sql_pengangkut = "SELECT NomorAju,Seri,KodeCaraAngkut,NamaPengangkut,NomorPengangkut,KodeBendera,CallSign,FlagAngkutPlb
                      FROM BC_PENGANGKUT WHERE NomorAju ='" . $nomor_aju . "' ";
  $data_pengangkut = $sqlLib->select($sql_pengangkut);

  $sql_bank = "SELECT NomorAju, Seri, Kode, Nama
                      FROM BC_BANK_DEVISA WHERE NomorAju ='" . $nomor_aju . "' ";
  $data_bank = $sqlLib->select($sql_bank);

  $sql_periksa = "SELECT NomorAju, Seri, KodeJenisBarang, KodeJenisGudang, NamaPic,
                          Alamat, NomorTelpPic, Lokasi, TanggalPkb, WaktuPeriksa
                      FROM BC_BARANG_SIAP_PERIKSA WHERE NomorAju ='" . $nomor_aju . "' ";
  $data_periksa = $sqlLib->select($sql_periksa);
  // String datetime yang akan diubah
  $datetime = $data_periksa[0]['WaktuPeriksa'];

  // Buat objek DateTime dari string datetime
  $date = new DateTime($datetime);

  // Set timezone ke UTC
  $date->setTimezone(new DateTimeZone('UTC'));

  // Format objek DateTime ke format ISO 8601 dengan milidetik dan 'Z'
  $waktuperiksa = $date->format("Y-m-d\TH:i:s.v\Z");


  // Membuat array kosong untuk dokumen
  $array_dokumen = array();
  $sql = "SELECT a.KodeDokumen, a.TanggalDokumen, a.NomorDokumen, a.Seri 
          FROM BC_DOKUMEN a
          WHERE a.NomorAju = '" . $nomor_aju . "' 
          ORDER BY CASE WHEN a.KodeDokumen ='380' THEN '1' 
                        WHEN a.KodeDokumen ='217' THEN '2' ELSE '3' END ASC ";
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

  //data barang  
  // Membuat array kosong untuk barang
  $array_barang = array();
  $sql = "SELECT  a.Fob,a.HargaEkspor, a.HargaSatuan, a.HargaPatokan, a.HargaPerolehan, a.JumlahKemasan,
                  a.JumlahSatuan, a.KodeAsalBarang, a.kodeBarang, a.kodeDaerahAsal, '30' as kodeDokumen,
                  a.KodeKemasan, a.kodeNegaraAsal, a.kodeSatuan, a.ndpbm, a.netto, a.nilaiBarang, a.Ukuran,
                  a.seriBarang, a.Merek, a.NilaiDanaSawit,a.Hs, a.SpesifikasiLain, a.Tipe, a.Uraian, a.volume,
                 '0' as KodeAsalBahanBaku,isnull(c.SeriDokumen,'0') as SeriDokumen 
          FROM BC_BARANG a
          LEFT JOIN BC_BARANG_DOKUMEN c on c.NomorAju = a.NomorAju AND a.SeriBarang = a.SeriBarang
          WHERE a.NomorAju = '" . $nomor_aju . "' Order By a.SeriBarang Asc ";
  $data_barang = $sqlLib->select($sql);
  foreach ($data_barang as $barang) {
    $uraian = preg_replace("/[^a-zA-Z0-9()\s]/", "", $barang['Uraian']);

    $array_tarif = array();
    // $sql_tarif = "SELECT a.SeriBarang, a.KodeTarif, a.KodeFasilitas, a.NilaiBayar, a.NilaiFasilitas, a.NilaiSudahDilunasi, a.Tarif, 
    //                     a.TarifFasilitas, a.KodePungutan, a.Urut
    //                 FROM BC_BARANG_TARIF a 
    //                 WHERE a.NomorAju = '" . $nomor_aju . "' AND a.SeriBarang = '" . $barang['SeriBarang'] . "' 
    //                 ORDER BY a.Urut Asc ";
    $sql_tarif = "SELECT a.JumlahSatuan FROM BC_BARANG a
                  WHERE a.NomorAju = '" . $nomor_aju . "' AND a.SeriBarang = '" . $barang['SeriBarang'] . "' ";
    $data_tarif = $sqlLib->select($sql_tarif);
    foreach ($data_tarif as $tarif) {
      // Membuat array untuk setiap barang
      $tarif_array = array(
        "jumlahSatuan" => floatval(number_format($barang['JumlahSatuan'], 2))
      );

      // Menambahkan data barang ke dalam array barang utama
      $array_tarif[] = $tarif_array;
    }

    // Membuat array untuk setiap barang
    $barang_array = array(
      "fob" => $barang['Fob'],
      "hargaPatokan" => $barang['HargaPatokan'],
      "hargaSatuan" => $barang['HargaSatuan'],
      "jumlahKemasan" => intval($barang['JumlahKemasan']),
      "kodeJenisKemasan" => $barang['KodeKemasan'],
      "merk" => $barang['Merek'],
      "nilaiDanaSawit" => $barang['NilaiDanaSawit'],
      "posTarif" => $barang['Hs'],
      "spesifikasiLain" => $barang['SpesifikasiLain'],
      "tipe" => $barang['Tipe'],
      "ukuran" => $barang['Ukuran'],
      "uraian" => $uraian,
      "barangTarif" => $array_tarif,
      "barangDokumen" => array(
        array(
          "seriDokumen" => trim($barang['SeriDokumen'])
        )
      )
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
        "bruto": ' . $data_header[0]['Bruto'] . ',
        "flagCurah":"' . $data_header[0]['FlagCurah'] . '",
        "flagMigas":"' . $data_header[0]['FlagMigas'] . '",
        "fob":' . $data_header[0]['Fob'] . ',
        "freight":' . $data_header[0]['Freight'] . ',
        "jabatanTtd": "' . $data_header[0]['JabatanPernyataan'] . '",
        "jumlahKontainer":1,
        "kodeAsuransi":"' . $data_header[0]['KodeAsuransi'] . '",
        "kodeCaraDagang":"' . $data_header[0]['KodeCaraDagang'] . '",
        "kodeCaraBayar":"' . $data_header[0]['KodeCaraBayar'] . '",
        "kodeDokumen":"30",
        "kodeJenisEkspor":"' . $data_header[0]['KodeJenisEkspor'] . '",
        "kodeKantor": "' . $data_header[0]['KodeKantor'] . '",
        "kodeKantorEkspor":"' . $data_header[0]['KodeKantorEkspor'] . '",
        "kodeKantorMuat":"' . $data_header[0]['KodeKantorMuat'] . '",
        "kodeKategoriEkspor":"' . $data_header[0]['KodeKategoriEkspor'] . '",
        "kodeLokasi":"' . $data_header[0]['KodeLokasi'] . '",
        "kodePelBongkar":"' . $data_header[0]['KodePelabuhanBongkar'] . '",
        "kodePelEkspor":"' . $data_header[0]['KodePelabuhanEkspor'] . '",
        "kodePelMuat":"' . $data_header[0]['KodePelabuhanMuat'] . '",
        "kodePelTujuan":"' . $data_header[0]['KodePelabuhanTujuan'] . '",
        "kodeValuta":"' . $data_header[0]['KodeValuta'] . '",
        "kotaTtd": "' . $data_header[0]['KotaPernyataan'] . '",
        "namaTtd": "' . $data_header[0]['NamaPernyataan'] . '",
        "ndpbm":' . $data_header[0]['Ndpbm'] . ',
        "netto": ' . $data_header[0]['Netto'] . ',
        "nilaiMaklon":' . $data_header[0]['NilaiMaklon'] . ',
        "nomorAju": "' . $noaju . '",
        "tanggalPeriksa":"' . $data_header[0]['TanggalPeriksa'] . '",
        "tanggalTtd": "' . $data_header[0]['TanggalPernyataan'] . '",

        "kodeKantorPeriksa":"' . $data_header[0]['KodeKantorPeriksa'] . '",
        "kodeTps":"' . $data_header[0]['KodeTps'] . '",
        "tanggalEkspor": "' . $data_header[0]['TanggalEkspor'] . '",
        "kodeNegaraTujuan": "' . $data_header[0]['KodeNegaraTujuan'] . '",
        "kodeIncoterm": "' . $data_header[0]['KodeIncoterm'] . '",

        "barang":' . json_encode($array_barang, JSON_PRETTY_PRINT) . ',
        "entitas":[
                    {
                      "alamatEntitas":"' . substr($data_entitas_2[0]['AlamatEntitas'], 0, 60) . '",
                      "kodeEntitas": "2",
                      "kodeJenisIdentitas": "' . $data_entitas_2[0]['KodeJenisIdentitas'] . '",
                      "namaEntitas": "' . $data_entitas_2[0]['NamaEntitas'] . '",
                      "nomorIdentitas": "' . $data_entitas_2[0]['NomorIdentitas'] . '",
                      "kodeStatus": "' . $data_entitas_2[0]['KodeStatus'] . '",
                      "seriEntitas": 2
                    },
                    {
                      "alamatEntitas":"' . substr($data_entitas_7[0]['AlamatEntitas'], 0, 60) . '",
                      "kodeEntitas": "7",
                      "kodeJenisIdentitas": "' . $data_entitas_7[0]['KodeJenisIdentitas'] . '",
                      "namaEntitas": "' . $data_entitas_7[0]['NamaEntitas'] . '",
                      "nomorIdentitas": "' . $data_entitas_7[0]['NomorIdentitas'] . '",
                      "kodeStatus": "' . $data_entitas_2[0]['KodeStatus'] . '",
                      "seriEntitas": 13
                    },
                    {
                      "alamatEntitas":"' . substr($data_entitas_8[0]['AlamatEntitas'], 0, 60) . '",
                      "kodeEntitas": "8",
                      "kodeNegara": "' . $data_entitas_8[0]['KodeNegara'] . '",
                      "namaEntitas": "' . $data_entitas_8[0]['NamaEntitas'] . '",
                      "seriEntitas": 8
                    },
                    {
                      "alamatEntitas":"' . substr($data_entitas_6[0]['AlamatEntitas'], 0, 60) . '",
                      "kodeEntitas": "6",
                      "kodeNegara": "' . $data_entitas_6[0]['KodeNegara'] . '",
                      "namaEntitas": "' . $data_entitas_6[0]['NamaEntitas'] . '",
                      "seriEntitas": 6
                    }
                  ],
        "kemasan":[
                    {
                      "jumlahKemasan":' . $data_kemasan[0]['JumlahKemasan'] . ',
                      "kodeJenisKemasan": "' . $data_kemasan[0]['KodeKemasan'] . '",
                      "seriKemasan": ' . $data_kemasan[0]['Seri'] . ',
                      "merkKemasan": "' . $data_kemasan[0]['Merek'] . '"                                           
                    }
                  ],
        "kontainer":[
                      {
                        "kodeJenisKontainer":"' . $data_kontainer[0]['KodeJenisKontainer'] . '",
                        "kodeTipeKontainer":"' . $data_kontainer[0]['KodeTipeKontainer'] . '",
                        "kodeUkuranKontainer":"' . $data_kontainer[0]['KodeUkuranKontainer'] . '",
                        "nomorKontainer":"' . $data_kontainer[0]['NomorKontiner'] . '",
                        "seriKontainer":' . $data_kontainer[0]['Seri'] . '
                      }
                    ],            
        "dokumen":' . json_encode($array_dokumen, JSON_PRETTY_PRINT) . ',
        "pengangkut": [
                        {
                          "kodeBendera":"' . $data_pengangkut[0]['KodeBendera'] . '",
                          "namaPengangkut":"' . $data_pengangkut[0]['NamaPengangkut'] . '",
                          "nomorPengangkut":"' . $data_pengangkut[0]['NomorPengangkut'] . '",
                          "kodeCaraAngkut":"' . $data_pengangkut[0]['KodeCaraAngkut'] . '",
                          "seriPengangkut":' . $data_pengangkut[0]['Seri'] . '
                        }
                    ],
        "bankDevisa":[
                        {
                          "kodeBank":"' . $data_bank[0]['Kode'] . '",
                          "namaBank":"' . $data_bank[0]['Nama'] . '",
                          "seriBank":' . $data_bank[0]['Seri'] . '
                        }
                    ] ,
        "kesiapanBarang":[
                          {
                            "kodeJenisGudang":"' . trim($data_periksa[0]['KodeJenisGudang']) . '",
                            "namaPic":"' . $data_periksa[0]['NamaPic'] . '",
                            "alamat":"' . $data_periksa[0]['Alamat'] . '",
                            "nomorTelpPic":"' . $data_periksa[0]['NomorTelpPic'] . '",
                            "lokasiSiapPeriksa":"' . $data_periksa[0]['Lokasi'] . '",
                            "tanggalPkb":"' . $data_periksa[0]['TanggalPkb'] . '",
                            "waktuSiapPeriksa":"'.$waktuperiksa.'"
                          }
                        ]     


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

 
  // echo "<pre>";
  // print_r($result);
  // echo "</pre>";
  // $status = $result->status;
 
  return $result;
}
