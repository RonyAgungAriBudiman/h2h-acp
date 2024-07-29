<?php
include_once "sqlLib.php";
$sqlLib = new sqlLib();

// Membuat array kosong untuk barang
  $array_barang = array();
  $sql_barang ="SELECT a.HargaPenyerahan, a.JumlahKemasan, a.JumlahSatuan, a.KodeBarang, '40' as KodeDokumen, a.KodeKemasan, a.KodeSatuan, a.Netto, a.NilaiBarang, 
                  a.Hs, a.SeriBarang, a.SpesifikasiLain, a.Tipe, a.Ukuran, a.Uraian, a.Volume, b.KodeTarif, b.KodeFasilitas, b.Tarif, b.NilaiFasilitas,
                  b.NilaiBayar, b.NilaiSudahDilunasi, b.TarifFasilitas
          FROM BC_BARANG a
          LEFT JOIN BC_BARANG_TARIF b on b.NomorAju = a.NomorAju AND b.SeriBarang = a.SeriBarang
          WHERE a.NomorAju = '000040-010694-20240521-000002'";
  $data_barang = $sqlLib->select($sql_barang); 

  foreach ($data_barang as $barang) {
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

echo "<pre>";
print_r($array_barang);
echo "</pre>";

// Menampilkan data JSON
//echo $json_data_barang;
