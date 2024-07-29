<ul class="sidebar-menu">
    <li <?php if ($_GET["m"] == "") {
            echo 'class="active"';
        } ?>><a class="nav-link" href="index.php"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
    <li class="dropdown">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-cloud-download-alt"></i> <span>API Accurate</span></a>
        <ul class="dropdown-menu">
            <li><a class="nav-link" href="index.php?m=<?php echo acakacak("encode", "apiaccurate") ?>&p=<?php echo acakacak("encode", "API Accurate") ?>">API Accurate</a></li>
            <li><a class="nav-link" href="index.php?m=<?php echo acakacak("encode", "apiaccurate") ?>&p=<?php echo acakacak("encode", "API Accurate") ?>">Transfer Stock</a></li>
            <!--
            <li><a class="nav-link" href="index.php?m=<?php echo acakacak("encode", "oauth-callback") ?>">Oauth Callback</a></li>
            <li><a class="nav-link" href="index.php?m=<?php echo acakacak("encode", "read-db") ?>">Read DB</a></li>
            <li><a class="nav-link" href="index.php?m=<?php echo acakacak("encode", "open-db") ?>">Open DB</a></li>
        	-->

        </ul>
    </li>
    <!--
    <li class="dropdown">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-folder"></i> <span>Sales Order</span></a>
        <ul class="dropdown-menu">
            <li><a class="nav-link" href="index.php?m=<?php echo acakacak("encode", "so/dataso") ?>&p=<?php echo acakacak("encode", "Data SO") ?>">Data SO</a></li>
        </ul>
    </li>  -->
              

    <li class="dropdown">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-file-alt"></i> <span>Report BC</span></a>
        <ul class="dropdown-menu">
            <li><a class="nav-link" href="index.php?m=<?php echo acakacak("encode", "report/barangmasuk") ?>&p=<?php echo acakacak("encode", "Laporan Pemasukan Barang Per Dokumen Pabean") ?>">Laporan Pemasukan Barang</a></li>
            <li><a class="nav-link" href="index.php?m=<?php echo acakacak("encode", "report/barangkeluar") ?>&p=<?php echo acakacak("encode", "Laporan Pengluaran Barang Per Dokumen Pabean") ?>">Laporan Pengluaran Barang</a></li>
            <li><a class="nav-link" href="index.php?m=<?php echo acakacak("encode", "report/bahanbaku") ?>&p=<?php echo acakacak("encode", "Laporan Bahan Baku Dan Penolong") ?>">Laporan Bahan Baku</a></li>
            <li><a class="nav-link" href="index.php?m=<?php echo acakacak("encode", "report/wip") ?>&p=<?php echo acakacak("encode", "Laporan Posisi Barang Dalam Proses (WIP)") ?>">Laporan WIP</a></li>
            <li><a class="nav-link" href="index.php?m=<?php echo acakacak("encode", "report/barangjadi") ?>&p=<?php echo acakacak("encode", "Laporan Barang Jadi") ?>">Laporan Barang Jadi</a></li>            
            <li><a class="nav-link" href="index.php?m=<?php echo acakacak("encode", "report/mesin") ?>&p=<?php echo acakacak("encode", "Laporan Mesin Dan Peralatan Kantor") ?>">Laporan Mesin</a></li>                       
            <li><a class="nav-link" href="index.php?m=<?php echo acakacak("encode", "report/scrap") ?>&p=<?php echo acakacak("encode", "Laporan Scrap") ?>">Laporan Scrap</a></li>
        </ul>
    </li>  

      

     <!--    
    <li class="dropdown">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-cog"></i> <span>Setting</span></a>
        <ul class="dropdown-menu">
            <li><a class="nav-link" href="index.php?m=<?php echo acakacak("encode", "setting/profilperusahaan") ?>&p=<?php echo acakacak("encode", "Profil Perusahaan") ?>">Profil Perusahaan</a></li>
        </ul>
    </li> -->
</ul>