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

                            }else{
                                
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