
<?php 
 if(isset($_POST['reset'])){

 
 	$sql1="DELETE FROM ac_penerimaan";
 	$run1=$sqlLib->delete($sql1);

 	$sql2="DELETE FROM ac_penerimaan_detail";
 	$run2=$sqlLib->delete($sql2);
 	

 	$sql3="DELETE FROM ac_stock";
 	$run3=$sqlLib->delete($sql3);

 	
 	$sql4="DELETE FROM ac_daily_request";
 	$run4=$sqlLib->delete($sql4);

 	$sql5="DELETE FROM ac_adjustment";
 	$run5=$sqlLib->delete($sql5);

 	$sql6="DELETE FROM ac_pengeluaran";
 	$run6=$sqlLib->delete($sql6);

 	$sql7="DELETE FROM ac_pengeluaran_detail";
 	$run7=$sqlLib->delete($sql7);
 	
 	$sql8="DELETE FROM ac_po";
 	$run8=$sqlLib->delete($sql8);
	 
 	$sql9="DELETE FROM ac_po_detail";
 	$run9=$sqlLib->delete($sql9);	 
	 
 	$sql10="DELETE FROM ac_so";
 	$run10=$sqlLib->delete($sql10);
	 
 	$sql11="DELETE FROM ac_so_detail";
 	$run11=$sqlLib->delete($sql11);
 	
 	if ($run11 == "1") {
	 	$alert = '0';
	    $note = "Data Berhasil Dihapus!!";
	}
	else{
		$alert = '1';
	    $note = "Data Gagal Dihapus!!";
	}


 }
?>

<div class="section-header">
  <h1><?php echo acakacak("decode", $_GET["p"]) ?></h1>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <form method="post" id="form" autocomplete="off" enctype="multipart/form-data">
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

                	
					<input type="submit" name="reset" class="btn btn-danger"  Value="RESET DATA" onclick="alert('Anda Yakin Untuk Hapus Data!!.')">
                    
                </div>
                
            </form>
        </div>
    </div>
</div>