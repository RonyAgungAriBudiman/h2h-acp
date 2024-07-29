<?php
$_POST["seri"] = "1";

$sql_urut = "SELECT MAX(Urut) as Urut FROM BC_HEADER 
                              WHERE KodeDokumen = '40' AND  YEAR(TanggalPernyataan) = '" . date("Y", strtotime($_POST['tanggalaju'])) . "' ";
$data_urut = $sqlLib->select($sql_urut);
$urut = $data_urut[0]['Urut'] + 1;
$nomor = str_pad($urut, 6, '0', STR_PAD_LEFT);
$_POST["nomor"] = $nomor;
$_POST["urut"] = $urut;
$_POST["nomoraju"] =  '0000' . $_POST['kodedokumenbc'] . '-' . substr($_POST['nomoridentitaspengusaha'], 0, 6) . '-' . date("Ymd", strtotime($_POST['tanggalaju'])) . '-' . $_POST['nomor'];

$_POST["bruto"] = $_POST["subbruto"];
$_POST["netto"] = $_POST["subnetto"];
$_POST["volume"] = $_POST["subvolume"];
$_POST["hargapenyerahan"] = $_POST["subtotal"];

$_POST["kodeentitaspengusaha"] = "3";
$_POST["serientitaspengusaha"] = "1";

$_POST["kodeentitaspemilik"] = "7";
$_POST["kodejenisapipemilik"] = "2";
$_POST["serientitaspemilik"] = "2";

$_POST["kodeentitaspengirim"] = "9";
$_POST["kodejenisapipengirim"] = "2";
$_POST["serientitaspengirim"] = "3";

$_POST["seripengangkut"] = "1";

$_POST["serikemasan"] = "1";
$_POST["kodejenispungutan"] = "PPN";
$jmlrow = $_POST["jmlrow"];
$jmldok = $_POST["jmldok"];

$_POST["dasarpengenaanpajak"] = "0";

//dasarpengenaanpajak            
$sql_header = "INSERT INTO BC_HEADER (Bruto,KodeJenisTpb, HargaPenyerahan, JabatanPernyataan, KodeDokumen, KodeKantor, KodeTujuanPengiriman,KotaPernyataan, NamaPernyataan, Netto, Volume, 
                            NomorAju, TanggalPernyataan, DasarPengenaanPajak, NoPo, Urut, RecUser) VALUES ( 
                          '" . $_POST["bruto"] . "', '" . $_POST["kodejenistpb"] . "','" . $_POST["hargapenyerahan"] . "','" . $_POST["jabatanttd"] . "', '" . $_POST["kodedokumenbc"] . "',
                          '" . $_POST["kodekantor"] . "','" . $_POST["kodetujuanpengiriman"] . "', '" . strtoupper($_POST["kotattd"]) . "','" . $_POST["namattd"] . "', '" . $_POST["netto"] . "',
                          '" . $_POST["volume"] . "','" . $_POST["nomoraju"] . "','" . $_POST["tanggalaju"] . "', '" . $_POST['dasarpengenaanpajak'] . "', '" . $_POST['nopo'] . "', 
                          '" . $_POST["urut"] . "' ,'" . $_SESSION["nama"] . "' )";
