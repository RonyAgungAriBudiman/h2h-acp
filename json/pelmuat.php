<?php
include "../sqlLib.php";
$sqlLib = new sqlLib();


// escape your parameters to prevent sql injection
$term = trim(strip_tags($_GET['term']));

// fetch a title for a better user experience maybe..
$sql = "SELECT DISTINCT TOP 10 a.KodePelMuat, a.NamaPelMuat
		FROM ms_pelmuat a
		WHERE (a.NamaPelMuat LIKE '%" . $term . "%' OR a.KodePelMuat LIKE '" . $term . "%') ";
$data_brg = $sqlLib->select($sql);
foreach ($data_brg as $data) {


    $row['id'] = $data['KodePelMuat'];
    $row['value'] = '(' . $data['KodePelMuat'] . ') ' . $data['NamaPelMuat'];
    $row['namapelmuat'] = '(' . $data['KodePelMuat'] . ') ' . $data['NamaPelMuat'];

    //buat array yang nantinya akan di konversi ke json
    $row_set[] = $row;
}
echo json_encode($row_set);
