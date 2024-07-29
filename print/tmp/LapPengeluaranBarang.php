<?php
session_start();
include_once "../sqlLib.php";
$sqlLib = new sqlLib();
include_once "../function/function.php";

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
				    <td align="center" >B.</td>
				    <td align="center" ><div align="left">LAPORAN PENGELUARAN BARANG PER DOKUMEN PABEAN</div></td>
				    <td align="center" >&nbsp;</td>
				</tr>
				<tr>
				    <td rowspan="2" align="center" >&nbsp;</td>
				    <td align="center"><div align="left">KAWASAN BERIKAT <?=$_SESSION['namapt']?></div></td>
				    <td rowspan="2" align="center" >&nbsp;</td>
				</tr>
				<tr>
				    <td align="center" ><div align="left">LAPORAN PENGELUARAN BARANG PER DOKUMEN PABEAN</div></td>
				</tr>
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
	                <td style="font-weight: bold; text-align:center;" rowspan="2">No</td>
	                <td style="font-weight: bold; text-align:center;" rowspan="2">Jenis Dokumen</td>
	                <td style="font-weight: bold; text-align:center;" colspan="2" >Dokumen Pabean</td>
	                <td style="font-weight: bold; text-align:center;" colspan="2" >Bukti/Dokumen Pengeluaran</td>
	                <td style="font-weight: bold; text-align:center;" rowspan="2">Pembeli/Peneriman</td>
	                <td style="font-weight: bold; text-align:center;" rowspan="2">Kode Barang</td>
	                <td style="font-weight: bold; text-align:center;" rowspan="2">Nama Barang</td>
	                <td style="font-weight: bold; text-align:center;" rowspan="2">Sat</td>
	                <td style="font-weight: bold; text-align:center;" rowspan="2">Jumlah</td>
	                <td style="font-weight: bold; text-align:center;" rowspan="2">Nilai Barang</td>
	            </tr>
	            <tr>                            
	                <td style="font-weight: bold; text-align:center;" >Nomor</td>
	                <td style="font-weight: bold; text-align:center;" >Tanggal</td>
	                <td style="font-weight: bold; text-align:center;" >Nomor</td>
	                <td style="font-weight: bold; text-align:center;" >Tanggal</td>
	            </tr>
	            
	            <?php
		            $no = 1;
		            $sql = "SELECT a.dokumenBC, a.nomorAju, a.nomorDaftar, a.tglDaftar,  a.customer, a.DO, a.tglDO,
										b.itemNo, b.detailName,  b.quantity, b.satuan, b.totalPrice, b.hsNumber, b.bruto, b.netto, b.volume
							FROM ac_pengeluaran a
							LEFT JOIN ac_pengeluaran_detail b on b.DO = a.DO                
		                    WHERE a.tglDaftar>='".$_GET['dari']."' AND a.tglDaftar<='".$_GET['sampai']."' ";
				            if($_GET['dokbc']!='') $sql .=" AND a.dokumenBC='".$_GET['dokbc']."'";  
		                    if($_GET['namabarang']!='') $sql .=" AND b.itemNo='".$_GET['kodebarang']."'"; 
		            $sql .=" Order By a.tglDaftar Asc";        
		            $data = $sqlLib->select($sql);
		            
		            foreach ($data as $row) 
		            {
		            	$t_qty +=$row['quantity'];
				        		$t_price +=$row['totalPrice'];
		            	?>
		                <tr>
		                    <td style="text-align:center;" ><?php echo $no ?></td>
		                    <td style="text-align:center;" >BC <?php echo trim($row['dokumenBC']) ?></td>
		                    <td style="text-align:center;" ><?php echo trim($row['nomorAju']) ?></td>
		                    <td style="text-align:center;" ><?php echo date("d-M-Y",strtotime($row['tglDaftar'])); ?></td>
		                    <td style="text-align:center;" ><?php echo trim($row['DO']) ?></td>
		                    <td style="text-align:center;" ><?php echo date("d-M-Y",strtotime($row['tglDO'])); ?></td>
		                    <td ><?php echo $row['customer'] ?></td>
		                    <td style="text-align:center;" ><?php echo $row['itemNo'] ?></td>
		                    <td><?php echo $row['detailName'] ?></td>
		                    <td style="text-align:center;" ><?php echo $row['satuan'] ?></td>
		                    <td style="text-align:right;" ><?php echo number_format($row['quantity']) ?></td>
		                    <td style="text-align:right;"><?php echo number_format($row['totalPrice']) ?></td>
		                </tr>
		                <?php $no++;
		            }
				?> 
				<tr style="font-weight: bold;">
	                <td colspan="10" style="text-align:center;">Grand Total</td>
	                <td style="text-align:right;"><?php echo number_format($t_qty) ?></td>
	                <td style="text-align:right;"><?php echo number_format($t_price) ?></td>
	            </tr>    
				       
			</table>
		</td>
	</tr>

	<tr>	
		<td>&nbsp;</td>
	</tr>
	<tr>	
		<td style="text-align:right;"><div style="margin-right: 200px;">Tangerang, <?php echo tgl_indo(date('Y-m-d')) ?></div></td>
	</tr>
	<tr>	
		<td style="text-align:right;"><div style="margin-right: 250px;">Nama PT</div></td>
	</tr>
</table>
</body>
</html>

<script type="text/javascript">
 window.onafterprint = window.close;
 window.print();
</script>
