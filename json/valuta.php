<?php
include "../sqlLib.php";
$sqlLib = new sqlLib();


// escape your parameters to prevent sql injection
$term = trim(strip_tags($_GET['term']));

// fetch a title for a better user experience maybe..
$sql = "SELECT DISTINCT TOP 10 a.KodeValuta, a.NamaValuta
		FROM ms_valuta a
		WHERE (a.NamaValuta LIKE '%" . $term . "%' OR a.KodeValuta LIKE '" . $term . "%') ";
$data_brg = $sqlLib->select($sql);
foreach ($data_brg as $data) {


    $row['id'] = $data['KodeValuta'];
    $row['value'] = '(' . $data['KodeValuta'] . ') ' . $data['NamaValuta'];
    $row['namavaluta'] = '(' . $data['KodeValuta'] . ') ' . $data['NamaValuta'];

    //buat array yang nantinya akan di konversi ke json
    $row_set[] = $row;
}
echo json_encode($row_set);
