<?php

$accessToken = $_GET["access_token"];

// Header
$header = array(
    "Authorization: Bearer $accessToken"
);

// URL
$url = "https://account.accurate.id/api/open-db.do?id=" . $_GET["id"];

// Connect API
$opts = array(
    "http" =>
    array(
        "method" => "GET",
        "header" => $header,
        "ignore_errors" => true,
    )
);
$context  = stream_context_create($opts);
$response = file_get_contents($url, false, $context);

// Output
$session = json_decode($response)->{"session"};
$host = json_decode($response)->{"host"};

if (isset($_POST['req'])) {
    $accessToken = $_POST['accessToken'];
    $session = $_POST['session'];
    $host = $_POST['host'];
    $dari = date("d/m/Y", strtotime($_POST['dari']));
    $sampai = date("d/m/Y", strtotime($_POST['sampai']));
    $tanggal = date("d/m/Y", strtotime($_POST['tanggal']));

    //SO
    if ($_POST['request_data'] == "so") {
        $cekSo = cekSo($accessToken, $session, $host, $dari, $sampai, $sqlLib);
        if ($cekSo < 1) {
            $alert = '1';
            $note = "Data tidak ditemukan!!";
        } else {
            echo $cekSo;
            $getso = getSo($accessToken, $session, $host, $dari, $sampai, $sqlLib);
            if ($getso < 1) {
                $alert = '1';
                $note = "Request data gagal!!";
            } else {
                $alert = '0';
                $note = "Request data berhasil!!";
                
            }
            
        }
    }

    //PO
    if ($_POST['request_data'] == "po") {
        $cekPo = cekPo($accessToken, $session, $host, $dari, $sampai, $sqlLib);
        if ($cekPo < 1) {
            $alert = '1';
            $note = "Data tidak ditemukan!!";
        } else {
            $getpo = getPo($accessToken, $session, $host, $dari, $sampai, $sqlLib);
            if ($getpo < 1) {
                $alert = '1';
                $note = "Request data gagal!!";
            } else {
                $alert = '0';
                $note = "Request data berhasil!!";
                //echo $getpo;
            }
        }
    }

    //penerimaan
    if ($_POST['request_data'] == "penerimaan") {
        $cekReceive = cekReceive($accessToken, $session, $host, $dari, $sampai, $sqlLib);
        if ($cekReceive < 1) {
            $alert = '1';
            $note = "Data tidak ditemukan!!";
        } else {
            $reqreceive = getReceive($accessToken, $session, $host, $dari, $sampai, $sqlLib);
            if ($reqreceive < 1) {
                $alert = '1';
                $note = "Request data gagal!!";
            } else {
                $alert = '0';
                $note = "Request data berhasil!!";
            }
        }
    }

    //stock gudang
    else if ($_POST['request_data'] == "stockgudang") {
        //$periode = $_POST['tahun']."-".$_POST['bulan']."-01";  
        $dari = date("d/m/Y", strtotime($_POST['tgldari']));
        $sampai = date("d/m/Y", strtotime($_POST['tgldari']));

        $reqstock = getStockGudang($accessToken, $session, $host, $dari, $sampai, $sqlLib);
        if ($reqstock > 1) {
            $alert = '1';
            $note = "Tarik data gagal!!";
        } else {
            $alert = '0';
            $note = "Tarik data berhasil!!";
        }
    }

    //stock produksi
    else if ($_POST['request_data'] == "stockproduksi") {
        //$periode = $_POST['tahun']."-".$_POST['bulan']."-01";  
        $dari = date("d/m/Y", strtotime($_POST['tgldari']));
        $sampai = date("d/m/Y", strtotime($_POST['tgldari']));

        $reqstockpro = getStockProduksi($accessToken, $session, $host, $dari, $sampai, $sqlLib);
        if ($reqstockpro > 1) {
            $alert = '1';
            $note = "Tarik data gagal!!";
        } else {
            $alert = '0';
            $note = "Tarik data berhasil!!";
        }
    }

    //stock finish good
    else if ($_POST['request_data'] == "stockfg") {
        //$periode = $_POST['tahun']."-".$_POST['bulan']."-01";  
        $dari = date("d/m/Y", strtotime($_POST['tgldari']));
        $sampai = date("d/m/Y", strtotime($_POST['tgldari']));

        $reqstockfg = getStockFg($accessToken, $session, $host, $dari, $sampai, $sqlLib);
        if ($reqstockfg > 1) {
            $alert = '1';
            $note = "Tarik data gagal!!";
        } else {
            $alert = '0';
            $note = "Tarik data berhasil!!";
        }
    }

    //stock scrap
    else if ($_POST['request_data'] == "scrap") {
        //$periode = $_POST['tahun']."-".$_POST['bulan']."-01";  
        $dari = date("d/m/Y", strtotime($_POST['tgldari']));
        $sampai = date("d/m/Y", strtotime($_POST['tgldari']));

        $reqstockscrap = getStockScrap($accessToken, $session, $host, $dari, $sampai, $sqlLib);
        if ($reqstockscrap > 1) {
            $alert = '1';
            $note = "Tarik data gagal!!";
        } else {
            $alert = '0';
            $note = "Tarik data berhasil!!";
        }
    }

    //stock mesin (barang modal)
    else if ($_POST['request_data'] == "mesin") {
        $dari = date("d/m/Y", strtotime($_POST['tgldari']));
        $sampai = date("d/m/Y", strtotime($_POST['tgldari']));

        $reqstockmesin = getStockMesin($accessToken, $session, $host, $dari, $sampai, $sqlLib);

        if ($reqstockmesin > 1) {
            $alert = '1';
            $note = "Tarik data gagal!!";
        } else {
            $alert = '0';
            $note = "Tarik data berhasil!!";
        }
    }

    //pengeluaran
    else if ($_POST['request_data'] == "pengeluaran") {
        $cekdo = cekDO($accessToken, $session, $host, $dari, $sampai, $sqlLib);
        if ($cekdo < 1) {
            $alert = '1';
            $note = "Data tidak ditemukan!!";
        } else {
            $reqdo = getDo($accessToken, $session, $host, $dari, $sampai, $sqlLib);
            if ($reqdo < 1) {
                $alert = '1';
                $note = "Request data gagal!!";
            } else {
                $alert = '0';
                $note = "Request data berhasil!!";
            }
        }
    }


    //adjustment
    else if ($_POST['request_data'] == "adjustment") {
        $reqadj = getAdjustment($accessToken, $session, $host, $sqlLib);
        if ($reqadj < 1) {
            $alert = '1';
            $note = "Tarik data gagal!!";
        } else {
            $alert = '0';
            $note = "Tarik data berhasil!!";
        }
    } else if ($_POST['request_data'] == "testpage") {

        //echo $_POST['request_data'];
        $jsonpage = jsonPage($accessToken, $session, $host);
    }
}
?>
<div class="section-header">
    <h1>API Accurate</h1>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <form method="post" id="form" autocomplete="off" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="form-group row ">
                        <div class="col-sm-3">
                            <select name="request_data" class="form-control" onchange="submit();">
                                <option value="">Pilih Data</option>
                                <option value="po" <?php if ($_POST['request_data'] == "po") {
                                                        echo "selected";
                                                    } ?>>Data PO</option>
                                <option value="so" <?php if ($_POST['request_data'] == "so") {
                                                        echo "selected";
                                                    } ?>>Data SO</option>
                                <option value="penerimaan" <?php if ($_POST['request_data'] == "penerimaan") {
                                                                echo "selected";
                                                            } ?>>Penerimaan Barang</option>
                                <option value="stockgudang" <?php if ($_POST['request_data'] == "stockgudang") {
                                                                echo "selected";
                                                            } ?>>Stock Gudang</option>
                                <option value="stockproduksi" <?php if ($_POST['request_data'] == "stockproduksi") {
                                                                    echo "selected";
                                                                } ?>>Stock Produksi</option>
                                <option value="stockfg" <?php if ($_POST['request_data'] == "stockfg") {
                                                            echo "selected";
                                                        } ?>>Stock Finish Good</option>
                                <option value="scrap" <?php if ($_POST['request_data'] == "scrap") {
                                                            echo "selected";
                                                        } ?>>Stock Scrap</option>
                                <option value="mesin" <?php if ($_POST['request_data'] == "mesin") {
                                                            echo "selected";
                                                        } ?>>Stock Mesin</option>
                                <option value="pengeluaran" <?php if ($_POST['request_data'] == "pengeluaran") {
                                                                echo "selected";
                                                            } ?>>Pengeluaran Barang</option>
                                <option value="testpage" <?php if ($_POST['request_data'] == "testpage") {
                                                                echo "selected";
                                                            } ?>>Json</option>

                            </select>
                        </div>
                    </div>
                </div>
            </form>

            <?php
            if ($_POST['request_data'] == "penerimaan") include "penerimaan.php";
            else if ($_POST['request_data'] == "po") include "po.php";
            else if ($_POST['request_data'] == "so") include "so.php";
            else if ($_POST['request_data'] == "stock") include "stock.php";
            else if ($_POST['request_data'] == "stockgudang") include "stockgudang.php";
            else if ($_POST['request_data'] == "stockproduksi") include "stockproduksi.php";
            else if ($_POST['request_data'] == "stockfg") include "stockfg.php";
            else if ($_POST['request_data'] == "scrap") include "scrap.php";
            else if ($_POST['request_data'] == "mesin") include "mesin.php";
            else if ($_POST['request_data'] == "pengeluaran") include "pengeluaran.php";
            else if ($_POST['request_data'] == "testpage") include "testpage.php";
            ?>
        </div>
    </div>
</div>