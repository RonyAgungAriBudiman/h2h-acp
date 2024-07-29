<?php
function kirimbc23($username, $access_token, $nomor_aju, $sqlLib)
{
  $curl = curl_init();

  //https://apisdev-gw.beacukai.go.id/openapi/document  
  //https://nlehub-dev.kemenkeu.go.id/openapi/document

  $sql_aju = "SELECT a.NoPO,a.NomorAju,a.DokumenBC,a.Urut,
              a.AsalData,a.Asuransi,a.Bruto,a.Cif,a.Fob,a.Freight,a.HargaPenyerahan,a.JabatanTtd,
              a.JumlahKontainer,a.KodeAsuransi,a.KodeIncoterm,
              a.KodeKantor,a.KodeKantorBongkar,a.KodePelBongkar,a.KodePelMuat,a.KodePelTransit,
              a.KodeTps,a.KodeTujuanTpb,a.KodeValuta,
              a.KotaTtd,a.NamaTtd,a.Ndpbm,a.Netto,a.NilaiBarang,
              a.NomorBc11,a.PosBc11,a.Seri,a.SubposBc11,a.TglBc11,
              a.TglTiba,a.TanggalTtd,a.BiayaTambahan,a.BiayaPengurang,
              a.AlamatEntitasPengusaha,a.KodeEntitasPengusaha,a.KodeJenisIdentitasPengusaha,
              a.NamaEntitasPengusaha,a.NibEntitasPengusaha,a.NomorIdentitasPengusaha,a.NomorIjinEntitasPengusaha,a.SeriEntitasPengusaha,a.TanggalIjinEntitasPengusaha,
              a.AlamatEntitasPemilik,a.KodeEntitasPemilik,a.KodeJenisApiPemilik,a.KodeJenisIdentitasPemilik,a.NamaEntitasPemilik,a.NibEntitasPemilik,a.NomorIdentitasPemilik,
              a.KodeStatusPemilik, a.SeriEntitasPemilik,
              a.AlamatEntitasPengirim,a.KodeEntitasPengirim,a.KodeJenisApiPengirim,a.KodeJenisIdentitasPengirim,a.NamaEntitasPengirim,a.NibEntitasPengirim,a.NomorIdentitasPengirim,
              a.SeriEntitasPengirim, a.KodeStatusPengirim, a.KodeNegara,
              a.KodeJenisKemasan, a.MerkKemasan, a.JumlahKemasan, a.SeriKemasan
              FROM ms_dokumen_aju a 
              WHERE a.NomorAju ='" . $nomor_aju . "' ";
  $data_aju = $sqlLib->select($sql_aju);
  $alamatpengirim = substr($data_aju[0]['AlamatEntitasPengirim'],0,32);

  
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
      "asuransi":'.$data_aju[0]['Asuransi'].',
      "bruto":'.$data_aju[0]['Bruto'].',
      "cif":'.$data_aju[0]['Cif'].',
      "fob":'.$data_aju[0]['Fob'].',
      "freight":'.$data_aju[0]['Freight'].',
      "hargaPenyerahan":'.$data_aju[0]['HargaPenyerahan'].',
      "jabatanTtd":"'.$data_aju[0]['HargaPenyerahan'].'",
      "jumlahKontainer":1,
      "kodeAsuransi":"'.$data_aju[0]['JabatanTtd'].'",
      "kodeDokumen":"23",
      "kodeIncoterm":"'.$data_aju[0]['KodeIncoterm'].'",
      "kodeKantor":"'.$data_aju[0]['KodeKantor'].'",
      "kodeKantorBongkar":"'.$data_aju[0]['KodeKantorBongkar'].'",
      "kodePelBongkar":"'.$data_aju[0]['KodePelBongkar'].'",
      "kodePelMuat":"'.$data_aju[0]['KodePelMuat'].'",
      "kodePelTransit":"'.$data_aju[0]['KodePelTransit'].'",
      "kodeTps":"'.$data_aju[0]['KodeTps'].'",
      "kodeTujuanTpb":"'.$data_aju[0]['KodeTujuanTpb'].'",
      "kodeTutupPu":"11",
      "kodeValuta":"'.$data_aju[0]['KodeValuta'].'",
      "kotaTtd":"'.$data_aju[0]['KotaTtd'].'",
      "namaTtd":"'.$data_aju[0]['NamaTtd'].'",
      "ndpbm":'.$data_aju[0]['Ndpbm'].',
      "netto":'.$data_aju[0]['Netto'].',
      "nik":"'.$data_aju[0]['NibEntitasPengusaha'].'",
      "nilaiBarang":'.$data_aju[0]['NilaiBarang'].',
      "nomorAju":"' . $data_aju[0]['NomorAju'] . '",
      "nomorBc11":"' . $data_aju[0]['NomorBc11'] . '",
      "posBc11":"' . $data_aju[0]['PosBc11'] . '",
      "seri":1,
      "subposBc11":"' . $data_aju[0]['SubposBc11'] . '",
      "tanggalBc11":"' . $data_aju[0]['TglBc11'] . '",
      "tanggalTiba":"' . $data_aju[0]['TglTiba'] . '",
      "tanggalTtd":"' . $data_aju[0]['TanggalTtd'] . '",
      "biayaTambahan":' . $data_aju[0]['BiayaTambahan'] . ',
      "biayaPengurang":' . $data_aju[0]['BiayaPengurang'] . ',
      "barang": [ 
                  { 
                    "asuransi":'.$data_aju[0]['Asuransi'].',                    
                    "cif":0,
                    "diskon":0,
                    "fob":0,
                    "freight":0,
                    "hargaEkspor":0,
                    "hargaPenyerahan":0,
                    "hargaSatuan":0,
                    "isiPerKemasan":0,
                    "jumlahKemasan":0,
                    "jumlahSatuan":0,
                    "kodeBarang":"A",
                    "kodeDokumen":"23",
                    "kodeKategoriBarang":"A",
                    "kodeJenisKemasan":"A",
                    "kodeNegaraAsal":"CN",
                    "kodePerhitungan":"0",
                    "kodeSatuanBarang":"A",
                    "merk":"A",
                    "netto":0,
                    "nilaiBarang":0,
                    "nilaiTambah":0,
                    "posTarif":"A",
                    "seriBarang":1,
                    "spesifikasiLain":"A",
                    "tipe":"A",
                    "ukuran":"A",
                    "uraian":"A",
                    "ndpbm":0,
                    "cifRupiah":0,
                    "hargaPerolehan":0,
                    "kodeAsalBahanBaku":"0",
                    "barangTarif": [
                                     {
                                      "kodeJenisTarif":"1",
                                      "jumlahSatuan":1, 
                                      "kodeFasilitasTarif":"3",
                                      "kodeSatuanBarang":"BE",
                                      "kodeJenisPungutan": "BM",
                                      "nilaiBayar":0,
                                      "nilaiFasilitas":0,
                                      "nilaiSudahDilunasi":0,
                                      "seriBarang":1,
                                      "tarif":0,
                                      "tarifFasilitas":0
                                     } 
                                    ],
                     "barangDokumen":[
                                      {
                                        "seriDokumen":"1"
                                      }
                                    ]               
                  }
                ],
       "entitas":[
                    {
                      "alamatEntitas":"' . $data_aju[0]['AlamatEntitasPengusaha'] . '",
                      "kodeEntitas": "3",
                      "kodeJenisIdentitas": "' . $data_aju[0]['KodeJenisIdentitasPengusaha'] . '",
                      "namaEntitas": "' . $data_aju[0]['NamaEntitasPengusaha'] . '",
                      "nibEntitas": "' . $data_aju[0]['NibEntitasPengusaha'] . '",
                      "nomorIdentitas": "' . $data_aju[0]['NomorIdentitasPengusaha'] . '",
                      "nomorIjinEntitas": "' . $data_aju[0]['NomorIjinEntitasPengusaha'] . '",
                      "tanggalIjinEntitas": "' . $data_aju[0]['TanggalIjinEntitasPengusaha'] . '",
                      "seriEntitas": 1
                    },
                    {
                      "alamatEntitas":"'.$alamatpengirim.'",
                      "kodeEntitas":"5",
                      "kodeNegara":"' . $data_aju[0]['KodeNegara'] . '",
                      "namaEntitas":"' . $data_aju[0]['NamaEntitasPengirim'] . '",
                      "seriEntitas":2
                    },
                    {
                      
                      "alamatEntitas":"' . $data_aju[0]['AlamatEntitasPemilik'] . '",
                      "kodeEntitas": "7",
                      "kodeJenisApi":"' . $data_aju[0]['KodeJenisApiPemilik'] . '",
                      "kodeJenisIdentitas": "' . $data_aju[0]['KodeJenisIdentitasPemilik'] . '",
                      "kodeStatus": "' . $data_aju[0]['KodeStatusPemilik'] . '",
                      "namaEntitas": "' . $data_aju[0]['NamaEntitasPemilik'] . '",
                      "nomorIdentitas": "' . $data_aju[0]['NomorIdentitasPemilik'] . '",
                      "nomorIjinEntitas": "' . $data_aju[0]['NomorIjinEntitasPemilik'] . '",
                      "tanggalIjinEntitas": "' . $data_aju[0]['TanggalIjinEntitasPengusaha'] . '",
                      "seriEntitas": 3
                    }
                  ],
         "kemasan":[
                    {
                      "jumlahKemasan":1,
                      "kodeJenisKemasan": "' . $data_aju[0]['KodeJenisKemasan'] . '",
                      "seriKemasan": ' . $data_aju[0]['SeriKemasan'] . ',
                      "merkKemasan": "' . $data_aju[0]['MerkKemasan'] . '"
                    }
                   ],
         "dokumen":[
                    {
                      "kodeDokumen":"380",
                      "nomorDokumen":"123",
                      "seriDokumen":1,
                      "tanggalDokumen":"2024-05-01"
                    }
                   ],
          "pengangkut":[
                        {
                          "kodeBendera":"CN",
                          "namaPengangkut":"Kapal",
                          "nomorPengangkut":"A123B",
                          "kodeCaraAngkut":"1",
                          "seriPengangkut":1
                        }
                      ]            

      


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

echo $data_aju[0]['Asuransi'];
  echo "<pre>";
      print_r($result);
    echo "</pre>";
  $status = $result->status;
  return $status;  

}
?>
