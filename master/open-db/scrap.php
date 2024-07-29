
<form method="post" id="form" autocomplete="off" enctype="multipart/form-data">  
<input type="hidden" name="request_data" value="<?php echo $_POST['request_data']; ?>">
<?php 
    $bulan = array("","januari","februari","maret","april","mei","juni","juli","agustus","september","oktober","november","desember");
    if($_POST["tahun"]=="")$_POST["tahun"] = date("Y");
    if($_POST["bulan"]=="")$_POST["bulan"] = date("m");
    $periode = $_POST['tahun']."-".$_POST['bulan']."-01";  
    $tglakhir = date("t",strtotime($periode)); 

?>
<div class="form-group row ml-3">
	<div class="col-sm-2">
        <select name="tahun" class="form-control" onchange="submit();">
            <option value="">-Year-</option>
            <?php
            for($i=2020; $i<=(date("Y")+1); $i++)
            {?>
                <option value="<?php echo $i?>" <?php if($_POST["tahun"]==$i){echo "selected";}?>><?php echo $i?></option>
            <?php }?>
        </select>
    </div> 
    <div class="col-sm-2">
        <select name="bulan" class="form-control" onchange="submit();">
            <option value="">-Month-</option>
            <?php
            for($i=1; $i<=12; $i++)
            {?>
                <option value="<?php echo $i?>" <?php if($_POST["bulan"]==$i){echo "selected";}?>>
                    <?php echo ucwords($bulan[$i])?></option>
            <?php }?>
        </select>                   
    </div> <!-- 
    <div class="col-sm-2">
    	<input type="submit" class="btn btn-primary" name="req" Value="Request Data">
	</div> -->

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
                    <th>Tanggal</th>
                    <th>Update</th>
                    <th>Aksi</th>
                </tr>                
            </thead>
            <tbody>
                <?php 
                $no = 1;
                for($tgl=1; $tgl<=$tglakhir; $tgl++)
                {
                    $jmldala =0;
                    $tanggal= $_POST['tahun']."-".$_POST['bulan']."-".$tgl;
                    $sql = "SELECT a.TglRequest, a.Recdate, a.Seq, a.RecUser
                                FROM ac_daily_request a
                                WHERE a.TglRequest='".$tanggal."' AND a.Gudang = 'Scrap' ";
                    $data = $sqlLib->select($sql);
                    if(COUNT($data)>0){
                        $tglupdate  = date("d-M-Y H:i:s", strtotime($data[0]['Recdate'])) ;
                        $jmldala = 1;
                    }else
                    {
                        $tglupdate  = "";
                        $jmldala = 0;
                    }


                    ?>
                    <tr>
                        <td><?php echo $no ?></td>
                        <td><?php echo date("d-M-Y", strtotime($tanggal)) ?></td>
                        <td><?php echo $tglupdate ?></td>
                        <td>
                            <form method="post" id="form" autocomplete="off" enctype="multipart/form-data">  
                                <input type="submit" name="req" <?php if($jmldala>0) { echo "Value='Tarik Ulang'"; echo "class='btn btn-success'"; } else { echo "Value='Tarik Data'"; echo "class='btn btn-primary'";} ?> >
                                <input type="hidden" name="tgldari" value="<?php echo $tanggal; ?>">
                                <input type="hidden" name="tahun" value="<?php echo $_POST['tahun']; ?>">
                                <input type="hidden" name="bulan" value="<?php echo $_POST['bulan']; ?>">
                                <input type="hidden" name="request_data" value="<?php echo $_POST['request_data']; ?>">
                                <input type="hidden" name="accessToken" value="<?php echo $accessToken?>">
                                <input type="hidden" name="session" value="<?php echo $session?>">
                                <input type="hidden" name="host" value="<?php echo $host?>">
                            </form>
                        </td>
                    </tr>
                    <?php $no++;
                }        
                ?>
            </tbody>    
        </table>
     </div>
</div>    