<?php

$_POST["seri"] = "1";
$sql_urut = "SELECT MAX(Urut) as Urut FROM BC_HEADER 
	                      WHERE KodeDokumen = '30' AND  YEAR(TanggalPernyataan) = '" . date("Y", strtotime($_POST['tanggalpernyataan'])) . "' ";
$data_urut = $sqlLib->select($sql_urut);
$urut = $data_urut[0]['Urut'] + 1;
$nomor = str_pad($urut, 6, '0', STR_PAD_LEFT);
$_POST["nomor"] = $nomor;
$_POST["urut"] = $urut;
$_POST["nomoraju"] =  '0000' . $_POST['kodedokumenbc'] . '-' . substr($_POST['nomoridentitaseksportir'], 0, 6) . '-' . date("Ymd", strtotime($_POST['tanggalpernyataan'])) . '-' . $_POST['nomor'];

$kodedokumen = $_POST["kodedokumenbc"];
$nomoraju = $_POST["nomoraju"];
$asuransi = $_POST["asuransi"];
$bruto = $_POST["bruto"];
$flagcurah = $_POST["flagcurah"];
$flagmigas = $_POST["flagmigas"];
$fob = $_POST["fob"];
$freight = $_POST["freight"];
$jabatanttd = $_POST["jabatanpernyataan"];
$kodeasuransi = $_POST["kodeasuransi"];
$kodecarabayar = $_POST["kodecarabayar"];
$kodejenisekspor = $_POST["kodejenisekspor"];
$kodekantor = $_POST["kodekantor"];
$kodekantorekspor = $_POST["kodekantorekspor"];
$kodekantormuat = $_POST["kodekantormuat"];
$kodekategoriekspor = $_POST["kodekategoriekspor"];
$kotattd = $_POST["kotapernyataan"];
$namattd = $_POST["namapernyataan"];
$tanggalttd = $_POST["tanggalpernyataan"];
$nilaimaklon = $_POST["nilaimaklon"];
$ndpbm = $_POST["ndpbm"];
$kodevaluta = $_POST["kodevaluta"];
$netto = $_POST["netto"];
$kodelokasi = $_POST["kodelokasi"];
$kodepelmuat = $_POST["kodepelmuat"];
$tanggalperiksa = $_POST["tanggalperiksa"];
$kodepelbongkar = $_POST["kodepelbongkar"];
$kodepelekspor = $_POST["kodepelekspor"];
$kodepeltujuan = $_POST["kodepeltujuan"];
$jumlahkontainer = 1;

$kodekantorperiksa = $_POST["kodekantorperiksa"];
$kodecaradagang = $_POST["kodecaradagang"];
$kodenegaratujuan = $_POST["kodenegaratujuan"];
$tanggalekspor = $_POST["tanggalekspor"];
$kodeincoterm = $_POST["kodeincoterm"];
$kodetps = $_POST["kodetps"];

$jmldok = $_POST["jmldok"];
$jmlrow = $_POST["jmlrow"];
$jmlkontainer = $_POST["jmlkontainer"];

$sql_header = "INSERT INTO BC_HEADER (
    NomorAju,KodeDokumen,Asuransi,Bruto,FlagCurah,FlagMigas,
    Fob,Freight,JabatanPernyataan,KodeAsuransi,KodeCaraBayar,KodeJenisEkspor,
    KodeKantor,KodeKantorEkspor,KodeKantorMuat,KodeKategoriEkspor,KodeLokasi,
    KodeKantorPeriksa, KodeCaraDagang, KodeNegaraTujuan, TanggalEkspor, KodeIncoterm,
    KodePelabuhanBongkar,KodePelabuhanEkspor,KodePelabuhanMuat,KodePelabuhanTujuan,KodeValuta,
    KotaPernyataan,NamaPernyataan,Ndpbm,Netto,NilaiMaklon,TanggalPeriksa, KodeTps,
    TanggalPernyataan,NoPo,Urut,RecUser) VALUES (
    '" . $nomoraju . "','" . $kodedokumen . "','" . $asuransi . "','" . $bruto . "','" . $flagcurah . "','" . $flagmigas . "',
    '" . $fob . "','" . $freight . "','" . $jabatanttd . "','" . $kodeasuransi . "','" . $kodecarabayar . "','" . $kodejenisekspor . "',
    '" . $kodekantor . "','" . $kodekantorekspor . "','" . $kodekantormuat . "','" . $kodekategoriekspor . "','" . $kodelokasi . "',
    '" . $kodekantorperiksa . "', '" . $kodecaradagang . "','" . $kodenegaratujuan . "','" . $tanggalekspor . "','" . $kodeincoterm . "',
    '" . $kodepelbongkar . "','" . $kodepelekspor . "','" . $kodepelmuat . "','" . $kodepeltujuan . "','" . $kodevaluta . "',
    '" . $kotattd . "','" . $namattd . "','" . $ndpbm . "','" . $netto . "','" . $nilaimaklon . "','" . $tanggalperiksa . "','" . $kodetps . "',
    '" . $tanggalttd . "','" . $_POST["noso"] . "', '" . $urut . "','" . $_SESSION["nama"] . "')";

