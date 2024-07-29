<?php
include "../sqlLib.php"; 
$sqlLib = new sqlLib();


// escape your parameters to prevent sql injection
$term = trim(strip_tags($_GET['term']));

// fetch a title for a better user experience maybe..
$sql = "SELECT DISTINCT TOP 10  nomorAju 
		FROM (
				SELECT DISTINCT nomorAju FROM ac_penerimaan  WHERE nomorAju !=''
				UNION ALL

				SELECT DISTINCT nomorAju FROM ac_pengeluaran  WHERE nomorAju !=''
			  ) as tbl
		WHERE (tbl.nomorAju LIKE '%".$term."%') ";	  
$data_brg=$sqlLib->select($sql);
foreach ($data_brg as $data)
{
	
	
    $row['id']=$data['nomorAju'];
	$row['value']=$data['nomorAju'];
	$row['nomoraju']=$data['nomorAju'];
	
	//buat array yang nantinya akan di konversi ke json
    $row_set[] = $row;
}
echo json_encode($row_set);
?>