<?php
include "../sqlLib.php";
$sqlLib = new sqlLib();


// escape your parameters to prevent sql injection
$term = trim(strip_tags($_GET['term']));

// fetch a title for a better user experience maybe..
$sql = "SELECT DISTINCT TOP 10 a.KodeDaerah, a.NamaDaerah
		FROM ms_daerah a
		WHERE (a.NamaDaerah LIKE '%" . $term . "%' OR a.KodeDaerah LIKE '" . $term . "%') ";
$data_brg = $sqlLib->select($sql);
foreach ($data_brg as $data) {


    $row['id'] = trim($data['KodeDaerah']);
    $row['value'] = '(' . trim($data['KodeDaerah']) . ') ' . $data['NamaDaerah'];
    $row['namadaerah'] = '(' . trim($data['KodeDaerah']) . ') ' . $data['NamaDaerah'];

    //buat array yang nantinya akan di konversi ke json
    $row_set[] = $row;
}
echo json_encode($row_set);