$save_header = $sqlLib->insert($sql_header);
if ($save_header == "1") {
    //Pengusaha
    $sql_entitas_3 = "INSERT INTO BC_ENTITAS (NomorAju,Seri,KodeEntitas,KodeJenisIdentitas,NomorIdentitas,NamaEntitas,AlamatEntitas,NibEntitas,KodeJenisApi,KodeStatus,
                                NomorIjinEntitas,TanggalIjinEntitas,KodeNegara,NiperEntitas,RecUser) VALUES (
                    '" . $_POST["nomoraju"] . "', '3', '" . $_POST["kodeentitaspengusaha"] . "', '" . $_POST["kodejenisidentitaspengusaha"] . "', '" . $_POST["nomoridentitaspengusaha"] . "', 
                    '" . $_POST["namaentitaspengusaha"] . "', '" . $_POST["alamatentitaspengusaha"] . "', '" . $_POST["nibentitaspengusaha"] . "', '', '',
                    '" . $_POST["nomorijinentitaspengusaha"] . "', '" . $_POST["tanggalijinentitaspengusaha"] . "', '', '','" . $_SESSION["nama"] . "')";
    $save_entitas_3 = $sqlLib->insert($sql_entitas_3);
    if ($save_entitas_3 == "1") {
        //Pemilik
        $sql_entitas_7 = "INSERT INTO BC_ENTITAS (NomorAju,Seri,KodeEntitas,KodeJenisIdentitas,NomorIdentitas,NamaEntitas,AlamatEntitas,NibEntitas,KodeJenisApi,KodeStatus,
                                    NomorIjinEntitas,TanggalIjinEntitas,KodeNegara,NiperEntitas,RecUser) VALUES (
                        '" . $_POST["nomoraju"] . "', '7', '" . $_POST["kodeentitaspemilik"] . "', '" . $_POST["kodejenisidentitaspemilik"] . "', '" . $_POST["nomoridentitaspemilik"] . "', 
                        '" . $_POST["namaentitaspemilik"] . "', '" . $_POST["alamatentitaspemilik"] . "', '" . $_POST["nibentitaspemilik"] . "', '" . $_POST["kodejenisapipemilik"] . "',
                        '" . $_POST["kodestatuspemilik"] . "','" . $_POST["nomorijinentitaspemilik"] . "', '" . $_POST["tanggalijinentitaspengusaha"] . "', '', '','" . $_SESSION["nama"] . "')";
        $save_entitas_7 = $sqlLib->insert($sql_entitas_7);
        if ($save_entitas_7 == "1") {
            //Pengirim
            $sql_entitas_9 = "INSERT INTO BC_ENTITAS (NomorAju,Seri,KodeEntitas,KodeJenisIdentitas,NomorIdentitas,NamaEntitas,AlamatEntitas,NibEntitas,KodeJenisApi,KodeStatus,
                                        NomorIjinEntitas,TanggalIjinEntitas,KodeNegara,NiperEntitas,RecUser) VALUES (
                            '" . $_POST["nomoraju"] . "', '9', '" . $_POST["kodeentitaspengirim"] . "', '" . $_POST["kodejenisidentitaspengirim"] . "', '" . $_POST["nomoridentitaspengirim"] . "', 
                            '" . $_POST["namaentitaspengirim"] . "', '" . $_POST["alamatentitaspengirim"] . "', '" . $_POST["nibentitaspengirim"] . "', '" . $_POST["kodejenisapipengirim"] . "',
                            '" . $_POST["kodestatuspengirim"] . "','" . $_POST["nomorijinentitaspengirim"] . "', '" . $_POST["tanggalijinentitaspengirim"] . "', '', '','" . $_SESSION["nama"] . "')";
            $save_entitas_9 = $sqlLib->insert($sql_entitas_9);
            if ($save_entitas_9 == "1") {
                //pengangkut
                $sql_pengangkut = "INSERT INTO BC_PENGANGKUT (NomorAju,Seri,KodeCaraAngkut,NamaPengangkut,NomorPengangkut,KodeBendera,CallSign,FlagAngkutPlb,RecUser) VALUES (
                            '" . $_POST["nomoraju"] . "','1','" . $_POST['kodecaraangkut'] . "','" . $_POST['namapengangkut'] . "','" . $_POST['nomorpengangkut'] . "','','','','" . $_SESSION["nama"] . "')";
                $save_pengangkut = $sqlLib->insert($sql_pengangkut);
                if ($save_pengangkut == "1") {
                    //kemasan
                    $sql_kemasan = "INSERT INTO BC_KEMASAN (NomorAju, Seri, KodeKemasan, JumlahKemasan, Merek, RecUser) VALUES (
                                '" . $_POST["nomoraju"] . "', '1', '" . $_POST["kodejeniskemasan"] . "', '" . $_POST["jumlahkemasan"] . "', '','" . $_SESSION["nama"] . "')";
                    $save_kemasan = $sqlLib->insert($sql_kemasan);
                    if ($save_kemasan == "1") {

                        //barang
                        $isi_barang = 0;
                        $isi_tarif = 0;
                        $nilaifasilitas = 0;
                        for ($i = 1; $i <= $jmlrow; $i++) {
                            $hsnumber = $_POST["hsnumber" . $i];
                            $kdbarang = $_POST["kdbarang" . $i];
                            $uraian = $_POST["namabarang" . $i];
                            $satuan = $_POST["satuan" . $i];
                            $kodesatuan = $_POST["kodesatuanbarang" . $i];
                            $jumlahsatuan = $_POST["qtyaju" . $i];
                            $kodekemasan = $_POST["kodekemasan" . $i];
                            $jumlahkemasan = $_POST["jumlahkemasan" . $i];
                            $harga = $_POST["harga" . $i];
                            $hargapenyerahan = $_POST["total" . $i];
                            $nilaifasilitas = ($hargapenyerahan * $_POST["tarifppn"]) / 100;
                            $bruto = $_POST["bruto" . $i];
                            $netto = $_POST["netto" . $i];
                            $volume = $_POST["volume" . $i];
                            $seqitem = $_POST["seqitem" . $i];
                            if ($_POST['kodefasilitastarif'] != "1") {
                                $tariffasilitas = 100;
                            }
                            $tot_nilaifasilitas += $nilaifasilitas;
                            if ($jumlahsatuan > 0) {
                                $sql_barang = "INSERT INTO BC_BARANG (
                                            NomorAju, SeriBarang, Hs, KodeBarang, Uraian, Merek, Tipe, Ukuran, SpesifikasiLain, KodeSatuan, JumlahSatuan, KodeKemasan, JumlahKemasan, KodeDokumenAsal,
                                            KodeKantorAsal, NomorDaftarAsal, TanggalDaftarAsal, NomorAjuAsal, SeriBarangAsal, Netto, Bruto, Volume, SaldoAwal, SaldoAkhir, JumlahRealisasi, Cif, 
                                            CifRupiah, Ndpbm, Fob, Asuransi, Freight, NilaiTambah, Diskon, HargaPenyerahan, HargaPerolehan, HargaSatuan, HargaEkspor, HargaPatokan, NilaiBarang,
                                            NilaiJasa,NilaiDanaSawit, NilaiDevisa, PersentaseImpor, KodeAsalBarang, KodeDaerahAsal, KodeGunaBarang, KodeJenisNilai, JatuhTempoRoyalti, 
                                            KodeKategoriBarang,KodeKondisiBarang, KodeNegaraAsal, KodePerhitungan, PernyataanLartas, Flag4Tahun, SeriIzin, TahunPembuatan, KapasitasSilinder, 
                                            KodeBkc, KodeKomoditiBkc, KodeSubKomoditiBkc, FlagTis, IsiPerKemasan, JumlahDilekatkan, JumlahPitaCukai, HjeCukai, TarifCukai, RecUser) VALUES (
                                            '" . $_POST["nomoraju"] . "', '" . $i . "', '" . $hsnumber . "', '" . $kdbarang . "', '" . $uraian . "', '" . $merek . "', '" . $tipe . "', '" . $ukuran . "', '" . $spesifikasilain . "',
                                            '" . $kodesatuan . "','" . $jumlahsatuan . "', '" . $kodekemasan . "', '" . $jumlahkemasan . "', '" . $kodedokumenasal . "', '" . $kodekantorasal . "', '" . $nomordaftarasal . "',
                                            '" . $tanggaldaftarasal . "','" . $nomorajuasal . "', '" . $seribarangasal . "', '" . $netto . "', '" . $bruto . "', '" . $volume . "', '" . $saldoawal . "', '" . $saldoakhir . "',
                                            '" . $jumlahrealisasi . "', '" . $cif . "', '" . $cifrupiah . "', '" . $ndpbm . "', '" . $fob . "', '" . $asuransi . "', '" . $freight . "', '" . $nilaitambah . "', '" . $diskon . "', 
                                            '" . $hargapenyerahan . "', '" . $hargaperolehan . "','" . $hargasatuan . "', '" . $hargaekspor . "', '" . $hargapatokan . "', '" . $nilaibarang . "', '" . $nilaijasa . "', 
                                            '" . $nilaidanasawit . "', '" . $nilaidevisa . "', '" . $persentaseimpor . "','" . $kodeasalbarang . "', '" . $kodedaerahasal . "', '" . $kodegunabarang . "', 
                                            '" . $kodejenisnilai . "', '" . $jatuhtemporoyalti . "', '" . $kodekategoribarang . "', '" . $kodekondisibarang . "', '" . $kodenegaraasal . "', '" . $kodeperhitungan . "', 
                                            '" . $pernyataanlartas . "', '" . $flag4tahun . "', '" . $seriizin . "', '" . $tahunpembuatan . "', '" . $kapasitassilinder . "', '" . $kodebkc . "', '" . $kodekomoditibkc . "', 
                                            '" . $kodesubkomoditibkc . "', '" . $flagtis . "', '" . $isiperkemasan . "', '" . $jumlahdilekatkan . "', '" . $jumlahpitacukai . "', '" . $hjecukai . "', '" . $tarifcukai . "',  
                                            '" . $_SESSION["nama"] . "')";
                                $save_barang = $sqlLib->insert($sql_barang);
                                if ($save_barang == "1") {
                                    $isi_barang++;
                                    $sql_barang_tarif = "INSERT INTO BC_BARANG_TARIF (
                                                NomorAju, SeriBarang, KodePungutan, KodeTarif, Tarif, KodeFasilitas, TarifFasilitas, NilaiBayar, NilaiFasilitas, NilaiSudahDilunasi, KodeSatuan, 
                                                JumlahSatuan,FlagBmtSementara, KodeKomoditiCukai, KodeSubKomoditiCukai, FlagTis, FlagPelekatan, KodeKemasan, JumlahKemasan, RecUser) VALUES (
                                                '" . $_POST["nomoraju"] . "', '" . $i . "', 'PPN', '1', '" . $_POST["tarifppn"] . "', '" . $_POST['kodefasilitastarif'] . "', '" . $tariffasilitas . "',
                                                '" . $nilaibayar . "','" . $nilaifasilitas . "', '" . $nilaisudahdilunasi . "', '" . $kodesatuan . "', '" . $jumlahsatuan . "', '" . $flagbmtsementara . "',
                                                '" . $kodekomoditicukai . "','" . $kodesubkomoditicukai . "', '" . $flagtis . "', '" . $flagpelekatan . "', '" . $kodekemasan . "', '" . $jumlahkemasan . "',   
                                                '" . $_SESSION["nama"] . "')";
                                    $save_barang_tarif = $sqlLib->insert($sql_barang_tarif);
                                    if ($save_barang_tarif == "1") {
                                        $isi_tarif++;
                                    }
                                }
                            }
                        }
                        if ($isi_barang > 0 and $isi_barang == $isi_tarif) {
                            //pungutan
                            $sql_pungutan = "INSERT INTO BC_PUNGUTAN ( NomorAju, KodeFasilitasTarif, KodeJenisPungutan, NilaiPungutan, NpwpBilling, RecUser) VALUES (
                                                '" . $_POST["nomoraju"] . "', '" . $_POST['kodefasilitastarif'] . "', 'PPN', '" . $tot_nilaifasilitas . "', '', '" . $_SESSION["nama"] . "')";
                            $save_pungutan = $sqlLib->insert($sql_pungutan);
                            if ($save_pungutan == "1") {
                                //save dokumen
                                $isi_dok = 0;
                                for ($a = 1; $a <= $jmldok; $a++) {
                                    $kodedokumen = $_POST["kodedokumen" . $a];
                                    $nomordokumen = $_POST["nomordokumen" . $a];
                                    $tanggaldokumen = $_POST["tanggaldokumen" . $a];
                                    if ($kodedokumen != "") {
                                        $sql_dokumen = "INSERT INTO BC_DOKUMEN (NomorAju,Seri,KodeDokumen,NomorDokumen,TanggalDokumen,KodeFasilitas,KodeIjin,RecUser) VALUES (
                                                    '" . $_POST["nomoraju"] . "', '" . $a . "', '" . $kodedokumen . "', '" . $nomordokumen . "', '" . $tanggaldokumen . "', '', '', '" . $_SESSION["nama"] . "')";
                                        $save_dokumen = $sqlLib->insert($sql_dokumen);
                                        if ($save_dokumen == "1") {
                                            $isi_dok++;
                                        }
                                    }
                                }
                                if ($isi_dok > 0) {
                                    $alert = '0';
                                    $note = "Proses simpan berhasil!!";
                                } else {
                                    $sql_pun = "DELETE FROM BC_PUNGUTAN WHERE NomorAju = '" . $_POST["nomoraju"] . "'";
                                    $data_pun = $sqlLib->delete($sql_pun);
                                    $sql_trf = "DELETE FROM BC_BARANG_TARIF WHERE NomorAju = '" . $_POST["nomoraju"] . "'";
                                    $data_trf = $sqlLib->delete($sql_trf);
                                    $sql_brg = "DELETE FROM BC_BARANG WHERE NomorAju = '" . $_POST["nomoraju"] . "'";
                                    $data_brg = $sqlLib->delete($sql_brg);
                                    $sql_kem = "DELETE FROM BC_KEMASAN WHERE NomorAju = '" . $_POST["nomoraju"] . "'";
                                    $data_kem = $sqlLib->delete($sql_kem);
                                    $sql_kut = "DELETE FROM BC_PENGANGKUT WHERE NomorAju = '" . $_POST["nomoraju"] . "'";
                                    $data_kut = $sqlLib->delete($sql_kut);
                                    $sql_ent = "DELETE FROM BC_ENTITAS WHERE NomorAju = '" . $_POST["nomoraju"] . "'";
                                    $data_ent = $sqlLib->delete($sql_ent);
                                    $sql_hdr = "DELETE FROM BC_HEADER WHERE NomorAju = '" . $_POST["nomoraju"] . "'";
                                    $data_hdr = $sqlLib->delete($sql_hdr);

                                    $alert = '1';
                                    $note = "Proses simpan pungutan gagal!!";
                                }
                            }
                        } else {
                            $sql_trf = "DELETE FROM BC_BARANG_TARIF WHERE NomorAju = '" . $_POST["nomoraju"] . "'";
                            $data_trf = $sqlLib->delete($sql_trf);
                            $sql_brg = "DELETE FROM BC_BARANG WHERE NomorAju = '" . $_POST["nomoraju"] . "'";
                            $data_brg = $sqlLib->delete($sql_brg);
                            $sql_kem = "DELETE FROM BC_KEMASAN WHERE NomorAju = '" . $_POST["nomoraju"] . "'";
                            $data_kem = $sqlLib->delete($sql_kem);
                            $sql_kut = "DELETE FROM BC_PENGANGKUT WHERE NomorAju = '" . $_POST["nomoraju"] . "'";
                            $data_kut = $sqlLib->delete($sql_kut);
                            $sql_ent = "DELETE FROM BC_ENTITAS WHERE NomorAju = '" . $_POST["nomoraju"] . "'";
                            $data_ent = $sqlLib->delete($sql_ent);
                            $sql_hdr = "DELETE FROM BC_HEADER WHERE NomorAju = '" . $_POST["nomoraju"] . "'";
                            $data_hdr = $sqlLib->delete($sql_hdr);

                            $alert = '1';
                            $note = "Proses simpan barang gagal!!";
                        }
                    } else {
                        $sql_kem = "DELETE FROM BC_KEMASAN WHERE NomorAju = '" . $_POST["nomoraju"] . "'";
                        $data_kem = $sqlLib->delete($sql_kem);
                        $sql_kut = "DELETE FROM BC_PENGANGKUT WHERE NomorAju = '" . $_POST["nomoraju"] . "'";
                        $data_kut = $sqlLib->delete($sql_kut);
                        $sql_ent = "DELETE FROM BC_ENTITAS WHERE NomorAju = '" . $_POST["nomoraju"] . "'";
                        $data_ent = $sqlLib->delete($sql_ent);
                        $sql_hdr = "DELETE FROM BC_HEADER WHERE NomorAju = '" . $_POST["nomoraju"] . "'";
                        $data_hdr = $sqlLib->delete($sql_hdr);

                        $alert = '1';
                        $note = "Proses simpan entitas kemasan gagal!!";
                    }
                } else {
                    $sql_kut = "DELETE FROM BC_PENGANGKUT WHERE NomorAju = '" . $_POST["nomoraju"] . "'";
                    $data_kut = $sqlLib->delete($sql_kut);
                    $sql_ent = "DELETE FROM BC_ENTITAS WHERE NomorAju = '" . $_POST["nomoraju"] . "'";
                    $data_ent = $sqlLib->delete($sql_ent);
                    $sql_hdr = "DELETE FROM BC_HEADER WHERE NomorAju = '" . $_POST["nomoraju"] . "'";
                    $data_hdr = $sqlLib->delete($sql_hdr);

                    $alert = '1';
                    $note = "Proses simpan entitas pengangkut gagal!!";
                }
            } else {
                $sql_ent = "DELETE FROM BC_ENTITAS WHERE NomorAju = '" . $_POST["nomoraju"] . "'";
                $data_ent = $sqlLib->delete($sql_ent);
                $sql_hdr = "DELETE FROM BC_HEADER WHERE NomorAju = '" . $_POST["nomoraju"] . "'";
                $data_hdr = $sqlLib->delete($sql_hdr);

                $alert = '1';
                $note = "Proses simpan entitas pengirim gagal!!";
            }
        } else {

            $sql_ent = "DELETE FROM BC_ENTITAS WHERE NomorAju = '" . $_POST["nomoraju"] . "'";
            $data_ent = $sqlLib->delete($sql_ent);
            $sql_hdr = "DELETE FROM BC_HEADER WHERE NomorAju = '" . $_POST["nomoraju"] . "'";
            $data_hdr = $sqlLib->delete($sql_hdr);

            $alert = '1';
            $note = "Proses simpan entitas pemilik gagal!!";
        }
    } else {

        $sql_hdr = "DELETE FROM BC_HEADER WHERE NomorAju = '" . $_POST["nomoraju"] . "'";
        $data_hdr = $sqlLib->delete($sql_hdr);

        $alert = '1';
        $note = "Proses simpan entitas pengusaha gagal!!";
    }
} else {
    $alert = '1';
    $note = "Proses simpan header gagal!!";
}
