
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
		font-size: 12px;
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


<?php 

include "function/ceisa/kirim_bc40.php";
include "function/ceisa/kirim_bc23.php";
include "function/ceisa/kirim_bc25.php";
include "function/ceisa/kirim_bc41.php";
include "function/ceisa/kirim_bc27.php";
include "function/ceisa/kirim_bc261.php";
include "function/ceisa/kirim_bc262.php";
include "function/ceisa/kirim_bc20.php";
include "function/ceisa/kirim_bc30.php";
if($_POST['kirim']){

    $access_login = getlogin($username,$password);
    if($access_login=="success"){
    	$access_token = gettoken($username,$password);
    	if($_POST['kode_dokumen']=="4.0"){
    		//echo "masuk";
            //$kirim = kirimbc40($username, $access_token, $_POST['nomor_aju'], $sqlLib);
            //echo $kirim;
    	}
        else if($_POST['kode_dokumen']=="2.3"){
            //echo "masuk";
            //$kirim = kirimbc23($username, $access_token, $_POST['nomor_aju'], $sqlLib);
            //echo $kirim;
        }
        else if($_POST['kode_dokumen']=="2.5"){
            //echo "masuk";
            //$kirim = kirimbc25($username, $access_token, $_POST['nomor_aju'], $sqlLib);
            //echo $kirim;
        }
        else if($_POST['kode_dokumen']=="4.1"){
            //echo "masuk";
            //$kirim = kirimbc41($username, $access_token, $_POST['nomor_aju'], $sqlLib);
            //echo $kirim;
        }

    	$alert = '0';
        $note = "Akses Ceisa 4.0 Berhasil";
    }else{
    	$alert = '1';
        $note = "Akses Ceisa 4.0 Gagal!!";
    }
/*
//update send mail
if($_POST['jenis']=="pemasukan"){
	$sql_up ="UPDATE ac_penerimaan SET kirimCeisa = '1' WHERE nomorAju = '".$_POST['nomor_aju']."'  AND receiveItem ='".$_POST['id']."'  ";
	$run_up =$sqlLib->update($sql_up);
	if($run_up=="1"){
            $alert = '0';
            $note = "Data berhasil dikirim";
        }else{
            $alert = '1';
            $note = "Gagal dikirim";
        }   

}elseif ($_POST['jenis']=="pengeluaran") {
	$sql_up ="UPDATE ac_pengeluaran SET kirimCeisa = '1' WHERE nomorAju = '".$_POST['nomor_aju']."' AND DO ='".$_POST['id']."'  ";
	$run_up =$sqlLib->update($sql_up);
	if($run_up=="1"){
            $alert = '0';
            $note = "Data berhasil dikirim ke Ceisa 4.0";
        }else{
            $alert = '1';
            $note = "Gagal dikirim";
        } 
}	
*/
	

}?>

