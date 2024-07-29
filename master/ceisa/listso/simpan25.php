<?php

$_POST["seri"] = "1";
$sql_urut = "SELECT MAX(Urut) as Urut FROM BC_HEADER 
	                      WHERE KodeDokumen = '25' AND  YEAR(TanggalPernyataan) = '" . date("Y", strtotime($_POST['tanggalpernyataan'])) . "' ";
$data_urut = $sqlLib->select($sql_urut);
$urut = $data_urut[0]['Urut'] + 1;
$nomor = str_pad($urut, 6, '0', STR_PAD_LEFT);
$_POST["nomor"] = $nomor;
$_POST["urut"] = $urut;
$_POST["nomoraju"] =  '0000' . $_POST['kodedokumenbc'] . '-' . substr($_POST['nomoridentitaspengusaha'], 0, 6) . '-' . date("Ymd", strtotime($_POST['tanggalpernyataan'])) . '-' . $_POST['nomor'];

$jmldok = $_POST["jmldok"];
$jmlrow = $_POST["jmlrow"];
$jmltarif = $_POST["jmltarif"];
$jmlimpor = $_POST["jmlimpor"];
$jmllokal = $_POST["jmllokal"];

$nomoraju = $_POST["nomoraju"];

$sql_header = "INSERT INTO BC_HEADER (
            NomorAju,KodeDokumen, Bruto,Cif,KodeJenisTpb,HargaPenyerahan,JabatanPernyataan,KodeKantor,Netto,KotaPernyataan,NamaPernyataan,Volume,TanggalPernyataan,
            KodeCaraBayar,KodeLokasiBayar,KodeTujuanPengiriman,KodeValuta,Ndpbm,NoPo,Urut,RecUser) VALUES (
	        '" . $nomoraju . "','25','" . $_POST["bruto"] . "','" . $_POST["cif"] . "','" . $_POST["kodejenistpb"] . "','" . $_POST["hargapenyerahan"] . "','" . $_POST["jabatanpernyataan"] . "',
	        '" . $_POST["kodekantor"] . "','" . $_POST["netto"] . "','" . $_POST["kotapernyataan"] . "','" . $_POST["namapernyataan"] . "','" . $_POST["volume"] . "',
	        '" . $_POST["tanggalpernyataan"] . "','" . $_POST["kodecarabayar"] . "','" . $_POST["kodelokasibayar"] . "','" . $_POST["kodetujuanpengiriman"] . "','" . $_POST["kodevaluta"] . "',
	        '" . $_POST["ndpbm"] . "','" . $_POST["noso"] . "', '" . $urut . "','" . $_SESSION["nama"] . "')";
