<?php
$_POST["seri"] = "1";
$sql_urut = "SELECT MAX(Urut) as Urut FROM BC_HEADER 
	                      WHERE KodeDokumen = '27' AND  YEAR(TanggalPernyataan) = '" . date("Y", strtotime($_POST['tanggalpernyataan'])) . "' ";
$data_urut = $sqlLib->select($sql_urut);
$urut = $data_urut[0]['Urut'] + 1;
$nomor = str_pad($urut, 6, '0', STR_PAD_LEFT);
$_POST["nomor"] = $nomor;
$_POST["urut"] = $urut;
$_POST["nomoraju"] =  '0000' . $_POST['kodedokumenbc'] . '-' . substr($_POST['nomoridentitaspengusaha'], 0, 6) . '-' . date("Ymd", strtotime($_POST['tanggalpernyataan'])) . '-' . $_POST['nomor'];

$nomoraju = $_POST["nomoraju"];
$jmlrow = $_POST["jmlrow"];
$jmldok = $_POST["jmldok"];
$jmltarif = $_POST["jmltarif"];
$jmlimpor = $_POST["jmlimpor"];
$jmllokal = $_POST["jmllokal"];
$jmljam = $_POST['jmljam'];
$jmlkemasan = $_POST['jmlkemasan'];
$jmlkontainer = $_POST['jmlkontainer'];

$sql_header = "INSERT INTO BC_HEADER (
    Asuransi, BiayaTambahan, BiayaPengurang, Bruto, Cif,  Freight, HargaPenyerahan,
    JabatanPernyataan, KodeDokumen, KodeJenisTpb, KodeKantor, KodeKantorTujuan, KodeTps,
    KodeTujuanPengiriman, KodeTujuanTpb, KodeValuta, KotaPernyataan,  NamaPernyataan,
    Ndpbm, Netto, NilaiBarang,NilaiJasa,NomorAju, TanggalPernyataan, NoPo,Urut,RecUser) VALUES (
    '0','0','0','" . $_POST['bruto'] . "','" . $_POST['cif'] . "','" . $_POST['freight'] . "','" . str_replace(",", "", $_POST['hargapenyerahan']) . "',
    '" . $_POST['jabatanpernyataan'] . "','" . $_POST['kodedokumenbc'] . "', '".$_POST['kodejenistpb']."','" . $_POST['kodekantor'] . "', '".$_POST["kodekantortujuan"]."','".$_POST["kodetps"]."',
    '" . $_POST['kodetujuanpengiriman'] . "','" . $_POST['kodejenistpbtujuan'] . "','" . $_POST['kodevaluta'] . "','" . $_POST['kotapernyataan'] . "','" . $_POST['namapernyataan'] . "',
    '" . $_POST['ndpbm'] . "', '" . $_POST['netto'] . "','" . $_POST['nilaibarang'] . "','" . $_POST['nilaijasa'] . "','" . $_POST['nomoraju'] . "','" . $_POST['tanggalpernyataan'] . "',
    '" . $_POST["nopo"] . "', '" . $urut . "','" . $_SESSION["nama"] . "')";
