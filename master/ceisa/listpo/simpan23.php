<?php

$_POST["seri"] = "1";
$sql_urut = "SELECT MAX(Urut) as Urut FROM BC_HEADER 
	                      WHERE KodeDokumen = '23' AND  YEAR(TanggalPernyataan) = '" . date("Y", strtotime($_POST['tanggalpernyataan'])) . "' ";
$data_urut = $sqlLib->select($sql_urut);
$urut = $data_urut[0]['Urut'] + 1;
$nomor = str_pad($urut, 6, '0', STR_PAD_LEFT);
$_POST["nomor"] = $nomor;
$_POST["urut"] = $urut;
$_POST["nomoraju"] =  '0000' . $_POST['kodedokumenbc'] . '-' . substr($_POST['nomoridentitaspengusaha'], 0, 6) . '-' . date("Ymd", strtotime($_POST['tanggalpernyataan'])) . '-' . $_POST['nomor'];

$nomoraju = $_POST["nomoraju"];
$asuransi = $_POST["asuransi"];
$bruto = $_POST["subbruto"];
$cif = $_POST["cif"];
$fob = $_POST["fob"];
$freight = $_POST["freight"];
$harga_penyerahan = $_POST["harga_penyerahan"];
$jabatanpernyataan = $_POST["jabatanpernyataan"];

$kodeasuransi = $_POST["kodeasuransi"];
$kodedokumen = $_POST["kodedokumenbc"];
$kodeincoterm = $_POST["kodeincoterm"];
$kodekantor = $_POST["kodekantor"];
$kodekantorbongkar = $_POST["kodekantorbongkar"];
$kodepelabuhanbongkar = $_POST["kodepelabuhanbongkar"];
$kodepelabuhanmuat = $_POST["kodepelabuhanmuat"];

$kodepelabuhantransit = $_POST["kodepelabuhantransit"];
$kodetps = $_POST["kodetps"];
$kodetujuantpb = $_POST["kodetujuantpb"];
$kodetutuppu = "11";
$kodevaluta = $_POST["kodevaluta"];
$kotapernyataan = $_POST["kotapernyataan"];
$namapernyataan = $_POST["namapernyataan"];
$ndpbm = $_POST["ndpbm"];

$netto = $_POST["subnetto"];
$nilaibarang = $_POST["nilaibarang"];
$nomorbc11 = $_POST["nomorbc11"];
$nomorpos = $_POST["nomorpos"];
$nomorsubpos = $_POST["nomorsubpos"];

$tanggalbc11 = $_POST["tanggalbc11"];
$tanggaltiba = $_POST["tanggaltiba"];
$biayatambahan = $_POST["biayatambahan"];
$tanggalpernyataan = $_POST["tanggalpernyataan"];

$_POST["bruto"] = $_POST["subbruto"];
$_POST["netto"] = $_POST["subnetto"];
$_POST["volume"] = $_POST["subvolume"];
$_POST["hargapenyerahan"] = $_POST["subtotal"];

$_POST["kodeentitaspengirim"] = "5";
$_POST["serientitaspengirim"] = "2";

$_POST["kodeentitaspemilik"] = "7";
$_POST["kodejenisapipemilik"] = "2";
$_POST["serientitaspemilik"] = "3";

$_POST["kodepeltransit"] = "";
$_POST["serikemasan"] = "1";
$jmlrow = $_POST["jmlrow"];
$jmldok = $_POST["jmldok"];
$jmltarif = $_POST["jmltarif"];

