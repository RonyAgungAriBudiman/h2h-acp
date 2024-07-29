<?php
include "../sqlLib.php";
$sqlLib = new sqlLib();


// escape your parameters to prevent sql injection
$term = trim(strip_tags($_GET['term']));

// fetch a title for a better user experience maybe..
$sql = "SELECT DISTINCT TOP 10 a.KodeJenisKemasan, a.JenisKemasan
		FROM ms_kemasan a
		WHERE (a.JenisKemasan LIKE '%" . $term . "%' OR a.KodeJenisKemasan LIKE '" . $term . "%') ";
$data_brg = $sqlLib->select($sql);
foreach ($data_brg as $data) {


    $row['id'] = $data['KodeJenisKemasan'];
    $row['value'] = '(' . $data['KodeJenisKemasan'] . ') ' . $data['JenisKemasan'];
    $row['jeniskemasan'] = '(' . $data['KodeJenisKemasan'] . ') ' . $data['JenisKemasan'];

    //buat array yang nantinya akan di konversi ke json
    $row_set[] = $row;
}
echo json_encode($row_set);
