<?php
function kirimbc40($username, $access_token, $nomor_aju, $sqlLib)
{
  $curl = curl_init();

  //https://apisdev-gw.beacukai.go.id/openapi/document  
  //https://nlehub-dev.kemenkeu.go.id/openapi/document

  $sql_aju = "SELECT a.NoPO,a.NomorAju,a.DokumenBC,a.Urut,a.AsalData,a.Asuransi,a.Bruto,a.KodeJenisTpb,a.HargaPenyerahan,a.JabatanTtd,a.KodeKantor,a.KodeTujuanPengiriman,a.KotaTtd,a.NamaTtd,
                  a.Netto,a.Seri,
                  a.TanggalAju,a.tanggalTtd,a.Volume,a.BiayaTambahan,a.BiayaPengurang,a.Vd,a.UangMuka,a.NilaiJasa,a.AlamatEntitasPengusaha,a.KodeEntitasPengusaha,
                  a.KodeJenisIdentitasPengusaha,
                  a.NamaEntitasPengusaha,a.NibEntitasPengusaha,a.NomorIdentitasPengusaha,a.NomorIjinEntitasPengusaha,a.SeriEntitasPengusaha,a.TanggalIjinEntitasPengusaha,
                  a.AlamatEntitasPemilik,
                  a.KodeEntitasPemilik,a.KodeJenisApiPemilik,a.KodeJenisIdentitasPemilik,a.NamaEntitasPemilik,a.NibEntitasPemilik,a.NomorIdentitasPemilik,a.KodeStatusPemilik, 
                  a.SeriEntitasPemilik,
                  a.AlamatEntitasPengirim,a.KodeEntitasPengirim,a.KodeJenisApiPengirim,a.KodeJenisIdentitasPengirim,a.NamaEntitasPengirim,a.NibEntitasPengirim,a.NomorIdentitasPengirim,
                  a.SeriEntitasPengirim,a.KodeStatusPengirim,
                  a.NamaPengangkut,a.NomorPengangkut,a.SeriPengangkut,a.JumlahKemasan,a.KodeJenisKemasan,a.SeriKemasan,a.KodeFasilitasTarif,a.KodeJenisPungutan,a.NilaiPungutan,a.Recuser,
                  a.Recdate,a.KirimCeisa
              FROM ms_dokumen_aju a 
              WHERE a.NomorAju ='" . $nomor_aju . "' ";
  $data_aju = $sqlLib->select($sql_aju);

  $alamatpengirim = substr($data_aju[0]['AlamatEntitasPengirim'],6);

  // Membuat array kosong untuk barang
  $array_barang = array();

  $sql = "SELECT b.Total as hargaPenyerahan, 0 as jumlahKemasan, b.Qty as jumlahSatuan, b.KdBarang as kodeBarang, a.DokumenBC as kodeDokumen,
            a.KodeJenisKemasan as kodeJenisKemasan, b.Satuan as kodeSatuanBarang, b.Netto as netto, b.Harga as nilaiBarang, b.HsNumber as posTarif,
            ROW_NUMBER() OVER (ORDER BY b.SeqItem) AS seriBarang, '-'as spesifikasiLain,'-'as tipe,'-'as ukuran, b.NamaBarang as uraian, 
            b.Volume as volume, a.kodeFasilitasTarif, a.TarifPPN, b.NilaiFasilitas as nilaiFasilitas, 
            CASE WHEN a.kodeFasilitasTarif !='1' THEN '100' ELSE '0' END AS tarifFasilitas
          FROM ms_dokumen_aju a
          LEFT JOIN ms_dokumen_aju_detail b on b.NomorAju = a.NomorAju
          WHERE a.NomorAju = '" . $nomor_aju . "'";
  $data_barang = $sqlLib->select($sql);
  foreach ($data_barang as $barang) {
    // Membuat array untuk setiap barang
    $barang_array = array(
      "hargaPenyerahan" => $barang['hargaPenyerahan'],
      "jumlahKemasan" => $barang['jumlahKemasan'],
      "jumlahSatuan" => $barang['jumlahSatuan'],
      "kodeBarang" => $barang['kodeBarang'],
      "kodeDokumen" => $barang['kodeDokumen'],
      "kodeJenisKemasan" => $barang['kodeJenisKemasan'],
      "kodeSatuanBarang" => $barang['kodeSatuanBarang'],
      "netto" => $barang['netto'],
      "nilaiBarang" => $barang['nilaiBarang'],
      "posTarif" => $barang['posTarif'],
      "seriBarang" => $barang['seriBarang'],
      "spesifikasiLain" => $barang['spesifikasiLain'],
      "tipe" => $barang['tipe'],
      "ukuran" => $barang['ukuran'],
      "uraian" => $barang['uraian'],
      "volume" => $barang['volume'],
      "barangTarif" => array(
        array(
          "kodeJenisTarif" => "1",
          "jumlahSatuan" => $barang['jumlahSatuan'],
          "kodeFasilitasTarif" => $barang['kodeFasilitasTarif'],
          "kodeSatuanBarang" => $barang['kodeSatuanBarang'],
          "nilaiBayar" => "0",
          "nilaiFasilitas" => $barang['nilaiFasilitas'],
          "nilaiSudahDilunasi" => "0",
          "seriBarang" => $barang['seriBarang'],
          "tarif" => $barang['TarifPPN'],
          "tarifFasilitas" => $barang['tarifFasilitas'],
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
      "asalData": "S",
      "bruto": "' . $data_aju[0]['Bruto'] . '",
      "kodeJenisTpb": "' . $data_aju[0]['KodeJenisTpb'] . '",
      "hargaPenyerahan": "' . $data_aju[0]['HargaPenyerahan'] . '",
      "jabatanTtd": "' . $data_aju[0]['JabatanTtd'] . '",
      "kodeDokumen": "40",
      "kodeKantor": "' . $data_aju[0]['KodeKantor'] . '",
      "kodeTujuanPengiriman": "1",
      "kotaTtd": "' . $data_aju[0]['KotaTtd'] . '",
      "namaTtd": "' . $data_aju[0]['NamaTtd'] . '",
      "netto": "' . $data_aju[0]['Netto'] . '",
      "nomorAju": "' . $nomor_aju . '",
      "seri": 1,
      "tanggalAju": "' . $data_aju[0]['TanggalAju'] . '",
      "tanggalTtd": "' . $data_aju[0]['tanggalTtd'] . '",
      "userPortal": "' . $username . '" ,

      "entitas": [
                  {
                    "alamatEntitas":"' . $data_aju[0]['AlamatEntitasPengusaha'] . '",
                    "kodeEntitas": "3",
                    "kodeJenisIdentitas": "' . $data_aju[0]['KodeJenisIdentitasPengusaha'] . '",
                    "namaEntitas": "' . $data_aju[0]['NamaEntitasPengusaha'] . '",
                    "nibEntitas": "' . $data_aju[0]['NibEntitasPengusaha'] . '",
                    "nomorIdentitas": "' . $data_aju[0]['NomorIdentitasPengusaha'] . '",
                    "nomorIjinEntitas": "' . $data_aju[0]['NomorIjinEntitasPengusaha'] . '",
                    "seriEntitas": 1,
                    "tanggalIjinEntitas": "' . $data_aju[0]['TanggalIjinEntitasPengusaha'] . '",
                  },
                  {
                    "alamatEntitas":"' . $data_aju[0]['AlamatEntitasPemilik'] . '",
                    "kodeEntitas": "7",
                    "kodeJenisApi":"' . $data_aju[0]['KodeJenisApiPemilik'] . '",
                    "kodeJenisIdentitas": "' . $data_aju[0]['KodeJenisIdentitasPemilik'] . '",
                    "kodeStatus": "' . $data_aju[0]['KodeStatusPemilik'] . '",
                    "namaEntitas": "' . $data_aju[0]['NamaEntitasPemilik'] . '",
                    "nibEntitas": "' . $data_aju[0]['NibEntitasPemilik'] . '",
                    "nomorIdentitas": "' . $data_aju[0]['NomorIdentitasPemilik'] . '",
                    "nomorIjinEntitas": "' . $data_aju[0]['NomorIjinEntitasPemilik'] . '",
                    "seriEntitas": 2,
                  },
                  {
                    "alamatEntitas":"' . $alamatpengirim . '",
                    "kodeEntitas":"9",
                    "kodeJenisApi":"' . $data_aju[0]['KodeJenisApiPengirim'] . '",
                    "kodeJenisIdentitas":"' . $data_aju[0]['KodeJenisIdentitasPengirim'] . '",
                    "kodeStatus":"' . $data_aju[0]['KodeStatusPengirim'] . '",
                    "namaEntitas":"' . $data_aju[0]['NamaEntitasPengirim'] . '",
                    "nibEntitas":"' . $data_aju[0]['NibEntitasPengirim'] . '",
                    "nomorIdentitas":"' . $data_aju[0]['NomorIdentitasPengirim'] . '",
                    "seriEntitas":"3",
                  }

                  ],
      "pengangkut": [
              {
                  "namaPengangkut":"' . $data_aju[0]['NamaPengangkut'] . '",
                  "nomorPengangkut": "' . $data_aju[0]['NomorPengangkut'] . '",
                  "seriPengangkut": 1
                }
                  ],

      "kemasan": [
              {
                  "jumlahKemasan": "' . $data_aju[0]['JumlahKemasan'] . '",
                  "kodeJenisKemasan": "' . $data_aju[0]['KodeJenisKemasan'] . '",
                  "seriKemasan": "1"
                }
                  ],

      "pungutan": [
              {
                  "kodeFasilitasTarif": "' . $data_aju[0]['KodeFasilitasTarif'] . '",
                  "kodeJenisPungutan": "' . $data_aju[0]['KodeJenisPungutan'] . '",
                  "nilaiPungutan": "' . $data_aju[0]['NilaiPungutan'] . '"
                }
                  ],

      "barang": "' . $json_barang . '"               


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

   echo "<pre>";
    print_r($result);
  echo "</pre>";


  //$status = $result->status;
  //return $status;
}
