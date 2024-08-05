<div class="section-header">
    <h1><?php echo acakacak("decode", $_GET["p"]) ?></h1>
</div>
<style>
    #parent {
        height: 600px;
    }

    th {
        background-color: #133b5c;
        color: rgb(241, 245, 179);

        text-align: center;
        font-weight: normal;
        font-size: 14px;
        outline: 0.7px solid black;
        border: 1.5px solid black;

    }

    td {
        border-bottom: 1.5px solid black;
        font-size: 12px;
    }
</style>

<script>
    $(document).ready(function() {
        $("#fixTable").tableHeadFixer();
        $("#fixTable").tableHeadFixer({
            'foot': true,
            'head': false
        });
    });;
    <?php if ($_POST['dari'] == "") {
        $_POST['dari'] = date("Y-m-01");
        $_POST['sampai'] = date("Y-m-d");
    } ?>
</script>


<?php

include "function/ceisa/kirim_bc40.php";
include "function/ceisa/kirim_bc23.php";
include "function/ceisa/kirim_bc30.php";
include "function/ceisa/kirim_bc25.php";
include "function/ceisa/kirim_bc262.php";

include "function/ceisa/kirim_bc41.php";
include "function/ceisa/kirim_bc27.php";
include "function/ceisa/kirim_bc261.php";
include "function/ceisa/kirim_bc20.php";

if ($_POST['hapus']) {

    $sql_bsp = "DELETE FROM BC_BARANG_SIAP_PERIKSA WHERE NomorAju = '" . $_POST['nomoraju'] . "'";
    $data_bsp = $sqlLib->delete($sql_bsp);
    $sql_bdv = "DELETE FROM BC_BANK_DEVISA WHERE NomorAju = '" . $_POST['nomoraju'] . "'";
    $data_bdv = $sqlLib->delete($sql_bdv);
    $sql_jam = "DELETE FROM BC_JAMINAN WHERE NomorAju = '" . $_POST['nomoraju'] . "'";
    $data_jam = $sqlLib->delete($sql_jam);
    $sql_tan = "DELETE FROM BC_PUNGUTAN WHERE NomorAju = '" . $_POST['nomoraju'] . "'";
    $data_tan = $sqlLib->delete($sql_tan);

    $sql_bbd = "DELETE FROM BC_BAHAN_BAKU_DOKUMEN WHERE NomorAju = '" . $_POST['nomoraju'] . "'";
    $data_bbd = $sqlLib->delete($sql_bbd);
    $sql_bbt = "DELETE FROM BC_BAHAN_BAKU_TARIF WHERE NomorAju = '" . $_POST['nomoraju'] . "'";
    $data_bbt = $sqlLib->delete($sql_bbt);
    $sql_bnb = "DELETE FROM BC_BAHAN_BAKU WHERE NomorAju = '" . $_POST['nomoraju'] . "'";
    $data_bnb = $sqlLib->delete($sql_bnb);

    $sql_brv = "DELETE FROM BC_BARANG_VD WHERE NomorAju = '" . $_POST['nomoraju'] . "'";
    $data_brv = $sqlLib->delete($sql_brv);
    $sql_bsk = "DELETE FROM BC_BARANG_SPEK_KHUSUS WHERE NomorAju = '" . $_POST['nomoraju'] . "'";
    $data_bsk = $sqlLib->delete($sql_bsk);
    $sql_bre = "DELETE FROM BC_BARANG_ENTITAS WHERE NomorAju = '" . $_POST['nomoraju'] . "'";
    $data_bre = $sqlLib->delete($sql_bre);
    $sql_brd = "DELETE FROM BC_BARANG_DOKUMEN WHERE NomorAju = '" . $_POST['nomoraju'] . "'";
    $data_brd = $sqlLib->delete($sql_brd);

    $sql_bat = "DELETE FROM BC_BARANG_TARIF WHERE NomorAju = '" . $_POST['nomoraju'] . "'";
    $data_bat = $sqlLib->delete($sql_bat);
    $sql_bar = "DELETE FROM BC_BARANG WHERE NomorAju = '" . $_POST['nomoraju'] . "'";
    $data_bar = $sqlLib->delete($sql_bar);

    $sql_ner = "DELETE FROM BC_KONTAINER WHERE NomorAju = '" . $_POST['nomoraju'] . "'";
    $data_ner = $sqlLib->delete($sql_ner);
    $sql_kem = "DELETE FROM BC_KEMASAN WHERE NomorAju = '" . $_POST['nomoraju'] . "'";
    $data_kem = $sqlLib->delete($sql_kem);
    $sql_kut = "DELETE FROM BC_PENGANGKUT WHERE NomorAju = '" . $_POST['nomoraju'] . "'";
    $data_kut = $sqlLib->delete($sql_kut);
    $sql_doc = "DELETE FROM BC_DOKUMEN WHERE NomorAju = '" . $_POST['nomoraju'] . "'";
    $data_doc = $sqlLib->delete($sql_doc);
    $sql_ent = "DELETE FROM BC_ENTITAS WHERE NomorAju = '" . $_POST['nomoraju'] . "'";
    $data_ent = $sqlLib->delete($sql_ent);
    $sql_hdr = "DELETE FROM BC_HEADER WHERE NomorAju = '" . $_POST['nomoraju'] . "'";
    $data_hdr = $sqlLib->delete($sql_hdr);
    if ($data_hdr == "1") {
        $alert = '0';
        $note = "Data berhasil dihapus";
    } else {
        $alert = '1';
        $note = "Gagal dihapus";
    }
}

