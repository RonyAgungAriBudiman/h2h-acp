<?php
include "../sqlLib.php";
$sqlLib = new sqlLib();


// escape your parameters to prevent sql injection
$term = trim(strip_tags($_GET['term']));

// fetch a title for a better user experience maybe..
$sql = "SELECT DISTINCT TOP 10 a.itemNo, a.detailName, a.satuan
		FROM ac_penerimaan_detail a
		WHERE (a.detailName LIKE '%" . $term . "%' OR a.itemNo LIKE '" . $term . "%') ";
$data_brg = $sqlLib->select($sql);
foreach ($data_brg as $data) {


	$row['id'] = $data['itemNo'];
	$row['value'] = $data['detailName'];
	$row['namabarang'] = $data['detailName'];

	//buat array yang nantinya akan di konversi ke json
	$row_set[] = $row;
}
echo json_encode($row_set);
