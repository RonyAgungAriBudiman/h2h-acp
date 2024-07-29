<?php
include "../sqlLib.php";
$sqlLib = new sqlLib();


// escape your parameters to prevent sql injection
$term = trim(strip_tags($_GET['term']));

// fetch a title for a better user experience maybe..
$sql = "SELECT TOP 10  NoSO 
		FROM ac_so
		WHERE (NoSO LIKE '%" . $term . "%' OR  NoSO LIKE '" . $term . "%') ";
$data_brg = $sqlLib->select($sql);
foreach ($data_brg as $data) {


	$row['id'] = $data['NoSO'];
	$row['value'] = $data['NoSO'];
	$row['noso'] = $data['NoSO'];

	//buat array yang nantinya akan di konversi ke json
	$row_set[] = $row;
}
echo json_encode($row_set);
