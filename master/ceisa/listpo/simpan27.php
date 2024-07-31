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