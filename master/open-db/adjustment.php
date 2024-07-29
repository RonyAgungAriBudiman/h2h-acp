
<form method="post" id="form" autocomplete="off" enctype="multipart/form-data">  

<div class="form-group row ml-3">
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
</div>  

<input type="hidden" name="request_data" value="<?php echo $_POST['request_data']; ?>">
<input type="hidden" name="accessToken" value="<?php echo $accessToken?>">
<input type="hidden" name="session" value="<?php echo $session?>">
<input type="hidden" name="host" value="<?php echo $host?>">
</form>