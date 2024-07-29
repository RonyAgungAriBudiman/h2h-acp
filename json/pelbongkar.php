<?php
include "../sqlLib.php";
$sqlLib = new sqlLib();


// escape your parameters to prevent sql injection
$term = trim(strip_tags($_GET['term']));

// fetch a title for a better user experience maybe..
$sql = "SELECT DISTINCT TOP 10 a.KodePelBongkar, a.NamaPelBongkar
		FROM ms_pelbongkar a
		WHERE (a.NamaPelBongkar LIKE '%" . $term . "%' OR a.KodePelBongkar LIKE '" . $term . "%') ";
$data_brg = $sqlLib->select($sql);
foreach ($data_brg as $data) {


    $row['id'] = $data['KodePelBongkar'];
    $row['value'] = '(' . $data['KodePelBongkar'] . ') ' . $data['NamaPelBongkar'];
    $row['namapelbongkar'] = '(' . $data['KodePelBongkar'] . ') ' . $data['NamaPelBongkar'];

    //buat array yang nantinya akan di konversi ke json
    $row_set[] = $row;
}
echo json_encode($row_set);
