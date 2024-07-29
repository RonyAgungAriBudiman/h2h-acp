
<div class="section-header">
   <h1><?php echo acakacak("decode", $_GET["p"]) ?></h1>
</div>
<style>
	#parent {
		height: 600px;
	}

	th{
    background-color: #133b5c;
    color: rgb(241, 245, 179);
    
    text-align: center;
    font-weight: normal;
    font-size: 14px;
    outline: 0.7px solid black;
    border: 1.5px solid black;

	} 
	td{
		border-bottom: 1.5px solid black;
	}
	
</style>

<script>
	$(document).ready(function() {
        $("#fixTable").tableHeadFixer();
        $("#fixTable").tableHeadFixer({
            'foot': true,
            'head': false
        });
    });;
	<?php if($_POST['dari']=="") {
	$_POST['dari'] = date("Y-m-01");
	$_POST['sampai'] = date("Y-m-d"); 
}?>
</script>
<link rel="stylesheet" href="assets/css/jquery-ui.css" />
<!-- <script src="assets/js/jquery-1.12.4.js"></script> -->
<script src="assets/js/jquery-ui.js"></script> 
<div class="row">
    <div class="col-12">
        <div class="card">
        	<form method="post" id="form" autocomplete="off" enctype="multipart/form-data">
          		<div class="form-group row mt-3 mb-0">
               		<div class="col-sm-2  ml-3">
               			<div class="input-group">
				            <div class="input-group-prepend">
				                <div class="input-group-text">
				                    <i class="fas fa-calendar"></i>
				                </div>
				            </div>
                        	<input type="text" name="dari" value="<?php echo $_POST['dari']; ?>" class="form-control datepicker" readonly="readonly">
                        </div>	
                    </div>
                    <div class="col-sm-2 ">
                    	<div class="input-group">
				            <div class="input-group-prepend">
				                <div class="input-group-text">
				                    <i class="fas fa-calendar"></i>
				                </div>
				            </div>
                        	<input type="text" name="sampai" value="<?php echo $_POST['sampai']; ?>" class="form-control datepicker" readonly="readonly">
                        </div>	
                    </div>  

                 
					
 
                    <div class="col-sm-2">
                        <select name="dokbc" class="form-control">
                            <option value="">-Dokumen BC-</option>
                            <?php
                            $sql_com = "SELECT DISTINCT dokumenBC FROM ac_penerimaan  WHERE dokumenBC !='' ";
                            $data_com = $sqlLib->select($sql_com);
                            foreach ($data_com as $com) {
                            ?>
                                <option value="<?php echo $com['dokumenBC'] ?>" <?php if ($_POST['dokbc'] == $com['dokumenBC']) {
                                                                                    echo "selected";
                                                                                } ?>><?php echo $com['dokumenBC'] ?></option>
                            <?php
                            }

                            ?>
                        </select>
                    </div>

                   	<div class="col-sm-2">
                    	<input type="text" name="namabarang" id="namabarang" value="<?php echo $_POST['namabarang']; ?>"  class="form-control">
                    	<input type="hidden" name="kodebarang" id="kodebarang" value="<?php echo $_POST['kodebarang']; ?>"  class="form-control">
					</div>	


                    <div class="col-sm-3 ml-3">
                        <input type="submit" name="search" class="btn btn-primary" value="Search">

                         <a href="print/LapPemasukanBarang.php?dari=<?php echo $_POST['dari'] ?>&sampai=<?php echo $_POST['sampai'] ?>&dokbc=<?php echo $_POST['dokbc'] ?>&kodebarang=<?php echo $_POST['kodebarang'] ?>&namabarang=<?php echo $_POST['namabarang'] ?>" target="_blank">
                            <input type="button" class="btn btn-warning" name="cetak" Value="Cetak">
                        </a>

                        <a href="download/excel/LapPemasukanBarang.php?dari=<?php echo $_POST['dari']; ?>&sampai=<?php echo $_POST['sampai']; ?>&dokbc=<?php echo $_POST['dokbc'] ?>&kodebarang=<?php echo $_POST['kodebarang'] ?>&namabarang=<?php echo $_POST['namabarang'] ?>" target="_blank">
                            <input type="button" name="export" class="btn btn-success" value="Export"></a> 

                    </div> 
          		</div>
          	</form>	           
            
            <div class="card-body">
            	<div class="table-responsive">
					 <div id="parent">
						<table id="fixTable" class="table" >
					        <thead>
					            <tr >
					                <th style="background-color:#133b5c; color:#FFF; padding: 2px;" rowspan="2">No.</th>
					                <th style="background-color:#133b5c; color:#FFF; padding: 2px;" rowspan="2">Jenis Dokumen</th>
					                <th style="background-color:#133b5c; color:#FFF; padding: 2px;" colspan="2" style="text-align:center;">Dokumen Pabean</th>
					                <th style="background-color:#133b5c; color:#FFF; padding: 2px;" colspan="2" style="text-align:center;">Bukti Penerimaan Barang</th>
					                <th style="background-color:#133b5c; color:#FFF; padding: 2px;" rowspan="2">Pemasok/Pengirim</th>
					                <th style="background-color:#133b5c; color:#FFF; padding: 2px;" rowspan="2">Kode Barang</th>
					                <th style="background-color:#133b5c; color:#FFF; padding: 2px;" rowspan="2">Nama Barang</th>
					                <th style="background-color:#133b5c; color:#FFF; padding: 2px;" rowspan="2">Sat</th>
					                <th style="background-color:#133b5c; color:#FFF; padding: 2px;" rowspan="2">Jumlah</th>
					                <th style="background-color:#133b5c; color:#FFF; padding: 2px;" rowspan="2">Nilai Barang</th>
					                <!-- <th>HS Number&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
					                <th>Bruto&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
					                <th>Netto&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th> -->
					            </tr>
					            <tr>                            
					                <th style="background-color:#133b5c; color:#FFF;" rowspan="2">Nomor</th>
					                <th style="background-color:#133b5c; color:#FFF;" rowspan="2">Tanggal</th>
					                <th style="background-color:#133b5c; color:#FFF;" rowspan="2">Nomor</th>
					                <th style="background-color:#133b5c; color:#FFF;" rowspan="2">Tanggal</th>
					            </tr> 
					        </thead>
					        <tbody>
					            <?php

					            $no = 1;
					            $sql = "SELECT a.dokumenBC, a.nomorAju, a.nomorDaftar, a.tglDaftar,  a.vendor, b.receiveItem, b.receiveNumber, b.receiveDate, 
					                            b.itemId, b.detailName,  b.quantity, b.satuan, b.totalPrice, b.hsNumber, b.bruto, b.netto, b.volume
					                    FROM ac_penerimaan a
					                    LEFT JOIN ac_penerimaan_detail b on b.purchaseInvoice = a.purchaseInvoice                      
					                    WHERE a.tglDaftar>='".$_POST['dari']."' AND a.tglDaftar<='".$_POST['sampai']."' ";
					                     if($_POST['dokbc']!='') $sql .=" AND a.dokumenBC='".$_POST['dokbc']."'";  
					                    if($_POST['namabarang']!='') $sql .=" AND b.itemId='".$_POST['kodebarang']."'";  
					            $sql .=" Order By a.tglDaftar Asc";        
					            $data = $sqlLib->select($sql);
					            
					            foreach ($data as $row) 
					            {	
					            	$t_qty +=$row['quantity'];
					            	$t_price +=$row['totalPrice'];
					                ?>
					                <tr>
					                    <td><?php echo $no ?></td>
					                    <td>BC <?php echo trim($row['dokumenBC']) ?></td>
					                    <td><?php echo trim($row['nomorAju']) ?></td>
					                    <!-- <td><?php echo $row['nomorDaftar'] ?></td> -->
					                    <td><?php echo date("d-M-Y",strtotime($row['tglDaftar'])); ?></td>
					                    <td><?php echo trim($row['receiveNumber']) ?></td>
					                    <td><?php echo date("d-M-Y",strtotime($row['receiveDate'])); ?></td>
					                    <td><?php echo $row['vendor'] ?></td>
					                    <td><?php echo $row['itemId'] ?></td>
					                    <td><?php echo $row['detailName'] ?></td>
					                    <td><?php echo $row['satuan'] ?></td>
					                    <td><?php echo number_format($row['quantity']) ?></td>
					                    <td><?php echo number_format($row['totalPrice']) ?></td>
					                    <!-- <td><?php echo $row['hsNumber'] ?></td>
					                    <td><?php echo number_format($row['bruto']) ?></td>
					                    <td><?php echo number_format($row['netto']) ?></td> -->
					                </tr>
					                <?php $no++;
					            }  
					            

					            ?>
					                   

					        </tbody>
					        <tfoot>
					        	<tr style="background-color:#133b5c; color:#FFF; font-weight: bold;">
					                
					                <td colspan="10" style="text-align: center;">Grand Total</td>
					                
					                <td><?php echo number_format($t_qty) ?></td>
					                <td><?php echo number_format($t_price) ?></td>
					            </tr> 
					        </tfoot>
					    </table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>	


<script>
    $(document).ready(function() {
        var ac_config = {
            source: "json/barang.php",
            select: function(event, ui) {
                $("#kodebarang").val(ui.item.id);
                $("#namabarang").val(ui.item.namabarang);
            },
            focus: function(event, ui) {
                $("#kodebarang").val(ui.item.id);
                $("#namabarang").val(ui.item.namabarang);
            },
            minLength: 1
        };
        $("#namabarang").autocomplete(ac_config);
    });
</script> 