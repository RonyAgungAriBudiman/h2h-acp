<?php
include "../sqlLib.php";
$sqlLib = new sqlLib();


// escape your parameters to prevent sql injection
$term = trim(strip_tags($_GET['term']));

// fetch a title for a better user experience maybe..
$sql = "SELECT DISTINCT TOP 10 a.KodeDokumen, a.NamaDokumen
		FROM ms_kode_dokumen a
		WHERE (a.NamaDokumen LIKE '%" . $term . "%' OR a.KodeDokumen LIKE '" . $term . "%') ";
$data_brg = $sqlLib->select($sql);
foreach ($data_brg as $data) {


    $row['id'] = $data['KodeDokumen'];
    $row['value'] = '(' . $data['KodeDokumen'] . ') ' . $data['NamaDokumen'];
    $row['namadokumen'] = '(' . $data['KodeDokumen'] . ') ' . $data['NamaDokumen'];

    //buat array yang nantinya akan di konversi ke json
    $row_set[] = $row;
}
echo json_encode($row_set);