$save_header = $sqlLib->insert($sql_header);
if ($save_header == "1") {
    //Pengusaha
    $sql_entitas_3 = "INSERT INTO BC_ENTITAS (
            NomorAju,Seri,KodeEntitas,KodeJenisIdentitas,NomorIdentitas,NamaEntitas,AlamatEntitas,NibEntitas,
            KodeJenisApi,KodeStatus,NomorIjinEntitas,TanggalIjinEntitas,KodeNegara,NiperEntitas,RecUser) VALUES (
            '" . $nomoraju . "', '3', '3', '" . $_POST["kodejenisidentitaspengusaha"] . "', '" . $_POST["nomoridentitaspengusaha"] . "', 
            '" . $_POST["namaentitaspengusaha"] . "', '" . $_POST["alamatentitaspengusaha"] . "', '" . $_POST["nibentitaspengusaha"] . "', '2', '" . $_POST["kodestatuspengusaha"] . "',
            '" . $_POST["nomorijinentitaspengusaha"] . "', '" . $_POST["tanggalijinentitaspengusaha"] . "', '', '','" . $_SESSION["nama"] . "')";
    $save_entitas_3 = $sqlLib->insert($sql_entitas_3);
    if ($save_entitas_3 == "1") {
        //Pemilik
        $sql_entitas_7 = "INSERT INTO BC_ENTITAS (
            NomorAju,Seri,KodeEntitas,KodeJenisIdentitas,NomorIdentitas,NamaEntitas,AlamatEntitas,NibEntitas,
            KodeJenisApi,KodeStatus,NomorIjinEntitas,TanggalIjinEntitas,KodeNegara,NiperEntitas,RecUser) VALUES (
            '" . $nomoraju . "', '7', '7', '" . $_POST["kodejenisidentitaspemilik"] . "', '" . $_POST["nomoridentitaspemilik"] . "', 
            '" . $_POST["namaentitaspemilik"] . "', '" . $_POST["alamatentitaspemilik"] . "', '" . $_POST["nibentitaspemilik"] . "', '2','" . $_POST["kodestatuspemilik"] . "',
            '" . $_POST["nomorijinentitaspemilik"] . "', '" . $_POST["tanggalijinentitaspengusaha"] . "', '', '','" . $_SESSION["nama"] . "')";
        $save_entitas_7 = $sqlLib->insert($sql_entitas_7);
        if ($save_entitas_7 == "1"){
            //Penerima
			$sql_entitas_8 = "INSERT INTO BC_ENTITAS (
                NomorAju,Seri,KodeEntitas,KodeJenisIdentitas,NomorIdentitas,NamaEntitas,AlamatEntitas,NibEntitas,
                KodeJenisApi,KodeStatus,NomorIjinEntitas,TanggalIjinEntitas,KodeNegara,NiperEntitas,RecUser) VALUES (
                '" . $_POST["nomoraju"] . "', '8', '8', '" . $_POST["kodejenisidentitaspenerima"] . "', '" . $_POST["nomoridentitaspenerima"] . "', 
                '" . $_POST["namaentitaspenerima"] . "', '" . $_POST["alamatentitaspenerima"] . "', '" . $_POST["nibentitaspenerima"] . "', '2',
                '" . $_POST["kodestatuspenerima"] . "','" . $_POST["nomorijinentitaspenerima"] . "', '" . $_POST["tanggalijinentitaspenerima"] . "', '', '','" . $_SESSION["nama"] . "')";
            $save_entitas_8 = $sqlLib->insert($sql_entitas_8);
            if ($save_entitas_8 == "1") {
                //dokumen
                $isi_dok = 0;
                for ($a = 1; $a <= $jmldok; $a++) {
                    $seqdoktmp = $_POST["seqdoktmp" . $a];
                    $kodedokumen = $_POST["kodedokumen" . $a];
                    $nomordokumen = $_POST["nomordokumen" . $a];
                    $tanggaldokumen = $_POST["tanggaldokumen" . $a];
                    if ($kodedokumen != "") {
                        $sql_dokumen = "INSERT INTO BC_DOKUMEN (
								NomorAju,Seri,KodeDokumen,NomorDokumen,TanggalDokumen,KodeFasilitas,KodeIjin,RecUser) VALUES (
								'" . $nomoraju . "', '" . $a . "', '" . $kodedokumen . "', '" . $nomordokumen . "', '" . $tanggaldokumen . "', '', '', '" . $_SESSION["nama"] . "')";
                        $save_dokumen = $sqlLib->insert($sql_dokumen);
                        if ($save_dokumen == "1") {
                            $isi_dok++;
                            $sql_deldok = "DELETE FROM BC_DOKUMEN_TMP WHERE SeqDokTmp ='" . $seqdoktmp . "' ";
                            $run_deldok = $sqlLib->delete($sql_deldok);
                        }
                    }
                }
                if ($isi_dok > 0) {
                    //Pengangkut
                    $sql_pengangkut = "INSERT INTO BC_PENGANGKUT (
                        NomorAju,Seri,KodeCaraAngkut,NamaPengangkut,NomorPengangkut,KodeBendera,CallSign,FlagAngkutPlb,RecUser) VALUES (
                        '" . $nomoraju . "','1','','".$_POST["namapengangkut"]."','".$_POST["nomorpengangkut"]."','','','','" . $_SESSION["nama"] . "')";
                    $save_pengangkut = $sqlLib->insert($sql_pengangkut);
                    if ($save_pengangkut == "1") {
                        //Kemasan
                        $isi_kemasan = 0;
                        for ($b = 1; $b <= $jmlkemasan; $b++) {                            
                            $seqkemasan = $_POST["seqkemasan" . $b];
                            $kodejeniskemasan = $_POST["kodejeniskemasan" . $b];
                            $jumlahkemasan = $_POST["jumlahkemasan" . $b];
                            $merek = $_POST["merek" . $b];
                            $sql_kemasan = "INSERT INTO BC_KEMASAN (
                                NomorAju, Seri, KodeKemasan, JumlahKemasan, Merek, RecUser) VALUES (
                                '" . $nomoraju . "', '" . $b . "', '" . $kodejeniskemasan . "', '" . $jumlahkemasan . "', 
                                '" . $merek . "','" . $_SESSION["nama"] . "')";
                            $save_kemasan = $sqlLib->insert($sql_kemasan);
                            if ($save_kemasan == "1") {
                                $isi_kemasan++;
                                $sql_delkem = "DELETE FROM BC_KEMASAN_TMP WHERE SeqKemasan ='" . $seqkemasan . "' ";
                                $run_delkem = $sqlLib->delete($sql_delkem);
                            }
                        }
                        if($isi_kemasan >0){
                            //kontainer
                            $isi_kontainer = 0;
                            for ($c = 1; $c <= $jmlkontainer; $c++) {
                                $seqkontainer = $_POST["seqkontainer" . $c];
                                $nomorkontiner = $_POST["nomorkontiner" . $c];
                                $kodeukurankontainer = $_POST["kodeukurankontainer" . $c];
                                $kodejeniskontainer = $_POST["kodejeniskontainer" . $c];
                                $kodetipekontainer = $_POST["kodetipekontainer" . $c];
                                $sql_kontainer = "INSERT INTO BC_KONTAINER (
                                NomorAju, Seri, NomorKontiner, KodeUkuranKontainer, KodeJenisKontainer, KodeTipeKontainer, RecUser) VALUES (
                                '" . $nomoraju . "', '" . $c . "', '" . $nomorkontiner . "', '" . $kodeukurankontainer . "',
                                 '" . $kodejeniskontainer . "', '" . $kodetipekontainer . "', '" . $_SESSION["nama"] . "')";
                                $save_kontainer = $sqlLib->insert($sql_kontainer);
                                if ($save_kontainer == "1") {
                                    $isi_kontainer++;
                                    $sql_delkon = "DELETE FROM BC_KONTAINER_TMP WHERE SeqKontainer ='" . $seqkontainer . "' ";
                                    $run_delkon = $sqlLib->delete($sql_delkon);
                                }
                            }
                            if($isi_kontainer>0){
                                //barang
                                $isi_barang = 0;
                                for ($d = 1; $d <= $jmlrow; $d++) {
                                    $hsnumber = $_POST["hsnumber" . $d];
                                    $kodebarang = $_POST["kdbarang" . $d];
                                    $uraian = $_POST["namabarang" . $d];
                                    $merk = $_POST["merk" . $d];
                                    $tipe = $_POST["tipe" . $d];
                                    $ukuran = $_POST["ukuran" . $d];
                                    $spesifikasilain = $_POST["spesifikasilain" . $d];

                                    $jumlahsatuan = $_POST["jumlahsatuan" . $d];
                                    $kodesatuanbarang = $_POST["kodesatuanbarang" . $d];
                                    $jumlahkemasan = $_POST["jumlahkemasan" . $d];
                                    $kodejeniskemasan = $_POST["kodejeniskemasan" . $d];
                                    $netto_dt = $_POST["netto_dt" . $d];
                                    $cif = $_POST["cif" . $d];
                                    $cifrupiah = $_POST["cifrupiah" . $d];
                                    $hargapenyerahan = $_POST["hargapenyerahan" . $d];
                                    $hargaperolehan = $_POST["hargaperolehan" . $d];
                                    $nilaijasa = $_POST["nilaijasa" . $d];
                                    if ($kodebarang != "") {
                                        $sql_barang = "INSERT INTO BC_BARANG (
                                                            NomorAju, SeriBarang, Hs, KodeBarang, Uraian, Merek, Tipe, Ukuran, SpesifikasiLain,
                                                            JumlahSatuan, KodeSatuan, KodeKemasan,JumlahKemasan, Netto, Cif, CifRupiah,HargaPenyerahan, HargaPerolehan, NilaiJasa, RecUser) VALUES (
                                                                '" . $nomoraju . "','" . $d . "','" . $hsnumber . "','" . $kodebarang . "','" . $uraian . "', '" . $merk . "','" . $tipe . "','" . $ukuran . "','" . $spesifikasilain . "',
                                                                '" . $jumlahsatuan . "', '" . $kodesatuanbarang . "','" . $kodejeniskemasan . "','" . $jumlahkemasan . "','" . $netto_dt . "','" . $cif . "',
                                                                '" . $cifrupiah . "','" . $hargapenyerahan . "','" . $hargaperolehan . "','" . $nilaijasa . "','" . $_SESSION["nama"] . "')";
                                        $save_barang = $sqlLib->insert($sql_barang);
                                        if ($save_barang == "1") {
                                            $isi_barang++;
                                            //save bb impor
                                            for ($e = 1; $e <= $jmlimpor; $e++) {
                                                $seqbb = $_POST["seqbb" . $e];
                                                $kodeasalbahanbaku = $_POST["kodeasalbahanbaku" . $e];
                                                $hs = $_POST["hs" . $e];
                                                $kodebarang = $_POST["kodebarang" . $e];
                                                $uraian = $_POST["uraian" . $e];
                                                $merek = $_POST["merek" . $e];
                                                $tipe = $_POST["tipe" . $e];
                                                $ukuran = $_POST["ukuran" . $e];
                                                $spesifikasilain = $_POST["spesifikasilain" . $e];
                                                $kodesatuan = $_POST["kodesatuan" . $e];
                                                $jumlahsatuan = $_POST["jumlahsatuan" . $e];
                                                $kodedokumenasal = $_POST["kodedokumenasal" . $e];
                                                $kodekantorasal = $_POST["kodekantorasal" . $e];
                                                $nomordaftarasal = $_POST["nomordaftarasal" . $e];
                                                $tanggaldaftarasal = $_POST["tanggaldaftarasal" . $e];
                                                $nomorajuasal = $_POST["nomorajuasal" . $e];
                                                $cif = $_POST["cif" . $e];
                                                $cifrupiah = $_POST["cifrupiah" . $e];
                                                $ndpbm = $_POST["ndpbm" . $e];
                                                $hargapenyerahan = $_POST["hargapenyerahan" . $e];

                                                $sql_bb_import = "INSERT INTO BC_BAHAN_BAKU (NomorAju, SeriBarang, SeriBahanBaku,KodeAsalBahanBaku,HS,KodeBarang,Uraian,Merek,Tipe,Ukuran,SpesifikasiLain,
                                                                                            KodeSatuan, JumlahSatuan, KodeDokumenAsal,KodeKantorAsal,NomorDaftarAsal,TanggalDaftarAsal,NomorAjuAsal,Cif,CifRupiah,Ndpbm,HargaPenyerahan,
                                                                                            SeriBarangAsal, SeriIzin, RecUser) 
                                                                                        VALUES ('" . $nomoraju . "','" . $d . "','" . $e . "','" . $kodeasalbahanbaku . "','" . $hs . "','" . $kodebarang . "','" . $uraian . "',
                                                                                                '" . $merek . "','" . $tipe . "','" . $ukuran . "','" . $spesifikasilain . "','" . $kodesatuan . "','" . $jumlahsatuan . "',
                                                                                                '" . $kodedokumenasal . "','" . $kodekantorasal . "','" . $nomordaftarasal . "','" . $tanggaldaftarasal . "','" . $nomorajuasal . "',
                                                                                                '" . $cif . "','" . $cifrupiah . "','" . $ndpbm . "','" . $hargapenyerahan . "','" . $seribarangasal . "','" . $seriizin . "','" . $_SESSION["nama"] . "')";
                                                $save_bb_import = $sqlLib->insert($sql_bb_import);                                                
                                                if ($save_bb_import == "1") {
                                                    //save bahan baku tarif
                                                    $sql_bb_tarif_imp = "SELECT SeqBBT, SeqBB,KodePungutan, KodeTarif, Tarif, KodeFasilitas, NilaiBayar, TarifFasilitas
                                                                            FROM BC_BAHAN_BAKU_TARIF_TMP WHERE SeqBB ='" . $seqbb . "' Order By SeqBBT Asc  ";
                                                    $data_bb_tarif_imp = $sqlLib->select($sql_bb_tarif_imp);
                                                    foreach ($data_bb_tarif_imp as $row_bbt_imp) {
                                                        $sql = "INSERT INTO BC_BAHAN_BAKU_TARIF (NomorAju, SeriBarang, SeriBahanBaku, KodeAsalBahanBaku, KodePungutan, KodeTarif, Tarif, KodeFasilitas, TarifFasilitas, NilaiBayar, 
                                                                                                KodeSatuan, RecUser)
                                                                                VALUES ('" . $nomoraju . "','" . $d . "','" . $e . "','" . $kodeasalbahanbaku . "','" . $row_bbt_imp['KodePungutan'] . "','" . $row_bbt_imp['KodeTarif'] . "',
                                                                                        '" . $row_bbt_imp['Tarif'] . "','" . $row_bbt_imp['KodeFasilitas'] . "','" . $row_bbt_imp['TarifFasilitas'] . "','" . $row_bbt_imp['NilaiBayar'] . "',
                                                                                        '" . $kodesatuan . "','" . $_SESSION["nama"] . "')";
                                                        $run = $sqlLib->insert($sql);
                                                        if ($run == "1") {
                                                            //hapus bahan baku tarif tmp
                                                            $sql_delbbt = "DELETE FROM BC_BAHAN_BAKU_TARIF_TMP WHERE SeqBBT ='" . $row_bbt_imp['SeqBBT'] . "' ";
                                                            $run_delbbt = $sqlLib->delete($sql_delbbt);
                                                        }
                                                    }

                                                    //hapus bahan baku tmp
                                                    $sql_delbb = "DELETE FROM BC_BAHAN_BAKU_TMP WHERE SeqBB ='" . $seqbb . "' ";
                                                    $run_delbb = $sqlLib->delete($sql_delbb);
                                                }    
                                            }  
                                            //save bb lokal
                                            for ($f = 1; $f <= $jmllokal; $f++) {
                                                $seqbb = $_POST["seqbb" . $f];
                                                $kodeasalbahanbaku = $_POST["kodeasalbahanbaku" . $f];
                                                $hs = $_POST["hs" . $f];
                                                $kodebarang = $_POST["kodebarang" . $f];
                                                $uraian = $_POST["uraian" . $f];
                                                $merek = $_POST["merek" . $f];
                                                $tipe = $_POST["tipe" . $f];
                                                $ukuran = $_POST["ukuran" . $f];
                                                $spesifikasilain = $_POST["spesifikasilain" . $f];
                                                $kodesatuan = $_POST["kodesatuan" . $f];
                                                $jumlahsatuan = $_POST["jumlahsatuan" . $f];
                                                $kodedokumenasal = $_POST["kodedokumenasal" . $f];
                                                $kodekantorasal = $_POST["kodekantorasal" . $f];
                                                $nomordaftarasal = $_POST["nomordaftarasal" . $f];
                                                $tanggaldaftarasal = $_POST["tanggaldaftarasal" . $f];
                                                $nomorajuasal = $_POST["nomorajuasal" . $f];
                                                $cif = $_POST["cif" . $f];
                                                $cifrupiah = $_POST["cifrupiah" . $f];
                                                $ndpbm = $_POST["ndpbm" . $f];
                                                $hargapenyerahan = $_POST["hargapenyerahan" . $f];
                                                $hargaperolehan = $_POST["hargaperola$hargaperolehan" . $f];

                                                $sql_bb_lokal = "INSERT INTO BC_BAHAN_BAKU (NomorAju, SeriBarang, SeriBahanBaku,KodeAsalBahanBaku,HS,KodeBarang,Uraian,Merek,Tipe,Ukuran,SpesifikasiLain,
                                                                                            KodeSatuan, JumlahSatuan, KodeDokumenAsal,KodeKantorAsal,NomorDaftarAsal,TanggalDaftarAsal,NomorAjuAsal,Cif,CifRupiah,Ndpbm,
                                                                                            HargaPenyerahan,SeriBarangAsal, SeriIzin, RecUser) 
                                                                                        VALUES ('" . $nomoraju . "','" . $d . "','" . $f . "','" . $kodeasalbahanbaku . "','" . $hs . "','" . $kodebarang . "','" . $uraian . "',
                                                                                                '" . $merek . "','" . $tipe . "','" . $ukuran . "','" . $spesifikasilain . "','" . $kodesatuan . "','" . $jumlahsatuan . "',
                                                                                                '" . $kodedokumenasal . "','" . $kodekantorasal . "','" . $nomordaftarasal . "','" . $tanggaldaftarasal . "','" . $nomorajuasal . "',
                                                                                                '" . $cif . "','" . $cifrupiah . "','" . $ndpbm . "','" . $hargapenyerahan . "','" . $seribarangasal . "','" . $seriizin . "','" . $_SESSION["nama"] . "')";
                                                $save_bb_lokal = $sqlLib->insert($sql_bb_lokal);                                                
                                                if ($save_bb_lokal == "1") {
                                                    //save bahan baku tarif
                                                    $sql_bb_tarif_lokal = "SELECT SeqBBT, SeqBB,KodePungutan, KodeTarif, Tarif, KodeFasilitas, NilaiBayar, TarifFasilitas
                                                                            FROM BC_BAHAN_BAKU_TARIF_TMP WHERE SeqBB ='" . $seqbb . "' Order By SeqBBT Asc  ";
                                                    $data_bb_tarif_lokal = $sqlLib->select($sql_bb_tarif_lokal);
                                                    foreach ($data_bb_tarif_lokal as $row_bbt_lok) {
                                                        $sql = "INSERT INTO BC_BAHAN_BAKU_TARIF (NomorAju, SeriBarang, SeriBahanBaku, KodeAsalBahanBaku, KodePungutan, KodeTarif, Tarif, KodeFasilitas, TarifFasilitas, NilaiBayar, 
                                                                                                KodeSatuan, RecUser)
                                                                                VALUES ('" . $nomoraju . "','" . $d . "','" . $f . "','" . $kodeasalbahanbaku . "','" . $row_bbt_lok['KodePungutan'] . "','" . $row_bbt_lok['KodeTarif'] . "',
                                                                                        '" . $row_bbt_lok['Tarif'] . "','" . $row_bbt_lok['KodeFasilitas'] . "','" . $row_bbt_lok['TarifFasilitas'] . "','" . $row_bbt_lok['NilaiBayar'] . "',
                                                                                        '" . $kodesatuan . "','" . $_SESSION["nama"] . "')";
                                                        $run = $sqlLib->insert($sql);
                                                        if ($run == "1") {
                                                            //hapus bahan baku tarif tmp
                                                            $sql_delbbt = "DELETE FROM BC_BAHAN_BAKU_TARIF_TMP WHERE SeqBBT ='" . $row_bbt_lok['SeqBBT'] . "' ";
                                                            $run_delbbt = $sqlLib->delete($sql_delbbt);
                                                        }
                                                    }

                                                    //hapus bahan baku tmp
                                                    $sql_delbb = "DELETE FROM BC_BAHAN_BAKU_TMP WHERE SeqBB ='" . $seqbb . "' ";
                                                    $run_delbb = $sqlLib->delete($sql_delbb);
                                                }    
                                            }    
                                        }
                                    }    
                                
                                }   
                                
                                if($isi_barang>0){
                                    $sql_tmp1 = "DELETE FROM BC_DOKUMEN_TMP";
                                    $data_tmp1 = $sqlLib->delete($sql_tmp1);
                                    $sql_tmp2 = "DELETE FROM BC_BAHAN_BAKU_TMP";
                                    $data_tmp2 = $sqlLib->delete($sql_tmp2);
                                    $sql_tmp3 = "DELETE FROM BC_BARANG_TARIF_TMP";
                                    $data_tmp3 = $sqlLib->delete($sql_tmp3);
                                    $sql_tmp4 = "DELETE FROM BC_BAHAN_BAKU_TARIF_TMP";
                                    $data_tmp4 = $sqlLib->delete($sql_tmp4);
                                    $sql_tmp5 = "DELETE FROM BC_KONTAINER_TMP";
                                    $data_tmp5 = $sqlLib->delete($sql_tmp5);
                                    $sql_tmp6 = "DELETE FROM BC_KEMASAN_TMP";
                                    $data_tmp6 = $sqlLib->delete($sql_tmp6);

                                    $alert = '0';
                                    $note = "Proses simpan berhasil!!";
                                    unset($_POST);

                                }else{
                                    $sql_brg = "DELETE FROM BC_BARANG WHERE NomorAju = '" . $nomoraju . "'";
                                    $data_brg = $sqlLib->delete($sql_brg);
                                    $sql_kem = "DELETE FROM BC_KEMASAN WHERE NomorAju = '" . $nomoraju . "'";
                                    $data_kem = $sqlLib->delete($sql_kem);
                                    $sql_kut = "DELETE FROM BC_PENGANGKUT WHERE NomorAju = '" . $nomoraju . "'";
                                    $data_kut = $sqlLib->delete($sql_kut);
                                    $sql_dok = "DELETE FROM BC_DOKUMEN WHERE NomorAju = '" . $nomoraju . "'";
                                    $data_dok = $sqlLib->delete($sql_dok);
                                    $sql_ent = "DELETE FROM BC_ENTITAS WHERE NomorAju = '" . $nomoraju . "'";
                                    $data_ent = $sqlLib->delete($sql_ent);
                                    $sql_hdr = "DELETE FROM BC_HEADER WHERE NomorAju = '" . $nomoraju . "'";
                                    $data_hdr = $sqlLib->delete($sql_hdr);

                                    $alert = '1';
                                    $note = "Proses simpan kontainer gagal!!";
                                }
                                    
                            }else{
                                $sql_kem = "DELETE FROM BC_KEMASAN WHERE NomorAju = '" . $nomoraju . "'";
                                $data_kem = $sqlLib->delete($sql_kem);
                                $sql_kut = "DELETE FROM BC_PENGANGKUT WHERE NomorAju = '" . $nomoraju . "'";
                                $data_kut = $sqlLib->delete($sql_kut);
                                $sql_dok = "DELETE FROM BC_DOKUMEN WHERE NomorAju = '" . $nomoraju . "'";
                                $data_dok = $sqlLib->delete($sql_dok);
                                $sql_ent = "DELETE FROM BC_ENTITAS WHERE NomorAju = '" . $nomoraju . "'";
                                $data_ent = $sqlLib->delete($sql_ent);
                                $sql_hdr = "DELETE FROM BC_HEADER WHERE NomorAju = '" . $nomoraju . "'";
                                $data_hdr = $sqlLib->delete($sql_hdr);

                                $alert = '1';
                                $note = "Proses simpan kontainer gagal!!";
                            }
                        }else{
                            $sql_kut = "DELETE FROM BC_PENGANGKUT WHERE NomorAju = '" . $nomoraju . "'";
                            $data_kut = $sqlLib->delete($sql_kut);
                            $sql_dok = "DELETE FROM BC_DOKUMEN WHERE NomorAju = '" . $nomoraju . "'";
                            $data_dok = $sqlLib->delete($sql_dok);
                            $sql_ent = "DELETE FROM BC_ENTITAS WHERE NomorAju = '" . $nomoraju . "'";
                            $data_ent = $sqlLib->delete($sql_ent);
                            $sql_hdr = "DELETE FROM BC_HEADER WHERE NomorAju = '" . $nomoraju . "'";
                            $data_hdr = $sqlLib->delete($sql_hdr);

                            $alert = '1';
                            $note = "Proses simpan kemasan gagal!!";
                        }
                    }else{
                        $sql_dok = "DELETE FROM BC_DOKUMEN WHERE NomorAju = '" . $nomoraju . "'";
                        $data_dok = $sqlLib->delete($sql_dok);
                        $sql_ent = "DELETE FROM BC_ENTITAS WHERE NomorAju = '" . $nomoraju . "'";
                        $data_ent = $sqlLib->delete($sql_ent);
                        $sql_hdr = "DELETE FROM BC_HEADER WHERE NomorAju = '" . $nomoraju . "'";
                        $data_hdr = $sqlLib->delete($sql_hdr);

                        $alert = '1';
                        $note = "Proses simpan pengangkut gagal!!";

                    }
                }else{
                    $sql_ent = "DELETE FROM BC_ENTITAS WHERE NomorAju = '" . $nomoraju . "'";
                    $data_ent = $sqlLib->delete($sql_ent);
                    $sql_hdr = "DELETE FROM BC_HEADER WHERE NomorAju = '" . $nomoraju . "'";
                    $data_hdr = $sqlLib->delete($sql_hdr);

                    $alert = '1';
                    $note = "Proses simpan dokumen gagal!!";
                }
            }
            else{
                $sql_ent = "DELETE FROM BC_ENTITAS WHERE NomorAju = '" . $nomoraju . "'";
                $data_ent = $sqlLib->delete($sql_ent);
                $sql_hdr = "DELETE FROM BC_HEADER WHERE NomorAju = '" . $nomoraju . "'";
                $data_hdr = $sqlLib->delete($sql_hdr);

                $alert = '1';
                $note = "Proses simpan entitas penerima gagal!!";
            }
        }else{
            $sql_ent = "DELETE FROM BC_ENTITAS WHERE NomorAju = '" . $nomoraju . "'";
            $data_ent = $sqlLib->delete($sql_ent);
            $sql_hdr = "DELETE FROM BC_HEADER WHERE NomorAju = '" . $nomoraju . "'";
            $data_hdr = $sqlLib->delete($sql_hdr);

            $alert = '1';
            $note = "Proses simpan entitas pemilik gagal!!";
        }
    }
    else{
        $sql_ent = "DELETE FROM BC_ENTITAS WHERE NomorAju = '" . $nomoraju . "'";
        $data_ent = $sqlLib->delete($sql_ent);
        $sql_hdr = "DELETE FROM BC_HEADER WHERE NomorAju = '" . $nomoraju . "'";
        $data_hdr = $sqlLib->delete($sql_hdr);

        $alert = '1';
        $note = "Proses simpan entitas pengusaha gagal!!";
    } 
}
else{
    $alert = '1';
    $note = "Proses simpan header gagal!!";
}