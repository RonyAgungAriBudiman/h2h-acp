<?php
function kirimbc41($username, $access_token, $nomor_aju, $sqlLib)
{
  $curl = curl_init();

  //https://apisdev-gw.beacukai.go.id/openapi/document  
  //https://nlehub-dev.kemenkeu.go.id/openapi/document

  $sql_bc25 = "SELECT a.nomorAju, a.nomorDaftar, a.tglDaftar, a.dokumenBC,
                      b.itemNo, b.detailName, b.quantity, b.satuan, b.bruto, b.netto, b.volume, b.hsNumber, b.totalPrice
                FROM ac_penerimaan a
                LEFT JOIN ac_penerimaan_detail b on b.receiveItem =a.receiveItem
              WHERE a.nomorAju ='".$nomor_aju."' "; 
  $data_bc25 = $sqlLib->select($sql_bc25);

  $sql_sum = "SELECT SUM(COALESCE(b.bruto,null,0)) as bruto, SUM(COALESCE(b.netto,null,0)) as netto ,  
                      SUM(COALESCE(b.volume,null,0)) as volume , SUM(COALESCE(b.totalPrice,null,0)) as totalPrice
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
  $sql="SELECT b.totalPrice as hargaPenyerahan, b.unitPrice as hargaSatuan, '1' as jumlahKemasan, b.quantity as jumlahSatuan, 
               b.itemNo as kodeBarang, '41' as kodeDokumen, b.satuan as kodeSatuanBarang, b.netto as netto, b.unitPrice as nilaiBarang, 
               b.hsNumber as posTarif, ROW_NUMBER() OVER (ORDER BY b.itemNo) AS seriBarang, '-' as spesifikasiLain,'-' as tipe, '-' as ukuran, b.detailName as uraian
      FROM ac_penerimaan a
      LEFT JOIN ac_penerimaan_detail b on b.receiveItem =a.receiveItem
      WHERE nomorAju ='".$nomor_aju."'";
  $data=$sqlLib->select($sql);
  foreach ($data as $row) 
  {
    
    $hasil_perulangan[] = $row;
  } 

  // Mengonversi array menjadi format JSON
  $json_barang = json_encode($hasil_perulangan);

  curl_setopt_array($curl, array(
    CURLOPT_URL =>'https://apisdev-gw.beacukai.go.id/openapi/document', //'http://www.example.com/'
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>'{ 
        "asalData": "S",
        "asuransi":"0",
        "bruto": "'.$data_sum[0]['bruto'].'",
        "cif":"0",
        "kodeJenisTpb": "'.$data_pt[0]['KodeJenisTpb'].'",
        "freight":"0",
        "hargaPenyerahan": "'.$data_sum[0]['totalPrice'].'",        
        "jabatanTtd": "'.$data_pt[0]['Jabatan'].'",        
        "jumlahKontainer":"1",
        "kodeDokumen":"41",
        "kodeKantor": "'.$data_pt[0]['KantorPabean'].'",
        "kodeLokasiBayar":"",
        "kodePembayar":"",
        "kodeTujuanPengiriman":"",
        "kotaTtd": "'.$data_pt[0]['Kota'].'",
        "namaTtd": "'.$data_pt[0]['Nama'].'",
        "netto": "'.$data_sum[0]['netto'].'",
        "nilaiBarang": "'.$data_sum[0]['totalPrice'].'",
        "nomorAju": "'.$nomor_aju.'",     
        "seri":1,
        "tanggalAju": "'.$data_bc25[0]['tglDaftar'].'",
        "tanggalTtd": "'.$data_bc25[0]['tglDaftar'].'", 
        "userPortal": "'.$username.'" ,
        "volume": "'.$data_sum[0]['volume'].'",  
        "biayaTambahan":"0",
        "biayaPengurang":"0",
        "entitas":[
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
                      "kodeEntitas":"8",
                      "kodeJenisApi":"",
                      "kodeJenisIdentitas":"",
                      "kodeStatus":"",
                      "namaEntitas":"",
                      "nibEntitas":"",
                      "nomorIdentitas":"",
                      "seriEntitas":"",
                    }

                  ],
        "dokumen":[
                    {
                      "kodeDokumen": "",
                      "nomorDokumen": "",
                      "seriDokumen": "",
                      "tanggalDokumen": ""
                      
                    }
                  ],
        "pengangkut": [
                        {
                          "namaPengangkut":"",
                          "nomorPengangkut":"",
                          "seriPengangkut":""
                        }
                    ],
        "kemasan": [
                    {
                      "jumlahKemasan": "",
                      "kodeJenisKemasan": "",
                      "seriKemasan": ""
                      
                    }
                  ],
        
      "barang": "'.$json_barang.'"               


  }',
    CURLOPT_HTTPHEADER => array(
      'Content-Type: application/json',
      'Authorization: Bearer '.$access_token
    ),
  ));

  $response = curl_exec($curl);
  //echo $response;

  curl_close($curl);  
  $result = json_decode($response);
  $status = $result->status;
  return $status;  

}
?>
