<?php 
include_once "sqlLib.php";
$sqlLib = new sqlLib();

// Membuat array kosong untuk barang
$array_dokumen = array();

$sql = "SELECT a.KodeDokumen, a.TanggalDokumen, a.NomorDokumen, a.Seri 
        FROM BC_DOKUMEN a
        WHERE a.NomorAju = '000040-010694-20240521-000002'" ;      
$data_dok=$sqlLib->select($sql);

foreach ($data_dok as $dok) 
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

echo "<pre>";
print_r($array_dokumen);
echo "</pre>";

// Menampilkan data JSON
echo $json_data;

/*
// Mengonversi array menjadi format JSON
$json_output = json_encode($hasil_perulangan);

// Menampilkan hasil JSON
echo $json_output;
*/