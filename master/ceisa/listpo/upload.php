<div class="section-header">
	<h1><?php echo acakacak("decode", $_GET["p"]) ?></h1>
</div>
<?php
if ($_POST['tanggal'] == "") {

	$_POST['tanggal'] = date("Y-m-d");
}

if ($_POST['upload']) {
	if ($_FILES["file"]["name"] != "") {
		$ext = end(explode(".", $_FILES["file"]["name"]));
		$allowedExts = array("xls", "xlsx");
		if (in_array($ext, $allowedExts)) {
			$filename = "pengajuan." . $ext;
			move_uploaded_file($_FILES["file"]["tmp_name"], "upload/pengajuan/" . $filename);
		}
		require_once("plugins/excel_reader2.php");
		$data = new Spreadsheet_Excel_Reader("upload/pengajuan/" . $filename);
		$jmlrow_header = count($data->sheets[0]['cells']);
		$jmlrow_entitas = count($data->sheets[1]['cells']);
		$jmlrow_dokumen = count($data->sheets[2]['cells']);
		$jmlrow_pengangkut = count($data->sheets[3]['cells']);
		$jmlrow_kemasan = count($data->sheets[4]['cells']);
		$jmlrow_kontainer = count($data->sheets[5]['cells']);
		$jmlrow_barang = count($data->sheets[6]['cells']);
		$jmlrow_barang_tarif = count($data->sheets[7]['cells']);
		$jmlrow_barang_dokumen = count($data->sheets[8]['cells']);
		$jmlrow_barang_entitas = count($data->sheets[9]['cells']);
		$jmlrow_barang_spekkhusus = count($data->sheets[10]['cells']);
		$jmlrow_barang_vd = count($data->sheets[11]['cells']);
		$jmlrow_bahan_baku = count($data->sheets[12]['cells']);
		$jmlrow_bahan_baku_tarif = count($data->sheets[13]['cells']);
		$jmlrow_bahan_baku_dokumen = count($data->sheets[14]['cells']);
		$jmlrow_pungutan = count($data->sheets[15]['cells']);
		$jmlrow_jaminan = count($data->sheets[16]['cells']);
		$jmlrow_bank_devisa = count($data->sheets[17]['cells']);

		$sql_pt = "SELECT a.NomorIdentitas
	                  FROM ms_perusahaan a
	                  WHERE a.IDPerusahaan ='1' "; //'3'
		$data_pt = $sqlLib->select($sql_pt);

		$sql_urut = "SELECT MAX(Urut) as Urut FROM BC_HEADER 
	                    WHERE KodeDokumen = '" . $_POST['kodedokumen'] . "' AND  YEAR(TanggalPernyataan) = '" . date("Y", strtotime($_POST['tanggal'])) . "' ";
		$data_urut = $sqlLib->select($sql_urut);
		$urut = $data_urut[0]['Urut'] + 1;
		$nomor = str_pad($urut, 6, '0', STR_PAD_LEFT);
		$_POST["nomor"] = $nomor;
		$_POST["urut"] = $urut;
		$nomoraju =  '0000' . $_POST['kodedokumen'] . '-' . substr($data_pt[0]['NomorIdentitas'], 0, 6) . '-' . date("Ymd", strtotime($_POST['tanggal'])) . '-' . $nomor;

		//save header
		$isi_header = 0;
		for ($i = 2; $i <= $jmlrow_header; $i++) {
			//$nomoraju = $data->sheets[0]['cells'][$i][1];
			$kodedokumen = $data->sheets[0]['cells'][$i][2];
			$kodekantor = $data->sheets[0]['cells'][$i][3];
			$kodekantorbongkar = $data->sheets[0]['cells'][$i][4];
			$kodekantorperiksa = $data->sheets[0]['cells'][$i][5];
			$kodekantortujuan = $data->sheets[0]['cells'][$i][6];
			$kodekantorekspor = $data->sheets[0]['cells'][$i][7];
			$kodejenisimpor = $data->sheets[0]['cells'][$i][8];
			$kodejenisekspor = $data->sheets[0]['cells'][$i][9];
			$kodejenistpb = $data->sheets[0]['cells'][$i][10];
			$kodejenisplb = $data->sheets[0]['cells'][$i][11];
			$kodejenisprosedur = $data->sheets[0]['cells'][$i][12];
			$kodetujuanpemasukan = $data->sheets[0]['cells'][$i][13];
			$kodetujuanpengiriman = $data->sheets[0]['cells'][$i][14];
			$kodetujuantpb = $data->sheets[0]['cells'][$i][15];
			$kodecaradagang = $data->sheets[0]['cells'][$i][16];
			$kodecarabayar = $data->sheets[0]['cells'][$i][17];
			$kodecarabayarlainnya = $data->sheets[0]['cells'][$i][18];
			//$kodegudangasal = $data->sheets[0]['cells'][$i][19]; duplikat
			//$kodegudangtujuan = $data->sheets[0]['cells'][$i][20];
			$kodejeniskirim = $data->sheets[0]['cells'][$i][21];
			$kodejenispengiriman = $data->sheets[0]['cells'][$i][22];
			$kodekategoriekspor = $data->sheets[0]['cells'][$i][23];
			$kodekategorimasukftz = $data->sheets[0]['cells'][$i][24];
			$kodekategorikeluarftz = $data->sheets[0]['cells'][$i][25];
			$kodekategoribarangftz = $data->sheets[0]['cells'][$i][26];
			$kodelokasi = $data->sheets[0]['cells'][$i][27];
			$kodelokasibayar = $data->sheets[0]['cells'][$i][28];
			$lokasiasal = $data->sheets[0]['cells'][$i][29];
			$lokasitujuan = $data->sheets[0]['cells'][$i][30];
			$kodedaerahasal = $data->sheets[0]['cells'][$i][31];
			$kodegudangasal = $data->sheets[0]['cells'][$i][32];
			$kodegudangtujuan = $data->sheets[0]['cells'][$i][33];
			$kodenegaratujuan = $data->sheets[0]['cells'][$i][34];
			$kodetutuppu = $data->sheets[0]['cells'][$i][35];
			$nomorbc11 = $data->sheets[0]['cells'][$i][36];
			$tanggalbc11 = $data->sheets[0]['cells'][$i][37];
			$nomorpos = $data->sheets[0]['cells'][$i][38];
			$nomorsubpos = $data->sheets[0]['cells'][$i][39];
			$kodepelabuhanbongkar = $data->sheets[0]['cells'][$i][40];
			$kodepelabuhanmuat = $data->sheets[0]['cells'][$i][41];
			$kodepelabuhanmuatakhir = $data->sheets[0]['cells'][$i][42];
			$kodepelabuhantransit = $data->sheets[0]['cells'][$i][43];
			$kodepelabuhantujuan = $data->sheets[0]['cells'][$i][44];
			$kodepelabuhanekspor = $data->sheets[0]['cells'][$i][45];
			$kodetps = $data->sheets[0]['cells'][$i][46];
			$tanggalberangkat = $data->sheets[0]['cells'][$i][47];
			$tanggalekspor = $data->sheets[0]['cells'][$i][48];
			$tanggalmasuk = $data->sheets[0]['cells'][$i][49];
			$tanggalmuat = $data->sheets[0]['cells'][$i][50];
			$tanggaltiba = $data->sheets[0]['cells'][$i][51];
			$tanggalperiksa = $data->sheets[0]['cells'][$i][52];
			$tempatstuffing = $data->sheets[0]['cells'][$i][53];
			$tanggalstuffing = $data->sheets[0]['cells'][$i][54];
			$kodetandapengaman = $data->sheets[0]['cells'][$i][55];
			$jumlahtandapengaman = $data->sheets[0]['cells'][$i][56];
			$flagcurah = $data->sheets[0]['cells'][$i][57];
			$flagsda = $data->sheets[0]['cells'][$i][58];
			$flagvd = $data->sheets[0]['cells'][$i][59];
			$flagapbk = $data->sheets[0]['cells'][$i][60];
			$flagmigas = $data->sheets[0]['cells'][$i][61];
			$kodeasuransi = $data->sheets[0]['cells'][$i][62];
			//$asuransi = $data->sheets[0]['cells'][$i][63]; //duplikat
			$nilaibarang = $data->sheets[0]['cells'][$i][64];
			$nilaiincoterm = $data->sheets[0]['cells'][$i][65];
			$nilaimaklon = $data->sheets[0]['cells'][$i][66];
			$asuransi = $data->sheets[0]['cells'][$i][67];
			$freight = $data->sheets[0]['cells'][$i][68];
			$fob = $data->sheets[0]['cells'][$i][69];
			$biayatambahan = $data->sheets[0]['cells'][$i][70];
			$biayapengurang = $data->sheets[0]['cells'][$i][71];
			$vd = $data->sheets[0]['cells'][$i][72];
			$cif = $data->sheets[0]['cells'][$i][73];
			$harga_penyerahan = $data->sheets[0]['cells'][$i][74];
			$ndpbm = $data->sheets[0]['cells'][$i][75];
			$totaldanasawit = $data->sheets[0]['cells'][$i][76];
			$dasarpengenaanpajak = $data->sheets[0]['cells'][$i][77];
			$nilaijasa = $data->sheets[0]['cells'][$i][78];
			$uangmuka = $data->sheets[0]['cells'][$i][79];
			$bruto = $data->sheets[0]['cells'][$i][80];
			$netto = $data->sheets[0]['cells'][$i][81];
			$volume = $data->sheets[0]['cells'][$i][82];
			$kotapernyataan = $data->sheets[0]['cells'][$i][83];
			$tanggalpernyataan = $data->sheets[0]['cells'][$i][84];
			$namapernyataan = $data->sheets[0]['cells'][$i][85];
			$jabatanpernyataan = $data->sheets[0]['cells'][$i][86];
			$kodevaluta = $data->sheets[0]['cells'][$i][87];
			$kodeincoterm = $data->sheets[0]['cells'][$i][88];
			$kodejasakenapajak = $data->sheets[0]['cells'][$i][89];
			$nomorbuktibayar = $data->sheets[0]['cells'][$i][90];
			$tanggalbuktibayar = $data->sheets[0]['cells'][$i][91];
			$kodejenisnilai = $data->sheets[0]['cells'][$i][92];
			$kodekantormuat = $data->sheets[0]['cells'][$i][93];
			$nomordaftar = $data->sheets[0]['cells'][$i][94];
			$tanggaldaftar = $data->sheets[0]['cells'][$i][95];
			$kodeasalbarangftz = $data->sheets[0]['cells'][$i][96];
			$kodetujuanpengeluaran = $data->sheets[0]['cells'][$i][97];
			$ppnpajak = $data->sheets[0]['cells'][$i][98];
			$ppnbmpajak = $data->sheets[0]['cells'][$i][99];
			$tarifppnpajak = $data->sheets[0]['cells'][$i][100];
			$tarifppnbmpajak = $data->sheets[0]['cells'][$i][101];
			$barangtidakberwujud = $data->sheets[0]['cells'][$i][102];
			$kodejenispengeluaran = $data->sheets[0]['cells'][$i][103];

			$sql_header = "INSERT INTO BC_HEADER (
				NomorAju,KodeDokumen,KodeKantor,KodeKantorBongkar,KodeKantorPeriksa,KodeKantorTujuan,KodeKantorEkspor,KodeJenisImpor,KodeJenisEkspor,KodeJenisTpb,KodeJenisPlb,KodeJenisProsedur,
				KodeTujuanPemasukan,KodeTujuanPengiriman,KodeTujuanTpb,KodeCaraDagang,KodeCaraBayar,KodeCaraBayarLainnya,KodeJenisKirim,KodeJenisPengiriman,KodeKategoriEkspor,KodeKategoriMasukFtz,
				KodeKategoriKeluarFtz,KodeKategoriBarangFtz,KodeLokasi,KodeLokasiBayar,LokasiAsal,LokasiTujuan,KodeDaerahAsal,KodeGudangAsal,KodeGudangTujuan,KodeNegaraTujuan,KodeTutupPu,NomorBc11,
				TanggalBc11,NomorPos,NomorSubPos,KodePelabuhanBongkar,KodePelabuhanMuat,KodePelabuhanMuatAkhir,KodePelabuhanTransit,KodePelabuhanTujuan,KodePelabuhanEkspor,KodeTps,TanggalBerangkat,
				TanggalEkspor,TanggalMasuk,TanggalMuat,TanggalTiba,TanggalPeriksa,TempatStuffing,TanggalStuffing,KodeTandaPengaman,JumlahTandaPengaman,FlagCurah,FlagSda,FlagVd,FlagApBk,FlagMigas,
				KodeAsuransi,Asuransi,NilaiBarang,NilaiIncoterm,NilaiMaklon,Freight,Fob,BiayaTambahan,BiayaPengurang,Vd,Cif,HargaPenyerahan,Ndpbm,TotalDanaSawit,DasarPengenaanPajak,NilaiJasa,UangMuka,
				Bruto,Netto,Volume,KotaPernyataan,TanggalPernyataan,NamaPernyataan,JabatanPernyataan,KodeValuta,KodeIncoterm,KodeJasaKenaPajak,NomorBuktiBayar,TanggalBuktiBayar,KodeJenisNilai,
				KodeKantorMuat,NomorDaftar,TanggalDaftar,KodeAsalBarangFtz,KodeTujuanPengeluaran,PpnPajak,PpnbmPajak,TarifPpnPajak,TarifPpnbmPajak,BarangTidakBerwujud,KodeJenisPengeluaran,NoPo,Urut,
				RecUser) VALUES (
				'" . $nomoraju . "','" . $kodedokumen . "','" . $kodekantor . "','" . $kodekantorbongkar . "','" . $kodekantorperiksa . "','" . $kodekantortujuan . "','" . $kodekantorekspor . "','" . $kodejenisimpor . "',
				'" . $kodejenisekspor . "','" . $kodejenistpb . "','" . $kodejenisplb . "','" . $kodejenisprosedur . "','" . $kodetujuanpemasukan . "','" . $kodetujuanpengiriman . "','" . $kodetujuantpb . "','" . $kodecaradagang . "',
				'" . $kodecarabayar . "','" . $kodecarabayarlainnya . "','" . $kodejeniskirim . "','" . $kodejenispengiriman . "','" . $kodekategoriekspor . "',
				'" . $kodekategorimasukftz . "','" . $kodekategorikeluarftz . "','" . $kodekategoribarangftz . "','" . $kodelokasi . "','" . $kodelokasibayar . "','" . $lokasiasal . "','" . $lokasitujuan . "','" . $kodedaerahasal . "',
				'" . $kodegudangasal . "','" . $kodegudangtujuan . "','" . $kodenegaratujuan . "','" . $kodetutuppu . "','" . $nomorbc11 . "','" . $tanggalbc11 . "','" . $nomorpos . "','" . $nomorsubpos . "','" . $kodepelabuhanbongkar . "',
				'" . $kodepelabuhanmuat . "','" . $kodepelabuhanmuatakhir . "','" . $kodepelabuhantransit . "','" . $kodepelabuhantujuan . "','" . $kodepelabuhanekspor . "','" . $kodetps . "','" . $tanggalberangkat . "',
				'" . $tanggalekspor . "','" . $tanggalmasuk . "','" . $tanggalmuat . "','" . $tanggaltiba . "','" . $tanggalperiksa . "','" . $tempatstuffing . "','" . $tanggalstuffing . "','" . $kodetandapengaman . "',
				'" . $jumlahtandapengaman . "','" . $flagcurah . "','" . $flagsda . "','" . $flagvd . "','" . $flagapbk . "','" . $flagmigas . "','" . $kodeasuransi . "','" . $asuransi . "','" . $nilaibarang . "','" . $nilaiincoterm . "',
				'" . $nilaimaklon . "','" . $freight . "','" . $fob . "','" . $biayatambahan . "','" . $biayapengurang . "','" . $vd . "','" . $cif . "','" . $harga_penyerahan . "','" . $ndpbm . "','" . $totaldanasawit . "',
				'" . $dasarpengenaanpajak . "','" . $nilaijasa . "','" . $uangmuka . "','" . $bruto . "','" . $netto . "','" . $volume . "','" . $kotapernyataan . "','" . $tanggalpernyataan . "','" . $namapernyataan . "',
				'" . $jabatanpernyataan . "','" . $kodevaluta . "','" . $kodeincoterm . "','" . $kodejasakenapajak . "','" . $nomorbuktibayar . "','" . $tanggalbuktibayar . "','" . $kodejenisnilai . "','" . $kodekantormuat . "',
				'" . $nomordaftar . "','" . $tanggaldaftar . "','" . $kodeasalbarangftz . "','" . $kodetujuanpengeluaran . "','" . $ppnpajak . "','" . $ppnbmpajak . "','" . $tarifppnpajak . "','" . $tarifppnbmpajak . "',
				'" . $barangtidakberwujud . "','" . $kodejenispengeluaran . "','" . $_POST["nopo"] . "', '" . $urut . "','" . $_SESSION["nama"] . "')";
			$save_header = $sqlLib->insert($sql_header);
			if ($save_header == "1") {
				$isi_header++;
			}
		}
		if ($isi_header > 0) {
			//save entitas
			$isi_entitas = 0;
			for ($i = 2; $i <= $jmlrow_entitas; $i++) {
				//$nomoraju = $data->sheets[1]['cells'][$i][1];
				$seri = $data->sheets[1]['cells'][$i][2];
				$kodeentitas = $data->sheets[1]['cells'][$i][3];
				$kodejenisidentitas = $data->sheets[1]['cells'][$i][4];
				$nomoridentitas = $data->sheets[1]['cells'][$i][5];
				$namaentitas = $data->sheets[1]['cells'][$i][6];
				$alamatentitas = $data->sheets[1]['cells'][$i][7];
				$nibentitas = $data->sheets[1]['cells'][$i][8];
				$kodejenisapi = $data->sheets[1]['cells'][$i][9];
				$kodestatus = $data->sheets[1]['cells'][$i][10];
				$nomorijinentitas = $data->sheets[1]['cells'][$i][11];
				$tanggalijinentitas = $data->sheets[1]['cells'][$i][12];
				$kodenegara = $data->sheets[1]['cells'][$i][13];
				$niperentitas = $data->sheets[1]['cells'][$i][14];

				$sql_entitas = "INSERT INTO BC_ENTITAS (
					NomorAju,Seri,KodeEntitas,KodeJenisIdentitas,NomorIdentitas,NamaEntitas,AlamatEntitas,NibEntitas,KodeJenisApi,KodeStatus,NomorIjinEntitas,TanggalIjinEntitas,KodeNegara,NiperEntitas,
					RecUser) VALUES (
					'" . $nomoraju . "', '" . $seri . "', '" . $kodeentitas . "', '" . $kodejenisidentitas . "', '" . $nomoridentitas . "', '" . $namaentitas . "', '" . $alamatentitas . "', '" . $nibentitas . "', '" . $kodejenisapi . "', 
					'" . $kodestatus . "', '" . $nomorijinentitas . "', '" . $tanggalijinentitas . "', '" . $kodenegara . "', '" . $niperentitas . "','" . $_SESSION["nama"] . "')";
				$save_entitas = $sqlLib->insert($sql_entitas);
				if ($save_entitas == "1") {
					$isi_entitas++;
				}
			}
			if ($isi_entitas == "3") {
				//save dokumen
				$isi_dokumen = 0;
				for ($i = 2; $i <= $jmlrow_dokumen; $i++) {
					//$nomoraju = $data->sheets[2]['cells'][$i][1];
					$seri = $data->sheets[2]['cells'][$i][2];
					$kodedokumen = $data->sheets[2]['cells'][$i][3];
					$nomordokumen = $data->sheets[2]['cells'][$i][4];
					$tanggaldokumen = $data->sheets[2]['cells'][$i][5];
					$kodefasilitas = $data->sheets[2]['cells'][$i][6];
					$kodeijin = $data->sheets[2]['cells'][$i][7];
					$sql_dokumen = "INSERT INTO BC_DOKUMEN (
						NomorAju,Seri,KodeDokumen,NomorDokumen,TanggalDokumen,KodeFasilitas,KodeIjin,RecUser) VALUES (
						'" . $nomoraju . "', '" . $seri . "', '" . $kodedokumen . "', '" . $nomordokumen . "', '" . $tanggaldokumen . "', '" . $kodefasilitas . "', '" . $kodeijin . "', '" . $_SESSION["nama"] . "')";
					$save_dokumen = $sqlLib->insert($sql_dokumen);
					if ($save_dokumen == "1") {
						$isi_dokumen++;
					}
				}
				if ($isi_dokumen > 0) {
					//save pengangkut
					$isi_pengangkut = 0;
					for ($i = 2; $i <= $jmlrow_pengangkut; $i++) {
						//$nomoraju = $data->sheets[3]['cells'][$i][1];
						$seri = $data->sheets[3]['cells'][$i][2];
						$kodecaraangkut = $data->sheets[3]['cells'][$i][3];
						$namapengangkut = $data->sheets[3]['cells'][$i][4];
						$nomorpengangkut = $data->sheets[3]['cells'][$i][5];
						$kodebendera = $data->sheets[3]['cells'][$i][6];
						$callsign = $data->sheets[3]['cells'][$i][7];
						$flagangkutplb = $data->sheets[3]['cells'][$i][8];
						$sql_pengangkut = "INSERT INTO BC_PENGANGKUT (
							NomorAju,Seri,KodeCaraAngkut,NamaPengangkut,NomorPengangkut,KodeBendera,CallSign,FlagAngkutPlb,RecUser) VALUES (
							'" . $nomoraju . "','" . $seri . "','" . $kodecaraangkut . "','" . $namapengangkut . "','" . $nomorpengangkut . "','" . $kodebendera . "','" . $callsign . "','" . $flagangkutplb . "',
							'" . $_SESSION["nama"] . "')";
						$save_pengangkut = $sqlLib->insert($sql_pengangkut);
						if ($save_pengangkut == "1") {
							$isi_pengangkut++;
						}
					}
					if ($isi_pengangkut > 0) {
						//save kemasan
						$isi_kemasan = 0;
						for ($i = 2; $i <= $jmlrow_kemasan; $i++) {
							//$nomoraju = $data->sheets[4]['cells'][$i][1];
							$seri = $data->sheets[4]['cells'][$i][2];
							$kodekemasan = $data->sheets[4]['cells'][$i][3];
							$jumlahkemasan = $data->sheets[4]['cells'][$i][4];
							$merek = $data->sheets[4]['cells'][$i][5];
							$sql_kemasan = "INSERT INTO BC_KEMASAN (
								NomorAju, Seri, KodeKemasan, JumlahKemasan, Merek, RecUser) VALUES (
								'" . $nomoraju . "', '" . $seri . "', '" . $kodekemasan . "', '" . $jumlahkemasan . "', '" . $merek . "','" . $_SESSION["nama"] . "')";
							$save_kemasan = $sqlLib->insert($sql_kemasan);
							if ($save_kemasan == "1") {
								$isi_kemasan++;
							}
						}
						if ($isi_kemasan > 0) {
							//save barang
							$isi_barang = 0;
							for ($i = 2; $i <= $jmlrow_barang; $i++) {
								//$nomoraju = $data->sheets[6]['cells'][$i][1];
								$seribarang = $data->sheets[6]['cells'][$i][2];
								$hs = $data->sheets[6]['cells'][$i][3];
								$kodebarang = $data->sheets[6]['cells'][$i][4];
								$uraian = $data->sheets[6]['cells'][$i][5];
								$merek = $data->sheets[6]['cells'][$i][6];
								$tipe = $data->sheets[6]['cells'][$i][7];
								$ukuran = $data->sheets[6]['cells'][$i][8];
								$spesifikasilain = $data->sheets[6]['cells'][$i][9];
								$kodesatuan = $data->sheets[6]['cells'][$i][10];
								$jumlahsatuan = $data->sheets[6]['cells'][$i][11];
								$kodekemasan = $data->sheets[6]['cells'][$i][12];
								$jumlahkemasan = $data->sheets[6]['cells'][$i][13];
								$kodedokumenasal = $data->sheets[6]['cells'][$i][14];
								$kodekantorasal = $data->sheets[6]['cells'][$i][15];
								$nomordaftarasal = $data->sheets[6]['cells'][$i][16];
								$tanggaldaftarasal = $data->sheets[6]['cells'][$i][17];
								$nomorajuasal = $data->sheets[6]['cells'][$i][18];
								$seribarangasal = $data->sheets[6]['cells'][$i][19];
								$netto = $data->sheets[6]['cells'][$i][20];
								$bruto = $data->sheets[6]['cells'][$i][21];
								$volume = $data->sheets[6]['cells'][$i][22];
								$saldoawal = $data->sheets[6]['cells'][$i][23];
								$saldoakhir = $data->sheets[6]['cells'][$i][24];
								$jumlahrealisasi = $data->sheets[6]['cells'][$i][25];
								$cif = $data->sheets[6]['cells'][$i][26];
								$cifrupiah = $data->sheets[6]['cells'][$i][27];
								$ndpbm = $data->sheets[6]['cells'][$i][28];
								$fob = $data->sheets[6]['cells'][$i][29];
								$asuransi = $data->sheets[6]['cells'][$i][30];
								$freight = $data->sheets[6]['cells'][$i][31];
								$nilaitambah = $data->sheets[6]['cells'][$i][32];
								$diskon = $data->sheets[6]['cells'][$i][33];
								$hargapenyerahan = $data->sheets[6]['cells'][$i][34];
								$hargaperolehan = $data->sheets[6]['cells'][$i][35];
								$hargasatuan = $data->sheets[6]['cells'][$i][36];
								$hargaekspor = $data->sheets[6]['cells'][$i][37];
								$hargapatokan = $data->sheets[6]['cells'][$i][38];
								$nilaibarang = $data->sheets[6]['cells'][$i][39];
								$nilaijasa = $data->sheets[6]['cells'][$i][40];
								$nilaidanasawit = $data->sheets[6]['cells'][$i][41];
								$nilaidevisa = $data->sheets[6]['cells'][$i][42];
								$persentaseimpor = $data->sheets[6]['cells'][$i][43];
								$kodeasalbarang = $data->sheets[6]['cells'][$i][44];
								$kodedaerahasal = $data->sheets[6]['cells'][$i][45];
								$kodegunabarang = $data->sheets[6]['cells'][$i][46];
								$kodejenisnilai = $data->sheets[6]['cells'][$i][47];
								$jatuhtemporoyalti = $data->sheets[6]['cells'][$i][48];
								$kodekategoribarang = $data->sheets[6]['cells'][$i][49];
								$kodekondisibarang = $data->sheets[6]['cells'][$i][50];
								$kodenegaraasal = $data->sheets[6]['cells'][$i][51];
								$kodeperhitungan = $data->sheets[6]['cells'][$i][52];
								$pernyataanlartas = $data->sheets[6]['cells'][$i][53];
								$flag4tahun = $data->sheets[6]['cells'][$i][54];
								$seriizin = $data->sheets[6]['cells'][$i][55];
								$tahunpembuatan = $data->sheets[6]['cells'][$i][56];
								$kapasitassilinder = $data->sheets[6]['cells'][$i][57];
								$kodebkc = $data->sheets[6]['cells'][$i][58];
								$kodekomoditibkc = $data->sheets[6]['cells'][$i][59];
								$kodesubkomoditibkc = $data->sheets[6]['cells'][$i][60];
								$flagtis = $data->sheets[6]['cells'][$i][61];
								$isiperkemasan = $data->sheets[6]['cells'][$i][62];
								$jumlahdilekatkan = $data->sheets[6]['cells'][$i][63];
								$jumlahpitacukai = $data->sheets[6]['cells'][$i][64];
								$hjecukai = $data->sheets[6]['cells'][$i][65];
								$tarifcukai = $data->sheets[6]['cells'][$i][66];

								$sql_barang = "INSERT INTO BC_BARANG (
									NomorAju, SeriBarang, Hs, KodeBarang, Uraian, Merek, Tipe, Ukuran, SpesifikasiLain, KodeSatuan, JumlahSatuan, KodeKemasan, JumlahKemasan, KodeDokumenAsal,
									KodeKantorAsal, NomorDaftarAsal, TanggalDaftarAsal, NomorAjuAsal, SeriBarangAsal, Netto, Bruto, Volume, SaldoAwal, SaldoAkhir, JumlahRealisasi, Cif, CifRupiah, 
									Ndpbm, Fob, Asuransi, Freight, NilaiTambah, Diskon, HargaPenyerahan, HargaPerolehan, HargaSatuan, HargaEkspor, HargaPatokan, NilaiBarang, NilaiJasa, 
									NilaiDanaSawit, NilaiDevisa, PersentaseImpor, KodeAsalBarang, KodeDaerahAsal, KodeGunaBarang, KodeJenisNilai, JatuhTempoRoyalti, KodeKategoriBarang,
									KodeKondisiBarang, KodeNegaraAsal, KodePerhitungan, PernyataanLartas, Flag4Tahun, SeriIzin, TahunPembuatan, KapasitasSilinder, KodeBkc, KodeKomoditiBkc,
									KodeSubKomoditiBkc, FlagTis, IsiPerKemasan, JumlahDilekatkan, JumlahPitaCukai, HjeCukai, TarifCukai, RecUser) VALUES (
									'" . $nomoraju . "', '" . $seribarang . "', '" . $hs . "', '" . $kodebarang . "', '" . $uraian . "', '" . $merek . "', '" . $tipe . "', '" . $ukuran . "', '" . $spesifikasilain . "', '" . $kodesatuan . "', 
									'" . $jumlahsatuan . "', '" . $kodekemasan . "', '" . $jumlahkemasan . "', '" . $kodedokumenasal . "', '" . $kodekantorasal . "', '" . $nomordaftarasal . "', '" . $tanggaldaftarasal . "', 
									'" . $nomorajuasal . "', '" . $seribarangasal . "', '" . $netto . "', '" . $bruto . "', '" . $volume . "', '" . $saldoawal . "', '" . $saldoakhir . "', '" . $jumlahrealisasi . "', '" . $cif . "', 
									'" . $cifrupiah . "', '" . $ndpbm . "', '" . $fob . "', '" . $asuransi . "', '" . $freight . "', '" . $nilaitambah . "', '" . $diskon . "', '" . $hargapenyerahan . "', '" . $hargaperolehan . "', 
									'" . $hargasatuan . "', '" . $hargaekspor . "', '" . $hargapatokan . "', '" . $nilaibarang . "', '" . $nilaijasa . "', '" . $nilaidanasawit . "', '" . $nilaidevisa . "', '" . $persentaseimpor . "', 
									'" . $kodeasalbarang . "', '" . $kodedaerahasal . "', '" . $kodegunabarang . "', '" . $kodejenisnilai . "', '" . $jatuhtemporoyalti . "', '" . $kodekategoribarang . "', 
									'" . $kodekondisibarang . "', '" . $kodenegaraasal . "', '" . $kodeperhitungan . "', '" . $pernyataanlartas . "', '" . $flag4tahun . "', '" . $seriizin . "', '" . $tahunpembuatan . "', 
									'" . $kapasitassilinder . "', '" . $kodebkc . "', '" . $kodekomoditibkc . "', '" . $kodesubkomoditibkc . "', '" . $flagtis . "', '" . $isiperkemasan . "', '" . $jumlahdilekatkan . "', 
									'" . $jumlahpitacukai . "', '" . $hjecukai . "', '" . $tarifcukai . "',  '" . $_SESSION["nama"] . "')";
								$save_barang = $sqlLib->insert($sql_barang);
								if ($save_barang == "1") {
									$isi_barang++;
								}
							}
							if ($isi_barang > 0) {
								//save barang tarif
								$isi_barang_tarif = 0;
								for ($i = 2; $i <= $jmlrow_barang_tarif; $i++) {
									//$nomoraju = $data->sheets[7]['cells'][$i][1];
									$seribarang = $data->sheets[7]['cells'][$i][2];
									$kodepungutan = $data->sheets[7]['cells'][$i][3];
									$kodetarif = $data->sheets[7]['cells'][$i][4];
									$tarif = $data->sheets[7]['cells'][$i][5];
									$kodefasilitas = $data->sheets[7]['cells'][$i][6];
									$tariffasilitas = $data->sheets[7]['cells'][$i][7];
									$nilaibayar = $data->sheets[7]['cells'][$i][8];
									$nilaifasilitas = $data->sheets[7]['cells'][$i][9];
									$nilaisudahdilunasi = $data->sheets[7]['cells'][$i][10];
									$kodesatuan = $data->sheets[7]['cells'][$i][11];
									$jumlahsatuan = $data->sheets[7]['cells'][$i][12];
									$flagbmtsementara = $data->sheets[7]['cells'][$i][13];
									$kodekomoditicukai = $data->sheets[7]['cells'][$i][14];
									$kodesubkomoditicukai = $data->sheets[7]['cells'][$i][15];
									$flagtis = $data->sheets[7]['cells'][$i][16];
									$flagpelekatan = $data->sheets[7]['cells'][$i][17];
									$kodekemasan = $data->sheets[7]['cells'][$i][18];
									$jumlahkemasan = $data->sheets[7]['cells'][$i][19];
									$sql_barang_tarif = "INSERT INTO BC_BARANG_TARIF (
										NomorAju, SeriBarang, KodePungutan, KodeTarif, Tarif, KodeFasilitas, TarifFasilitas, NilaiBayar, NilaiFasilitas, NilaiSudahDilunasi, KodeSatuan, JumlahSatuan,
										FlagBmtSementara, KodeKomoditiCukai, KodeSubKomoditiCukai, FlagTis, FlagPelekatan, KodeKemasan, JumlahKemasan, RecUser) VALUES (
										'" . $nomoraju . "', '" . $seribarang . "', '" . $kodepungutan . "', '" . $kodetarif . "', '" . $tarif . "', '" . $kodefasilitas . "', '" . $tariffasilitas . "', '" . $nilaibayar . "', 
										'" . $nilaifasilitas . "', '" . $nilaisudahdilunasi . "', '" . $kodesatuan . "', '" . $jumlahsatuan . "', '" . $flagbmtsementara . "', '" . $kodekomoditicukai . "', 
										'" . $kodesubkomoditicukai . "', '" . $flagtis . "', '" . $flagpelekatan . "', '" . $kodekemasan . "', '" . $jumlahkemasan . "',   '" . $_SESSION["nama"] . "')";
									$save_barang_tarif = $sqlLib->insert($sql_barang_tarif);
									if ($save_barang_tarif == "1") {
										$isi_barang_tarif++;
									}
								}
								if ($isi_barang_tarif > 0) {
									//save pungutan
									$isi_pungutan = 0;
									for ($i = 2; $i <= $jmlrow_pungutan; $i++) {
										//$nomoraju = $data->sheets[15]['cells'][$i][1];
										$kodefasilitastarif = $data->sheets[15]['cells'][$i][2];
										$kodejenispungutan = $data->sheets[15]['cells'][$i][3];
										$nilaipungutan = $data->sheets[15]['cells'][$i][4];
										$npwpbilling = $data->sheets[15]['cells'][$i][5];
										$sql_pungutan = "INSERT INTO BC_PUNGUTAN (
											NomorAju, KodeFasilitasTarif, KodeJenisPungutan, NilaiPungutan, NpwpBilling, RecUser) VALUES (
											'" . $nomoraju . "', '" . $kodefasilitastarif . "', '" . $kodejenispungutan . "', '" . $nilaipungutan . "', '" . $npwpbilling . "', '" . $_SESSION["nama"] . "')";
										$save_pungutan = $sqlLib->insert($sql_pungutan);
										if ($save_pungutan == "1") {
											$isi_pungutan++;
										}
									}
									if ($isi_pungutan > 0) {
										//save kontainer
										$isi_kontainer = 0;
										$sukses = flase;
										for ($i = 2; $i <= $jmlrow_kontainer; $i++) {
											//$nomoraju = $data->sheets[5]['cells'][$i][1];
											$seri = $data->sheets[5]['cells'][$i][2];
											$nomorkontiner = $data->sheets[5]['cells'][$i][3];
											$kodeukurankontainer = $data->sheets[5]['cells'][$i][4];
											$kodejeniskontainer = $data->sheets[5]['cells'][$i][5];
											$kodetipekontainer = $data->sheets[5]['cells'][$i][6];
											$sql_kontainer = "INSERT INTO BC_KONTAINER (
												NomorAju, Seri, NomorKontiner, KodeUkuranKontainer, KodeJenisKontainer, KodeTipeKontainer, RecUser) VALUES (
												'" . $nomoraju . "', '" . $seri . "', '" . $nomorkontiner . "', '" . $kodeukurankontainer . "', '" . $kodejeniskontainer . "', '" . $kodetipekontainer . "', '" . $_SESSION["nama"] . "')";
											$save_kontainer = $sqlLib->insert($sql_kontainer);
										}
										$sukses = true;
										if ($sukses) {
											$alert = '0';
											$note = "Proses simpan berhasil!!";
										} else {
											$sql_tan = "DELETE FROM BC_PUNGUTAN WHERE NomorAju = '" . $nomoraju . "'";
											$data_tan = $sqlLib->delete($sql_tan);
											$sql_bat = "DELETE FROM BC_BARANG_TARIF WHERE NomorAju = '" . $nomoraju . "'";
											$data_bat = $sqlLib->delete($sql_bat);
											$sql_bar = "DELETE FROM BC_BARANG WHERE NomorAju = '" . $nomoraju . "'";
											$data_bar = $sqlLib->delete($sql_bar);
											$sql_kem = "DELETE FROM BC_KEMASAN WHERE NomorAju = '" . $nomoraju . "'";
											$data_kem = $sqlLib->delete($sql_kem);
											$sql_kut = "DELETE FROM BC_PENGANGKUT WHERE NomorAju = '" . $nomoraju . "'";
											$data_kut = $sqlLib->delete($sql_kut);
											$sql_doc = "DELETE FROM BC_DOKUMEN WHERE NomorAju = '" . $nomoraju . "'";
											$data_doc = $sqlLib->delete($sql_doc);
											$sql_ent = "DELETE FROM BC_ENTITAS WHERE NomorAju = '" . $nomoraju . "'";
											$data_ent = $sqlLib->delete($sql_ent);
											$sql_hdr = "DELETE FROM BC_HEADER WHERE NomorAju = '" . $nomoraju . "'";
											$data_hdr = $sqlLib->delete($sql_hdr);

											$alert = '1';
											$note = "Proses simpan gagal!!";
										}
									} else {
										$sql_bat = "DELETE FROM BC_BARANG_TARIF WHERE NomorAju = '" . $nomoraju . "'";
										$data_bat = $sqlLib->delete($sql_bat);
										$sql_bar = "DELETE FROM BC_BARANG WHERE NomorAju = '" . $nomoraju . "'";
										$data_bar = $sqlLib->delete($sql_bar);
										$sql_kem = "DELETE FROM BC_KEMASAN WHERE NomorAju = '" . $nomoraju . "'";
										$data_kem = $sqlLib->delete($sql_kem);
										$sql_kut = "DELETE FROM BC_PENGANGKUT WHERE NomorAju = '" . $nomoraju . "'";
										$data_kut = $sqlLib->delete($sql_kut);
										$sql_doc = "DELETE FROM BC_DOKUMEN WHERE NomorAju = '" . $nomoraju . "'";
										$data_doc = $sqlLib->delete($sql_doc);
										$sql_ent = "DELETE FROM BC_ENTITAS WHERE NomorAju = '" . $nomoraju . "'";
										$data_ent = $sqlLib->delete($sql_ent);
										$sql_hdr = "DELETE FROM BC_HEADER WHERE NomorAju = '" . $nomoraju . "'";
										$data_hdr = $sqlLib->delete($sql_hdr);

										$alert = '1';
										$note = "Proses simpan pungutan gagal!!";
									}
								} else {
									$sql_bar = "DELETE FROM BC_BARANG WHERE NomorAju = '" . $nomoraju . "'";
									$data_bar = $sqlLib->delete($sql_bar);
									$sql_kem = "DELETE FROM BC_KEMASAN WHERE NomorAju = '" . $nomoraju . "'";
									$data_kem = $sqlLib->delete($sql_kem);
									$sql_kut = "DELETE FROM BC_PENGANGKUT WHERE NomorAju = '" . $nomoraju . "'";
									$data_kut = $sqlLib->delete($sql_kut);
									$sql_doc = "DELETE FROM BC_DOKUMEN WHERE NomorAju = '" . $nomoraju . "'";
									$data_doc = $sqlLib->delete($sql_doc);
									$sql_ent = "DELETE FROM BC_ENTITAS WHERE NomorAju = '" . $nomoraju . "'";
									$data_ent = $sqlLib->delete($sql_ent);
									$sql_hdr = "DELETE FROM BC_HEADER WHERE NomorAju = '" . $nomoraju . "'";
									$data_hdr = $sqlLib->delete($sql_hdr);

									$alert = '1';
									$note = "Proses simpan barang tarif gagal!!";
								}
							} else {
								$sql_kem = "DELETE FROM BC_KEMASAN WHERE NomorAju = '" . $nomoraju . "'";
								$data_kem = $sqlLib->delete($sql_kem);
								$sql_kut = "DELETE FROM BC_PENGANGKUT WHERE NomorAju = '" . $nomoraju . "'";
								$data_kut = $sqlLib->delete($sql_kut);
								$sql_doc = "DELETE FROM BC_DOKUMEN WHERE NomorAju = '" . $nomoraju . "'";
								$data_doc = $sqlLib->delete($sql_doc);
								$sql_ent = "DELETE FROM BC_ENTITAS WHERE NomorAju = '" . $nomoraju . "'";
								$data_ent = $sqlLib->delete($sql_ent);
								$sql_hdr = "DELETE FROM BC_HEADER WHERE NomorAju = '" . $nomoraju . "'";
								$data_hdr = $sqlLib->delete($sql_hdr);

								$alert = '1';
								$note = "Proses simpan barang gagal!!";
							}
						} else {
							$sql_kut = "DELETE FROM BC_PENGANGKUT WHERE NomorAju = '" . $nomoraju . "'";
							$data_kut = $sqlLib->delete($sql_kut);
							$sql_doc = "DELETE FROM BC_DOKUMEN WHERE NomorAju = '" . $nomoraju . "'";
							$data_doc = $sqlLib->delete($sql_doc);
							$sql_ent = "DELETE FROM BC_ENTITAS WHERE NomorAju = '" . $nomoraju . "'";
							$data_ent = $sqlLib->delete($sql_ent);
							$sql_hdr = "DELETE FROM BC_HEADER WHERE NomorAju = '" . $nomoraju . "'";
							$data_hdr = $sqlLib->delete($sql_hdr);

							$alert = '1';
							$note = "Proses simpan kemasan gagal!!";
						}
					} else {
						$sql_doc = "DELETE FROM BC_DOKUMEN WHERE NomorAju = '" . $nomoraju . "'";
						$data_doc = $sqlLib->delete($sql_doc);
						$sql_ent = "DELETE FROM BC_ENTITAS WHERE NomorAju = '" . $nomoraju . "'";
						$data_ent = $sqlLib->delete($sql_ent);
						$sql_hdr = "DELETE FROM BC_HEADER WHERE NomorAju = '" . $nomoraju . "'";
						$data_hdr = $sqlLib->delete($sql_hdr);

						$alert = '1';
						$note = "Proses simpan pengangkut gagal!!";
					}
				} else {
					$sql_hdr = "DELETE FROM BC_HEADER WHERE NomorAju = '" . $nomoraju . "'";
					$data_hdr = $sqlLib->delete($sql_hdr);
					$sql_ent = "DELETE FROM BC_ENTITAS WHERE NomorAju = '" . $nomoraju . "'";
					$data_ent = $sqlLib->delete($sql_ent);

					$alert = '1';
					$note = "Proses simpan dokumen gagal!!";
				}
			} else {
				$sql_hdr = "DELETE FROM BC_HEADER WHERE NomorAju = '" . $nomoraju . "'";
				$data_hdr = $sqlLib->delete($sql_hdr);
				$sql_ent = "DELETE FROM BC_ENTITAS WHERE NomorAju = '" . $nomoraju . "'";
				$data_ent = $sqlLib->delete($sql_ent);

				$alert = '1';
				$note = "Proses simpan entitas gagal!!";
			}
		} else {
			$alert = '1';
			$note = "Proses simpan header gagal!!";
		}
	}
}

