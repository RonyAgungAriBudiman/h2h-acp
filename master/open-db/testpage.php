
<form method="post" id="form" autocomplete="off" enctype="multipart/form-data">  

<input type="hidden" name="request_data" value="<?php echo $_POST['request_data']; ?>">
<input type="hidden" name="accessToken" value="<?php echo $accessToken?>">
<input type="hidden" name="session" value="<?php echo $session?>">
<input type="hidden" name="host" value="<?php echo $host?>">
<div class="form-group row mt-3 ml-3">
    <div class="col-sm-2 mt-1">
        <br>
        <input type="submit" class="btn btn-primary" name="req" Value="Request Data">
    </div>
</div>  
<div class="card-body">
<?php echo $jsonpage ?>
</div>	
</form>