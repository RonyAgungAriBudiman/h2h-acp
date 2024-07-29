<?php
include "../sqlLib.php";
$sqlLib = new sqlLib();


// escape your parameters to prevent sql injection
$term = trim(strip_tags($_GET['term']));

// fetch a title for a better user experience maybe..
$sql = "SELECT DISTINCT TOP 10 a.KodeKantor, a.NamaKantor
		FROM ms_kantor a
		WHERE (a.NamaKantor LIKE '%" . $term . "%' OR a.KodeKantor LIKE '" . $term . "%') ";
$data_brg = $sqlLib->select($sql);
foreach ($data_brg as $data) {


    $row['id'] = $data['KodeKantor'];
    $row['value'] = '(' . $data['KodeKantor'] . ') ' . $data['NamaKantor'];
    $row['namaKantor'] = '(' . $data['KodeKantor'] . ') ' . $data['NamaKantor'];

    //buat array yang nantinya akan di konversi ke json
    $row_set[] = $row;
}
echo json_encode($row_set);