if ($_POST['ulang']) {

    $sql_up = "UPDATE BC_HEADER SET KirimCeisa = '0' WHERE NomorAju = '" . $_POST['nomoraju'] . "'   ";
    $run_up = $sqlLib->update($sql_up);
    if ($run_up == "1") {
        $alert = '0';
        $note = "Data berhasil diupdate";
    } else {
        $alert = '1';
        $note = "Gagal diupdate";
    }
}


if ($_POST['kirim']) {
    $access_login = getlogin($username, $password);
    if ($access_login == "success") {
        $access_token = gettoken($username, $password);
        //BC 40
        if ($_POST['kodedokumen'] == "40") {
            $kirim = kirimbc40($username, $access_token, $_POST['nomoraju'], $sqlLib);
            $status = $kirim->status;
            $message = $kirim->message;
            if ($status == "OK") {
                $sql_up = "UPDATE BC_HEADER SET KirimCeisa = '1' WHERE NomorAju = '" . $_POST['nomoraju'] . "'   ";
                $run_up = $sqlLib->update($sql_up);
                $alert = '0';
                $note = $message;
            } else if($status == "false") {
                $alert = '1';
                $pesan = "";
                foreach ($message as $isi) {
                           $pesan .=  $isi . "<br>";
                         }
                $note = $pesan; 
            } else if($status == ""){
                $alert = '1';
                $note = "Proses gagal, gangguan jaringan pada Ceisa 40!";
            }
        }
        //BC 23
        if ($_POST['kodedokumen'] == "23") {
            $kirim = kirimbc23($username, $access_token, $_POST['nomoraju'], $sqlLib);
            $status = $kirim->status;
            $message = $kirim->message;
            if ($status == "OK") {
                $sql_up = "UPDATE BC_HEADER SET KirimCeisa = '1' WHERE NomorAju = '" . $_POST['nomoraju'] . "'   ";
                $run_up = $sqlLib->update($sql_up);
                $alert = '0';
                $note = $message;
            } else if($status == "failed") {
                $alert = '1';
                $pesan = "";
                foreach ($message as $isi) {
                           $pesan .=  $isi . "<br>";
                         }
                $note = $pesan; 
            } else if($status == ""){
                $alert = '1';
                $note = "Proses gagal, gangguan jaringan pada Ceisa 40!";
            }
        }
        //BC 30
        if ($_POST['kodedokumen'] == "30") {
            $kirim = kirimbc30($username, $access_token, $_POST['nomoraju'], $sqlLib);
            $status = $kirim->status;
            $message = $kirim->message;
            if ($status == "OK") {
                $sql_up = "UPDATE BC_HEADER SET KirimCeisa = '1' WHERE NomorAju = '" . $_POST['nomoraju'] . "'   ";
                $run_up = $sqlLib->update($sql_up);
                $alert = '0';
                $note = $message;
            } else if($status == "failed") {
                $alert = '1';
                $pesan = "";
                foreach ($message as $isi) {
                           $pesan .=  $isi . "<br>";
                         }
                $note = $pesan; 
            } else if($status == ""){
                $alert = '1';
                $note = "Proses gagal, gangguan jaringan pada Ceisa 40!";
            }
        }
        //BC 25
        if ($_POST['kodedokumen'] == "25") {
            $kirim = kirimbc25($username, $access_token, $_POST['nomoraju'], $sqlLib);
            $status = $kirim->status;
            $message = $kirim->message;
            if ($status == "OK") {
                $sql_up = "UPDATE BC_HEADER SET KirimCeisa = '1' WHERE NomorAju = '" . $_POST['nomoraju'] . "'   ";
                $run_up = $sqlLib->update($sql_up);
                $alert = '0';
                $note = $message;
            } else if($status == "failed") {
                $alert = '1';
                $pesan = "";
                foreach ($message as $isi) {
                           $pesan .=  $isi . "<br>";
                         }
                $note = $pesan; 
            } else if($status == ""){
                $alert = '1';
                $note = "Proses gagal, gangguan jaringan pada Ceisa 40!";
            }
        }
        //BC 262
        if ($_POST['kodedokumen'] == "262") {
            $kirim = kirimbc262($username, $access_token, $_POST['nomoraju'], $sqlLib);
            $status = $kirim->status;
            $message = $kirim->message;
            if ($status == "OK") {
                $sql_up = "UPDATE BC_HEADER SET KirimCeisa = '1' WHERE NomorAju = '" . $_POST['nomoraju'] . "'   ";
                $run_up = $sqlLib->update($sql_up);
                $alert = '0';
                $note = $message;
            } else {
                $alert = '1';
                $pesan = "";
                foreach ($message as $isi) {
                    $pesan .=  $isi . "<br>";
                }
                $note = $pesan;
            }
        }
    }
}