$sql_header = "INSERT INTO BC_HEADER (
            NomorAju, Asuransi, Bruto, Cif, Fob, Freight, HargaPenyerahan, JabatanPernyataan,
            KodeAsuransi,KodeDokumen,KodeIncoterm, KodeKantor, KodeKantorBongkar, KodePelabuhanBongkar, KodePelabuhanMuat,
            KodePelabuhanTransit, KodeTps, KodeTujuanTpb, KodeTutupPu, KodeValuta, KotaPernyataan, NamaPernyataan, Ndpbm,
            Netto, NilaiBarang,  NomorBc11, NomorPos, NomorSubPos, TanggalBc11, TanggalTiba,TanggalPernyataan,
            BiayaTambahan, BiayaPengurang,NoPo,Urut,RecUser) VALUES (
	        '" . $nomoraju . "','" . $asuransi . "','" . $bruto . "','" . $cif . "','" . $fob . "','" . $freight . "','" . $harga_penyerahan . "','" . $jabatanpernyataan . "',
	        '" . $kodeasuransi . "','" . $kodedokumen . "','" . $kodeincoterm . "','" . $kodekantor . "','" . $kodekantorbongkar . "','" . $kodepelabuhanbongkar . "','" . $kodepelabuhanmuat . "',
	        '" . $kodepelabuhantransit . "','" . $kodetps . "','" . $kodetujuantpb . "','" . $kodetutuppu . "','" . $kodevaluta . "','" . $kotapernyataan . "','" . $namapernyataan . "','" . $ndpbm . "',
	        '" . $netto . "','" . $nilaibarang . "','" . $nomorbc11 . "','" . $nomorpos . "','" . $nomorsubpos . "','" . $tanggalbc11 . "','" . $tanggaltiba . "','" . $tanggalpernyataan . "',
			'" . $biayatambahan . "','" . $biayapengurang . "','" . $_POST["nopo"] . "', '" . $urut . "','" . $_SESSION["nama"] . "')";
$save_header = $sqlLib->insert($sql_header);

