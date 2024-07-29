<?php
function kirimbc40($access_token, $nomor_aju, $sqlLib)
{
  $curl = curl_init();

  //https://apisdev-gw.beacukai.go.id/openapi/document  
  //https://nlehub-dev.kemenkeu.go.id/openapi/document

  $sql_bc40 = "SELECT a.NomorAju, b.kodeKantor, b.Kantor, c.Dokumen
              FROM ms_dokumen_pabean a 
              LEFT JOIN ms_kantor_pabean b on b.KodeKantor = a.KodeKantor
              LEFT JOIN ms_jenis_dokumen c on c.KodeDokumen = a.KodeDokumen
              WHERE a.NomorAju ='".$nomor_aju."' "; 
  $data_bc40 = $sqlLib->select($sql_bc40);
  $nomor_aju = str_replace('-', '', $data_bc40[0]['NomorAju']);

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
      "bruto": 12345,
      "kodeJenisTpb": "1",
      "hargaPenyerahan": 1234567,
      "jabatanTtd": "DIREKTUR",
      "kodeDokumen": "40",
      "kodeKantor": "'.$data_bc40[0]['NomorAju'].'",
      "kodeTujuanPengiriman": "1",
      "kotaTtd": "TANGERANG",
      "namaTtd": "FHR",
      "netto": 123456,
      "nomorAju": "'.$nomor_aju.'",
      "seri": 1,
      "tanggalAju": "2022-12-12",
      "tanggalTtd": "2022-12-12",
      "userPortal": "hkcidev" ,

  "entitas": [
          {
            "alamatEntitas":"JL. RAYA",
              "kodeEntitas": "3",
              "kodeJenisIdentitas": "5",
              "namaEntitas": "PT. ABAY ZONE",
              "nibEntitas": "1234567890",
              "nomorIdentitas": "010694800052000",
              "nomorIjinEntitas": "1234/KM.5/2022",
              "seriEntitas": 1,
              "tanggalIjinEntitas": "2022-12-12"
            }
              ],
  "pengangkut": [
          {
            "namaPengangkut":"MOBIL",
              "nomorPengangkut": "A 1428 WG",
              "seriPengangkut": 1
            }
              ],

  "kemasan": [
          {
              "jumlahKemasan": 1,
              "kodeJenisKemasan": "4H",
              "seriKemasan": 846
            }
              ],

  "pungutan": [
          {
              "kodeFasilitasTarif": "6",
              "kodeJenisPungutan": "PPN",
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
