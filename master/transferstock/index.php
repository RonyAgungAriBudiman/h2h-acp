<?php

$bulan = array("","januari","februari","maret","april","mei","juni","juli","agustus","september","oktober","november","desember");
    if($_POST["tahun"]=="")$_POST["tahun"] = date("Y");
    if($_POST["bulan"]=="")$_POST["bulan"] = date("m");

if(isset($_POST['proses'])){
	$periode    = $_POST["tahun"] . "-" . $_POST["bulan"] . "-01"; 
	$mm      = date("m", strtotime($periode));
	if($mm =="12"){
	 	$nextmm = '01';
	 	$nextyyyy = $_POST["tahun"] + 1  ;
	} else {
		$nextmm = $mm + 1;
	 	$nextyyyy = $_POST["tahun"] ;
	}

	$tanggal = $nextyyyy. "-" .$nextmm. "-01";


	//update saldo awal 0 di bulan berikut-nya
	$sql_up = "UPDATE ac_stock SET awal = '0' WHERE tanggal ='".$tanggal."' ";
	$run_up = $sqlLib->update($sql_up);

	$sukses = false;
	//data
	$sql = "SELECT  itemNo, itemName, unit1Name, itemCategory, lokasiGudang, SUM(awal + masuk - keluar + adjustment)  AS stockakhir
				FROM    ac_stock
				WHERE   MONTH(tanggal) = '".$mm."' AND YEAR(tanggal) = '".$_POST["tahun"]."'
				GROUP BY itemNo, itemName, unit1Name, itemCategory, lokasiGudang" ;
	$data= $sqlLib->select($sql);
	
	foreach ($data as $row) 
	{
		//cek
		$sql_cek ="SELECT TOP 1 itemNo FROM ac_stock
					WHERE tanggal = '".$tanggal."' AND itemNo ='".$row['itemNo']."' AND lokasiGudang ='".$row['lokasiGudang']."' ";
		$data_cek=$sqlLib->select($sql_cek);
		
		if(count($data_cek)>0){
			//update
			$sql_up = "UPDATE ac_stock SET awal	= '".$row['stockakhir']."',						
										recuser	= '".$_SESSION['userid']."',
										recdate = '".date("Y-m-d H:i:s")."' 
						WHERE tanggal ='".$tanggal."' AND itemNo ='".$row['itemNo']."' AND lokasiGudang ='".$row['lokasiGudang']."'	";
			$run_up = $sqlLib->update($sql_up);	
			if($run_up=="1"){
				$sukses = true;
			}						
		} else {
			$sql_in = "INSERT INTO ac_stock	(tanggal, itemNo, itemName, unit1Name, itemCategory, lokasiGudang, awal, masuk, keluar, adjustment, so, recuser, recdate)
						VALUES ('".$tanggal."','".$row['itemNo']."', '".$row['itemName']."', '".$row['unit1Name']."', '".$row['itemCategory']."' , '".$row['lokasiGudang']."' ,'".$row['stockakhir']."','0','0','0','0',
								'".$_SESSION['userid']."','".date("Y-m-d H:i:s")."' )" ;
			$run_in = $sqlLib->insert($sql_in);	
			if($run_in=="1"){
				$sukses = true;
			}		
		}
		
	}
	
	if($sukses){
		$alert = '0';
        $note = "Proses transfer stock berhasil!!";        
    }else{
        $alert = '1';
        $note = "Proses transfer stock gagal!!";
    }	
    	
}
if(isset($_POST['delete'])){

	$tanggal    = $_POST["tahun"] . "-" . $_POST["bulan"] . "-01"; 
	//update
	
	$sql_up = "UPDATE ac_stock SET awal	= '0',						
								recuser	= '".$_SESSION['userid']."',
								recdate = '".date("Y-m-d H:i:s")."' 
				WHERE tanggal ='".$tanggal."'	";
	$run_up = $sqlLib->update($sql_up);	
	if($run_up=="1"){
		$alert = '0';
        $note = "Proses hapus berhasil!!";        
    }else{
        $alert = '1';
        $note = "Proses hapus gagal!!";
    }	
    
}

?>   