if ($save_header == "1") {
	//Pengusaha
	$sql_entitas_3 = "INSERT INTO BC_ENTITAS (
        		NomorAju,Seri,KodeEntitas,KodeJenisIdentitas,NomorIdentitas,NamaEntitas,AlamatEntitas,NibEntitas,
        		KodeJenisApi,KodeStatus,NomorIjinEntitas,TanggalIjinEntitas,KodeNegara,NiperEntitas,RecUser) VALUES (
                '" . $nomoraju . "', '3', '3', '" . $_POST["kodejenisidentitaspengusaha"] . "', '" . $_POST["nomoridentitaspengusaha"] . "', 
                '" . $_POST["namaentitaspengusaha"] . "', '" . $_POST["alamatentitaspengusaha"] . "', '" . $_POST["nibentitaspengusaha"] . "', '', '',
                '" . $_POST["nomorijinentitaspengusaha"] . "', '" . $_POST["tanggalijinentitaspengusaha"] . "', '', '','" . $_SESSION["nama"] . "')";
	$save_entitas_3 = $sqlLib->insert($sql_entitas_3);
	if ($save_entitas_3 == "1") {
		//Pemasok/Pengirim
		$sql_entitas_5 = "INSERT INTO BC_ENTITAS (
            		NomorAju,Seri,KodeEntitas,KodeJenisIdentitas,NomorIdentitas,NamaEntitas,AlamatEntitas,NibEntitas,
            		KodeJenisApi,KodeStatus,NomorIjinEntitas,TanggalIjinEntitas,KodeNegara,NiperEntitas,RecUser) VALUES (
                    '" . $_POST["nomoraju"] . "', '5', '5', '', '','" . $_POST["namaentitaspengirim"] . "', '" . $_POST["alamatentitaspengirim"] . "', '',
                    '','','', '', '" . $_POST["kodenegara"] . "', '','" . $_SESSION["nama"] . "')";
		$save_entitas_5 = $sqlLib->insert($sql_entitas_5);
		if ($save_entitas_5 == "1") {
			//Pemilik
			$sql_entitas_7 = "INSERT INTO BC_ENTITAS (
	            		NomorAju,Seri,KodeEntitas,KodeJenisIdentitas,NomorIdentitas,NamaEntitas,AlamatEntitas,NibEntitas,
	            		KodeJenisApi,KodeStatus,NomorIjinEntitas,TanggalIjinEntitas,KodeNegara,NiperEntitas,RecUser) VALUES (
                        '" . $_POST["nomoraju"] . "', '7', '7', '" . $_POST["kodejenisidentitaspemilik"] . "', '" . $_POST["nomoridentitaspemilik"] . "', 
                        '" . $_POST["namaentitaspemilik"] . "', '" . $_POST["alamatentitaspemilik"] . "', '" . $_POST["nibentitaspemilik"] . "', '" . $_POST["kodejenisapipemilik"] . "',
                        '" . $_POST["kodestatuspemilik"] . "','" . $_POST["nomorijinentitaspemilik"] . "', '" . $_POST["tanggalijinentitaspengusaha"] . "', '', '','" . $_SESSION["nama"] . "')";
			$save_entitas_7 = $sqlLib->insert($sql_entitas_7);
			if ($save_entitas_7 == "1") {
				//Kemasan
				$sql_kemasan = "INSERT INTO BC_KEMASAN (
		            		NomorAju, Seri, KodeKemasan, JumlahKemasan, Merek, RecUser) VALUES (
                            '" . $_POST["nomoraju"] . "', '1', '" . $_POST["kodejeniskemasan"] . "', '" . $_POST["jumlahkemasan"] . "', '','" . $_SESSION["nama"] . "')";
				$save_kemasan = $sqlLib->insert($sql_kemasan);
				if ($save_kemasan == "1") {
					//Pengangkut
					$sql_pengangkut = "INSERT INTO BC_PENGANGKUT (
			            	NomorAju,Seri,KodeCaraAngkut,NamaPengangkut,NomorPengangkut,KodeBendera,CallSign,FlagAngkutPlb,RecUser) VALUES (
                        	'" . $_POST["nomoraju"] . "','1','" . $_POST['kodecaraangkut'] . "','" . $_POST['namapengangkut'] . "','" . $_POST['nomorpengangkut'] . "','" . $_POST['kodebendera'] . "','','','" . $_SESSION["nama"] . "')";
					$save_pengangkut = $sqlLib->insert($sql_pengangkut);
					if ($save_pengangkut == "1") {
						$isi_dok = 0;
						for ($a = 1; $a <= $jmldok; $a++) {
							$kodedokumen = $_POST["kodedokumen" . $a];
							$nomordokumen = $_POST["nomordokumen" . $a];
							$tanggaldokumen = $_POST["tanggaldokumen" . $a];
							if ($kodedokumen != "") {
								$sql_dokumen = "INSERT INTO BC_DOKUMEN (
									NomorAju,Seri,KodeDokumen,NomorDokumen,TanggalDokumen,KodeFasilitas,KodeIjin,RecUser) VALUES (
									'" . $_POST["nomoraju"] . "', '" . $a . "', '" . $kodedokumen . "', '" . $nomordokumen . "', '" . $tanggaldokumen . "', '', '', '" . $_SESSION["nama"] . "')";
								$save_dokumen = $sqlLib->insert($sql_dokumen);
								if ($save_dokumen == "1") {
									$isi_dok++;
								}
							}
						}
						if ($isi_dok > 0) {
							$isi_barang = 0;
							for ($b = 1; $b <= $jmlrow; $b++) {
								$hsnumber = $_POST["hsnumber" . $b];
								$kodebarang = $_POST["kdbarang" . $b];
								$uraian = $_POST["namabarang" . $b];
								$kodekategoribarang = $_POST["kodekategoribarang" . $b];
								$kodenegara =  $_POST["kodenegara" . $b];
								$cifrp = $_POST["cifrp" . $b];
								$hargaperolehan = 0;
								$asuransi = $_POST["asuransi" . $b];
								$cif = $_POST["cif" . $b];
								$diskon = $_POST["diskon" . $b];
								$fob = $_POST["fob" . $b];
								$freight = $_POST["freight" . $b];
								$hargaekspor = $_POST["hargaekspor" . $b];
								$hargasatuan = $_POST["harga" . $b];
								$kodeperhitungan = $_POST["kodeperhitungan" . $b];
								$nilaibarang = $_POST["nilaibarang" . $b];
								$nilaitambah = $_POST["nilaitambah" . $b];
								$isikemasan = $_POST["isikemasan" . $b];
								$jumlahkemasan = $_POST["jumlahkemasan" . $b];
								$jumlahsatuan = $_POST["jumlahsatuan" . $b];
								$kodekemasan = $_POST["kodejeniskemasan" . $b];
								$kodesatuan = $_POST["kodesatuanbarang" . $b];
								$netto = $_POST["netto" . $b];
								if ($hsnumber != "") {
									$sql_barang = "INSERT INTO BC_BARANG (
										NomorAju, SeriBarang, Hs, KodeBarang, Uraian, KodeKategoriBarang, KodeNegaraAsal, Ndpbm, CifRupiah, HargaPerolehan,
										Asuransi,Cif,Diskon, Fob, Freight, HargaEkspor, HargaSatuan,KodePerhitungan,NilaiBarang, NilaiTambah,
										IsiPerKemasan,JumlahKemasan,JumlahSatuan, KodeKemasan,KodeSatuan, Netto,
										SpesifikasiLain, Tipe, Ukuran, Merek, RecUser) VALUES (
											'" . $_POST["nomoraju"] . "','" . $b . "','" . $hsnumber . "','" . $kodebarang . "','" . $uraian . "', '" . $kodekategoribarang . "','" . $kodenegara . "',
											'" . $ndpbm . "','" . $cifrp . "','" . $hargaperolehan . "','" . $asuransi . "','" . $cif . "','" . $diskon . "','" . $fob . "','" . $freight . "',
											'" . $hargaekspor . "','" . $hargasatuan . "','" . $kodeperhitungan . "','" . $nilaibarang . "','" . $nilaitambah . "','" . $isikemasan . "',
											'" . $jumlahkemasan . "','" . $jumlahsatuan . "','" . $kodekemasan . "','" . $kodesatuan . "','" . $netto . "','-','-','-','-',
											'" . $_SESSION["nama"] . "')";
									$save_barang = $sqlLib->insert($sql_barang);
									if ($save_barang == "1") {
										$isi_barang++;
										//save tarif
										for ($c = 1; $c <= $jmltarif; $c++) {
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
													'" . $_POST["nomoraju"] . "','" . $b . "','" . $kodepungutan . "','" . $kodetarif . "','" . $tarif . "', '" . $kodefasilitastarif . "', '" . $kodesatuan . "','" . $jumlahsatuan . "',
													'0','" . $nilaifasilitas . "','0','" . $tariffasilitas . "','" . $_SESSION["nama"] . "', '" . $c . "')";
												$save_tarif = $sqlLib->insert($sql_tarif);
											}
										}
									}
								}
							}
							if ($isi_barang > 0) {
								$alert = '0';
								$note = "Proses simpan berhasil!!";
							} else {
								$sql_dok = "DELETE FROM BC_DOKUMEN WHERE NomorAju = '" . $nomoraju . "'";
								$data_dok = $sqlLib->delete($sql_dok);
								$sql_kut = "DELETE FROM BC_PENGANGKUT WHERE NomorAju = '" . $nomoraju . "'";
								$data_kut = $sqlLib->delete($sql_kut);
								$sql_kem = "DELETE FROM BC_KEMASAN WHERE NomorAju = '" . $nomoraju . "'";
								$data_kem = $sqlLib->delete($sql_kem);
								$sql_ent = "DELETE FROM BC_ENTITAS WHERE NomorAju = '" . $nomoraju . "'";
								$data_ent = $sqlLib->delete($sql_ent);
								$sql_hdr = "DELETE FROM BC_HEADER WHERE NomorAju = '" . $nomoraju . "'";
								$data_hdr = $sqlLib->delete($sql_hdr);

								$alert = '1';
								$note = "Proses simpan barang gagal!!";
							}
						} else {
							$sql_dok = "DELETE FROM BC_DOKUMEN WHERE NomorAju = '" . $nomoraju . "'";
							$data_dok = $sqlLib->delete($sql_dok);
							$sql_kut = "DELETE FROM BC_PENGANGKUT WHERE NomorAju = '" . $nomoraju . "'";
							$data_kut = $sqlLib->delete($sql_kut);
							$sql_kem = "DELETE FROM BC_KEMASAN WHERE NomorAju = '" . $nomoraju . "'";
							$data_kem = $sqlLib->delete($sql_kem);
							$sql_ent = "DELETE FROM BC_ENTITAS WHERE NomorAju = '" . $nomoraju . "'";
							$data_ent = $sqlLib->delete($sql_ent);
							$sql_hdr = "DELETE FROM BC_HEADER WHERE NomorAju = '" . $nomoraju . "'";
							$data_hdr = $sqlLib->delete($sql_hdr);

							$alert = '1';
							$note = "Proses simpan dokumen gagal!!";
						}
					} else {
						$sql_kut = "DELETE FROM BC_PENGANGKUT WHERE NomorAju = '" . $nomoraju . "'";
						$data_kut = $sqlLib->delete($sql_kut);
						$sql_kem = "DELETE FROM BC_KEMASAN WHERE NomorAju = '" . $nomoraju . "'";
						$data_kem = $sqlLib->delete($sql_kem);
						$sql_ent = "DELETE FROM BC_ENTITAS WHERE NomorAju = '" . $nomoraju . "'";
						$data_ent = $sqlLib->delete($sql_ent);
						$sql_hdr = "DELETE FROM BC_HEADER WHERE NomorAju = '" . $nomoraju . "'";
						$data_hdr = $sqlLib->delete($sql_hdr);

						$alert = '1';
						$note = "Proses simpan pengangkut gagal!!";
					}
				} else {
					$sql_kem = "DELETE FROM BC_KEMASAN WHERE NomorAju = '" . $nomoraju . "'";
					$data_kem = $sqlLib->delete($sql_kem);
					$sql_ent = "DELETE FROM BC_ENTITAS WHERE NomorAju = '" . $nomoraju . "'";
					$data_ent = $sqlLib->delete($sql_ent);
					$sql_hdr = "DELETE FROM BC_HEADER WHERE NomorAju = '" . $nomoraju . "'";
					$data_hdr = $sqlLib->delete($sql_hdr);

					$alert = '1';
					$note = "Proses simpan kemasan gagal!!";
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
			$note = "Proses simpan entitas pemasok gagal!!";
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



/*    
	if ($run == "1") {
	    for ($i = 1; $i <= $jmlrow; $i++) {
	        
	        $hsnumber = $_POST["hsnumber" . $i];
	        $kdbarang = $_POST["kdbarang" . $i];
	        $namabarang = $_POST["namabarang" . $i];
	        $kodekategoribarang = $_POST["kodekategoribarang".$i];
	        $kodenegara = $_POST["kodenegara".$i];
	        $ndpbm = $_POST["ndpbm".$i];
	        $cifrp = $_POST["cifrp".$i];
	        $hargaperolehan = $_POST["hargaperolehan".$i];
	        $kodeasalbahanbaku = $_POST["kodeasalbahanbaku".$i];

	        $asuransi = $_POST["asuransi".$i];
	        $cif = $_POST["cif".$i];
	        $diskon = $_POST["diskon".$i];
	        $fob = $_POST["fob".$i];
	        $freight = $_POST["freight".$i];
	        $hargaekspor = $_POST["hargaekspor".$i];
	        $harga = $_POST["harga" . $i];
	        $kodeperhitungan = $_POST["kodeperhitungan" . $i];
	        $nilaibarang = $_POST["nilaibarang" . $i];

	        $satuan = $_POST["satuan" . $i];
	        $qtyaju = $_POST["qtyaju" . $i];
	        $total = $_POST["total" . $i];
	        $nilaifasilitas = 0; //($total * $_POST["tarifppn"]) / 100;
	        $bruto = $_POST["bruto" . $i];
	        $netto = $_POST["netto" . $i];
	        $volume = $_POST["volume" . $i];
	        $seqitem = $_POST["seqitem" . $i];
	        if ($qtyaju > 0) {
	            $sql_dt = "INSERT INTO ms_dokumen_aju_detail(NoPO,NomorAju,DokumenBC,
	                        HsNumber, KdBarang, NamaBarang, KodeKategoriBarang, KodeNegara, Ndpbm, CifRp, HargaPerolehan, KodeAsalBahanBaku, 
	                        Asuransi, Cif, Diskon, Fob, Freight, HargaEkspor, Harga, KodePerhitungan, NlaiBarang,

	           Satuan,HsNumber,Qty,Total,NilaiFasilitas,Bruto,Netto,Volume,SeqItem,Recuser)
	                    VALUES( '" . $_POST['nopo']  . "','" . $_POST["nomoraju"] . "','" . $_POST["kodedokumenbc"] . "','" . $kdbarang . "','" . $namabarang . "','" . $satuan . "',
	                            '" . $hsnumber . "','" . $harga . "','" . $qtyaju . "','" . $total . "','" . $nilaifasilitas . "','" . $bruto . "','" . $netto . "','" . $volume . "','" . $seqitem . "',
	                            '" . $_SESSION["nama"] . "')";
	            $run_dt =  $sqlLib->insert($sql_dt);
	        }
	    }

	    //save dokumen
	    for ($a = 1; $a <= 2; $a++) {

	        $kodedokumen = $_POST["kodedokumen" . $a];
	        $nomordokumen = $_POST["nomordokumen" . $a];
	        $tgldokumen = $_POST["tgldokumen" . $a];
	        if ($kodedokumen == "380") {
	            $jenisdokumen = "Invoice";
	        } elseif ($kodedokumen == "705") {
	            $jenisdokumen = "B/L";
	        } elseif ($kodedokumen == "740") {
	            $jenisdokumen = "AWB";
	        }

	        if ($nomordokumen != "") {
	            $sql_dok = "INSERT INTO ms_dokumen_pendukung(NomorAju, KodeDokumen, NomorDokumen, JenisDokumen, TanggalDokumen, SeriDokumen)
	                    VALUES( '" . $_POST["nomoraju"] . "','" . $kodedokumen . "','" . $nomordokumen . "','" . $jenisdokumen . "','" . $tgldokumen . "','" . $a . "')";
	            $run_dok =  $sqlLib->insert($sql_dok);
	        }
	    }

	    //cek data detail
	    $sql_isi = "SELECT NomorAju FROM ms_dokumen_aju_detail 
	                WHERE NoPO = '" . $_POST['nopo'] . "' AND NomorAju = '" . $_POST["nomoraju"] . "' AND DokumenBC = '" . $_POST["kodedokumenbc"] . "' ";
	    $data_isi = $sqlLib->select($sql_isi);
	    if (count($data_isi) > 0) {
	        $alert = '0';
	        $note = "Proses simpan berhasil!!";
	        unset($_POST);
	    } else {
	        $sql_del = "DELETE FROM ms_dokumen_aju 
	                    WHERE NoPO = '" . $_POST['nopo'] . "' AND NomorAju = '" . $_POST["nomoraju"] . "' AND DokumenBC = '" . $_POST["kodedokumenbc"] . "'  ";
	        $run_del = $sqlLib->delete($sql_del);

	        $sql_del2 = "DELETE FROM ms_dokumen_pendukung 
	                    WHERE NomorAju = '" . $_POST["nomoraju"] . "' ";
	        $run_del2 = $sqlLib->delete($sql_del2);

	        $alert = '1';
	        $note = "Proses simpan gagal!!";
	    }
	} else {
	    $alert = '1';
	    $note = "Proses simpan header gagal!!";
	}
	*/
