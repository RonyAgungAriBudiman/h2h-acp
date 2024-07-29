<form method="post" id="form" autocomplete="off" enctype="multipart/form-data">  
<input type="hidden" name="request_data" value="<?php echo $_POST['request_data']; ?>">
<input type="hidden" name="accessToken" value="<?php echo $accessToken?>">
<input type="hidden" name="session" value="<?php echo $session?>">
<input type="hidden" name="host" value="<?php echo $host?>">
<div class="form-group row mt-3 ml-3">
    <div class="col-sm-2"> Dari Tanggal       
        <div class="input-group">
            <div class="input-group-prepend">
                <div class="input-group-text">
                    <i class="fas fa-calendar"></i>
                </div>
            </div>
            <input type="text" name="dari" readonly="readonly" value="<?php echo $_POST['dari']; ?>"  class="form-control datepicker">
        </div>
    </div>

    <div class="col-sm-2"> Sampai Tanggal       
        <div class="input-group">
            <div class="input-group-prepend">
                <div class="input-group-text">
                    <i class="fas fa-calendar"></i>
                </div>
            </div>
            <input type="text" name="sampai" readonly="readonly" value="<?php echo $_POST['sampai']; ?>"  class="form-control datepicker">
        </div>
    </div>

    <div class="col-sm-2 mt-1">
        <br>
        <input type="submit" class="btn btn-primary" name="req" Value="Request Data">
    </div>
</div>   


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
                    <th class="text-center">
                        No
                    </th>
                    <th>No PO</th>
                    <th>Tanggal PO</th>
                    <th>Vendor</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Harga</th>
                    <th>Qty</th>
                    <th>Satuan</th>
                    <th>Total Harga</th>
                </tr>                
            </thead>
            <tbody>
                <?php 
                
                $no = 1;
                $sql = "SELECT a.NoPO, a.TanggalPo, a.Vendor, a.Alamat, a.Subtotal, a.Tax2Amount, a.TotalAmount,
                            b.KdBarang, b.NamaBarang, b.Harga, b.Qty, b.ItemDiscPercent, b.ItemCost, b.Satuan, b.TotalHarga
                        FROM ac_po a
                        LEFT JOIN ac_po_detail b on b.NoPO = a.NoPO
                        WHERE a.TanggalPo>='".$_POST['dari']."' AND a.TanggalPo<='".$_POST['sampai']."' ";
                $data = $sqlLib->select($sql);

                foreach ($data as $row) 
                {
                    ?>
                    <tr>
                        <td><?php echo $no ?></td>
                        <td><?php echo $row['NoPO'] ?></td>
                        <td><?php echo date("d-M-Y",strtotime($row['TanggalPo'])); ?></td>
                        <td><?php echo $row['Vendor'] ?></td>
                        <td><?php echo $row['KdBarang'] ?></td>
                        <td><?php echo $row['NamaBarang'] ?></td>
                        <td><?php echo $row['Harga'] ?></td>
                        <td><?php echo $row['Qty'] ?></td>
                        <td><?php echo $row['Satuan'] ?></td>
                        <td><?php echo $row['TotalHarga'] ?></td>
                    </tr>
                    <?php $no++;
                }   
                     
                ?>
            </tbody>    
        </table>
     </div>
</div>                                      
</form>