$save_header = $sqlLib->insert($sql_header);
if ($save_header == "1") {
    //Eksportir
    $sql_entitas_2 = "INSERT INTO BC_ENTITAS (
        NomorAju,Seri,KodeEntitas,KodeJenisIdentitas,NomorIdentitas,NamaEntitas,AlamatEntitas,NibEntitas,
        KodeJenisApi,KodeStatus,NomorIjinEntitas,TanggalIjinEntitas,KodeNegara,NiperEntitas,RecUser) VALUES (
        '" . $nomoraju . "', '2', '2', '" . $_POST["kodejenisidentitaseksportir"] . "', '" . $_POST["nomoridentitaseksportir"] . "', 
        '" . $_POST["namaeksportir"] . "', '" . $_POST["alamateksportir"] . "', '" . $_POST["nibeksportir"] . "', '', '" . $_POST["kodestatuseksportir"] . "',
        '" . $_POST["nomorijinentitaseksportir"] . "', '" . $_POST["tanggalijinentitaseksportir"] . "', '', '','" . $_SESSION["nama"] . "')";
    $save_entitas_2 = $sqlLib->insert($sql_entitas_2);
    if ($save_entitas_2 == "1") {
        //Pembeli
        $sql_entitas_6 = "INSERT INTO BC_ENTITAS (
            NomorAju,Seri,KodeEntitas,KodeJenisIdentitas,NomorIdentitas,NamaEntitas,AlamatEntitas,NibEntitas,
            KodeJenisApi,KodeStatus,NomorIjinEntitas,TanggalIjinEntitas,KodeNegara,NiperEntitas,RecUser) VALUES (
            '" . $nomoraju . "', '6', '6', '" . $_POST["kodejenisidentitaspembeli"] . "', '" . $_POST["nomoridentitaspembeli"] . "', 
            '" . $_POST["namapembeli"] . "', '" . $_POST["alamatpembeli"] . "', '" . $_POST["nibpembeli"] . "', '', '',
            '" . $_POST["nomorijinentitaspembeli"] . "', '" . $_POST["tanggalijinentitaspembeli"] . "', '" . $_POST["kodenegarapembeli"] . "', '','" . $_SESSION["nama"] . "')";
        $save_entitas_6 = $sqlLib->insert($sql_entitas_6);
        if ($save_entitas_6 == "1") {
            //Penerima
            $sql_entitas_8 = "INSERT INTO BC_ENTITAS (
                NomorAju,Seri,KodeEntitas,KodeJenisIdentitas,NomorIdentitas,NamaEntitas,AlamatEntitas,NibEntitas,
                KodeJenisApi,KodeStatus,NomorIjinEntitas,TanggalIjinEntitas,KodeNegara,NiperEntitas,RecUser) VALUES (
                '" . $nomoraju . "', '8', '8', '" . $_POST["kodejenisidentitaspenerima"] . "', '" . $_POST["nomoridentitaspenerima"] . "', 
                '" . $_POST["namapenerima"] . "', '" . $_POST["alamatpenerima"] . "', '" . $_POST["nibpenerima"] . "', '', '',
                '" . $_POST["nomorijinentitaspenerima"] . "', '" . $_POST["tanggalijinentitaspenerima"] . "', '" . $_POST["kodenegarapenerima"] . "', '','" . $_SESSION["nama"] . "')";
            $save_entitas_8 = $sqlLib->insert($sql_entitas_8);
            if ($save_entitas_8 == "1") {
                //pemilik
                $sql_entitas_7 = "INSERT INTO BC_ENTITAS (
                    NomorAju,Seri,KodeEntitas,KodeJenisIdentitas,NomorIdentitas,NamaEntitas,AlamatEntitas,NibEntitas,
                    KodeJenisApi,KodeStatus,NomorIjinEntitas,TanggalIjinEntitas,KodeNegara,NiperEntitas,RecUser) VALUES (
                    '" . $nomoraju . "', '13', '7', '" . $_POST["kodejenisidentitaspemilik"] . "', '" . $_POST["nomoridentitaspemilik"] . "', 
                    '" . $_POST["namapemilik"] . "', '" . $_POST["alamatpemilik"] . "', '" . $_POST["nibpemilik"] . "', '', '" . $_POST["kodestatuspemilik"] . "',
                    '" . $_POST["nomorijinentitaspemilik"] . "', '" . $_POST["tanggalijinentitaspemilik"] . "', '', '','" . $_SESSION["nama"] . "')";
                $save_entitas_7 = $sqlLib->insert($sql_entitas_7);
                if ($save_entitas_7 == "1") {
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
                                '" . $nomoraju . "','1','" . $_POST['kodecaraangkut'] . "','" . $_POST['namapengangkut'] . "','" . $_POST['nomorpengangkut'] . "','" . $_POST['kodebendera'] . "','','','" . $_SESSION["nama"] . "')";
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
                                if ($isi_kontainer > 0) {
                                    //barang
                                    $isi_barang = 0;
                                    for ($b = 1; $b <= $jmlrow; $b++) {
                                        $hsnumber = $_POST["hsnumber" . $b];
                                        $kodebarang = $_POST["kodebarang" . $b];
                                        $uraian = $_POST["uraian" . $b];
                                        $merk = $_POST["merk" . $b];
                                        $tipe = $_POST["tipe" . $b];
                                        $ukuran = $_POST["ukuran" . $b];
                                        $kodesatuanbarang = $_POST["kodesatuanbarang" . $b];
                                        $jumlahsatuan = $_POST["jumlahsatuan" . $b];
                                        $kodejeniskemasan = $_POST["kodejeniskemasan" . $b];
                                        $jumlahkemasan = $_POST["jumlahkemasan" . $b];
                                        $netto_dt = $_POST["netto_dt" . $b];
                                        $volume_dt = $_POST["volume_dt" . $b];
                                        $kodedaerah = $_POST["kodedaerah" . $b];
                                        $kodenegara = $_POST["kodenegara" . $b];
                                        $fob_dt = $_POST["fob" . $b];
                                        $hargasatuan = $_POST['hargasatuan' . $b];
                                        if ($hsnumber != "") {
                                            $sql_barang = "INSERT INTO BC_BARANG (
                                                NomorAju, SeriBarang, Hs, KodeBarang, Uraian, 
                                                Merek, Tipe, Ukuran, KodeSatuan,JumlahSatuan,
                                                KodeKemasan,JumlahKemasan, Netto, Volume,SpesifikasiLain,
                                                KodeDaerahAsal, KodeNegaraAsal, Fob, HargaSatuan,  Ndpbm, RecUser) VALUES (
                                                    '" . $nomoraju . "','" . $b . "','" . $hsnumber . "','" . $kodebarang . "','" . $uraian . "',
                                                    '" . $merk . "','" . $tipe . "','" . $ukuran . "','" . $kodesatuanbarang . "','" . $jumlahsatuan . "',
                                                    '" . $kodejeniskemasan . "','" . $jumlahkemasan . "','" . $netto_dt . "','" . $volume_dt . "','-',
                                                    '" . $kodedaerah . "','" . $kodenegara . "','" . $fob_dt . "','" . $hargasatuan . "','" . $ndpbm . "',
                                                    '" . $_SESSION["nama"] . "')";
                                            $save_barang = $sqlLib->insert($sql_barang);
                                            if ($save_barang == "1") {
                                                $isi_barang++;
                                            }
                                        }
                                    }
                                    if ($isi_barang > 0) {
                                        //bank devisa
                                        $sql_bd = "INSERT INTO BC_BANK_DEVISA (
                                            NomorAju, Seri, Kode, Nama,  RecUser) VALUES (
                                            '" . $nomoraju . "', '1', '" . $_POST["kodebank"] . "', '" . $_POST["namabank"] . "','" . $_SESSION["nama"] . "')";
                                        $save_bd = $sqlLib->insert($sql_bd);
                                        if ($save_bd == "1") {
                                            //barang siap periksa
                                            $waktuperiksa = $_POST["tanggalperiksa"] . ' ' . $_POST["waktuperiksa"];
                                            $sql_sp = "INSERT INTO BC_BARANG_SIAP_PERIKSA (
                                                NomorAju, Seri, KodeJenisBarang, KodeJenisGudang, NamaPic,
                                                Alamat, NomorTelpPic, Lokasi, TanggalPkb, WaktuPeriksa,
                                                RecUser) VALUES (
                                                '" . $nomoraju . "', '1', '" . $_POST["kodejenisbarang"] . "', '" . $_POST["kodejenisgudang"] . "','" . $_POST["namapic"] . "',
                                                '" . $_POST["alamatpic"] . "', '" . $_POST["telppic"] . "','" . $_POST["lokasisiapperiksa"] . "', '" . $_POST["tanggalpkb"] . "',
                                                '" . $waktuperiksa . "','" . $_SESSION["nama"] . "')";
                                            $save_sp = $sqlLib->insert($sql_sp);
                                            if ($save_sp == "1") {
                                                $sql_delkon = "DELETE FROM BC_KONTAINER_TMP ";
                                                $run_delkon = $sqlLib->delete($sql_delkon);
                                                $alert = '0';
                                                $note = "Proses simpan berhasil!!";
                                            } else {
                                                $sql_bdv = "DELETE FROM BC_BANK_DEVISA WHERE NomorAju = '" . $nomoraju . "'";
                                                $data_bdv = $sqlLib->delete($sql_bdv);
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
                                                $note = "Proses simpan kesiapan barang gagal!!";
                                            }
                                        } else {
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
                                            $note = "Proses simpan bank devisa gagal!!";
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
                    $note = "Proses simpan entitas pemilik gagal!!";
                }
            } else {
                $sql_ent = "DELETE FROM BC_ENTITAS WHERE NomorAju = '" . $nomoraju . "'";
                $data_ent = $sqlLib->delete($sql_ent);
                $sql_hdr = "DELETE FROM BC_HEADER WHERE NomorAju = '" . $nomoraju . "'";
                $data_hdr = $sqlLib->delete($sql_hdr);

                $alert = '1';
                $note = "Proses simpan entitas penerima gagal!!";
            }
        } else {
            $sql_ent = "DELETE FROM BC_ENTITAS WHERE NomorAju = '" . $nomoraju . "'";
            $data_ent = $sqlLib->delete($sql_ent);
            $sql_hdr = "DELETE FROM BC_HEADER WHERE NomorAju = '" . $nomoraju . "'";
            $data_hdr = $sqlLib->delete($sql_hdr);

            $alert = '1';
            $note = "Proses simpan entitas pembeli gagal!!";
        }
    } else {
        $sql_ent = "DELETE FROM BC_ENTITAS WHERE NomorAju = '" . $nomoraju . "'";
        $data_ent = $sqlLib->delete($sql_ent);
        $sql_hdr = "DELETE FROM BC_HEADER WHERE NomorAju = '" . $nomoraju . "'";
        $data_hdr = $sqlLib->delete($sql_hdr);

        $alert = '1';
        $note = "Proses simpan entitas eksportir gagal!!";
    }
} else {
    $alert = 1;
    $note = "Proses simpan header gagal";
}