<link rel="stylesheet" href="assets/css/jquery-ui.css" />
<!-- <script src="assets/js/jquery-1.12.4.js"></script>  -->
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
                            $sql_com = "SELECT dokumenBC 
										FROM (
												SELECT DISTINCT dokumenBC FROM ac_penerimaan  WHERE dokumenBC !=''
												UNION ALL

												SELECT DISTINCT dokumenBC FROM ac_pengeluaran  WHERE dokumenBC !=''
											  ) as tbl";
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
                    	<input type="text" placeholder="Nomor Aju" name="nomoraju" id="nomoraju" value="<?php echo $_POST['nomoraju']; ?>"  class="form-control">
					</div>	


                    <div class="col-sm-3 ml-3">
                        <input type="submit" name="search" class="btn btn-primary" value="Search">
                    </div> 
          		</div>
          	</form>	           
            	
            <div class="card-body">
            	<?php
                if ($alert == "0") {
                ?> <div class="form-group">
                        <div class="alert alert-success alert-dismissible show fade">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                <?php echo $note ?>
                            </div>
                        </div>
                    </div><?php } else if ($alert == "1") { ?>
                    <div class="form-group">
                        <div class="alert alert-danger alert-dismissible show fade">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                <?php echo $note ?>
                            </div>
                        </div>
                    </div><?php
                } ?>
            	<div class="table-responsive">
					 <div id="parent">
						<table id="fixTable" class="table" >
					        <thead>
					            <tr >
					                <th style="background-color:#133b5c; color:#FFF; padding: 2px;" >No.</th>
					                <th style="background-color:#133b5c; color:#FFF; padding: 2px;" >No Pengajuan</th>
					                <th style="background-color:#133b5c; color:#FFF; padding: 2px;" >Dokumen</th>
					                <th style="background-color:#133b5c; color:#FFF; padding: 2px;" >Tanggal Daftar</th>
					                <th style="background-color:#133b5c; color:#FFF; padding: 2px;" ></th>
					            </tr>
					            
					        </thead>
					        <tbody>
					            <?php

					            $no = 1;
					            $sql = "SELECT dokumenBC, nomorAju, tglDaftar, nomorDaftar, kirimCeisa, jenis , ID 
										FROM (
												SELECT DISTINCT dokumenBC, nomorAju, tglDaftar, nomorDaftar, kirimCeisa, 'pemasukan' as jenis, receiveItem as ID  
												FROM ac_penerimaan  
												WHERE dokumenBC !='' AND tglDaftar>='".$_POST['dari']."' AND tglDaftar<='".$_POST['sampai']."'
												UNION ALL

												SELECT DISTINCT dokumenBC, nomorAju, tglDaftar, nomorDaftar, kirimCeisa, 'pengeluaran' as jenis, DO as ID    
												FROM ac_pengeluaran  
												WHERE dokumenBC !='' AND tglDaftar>='".$_POST['dari']."' AND tglDaftar<='".$_POST['sampai']."'
											  ) as tbl	";     
					            $data = $sqlLib->select($sql);
					            
					            foreach ($data as $row) 
					            {	
					            	$t_qty +=$row['quantity'];
					            	$t_price +=$row['totalPrice'];
					                ?>
					                <tr style="color:#000;">
					                    <td style="text-align: center;"><?php echo $no ?></td>
					                    <td style="text-align: center;"><?php echo trim($row['nomorAju']) ?></td>
					                    <td style="text-align: center;">BC <?php echo trim($row['dokumenBC']) ?></td>
					                    <td style="text-align: center;"><?php echo date("d-M-Y",strtotime($row['tglDaftar'])); ?></td>
					                    <td style="text-align: center;">
					                    	<?php if($row['kirimCeisa']!="1") {?>
					                    	<form method="post" id="form_detail" autocomplete="off" enctype="multipart/form-data">   
		                                        <input type="submit" class="btn btn-primary" name="kirim" value="Kirim">
		                                        <input type="hidden" name="jenis" class="form-control" value="<?php echo $row['jenis'] ?>">
		                                        <input type="hidden" name="id" class="form-control" value="<?php echo $row['ID'] ?>">
		                                        <input type="hidden" name="nomor_aju" class="form-control" value="<?php echo $row['nomorAju'] ?>">
		                                        <input type="hidden" name="kode_dokumen" class="form-control" value="<?php echo $row['dokumenBC'] ?>">
                                                <input type="hidden" name="sampai" class="form-control" value="<?php echo $_POST['sampai'] ?>">
                                                <input type="hidden" name="dari" class="form-control" value="<?php echo $_POST['dari'] ?>">
		                                    </form> 
		                                    <?php } ?>
					                    </td>
					                </tr>
					                <?php $no++;
					            }  
					            

					            ?>
					                   

					        </tbody>
					        
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
            source: "json/nomoraju.php",
            select: function(event, ui) {
                $("#nomoraju").val(ui.item.id);
                //$("#namabarang").val(ui.item.namabarang);
            },
            focus: function(event, ui) {
                $("#nomoraju").val(ui.item.id);
                //$("#namabarang").val(ui.item.namabarang);
            },
            minLength: 1
        };
        $("#nomoraju").autocomplete(ac_config);
    });
</script> 