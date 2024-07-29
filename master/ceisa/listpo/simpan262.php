<?php

$_POST["seri"] = "1";
$sql_urut = "SELECT MAX(Urut) as Urut FROM BC_HEADER 
	                      WHERE KodeDokumen = '262' AND  YEAR(TanggalPernyataan) = '" . date("Y", strtotime($_POST['tanggalpernyataan'])) . "' ";
$data_urut = $sqlLib->select($sql_urut);
$urut = $data_urut[0]['Urut'] + 1;
$nomor = str_pad($urut, 6, '0', STR_PAD_LEFT);
$_POST["nomor"] = $nomor;
$_POST["urut"] = $urut;
$_POST["nomoraju"] =  '000' . $_POST['kodedokumenbc'] . '-' . substr($_POST['nomoridentitaspengusaha'], 0, 6) . '-' . date("Ymd", strtotime($_POST['tanggalpernyataan'])) . '-' . $_POST['nomor'];

$nomoraju = $_POST["nomoraju"];

$jmlrow = $_POST["jmlrow"];
$jmldok = $_POST["jmldok"];
$jmltarif = $_POST["jmltarif"];
$jmlimpor = $_POST["jmlimpor"];
$jmljam = $_POST['jmljam'];

$sql_header = "INSERT INTO BC_HEADER (
            Asuransi, BiayaTambahan, BiayaPengurang, Bruto, Cif,  Freight, HargaPenyerahan,
        	JabatanPernyataan, KodeDokumen, KodeKantor,  KodeTujuanPemasukan, KodeValuta, KotaPernyataan,  NamaPernyataan,
        	Ndpbm, Netto, NilaiBarang, NomorAju, TanggalPernyataan,NoPo,Urut,RecUser) VALUES (
        	'0','0','0','" . $_POST['bruto'] . "','" . $_POST['cif'] . "','" . $_POST['freight'] . "','" . $_POST['hargapenyerahan'] . "',
        	'" . $_POST['jabatanpernyataan'] . "','" . $_POST['kodedokumenbc'] . "','" . $_POST['kodekantor'] . "','" . $_POST['kodetujuanpemasukan'] . "','" . $_POST['kodevaluta'] . "','" . $_POST['kotapernyataan'] . "',
        	'" . $_POST['namapernyataan'] . "','" . $_POST['ndpbm'] . "', '" . $_POST['netto'] . "','" . $_POST['nilaibarang'] . "','" . $_POST['nomoraju'] . "','" . $_POST['tanggalpernyataan'] . "',
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
        if ($save_entitas_7 == "1") {
            //Pengirim
            $sql_entitas_9 = "INSERT INTO BC_ENTITAS (
	            		NomorAju,Seri,KodeEntitas,KodeJenisIdentitas,NomorIdentitas,NamaEntitas,AlamatEntitas,NibEntitas,
	            		KodeJenisApi,KodeStatus,NomorIjinEntitas,TanggalIjinEntitas,KodeNegara,NiperEntitas,RecUser) VALUES (
	                    '" . $nomoraju . "', '9', '9', '" . $_POST["kodejenisidentitaspengirim"] . "', '" . $_POST["nomoridentitaspengirim"] . "', 
	                    '" . $_POST["namaentitaspengirim"] . "', '" . $_POST["alamatentitaspengirim"] . "', '" . $_POST["nibentitaspengirim"] . "', '','" . $_POST["kodestatuspengirim"] . "',
	                    '" . $_POST["nomorijinentitaspengirim"] . "', '" . $_POST["tanggalijinentitaspengirim"] . "', '', '','" . $_SESSION["nama"] . "')";
            $save_entitas_9 = $sqlLib->insert($sql_entitas_9);
            if ($save_entitas_9 == "1") {
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
                                //jaminan
                                $isi_jaminan = 0;
                                for ($j = 1; $j <= $jmljam; $j++) {
                                    $seqj = $_POST["seqj" . $j];
                                    $kodejaminan = $_POST["kodejaminan" . $j];
                                    $nomorjaminan = $_POST["nomorjaminan" . $j];
                                    $tanggaljaminan = $_POST["tanggaljaminan" . $j];
                                    $nilaijaminan = $_POST["nilaijaminan" . $j];
                                    $penjamin = $_POST["penjamin" . $j];
                                    $tanggaljatuhtempo = $_POST["tanggaljatuhtempo" . $j];
                                    $nomorbpj = $_POST["nomorbpj" . $j];
                                    $tanggalbpj = $_POST["tanggalbpj" . $j];
                                    if ($kodejaminan != "") {
                                        $sql_jaminan = "INSERT INTO BC_JAMINAN (
                                            NomorAju,KodeKantor,KodeJaminan, NomorJaminan, TanggalJaminan, NilaiJaminan, Penjamin, TanggalJatuhTempo, NomorBpj, TanggalBpj,RecUser) VALUES (
                                            '" . $nomoraju . "', '" . $_POST['kodekantor'] . "', '" . $kodejaminan . "', '" . $nomorjaminan . "', '" . $tanggaljaminan . "',
                                            '" . $nilaijaminan . "','" . $penjamin . "','" . $tanggaljatuhtempo . "','" . $nomorbpj . "','" . $tanggalbpj . "','" . $_SESSION["nama"] . "')";
                                        $save_jaminan = $sqlLib->insert($sql_jaminan);
                                        if ($save_jaminan == "1") {
                                            $isi_jaminan++;
                                            $sql_deljam = "DELETE FROM BC_JAMINAN_TMP WHERE SeqJ ='" . $seqj . "' ";
                                            $run_deljam = $sqlLib->delete($sql_deljam);
                                        }
                                    }
                                }
                                if ($isi_jaminan > 0) {
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

                                        $jumlahsatuan = $_POST["jumlahsatuan" . $b];
                                        $kodesatuanbarang = $_POST["kodesatuanbarang" . $b];
                                        $jumlahkemasan = $_POST["jumlahkemasan" . $b];
                                        $kodejeniskemasan = $_POST["kodejeniskemasan" . $b];
                                        $kodeasalbarang = $_POST["kodeasalbarang" . $b];
                                        $kodenegara = $_POST["kodenegara" . $b];
                                        $netto_dt = $_POST["netto_dt" . $b];
                                        $cif = $_POST["cif" . $b];


                                        if ($hsnumber != "") {
                                            $sql_barang = "INSERT INTO BC_BARANG (
                                                                NomorAju, SeriBarang, Hs, KodeBarang, Uraian, Merek, Tipe, Ukuran, SpesifikasiLain,KodeAsalBarang,KodeNegaraAsal,
                                                                JumlahSatuan, KodeSatuan, KodeKemasan,JumlahKemasan, Netto, Cif, RecUser) VALUES (
                                                                    '" . $nomoraju . "','" . $b . "','" . $hsnumber . "','" . $kodebarang . "','" . $uraian . "', '" . $merk . "','" . $tipe . "','" . $ukuran . "','" . $spesifikasilain . "',
                                                                    '" . $kodeasalbarang . "','" . $kodenegara . "',
                                                                    '" . $jumlahsatuan . "', '" . $kodesatuanbarang . "','" . $kodejeniskemasan . "','" . $jumlahkemasan . "','" . $netto_dt . "','" . $cif . "','" . $_SESSION["nama"] . "')";
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
                                                                            '" . $nomoraju . "','" . $b . "','" . $kodepungutan . "','" . $kodetarif . "','" . $tarif . "', '" . $kodefasilitastarif . "', '" . $kodesatuanbarang . "',
                                                                            '" . $jumlahsatuan . "',
                                                                            '0','" . $nilaifasilitas . "','0','" . $tariffasilitas . "','" . $_SESSION["nama"] . "', '" . $c . "')";
                                                        $save_tarif = $sqlLib->insert($sql_tarif);
                                                        if ($save_tarif == "1") {
                                                            $sql_deltar = "DELETE FROM BC_BARANG_TARIF_TMP WHERE SeqBT ='" . $seqbt . "' ";
                                                            $run_deltar = $sqlLib->delete($sql_deltar);
                                                        }
                                                    }
                                                }

                                                //save bb
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
                                                                                        VALUES ('" . $nomoraju . "','" . $b . "','" . $d . "','" . $kodeasalbahanbaku . "','" . $hs . "','" . $kodebarang . "','" . $uraian . "',
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
                                                                                    VALUES ('" . $nomoraju . "','" . $b . "','" . $d . "','" . $kodeasalbahanbaku . "','" . $row_bbt_imp['KodePungutan'] . "','" . $row_bbt_imp['KodeTarif'] . "',
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
                                            }
                                        }
                                    }

                                    if ($isi_barang > 0) {
                                        //save pungutan
                                        $isi_pungutan = 0;
                                        $sql_sum_tarif = "SELECT KodeFasilitas, KodePungutan, SUM(NilaiBayar) as NilaiPungutan
                                                                            FROM BC_BAHAN_BAKU_TARIF
                                                                            WHERE NomorAju ='" . $nomoraju . "' 
                                                                            Group By KodeFasilitas, KodePungutan ";
                                        $data_sum_tarif = $sqlLib->select($sql_sum_tarif);
                                        foreach ($data_sum_tarif as $row_tarif) {
                                            $sql_save_pungutan = "INSERT INTO BC_PUNGUTAN ( NomorAju, KodeFasilitasTarif, KodeJenisPungutan, NilaiPungutan, NpwpBilling, RecUser) 
                                                                 VALUES ('" . $nomoraju . "', '" . $row_tarif['KodeFasilitas'] . "', '" . $row_tarif['KodePungutan'] . "', '" . $row_tarif['NilaiPungutan'] . "', '', '" . $_SESSION["nama"] . "')";
                                            $run_save_pungutan = $sqlLib->insert($sql_save_pungutan);
                                            if ($run_save_pungutan == "1") {
                                                $isi_pungutan++;
                                            }
                                        }
                                        if ($isi_pungutan > 0) {

                                            $sql_tmp1 = "DELETE FROM BC_DOKUMEN_TMP";
                                            $data_tmp1 = $sqlLib->delete($sql_tmp1);
                                            $sql_tmp2 = "DELETE FROM BC_BAHAN_BAKU_TMP";
                                            $data_tmp2 = $sqlLib->delete($sql_tmp2);
                                            $sql_tmp3 = "DELETE FROM BC_BARANG_TARIF_TMP";
                                            $data_tmp3 = $sqlLib->delete($sql_tmp3);
                                            $sql_tmp4 = "DELETE FROM BC_BAHAN_BAKU_TARIF_TMP";
                                            $data_tmp4 = $sqlLib->delete($sql_tmp4);
                                            $sql_tmp5 = "DELETE FROM BC_JAMINAN_TMP";
                                            $data_tmp5 = $sqlLib->delete($sql_tmp5);

                                            $alert = '0';
                                            $note = "Proses simpan berhasil!!";
                                            unset($_POST);
                                        } else {
                                            $sql_bbt = "DELETE FROM BC_BAHAN_BAKU_TARIF WHERE NomorAju = '" . $nomoraju . "'";
                                            $data_bbt = $sqlLib->delete($sql_bbt);
                                            $sql_bbk = "DELETE FROM BC_BAHAN_BAKU WHERE NomorAju = '" . $nomoraju . "'";
                                            $data_bbk = $sqlLib->delete($sql_bbk);
                                            $sql_brt = "DELETE FROM BC_BARANG_TARIF WHERE NomorAju = '" . $nomoraju . "'";
                                            $data_brt = $sqlLib->delete($sql_brt);
                                            $sql_brg = "DELETE FROM BC_BARANG WHERE NomorAju = '" . $nomoraju . "'";
                                            $data_brg = $sqlLib->delete($sql_brg);
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
                                            $note = "Proses simpan pungutan gagal!!";
                                        }
                                    } else {
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
                                } else {
                                    $sql_jam = "DELETE FROM BC_JAMINAN WHERE NomorAju = '" . $nomoraju . "'";
                                    $data_jam = $sqlLib->delete($sql_jam);
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
                            } else {
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
                        } else {
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
                    } else {
                        $sql_dok = "DELETE FROM BC_DOKUMEN WHERE NomorAju = '" . $nomoraju . "'";
                        $data_dok = $sqlLib->delete($sql_dok);
                        $sql_ent = "DELETE FROM BC_ENTITAS WHERE NomorAju = '" . $nomoraju . "'";
                        $data_ent = $sqlLib->delete($sql_ent);
                        $sql_hdr = "DELETE FROM BC_HEADER WHERE NomorAju = '" . $nomoraju . "'";
                        $data_hdr = $sqlLib->delete($sql_hdr);

                        $alert = '1';
                        $note = "Proses simpan pengangkut gagal!!";
                    }
                } else {
                    $sql_ent = "DELETE FROM BC_ENTITAS WHERE NomorAju = '" . $nomoraju . "'";
                    $data_ent = $sqlLib->delete($sql_ent);
                    $sql_hdr = "DELETE FROM BC_HEADER WHERE NomorAju = '" . $nomoraju . "'";
                    $data_hdr = $sqlLib->delete($sql_hdr);

                    $alert = '1';
                    $note = "Proses simpan dokumen gagal!!";
                }
            } else {
                $sql_ent = "DELETE FROM BC_ENTITAS WHERE NomorAju = '" . $nomoraju . "'";
                $data_ent = $sqlLib->delete($sql_ent);
                $sql_hdr = "DELETE FROM BC_HEADER WHERE NomorAju = '" . $nomoraju . "'";
                $data_hdr = $sqlLib->delete($sql_hdr);

                $alert = '1';
                $note = "Proses simpan entitas pengirim gagal!!";
            }
        } else {
            $sql_ent = "DELETE FROM BC_ENTITAS WHERE NomorAju = '" . $nomoraju . "'";
            $data_ent = $sqlLib->delete($sql_ent);
            $sql_hdr = "DELETE FROM BC_HEADER WHERE NomorAju = '" . $nomoraju . "'";
            $data_hdr = $sqlLib->delete($sql_hdr);

            $alert = '1';
            $note = "Proses simpan entitas pemilik gagal!!";
        }
    } else {
        $sql_ent = "DELETE FROM BC_ENTITAS WHERE NomorAju = '" . $nomoraju . "'";
        $data_ent = $sqlLib->delete($sql_ent);
        $sql_hdr = "DELETE FROM BC_HEADER WHERE NomorAju = '" . $nomoraju . "'";
        $data_hdr = $sqlLib->delete($sql_hdr);

        $alert = '1';
        $note = "Proses simpan entitas pengusaha gagal!!";
    }
} else {
    $alert = '1';
    $note = "Proses simpan header gagal!!";
}
