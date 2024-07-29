<ul class="sidebar-menu">

  <li <?php if ($_GET["m"] == "") {
        echo 'class="active"';
      } ?>><a class="nav-link" href="index.php"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>


  <?php
  $sql_nav1 = "SELECT DISTINCT(a.Nav1) AS Nav1, a.Icon, UrutMenu
              FROM ms_nav a WHERE a.Nav1 != '' AND (SELECT COUNT(b.NavID) FROM ms_auth b 
                                WHERE b.NavID = a.NavID AND b.UserID = '".$_SESSION["userid"]."') > 0
              ORDER BY a.UrutMenu ASC";
  $data_nav1 = $sqlLib->select($sql_nav1);
  foreach ($data_nav1 as $row_nav1) {
  ?>
    <li class="nav-item dropdown">
      <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="<?php echo $row_nav1['Icon'] ?>"></i> <span><?php echo $row_nav1['Nav1'] ?></span></a>
      <ul class="dropdown-menu">
        <?php
        $sql_nav2 = "SELECT a.Nav2, a.Path, 
                      CASE WHEN a.NavID ='4' THEN 'Laporan Pemasukan Barang Per Dokumen Pabean'
                            WHEN a.NavID ='5' THEN 'Laporan Pengluaran Barang Per Dokumen Pabean'
                            WHEN a.NavID ='13' THEN 'Kirim Dokumen Ke Ceisa 4.0' ELSE a.Nav2 END as Nav3
                        FROM ms_nav a WHERE a.Nav1 = '" . $row_nav1["Nav1"] . "' AND 
                                    (SELECT COUNT(b.NavID) FROM ms_auth b 
                                        WHERE b.NavID = a.NavID AND b.UserID = '".$_SESSION["userid"]."') > 0
                        ORDER BY a.UrutSub ASC";
        $data_nav2 = $sqlLib->select($sql_nav2);
        foreach ($data_nav2 as $row_nav2) { ?>
          <li><a class="nav-link" href="index.php?m=<?php echo acakacak("encode", $row_nav2['Path']) ?>&p=<?php echo acakacak("encode", $row_nav2['Nav3']) ?>" onsubmit="myFunction()"><?php echo $row_nav2['Nav2'] ?></a></li>

        <?php } ?>

      </ul>
    </li>
  <?php
  }
  ?>
</ul>