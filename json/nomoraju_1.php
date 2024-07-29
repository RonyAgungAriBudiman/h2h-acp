<?php
include "../sqlLib.php"; 
$sqlLib = new sqlLib();


// escape your parameters to prevent sql injection
$term = trim(strip_tags($_GET['term']));

// fetch a title for a better user experience maybe..
$sql = "SELECT DISTINCT TOP 10  NomorAju 
		FROM ms_dokumen_aju
		WHERE  (NomorAju LIKE '%".$term."%') ";	  
$data_brg=$sqlLib->select($sql);
foreach ($data_brg as $data)
{
	
	
    $row['id']=$data['NomorAju'];
	$row['value']=$data['NomorAju'];
	$row['nomoraju']=$data['NomorAju'];
	
	//buat array yang nantinya akan di konversi ke json
    $row_set[] = $row;
}
echo json_encode($row_set);
?>