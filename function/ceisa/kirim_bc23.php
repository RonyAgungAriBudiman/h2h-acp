<?php
function kirimbc23($username, $access_token, $nomor_aju, $sqlLib)
{
  $curl = curl_init();

  //https://apisdev-gw.beacukai.go.id/openapi/document  
  //https://nlehub-dev.kemenkeu.go.id/openapi/document

  $sql_header = "SELECT a.NomorAju, a.Asuransi, a.Bruto, a.Cif, a.Fob, a.Freight, a.HargaPenyerahan, a.JabatanPernyataan,
                        a.KodeAsuransi,a.KodeDokumen,a.KodeIncoterm, a.KodeKantor, a.KodeKantorBongkar, a.KodePelabuhanBongkar, a.KodePelabuhanMuat,
                        a.KodePelabuhanTransit, a.KodeTps, a.KodeTujuanTpb, a.KodeTutupPu, a.KodeValuta, a.KotaPernyataan, a.NamaPernyataan, a.Ndpbm,
                        a.Netto, a.NilaiBarang,  a.NomorBc11, a.NomorPos, a.NomorSubPos, a.TanggalBc11, a.TanggalTiba,a.TanggalPernyataan,
                        a.BiayaTambahan, a.BiayaPengurang
                FROM BC_HEADER a WHERE a.NomorAju ='" . $nomor_aju . "' ";
  $data_header = $sqlLib->select($sql_header);
  $noaju = str_replace("-", "", $data_header[0]['NomorAju']);

  $sql_entitas_3 = "SELECT NomorAju,Seri,KodeEntitas,KodeJenisIdentitas,NomorIdentitas,NamaEntitas,AlamatEntitas,NibEntitas,KodeJenisApi,KodeStatus,NomorIjinEntitas,TanggalIjinEntitas,KodeNegara,
                              NiperEntitas
                    FROM BC_ENTITAS WHERE NomorAju ='" . $nomor_aju . "' AND KodeEntitas ='3' ";
  $data_entitas_3 = $sqlLib->select($sql_entitas_3);

  $sql_entitas_5 = "SELECT NomorAju,Seri,KodeEntitas,KodeJenisIdentitas,NomorIdentitas,NamaEntitas,AlamatEntitas,NibEntitas,KodeJenisApi,KodeStatus,NomorIjinEntitas,TanggalIjinEntitas,KodeNegara,
                              NiperEntitas
                    FROM BC_ENTITAS WHERE NomorAju ='" . $nomor_aju . "' AND KodeEntitas ='5' ";
  $data_entitas_5 = $sqlLib->select($sql_entitas_5);

  $sql_entitas_7 = "SELECT NomorAju,Seri,KodeEntitas,KodeJenisIdentitas,NomorIdentitas,NamaEntitas,AlamatEntitas,NibEntitas,KodeJenisApi,KodeStatus,NomorIjinEntitas,TanggalIjinEntitas,KodeNegara,
                              NiperEntitas
                    FROM BC_ENTITAS WHERE NomorAju ='" . $nomor_aju . "' AND KodeEntitas ='7' ";
  $data_entitas_7 = $sqlLib->select($sql_entitas_7);

  $sql_kemasan = "SELECT NomorAju, Seri, KodeKemasan, JumlahKemasan, Merek
                    FROM BC_KEMASAN WHERE NomorAju ='" . $nomor_aju . "' ";
  $data_kemasan = $sqlLib->select($sql_kemasan);

  $sql_pengangkut = "SELECT NomorAju,Seri,KodeCaraAngkut,NamaPengangkut,NomorPengangkut,KodeBendera,CallSign,FlagAngkutPlb
                      FROM BC_PENGANGKUT WHERE NomorAju ='" . $nomor_aju . "' ";
  $data_pengangkut = $sqlLib->select($sql_pengangkut);
  /*
  $sql_pungutan = "SELECT NomorAju, KodeFasilitasTarif, KodeJenisPungutan, NilaiPungutan, NpwpBilling
                    FROM BC_PUNGUTAN WHERE NomorAju ='" . $nomor_aju . "' ";
  $data_pungutan=$sqlLib->select($sql_pungutan);
*/

  // Membuat array kosong untuk barang
  $array_barang = array();
  $sql = "SELECT  a.Asuransi, a.Cif, a.Diskon, a.Fob, a.Freight, a.HargaEkspor, a.HargaPenyerahan, a.HargaSatuan,
                  a.IsiPerKemasan, a.JumlahKemasan, a.JumlahSatuan, a.KodeBarang, '23' as KodeDokumen, a.KodeKategoriBarang,
                  a.KodeKemasan, a.KodeNegaraAsal, a.KodePerhitungan, a.KodeSatuan, a.Merek, a.Netto, a.NilaiBarang, a.NilaiTambah, a.Hs, 
                  a.SeriBarang, a.SpesifikasiLain, a.Tipe, a.Ukuran, a.Uraian,
                  a.Ndpbm, a.CifRupiah, a.HargaPerolehan, '0' as KodeAsalBahanBaku,isnull(c.SeriDokumen,'0') as SeriDokumen 
          FROM BC_BARANG a
          LEFT JOIN BC_BARANG_DOKUMEN c on c.NomorAju = a.NomorAju AND a.SeriBarang = a.SeriBarang
          WHERE a.NomorAju = '" . $nomor_aju . "' Order By a.SeriBarang Asc ";
  $data_barang = $sqlLib->select($sql);
  foreach ($data_barang as $barang) {
    $uraian = preg_replace("/[^a-zA-Z0-9()\s]/", "", $barang['Uraian']);
    $array_tarif = array();
    $sql_tarif = "SELECT a.SeriBarang, a.KodeTarif, a.KodeFasilitas, a.NilaiBayar, a.NilaiFasilitas, a.NilaiSudahDilunasi, a.Tarif, 
                        a.TarifFasilitas, a.KodePungutan, a.Urut
                    FROM BC_BARANG_TARIF a 
                    WHERE a.NomorAju = '" . $nomor_aju . "' AND a.SeriBarang = '" . $barang['SeriBarang'] . "' 
                    ORDER BY a.Urut Asc ";
    $data_tarif = $sqlLib->select($sql_tarif);
    foreach ($data_tarif as $tarif) {
      // Membuat array untuk setiap barang
      $tarif_array = array(
        "kodeJenisTarif" => $tarif['KodeTarif'],
        "jumlahSatuan" => floatval(number_format($barang['JumlahSatuan'], 2)),
        "kodeFasilitasTarif" => $tarif['KodeFasilitas'],
        "kodeSatuanBarang" => $barang['KodeSatuan'],
        "nilaiBayar" => $tarif['NilaiBayar'],
        "nilaiFasilitas" => $tarif['NilaiFasilitas'],
        "nilaiSudahDilunasi" => $tarif['NilaiSudahDilunasi'],
        "seriBarang" => intval($tarif['seriBarang']),
        "tarif" => $tarif['Tarif'],
        "tarifFasilitas" => intval($tarif['TarifFasilitas']),
        "kodeJenisPungutan" => $tarif['KodePungutan']
      );

      // Menambahkan data barang ke dalam array barang utama
      $array_tarif[] = $tarif_array;
    }

    // Membuat array untuk setiap barang
    $barang_array = array(
      "asuransi" => $barang['Asuransi'],
      "cif" => $barang['Cif'],
      "diskon" => $barang['Diskon'],
      "fob" => $barang['Fob'],
      "freight" => $barang['Freight'],
      "hargaEkspor" => $barang['HargaEkspor'],
      "hargaPenyerahan" => $barang['HargaPenyerahan'],
      "hargaSatuan" => $barang['HargaSatuan'],
      "isiPerKemasan" => $barang['IsiPerKemasan'],
      "jumlahKemasan" => intval($barang['JumlahKemasan']),
      "jumlahSatuan" => floatval(number_format($barang['JumlahSatuan'], 2)),
      "kodeBarang" => $barang['KodeBarang'],
      "kodeDokumen" => $barang['KodeDokumen'],
      "kodeKategoriBarang" => $barang['KodeKategoriBarang'],
      "kodeJenisKemasan" => $barang['KodeKemasan'],
      "kodeNegaraAsal" => $barang['KodeNegaraAsal'],
      "kodePerhitungan" => $barang['KodePerhitungan'],
      "kodeSatuanBarang" => $barang['KodeSatuan'],
      "merk" => $barang['Merek'],
      "netto" => $barang['Netto'],
      "nilaiBarang" => $barang['NilaiBarang'],
      "nilaiTambah" => $barang['NilaiTambah'],
      "posTarif" => $barang['Hs'],
      "seriBarang" => intval($barang['SeriBarang']),
      "spesifikasiLain" => $barang['SpesifikasiLain'],
      "tipe" => $barang['Tipe'],
      "ukuran" => $barang['Ukuran'],
      "uraian" => $uraian,
      "ndpbm" => $barang['Ndpbm'],
      "cifRupiah" => $barang['CifRupiah'],
      "hargaPerolehan" => $barang['HargaPerolehan'],
      "kodeAsalBahanBaku" => $barang['KodeAsalBahanBaku'],
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

  // Membuat array kosong untuk dokumen
  $array_dokumen = array();
  $sql = "SELECT a.KodeDokumen, a.TanggalDokumen, a.NomorDokumen, a.Seri 
          FROM BC_DOKUMEN a
          WHERE a.NomorAju = '" . $nomor_aju . "' 
          ORDER BY CASE WHEN a.KodeDokumen ='380' THEN '1' 
                        WHEN a.KodeDokumen ='705' OR a.KodeDokumen ='740' THEN '2' ELSE '3' END ASC ";
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
      "kodeDokumen":"' . $data_header[0]['KodeDokumen'] . '",
      "bruto":' . $data_header[0]['Bruto'] . ',
      "cif":' . $data_header[0]['Cif'] . ',
      "fob":' . $data_header[0]['Fob'] . ',
      "freight":' . $data_header[0]['Freight'] . ',
      "hargaPenyerahan":' . $data_header[0]['HargaPenyerahan'] . ',
      "jabatanTtd":"' . $data_header[0]['JabatanPernyataan'] . '",
      "jumlahKontainer":1,
      "kodeAsuransi":"' . $data_header[0]['KodeAsuransi'] . '",
      "kodeIncoterm":"' . $data_header[0]['KodeIncoterm'] . '",
      "kodeKantor":"' . $data_header[0]['KodeKantor'] . '",
      "kodeKantorBongkar":"' . $data_header[0]['KodeKantorBongkar'] . '",
      "kodePelBongkar":"' . $data_header[0]['KodePelabuhanBongkar'] . '",
      "kodePelMuat":"' . $data_header[0]['KodePelabuhanMuat'] . '",
      "kodePelTransit":"' . $data_header[0]['KodePelabuhanTransit'] . '",
      "kodeTps":"' . $data_header[0]['KodeTps'] . '",
      "kodeTujuanTpb":"' . $data_header[0]['KodeTujuanTpb'] . '",
      "kodeTutupPu":"' . $data_header[0]['KodeTutupPu'] . '",
      "kodeValuta":"' . $data_header[0]['KodeValuta'] . '",
      "kotaTtd":"' . $data_header[0]['KotaPernyataan'] . '",
      "namaTtd":"' . $data_header[0]['NamaPernyataan'] . '",
      "ndpbm":' . $data_header[0]['Ndpbm'] . ',
      "netto":' . $data_header[0]['Netto'] . ',
      "nik":"",
      "nilaiBarang":' . $data_header[0]['NilaiBarang'] . ',
      "nomorAju":"' . $noaju . '",
      "nomorBc11":"' . $data_header[0]['NomorBc11'] . '",
      "posBc11":"' . $data_header[0]['NomorPos'] . '",
      "seri":1,
      "subposBc11":"' . $data_header[0]['NomorSubPos'] . '",
      "tanggalBc11":"' . $data_header[0]['TanggalBc11'] . '",
      "tanggalTiba":"' . $data_header[0]['TanggalTiba'] . '",
      "tanggalTtd":"' . $data_header[0]['TanggalPernyataan'] . '",
      "biayaTambahan":' . $data_header[0]['BiayaTambahan'] . ',
      "biayaPengurang":' . $data_header[0]['BiayaPengurang'] . ',
      "barang":' . json_encode($array_barang, JSON_PRETTY_PRINT) . ',
      "entitas":[
                    {
                      "alamatEntitas":"' . $data_entitas_3[0]['AlamatEntitas'] . '",
                      "kodeEntitas": "3",
                      "kodeJenisIdentitas": "' . $data_entitas_3[0]['KodeJenisIdentitas'] . '",
                      "namaEntitas": "' . $data_entitas_3[0]['NamaEntitas'] . '",
                      "nibEntitas": "' . $data_entitas_3[0]['NibEntitas'] . '",
                      "nomorIdentitas": "' . $data_entitas_3[0]['NomorIdentitas'] . '",
                      "nomorIjinEntitas": "' . $data_entitas_3[0]['NomorIjinEntitas'] . '",
                      "tanggalIjinEntitas": "' . $data_entitas_3[0]['TanggalIjinEntitas'] . '",
                      "seriEntitas": 1
                    },
                    {
                      "alamatEntitas":"' . substr($data_entitas_5[0]['AlamatEntitas'], 0, 60) . '",
                      "kodeEntitas":"5",
                      "kodeNegara":"' . $data_entitas_5[0]['KodeNegara'] . '",
                      "namaEntitas":"' . $data_entitas_5[0]['NamaEntitas'] . '",
                      "seriEntitas":2
                    },
                    {
                      
                      "alamatEntitas":"' . $data_entitas_7[0]['AlamatEntitas'] . '",
                      "kodeEntitas": "7",
                      "kodeJenisApi":"' . $data_entitas_7[0]['KodeJenisApi'] . '",
                      "kodeJenisIdentitas": "' . $data_entitas_7[0]['KodeJenisIdentitas'] . '",
                      "kodeStatus": "' . $data_entitas_7[0]['KodeStatus'] . '",
                      "namaEntitas": "' . $data_entitas_7[0]['NamaEntitas'] . '",
                      "nomorIdentitas": "' . $data_entitas_7[0]['NomorIdentitas'] . '",
                      "nomorIjinEntitas": "' . $data_entitas_7[0]['NomorIjinEntitas'] . '",
                      "tanggalIjinEntitas": "' . $data_entitas_7[0]['TanggalIjinEntitas'] . '",
                      "seriEntitas": 3
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
                   
      "dokumen":' . json_encode($array_dokumen, JSON_PRETTY_PRINT) . ',
      "pengangkut":[
                    {
                      "kodeBendera":"' . $data_pengangkut[0]['KodeBendera'] . '",
                      "namaPengangkut":"' . $data_pengangkut[0]['NamaPengangkut'] . '",
                      "nomorPengangkut":"' . $data_pengangkut[0]['NomorPengangkut'] . '",
                      "kodeCaraAngkut":"' . $data_pengangkut[0]['KodeCaraAngkut'] . '",
                      "seriPengangkut":' . $data_pengangkut[0]['Seri'] . '
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
  //$status = $result->status;
  return $result;
}
