<?php
include "../sqlLib.php";
$sqlLib = new sqlLib();


// escape your parameters to prevent sql injection
$term = trim(strip_tags($_GET['term']));

// fetch a title for a better user experience maybe..
$sql = "SELECT DISTINCT TOP 10 a.KodeBank, a.NamaBank
		FROM ms_bank a
		WHERE (a.NamaBank LIKE '%" . $term . "%' OR a.KodeBank LIKE '" . $term . "%') ";
$data_brg = $sqlLib->select($sql);
foreach ($data_brg as $data) {


    $row['id'] = trim($data['KodeBank']);
    $row['value'] = '(' . trim($data['KodeBank']) . ') ' . $data['NamaBank'];
    $row['namabank'] = '(' . trim($data['KodeBank']) . ') ' . $data['NamaBank'];

    //buat array yang nantinya akan di konversi ke json
    $row_set[] = $row;
}
echo json_encode($row_set);