$save_header = $sqlLib->insert($sql_header);
if($save_header=="1"){
	//Pengusaha
	$sql_entitas_3 = "INSERT INTO BC_ENTITAS (
        		NomorAju,Seri,KodeEntitas,KodeJenisIdentitas,NomorIdentitas,NamaEntitas,AlamatEntitas,NibEntitas,
        		KodeJenisApi,KodeStatus,NomorIjinEntitas,TanggalIjinEntitas,KodeNegara,NiperEntitas,RecUser) VALUES (
                '" . $nomoraju . "', '3', '3', '" . $_POST["kodejenisidentitaspengusaha"] . "', '" . $_POST["nomoridentitaspengusaha"] . "', 
                '" . $_POST["namaentitaspengusaha"] . "', '" . $_POST["alamatentitaspengusaha"] . "', '" . $_POST["nibentitaspengusaha"] . "', '', '',
                '" . $_POST["nomorijinentitaspengusaha"] . "', '" . $_POST["tanggalijinentitaspengusaha"] . "', '', '','" . $_SESSION["nama"] . "')";
	$save_entitas_3 = $sqlLib->insert($sql_entitas_3);
	if ($save_entitas_3 == "1") {
		//Pemilik
		$sql_entitas_7 = "INSERT INTO BC_ENTITAS (
            		NomorAju,Seri,KodeEntitas,KodeJenisIdentitas,NomorIdentitas,NamaEntitas,AlamatEntitas,NibEntitas,
            		KodeJenisApi,KodeStatus,NomorIjinEntitas,TanggalIjinEntitas,KodeNegara,NiperEntitas,RecUser) VALUES (
                    '" . $_POST["nomoraju"] . "', '7', '7', '" . $_POST["kodejenisidentitaspemilik"] . "', '" . $_POST["nomoridentitaspemilik"] . "', 
                    '" . $_POST["namaentitaspemilik"] . "', '" . $_POST["alamatentitaspemilik"] . "', '" . $_POST["nibentitaspemilik"] . "', '" . $_POST["kodejenisapipemilik"] . "',
                    '" . $_POST["kodestatuspemilik"] . "','" . $_POST["nomorijinentitaspemilik"] . "', '" . $_POST["tanggalijinentitaspengusaha"] . "', '', '','" . $_SESSION["nama"] . "')";
		$save_entitas_7 = $sqlLib->insert($sql_entitas_7);
		if ($save_entitas_7 == "1") {
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
                            $sql_deldok = "DELETE FROM BC_DOKUMEN_TMP WHERE SeqDokTmp ='".$seqdoktmp."' ";
                            $run_deldok = $sqlLib->delete($sql_deldok);
                        }
                    }
                }
                if ($isi_dok > 0) {
                	//Pengangkut
                    $sql_pengangkut = "INSERT INTO BC_PENGANGKUT (
                            NomorAju,Seri,KodeCaraAngkut,NamaPengangkut,NomorPengangkut,KodeBendera,CallSign,FlagAngkutPlb,RecUser) VALUES (
                            '" . $nomoraju . "','1','" . $_POST['kodecaraangkut'] . "','','','','','','" . $_SESSION["nama"] . "')";
                    $save_pengangkut = $sqlLib->insert($sql_pengangkut);
                    if ($save_pengangkut == "1") {
                    	//Kemasan
                        $sql_kemasan = "INSERT INTO BC_KEMASAN (
                            NomorAju, Seri, KodeKemasan, JumlahKemasan, Merek, RecUser) VALUES (
                            '" . $nomoraju . "', '1', '" . $_POST["kodejeniskemasan"] . "', '" . $_POST["jumlahkemasan"] . "', 
                            '" . $_POST["merkkemasan"] . "','" . $_SESSION["nama"] . "')";
                        $save_kemasan = $sqlLib->insert($sql_kemasan);
                        if ($save_kemasan == "1") {
                        	//kontainer
                            $sql_kontainer = "INSERT INTO BC_KONTAINER (
                                NomorAju, Seri, NomorKontiner, KodeUkuranKontainer, KodeJenisKontainer, KodeTipeKontainer, RecUser) VALUES (
                                '" . $nomoraju . "', '1', '" . $_POST["nomorkontainer"] . "', '" . $_POST['kodeukurankontainer'] . "',
                                 '" . $_POST['kodejeniskontainer'] . "', '" . $_POST['kodetipekontainer'] . "', '" . $_SESSION["nama"] . "')";
                            $save_kontainer = $sqlLib->insert($sql_kontainer);
                            if ($save_kontainer == "1") {
                            	//barang
                                $isi_barang = 0;
                                for ($b = 1; $b <= $jmlrow; $b++) {
                                    $hsnumber = $_POST["hsnumber" . $b];
                                    $kodebarang = $_POST["kdbarang" . $b];
                                    $uraian = $_POST["namabarang" . $b];
                                    $merk = $_POST["merk" . $b];
                                    $tipe = $_POST["tipe" . $b];
                                    $ukuran = $_POST["ukuran" . $b];
                                    $spesifikasilain = $_POST["spesifikasilain" . $b];

                                    $penggunaan = $_POST["penggunaan" . $b];
                                    $kategoribarang = $_POST["kategoribarang" . $b];
                                    $kondisibarang = $_POST["kondisibarang" . $b];
                                    $flag4tahun = $_POST["flag4tahun" . $b];
                                    if($flag4tahun<1) $flag4tahun="";
                                    $kodeperhitungan = $_POST["kodeperhitungan" . $b];

                                    $jumlahsatuan = $_POST["jumlahsatuan" . $b];
                                    $kodesatuanbarang = $_POST["kodesatuanbarang" . $b];
                                    $jumlahkemasan = $_POST["jumlahkemasan" . $b];
                                    $kodejeniskemasan = $_POST["kodejeniskemasan" . $b];
                                    $netto_dt = $_POST["netto_dt" . $b];
                                    $cif = $_POST["cif" . $b];
                                    $cif_rupiah = $_POST["ndpbm"] * $cif;
                                    $nilaipabean = $_POST["nilaipabean" . $b];
                                    $hargapenyerahan = $_POST["hargapenyerahan" . $b];
                                    $seribarangasal = 1;
                                    $seriizin = 0;
                                    if ($hsnumber != "") {
                                        $sql_barang = "INSERT INTO BC_BARANG (
                                            NomorAju, SeriBarang, Hs, KodeBarang, Uraian,KodePerhitungan, KodeDokumenAsal, Flag4tahun, SeriBarangAsal,SeriIzin,
                                            Merek, Tipe, Ukuran, KodeSatuan,JumlahSatuan,
                                            KodeKemasan,JumlahKemasan, Netto, Volume,SpesifikasiLain,
                                            KodeGunaBarang, KodeKategoriBarang, KodeKondisiBarang,
                                            Ndpbm,Cif,CifRupiah,HargaPenyerahan, RecUser) VALUES (
                                                '" . $nomoraju . "','" . $b . "','" . $hsnumber . "','" . $kodebarang . "','" . $uraian . "','" . $kodeperhitungan . "','','" . $flag4tahun . "','" . $seribarangasal . "',
                                                '" . $seriizin . "',
                                                '" . $merk . "','" . $tipe . "','" . $ukuran . "','" . $kodesatuanbarang . "','" . $jumlahsatuan . "',
                                                '" . $kodejeniskemasan . "','" . $jumlahkemasan . "','" . $netto_dt . "','" . $volume_dt . "','".$spesifikasilain."',
                                                '".$penggunaan."','".$kategoribarang."','".$kondisibarang."',
                                               	'" . $ndpbm . "','" . $cif . "','" . $cif_rupiah . "','" . $hargapenyerahan . "','" . $_SESSION["nama"] . "')";
                                        $save_barang = $sqlLib->insert($sql_barang);
										if ($save_barang == "1") {
                                            $isi_barang++;
                                            //save barang tarif
											for ($c = 1; $c <= $jmltarif; $c++) {
												$seqbt = $_POST["seqbt" . $c];
                                                $kodepungutan = $_POST["kodepungutan" . $c];
												$kodetarif = $_POST["kodetarif" . $c];
												$tarif = $_POST["tarif" . $c];
												$kodefasilitastarif = $_POST["kodefasilitastarif" . $c];
												if ($kodefasilitastarif != "1") {
													$tariffasilitas = 100;
													$nilaifasilitas = ($cifrp * $tarif) / $tariffasilitas;
												}
												if ($kodepungutan != '') {
													$sql_tarif = "INSERT INTO BC_BARANG_TARIF (
														NomorAju, SeriBarang, KodePungutan, KodeTarif, Tarif, KodeFasilitas, KodeSatuan, JumlahSatuan,
														NilaiBayar, NilaiFasilitas, NilaiSudahDilunasi, TarifFasilitas,  RecUser, Urut) VALUES (
														'" . $nomoraju . "','" . $b . "','" . $kodepungutan . "','" . $kodetarif . "','" . $tarif . "', '" . $kodefasilitastarif . "', '" . $kodesatuan . "','" . $jumlahsatuan . "',
														'0','" . $nilaifasilitas . "','0','" . $tariffasilitas . "','" . $_SESSION["nama"] . "', '" . $c . "')";
													$save_tarif = $sqlLib->insert($sql_tarif);
                                                    if($save_tarif=="1"){
                                                        $sql_deltar = "DELETE FROM BC_BARANG_TARIF_TMP WHERE SeqBT ='".$seqbt."' ";
                                                        $run_deltar = $sqlLib->delete($sql_deltar);
                                                    }
												}
											}
                                            //save bb import
                                            for ($d = 1; $d <= $jmlimpor; $d++) {
                                                $seqbb = $_POST["seqbb" . $d];
                                                $kodeasalbahanbaku = $_POST["kodeasalbahanbaku" . $d];
                                                $hs = $_POST["hs" . $d];
                                                $kodebarang = $_POST["kodebarang" . $d];
                                                $uraian = $_POST["uraian" . $d];
                                                $merek = $_POST["merek" . $d];
                                                $tipe = $_POST["tipe" . $d];
                                                $ukuran = $_POST["ukuran" . $d];
                                                $spesifikasilain = $_POST["spesifikasilain" . $d];
                                                $kodesatuan = $_POST["kodesatuan" . $d];
                                                $jumlahsatuan = $_POST["jumlahsatuan" . $d];
                                                $kodedokumenasal = $_POST["kodedokumenasal" . $d];
                                                $kodekantorasal = $_POST["kodekantorasal" . $d];
                                                $nomordaftarasal = $_POST["nomordaftarasal" . $d];
                                                $tanggaldaftarasal = $_POST["tanggaldaftarasal" . $d];
                                                $nomorajuasal = $_POST["nomorajuasal" . $d];
                                                $cif = $_POST["cif" . $d];
                                                $cifrupiah = $_POST["cifrupiah" . $d];
                                                $ndpbm = $_POST["ndpbm" . $d];
                                                $hargapenyerahan = $_POST["hargapenyerahan" . $d];

                                                $sql_bb_import = "INSERT INTO BC_BAHAN_BAKU (NomorAju, SeriBarang, SeriBahanBaku,KodeAsalBahanBaku,HS,KodeBarang,Uraian,Merek,Tipe,Ukuran,SpesifikasiLain,
                                                                        KodeSatuan, JumlahSatuan, KodeDokumenAsal,KodeKantorAsal,NomorDaftarAsal,TanggalDaftarAsal,NomorAjuAsal,Cif,CifRupiah,Ndpbm,HargaPenyerahan,
                                                                        SeriBarangAsal, SeriIzin, RecUser) 
                                                                    VALUES ('" . $nomoraju . "','" . $b . "','" . $seqbb . "','" . $kodeasalbahanbaku . "','" . $hs . "','" . $kodebarang . "','" . $uraian . "',
                                                                            '" . $merek . "','" . $tipe . "','" . $ukuran . "','" . $spesifikasilain . "','" . $kodesatuan . "','" . $jumlahsatuan . "',
                                                                            '" . $kodedokumenasal . "','" . $kodekantorasal . "','" . $nomordaftarasal . "','" . $tanggaldaftarasal . "','" . $nomorajuasal . "',
                                                                            '" . $cif . "','" . $cifrupiah . "','" . $ndpbm . "','" . $hargapenyerahan . "','" . $seribarangasal . "','" . $seriizin . "','" . $_SESSION["nama"] . "')";
                                                $save_bb_import = $sqlLib->insert($sql_bb_import);
                                                if($save_bb_import=="1"){
                                                    //save bahan baku tarif
                                                    $sql_bb_tarif_imp ="SELECT SeqBBT, SeqBB,KodePungutan, KodeTarif, Tarif, KodeFasilitas, NilaiBayar, TarifFasilitas
                                                                        FROM BC_BAHAN_BAKU_TARIF_TMP WHERE SeqBB ='".$seqbb."' Order By SeqBBT Asc  ";
                                                    $data_bb_tarif_imp=$sqlLib->select($sql_bb_tarif_imp);
                                                    foreach ($data_bb_tarif_imp as $row_bbt_imp) {
                                                        $sql = "INSERT INTO BC_BAHAN_BAKU_TARIF (NomorAju, SeriBarang, SeriBahanBaku, KodeAsalBahanBaku, KodePungutan, KodeTarif, Tarif, KodeFasilitas, TarifFasilitas, NilaiBayar, RecUser)
                                                                VALUES ('".$nomoraju."','" . $b . "','" . $seqbb . "','" . $kodeasalbahanbaku . "','" . $row_bbt_imp['KodePungutan'] . "','" . $row_bbt_imp['KodeTarif'] . "',
                                                                        '" . $row_bbt_imp['Tarif'] . "','" . $row_bbt_imp['KodeFasilitas'] . "','" . $row_bbt_imp['TarifFasilitas'] . "','" . $row_bbt_imp['NilaiBayar'] . "',
                                                                        '" . $_SESSION["nama"] . "')";
                                                        $run = $sqlLib->insert($sql);    
                                                        if($run=="1"){
                                                            //hapus bahan baku tarif tmp
                                                            $sql_delbbt = "DELETE FROM BC_BAHAN_BAKU_TARIF_TMP WHERE SeqBBT ='".$row_bbt_imp['SeqBBT']."' ";
                                                            $run_delbbt = $sqlLib->delete($sql_delbbt);
                                                        }         
                                                    }                    

                                                    //hapus bahan baku tmp
                                                    $sql_delbb = "DELETE FROM BC_BAHAN_BAKU_TMP WHERE SeqBB ='".$seqbb."' ";
                                                    $run_delbb = $sqlLib->delete($sql_delbb);
                                                    
                                                }

                                            }

                                            //save bb lokal
                                            for ($e = 1; $e <= $jmllokal; $e++) {
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

                                                $sql_bb_lokal = "INSERT INTO BC_BAHAN_BAKU (NomorAju, SeriBarang, SeriBahanBaku,KodeAsalBahanBaku,HS,KodeBarang,Uraian,Merek,Tipe,Ukuran,SpesifikasiLain,
                                                                        KodeSatuan, JumlahSatuan, KodeDokumenAsal,KodeKantorAsal,NomorDaftarAsal,TanggalDaftarAsal,NomorAjuAsal,Cif,CifRupiah,Ndpbm,HargaPenyerahan,
                                                                        SeriBarangAsal, SeriIzin, RecUser) 
                                                                    VALUES ('" . $nomoraju . "','" . $b . "','" . $seqbb . "','" . $kodeasalbahanbaku . "','" . $hs . "','" . $kodebarang . "','" . $uraian . "',
                                                                            '" . $merek . "','" . $tipe . "','" . $ukuran . "','" . $spesifikasilain . "','" . $kodesatuan . "','" . $jumlahsatuan . "',
                                                                            '" . $kodedokumenasal . "','" . $kodekantorasal . "','" . $nomordaftarasal . "','" . $tanggaldaftarasal . "','" . $nomorajuasal . "',
                                                                            '" . $cif . "','" . $cifrupiah . "','" . $ndpbm . "','" . $hargapenyerahan . "','" . $seribarangasal . "','" . $seriizin . "','" . $_SESSION["nama"] . "')";
                                                $save_bb_lokal = $sqlLib->insert($sql_bb_lokal);
                                                if($save_bb_lokal=="1"){
                                                    //save bahan baku tarif
                                                    $sql_bb_tarif_lok ="SELECT SeqBBT, SeqBB,KodePungutan, KodeTarif, Tarif, KodeFasilitas, NilaiBayar, TarifFasilitas
                                                                        FROM BC_BAHAN_BAKU_TARIF_TMP WHERE SeqBB ='".$seqbb."' Order By SeqBBT Asc  ";
                                                    $data_bb_tarif_lok=$sqlLib->select($sql_bb_tarif_lok);
                                                    foreach ($data_bb_tarif_lok as $row_bbt_lok) {
                                                        $sql = "INSERT INTO BC_BAHAN_BAKU_TARIF (NomorAju, SeriBarang, SeriBahanBaku, KodeAsalBahanBaku, KodePungutan, KodeTarif, Tarif, KodeFasilitas, TarifFasilitas, NilaiBayar, RecUser)
                                                                VALUES ('".$nomoraju."','" . $b . "','" . $seqbb . "','" . $kodeasalbahanbaku . "','" . $row_bbt_lok['KodePungutan'] . "','" . $row_bbt_lok['KodeTarif'] . "',
                                                                        '" . $row_bbt_lok['Tarif'] . "','" . $row_bbt_lok['KodeFasilitas'] . "','" . $row_bbt_lok['TarifFasilitas'] . "','" . $row_bbt_lok['NilaiBayar'] . "',
                                                                        '" . $_SESSION["nama"] . "')";
                                                        $run = $sqlLib->insert($sql);    
                                                        if($run=="1"){
                                                            //hapus bahan baku tarif tmp
                                                            $sql_delbbt = "DELETE FROM BC_BAHAN_BAKU_TARIF_TMP WHERE SeqBBT ='".$row_bbt_lok['SeqBBT']."' ";
                                                            $run_delbbt = $sqlLib->delete($sql_delbbt);
                                                        }         
                                                    }                    

                                                    //hapus bahan baku tmp
                                                    $sql_delbb = "DELETE FROM BC_BAHAN_BAKU_TMP WHERE SeqBB ='".$seqbb."' ";
                                                    $run_delbb = $sqlLib->delete($sql_delbb);
                                                    
                                                }

                                            }
                                        }
                                    }
                                } 
                                if ($isi_barang > 0) {
                                	$sql_tmp1 = "DELETE FROM BC_DOKUMEN_TMP";
                                    $data_tmp1 = $sqlLib->delete($sql_tmp1);
                                    $sql_tmp2 = "DELETE FROM BC_BAHAN_BAKU_TMP";
                                    $data_tmp2 = $sqlLib->delete($sql_tmp2);
                                    $sql_tmp3 = "DELETE FROM BC_BARANG_TARIF_TMP";
                                    $data_tmp3 = $sqlLib->delete($sql_tmp3);
                                    $sql_tmp4 = "DELETE FROM BC_BAHAN_BAKU_TARIF_TMP";
                                    $data_tmp4 = $sqlLib->delete($sql_tmp4);

                                    $alert = '0';
                                    $note = "Proses simpan barang berhasil!!";

                                    
                                }
                                else{
                                	$sql_ner = "DELETE FROM BC_KONTAINER WHERE NomorAju = '" . $nomoraju . "'";
                                    $data_ner = $sqlLib->delete($sql_ner);
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
                                    $note = "Proses simpan barang gagal!!";
                                }       
                            }
                            else{
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
                                $note = "Proses simpan peti kemas gagal!!";
                            }
                        }
                        else{
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
                    }
                    else{
                    	$sql_dok = "DELETE FROM BC_DOKUMEN WHERE NomorAju = '" . $nomoraju . "'";
                        $data_dok = $sqlLib->delete($sql_dok);
                        $sql_ent = "DELETE FROM BC_ENTITAS WHERE NomorAju = '" . $nomoraju . "'";
                        $data_ent = $sqlLib->delete($sql_ent);
                        $sql_hdr = "DELETE FROM BC_HEADER WHERE NomorAju = '" . $nomoraju . "'";
                        $data_hdr = $sqlLib->delete($sql_hdr);

                        $alert = '1';
                        $note = "Proses simpan pengangkut gagal!!";
                    }
                }
                else{
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
				$note = "Proses simpan penerima gagal!!";
			}
		}
		else{
			$sql_ent = "DELETE FROM BC_ENTITAS WHERE NomorAju = '" . $nomoraju . "'";
			$data_ent = $sqlLib->delete($sql_ent);
			$sql_hdr = "DELETE FROM BC_HEADER WHERE NomorAju = '" . $nomoraju . "'";
			$data_hdr = $sqlLib->delete($sql_hdr);
			
			$alert = '1';
			$note = "Proses simpan pemilik gagal!!";
		}	
	}
	else{
		$sql_ent = "DELETE FROM BC_ENTITAS WHERE NomorAju = '" . $nomoraju . "'";
		$data_ent = $sqlLib->delete($sql_ent);
		$sql_hdr = "DELETE FROM BC_HEADER WHERE NomorAju = '" . $nomoraju . "'";
		$data_hdr = $sqlLib->delete($sql_hdr);

		$alert = '1';
		$note = "Proses simpan pengusaha gagal!!";
	}
}else{
	$alert = '1';
	$note = "Proses simpan gagal!!";
}