if ($_GET['nopo'] != "") {
	$sql = "SELECT a.NoPO, a.TanggalPo, a.Vendor, a.Alamat, a.Subtotal, a.Tax2Amount, a.TotalAmount,
                            b.KdBarang, b.NamaBarang, b.Harga, b.Qty, b.ItemDiscPercent, b.ItemCost, b.Satuan, b.TotalHarga, b.SeqItem
                        FROM ac_po a
                        LEFT JOIN ac_po_detail b on b.NoPO = a.NoPO
                        WHERE a.NoPO = '" . $_GET['nopo'] . "' ";
	$data = $sqlLib->select($sql);
	$_POST["nopo"] = $data[0]['NoPO'];
}
?>
<div class="row">
	<div class="col-12">
		<div class="card">


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
				<div class="form-group">
					<a href="download/excel/format_upload.php" target="_blank">
						<button class="btn btn-info"><i class="fa fa-download"></i> Format</button>
					</a>
					<?php echo $notes; ?>
				</div>

				<form method="post" id="form-transaksi" autocomplete="off" enctype="multipart/form-data">
					<div class="form-group">
						<label>Tanggal</label>
						<input type="text" name="tanggal" class="form-control datepicker" readonly="readonly" value="<?php echo $_POST["tanggal"] ?>">

					</div>
					<div class="form-group">
						<label>Nomor PO </label>
						<input type="text" name="nopo" class="form-control" readonly="readonly" value="<?php echo $_POST["nopo"] ?>">

					</div>
					<div class="form-group">
						<label>Dokumen BC</label>
						<select name="kodedokumen" class="form-control" required="required"> <!--atributbc()  onclick="selected();"-->
							<option value="">Pilih Dokumen</option>
							<option value="40" <?php if ($_POST['kodedokumen'] == "40") {
													echo "selected";
												} ?>>BC 40</option>
							<option value="23" <?php if ($_POST['kodedokumen'] == "23") {
													echo "selected";
												} ?>>BC 23</option>

						</select>

					</div>
					<div class="form-group">
						<label>File</label>
						<input type="file" name="file" class="form-control" required="required" />

					</div>
			</div>
			<div class="card-footer text-right">
				<button type="reset" name="batal" class="btn btn-danger">Batal</button>
				<input type="submit" class="btn btn-primary" name="upload" Value="Upload">

			</div>


			</form>
		</div>
	</div>
</div>