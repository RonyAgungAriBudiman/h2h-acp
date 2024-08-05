<?php
function kirimbc40($username, $access_token, $nomor_aju, $sqlLib)
{
  $curl = curl_init();

  //https://apisdev-gw.beacukai.go.id/openapi/document  
  //https://nlehub-dev.kemenkeu.go.id/openapi/document

  $sql_header = "SELECT a.Bruto,a.KodeJenisTpb, a.HargaPenyerahan, a.JabatanPernyataan, a.KodeDokumen, a.KodeKantor, a.KodeTujuanPengiriman,
                          a.KotaPernyataan, a.NamaPernyataan, a.Netto, a.NomorAju, '1' as Seri, a.TanggalPernyataan
                FROM BC_HEADER a WHERE a.NomorAju ='" . $nomor_aju . "' ";
  $data_header=$sqlLib->select($sql_header);
  $noaju = str_replace("-","",$data_header[0]['NomorAju']);

  $sql_entitas_3 = "SELECT NomorAju,Seri,KodeEntitas,KodeJenisIdentitas,NomorIdentitas,NamaEntitas,AlamatEntitas,NibEntitas,KodeJenisApi,KodeStatus,NomorIjinEntitas,TanggalIjinEntitas,KodeNegara,
                              NiperEntitas
                    FROM BC_ENTITAS WHERE NomorAju ='" . $nomor_aju . "' AND KodeEntitas ='3' ";
  $data_entitas_3=$sqlLib->select($sql_entitas_3);
  $data_entitas_3[0]['AlamatEntitas'] = trim(preg_replace('/\s+/', ' ', $data_entitas_3[0]['AlamatEntitas']));

  $sql_entitas_7 = "SELECT NomorAju,Seri,KodeEntitas,KodeJenisIdentitas,NomorIdentitas,NamaEntitas,AlamatEntitas,NibEntitas,KodeJenisApi,KodeStatus,NomorIjinEntitas,TanggalIjinEntitas,KodeNegara,
                              NiperEntitas
                    FROM BC_ENTITAS WHERE NomorAju ='" . $nomor_aju . "' AND KodeEntitas ='7' ";
  $data_entitas_7=$sqlLib->select($sql_entitas_7);
  $data_entitas_7[0]['AlamatEntitas'] = trim(preg_replace('/\s+/', ' ', $data_entitas_7[0]['AlamatEntitas']));

  $sql_entitas_9 = "SELECT NomorAju,Seri,KodeEntitas,KodeJenisIdentitas,NomorIdentitas,NamaEntitas,AlamatEntitas,NibEntitas,KodeJenisApi,KodeStatus,NomorIjinEntitas,TanggalIjinEntitas,KodeNegara,
                              NiperEntitas
                    FROM BC_ENTITAS WHERE NomorAju ='" . $nomor_aju . "' AND KodeEntitas ='9' ";
  $data_entitas_9=$sqlLib->select($sql_entitas_9);
  $data_entitas_9[0]['AlamatEntitas'] = trim(preg_replace('/\s+/', ' ', $data_entitas_9[0]['AlamatEntitas']));

  $sql_pengangkut = "SELECT NomorAju,Seri,KodeCaraAngkut,NamaPengangkut,NomorPengangkut,KodeBendera,CallSign,FlagAngkutPlb
                      FROM BC_PENGANGKUT WHERE NomorAju ='" . $nomor_aju . "' ";
  $data_pengangkut=$sqlLib->select($sql_pengangkut);

  $sql_kemasan = "SELECT NomorAju, Seri, KodeKemasan, JumlahKemasan, Merek
                    FROM BC_KEMASAN WHERE NomorAju ='" . $nomor_aju . "' ";
  $data_kemasan=$sqlLib->select($sql_kemasan);

  $sql_pungutan = "SELECT NomorAju, KodeFasilitasTarif, KodeJenisPungutan, NilaiPungutan, NpwpBilling
                    FROM BC_PUNGUTAN WHERE NomorAju ='" . $nomor_aju . "' ";
  $data_pungutan=$sqlLib->select($sql_pungutan);

  // Membuat array kosong untuk barang
  $array_dokumen = array();  
  $sql_dokumen = "SELECT NomorAju,Seri,KodeDokumen,NomorDokumen,TanggalDokumen,KodeFasilitas,KodeIjin
                    FROM BC_DOKUMEN WHERE NomorAju ='" . $nomor_aju . "' ";
  $data_dokumen=$sqlLib->select($sql_dokumen);
  foreach ($data_dokumen as $dok) 
  {
    // Membuat array untuk setiap barang
      $dokumen_array = array(
          "kodeDokumen" => $dok['KodeDokumen'],
          "nomorDokumen" => $dok['NomorDokumen'],
          "tanggalDokumen" => $dok['TanggalDokumen'],
          "seriDokumen" => $dok['Seri']
          );
      // Menambahkan data barang ke dalam array barang utama
      $array_dokumen[] = $dokumen_array;
  }
  // Mengonversi array menjadi JSON
  $json_data = json_encode($array_dokumen, JSON_PRETTY_PRINT);

  // Membuat array kosong untuk barang
  $array_barang = array();
  $sql_barang ="SELECT a.HargaPenyerahan, a.JumlahKemasan, a.JumlahSatuan, a.KodeBarang, '40' as KodeDokumen, a.KodeKemasan, a.KodeSatuan, a.Netto, a.NilaiBarang, 
                  a.Hs, a.SeriBarang, a.SpesifikasiLain, a.Tipe, a.Ukuran, a.Uraian, a.Volume, b.KodeTarif, b.KodeFasilitas, b.Tarif, b.NilaiFasilitas,
                  b.NilaiBayar, b.NilaiSudahDilunasi, b.TarifFasilitas
          FROM BC_BARANG a
          LEFT JOIN BC_BARANG_TARIF b on b.NomorAju = a.NomorAju AND b.SeriBarang = a.SeriBarang
          WHERE a.NomorAju = '" . $nomor_aju . "'";
  $data_barang = $sqlLib->select($sql_barang); 
  foreach ($data_barang as $barang) {
    $barang['Uraian'] = preg_replace("/[^a-zA-Z0-9()\s]/", "", $barang['Uraian']);
    // Membuat array untuk setiap barang
    $barang_array = array(
      "hargaPenyerahan" => $barang['HargaPenyerahan'],
      "jumlahKemasan" => $barang['JumlahKemasan'],
      "jumlahSatuan" => $barang['JumlahSatuan'],
      "kodeBarang" => $barang['KodeBarang'],
      "kodeDokumen" => $barang['KodeDokumen'],
      "kodeJenisKemasan" => $barang['KodeKemasan'],
      "kodeSatuanBarang" => $barang['KodeSatuan'],
      "netto" => $barang['Netto'],
      "nilaiBarang" => $barang['NilaiBarang'],
      "posTarif" => $barang['Hs'],
      "seriBarang" => intval($barang['SeriBarang']),
      "spesifikasiLain" => $barang['SpesifikasiLain'],
      "tipe" => $barang['Tipe'],
      "ukuran" => $barang['Ukuran'],
      "uraian" => $barang['Uraian'],
      "volume" => $barang['Volume'],
      "barangTarif" => array(
        array(
          "kodeJenisTarif" => $barang['KodeTarif'],
          "jumlahSatuan" => $barang['JumlahSatuan'],
          "kodeFasilitasTarif" => $barang['KodeFasilitas'],
          "kodeSatuanBarang" => $barang['KodeSatuan'],
          "nilaiBayar" => $barang['NilaiBayar'],
          "nilaiFasilitas" => $barang['NilaiFasilitas'],
          "nilaiSudahDilunasi" => $barang['NilaiSudahDilunasi'],
          "seriBarang" => intval($barang['seriBarang']),
          "tarif" => $barang['Tarif'],
          "tarifFasilitas" => intval($barang['TarifFasilitas']),
          "kodeJenisPungutan" => "PPN"
        )
      )
    );

    // Menambahkan data barang ke dalam array barang utama
    $array_barang[] = $barang_array;
  }
  // Mengonversi array menjadi JSON
  $json_barang = json_encode($array_barang, JSON_PRETTY_PRINT);

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
      
      "asalData":"S",
      "bruto":' . $data_header[0]['Bruto'] . ',
      "kodeJenisTpb":"' . $data_header[0]['KodeJenisTpb'] . '",
      "hargaPenyerahan":' . $data_header[0]['HargaPenyerahan'] . ',
      "jabatanTtd":"' . $data_header[0]['JabatanPernyataan'] . '",
      "kodeDokumen":"' . $data_header[0]['KodeDokumen'] . '",
      "kodeKantor":"' . $data_header[0]['KodeKantor'] . '",
      "kodeTujuanPengiriman":"' . $data_header[0]['KodeTujuanPengiriman'] . '",
      "kotaTtd":"' . $data_header[0]['KotaPernyataan'] . '",
      "namaTtd":"' . $data_header[0]['NamaPernyataan'] . '",
      "netto":' . $data_header[0]['Netto'] . ',
      "nomorAju":"' . $noaju . '",
      "seri":' . $data_header[0]['Seri'] . ',
      "tanggalAju": "' . $data_header[0]['TanggalPernyataan'] . '",
      "tanggalTtd": "' . $data_header[0]['TanggalPernyataan'] . '",

      
      "pengangkut": [
                      {
                          "namaPengangkut":"' . $data_pengangkut[0]['NamaPengangkut'] . '",
                          "nomorPengangkut": "' . $data_pengangkut[0]['NomorPengangkut'] . '",
                          "seriPengangkut": 1
                       }
                    ],

      "kemasan": [
                  {
                      "jumlahKemasan": ' . $data_kemasan[0]['JumlahKemasan'] . ',
                      "kodeJenisKemasan": "' . $data_kemasan[0]['KodeKemasan'] . '",
                      "merkKemasan":"' . $data_kemasan[0]['Merek'] . '",
                      "seriKemasan": 1
                   }
                  ],

      "pungutan": [
                    {
                        "kodeFasilitasTarif": "' . $data_pungutan[0]['KodeFasilitasTarif'] . '",
                        "kodeJenisPungutan": "' . $data_pungutan[0]['KodeJenisPungutan'] . '",
                        "nilaiPungutan": ' . $data_pungutan[0]['NilaiPungutan'] . '
                      }
                  ],
      "entitas": [
                  {
                    
                    "alamatEntitas":"' . $data_entitas_3[0]['AlamatEntitas'] . '",
                    "kodeEntitas": "3",
                    "kodeJenisIdentitas": "' . $data_entitas_3[0]['KodeJenisIdentitas'] . '",
                    "namaEntitas": "' . $data_entitas_3[0]['NamaEntitas'] . '",
                    "nibEntitas": "' . $data_entitas_3[0]['NibEntitas'] . '",
                    "nomorIdentitas": "' . $data_entitas_3[0]['NomorIdentitas'] . '",
                    "nomorIjinEntitas": "' . $data_entitas_3[0]['NomorIjinEntitas'] . '",
                    "seriEntitas": 1,
                    "tanggalIjinEntitas": "' . $data_entitas_3[0]['TanggalIjinEntitas'] . '"
                 },
                 {
                    "alamatEntitas":"' . $data_entitas_7[0]['AlamatEntitas'] . '",
                    "kodeEntitas": "7",
                    "kodeJenisApi":"' . $data_entitas_7[0]['KodeJenisApi'] . '",
                    "kodeJenisIdentitas": "' . $data_entitas_7[0]['KodeJenisIdentitas'] . '",
                    "kodeStatus": "' . $data_entitas_7[0]['KodeStatus'] . '",
                    "namaEntitas": "' . $data_entitas_7[0]['NamaEntitas'] . '",
                    "nibEntitas": "' . $data_entitas_7[0]['NibEntitas'] . '",
                    "nomorIdentitas": "' . $data_entitas_7[0]['NomorIdentitas'] . '",
                    "seriEntitas": 2
                 },
                 {
                    "alamatEntitas":"'.$data_entitas_9[0]['AlamatEntitas'].'",
                    "kodeEntitas":"9",
                    "kodeJenisApi":"' . $data_entitas_9[0]['KodeJenisApi'] . '",
                    "kodeJenisIdentitas":"' . $data_entitas_9[0]['KodeJenisIdentitas'] . '",
                    "kodeStatus":"' . $data_entitas_9[0]['KodeStatus'] . '",
                    "namaEntitas":"' . $data_entitas_9[0]['NamaEntitas'] . '",
                    "nibEntitas":"' . $data_entitas_9[0]['NibEntitas'] . '",
                    "nomorIdentitas":"' . $data_entitas_9[0]['NomorIdentitas'] . '",
                    "seriEntitas":3

                    
                 }

                ],            
      "barang": '.json_encode($array_barang, JSON_PRETTY_PRINT).',
      "dokumen": '.json_encode($array_dokumen, JSON_PRETTY_PRINT).'            



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

  // echo $alamatpengirim;

     echo "<pre>";
       print_r($result);
    echo "</pre>";


  //$status = $result->status;
  return $result;
}
