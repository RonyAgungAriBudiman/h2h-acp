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
    '0','0','0','" . $_POST['bruto'] . "','" . $_POST['cif'] . "','" . $_POST['freight'] . "','" . $_POST['hargapenyerahan'] . "',
    '" . $_POST['jabatanpernyataan'] . "','" . $_POST['kodedokumenbc'] . "', '".$_POST['kodejenistpb']."','" . $_POST['kodekantor'] . "', '".$_POST["kodekantortujuan"]."','".$_POST["kodetps"]."',
    '" . $_POST['kodetujuanpengiriman'] . "','" . $_POST['kodejenistpbtujuan'] . "','" . $_POST['kodevaluta'] . "','" . $_POST['kotapernyataan'] . "','" . $_POST['namapernyataan'] . "',
    '" . $_POST['ndpbm'] . "', '" . $_POST['netto'] . "','" . $_POST['nilaibarang'] . "','" . $_POST['nilaijasa'] . "','" . $_POST['nomoraju'] . "','" . $_POST['tanggalpernyataan'] . "',
    '" . $_POST["nopo"] . "', '" . $urut . "','" . $_SESSION["nama"] . "')";
$save_header = $sqlLib->insert($sql_header);
if ($save_header == "1") {
    $alert = '01';
    $note = "Proses simpan header berhasil!!";
}
else{
    $alert = '1';
    $note = "Proses simpan header gagal!!";
}