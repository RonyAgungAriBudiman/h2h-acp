<?php
session_start();
include("../../plugins/PHPExcel/PHPExcel.php");
include("../../plugins/PHPExcel/PHPExcel/IOFactory.php");
include_once "../../function/function.php";



$excel = new PHPExcel();
$excel->setActiveSheetIndex(0);

$inputFileType = 'Excel5';
$inputFileName = 'template/LapMesin.xls';
$objReader = PHPExcel_IOFactory::createReader($inputFileType);
$objPHPExcel = $objReader->load($inputFileName);

include_once "../../sqlLib.php";
$sqlLib = new sqlLib();

$objPHPExcel->setActiveSheetIndex(0);
$outputFileName = 'Laporan Pertanggungjawaban Mutasi Mesin Dan Peralatan Kantor.xls';


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
$objPHPExcel->getActiveSheet()->setCellValue('B3',  'PERIODE '.strtoupper(tgl_indo($_GET['dari'])).' S/D '.strtoupper(tgl_indo($_GET['sampai']))  );

$no = 1;
$baris = 7;
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
        WHERE   a.tanggal ='".$_GET['dari']."' AND a.lokasiGudang='Barang Modal'
        Group By a.itemNo, a.itemName, a.unit1Name, a.itemCategory ,a.lokasiGudang
        UNION ALL

        SELECT a.itemNo, a.itemName, a.unit1Name, a.itemCategory,
            sum(0) as awal, sum(a.masuk) as masuk, sum(a.keluar) as keluar, 
            sum(a.adjustment) as adjustment, 
            sum(a.awal + a.masuk - a.keluar + a.adjustment) as akhir,       
            sum(a.so) as so,                
            sum(a.so) - sum(a.awal + a.masuk - a.keluar + a.adjustment) as selisih 
            FROM ac_stock a
        WHERE   a.tanggal>='".$_GET['dari']."' AND a.tanggal<='".$_GET['sampai']."' AND a.lokasiGudang='Barang Modal'
        Group By a.itemNo, a.itemName, a.unit1Name, a.itemCategory ,a.lokasiGudang
        ) as tbl WHERE tbl.itemNo!='' ";
    if($_GET['namabarang']!='') $sql .=" AND tbl.itemNo='".$_GET['kodebarang']."'";     
$sql .=" Group By itemNo, itemName, unit1Name, itemCategory" ;  
$data = $sqlLib->select($sql);
foreach ($data as $row) {
	$t_awal +=$row['awal'];
    $t_masuk +=$row['masuk'];
    $t_keluar +=$row['keluar'];
    $t_adjustment +=$row['adjustment'];
    $t_akhir +=$row['akhir'];
    $t_so +=$row['so'];
    $t_selisih +=$row['selisih'];

    $objPHPExcel->getActiveSheet()->setCellValue('A' . $baris, $no);
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $baris, $row["itemNo"]);
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $baris, $row["itemName"]);
    $objPHPExcel->getActiveSheet()->setCellValue('D' . $baris, $row['unit1Name']);
    $objPHPExcel->getActiveSheet()->setCellValue('E' . $baris, number_format($row["awal"]));
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $baris, number_format($row["masuk"]));
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $baris, number_format($row["keluar"]));
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $baris, number_format($row["adjustment"]));
    $objPHPExcel->getActiveSheet()->setCellValue('I' . $baris, number_format($row["akhir"]));
    $objPHPExcel->getActiveSheet()->setCellValue('J' . $baris, number_format($row["so"]));
    $objPHPExcel->getActiveSheet()->setCellValue('K' . $baris, number_format($row["selisih"]));
    $objPHPExcel->getActiveSheet()->setCellValue('L' . $baris, '');


    $objPHPExcel->getActiveSheet()->getStyle("A" . $baris . ":B" . $baris)->applyFromArray($bordercenter);
    $objPHPExcel->getActiveSheet()->getStyle("C" . $baris . ":C" . $baris)->applyFromArray($borderleft);
    $objPHPExcel->getActiveSheet()->getStyle("D" . $baris . ":D" . $baris)->applyFromArray($bordercenter);
    $objPHPExcel->getActiveSheet()->getStyle("E" . $baris . ":L" . $baris)->applyFromArray($borderright);
    $baris = $baris + 1;
    $no++;
}
	$objPHPExcel->getActiveSheet()->setCellValue('A' . $baris, '');
	$objPHPExcel->getActiveSheet()->setCellValue('B' . $baris, 'GRAND TOTAL');
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $baris, '');
    $objPHPExcel->getActiveSheet()->setCellValue('D' . $baris, '');
    $objPHPExcel->getActiveSheet()->setCellValue('E' . $baris, number_format($t_awal));
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $baris, number_format($t_masuk));
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $baris, number_format($t_keluar));
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $baris, number_format($t_adjustment));
    $objPHPExcel->getActiveSheet()->setCellValue('I' . $baris, number_format($t_akhir));
    $objPHPExcel->getActiveSheet()->setCellValue('J' . $baris, number_format($t_so));
    $objPHPExcel->getActiveSheet()->setCellValue('K' . $baris, number_format($t_selisih));
    $objPHPExcel->getActiveSheet()->setCellValue('L' . $baris, '');

    $objPHPExcel->getActiveSheet()->getStyle("A" . $baris . ":D" . $baris)->applyFromArray($bordercenter);
    $objPHPExcel->getActiveSheet()->getStyle("E" . $baris . ":L" . $baris)->applyFromArray($borderright);

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