?>

<link rel="stylesheet" href="assets/css/jquery-ui.css" />
<!-- <script src="assets/js/jquery-1.12.4.js"></script>  -->
<script src="assets/js/jquery-ui.js"></script>
<div class="row">
    <div class="col-12">
        <div class="card">
            <form method="post" id="form" autocomplete="off">
                <div class="form-group row mt-3 mb-0">
                    <div class="col-sm-2  ml-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-calendar"></i>
                                </div>
                            </div>
                            <input type="text" name="dari" value="<?php echo $_POST['dari']; ?>" class="form-control datepicker" readonly="readonly">
                        </div>
                    </div>
                    <div class="col-sm-2 ">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-calendar"></i>
                                </div>
                            </div>
                            <input type="text" name="sampai" value="<?php echo $_POST['sampai']; ?>" class="form-control datepicker" readonly="readonly">
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <select name="dokbc" class="form-control">
                            <option value="">-Dokumen BC-</option>
                            <?php
                            $sql_com = "SELECT DISTINCT KodeDokumen 
										FROM BC_HEADER WHERE KodeDokumen !=''";
                            $data_com = $sqlLib->select($sql_com);
                            foreach ($data_com as $com) {
                            ?>
                                <option value="<?php echo $com['KodeDokumen'] ?>" <?php if ($_POST['dokbc'] == $com['KodeDokumen']) {
                                                                                        echo "selected";
                                                                                    } ?>>BC <?php echo $com['KodeDokumen'] ?></option>
                            <?php
                            }

                            ?>
                        </select>
                    </div>

                    <div class="col-sm-2">
                        <input type="text" placeholder="Nomor Aju" name="nomoraju" id="nomoraju" value="<?php echo $_POST['nomoraju']; ?>" class="form-control">
                    </div>


                    <div class="col-sm-3 ml-3">
                        <input type="submit" name="search" class="btn btn-primary" value="Search">
                    </div>
                </div>
            </form>

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
                <div class="table-responsive">
                    <div id="parent">
                        <table id="fixTable" class="table">
                            <thead>
                                <tr>
                                    <th style="background-color:#133b5c; color:#FFF; padding: 2px;">No.</th>
                                    <th style="background-color:#133b5c; color:#FFF; padding: 2px;">No PO</th>
                                    <th style="background-color:#133b5c; color:#FFF; padding: 2px;">No Pengajuan</th>
                                    <th style="background-color:#133b5c; color:#FFF; padding: 2px;">Dokumen</th>
                                    <th style="background-color:#133b5c; color:#FFF; padding: 2px;">Harga Penyerahan</th>
                                    <th style="background-color:#133b5c; color:#FFF; padding: 2px;"></th>
                                </tr>

                            </thead>
                            <tbody>
                                <?php

                                $no = 1;
                                $sql = "SELECT a.NoPo, a.NomorAju, a.KodeDokumen, a.HargaPenyerahan, a.KirimCeisa
                                        FROM BC_HEADER a
                                        WHERE a.KodeDokumen !='' AND a.NomorAju !='' 
                                            AND CONVERT(date, a.RecDate)>='" . $_POST['dari'] . "' AND CONVERT(date, a.RecDate)<='" . $_POST['sampai'] . "'";
                                if ($_POST['dokbc'] != "") $sql .= " AND a.KodeDokumen = '" . $_POST['dokbc'] . "' ";
                                if ($_POST['nomoraju'] != "") $sql .= " AND a.NomorAju = '" . $_POST['nomoraju'] . "' ";
                                $data = $sqlLib->select($sql);

                                foreach ($data as $row) {
                                    $t_qty += $row['quantity'];
                                    $t_price += $row['totalPrice'];
                                ?>
                                    <tr style="color:#000;">
                                        <td style="text-align: center;"><?php echo $no ?></td>
                                        <td style="text-align: center;"><?php echo trim($row['NoPo']) ?></td>
                                        <td style="text-align: center;"><?php echo trim($row['NomorAju']) ?></td>
                                        <td style="text-align: center;">BC <?php echo trim($row['KodeDokumen']) ?></td>
                                        <td style="text-align: center;"><?php echo number_format($row['HargaPenyerahan']) ?></td>
                                        <td style="text-align: center;">
                                            <?php if ($row['KirimCeisa'] != "1") { ?>
                                                <form method="post" id="form_detail" autocomplete="off">
                                                    <input type="submit" class="btn btn-primary" name="kirim" value="Kirim">
                                                    <input type="submit" class="btn btn-danger" name="hapus" value="Hapus">
                                                    <input type="hidden" name="nomoraju" value="<?php echo $row['NomorAju'] ?>">
                                                    <input type="hidden" name="kodedokumen" value="<?php echo $row['KodeDokumen'] ?>">
                                                </form>
                                            <?php } else { ?>
                                                <form method="post" id="form_detail" autocomplete="off">
                                                    <input type="submit" class="btn btn-success" name="ulang" value="Kirim Ulang">
                                                    <input type="hidden" name="nomoraju" value="<?php echo $row['NomorAju'] ?>">
                                                    <input type="hidden" name="kodedokumen" value="<?php echo $row['KodeDokumen'] ?>">
                                                </form>
                                            <?php
                                            } ?>
                                        </td>

                                    </tr>
                                <?php $no++;
                                }


                                ?>


                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        var ac_config = {
            source: "json/nomoraju.php",
            select: function(event, ui) {
                $("#nomoraju").val(ui.item.id);
                //$("#namabarang").val(ui.item.namabarang);
            },
            focus: function(event, ui) {
                $("#nomoraju").val(ui.item.id);
                //$("#namabarang").val(ui.item.namabarang);
            },
            minLength: 1
        };
        $("#nomoraju").autocomplete(ac_config);
    });
</script>