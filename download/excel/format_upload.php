<?php
session_start();
include("../../plugins/PHPExcel/PHPExcel.php");
include("../../plugins/PHPExcel/PHPExcel/IOFactory.php");

include_once "../../function/function.php";



$excel = new PHPExcel();
$excel->setActiveSheetIndex(0);

$inputFileType = 'Excel5';
$inputFileName = 'template/format_upload.xls';
$objReader = PHPExcel_IOFactory::createReader($inputFileType);
$objPHPExcel = $objReader->load($inputFileName);

include_once "../../sqlLib.php";
$sqlLib = new sqlLib();

$objPHPExcel->setActiveSheetIndex(0);
$outputFileName = 'DokumenPengajuan.xls';



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