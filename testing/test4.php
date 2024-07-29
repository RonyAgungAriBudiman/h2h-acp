<?php
include_once "sqlLib.php";
$sqlLib = new sqlLib();

// Membuat array kosong untuk barang
$array_barang = array();
$sql = "SELECT TOP 2  a.Asuransi, a.Cif, a.Diskon, a.Fob, a.Freight, a.HargaEkspor, a.HargaPenyerahan, a.HargaSatuan,
                a.IsiPerKemasan, a.JumlahKemasan, a.JumlahSatuan, a.KodeBarang, '23' as KodeDokumen, a.KodeKategoriBarang,
                a.KodeKemasan, a.KodeNegaraAsal, a.KodePerhitungan, a.KodeSatuan, a.Merek, a.Netto, a.NilaiBarang, a.NilaiTambah, a.Hs, 
                a.SeriBarang, a.SpesifikasiLain, a.Tipe, a.Ukuran, a.Uraian,
                a.Ndpbm, a.CifRupiah, a.HargaPerolehan, '0' as KodeAsalBahanBaku,isnull(c.SeriDokumen,'0') as SeriDokumen 
        FROM BC_BARANG a
        LEFT JOIN BC_BARANG_DOKUMEN c on c.NomorAju = a.NomorAju AND a.SeriBarang = a.SeriBarang
        WHERE a.NomorAju = '000023-010694-20240615-000004'  Order By a.SeriBarang Asc ";
$data_barang = $sqlLib->select($sql);
foreach ($data_barang as $barang) {
    $uraian = preg_replace("/[^a-zA-Z0-9()\s]/", "", $barang['Uraian']);

    $array_tarif = array();
    $sql_tarif = "SELECT a.SeriBarang, 
                          a.KodeTarif, a.KodeFasilitas, a.NilaiBayar, a.NilaiFasilitas, a.NilaiSudahDilunasi, a.Tarif, a.TarifFasilitas, a.KodePungutan
                  FROM BC_BARANG_TARIF a 
                  WHERE a.NomorAju = '000023-010694-20240615-000004' AND a.SeriBarang = '" . $barang['SeriBarang'] . "' ";
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
            "seriBarang" => intval($tarif['SeriBarang']),
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

// Mengonversi array menjadi JSON
//$json_data_barang = json_encode($array_tarif, JSON_PRETTY_PRINT);

echo "<pre>";
print_r($json_data_barang);
echo "</pre>";
