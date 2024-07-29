<?php
include_once "sqlLib.php";
$sqlLib = new sqlLib();

// Membuat array kosong untuk barang
  $array_barang = array();
  $sql = "SELECT  a.NomorAju, a.SeriBarang, a.Hs, a.KodeBarang, a.Uraian, a.Merek, a.Tipe, a.Ukuran, a.SpesifikasiLain, a.KodeSatuan, a.JumlahSatuan, a.KodeKemasan, a.JumlahKemasan, a.KodeDokumenAsal,
                  a.KodeKantorAsal, a.NomorDaftarAsal, a.TanggalDaftarAsal, a.NomorAjuAsal, a.SeriBarangAsal, a.Netto, a.Bruto, a.Volume, a.SaldoAwal, a.SaldoAkhir, a.JumlahRealisasi, a.Cif, a.CifRupiah, 
                  a.Ndpbm, a.Fob, a.Asuransi, a.Freight, a.NilaiTambah, a.Diskon, a.HargaPenyerahan, a.HargaPerolehan, a.HargaSatuan, a.HargaEkspor, a.HargaPatokan, a.NilaiBarang, a.NilaiJasa, 
                  a.NilaiDanaSawit, a.NilaiDevisa, a.PersentaseImpor, a.KodeAsalBarang, a.KodeDaerahAsal, a.KodeGunaBarang, a.KodeJenisNilai, a.JatuhTempoRoyalti, a.KodeKategoriBarang,
                  a.KodeKondisiBarang, a.KodeNegaraAsal, a.KodePerhitungan, a.PernyataanLartas, a.Flag4Tahun, a.SeriIzin, a.TahunPembuatan, a.KapasitasSilinder, a.KodeBkc, a.KodeKomoditiBkc,
                  a.KodeSubKomoditiBkc, a.FlagTis, a.IsiPerKemasan, a.JumlahDilekatkan, a.JumlahPitaCukai, a.HjeCukai, a.TarifCukai,'25' as KodeDokumen, isnull(c.SeriDokumen,'0') as SeriDokumen,
                  isnull(c.SeriIzin,'0') as SeriIzin  
          FROM BC_BARANG a
          LEFT JOIN BC_BARANG_DOKUMEN c on c.NomorAju = a.NomorAju AND a.SeriBarang = a.SeriBarang
          WHERE a.NomorAju = '000025-010694-20240625-000001' AND a.SeriBarang ='1'  Order By a.SeriBarang Asc ";
  $data_barang = $sqlLib->select($sql);
  foreach ($data_barang as $barang) {
    $uraian = preg_replace("/[^a-zA-Z0-9()\s]/", "", $barang['Uraian']);

    //tarif barang
    $array_tarif = array();
    $sql_tarif = "SELECT a.SeriBarang, a.KodeTarif, a.KodeFasilitas, a.NilaiBayar, a.NilaiFasilitas, a.NilaiSudahDilunasi, a.Tarif, 
                        a.TarifFasilitas, a.KodePungutan, a.Urut
                    FROM BC_BARANG_TARIF a 
                    WHERE a.NomorAju = '000025-010694-20240625-000001' AND a.SeriBarang = '" . $barang['SeriBarang'] . "' 
                    ORDER BY a.Urut Asc ";
    $data_tarif = $sqlLib->select($sql_tarif);
    foreach ($data_tarif as $tarif) {
      // Membuat array untuk setiap barang
      $tarif_array = array(
        "jumlahSatuan" => floatval(number_format($barang['JumlahSatuan'], 2)),
        "kodeFasilitasTarif" => $tarif['KodeFasilitas'],
        "kodeJenisPungutan" => $tarif['KodePungutan'],
        "kodeJenisTarif" => $tarif['KodeTarif'],
        "kodeSatuanBarang" => $barang['KodeSatuan'],
        "nilaiBayar" => $tarif['NilaiBayar'],
        "nilaiFasilitas" => $tarif['NilaiFasilitas'],
        "nilaiSudahDilunasi" => $tarif['NilaiSudahDilunasi'],
        "tarif" => $tarif['Tarif'],
        "tarifFasilitas" => intval($tarif['TarifFasilitas'])
      );

      // Menambahkan data barang ke dalam array barang utama
      $array_tarif[] = $tarif_array;
    }

    //bahan baku
    $array_bahan_baku = array();
    $sql_bahan_baku = "SELECT a.NomorAju, a.SeriBarang, a.SeriBahanBaku, a.KodeAsalBahanBaku, a.Hs, a.KodeBarang, a.Uraian, a.Merek, a.Tipe, a.Ukuran, a.SpesifikasiLain, a.KodeSatuan, a.JumlahSatuan, 
                                a.KodeKemasan, a.JumlahKemasan, a.KodeDokumenAsal, a.KodeKantorAsal, a.NomorDaftarAsal, a.TanggalDaftarAsal, a.NomorAjuAsal, a.SeriBarangAsal, a.Netto, a.Bruto, a.Volume, 
                                a.Cif, a.CifRupiah, a.Ndpbm, a.HargaPenyerahan, a.HargaPerolehan, a.NilaiJasa, a.SeriIzin, a.Valuta, a.KodeBkc, a.KodeKomoditiBkc, a.KodeSubKomoditiBkc, a.FlagTis, 
                                a.IsiPerKemasan, a.JumlahDilekatkan, a.JumlahPitaCukai, a.HjeCukai, a.TarifCukai
                    FROM BC_BAHAN_BAKU a 
                    WHERE a.NomorAju = '000025-010694-20240625-000001' AND a.SeriBarang = '" . $barang['SeriBarang'] . "' 
                    ORDER BY a.SeriBahanBaku Asc ";
    $data_bahan_baku = $sqlLib->select($sql_bahan_baku);
    foreach ($data_bahan_baku as $bahan_baku) {

        //bahan baku tarif
        $array_bb_tarif = array();
        $sql_bb_tarif = "SELECT a.NomorAju, a.SeriBarang, a.SeriBahanBaku, a.KodeAsalBahanBaku, a.KodePungutan, a.KodeTarif, a.Tarif, a.KodeFasilitas, a.TarifFasilitas, a.NilaiBayar, a.NilaiFasilitas,
                                a.NilaiSudahDilunasi, a.KodeSatuan, a.JumlahSatuan, a.FlagBmtSementara, a.KodeKomoditiCukai, a.KodeSubKomoditiCukai, a.FlagTis, a.FlagPelekatan, a.KodeKemasan,
                                a.JumlahKemasan
                        FROM BC_BAHAN_BAKU_TARIF a 
                        WHERE a.NomorAju = '000025-010694-20240625-000001' AND a.SeriBarang = '" . $barang['SeriBarang'] . "' AND a.SeriBahanBaku = '" . $bahan_baku['SeriBahanBaku'] . "' 
                        ORDER BY a.SeriBahanBaku Asc ";
        $data_bb_tarif = $sqlLib->select($sql_bb_tarif);
        foreach ($data_bb_tarif as $bb_tarif) {
            $bb_tarif_array = array(

            "jumlahSatuan" => $bb_tarif['JumlahSatuan'],
            "kodeFasilitasTarif" => $bb_tarif['KodeFasilitas'],
            "kodeJenisPungutan" => $bb_tarif['KodePungutan'],
            "kodeJenisTarif"=> $bb_tarif['KodeTarif'],
            "nilaiBayar" => $bb_tarif['NilaiBayar'],
            "nilaiFasilitas"=> $bb_tarif['NilaiFasilitas'],
            "nilaiSudahDilunasi"=> $bb_tarif['NilaiSudahDilunasi'],
            "seriBahanBaku"=> $bb_tarif['SeriBahanBaku'],
            "tarif" => $bb_tarif['Tarif'],
            "tarifFasilitas" => $bb_tarif['TarifFasilitas']  
            );

            // Menambahkan data barang ke dalam array barang utama
            $array_bb_tarif[] = $bb_tarif_array;

        }
        $uraian_bb = preg_replace("/[^a-zA-Z0-9()\s]/", "", $bahan_baku['Uraian']);
        $bahan_baku_array = array(
            "cif" => $bahan_baku['Cif'],
            "cifRupiah" => $bahan_baku['CifRupiah'],
            "hargaPenyerahan" => $bahan_baku['HargaPenyerahan'],
            "jumlahSatuan" => $bahan_baku['JumlahSatuan'],
            "kodeAsalBahanBaku" => $bahan_baku['KodeAsalBahanBaku'],
            "kodeBarang" => $bahan_baku['KodeBarang'],
            "kodeDokAsal" => $bahan_baku['KodeDokumenAsal'],
            "kodeKantor" => $bahan_baku['KodeKantorAsal'],
            "kodeSatuanBarang" => $bahan_baku['KodeSatuan'],
            "merkBarang" => $bahan_baku['Merek'],
            "ndpbm" => $bahan_baku['Ndpbm'],
            "nomorAjuDokAsal" => $bahan_baku['NomorAjuAsal'],
            "nomorDaftarDokAsal" => $bahan_baku['NomorDaftarAsal'],
            "posTarif" => $bahan_baku['Hs'],
            "seriBahanBaku"  => $bahan_baku['SeriBahanBaku'],
            "seriBarang" => $bahan_baku['SeriBarang'],
            "seriBarangDokAsal" => $bahan_baku['SeriBarangAsal'],
            "seriIjin" => $bahan_baku['SeriIzin'],
            "spesifikasiLainBarang"  => $bahan_baku['SpesifikasiLain'],
            "tanggalDaftarDokAsal" => $bahan_baku['TanggalDaftarAsal'],
            "tipeBarang" => $bahan_baku['Tipe'],
            "ukuranBarang"  => $bahan_baku['Ukuran'],
            "uraianBarang" => $uraian_bb,
            "bahanBakuTarif" => $array_bb_tarif
        );

        // Menambahkan data barang ke dalam array barang utama
        $array_bahan_baku[] = $bahan_baku_array;
    }

    // Membuat array untuk setiap barang
    $barang_array = array(
      "bruto" => $barang['Bruto'],
      "cif" => $barang['Cif'],
      "diskon" => $barang['Diskon'],
      "fob" => $barang['Fob'],
      "freight" => $barang['Freight'],
      "hargaEkspor" => $barang['HargaEkspor'],
      "hargaPenyerahan" => $barang['HargaPenyerahan'],
      "isiPerKemasan" => $barang['IsiPerKemasan'],
      "jumlahKemasan" => intval($barang['JumlahKemasan']),
      "jumlahSatuan" => floatval(number_format($barang['JumlahSatuan'], 2)),
      "kodeBarang" => $barang['KodeBarang'],
      "kodeGunaBarang" => $barang['KodeGunaBarang'],
      "kodeKategoriBarang" => $barang['KodeKategoriBarang'],
      "kodeJenisKemasan" => $barang['KodeKemasan'],
      "kodeKondisiBarang" => $barang['KodeKondisiBarang'],
      "kodePerhitungan" => $barang['KodePerhitungan'],
      "kodeSatuanBarang" => $barang['KodeSatuan'],
      "merk" => $barang['Merek'],
      "netto" => $barang['Netto'],
      "nilaiBarang" => $barang['NilaiBarang'],
      "posTarif" => $barang['Hs'],
      "seriBarang" => intval($barang['SeriBarang']),
      "spesifikasiLain" => $barang['SpesifikasiLain'],
      "tipe" => $barang['Tipe'],
      "ukuran" => $barang['Ukuran'],
      "uraian" => $uraian,
      "ndpbm" => $barang['Ndpbm'],
      "cifRupiah" => $barang['CifRupiah'],
      "hargaPerolehan" => $barang['HargaPerolehan'],
      "kodeDokAsal" => $barang['KodeDokumenAsal'],
      "flag4tahun" => $barang['Flag4Tahun'],
      "barangTarif" => $array_tarif,
      "barangDokumen" => array(
        array(
          "seriDokumen" => trim($barang['SeriDokumen']),
          "seriIjin" => trim($barang['SeriIzin'])
        )
      ), 
      "bahanBaku" =>  $array_bahan_baku   

    );

    // Menambahkan data barang ke dalam array barang utama
    $array_barang[] = $barang_array;
  }  
  // Mengonversi array menjadi JSON
  $json_data_barang = json_encode($array_barang, JSON_PRETTY_PRINT);

echo "<pre>";
print_r($json_data_barang);
echo "</pre>";

// Menampilkan data JSON
//echo $json_data_barang;
