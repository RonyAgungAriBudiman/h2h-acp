<?php
include "../sqlLib.php";
$sqlLib = new sqlLib();


// escape your parameters to prevent sql injection
$term = trim(strip_tags($_GET['term']));

// fetch a title for a better user experience maybe..
$sql = "SELECT DISTINCT TOP 10 a.KodeNegara, a.NamaNegara
		FROM ms_negara a
		WHERE (a.NamaNegara LIKE '%" . $term . "%' OR a.KodeNegara LIKE '" . $term . "%') ";
$data_brg = $sqlLib->select($sql);
foreach ($data_brg as $data) {


    $row['id'] = $data['KodeNegara'];
    $row['value'] = '(' . $data['KodeNegara'] . ') ' . $data['NamaNegara'];
    $row['namaNegara'] = '(' . $data['KodeNegara'] . ') ' . $data['NamaNegara'];

    //buat array yang nantinya akan di konversi ke json
    $row_set[] = $row;
}
echo json_encode($row_set);