<div class="section-header">
    <h1><?php echo acakacak("decode", $_GET["p"]) ?> <?php echo $tanggal ?></h1>
</div>


<div class="row">
    <div class="col-12">
        <div class="card">
        	<form method="post" id="form" autocomplete="off" enctype="multipart/form-data">
			    <div class="form-group row mt-3 ml-3">
			        <div class="col-sm-2">
			            <select name="tahun" class="form-control">
			                <option value="">-Year-</option>
			                <?php
			                for($i=2020; $i<=(date("Y")+1); $i++)
							{?>
							    <option value="<?php echo $i?>" <?php if($_POST["tahun"]==$i){echo "selected";}?>><?php echo $i?></option>
							<?php }?>
			            </select>
			        </div>
			        <div class="col-sm-2">
			            <select name="bulan" class="form-control">
			                <option value="">-Month-</option>
			                <?php
			                for($i=1; $i<=12; $i++)
							{?>
								<option value="<?php echo $i?>" <?php if($_POST["bulan"]==$i){echo "selected";}?>>
								    <?php echo ucwords($bulan[$i])?></option>
							<?php }?>
			            </select>
			        </div>
			        <div class="col-sm-2">
                        <input type="submit" name="proses" class="btn btn-primary" value="Transfer Stock">
                    </div>                        
			    </div>			    
			</form> 
			<div class="card-body">
			    <?php
			    if ($alert == "0") { ?> 
			        <div class="form-group">
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
			        <table class="table table-striped" id="table-1">
			            <thead>
			                <tr>
			                    <th class="text-center">No</th>
			                    <th>Periode</th>
			                    <th>Gudang</th>
			                    <th>Produksi</th>
			                    <th>Aksi</th>
			                </tr>                
			            </thead>
			            <tbody>
			                <?php 
			                $no = 1;
			                $sql="SELECT Year(a.tanggal) as Tahun, MONTH(a.tanggal) as Bulan, 
									(SELECT COALESCE(sum(b.awal),NULL,0)  FROM ac_stock b WHERE Year(b.tanggal) = Year(a.tanggal) AND MONTH(b.tanggal)= MONTH(a.tanggal) AND lokasiGudang ='Utama' ) as Gudang, 
									(SELECT  COALESCE(sum(c.awal),NULL,0) FROM ac_stock c WHERE Year(c.tanggal) = Year(a.tanggal) AND MONTH(c.tanggal)= MONTH(a.tanggal) AND lokasiGudang ='Produksi' ) as Produksi
									FROM ac_stock a
									WHERE YEAR(a.tanggal) = '".$_POST["tahun"]."' AND (
										(SELECT COALESCE(sum(b.awal),NULL,0)  FROM ac_stock b WHERE Year(b.tanggal) = Year(a.tanggal) AND MONTH(b.tanggal)= MONTH(a.tanggal) AND lokasiGudang ='Utama' ) >'0' OR
										(SELECT  COALESCE(sum(c.awal),NULL,0) FROM ac_stock c WHERE Year(c.tanggal) = Year(a.tanggal) AND MONTH(c.tanggal)= MONTH(a.tanggal) AND lokasiGudang ='Produksi' ) >'0' )
									GROUP BY Year(a.tanggal),  MONTH(a.tanggal)";
							$data=$sqlLib->select($sql);
							foreach ($data as $row) {
								?>
			                    <tr>
			                        <td><?php echo $no ?></td>
			                        <td><?php echo $row['Tahun']."".$row['Bulan'] ?></td>
			                        <td><?php echo $row['Gudang'] ?></td>
			                        <td><?php echo $row['Produksi'] ?></td>
			                        <td>
			                        	<form method="post" id="form" autocomplete="off" enctype="multipart/form-data">
			                        	<input type="submit" name="delete" class="btn btn-danger" value="Delete">
			                        	<input type="hidden" name="mm"  value="<?php echo $row['Bulan'] ?>">
			                        	<input type="hidden" name="yyyy"  value="<?php echo $row['Tahun'] ?>">
			                        	</form>
			                        </td>
			                    </tr>
			                    <?php   
			                    $no++;  
							}?>

			               
			            </tbody> 
			        </table>
			    </div>
			</div>            

        </div>
    </div>
</div>
