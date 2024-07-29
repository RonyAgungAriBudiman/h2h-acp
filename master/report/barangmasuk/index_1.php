<?php 

if ($_POST['dari'] == "") {
    $_POST['dari'] = date("Y-m-d");
}

if ($_POST['sampai'] == "") {
    $_POST['sampai'] = date("Y-m-d");
}

?>
 <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css"> 
<!-- <style type="text/css">
    div.dataTables_wrapper {
        width: 100%;
        margin: 0 auto;
    }
</style> -->
<div class="section-header">
   <h1><?php echo acakacak("decode", $_GET["p"]) ?></h1>
</div>

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

                    <div class="col-sm-3 ml-3">
                        <input type="submit" name="search" class="btn btn-info" value="Search">
                        <!-- <a href="download/excel/kreditrecords.php?dari=<?php echo $_POST['dari']; ?>&sampai=<?php echo $_POST['sampai']; ?>&companyid=<?php echo $_POST['companyid']; ?>" target="_blank">
                            <input type="button" name="export" class="btn btn-info" value="Export"></a> -->

                    </div> 
          		</div>
          	</form>	           
            
            <div class="card-body">
                <div class="table-responsive">

                    <!-- <table class="table table-striped" id="table-a"> -->
                    <table id="example" class="display nowrap" style="width: 100%;">	
                        <thead>
                            <tr style="background-color: #eeeeee;">
                                <th class="text-center" rowspan="2">No&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                <th rowspan="2">Jenis Dokumen&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
			                    <th colspan="2" style="text-align:center;">Dokumen Pabean&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                <th colspan="2" style="text-align:center;">Bukti Penerimaan Barang&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
			                    <th rowspan="2">Pemasok&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
			                    <th rowspan="2">Kode Barang&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
			                    <th rowspan="2">Nama Barang&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
			                    <th rowspan="2">Satuan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
			                    <th rowspan="2">Jumlah&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
			                    <th rowspan="2">Nilai Barang&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
			                    <!-- <th>HS Number&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
			                    <th>Bruto&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
			                    <th>Netto&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th> -->
                            </tr>
                            <tr style="background-color: #eeeeee;">
                                
                                <th>Nomor&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                <th>Tanggal&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                <th>Nomor&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                <th>Tanggal&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
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
                            $sql .=" Order By a.tglDaftar Asc";        
                            $data = $sqlLib->select($sql);
                            //echo $sql;
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
			                <tr style="background-color: #eeeeee; font-weight: bold;">
			                    <td>&nbsp;</td>
			                    <td>&nbsp;</td>
			                    <td>&nbsp;</td>
			                    <td>&nbsp;</td>
			                    <td>Grand Total</td>
			                    <td>&nbsp;</td>
			                    <td>&nbsp;</td>
			                    <td>&nbsp;</td>
			                    <td>&nbsp;</td>
			                    <td>&nbsp;</td>
			                    <td><?php echo number_format($t_qty) ?></td>
			                    <td><?php echo number_format($t_price) ?></td>
			                </tr>        

                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
  
    new DataTable('#example', {
    scrollX: true,
    paging: false,
    ordering   : false,
    scrollY: 100,
    bInfo:false,
    bFilter:false,
});
</script>