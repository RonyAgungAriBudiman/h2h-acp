<?php
session_start();
include_once "../sqlLib.php";
$sqlLib = new sqlLib();
include_once "../function/function.php";
$sql_cp = "SELECT IDPerusahaan, NamaPerusahaan, Kota
				FROM ms_perusahaan
				WHERE IDPerusahaan ='1'	";
$data_cp=$sqlLib->select($sql_cp);
?>

<html>
<head>
	<title>PRINT</title>
</head>
<body>
<table style="font-family:'Times New Roman'; font-size:12px; padding:10px;" border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>	
		<td>
			<table style="font-family:'Times New Roman'; font-size:14px; font-weight: bold;" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
				    <td align="center" >&nbsp;</td>
				    <td align="center" ><div align="left">LAPORAN PERTANGGUNGJAWABAN MUTASI BARANG SISA DAN SCRAP</div></td>
				    <td align="center" >&nbsp;</td>
				</tr>
				<tr>
				    <td align="center" >&nbsp;</td>
				    <td align="center"><div align="left">KAWASAN BERIKAT <?php echo strtoupper($data_cp[0]['NamaPerusahaan']) ?></div></td>
				    <td align="center" >&nbsp;</td>
				</tr>
				<!-- <tr>
				    <td align="center" ><div align="left">LAPORAN PERTANGGUNGJAWABAN MUTASI BAHAN BAKU DAN BAHAN PENOLONG</div></td>
				</tr> -->
				<tr>
				    <td align="center" >&nbsp;</td>
				    <td align="center" ><div align="left">PERIODE <?php echo strtoupper(tgl_indo($_GET['dari'])) ;?> S/D <?php echo strtoupper(tgl_indo($_GET['sampai'])); ?></div></td>
				    <td align="center" >&nbsp;</td>
				</tr>
				<tr>
				    <td align="center" colspan="3">&nbsp;</td>
				</tr>     
			</table>	
		</td>
	</tr>

	<tr>	
		<td>
			<table style="font-family:'Times New Roman'; font-size:12px; border:solid 1px #000;" border="1" cellpadding="1" cellspacing="1" width="100%">
				<tr>
	                <td style="font-weight: bold; text-align:center;" >No</td>
	                <td style="font-weight: bold; text-align:center;" >Kode Barang</td>
	                <td style="font-weight: bold; text-align:center;" >Nama Barang</td>
	                <td style="font-weight: bold; text-align:center;" >Sat</td>
	                <td style="font-weight: bold; text-align:center;" >Saldo Awal</td>
	                <td style="font-weight: bold; text-align:center;" >Pemasukan</td>
	                <td style="font-weight: bold; text-align:center;" >Pengeluaran</td>
	                <td style="font-weight: bold; text-align:center;" >Penyesuaian</td>
	                <td style="font-weight: bold; text-align:center;" >Saldo Akhir</td>
	                <td style="font-weight: bold; text-align:center;" >Stock Opname</td>
	                <td style="font-weight: bold; text-align:center;" >Selisih</td>
	                <td style="font-weight: bold; text-align:center;" >Keterangan</td>
	            </tr>
	            
	            
	            <?php
		            $no = 1;
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
									WHERE  a.tanggal ='".$_GET['dari']."' AND a.lokasiGudang='Scrap'
									Group By a.itemNo, a.itemName, a.unit1Name, a.itemCategory 
									UNION ALL

									SELECT a.itemNo, a.itemName, a.unit1Name, a.itemCategory,
										sum(0) as awal, sum(a.masuk) as masuk, sum(a.keluar) as keluar,	
										sum(a.adjustment) as adjustment, 
										sum(a.awal + a.masuk - a.keluar + a.adjustment) as akhir, 		
										sum(a.so) as so, 				
										sum(a.so) - sum(a.awal + a.masuk - a.keluar + a.adjustment) as selisih 
										FROM ac_stock a
									WHERE  a.tanggal>='".$_GET['dari']."' AND a.tanggal<='".$_GET['sampai']."'  AND a.lokasiGudang='Scrap'
									Group By a.itemNo, a.itemName, a.unit1Name, a.itemCategory 
									) as tbl WHERE tbl.itemNo!='' ";
								if($_GET['namabarang']!='') $sql .=" AND tbl.itemNo='".$_GET['kodebarang']."'";  	
				    $sql .=" Group By itemNo, itemName, unit1Name, itemCategory" ;        
		            $data = $sqlLib->select($sql);
		            
		            foreach ($data as $row) 
		            {
		            	$t_awal +=$row['awal'];
		            	$t_masuk +=$row['masuk'];
		            	$t_keluar +=$row['keluar'];
		            	$t_adjustment +=$row['adjustment'];
		            	$t_akhir +=$row['akhir'];
		            	$t_so +=$row['so'];
		            	$t_selisih +=$row['selisih'];
		            	?>
		                <tr>
		                    <td style="text-align:center;"><?php echo $no ?></td>
		                    <td style="text-align:center;"><?php echo $row['itemNo'] ?></td>
		                    <td style="text-align:left;"><?php echo $row['itemName'] ?></td>
		                    <td style="text-align:center;"><?php echo $row['unit1Name'] ?></td>
		                    <td style="text-align:right;"><?php echo $row['awal'] ?></td>
		                    <td style="text-align:right;"><?php echo $row['masuk'] ?></td>
		                    <td style="text-align:right;"><?php echo $row['keluar'] ?></td>
		                    <td style="text-align:right;"><?php echo $row['adjustment'] ?></td>
		                    <td style="text-align:right;"><?php echo $row['akhir'] ?></td>
		                    <td style="text-align:right;"><?php echo $row['so'] ?></td>
		                    <td style="text-align:right;"><?php echo $row['selisih'] ?></td>
		                    <td></td>
		                </tr>
		                <?php $no++;
		            }
				?> 
				<tr style="font-weight: bold;">
	                <td colspan="4" style="text-align:center;">Grand Total</td>
	                <td style="text-align:right;"><?php echo number_format($t_awal) ?></td>
	                <td style="text-align:right;"><?php echo number_format($t_masuk) ?></td>
	                <td style="text-align:right;"><?php echo number_format($t_keluar) ?></td>
	                <td style="text-align:right;"><?php echo number_format($t_adjustment) ?></td>
	                <td style="text-align:right;"><?php echo number_format($t_akhir) ?></td>
	                <td style="text-align:right;"><?php echo number_format($t_so) ?></td>
	                <td style="text-align:right;"><?php echo number_format($t_selisih) ?></td>
	                <td>&nbsp;</td>
	            </tr>    
				       
			</table>
		</td>
	</tr>

	<tr>	
		<td>&nbsp;</td>
	</tr>
	<tr>	
		<td style="text-align:right;"><div style="margin-right: 200px;"><?php echo $data_cp[0]['Kota'] ?>, <?php echo tgl_indo(date('Y-m-d')) ?></div></td>
	</tr>
	<tr>	
		<td style="text-align:right;"><div style="margin-right: 250px;"><?php echo strtoupper($data_cp[0]['NamaPerusahaan']) ?></div></td>
	</tr>
</table>
</body>
</html>

<script type="text/javascript">
 window.onafterprint = window.close;
 window.print();
</script>
