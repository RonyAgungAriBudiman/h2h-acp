<?php

if ($_POST['simpan']) {

    $sql_cek = "SELECT UserID FROM ms_user WHERE UserID ='" . $_SESSION['userid'] . "' AND Password = '" . substr(md5($_POST['old_pass']), 0, 10) . "' "; //substr(md5($_POST['old_pass']), 1, 11)
    $data_cek = $sqlLib->select($sql_cek);
    if (COUNT($data_cek) > 0) {
        $sql_up = "UPDATE ms_user SET Password ='" . substr(md5($_POST['new_pass']), 0, 10) . "' 
                        WHERE UserID ='" . $_SESSION['userid'] . "' "; //substr(md5($_POST['new_pass']), 1, 11)
        $run_up = $sqlLib->update($sql_up);
        if ($run_up == "1") {
            header("location:signout.php");
        }
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
                <div class="card-header mb-0">
                    <h4></h4>
                </div>


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
                    <div class="form-group">
                        <label>Old Password <?php echo $_SESSION['userid']; ?></label>
                        <input type="password" name="old_pass" class="form-control" required="required" value="<?php echo $_POST["old_pass"] ?>">

                    </div>
                    <div class="form-group">
                        <label>New Password</label>
                        <input type="password" name="new_pass" class="form-control" required="required" value="<?php echo $_POST["new_pass"] ?>">

                    </div>

                </div>
                <div class="card-footer text-right">
                    <button type="reset" name="batal" class="btn btn-danger">Cancel</button>
                    <input type="submit" class="btn btn-primary" name="simpan" Value="Save">
                </div>


            </form>
        </div>
    </div>
</div>