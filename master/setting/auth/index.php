<?php
if(isset($_POST['simpan']))
{
    
    $cekbox     = $_POST["cekbox"]; 
    //echo $cekbox;
    $sql_del    ="DELETE FROM ms_auth WHERE UserID ='".$_POST['user']."'  AND Nav1 ='".$_POST['module']."'  ";
    $run_del    = $sqlLib->delete($sql_del);

    $sukses = false; $notif="";   
    for($i=1;$i<=$cekbox;$i++)
    {
        $cb = $_POST['chk'.$i];
        if ($cb !="")
        {
            
            $navid    = $_POST["navid".$i];
            $sql_save = "INSERT INTO ms_auth (NavID, UserID, Nav1) VALUES ('".$navid."','".$_POST['user']."' ,'".$_POST['module']."' )";
            $run      = $sqlLib->insert($sql_save);

           //$notif .=$sql_save;
            $sukses = true; 
        }
    }
    

    if($sukses)
    {
        $alert = '0'; 
        $note = "Process is successful!!";
    }
    else
    {
        $alert = '1'; 
        $note = "Process failed!!";
    }   

}       


?>

<div class="section-header">
    <h1><?php echo acakacak("decode", $_GET["p"]) ?> </h1>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <form method="post" id="form" autocomplete="off" enctype="multipart/form-data">
                <div class="form-group row mt-3">                    
                    <div class="col-sm-2 ml-3">
                        <select name="userid" class="form-control" onchange="submit();">
                            <option value="">-Select User-</option>
                            <?php
                            $sql_user = "SELECT UserID FROM ms_user  WHERE UserID !='' AND Aktif='1'  ";
                            if($_SESSION["admin"]!="7") $sql_user .= "AND UserID !='sa' ";
                            $data_user = $sqlLib->select($sql_user);
                            foreach ($data_user as $com) {
                            ?>
                                <option value="<?php echo $com['UserID'] ?>" <?php if ($_POST['userid'] == $com['UserID']) {
                                                                                    echo "selected";
                                                                                } ?>><?php echo $com['UserID'] ?></option>
                            <?php
                            }

                            ?>
                        </select>
                    </div>

                    <div class="col-sm-2">
                        <select name="nav" class="form-control" onchange="submit();">
                            <option value="">-Select Module-</option>
                            <?php
                            $sql_modul = "SELECT DISTINCT Nav1 FROM ms_nav WHERE Nav1 !='' ";
                            if($_SESSION["admin"]!="7") $sql_modul .= "AND Nav1 !='Ceisa 4.0' ";
                            $data_modul= $sqlLib->select($sql_modul); 
                            foreach ($data_modul as $nav)
                            {
                            ?>
                                <option value="<?php echo $nav['Nav1'] ?>" <?php if ($_POST['nav'] == $nav['Nav1']) {
                                                                                    echo "selected";
                                                                                } ?>><?php echo $nav['Nav1'] ?></option>
                            <?php
                            }

                            ?>
                        </select>
                    </div>
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
                    <div class="table-responsive">
                        <table class="table table-striped" id="table-1">
                            <thead>
                                <tr>
                                    <th class="text-center">
                                        #
                                    </th>
                                    <th>Module</th>
                                    <th>Menu</th>
                                    <th>Active</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                $no = 1;
                                $sql = "SELECT a.NavID, a.Nav1, a.Nav2 FROM ms_nav a WHERE a.Nav1 ='".$_POST['nav']."'";
                                $data = $sqlLib->select($sql);
                                foreach ($data as $row) {

                                    $sql_2 ="SELECT a.NavID, a.UserID FROM ms_auth a WHERE a.NavID = '".$row['NavID']."' AND a.UserID = '".$_POST['userid']."' ";
                                    $data_2=$sqlLib->select($sql_2);

                                ?>
                                    <tr>
                                        <td><?php echo $no ?></td>
                                        <td><?php echo $row['Nav1'] ?></td>
                                        <td><?php echo $row['Nav2'] ?></td>
                                        <td>
                                           <input type="checkbox" name="chk<?php echo $no ?>" <?php if($data_2[0]['NavID']!=""){ echo "checked"; } ?>>
                                            <input type="hidden" name="navid<?php echo $no ?>" value="<?php echo $row['NavID'] ?>">
                                        </td>
                                    </tr>
                                <?php $no++;
                                } ?>
                                

                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12"> 
                                <div class="form-group">
                                  
                                  <div class="col-sm-12">
                                    <input type="hidden" name="cekbox" value="<?php echo $no; ?>">  
                                    <input type="hidden" name="module" value="<?php echo $_POST['nav']; ?>">  
                                    <input type="hidden" name="user" value="<?php echo $_POST['userid']; ?>"> 
                                    <input type="submit" class="btn btn-primary" name="simpan"   Value="Save">
                                    <button type="reset" name="batal" class="btn btn-danger">Cancel</button>
                                  </div>
                                </div> 
                    </div>  
                </div>            
            </form>    
        </div>
    </div>
</div>