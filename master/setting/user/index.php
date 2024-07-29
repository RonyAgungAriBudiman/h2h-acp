<?php

if ($_POST["status"] == "") {
  $_POST["status"] = "1";
}

if ($_POST['nonaktif']) {
  $sql = "UPDATE ms_user SET Aktif = '0'
              WHERE UserID = '" . $_POST['userid'] . "'  ";
  $run = $sqlLib->update($sql);

  if ($run == "1") {
    $alert = '0';
    $note = "Proses Berhasil!!";
  } else {
    $alert = '1';
    $note = "Proses gagal!!";
  }
}

if ($_POST['aktifkan']) {
  $sql = "UPDATE ms_user SET Aktif = '1'
              WHERE UserID = '" . $_POST['userid'] . "'  ";
  $run = $sqlLib->update($sql);

  if ($run == "1") {
    $alert = '0';
    $note = "Proses Berhasil!!";
  } else {
    $alert = '1';
    $note = "Proses gagal!!";
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
        <div class="form-group row mt-3">
          <div class="col-sm-2 ml-3">
            <select name="status" class="form-control" onchange="submit();">
              <option value="1" <?php if ($_POST['status'] == "1") { echo "selected"; } ?>>Aktif</option>
              <option value="0" <?php if ($_POST['status'] == "0") { echo "selected"; } ?>>Tidak Aktif</option>                           
            </select>
          </div>
          <div class="col-sm-5">&nbsp;</div>   
          <div class="col-sm-4" style="float:right;">
            <a href="index.php?m=<?php echo acakacak("encode", "setting/user") ?>&sm=<?php echo acakacak("encode", "add") ?>&p=<?php echo acakacak("encode", "Input Data User") ?>">
              <button type="button" name="tambah" class="btn btn-info float-right"><i class="fa fa-plus"></i> User Baru</button>
            </a>
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
          <table class="table table-striped" id="table-1">
            <thead>
              <tr>
                <th class="text-center">
                  #
                </th>
                <th>UserID</th>
                <th>Nama</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              $sql = "SELECT a.UserID, a.Nama, a.Aktif, 
                      CASE WHEN a.Aktif='1' THEN 'Aktif' ELSE 'Tidak Aktif' END as Status
                        FROM ms_user a
                        WHERE a.UserID !='' ";
              if ($_POST["status"] != "") $sql .= " AND a.Aktif = '" . $_POST["status"] . "' ";
              if($_SESSION["admin"]!="7") $sql .= "AND a.UserID !='sa' ";
              $data = $sqlLib->select($sql);
              foreach ($data   as $row) {

              ?>
                <tr>
                  <td><?php echo $no ?></td>
                  <td><?php echo $row['UserID'] ?></td>
                  <td><?php echo $row['Nama'] ?></td>
                  <td><?php echo $row['Status'] ?></td>

                  <td>
                    <form method="post">

                      <?php if ($row['Aktif'] == "1") { ?>
                        <input type="submit" class="btn btn-danger" name="nonaktif" Value="Non Aktif">
                        <input type="hidden" name="userid" Value="<?php echo $row['UserID'] ?>">
                      <?php } else if ($row['Aktif'] == "0") { ?>

                        <input type="submit" class="btn btn-primary" name="aktifkan" Value="Aktifkan">
                        <input type="hidden" name="userid" Value="<?php echo $row['UserID'] ?>">
                      <?php } ?>

                      <a href="index.php?m=<?php echo acakacak("encode", "setting/user") ?>&sm=<?php echo acakacak("encode", "edit") ?>&p=<?php echo acakacak("decode", $_GET["p"]) ?>&userid=<?php echo $row["UserID"] ?>">
                        <button type="button" class="btn btn-success"><i class="fa fa-edit"> </i> Edit</button>
                      </a>
                    </form>
                  </td>
                </tr>
              <?php $no++;
              } ?>

            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>