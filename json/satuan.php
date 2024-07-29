<?php
include "../sqlLib.php";
$sqlLib = new sqlLib();


// escape your parameters to prevent sql injection
$term = trim(strip_tags($_GET['term']));

// fetch a title for a better user experience maybe..
$sql = "SELECT DISTINCT TOP 10 a.KodeSatuanBarang, a.NamaSatuanBarang
		FROM ms_satuan_barang a
		WHERE (a.NamaSatuanBarang LIKE '%" . $term . "%' OR a.KodeSatuanBarang LIKE '" . $term . "%') ";
$data_brg = $sqlLib->select($sql);
foreach ($data_brg as $data) {


    $row['id'] = $data['KodeSatuanBarang'];
    $row['value'] = '(' . $data['KodeSatuanBarang'] . ') ' . $data['NamaSatuanBarang'];
    $row['namasatuanbarang'] = '(' . $data['KodeSatuanBarang'] . ') ' . $data['NamaSatuanBarang'];

    //buat array yang nantinya akan di konversi ke json
    $row_set[] = $row;
}
echo json_encode($row_set);
