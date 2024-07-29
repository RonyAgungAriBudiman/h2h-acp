<?php
function kirimbc40($username, $access_token, $nomor_aju, $sqlLib)
{
  $curl = curl_init();

  //https://apisdev-gw.beacukai.go.id/openapi/document  
  //https://nlehub-dev.kemenkeu.go.id/openapi/document

  $sql_bc40 = "SELECT a.nomorAju, a.nomorDaftar, a.tglDaftar, a.dokumenBC,
                      b.itemNo, b.detailName, b.quantity, b.satuan, b.bruto, b.netto, b.volume, b.hsNumber, b.totalPrice
                FROM ac_penerimaan a
                LEFT JOIN ac_penerimaan_detail b on b.receiveItem =a.receiveItem
              WHERE a.nomorAju ='".$nomor_aju."' "; 
  $data_bc40 = $sqlLib->select($sql_bc40);

  $sql_sum = "SELECT COALESCE(b.bruto,null,0) as bruto, COALESCE(b.netto,null,0) as netto , COALESCE(b.totalPrice,null,0) as totalPrice
                FROM ac_penerimaan a
                LEFT JOIN ac_penerimaan_detail b on b.receiveItem =a.receiveItem
              WHERE a.nomorAju ='".$nomor_aju."' "; 
  $data_sum = $sqlLib->select($sql_sum);

  $sql_pt = "SELECT TOP 1 *, CASE WHEN LEN(NPWP)='12' THEN '0' 
                    WHEN LEN(NPWP)='10' THEN '1'
                    WHEN LEN(NPWP)='15' THEN '5' ELSE '4' END as kodeJenisIdentitas 
                    FROM ms_perusahaan ";
  $data_pt=$sqlLib->select($sql_pt);

  //data barang  
  $sql="SELECT b.totalPrice as hargaPenyerahan, '1' as jumlahKemasan, b.quantity as jumlahSatuan,
      b.itemNo as kodeBarang, '40' as kodeDokumen, '' as kodeJenisKemasan, b.satuan as kodeSatuanBarang,
      b.netto as netto, b.unitPrice as nilaiBarang, b.hsNumber as posTarif, ROW_NUMBER() OVER (ORDER BY b.itemNo) AS seriBarang,
      '-' as spesifikasiLain,'-' as tipe, '-' as ukuran, b.detailName as uraian, b.volume as volume, '' as barangTarif,
      '' as kodeJenisTarif, '' as kodeFasilitasTarif, '0' as nilaiBayar, '0' as nilaiFasilitas, '0' as nilaiSudahDilunasi,
      '0' as tarif, '0' as tarifFasilitas
      FROM ac_penerimaan a
      LEFT JOIN ac_penerimaan_detail b on b.receiveItem =a.receiveItem
      WHERE nomorAju ='".$nomor_aju."'";
  $data=$sqlLib->select($sql);
  foreach ($data as $row) 
  {
    
    $hasil_perulangan[] = $row;
  } 

  // Mengonversi array menjadi format JSON
  $json_output = json_encode($hasil_perulangan);

  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://apisdev-gw.beacukai.go.id/openapi/document',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>'{
      "asalData": "S",
      "bruto": "'.$data_sum[0]['bruto'].'",
      "kodeJenisTpb": "'.$data_pt[0]['KodeJenisTpb'].'",
      "hargaPenyerahan": "'.$data_sum[0]['totalPrice'].'",
      "jabatanTtd": "'.$data_pt[0]['Jabatan'].'",
      "kodeDokumen": "40",
      "kodeKantor": "'.$data_pt[0]['KantorPabean'].'",
      "kodeTujuanPengiriman": "1",
      "kotaTtd": "'.$data_pt[0]['Kota'].'",
      "namaTtd": "'.$data_pt[0]['Nama'].'",
      "netto": "'.$data_sum[0]['netto'].'",
      "nomorAju": "'.$nomor_aju.'",
      "seri": 1,
      "tanggalAju": "'.$data_bc40[0]['tglDaftar'].'",
      "tanggalTtd": "'.$data_bc40[0]['tglDaftar'].'",
      "userPortal": "'.$username.'" ,

      "entitas": [
                  {
                    "alamatEntitas":"'.$data_pt[0]['Alamat'].'",
                    "kodeEntitas": "3",
                    "kodeJenisIdentitas": "'.$data_pt[0]['kodeJenisIdentitas'].'",
                    "namaEntitas": "'.$data_pt[0]['NamaPerusahaan'].'",
                    "nibEntitas": "'.$data_pt[0]['NIB'].'",
                    "nomorIdentitas": "'.$data_pt[0]['NPWP'].'",
                    "nomorIjinEntitas": "'.$data_pt[0]['NomorIjinEntitas'].'",
                    "seriEntitas": 1,
                    "tanggalIjinEntitas": "'.$data_pt[0]['TanggalIjinEntitas'].'",
                  },
                  {
                    "alamatEntitas":"'.$data_pt[0]['Alamat'].'",
                    "kodeEntitas": "7",
                    "kodeJenisApi":"",
                    "kodeJenisIdentitas": "'.$data_pt[0]['kodeJenisIdentitas'].'",
                    "kodeStatus": "",
                    "namaEntitas": "'.$data_pt[0]['NamaPerusahaan'].'",
                    "nibEntitas": "'.$data_pt[0]['NIB'].'",
                    "nomorIdentitas": "'.$data_pt[0]['NPWP'].'",
                    "nomorIjinEntitas": "'.$data_pt[0]['NomorIjinEntitas'].'",
                    "seriEntitas": 1,
                  },
                  {
                    "alamatEntitas":"",
                    "kodeEntitas":"9",
                    "kodeJenisApi":"",
                    "kodeJenisIdentitas":"",
                    "kodeStatus":"",
                    "namaEntitas":"",
                    "nibEntitas":"",
                    "nomorIdentitas":"",
                    "seriEntitas":"",
                  }

                  ],
      "pengangkut": [
              {
                  "namaPengangkut":"",
                  "nomorPengangkut": "",
                  "seriPengangkut": 1
                }
                  ],

      "kemasan": [
              {
                  "jumlahKemasan": "",
                  "kodeJenisKemasan": "",
                  "seriKemasan": ""
                }
                  ],

      "pungutan": [
              {
                  "kodeFasilitasTarif": "",
                  "kodeJenisPungutan": "",
                  "nilaiPungutan": 0
                }
                  ],

      "barang": [
              {
                  "hargaPenyerahan": 123.45,
                  "jumlahKemasan": 1,
                  "jumlahSatuan": 2,
                  "kodeBarang": "BB00001",
                  "kodeDokumen": "40",
                  "kodeJenisKemasan": "4H",
                  "kodeSatuanBarang": "EA",
                  "netto": 0.5,
                  "nilaiBarang": 0,
                  "posTarif": "0",
                  "seriBarang": 1,
                  "spesifikasiLain": "-",
                  "tipe": "-",
                  "ukuran": "L",
                  "uraian": "MINYAK",
                  "volume": 0,
                  "barangTarif": [],
                  "kodeJenisTarif": "",
                  "kodeFasilitasTarif": "",
                  "nilaiBayar": 0,
                  "nilaiFasilitas": 0,
                  "nilaiSudahDilunasi": 0,
                  "tarif": 0,
                  "tarifFasilitas": 0


                }
                  ]                


  }',
    CURLOPT_HTTPHEADER => array(
      'Content-Type: application/json',
      'Authorization: Bearer '.$access_token
    ),
  ));

  $response = curl_exec($curl);

  curl_close($curl);  
  $result = json_decode($response);
  $status = $result->status;
  return $status;

  //echo $response;

}
?>
