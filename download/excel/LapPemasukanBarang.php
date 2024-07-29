<?php
session_start();
include("../../plugins/PHPExcel/PHPExcel.php");
include("../../plugins/PHPExcel/PHPExcel/IOFactory.php");
include_once "../../function/function.php";



$excel = new PHPExcel();
$excel->setActiveSheetIndex(0);

$inputFileType = 'Excel5';
$inputFileName = 'template/LapPemasukanBarang.xls';
$objReader = PHPExcel_IOFactory::createReader($inputFileType);
$objPHPExcel = $objReader->load($inputFileName);

include_once "../../sqlLib.php";
$sqlLib = new sqlLib();

$objPHPExcel->setActiveSheetIndex(0);
$outputFileName = 'Laporan Pemasukan Barang Per Dokumen Pabean.xls';


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
$objPHPExcel->getActiveSheet()->setCellValue('B4',  'PERIODE '.strtoupper(tgl_indo($_GET['dari'])).' S/D '.strtoupper(tgl_indo($_GET['sampai']))  );

$no = 1;
$baris = 8;
$sql = "SELECT a.dokumenBC, a.nomorAju, a.nomorDaftar, a.tglDaftar,  a.vendor, a.receiveItem, a.receiveNumber, a.receiveDate, 
            b.itemNo, b.detailName,  b.quantity, b.satuan, b.totalPrice, b.hsNumber, b.bruto, b.netto, b.volume
        FROM ac_penerimaan a
        LEFT JOIN ac_penerimaan_detail b on b.receiveItem = a.receiveItem                      
        WHERE a.tglDaftar>='".$_GET['dari']."' AND a.tglDaftar<='".$_GET['sampai']."' ";
        if($_GET['dokbc']!='') $sql .=" AND a.dokumenBC='".$_GET['dokbc']."'";  
        if($_GET['namabarang']!='') $sql .=" AND b.itemNo='".$_GET['kodebarang']."'"; 
$sql .=" Order By a.tglDaftar Asc";
$data = $sqlLib->select($sql);
foreach ($data as $row) {
	$dokumenBC = "BC ".$row["dokumenBC"];
	$t_qty +=$row['quantity'];
	$t_price +=$row['totalPrice'];

    $objPHPExcel->getActiveSheet()->setCellValue('A' . $baris, $no);
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $baris, $dokumenBC);
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $baris, $row["nomorDaftar"]);
    $objPHPExcel->getActiveSheet()->setCellValue('D' . $baris, date("d-M-Y", strtotime($row['tglDaftar'])));
    $objPHPExcel->getActiveSheet()->setCellValue('E' . $baris, $row["receiveNumber"]);
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $baris, date("d-M-Y", strtotime($row['receiveDate'])));
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $baris, $row["vendor"]);
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $baris, $row["itemNo"]);
    $objPHPExcel->getActiveSheet()->setCellValue('I' . $baris, $row["detailName"]);
    $objPHPExcel->getActiveSheet()->setCellValue('J' . $baris, $row['satuan']);
    $objPHPExcel->getActiveSheet()->setCellValue('K' . $baris, number_format($row["quantity"]));
    $objPHPExcel->getActiveSheet()->setCellValue('L' . $baris, number_format($row["totalPrice"]));


    $objPHPExcel->getActiveSheet()->getStyle("A" . $baris . ":F" . $baris)->applyFromArray($bordercenter);
    $objPHPExcel->getActiveSheet()->getStyle("G" . $baris . ":G" . $baris)->applyFromArray($borderleft);
    $objPHPExcel->getActiveSheet()->getStyle("H" . $baris . ":H" . $baris)->applyFromArray($bordercenter);
    $objPHPExcel->getActiveSheet()->getStyle("I" . $baris . ":I" . $baris)->applyFromArray($borderleft);
    $objPHPExcel->getActiveSheet()->getStyle("J" . $baris . ":J" . $baris)->applyFromArray($bordercenter);
    $objPHPExcel->getActiveSheet()->getStyle("K" . $baris . ":L" . $baris)->applyFromArray($borderright);
    $baris = $baris + 1;
    $no++;
}
	$objPHPExcel->getActiveSheet()->setCellValue('A' . $baris, '');
	$objPHPExcel->getActiveSheet()->setCellValue('B' . $baris, 'GRAND TOTAL');
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $baris, '');
    $objPHPExcel->getActiveSheet()->setCellValue('D' . $baris, '');
    $objPHPExcel->getActiveSheet()->setCellValue('E' . $baris, '');
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $baris, '');
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $baris, '');
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $baris, '');
    $objPHPExcel->getActiveSheet()->setCellValue('I' . $baris, '');
    $objPHPExcel->getActiveSheet()->setCellValue('J' . $baris, '');
    $objPHPExcel->getActiveSheet()->setCellValue('K' . $baris, number_format($t_qty));
    $objPHPExcel->getActiveSheet()->setCellValue('L' . $baris, number_format($t_price));

    $objPHPExcel->getActiveSheet()->getStyle("A" . $baris . ":A" . $baris)->applyFromArray($bordercenter);
    $objPHPExcel->getActiveSheet()->getStyle("B" . $baris . ":B" . $baris)->applyFromArray($bordercenter);
    $objPHPExcel->getActiveSheet()->getStyle("C" . $baris . ":J" . $baris)->applyFromArray($bordercenter);
    $objPHPExcel->getActiveSheet()->getStyle("K" . $baris . ":L" . $baris)->applyFromArray($borderright);

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