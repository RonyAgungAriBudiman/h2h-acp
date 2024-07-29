<?php
session_start();
include("../../plugins/PHPExcel/PHPExcel.php");
include("../../plugins/PHPExcel/PHPExcel/IOFactory.php");

include_once "../../function/function.php";

$excel = new PHPExcel();
$excel->setActiveSheetIndex(0);

$inputFileType = 'Excel5';
$inputFileName = 'template/wip.xls';
$objReader = PHPExcel_IOFactory::createReader($inputFileType);
$objPHPExcel = $objReader->load($inputFileName);

include_once "../../sqlLib.php";
$sqlLib = new sqlLib();

$objPHPExcel->setActiveSheetIndex(0);
$outputFileName = 'Laporan Posisi Barang Dalam Proses (WIP).xls';


$bordercenter = array(
    'font'  => array(
        'bold'  => false,
        'size'  => 8,
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
    ),
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN
        )
    )
);

$borderleft = array(
    'font'  => array(
        'bold'  => false,
        'size'  => 8,
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
    ),
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN
        )
    )
);


$borderright = array(
    'font'  => array(
        'bold'  => false,
        'size'  => 8,
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
    ),
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN
        )
    )
);


$center = array(
    'font'  => array(
        'bold'  => true,
        'size'  => 11,
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
    ),
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN
        )
    )
);

$left = array(
    'font'  => array(
        'bold'  => true,
        'size'  => 11,
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
    ),
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN
        )
    )
);


$right = array(
    'font'  => array(
        'bold'  => true,
        'size'  => 11,
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
    ),
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN
        )
    )
);

$sql_cp = "SELECT IDPerusahaan, NamaPerusahaan, Kota
                FROM ms_perusahaan
                WHERE IDPerusahaan ='1' ";
$data_cp=$sqlLib->select($sql_cp);

$objPHPExcel->getActiveSheet()->setCellValue('B2',  'KAWASAN BERIKAT '.strtoupper($data_cp[0]['NamaPerusahaan']));
$objPHPExcel->getActiveSheet()->setCellValue('B4',  'PERIODE '.strtoupper(tgl_indo($_GET['dari'])).' S/D '.strtoupper(tgl_indo($_GET['sampai'])) );

$no = 1;
$baris = 8;
$sql = "SELECT itemNo, itemName, unit1Name, itemCategory,
            sum(awal) as awal, sum(masuk) as masuk, sum(keluar) as keluar,
            sum(adjustment) as adjustment, 
            sum(awal + masuk - keluar + adjustment) as akhir,       
            sum(so) as so,              
            sum(so) - sum(awal + masuk - keluar + adjustment)  as selisih 
        FROM 
        (
        SELECT a.itemNo, a.itemName, a.unit1Name, a.itemCategory,
            sum(a.awal) as awal, sum(0) as masuk, sum(0) as keluar, 
                sum(0) as adjustment, 
                sum(0) as akhir,        
                sum(0) as so,               
                sum(0) as selisih 
            FROM ac_stock a
        WHERE a.tanggal ='".$_GET['dari']."' AND a.lokasiGudang='Produksi'
        Group By a.itemNo, a.itemName, a.unit1Name, a.itemCategory, a.lokasiGudang 
        UNION ALL

        SELECT a.itemNo, a.itemName, a.unit1Name, a.itemCategory,
            sum(0) as awal, sum(a.masuk) as masuk, sum(a.keluar) as keluar, 
            sum(a.adjustment) as adjustment, 
            sum(a.awal + a.masuk - a.keluar + a.adjustment) as akhir,       
            sum(a.so) as so,                
            sum(a.so) - sum(a.awal + a.masuk - a.keluar + a.adjustment) as selisih 
            FROM ac_stock a
        WHERE a.tanggal>='".$_GET['dari']."' AND a.tanggal<='".$_GET['sampai']."'  AND a.lokasiGudang='Produksi'
        Group By a.itemNo, a.itemName, a.unit1Name, a.itemCategory, a.lokasiGudang 
        ) as tbl WHERE tbl.itemNo!='' ";
    if($_GET['namabarang']!='') $sql .=" AND tbl.itemNo='".$_GET['kodebarang']."'";     
$sql .=" Group By itemNo, itemName, unit1Name, itemCategory" ;  
$data = $sqlLib->select($sql);
foreach ($data as $row) {
	$t_akhir +=$row['akhir'];

    $objPHPExcel->getActiveSheet()->setCellValue('A' . $baris, $no);
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $baris, $row["itemNo"]);
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $baris, $row["itemName"]);
    $objPHPExcel->getActiveSheet()->setCellValue('D' . $baris, $row['unit1Name']);
    $objPHPExcel->getActiveSheet()->setCellValue('E' . $baris, number_format($row["akhir"]));
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $baris, '');


    $objPHPExcel->getActiveSheet()->getStyle("A" . $baris . ":B" . $baris)->applyFromArray($bordercenter);
    $objPHPExcel->getActiveSheet()->getStyle("C" . $baris . ":C" . $baris)->applyFromArray($borderleft);
    $objPHPExcel->getActiveSheet()->getStyle("D" . $baris . ":D" . $baris)->applyFromArray($bordercenter);
    $objPHPExcel->getActiveSheet()->getStyle("E" . $baris . ":E" . $baris)->applyFromArray($borderright);
    $objPHPExcel->getActiveSheet()->getStyle("F" . $baris . ":F" . $baris)->applyFromArray($borderleft);
    $baris = $baris + 1;
    $no++;
}
	$objPHPExcel->getActiveSheet()->setCellValue('A' . $baris, '');
	$objPHPExcel->getActiveSheet()->setCellValue('B' . $baris, 'GRAND TOTAL');
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $baris, '');
    $objPHPExcel->getActiveSheet()->setCellValue('D' . $baris, '');
    $objPHPExcel->getActiveSheet()->setCellValue('E' . $baris, number_format($t_akhir));
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $baris, '');

    $objPHPExcel->getActiveSheet()->getStyle("A" . $baris . ":D" . $baris)->applyFromArray($bordercenter);
    $objPHPExcel->getActiveSheet()->getStyle("E" . $baris . ":E" . $baris)->applyFromArray($borderright);
    $objPHPExcel->getActiveSheet()->getStyle("F" . $baris . ":F" . $baris)->applyFromArray($bordercenter);

$outputFileType = 'Excel5';
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $outputFileType);
$objWriter->save($outputFileName);
$excel->disconnectWorksheets();
unset($excel);

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="' . $outputFileName . '"');
header("Cache-Control: no-cache, must-revalidate");
$objWriter->save('php://output');

unlink($outputFileName);