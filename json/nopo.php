<?php
include "../sqlLib.php";
$sqlLib = new sqlLib();


// escape your parameters to prevent sql injection
$term = trim(strip_tags($_GET['term']));

// fetch a title for a better user experience maybe..
$sql = "SELECT TOP 10  NoPO 
		FROM ac_po
		WHERE (NoPO LIKE '%" . $term . "%' OR  NoPO LIKE '" . $term . "%') ";
$data_brg = $sqlLib->select($sql);
foreach ($data_brg as $data) {


	$row['id'] = $data['NoPO'];
	$row['value'] = $data['NoPO'];
	$row['nopo'] = $data['NoPO'];

	//buat array yang nantinya akan di konversi ke json
	$row_set[] = $row;
}
echo json_encode($row_set);
