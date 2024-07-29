<?php


function cekReceive($accessToken, $session, $host, $dari, $sampai, $sqlLib)
{
    // Header
    $header = array(
        "Content-Type: application/json",
        "Authorization: Bearer $accessToken",
        "X-SESSION-ID: $session"
    );

    // Content
    $content = array(

        "fields" => "number,approvalStatus,statusName,transDate",
        "filter.transDate.op" => "BETWEEN",
        "filter.transDate.val[0]" => $dari,
        "filter.transDate.val[1]" => $sampai,


    );

    // URL 
    $url = $host . "/accurate/api/receive-item/list.do?" . http_build_query($content);

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
    $result = file_get_contents($url, false, $context);

    // Membuat array dari data JSON
    $row_result_api = json_decode($result);
    $jmldata = $row_result_api->sp->rowCount;
    //return  $row_result_api;          
    return  $jmldata;
}

function getReceive($accessToken, $session, $host, $dari, $sampai, $sqlLib)
{
    // Header
    $header = array(
        "Content-Type: application/json",
        "Authorization: Bearer $accessToken",
        "X-SESSION-ID: $session"
    );

    // Content
    $content = array(

        "fields" => "number,approvalStatus,statusName,transDate",
        "filter.transDate.op" => "BETWEEN",
        "filter.transDate.val[0]" => $dari,
        "filter.transDate.val[1]" => $sampai,

    );

    // URL 
    $url = $host . "/accurate/api/receive-item/list.do?" . http_build_query($content);

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
    $result = file_get_contents($url, false, $context);

    // Membuat array dari data JSON
    $row_result_api = json_decode($result);
    $jmldata = $row_result_api->sp->rowCount;
    //return  $row_result_api;          

    $sukses = false;
    foreach ($row_result_api->d as $row_api) {
        $number = $row_api->number;
        // Content
        $content = array(
            "number" => $number
        );

        // URL
        $url = $host . "/accurate/api/receive-item/detail.do?" . http_build_query($content);

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
        $result = file_get_contents($url, false, $context);

        // Membuat array dari data JSON
        $row_hd = json_decode($result);

        $number = $row_hd->d->number;
        $receiveNumber = $row_hd->d->receiveNumber;
        $vendor = $row_hd->d->vendor->name;
        $dokumenBC = $row_hd->d->charField2;
        $nomorAju = $row_hd->d->charField1;
        $nomorDaftar = $row_hd->d->charField3;
        $tglDaftar = $row_hd->d->dateField1;
        $transDate = $row_hd->d->transDate;
        $shipDate = $row_hd->d->shipDate;
        $approvalStatus = $row_hd->d->approvalStatus;
        $status = $row_hd->d->status;
        $statusName = $row_hd->d->statusName;


        // Menggunakan DateTime untuk parsing
        $tanggal_obj1 = DateTime::createFromFormat('d/m/Y', $tglDaftar);
        $tanggal_obj2 = DateTime::createFromFormat('d/m/Y', $transDate);
        $tanggal_obj3 = DateTime::createFromFormat('d/m/Y', $shipDate);

        // Menggunakan format() untuk mengonversi ke format yang diinginkan
        $tglDaftar = $tanggal_obj1->format('Y-m-d');
        $transDate = $tanggal_obj2->format('Y-m-d');
        $shipDate = $tanggal_obj3->format('Y-m-d');

        //delete penerimaan
        $sql_del = "DELETE FROM ac_penerimaan WHERE receiveItem = '" . $number . "' ";
        $run1 = $sqlLib->delete($sql_del);

        $sql_del2 = "DELETE FROM ac_penerimaan_detail WHERE receiveItem = '" . $number . "' ";
        $run2 = $sqlLib->delete($sql_del2);

        if ($dokumenBC != "") {
            $sql_save = "INSERT INTO ac_penerimaan 
                                    (receiveItem, receiveNumber, receiveDate, vendor, dokumenBC, nomorAju, nomorDaftar, tglDaftar,  transDate, shipDate, approvalStatus, status, statusName, recuser, recdate)
                                    VALUES('" . $number . "' ,'" . $receiveNumber . "','" . $transDate . "','" . $vendor . "','" . $dokumenBC . "'  ,'" . $nomorAju . "','" . $nomorDaftar . "','" . $tglDaftar . "',
                                            '" . $transDate . "','" . $shipDate . "','" . $approvalStatus . "','" . $status . "','" . $statusName . "','" . $_SESSION['userid'] . "','" . date("Y-m-d H:i:s") . "') ";
            //$run = sqlsrv_query($conn, $sql_save);                      
            $run = $sqlLib->insert($sql_save);
            if ($run == "1") {

                foreach ($row_hd->d->detailItem as $row) // detail receive
                {

                    $itemNo = $row->item->no;
                    $detailName = $row->item->name;
                    $unitPrice = $row->unitPrice;
                    $quantity = $row->quantity;
                    $itemDiscPercent = $row->itemDiscPercent;
                    $itemCost = $row->itemCost;
                    $satuan = $row->itemUnit->name;
                    $totalPrice = $row->totalPrice;

                    $hsNumber = $row->charField1;
                    $bruto = $row->numericField1;
                    $netto = $row->numericField2;
                    $volume = $row->numericField3;

                    $itemCategoryId = $row->item->itemCategoryId;


                    $sql_save_dt = "INSERT INTO ac_penerimaan_detail (receiveItem,itemNo,detailName,unitPrice,quantity,itemDiscPercent,itemCost,satuan, totalPrice,bruto, netto, volume, hsNumber, itemCategoryId, recUser, recDate)
                                            VALUES('" . $number . "','" . $itemNo . "','" . $detailName . "','" . $unitPrice . "','" . $quantity . "','" . $itemDiscPercent . "','" . $itemCost . "','" . $satuan . "','" . $totalPrice . "',
                                        '" . $bruto . "','" . $netto . "','" . $volume . "','" . $hsNumber . "', '" . $itemCategoryId . "', '" . $_SESSION["userid"] . "','" . date("Y-m-d H:i:s") . "')";
                    $run_dt = $sqlLib->insert($sql_save_dt);
                    //echo $sql_save_dt;

                }

                //cek detail
                $sql_dtl = "SELECT COUNT(receiveItem) as jmldata FROM ac_penerimaan_detail WHERE receiveItem = '" . $number . "' ";
                $data_dtl = $sqlLib->select($sql_dtl);
                if ($data_dtl[0]['jmldata'] < 1) {
                    $sql_del3 = "DELETE FROM ac_penerimaan WHERE receiveItem = '" . $number . "' ";
                    $run3 = $sqlLib->delete($sql_del3);
                }

                $sukses = true;
            }
        }
    }

    return  $sukses;
}

function getStockGudang($accessToken, $session, $host, $dari, $sampai, $sqlLib)
{
    $sukses = 0;
    $tanggal_obj2 = DateTime::createFromFormat('d/m/Y', $dari);
    $tanggal = $tanggal_obj2->format('Y-m-d');
    $latsupdate = $dari . " 00:00:00";

    // Header
    $header = array(
        "Content-Type: application/json",
        "Authorization: Bearer $accessToken",
        "X-SESSION-ID: $session"
    );

    // Content
    $content = array(
        "fields" => "id,name,no,itemUnit,itemCategory",
        "filter.itemCategoryId.op" => "EQUAL",
        "filter.itemCategoryId.val[0]" => "100", // Bahan Baku        

    );

    // URL
    $url = $host . "/accurate/api/item/list.do?" . http_build_query($content);

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
    $result = file_get_contents($url, false, $context);

    // Membuat array dari data JSON
    $row_result_api = json_decode($result);

    //delete ac_daily_request
    $sql_del2 = "DELETE FROM ac_daily_request WHERE TglRequest = '" . $tanggal . "' AND Gudang ='Utama' ";
    $run_del2 = $sqlLib->delete($sql_del2);

    foreach ($row_result_api->d as $row_api) {
        $itemId = $row_api->id;
        $itemNo = $row_api->no;
        $itemNoMs = $row_api->no;
        $itemName = $row_api->name;
        $unit1Name = $row_api->unit1Name;
        $itemCategory = $row_api->itemCategory->name;

        //cek data
        $sql_cek = "SELECT TOP 1 itemNo FROM ac_stock WHERE tanggal ='" . $tanggal . "' AND itemNo ='" . $itemNo . "' AND lokasiGudang ='Utama' ";
        $data_cek = $sqlLib->select($sql_cek);
        //proses stock
        if (count($data_cek) > 0) {
            //update stock in out
            $content = array(
                "fromDate" => $dari,
                "toDate" => $sampai,
                "itemNo" => $itemNo,
                "warehouseName" => "Utama"
            );

            $url = $host . "/accurate/api/report/stock-mutation-summary.do?" . http_build_query($content);

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
            $result = file_get_contents($url, false, $context);

            // Membuat array dari data JSON
            $row_result_stock = json_decode($result);
            foreach ($row_result_stock->d as $row_stock) {
                $awal = $row_stock->startBalance;
                $masuk = $row_stock->quantityIn;
                $keluar = $row_stock->quantityOut;
                $akhir = $row_stock->lastBalance;

                //cek stock sebelumnya
                $tglkemarin = date('Y-m-d', strtotime('-1 days', strtotime($tanggal)));
                $sql_kemarin = "SELECT COALESCE(akhir,NULL,0) as stockkemarin FROM ac_stock WHERE tanggal ='" . $tglkemarin . "' AND itemNo = '" . $itemNo . "' AND lokasiGudang ='Utama' ";
                $data_kemarin = $sqlLib->select($sql_kemarin);
                if ($data_kemarin[0]['stockkemarin'] == "") {
                    $qtyawal = 0;
                } else {
                    $qtyawal = $data_kemarin[0]['stockkemarin'];
                }

                $qtyakhir = $qtyawal + $masuk - $keluar;

                $sql_up = "UPDATE ac_stock SET awal = '" . $qtyawal . "', masuk = '" . $masuk . "',keluar = '" . $keluar . "', akhir = '" . $qtyakhir . "' , adjustment = '0' , so = '0' 
                                WHERE tanggal ='" . $tanggal . "' AND itemNo = '" . $itemNo . "' AND lokasiGudang ='Utama' ";
                $run_up = $sqlLib->update($sql_up);
            }
        } else {
            $sql_save = "INSERT INTO ac_stock (tanggal, itemNo, itemName, unit1Name, itemCategory,  lokasiGudang, itemId, recuser, recdate)
                                VALUES ('" . $tanggal . "','" . $itemNo . "','" . $itemName . "','" . $unit1Name . "','" . $itemCategory . "','Utama','" . $itemId . "', '" . $_SESSION['userid'] . "','" . date("Y-m-d H:i:s") . "') ";
            $run_save = $sqlLib->insert($sql_save);
            //echo $sql_save  ;    
            if ($run_save == "1") {
                $content = array(
                    "fromDate" => $dari,
                    "toDate" => $sampai,
                    "itemNo" => $itemNo,
                    "warehouseName" => "Utama"
                );

                $url = $host . "/accurate/api/report/stock-mutation-summary.do?" . http_build_query($content);

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
                $result = file_get_contents($url, false, $context);

                // Membuat array dari data JSON
                $row_result_stock = json_decode($result);
                foreach ($row_result_stock->d as $row_stock) {
                    $awal = $row_stock->startBalance;
                    $masuk = $row_stock->quantityIn;
                    $keluar = $row_stock->quantityOut;
                    $akhir = $row_stock->lastBalance;

                    //cek stock sebelumnya
                    $tglkemarin = date('Y-m-d', strtotime('-1 days', strtotime($tanggal)));
                    $sql_kemarin = "SELECT COALESCE(akhir,NULL,0) as stockkemarin FROM ac_stock WHERE tanggal ='" . $tglkemarin . "' AND itemNo = '" . $itemNo . "' AND lokasiGudang ='Utama' ";
                    $data_kemarin = $sqlLib->select($sql_kemarin);
                    if ($data_kemarin[0]['stockkemarin'] == "") {
                        $qtyawal = 0;
                    } else {
                        $qtyawal = $data_kemarin[0]['stockkemarin'];
                    }

                    $qtyakhir = $qtyawal + $masuk - $keluar;
                    $sql_up = "UPDATE ac_stock SET awal = '" . $qtyawal . "', masuk = '" . $masuk . "',keluar = '" . $keluar . "', akhir = '" . $qtyakhir . "' , adjustment = '0' , so = '0' 
                                    WHERE tanggal ='" . $tanggal . "' AND itemNo = '" . $itemNo . "' AND lokasiGudang ='Utama' ";
                    $run_up = $sqlLib->update($sql_up);
                }
            }
        }

        //cek data2
        $sql_cek2 = "SELECT TOP 1 itemNo FROM ac_stock WHERE tanggal ='" . $tanggal . "' AND itemNo ='" . $itemNo . "' AND lokasiGudang ='Utama' ";
        $data_cek2 = $sqlLib->select($sql_cek2);
        //proses adjuatment
        if (count($data_cek2) > 0) {
            // Header
            $header = array(
                "Content-Type: application/json",
                "Authorization: Bearer $accessToken",
                "X-SESSION-ID: $session"
            );

            // Content
            $content = array(
                "fields" => "number,transDate,id",
                "filter.lastUpdate.op" => "GREATER_EQUAL_THAN",
                "filter.lastUpdate" => $latsupdate,
            );

            // URL 
            $url = $host . "/accurate/api/item-adjustment/list.do?" . http_build_query($content);

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
            $result = file_get_contents($url, false, $context);

            // Membuat array dari data JSON
            $row_result_api = json_decode($result);
            $jmldata = $row_result_api->sp->rowCount;

            if ($jmldata > 0) {
                foreach ($row_result_api->d as $row_api) {
                    $id = $row_api->id;
                    $transDate = $row_api->transDate;

                    if ($transDate == $dari) {
                        // Content
                        $content = array(
                            "id" => $id
                        );

                        // URL
                        $url = $host . "/accurate/api/item-adjustment/detail.do?" . http_build_query($content);

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
                        $result = file_get_contents($url, false, $context);

                        // Membuat array dari data JSON
                        $row_hd = json_decode($result);
                        $number = $row_hd->d->number;

                        foreach ($row_hd->d->detailItem as $row2) // detail item
                        {
                            $adj = 0;
                            $itemNo = $row2->item->no;
                            $quantity = $row2->quantity;
                            $itemAdjustmentTypeName = $row2->itemAdjustmentTypeName;
                            $warehouse = $row2->warehouse->name;
                            if ($warehouse == 'Utama') {
                                //declear stock
                                $sql_cek_adj = "SELECT TOP 1 itemNo, lokasiGudang, awal, masuk, keluar
                                                FROM ac_stock 
                                                WHERE tanggal ='" . $tanggal . "' AND itemNo ='" . $itemNo . "' AND lokasiGudang ='Utama' ";
                                $data_cek_adj = $sqlLib->select($sql_cek_adj);
                                if ($itemAdjustmentTypeName == "Penambahan") {
                                    $masuk = $masuk - $quantity;
                                    $adj = $quantity;
                                    $qtyakhir = $data_cek_adj[0]['awal'] + $masuk - $data_cek_adj[0]['keluar'] + $adj;
                                    if ($itemNoMs == $itemNo) {
                                        $sql_up_adj = "UPDATE ac_stock SET masuk = '" . $masuk . "', adjustment ='" . $adj . "' , akhir ='" . $qtyakhir . "' 
                                                    WHERE tanggal ='" . $tanggal . "' AND itemNo ='" . $itemNo . "' AND lokasiGudang ='Utama'  ";
                                        $run_up_adj = $sqlLib->update($sql_up_adj);
                                    }
                                } else {
                                    $keluar = $keluar - $quantity;
                                    $adj = $quantity * (-1);
                                    $qtyakhir = $data_cek_adj[0]['awal'] + $data_cek_adj[0]['masuk'] - $keluar + $adj;
                                    if ($itemNoMs == $itemNo) {
                                        $sql_up_adj = "UPDATE ac_stock SET keluar = '" . $keluar . "', adjustment ='" . $adj . "' , akhir ='" . $qtyakhir . "' 
                                                WHERE tanggal ='" . $tanggal . "' AND itemNo ='" . $itemNo . "' AND lokasiGudang ='Utama'  ";
                                        $run_up_adj = $sqlLib->update($sql_up_adj);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        //delete Stock
        $sql_del3 = "DELETE FROM ac_stock WHERE tanggal = '" . $tanggal . "' AND itemNo = '" . $itemNoMs . "'
                             AND awal = '0' AND masuk = '0' AND keluar = '0' AND akhir = '0'  AND adjustment = '0'  AND so = '0'  ";
        $run_del3 = $sqlLib->delete($sql_del3);

        $sukses = 1;
    }

    $sql_save = "INSERT INTO ac_daily_request (TglRequest, Gudang, recuser, recdate)
                                VALUES ('" . $tanggal . "', 'Utama' ,  '" . $_SESSION['userid'] . "','" . date("Y-m-d H:i:s") . "') ";
    $run_save = $sqlLib->insert($sql_save);

    return  $sukses;
}


function getStockProduksi($accessToken, $session, $host, $dari, $sampai, $sqlLib)
{

    $sukses = 0;
    $tanggal_obj2 = DateTime::createFromFormat('d/m/Y', $dari);
    $tanggal = $tanggal_obj2->format('Y-m-d');
    $latsupdate = $dari . " 00:00:00";

    // Header
    $header = array(
        "Content-Type: application/json",
        "Authorization: Bearer $accessToken",
        "X-SESSION-ID: $session"
    );

    // Content
    $content = array(
        "fields" => "id,name,no,itemUnit,itemCategory",
        "filter.itemCategoryId.op" => "EQUAL",
        "filter.itemCategoryId.val[0]" => "100", // Bahan Baku
        "filter.itemCategoryId.val[1]" => "200", // Finish Good
        "filter.itemCategoryId.val[2]" => "201", // Scrap     

    );

    // URL
    $url = $host . "/accurate/api/item/list.do?" . http_build_query($content);

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
    $result = file_get_contents($url, false, $context);

    // Membuat array dari data JSON
    $row_result_api = json_decode($result);

    //delete ac_daily_request
    $sql_del2 = "DELETE FROM ac_daily_request WHERE TglRequest = '" . $tanggal . "' AND Gudang ='Produksi' ";
    $run_del2 = $sqlLib->delete($sql_del2);

    foreach ($row_result_api->d as $row_api) {
        $itemId = $row_api->id;
        $itemNo = $row_api->no;
        $itemNoMs = $row_api->no;
        $itemName = $row_api->name;
        $unit1Name = $row_api->unit1Name;
        $itemCategory = $row_api->itemCategory->name;

        //cek data
        $sql_cek = "SELECT TOP 1 itemNo FROM ac_stock WHERE tanggal ='" . $tanggal . "' AND itemNo ='" . $itemNo . "' AND lokasiGudang ='Produksi' ";
        $data_cek = $sqlLib->select($sql_cek);
        if (count($data_cek) > 0) {
            $content = array(
                "fromDate" => $dari,
                "toDate" => $sampai,
                "itemNo" => $itemNo,
                "warehouseName" => "Produksi"
            );

            $url = $host . "/accurate/api/report/stock-mutation-summary.do?" . http_build_query($content);

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
            $result = file_get_contents($url, false, $context);

            // Membuat array dari data JSON
            $row_result_stock = json_decode($result);
            foreach ($row_result_stock->d as $row_stock) {
                $awal = $row_stock->startBalance;
                $masuk = $row_stock->quantityIn;
                $keluar = $row_stock->quantityOut;
                $akhir = $row_stock->lastBalance;

                //cek stock sebelumnya
                $tglkemarin = date('Y-m-d', strtotime('-1 days', strtotime($tanggal)));
                $sql_kemarin = "SELECT COALESCE(akhir,NULL,0) as stockkemarin FROM ac_stock WHERE tanggal ='" . $tglkemarin . "' AND itemNo = '" . $itemNo . "' AND lokasiGudang ='Produksi' ";
                $data_kemarin = $sqlLib->select($sql_kemarin);
                if ($data_kemarin[0]['stockkemarin'] == "") {
                    $qtyawal = 0;
                } else {
                    $qtyawal = $data_kemarin[0]['stockkemarin'];
                }

                $qtyakhir = $qtyawal + $masuk - $keluar;

                $sql_up = "UPDATE ac_stock SET awal = '" . $qtyawal . "', masuk = '" . $masuk . "',keluar = '" . $keluar . "', akhir = '" . $qtyakhir . "' , adjustment = '0' , so = '0' 
                                WHERE tanggal ='" . $tanggal . "' AND itemNo = '" . $itemNo . "' AND lokasiGudang ='Produksi' ";
                $run_up = $sqlLib->update($sql_up);
            }
        } else {

            $sql_save = "INSERT INTO ac_stock (tanggal, itemNo, itemName, unit1Name, itemCategory,  lokasiGudang, itemId, recuser, recdate)
                                VALUES ('" . $tanggal . "','" . $itemNo . "','" . $itemName . "','" . $unit1Name . "','" . $itemCategory . "','Produksi','" . $itemId . "', '" . $_SESSION['userid'] . "','" . date("Y-m-d H:i:s") . "') ";
            $run_save = $sqlLib->insert($sql_save);
            //echo $sql_save  ;    
            if ($run_save == "1") {
                $content = array(
                    "fromDate" => $dari,
                    "toDate" => $sampai,
                    "itemNo" => $itemNo,
                    "warehouseName" => "Produksi"
                );

                $url = $host . "/accurate/api/report/stock-mutation-summary.do?" . http_build_query($content);

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
                $result = file_get_contents($url, false, $context);

                // Membuat array dari data JSON
                $row_result_stock = json_decode($result);
                foreach ($row_result_stock->d as $row_stock) {
                    $awal = $row_stock->startBalance;
                    $masuk = $row_stock->quantityIn;
                    $keluar = $row_stock->quantityOut;
                    $akhir = $row_stock->lastBalance;

                    //cek stock sebelumnya
                    $tglkemarin = date('Y-m-d', strtotime('-1 days', strtotime($tanggal)));
                    $sql_kemarin = "SELECT COALESCE(akhir,NULL,0) as stockkemarin FROM ac_stock WHERE tanggal ='" . $tglkemarin . "' AND itemNo = '" . $itemNo . "' AND lokasiGudang ='Produksi' ";
                    $data_kemarin = $sqlLib->select($sql_kemarin);
                    if ($data_kemarin[0]['stockkemarin'] == "") {
                        $qtyawal = 0;
                    } else {
                        $qtyawal = $data_kemarin[0]['stockkemarin'];
                    }
                    $qtyakhir = $qtyawal + $masuk - $keluar;

                    $sql_up = "UPDATE ac_stock SET awal = '" . $qtyawal . "', masuk = '" . $masuk . "',keluar = '" . $keluar . "', akhir = '" . $qtyakhir . "' , adjustment = '0' , so = '0' 
                                    WHERE tanggal ='" . $tanggal . "' AND itemNo = '" . $itemNo . "' AND lokasiGudang ='Produksi' ";
                    $run_up = $sqlLib->update($sql_up);
                }
            }
        }

        //cek data2
        $sql_cek2 = "SELECT TOP 1 itemNo FROM ac_stock WHERE tanggal ='" . $tanggal . "' AND itemNo ='" . $itemNo . "' AND lokasiGudang ='Produksi' ";
        $data_cek2 = $sqlLib->select($sql_cek2);
        //proses adjuatment
        if (count($data_cek2) > 0) {
            // Header
            $header = array(
                "Content-Type: application/json",
                "Authorization: Bearer $accessToken",
                "X-SESSION-ID: $session"
            );

            // Content
            $content = array(
                "fields" => "number,transDate,id",
                "filter.lastUpdate.op" => "GREATER_EQUAL_THAN",
                "filter.lastUpdate" => $latsupdate,
            );

            // URL 
            $url = $host . "/accurate/api/item-adjustment/list.do?" . http_build_query($content);

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
            $result = file_get_contents($url, false, $context);

            // Membuat array dari data JSON
            $row_result_api = json_decode($result);
            $jmldata = $row_result_api->sp->rowCount;

            if ($jmldata > 0) {
                foreach ($row_result_api->d as $row_api) {
                    $id = $row_api->id;
                    $transDate = $row_api->transDate;

                    if ($transDate == $dari) {
                        // Content
                        $content = array(
                            "id" => $id
                        );

                        // URL
                        $url = $host . "/accurate/api/item-adjustment/detail.do?" . http_build_query($content);

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
                        $result = file_get_contents($url, false, $context);

                        // Membuat array dari data JSON
                        $row_hd = json_decode($result);
                        $number = $row_hd->d->number;

                        foreach ($row_hd->d->detailItem as $row2) // detail item
                        {
                            $adj = 0;
                            $itemNo = $row2->item->no;
                            $quantity = $row2->quantity;
                            $itemAdjustmentTypeName = $row2->itemAdjustmentTypeName;
                            $warehouse = $row2->warehouse->name;
                            if ($warehouse == 'Produksi') {
                                //declear stock
                                $sql_cek_adj = "SELECT TOP 1 itemNo, lokasiGudang, awal, masuk, keluar
                                                FROM ac_stock 
                                                WHERE tanggal ='" . $tanggal . "' AND itemNo ='" . $itemNo . "' AND lokasiGudang ='Produksi' ";
                                $data_cek_adj = $sqlLib->select($sql_cek_adj);
                                if ($itemAdjustmentTypeName == "Penambahan") {
                                    $masuk = $masuk - $quantity;
                                    $adj = $quantity;
                                    $qtyakhir = $data_cek_adj[0]['awal'] + $masuk - $data_cek_adj[0]['keluar'] + $adj;
                                    if ($itemNoMs == $itemNo) {
                                        $sql_up_adj = "UPDATE ac_stock SET masuk = '" . $masuk . "', adjustment ='" . $adj . "' , akhir ='" . $qtyakhir . "' 
                                                    WHERE tanggal ='" . $tanggal . "' AND itemNo ='" . $itemNo . "' AND lokasiGudang ='Produksi'  ";
                                        $run_up_adj = $sqlLib->update($sql_up_adj);
                                    }
                                } else {
                                    $keluar = $keluar - $quantity;
                                    $adj = $quantity * (-1);
                                    $qtyakhir = $data_cek_adj[0]['awal'] + $data_cek_adj[0]['masuk'] - $keluar + $adj;
                                    if ($itemNoMs == $itemNo) {
                                        $sql_up_adj = "UPDATE ac_stock SET keluar = '" . $keluar . "', adjustment ='" . $adj . "' , akhir ='" . $qtyakhir . "' 
                                                WHERE tanggal ='" . $tanggal . "' AND itemNo ='" . $itemNo . "' AND lokasiGudang ='Produksi'  ";
                                        $run_up_adj = $sqlLib->update($sql_up_adj);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        //delete Stock
        $sql_del3 = "DELETE FROM ac_stock WHERE tanggal = '" . $tanggal . "' AND itemNo = '" . $itemNoMs . "'
                             AND awal = '0' AND masuk = '0' AND keluar = '0' AND akhir = '0'  AND adjustment = '0'  AND so = '0'  ";
        $run_del3 = $sqlLib->delete($sql_del3);

        $sukses = 1;
    }

    $sql_save = "INSERT INTO ac_daily_request (TglRequest, Gudang, recuser, recdate)
                                VALUES ('" . $tanggal . "','Produksi', '" . $_SESSION['userid'] . "','" . date("Y-m-d H:i:s") . "') ";
    $run_save = $sqlLib->insert($sql_save);

    return  $sukses;
}

function getStockFg($accessToken, $session, $host, $dari, $sampai, $sqlLib)
{

    $sukses = 0;
    $tanggal_obj2 = DateTime::createFromFormat('d/m/Y', $dari);
    $tanggal = $tanggal_obj2->format('Y-m-d');
    $latsupdate = $dari . " 00:00:00";

    // Header
    $header = array(
        "Content-Type: application/json",
        "Authorization: Bearer $accessToken",
        "X-SESSION-ID: $session"
    );

    // Content
    $content = array(
        "fields" => "id,name,no,itemUnit,itemCategory",
        "filter.itemCategoryId.op" => "EQUAL",
        "filter.itemCategoryId.val[0]" => "200", // Finish Good

    );

    // URL
    $url = $host . "/accurate/api/item/list.do?" . http_build_query($content);

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
    $result = file_get_contents($url, false, $context);

    // Membuat array dari data JSON
    $row_result_api = json_decode($result);

    //delete ac_daily_request
    $sql_del2 = "DELETE FROM ac_daily_request WHERE TglRequest = '" . $tanggal . "' AND Gudang ='Finish Good' ";
    $run_del2 = $sqlLib->delete($sql_del2);

    foreach ($row_result_api->d as $row_api) {
        $itemId = $row_api->id;
        $itemNo = $row_api->no;
        $itemNoMs = $row_api->no;
        $itemName = $row_api->name;
        $unit1Name = $row_api->unit1Name;
        $itemCategory = $row_api->itemCategory->name;

        //cek data
        $sql_cek = "SELECT TOP 1 itemNo FROM ac_stock WHERE tanggal ='" . $tanggal . "' AND itemNo ='" . $itemNo . "' AND lokasiGudang ='Finish Good' ";
        $data_cek = $sqlLib->select($sql_cek);
        if (count($data_cek) > 0) {
            $content = array(
                "fromDate" => $dari,
                "toDate" => $sampai,
                "itemNo" => $itemNo,
                "warehouseName" => "Finish Good"
            );

            $url = $host . "/accurate/api/report/stock-mutation-summary.do?" . http_build_query($content);

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
            $result = file_get_contents($url, false, $context);

            // Membuat array dari data JSON
            $row_result_stock = json_decode($result);
            foreach ($row_result_stock->d as $row_stock) {
                $awal = $row_stock->startBalance;
                $masuk = $row_stock->quantityIn;
                $keluar = $row_stock->quantityOut;
                $akhir = $row_stock->lastBalance;

                //cek stock sebelumnya
                $tglkemarin = date('Y-m-d', strtotime('-1 days', strtotime($tanggal)));
                $sql_kemarin = "SELECT COALESCE(akhir,NULL,0) as stockkemarin FROM ac_stock WHERE tanggal ='" . $tglkemarin . "' AND itemNo = '" . $itemNo . "' AND lokasiGudang ='Finish Good' ";
                $data_kemarin = $sqlLib->select($sql_kemarin);
                if ($data_kemarin[0]['stockkemarin'] == "") {
                    $qtyawal = 0;
                } else {
                    $qtyawal = $data_kemarin[0]['stockkemarin'];
                }

                $qtyakhir = $qtyawal + $masuk - $keluar;

                $sql_up = "UPDATE ac_stock SET awal = '" . $qtyawal . "', masuk = '" . $masuk . "',keluar = '" . $keluar . "', akhir = '" . $qtyakhir . "' , adjustment = '0' , so = '0' 
                                WHERE tanggal ='" . $tanggal . "' AND itemNo = '" . $itemNo . "' AND lokasiGudang ='Finish Good' ";
                $run_up = $sqlLib->update($sql_up);
            }
        } else {

            $sql_save = "INSERT INTO ac_stock (tanggal, itemNo, itemName, unit1Name, itemCategory,  lokasiGudang, itemId, recuser, recdate)
                                VALUES ('" . $tanggal . "','" . $itemNo . "','" . $itemName . "','" . $unit1Name . "','" . $itemCategory . "','Finish Good','" . $itemId . "', '" . $_SESSION['userid'] . "','" . date("Y-m-d H:i:s") . "') ";
            $run_save = $sqlLib->insert($sql_save);
            //echo $sql_save  ;    
            if ($run_save == "1") {
                $content = array(
                    "fromDate" => $dari,
                    "toDate" => $sampai,
                    "itemNo" => $itemNo,
                    "warehouseName" => "Finish Good"
                );

                $url = $host . "/accurate/api/report/stock-mutation-summary.do?" . http_build_query($content);

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
                $result = file_get_contents($url, false, $context);

                // Membuat array dari data JSON
                $row_result_stock = json_decode($result);
                foreach ($row_result_stock->d as $row_stock) {
                    $awal = $row_stock->startBalance;
                    $masuk = $row_stock->quantityIn;
                    $keluar = $row_stock->quantityOut;
                    $akhir = $row_stock->lastBalance;

                    //cek stock sebelumnya
                    $tglkemarin = date('Y-m-d', strtotime('-1 days', strtotime($tanggal)));
                    $sql_kemarin = "SELECT COALESCE(akhir,NULL,0) as stockkemarin FROM ac_stock WHERE tanggal ='" . $tglkemarin . "' AND itemNo = '" . $itemNo . "' AND lokasiGudang ='Finish Good' ";
                    $data_kemarin = $sqlLib->select($sql_kemarin);
                    if ($data_kemarin[0]['stockkemarin'] == "") {
                        $qtyawal = 0;
                    } else {
                        $qtyawal = $data_kemarin[0]['stockkemarin'];
                    }
                    $qtyakhir = $qtyawal + $masuk - $keluar;

                    $sql_up = "UPDATE ac_stock SET awal = '" . $qtyawal . "', masuk = '" . $masuk . "',keluar = '" . $keluar . "', akhir = '" . $qtyakhir . "' , adjustment = '0' , so = '0' 
                                    WHERE tanggal ='" . $tanggal . "' AND itemNo = '" . $itemNo . "' AND lokasiGudang ='Finish Good' ";
                    $run_up = $sqlLib->update($sql_up);
                }
            }
        }

        //cek data2
        $sql_cek2 = "SELECT TOP 1 itemNo FROM ac_stock WHERE tanggal ='" . $tanggal . "' AND itemNo ='" . $itemNo . "' AND lokasiGudang ='Finish Good' ";
        $data_cek2 = $sqlLib->select($sql_cek2);
        //proses adjuatment
        if (count($data_cek2) > 0) {
            // Header
            $header = array(
                "Content-Type: application/json",
                "Authorization: Bearer $accessToken",
                "X-SESSION-ID: $session"
            );

            // Content
            $content = array(
                "fields" => "number,transDate,id",
                "filter.lastUpdate.op" => "GREATER_EQUAL_THAN",
                "filter.lastUpdate" => $latsupdate,
            );

            // URL 
            $url = $host . "/accurate/api/item-adjustment/list.do?" . http_build_query($content);

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
            $result = file_get_contents($url, false, $context);

            // Membuat array dari data JSON
            $row_result_api = json_decode($result);
            $jmldata = $row_result_api->sp->rowCount;

            if ($jmldata > 0) {
                foreach ($row_result_api->d as $row_api) {
                    $id = $row_api->id;
                    $transDate = $row_api->transDate;

                    if ($transDate == $dari) {
                        // Content
                        $content = array(
                            "id" => $id
                        );

                        // URL
                        $url = $host . "/accurate/api/item-adjustment/detail.do?" . http_build_query($content);

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
                        $result = file_get_contents($url, false, $context);

                        // Membuat array dari data JSON
                        $row_hd = json_decode($result);
                        $number = $row_hd->d->number;

                        foreach ($row_hd->d->detailItem as $row2) // detail item
                        {
                            $adj = 0;
                            $itemNo = $row2->item->no;
                            $quantity = $row2->quantity;
                            $itemAdjustmentTypeName = $row2->itemAdjustmentTypeName;
                            $warehouse = $row2->warehouse->name;
                            if ($warehouse == 'Finish Good') {
                                //declear stock
                                $sql_cek_adj = "SELECT TOP 1 itemNo, lokasiGudang, awal, masuk, keluar
                                                FROM ac_stock 
                                                WHERE tanggal ='" . $tanggal . "' AND itemNo ='" . $itemNo . "' AND lokasiGudang ='Finish Good' ";
                                $data_cek_adj = $sqlLib->select($sql_cek_adj);
                                if ($itemAdjustmentTypeName == "Penambahan") {
                                    $masuk = $masuk - $quantity;
                                    $adj = $quantity;
                                    $qtyakhir = $data_cek_adj[0]['awal'] + $masuk - $data_cek_adj[0]['keluar'] + $adj;
                                    if ($itemNoMs == $itemNo) {
                                        $sql_up_adj = "UPDATE ac_stock SET masuk = '" . $masuk . "', adjustment ='" . $adj . "' , akhir ='" . $qtyakhir . "' 
                                                    WHERE tanggal ='" . $tanggal . "' AND itemNo ='" . $itemNo . "' AND lokasiGudang ='Finish Good'  ";
                                        $run_up_adj = $sqlLib->update($sql_up_adj);
                                    }
                                } else {
                                    $keluar = $keluar - $quantity;
                                    $adj = $quantity * (-1);
                                    $qtyakhir = $data_cek_adj[0]['awal'] + $data_cek_adj[0]['masuk'] - $keluar + $adj;
                                    if ($itemNoMs == $itemNo) {
                                        $sql_up_adj = "UPDATE ac_stock SET keluar = '" . $keluar . "', adjustment ='" . $adj . "' , akhir ='" . $qtyakhir . "' 
                                                WHERE tanggal ='" . $tanggal . "' AND itemNo ='" . $itemNo . "' AND lokasiGudang ='Finish Good'  ";
                                        $run_up_adj = $sqlLib->update($sql_up_adj);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        //delete Stock
        $sql_del3 = "DELETE FROM ac_stock WHERE tanggal = '" . $tanggal . "' AND itemNo = '" . $itemNoMs . "'
                             AND awal = '0' AND masuk = '0' AND keluar = '0' AND akhir = '0'  AND adjustment = '0'  AND so = '0'  ";
        $run_del3 = $sqlLib->delete($sql_del3);

        $sukses = 1;
    }

    $sql_save = "INSERT INTO ac_daily_request (TglRequest, Gudang, recuser, recdate)
                                VALUES ('" . $tanggal . "','Finish Good', '" . $_SESSION['userid'] . "','" . date("Y-m-d H:i:s") . "') ";
    $run_save = $sqlLib->insert($sql_save);

    return  $sukses;
}

function getStockScrap($accessToken, $session, $host, $dari, $sampai, $sqlLib)
{

    $sukses = 0;
    $tanggal_obj2 = DateTime::createFromFormat('d/m/Y', $dari);
    $tanggal = $tanggal_obj2->format('Y-m-d');
    $latsupdate = $dari . " 00:00:00";

    // Header
    $header = array(
        "Content-Type: application/json",
        "Authorization: Bearer $accessToken",
        "X-SESSION-ID: $session"
    );

    // Content
    $content = array(
        "fields" => "id,name,no,itemUnit,itemCategory",
        "filter.itemCategoryId.op" => "EQUAL",
        "filter.itemCategoryId.val[0]" => "201", // Scrap
    );

    // URL
    $url = $host . "/accurate/api/item/list.do?" . http_build_query($content);

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
    $result = file_get_contents($url, false, $context);

    // Membuat array dari data JSON
    $row_result_api = json_decode($result);

    //delete ac_daily_request
    $sql_del2 = "DELETE FROM ac_daily_request WHERE TglRequest = '" . $tanggal . "' AND Gudang ='Scrap' ";
    $run_del2 = $sqlLib->delete($sql_del2);

    foreach ($row_result_api->d as $row_api) {
        $itemId = $row_api->id;
        $itemNo = $row_api->no;
        $itemNoMs = $row_api->no;
        $itemName = $row_api->name;
        $unit1Name = $row_api->unit1Name;
        $itemCategory = $row_api->itemCategory->name;

        //cek data
        $sql_cek = "SELECT TOP 1 itemNo FROM ac_stock WHERE tanggal ='" . $tanggal . "' AND itemNo ='" . $itemNo . "' AND lokasiGudang ='Scrap' ";
        $data_cek = $sqlLib->select($sql_cek);
        if (count($data_cek) > 0) {
            $content = array(
                "fromDate" => $dari,
                "toDate" => $sampai,
                "itemNo" => $itemNo,
                "warehouseName" => "Scrap"
            );

            $url = $host . "/accurate/api/report/stock-mutation-summary.do?" . http_build_query($content);

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
            $result = file_get_contents($url, false, $context);

            // Membuat array dari data JSON
            $row_result_stock = json_decode($result);
            foreach ($row_result_stock->d as $row_stock) {
                $awal = $row_stock->startBalance;
                $masuk = $row_stock->quantityIn;
                $keluar = $row_stock->quantityOut;
                $akhir = $row_stock->lastBalance;

                //cek stock sebelumnya
                $tglkemarin = date('Y-m-d', strtotime('-1 days', strtotime($tanggal)));
                $sql_kemarin = "SELECT COALESCE(akhir,NULL,0) as stockkemarin FROM ac_stock WHERE tanggal ='" . $tglkemarin . "' AND itemNo = '" . $itemNo . "' AND lokasiGudang ='Scrap' ";
                $data_kemarin = $sqlLib->select($sql_kemarin);
                if ($data_kemarin[0]['stockkemarin'] == "") {
                    $qtyawal = 0;
                } else {
                    $qtyawal = $data_kemarin[0]['stockkemarin'];
                }

                $qtyakhir = $qtyawal + $masuk - $keluar;

                $sql_up = "UPDATE ac_stock SET awal = '" . $qtyawal . "', masuk = '" . $masuk . "',keluar = '" . $keluar . "', akhir = '" . $qtyakhir . "' , adjustment = '0' , so = '0' 
                                WHERE tanggal ='" . $tanggal . "' AND itemNo = '" . $itemNo . "' AND lokasiGudang ='Scrap' ";
                $run_up = $sqlLib->update($sql_up);
            }
        } else {

            $sql_save = "INSERT INTO ac_stock (tanggal, itemNo, itemName, unit1Name, itemCategory,  lokasiGudang, itemId, recuser, recdate)
                                VALUES ('" . $tanggal . "','" . $itemNo . "','" . $itemName . "','" . $unit1Name . "','" . $itemCategory . "','Scrap','" . $itemId . "', '" . $_SESSION['userid'] . "','" . date("Y-m-d H:i:s") . "') ";
            $run_save = $sqlLib->insert($sql_save);
            //echo $sql_save  ;    
            if ($run_save == "1") {
                $content = array(
                    "fromDate" => $dari,
                    "toDate" => $sampai,
                    "itemNo" => $itemNo,
                    "warehouseName" => "Scrap"
                );

                $url = $host . "/accurate/api/report/stock-mutation-summary.do?" . http_build_query($content);

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
                $result = file_get_contents($url, false, $context);

                // Membuat array dari data JSON
                $row_result_stock = json_decode($result);
                foreach ($row_result_stock->d as $row_stock) {
                    $awal = $row_stock->startBalance;
                    $masuk = $row_stock->quantityIn;
                    $keluar = $row_stock->quantityOut;
                    $akhir = $row_stock->lastBalance;

                    //cek stock sebelumnya
                    $tglkemarin = date('Y-m-d', strtotime('-1 days', strtotime($tanggal)));
                    $sql_kemarin = "SELECT COALESCE(akhir,NULL,0) as stockkemarin FROM ac_stock WHERE tanggal ='" . $tglkemarin . "' AND itemNo = '" . $itemNo . "' AND lokasiGudang ='Scrap' ";
                    $data_kemarin = $sqlLib->select($sql_kemarin);
                    if ($data_kemarin[0]['stockkemarin'] == "") {
                        $qtyawal = 0;
                    } else {
                        $qtyawal = $data_kemarin[0]['stockkemarin'];
                    }
                    $qtyakhir = $qtyawal + $masuk - $keluar;

                    $sql_up = "UPDATE ac_stock SET awal = '" . $qtyawal . "', masuk = '" . $masuk . "',keluar = '" . $keluar . "', akhir = '" . $qtyakhir . "' , adjustment = '0' , so = '0' 
                                    WHERE tanggal ='" . $tanggal . "' AND itemNo = '" . $itemNo . "' AND lokasiGudang ='Scrap' ";
                    $run_up = $sqlLib->update($sql_up);
                }
            }
        }

        //cek data2
        $sql_cek2 = "SELECT TOP 1 itemNo FROM ac_stock WHERE tanggal ='" . $tanggal . "' AND itemNo ='" . $itemNo . "' AND lokasiGudang ='Scrap' ";
        $data_cek2 = $sqlLib->select($sql_cek2);
        //proses adjuatment
        if (count($data_cek2) > 0) {
            // Header
            $header = array(
                "Content-Type: application/json",
                "Authorization: Bearer $accessToken",
                "X-SESSION-ID: $session"
            );

            // Content
            $content = array(
                "fields" => "number,transDate,id",
                "filter.lastUpdate.op" => "GREATER_EQUAL_THAN",
                "filter.lastUpdate" => $latsupdate,
            );

            // URL 
            $url = $host . "/accurate/api/item-adjustment/list.do?" . http_build_query($content);

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
            $result = file_get_contents($url, false, $context);

            // Membuat array dari data JSON
            $row_result_api = json_decode($result);
            $jmldata = $row_result_api->sp->rowCount;

            if ($jmldata > 0) {
                foreach ($row_result_api->d as $row_api) {
                    $id = $row_api->id;
                    $transDate = $row_api->transDate;

                    if ($transDate == $dari) {
                        // Content
                        $content = array(
                            "id" => $id
                        );

                        // URL
                        $url = $host . "/accurate/api/item-adjustment/detail.do?" . http_build_query($content);

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
                        $result = file_get_contents($url, false, $context);

                        // Membuat array dari data JSON
                        $row_hd = json_decode($result);
                        $number = $row_hd->d->number;

                        foreach ($row_hd->d->detailItem as $row2) // detail item
                        {
                            $adj = 0;
                            $itemNo = $row2->item->no;
                            $quantity = $row2->quantity;
                            $itemAdjustmentTypeName = $row2->itemAdjustmentTypeName;
                            $warehouse = $row2->warehouse->name;
                            if ($warehouse == 'Scrap') {

                                //declear stock
                                $sql_cek_adj = "SELECT TOP 1 itemNo, lokasiGudang, awal, masuk, keluar
                                                FROM ac_stock 
                                                WHERE tanggal ='" . $tanggal . "' AND itemNo ='" . $itemNo . "' AND lokasiGudang ='Scrap' ";
                                $data_cek_adj = $sqlLib->select($sql_cek_adj);
                                if ($itemAdjustmentTypeName == "Penambahan") {
                                    $masuk = $masuk - $quantity;
                                    $adj = $quantity;
                                    $qtyakhir = $data_cek_adj[0]['awal'] + $masuk - $data_cek_adj[0]['keluar'] + $adj;
                                    if ($itemNoMs == $itemNo) {
                                        $sql_up_adj = "UPDATE ac_stock SET masuk = '" . $masuk . "', adjustment ='" . $adj . "' , akhir ='" . $qtyakhir . "' 
                                                    WHERE tanggal ='" . $tanggal . "' AND itemNo ='" . $itemNo . "' AND lokasiGudang ='Scrap'  ";
                                        $run_up_adj = $sqlLib->update($sql_up_adj);
                                    }
                                } else {
                                    $keluar = $keluar - $quantity;
                                    $adj = $quantity * (-1);
                                    $qtyakhir = $data_cek_adj[0]['awal'] + $data_cek_adj[0]['masuk'] - $keluar + $adj;
                                    if ($itemNoMs == $itemNo) {
                                        $sql_up_adj = "UPDATE ac_stock SET keluar = '" . $keluar . "', adjustment ='" . $adj . "' , akhir ='" . $qtyakhir . "' 
                                                WHERE tanggal ='" . $tanggal . "' AND itemNo ='" . $itemNo . "' AND lokasiGudang ='Scrap'  ";
                                        $run_up_adj = $sqlLib->update($sql_up_adj);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        //delete Stock
        $sql_del3 = "DELETE FROM ac_stock WHERE tanggal = '" . $tanggal . "' AND itemNo = '" . $itemNoMs . "'
                             AND awal = '0' AND masuk = '0' AND keluar = '0' AND akhir = '0'  AND adjustment = '0'  AND so = '0'  ";
        $run_del3 = $sqlLib->delete($sql_del3);

        $sukses = 1;
    }

    $sql_save = "INSERT INTO ac_daily_request (TglRequest, Gudang, recuser, recdate)
                                VALUES ('" . $tanggal . "','Scrap', '" . $_SESSION['userid'] . "','" . date("Y-m-d H:i:s") . "') ";
    $run_save = $sqlLib->insert($sql_save);

    return  $sukses;
}

function getStockMesin_($accessToken, $session, $host, $dari, $sampai, $sqlLib)
{

    $sukses = 0;
    $tanggal_obj2 = DateTime::createFromFormat('d/m/Y', $dari);
    $tanggal = $tanggal_obj2->format('Y-m-d');

    // Header
    $header = array(
        "Content-Type: application/json",
        "Authorization: Bearer $accessToken",
        "X-SESSION-ID: $session"
    );

    // Content
    $content = array(
        "fields" => "id,name,no,itemUnit,itemCategory",
        "filter.itemCategoryId.op" => "EQUAL",
        "filter.itemCategoryId.val[0]" => "101", // Mesin
    );

    // URL
    $url = $host . "/accurate/api/item/list.do?" . http_build_query($content);

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
    $result = file_get_contents($url, false, $context);

    // Membuat array dari data JSON
    $row_result_api = json_decode($result);

    //delete ac_daily_request
    $sql_del2 = "DELETE FROM ac_daily_request WHERE TglRequest = '" . $tanggal . "' AND Gudang ='Barang Modal' ";
    $run_del2 = $sqlLib->delete($sql_del2);

    foreach ($row_result_api->d as $row_api) {
        $itemId = $row_api->id;
        $itemNo = $row_api->no;
        $itemName = $row_api->name;
        $unit1Name = $row_api->unit1Name;
        $itemCategory = $row_api->itemCategory->name;

        //cek data
        $sql_cek = "SELECT TOP 1 itemNo FROM ac_stock WHERE tanggal ='" . $tanggal . "' AND itemNo ='" . $itemNo . "' AND lokasiGudang ='Barang Modal' ";
        $data_cek = $sqlLib->select($sql_cek);
        if (count($data_cek) > 0) {
            $content = array(
                "fromDate" => $dari,
                "toDate" => $sampai,
                "itemNo" => $itemNo,
                "warehouseName" => "Barang Modal"
            );

            $url = $host . "/accurate/api/report/stock-mutation-summary.do?" . http_build_query($content);

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
            $result = file_get_contents($url, false, $context);

            // Membuat array dari data JSON
            $row_result_stock = json_decode($result);
            foreach ($row_result_stock->d as $row_stock) {
                $awal = $row_stock->startBalance;
                $masuk = $row_stock->quantityIn;
                $keluar = $row_stock->quantityOut;
                $akhir = $row_stock->lastBalance;

                //cek stock sebelumnya
                $tglkemarin = date('Y-m-d', strtotime('-1 days', strtotime($tanggal)));
                $sql_kemarin = "SELECT COALESCE(akhir,NULL,0) as stockkemarin FROM ac_stock WHERE tanggal ='" . $tglkemarin . "' AND itemNo = '" . $itemNo . "' AND lokasiGudang ='Barang Modal' ";
                $data_kemarin = $sqlLib->select($sql_kemarin);
                if ($data_kemarin[0]['stockkemarin'] == "") {
                    $qtyawal = 0;
                } else {
                    $qtyawal = $data_kemarin[0]['stockkemarin'];
                }

                $qtyakhir = $qtyawal + $masuk - $keluar;

                $sql_up = "UPDATE ac_stock SET awal = '" . $qtyawal . "', masuk = '" . $masuk . "',keluar = '" . $keluar . "', akhir = '" . $qtyakhir . "' , adjustment = '0' , so = '0' 
                                WHERE tanggal ='" . $tanggal . "' AND itemNo = '" . $itemNo . "' AND lokasiGudang ='Barang Modal' ";
                $run_up = $sqlLib->update($sql_up);
            }
        } else {

            $sql_save = "INSERT INTO ac_stock (tanggal, itemNo, itemName, unit1Name, itemCategory,  lokasiGudang, itemId, recuser, recdate)
                                VALUES ('" . $tanggal . "','" . $itemNo . "','" . $itemName . "','" . $unit1Name . "','" . $itemCategory . "','Barang Modal','" . $itemId . "', '" . $_SESSION['userid'] . "','" . date("Y-m-d H:i:s") . "') ";
            $run_save = $sqlLib->insert($sql_save);
            //echo $sql_save  ;    
            if ($run_save == "1") {
                $content = array(
                    "fromDate" => $dari,
                    "toDate" => $sampai,
                    "itemNo" => $itemNo,
                    "warehouseName" => "Barang Modal"
                );

                $url = $host . "/accurate/api/report/stock-mutation-summary.do?" . http_build_query($content);

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
                $result = file_get_contents($url, false, $context);

                // Membuat array dari data JSON
                $row_result_stock = json_decode($result);
                foreach ($row_result_stock->d as $row_stock) {
                    $awal = $row_stock->startBalance;
                    $masuk = $row_stock->quantityIn;
                    $keluar = $row_stock->quantityOut;
                    $akhir = $row_stock->lastBalance;

                    //cek stock sebelumnya
                    $tglkemarin = date('Y-m-d', strtotime('-1 days', strtotime($tanggal)));
                    $sql_kemarin = "SELECT COALESCE(akhir,NULL,0) as stockkemarin FROM ac_stock WHERE tanggal ='" . $tglkemarin . "' AND itemNo = '" . $itemNo . "' AND lokasiGudang ='Barang Modal' ";
                    $data_kemarin = $sqlLib->select($sql_kemarin);
                    if ($data_kemarin[0]['stockkemarin'] == "") {
                        $qtyawal = 0;
                    } else {
                        $qtyawal = $data_kemarin[0]['stockkemarin'];
                    }
                    $qtyakhir = $qtyawal + $masuk - $keluar;

                    $sql_up = "UPDATE ac_stock SET awal = '" . $qtyawal . "', masuk = '" . $masuk . "',keluar = '" . $keluar . "', akhir = '" . $qtyakhir . "' , adjustment = '0' , so = '0' 
                                    WHERE tanggal ='" . $tanggal . "' AND itemNo = '" . $itemNo . "' AND lokasiGudang ='Barang Modal' ";
                    $run_up = $sqlLib->update($sql_up);
                }
            }
        }

        //delete Stock
        $sql_del3 = "DELETE FROM ac_stock WHERE tanggal = '" . $tanggal . "' AND itemNo = '" . $itemNo . "'
                             AND awal = '0' AND masuk = '0' AND keluar = '0' AND akhir = '0'  AND adjustment = '0'  AND so = '0'  ";
        $run_del3 = $sqlLib->delete($sql_del3);

        $sukses = 1;
    }

    $sql_save = "INSERT INTO ac_daily_request (TglRequest, Gudang, recuser, recdate)
                                VALUES ('" . $tanggal . "','Barang Modal', '" . $_SESSION['userid'] . "','" . date("Y-m-d H:i:s") . "') ";
    $run_save = $sqlLib->insert($sql_save);

    return  $sukses;
}

function getStockMesin($accessToken, $session, $host, $dari, $sampai, $sqlLib)
{

    $sukses = 0;
    $tanggal_obj2 = DateTime::createFromFormat('d/m/Y', $dari);
    $tanggal = $tanggal_obj2->format('Y-m-d');
    $latsupdate = $dari . " 00:00:00";
    $endupdate = $dari . " 23:59:00";
    
    //================== Setting Stock Awal =====================//
    //cek data kemarin untuk create stock awal
    $tglkemarin = date('Y-m-d', strtotime('-1 days', strtotime($tanggal)));
    $sql_kemarin = "SELECT itemNo, itemName, unit1Name, itemCategory, COALESCE(akhir,NULL,0) as stockkemarin 
                    FROM ac_stock 
                    WHERE tanggal = '" . $tglkemarin . "' AND lokasiGudang ='Barang Modal' Order By tanggal desc ";
    $data_kemarin = $sqlLib->select($sql_kemarin);
    
    if(count($data_kemarin)>0)
    {   
        //delete ac_stock
        $sql_del1 = "DELETE FROM ac_stock WHERE tanggal ='" . $tanggal . "' AND lokasiGudang ='Barang Modal' ";
        $run_del1 = $sqlLib->delete($sql_del1);    
        

        foreach ($data_kemarin as $row_kemarin) {

            if ($row_kemarin['stockkemarin'] == "") {
                $qtyawal = 0;
            } else {
                $qtyawal = $row_kemarin['stockkemarin'];
            }

            $sql_save = "INSERT INTO ac_stock (tanggal, itemNo, itemName, unit1Name, itemCategory, awal, masuk, keluar, akhir, adjustment, so, lokasiGudang, itemId, recuser, recdate)
                            VALUES ('" . $tanggal . "','" . $row_kemarin['itemNo'] . "','" . $row_kemarin['itemName'] . "','" . $row_kemarin['unit1Name'] . "','" . $row_kemarin['itemCategory'] . "',
                                    '" . $qtyawal . "','0','0','" .$qtyawal. "','0', '0', 'Barang Modal','', '" . $_SESSION['userid'] . "','" . date("Y-m-d H:i:s") . "') ";
            $run_save = $sqlLib->insert($sql_save);

        }
    }    

    //================= Declear Data Aset Per Tanggal ==================//
    // Header
    $header = array(
        "Content-Type: application/json",
        "Authorization: Bearer $accessToken",
        "X-SESSION-ID: $session"
    );

    // Content
    $content = array(
        "fields" => "id",
        "filter.lastUpdate.op" => "BETWEEN",
        "filter.lastUpdate.val[0]" => $latsupdate, 
        "filter.lastUpdate.val[1]" => $endupdate,
    );

    // URL
    $url = $host . "/accurate/api/fixed-asset/list.do?" . http_build_query($content);

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
    $result = file_get_contents($url, false, $context);

    // Membuat array dari data JSON
    $row_result_api = json_decode($result);
    $jmldata = $row_result_api->sp->rowCount;
    $idnum = $row_result_api->d->id;

    //delete ac_daily_request
    $sql_del2 = "DELETE FROM ac_daily_request WHERE TglRequest = '" . $tanggal . "' AND Gudang ='Barang Modal' ";
    $run_del2 = $sqlLib->delete($sql_del2);

    
    //=========== Jika data ada maka proses simpan ==================//
    if ($jmldata > 0) {
        foreach ($row_result_api->d as $row_api) 
        {
            $id = $row_api->id;

            // Header
            $header = array(
                "Content-Type: application/json",
                "Authorization: Bearer $accessToken",
                "X-SESSION-ID: $session"
            );

            // Content
            $content = array(
                "id" => $id,
                
            );

            // URL
            $url = $host . "/accurate/api/fixed-asset/detail.do?" . http_build_query($content);

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
            $result = file_get_contents($url, false, $context);

            // Membuat array dari data JSON
            $row_result_detail = json_decode($result);

            
            $transDate = $row_result_detail->d->transDate;
            $lastJournalDate = $row_result_detail->d->lastJournalDate;
            $itemNo = $row_result_detail->d->number;
            $draft  = $row_result_detail->d->draft;
            $quantityDisposed  = $row_result_detail->d->quantityDisposed;
            $quantity  = $row_result_detail->d->quantity;
            $itemName = $row_result_detail->d->description;
            $itemCategory = "Mesin";
            $unit1Name = "Unit";
            $itemId = "";
            if ($quantityDisposed >= "1") {
                $masuk = 0;
                $keluar = $quantityDisposed;
            } else {
                $masuk = $quantity;
                $keluar = 0;
            }
            if ($draft != "1") 
            {

                //cek data
                $sql_cek = "SELECT TOP 1 itemNo, COALESCE(awal,NULL,0) as awal, COALESCE(masuk,NULL,0) as masuk, COALESCE(keluar,NULL,0) as keluar
                             FROM ac_stock 
                             WHERE tanggal ='" . $tanggal . "' AND itemNo ='" . $itemNo . "' AND lokasiGudang ='Barang Modal' ";
                $data_cek = $sqlLib->select($sql_cek);
                //jika data ada maka update
                if (count($data_cek) > 0) {
                    if ($quantityDisposed >= "1") {
                        //update qty out
                        $qtyakhir = $data_cek[0]['awal'] + $data_cek[0]['masuk'] - $keluar;
                        $sql_up = "UPDATE ac_stock SET keluar = '" . $keluar . "',  akhir = '" . $qtyakhir . "' , recuser = '" . $_SESSION['userid'] . "', recdate = '" . date("Y-m-d H:i:s") . "'
                                        WHERE tanggal ='" . $tanggal . "' AND itemNo = '" . $itemNo . "' AND lokasiGudang ='Barang Modal' ";
                        $run_up = $sqlLib->update($sql_up);
                        //echo "update out";

                    }
                    else
                    {
                        //update qty in
                        $qtyakhir = $data_cek[0]['awal'] + $masuk - $keluar;
                        $sql_up = "UPDATE ac_stock SET masuk = '" . $masuk . "',  akhir = '" . $qtyakhir . "' , recuser = '" . $_SESSION['userid'] . "', recdate = '" . date("Y-m-d H:i:s") . "'
                                        WHERE tanggal ='" . $tanggal . "' AND itemNo = '" . $itemNo . "' AND lokasiGudang ='Barang Modal' ";
                        $run_up = $sqlLib->update($sql_up);
                         //echo "update in";
                    }    
                }
                else
                // jika tidak ada  maka insert    
                {
                    
                    if ($quantityDisposed >= "1") {
                        $qtyakhir = $quantity - $quantityDisposed ;
                        $sql_save = "INSERT INTO ac_stock (tanggal, itemNo, itemName, unit1Name, itemCategory, awal, masuk, keluar, akhir, adjustment, so, lokasiGudang, itemId, recuser, recdate)
                                        VALUES ('" . $tanggal . "','" . $itemNo . "','" . $itemName . "','" . $unit1Name . "','" . $itemCategory . "','0','" . $quantity . "','".$quantityDisposed."',
                                        '" . $qtyakhir . "','0' ,'0' ,'Barang Modal','', '" . $_SESSION['userid'] . "','" . date("Y-m-d H:i:s") . "') ";
                        $run_save = $sqlLib->insert($sql_save);
                    }
                    else{
                        $sql_save = "INSERT INTO ac_stock (tanggal, itemNo, itemName, unit1Name, itemCategory, awal, masuk, keluar, akhir, adjustment, so, lokasiGudang, itemId, recuser, recdate)
                                        VALUES ('" . $tanggal . "','" . $itemNo . "','" . $itemName . "','" . $unit1Name . "','" . $itemCategory . "','0','" . $masuk . "','0','" . $masuk . "','0' ,'0' ,
                                                'Barang Modal','', '" . $_SESSION['userid'] . "','" . date("Y-m-d H:i:s") . "') ";
                        $run_save = $sqlLib->insert($sql_save);
                    }
                    
                    //echo "create";

                }    


            }
        }
        $sukses = 1;
        
        
       
    }
    
    $sql_save = "INSERT INTO ac_daily_request (TglRequest, Gudang, recuser, recdate)
                                VALUES ('" . $tanggal . "','Barang Modal', '" . $_SESSION['userid'] . "','" . date("Y-m-d H:i:s") . "') ";
    $run_save = $sqlLib->insert($sql_save);
    
    return $sukses;
        
}


function getAdjustment($accessToken, $session, $host, $sqlLib)
{
    // Header
    $header = array(
        "Content-Type: application/json",
        "Authorization: Bearer $accessToken",
        "X-SESSION-ID: $session"
    );

    // Content
    $content = array(

        "fields" => "id,name,no,transDate",

    );

    // URL 
    $url = $host . "/accurate/api/item-adjustment/list.do?" . http_build_query($content);

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
    $result = file_get_contents($url, false, $context);

    // Membuat array dari data JSON
    $row_result_api = json_decode($result);
    $jmldata = $row_result_api->sp->rowCount;
    //return  $jmldata; 

    $sukses = false;
    foreach ($row_result_api->d as $row_api) {
        $id = $row_api->id;
        // Content
        $content = array(
            "id" => $id,
        );

        // URL
        $url = $host . "/accurate/api/item-adjustment/detail.do?" . http_build_query($content);

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
        $result = file_get_contents($url, false, $context);

        // Membuat array dari data JSON
        $row_dt = json_decode($result);

        $transDate = $row_dt->d->transDate;

        // Menggunakan DateTime untuk parsing
        $tanggal_obj1 = DateTime::createFromFormat('d/m/Y', $transDate);

        // Menggunakan format() untuk mengonversi ke format yang diinginkan
        $tanggal = $tanggal_obj1->format('Y-m-d');

        $sql_del = "DELETE FROM ac_adjustment WHERE tanggal = '" . $tanggal . "' ";
        $run = $sqlLib->delete($sql_del);

        foreach ($row_dt->d->detailItem as $row) // detail
        {

            $itemNo = $row->item->no;
            $detailName = $row->item->name;
            $quantity = $row->quantity;
            $kategori = $row->itemAdjustmentTypeName;
            $warehouse = $row->warehouse->name;
            if ($kategori != "Penambahan") {
                $quantity = $quantity * (-1);
            } else {
                $quantity = $quantity;
            }

            $sql_save_dt = "INSERT INTO ac_adjustment (tanggal, itemNo, detailName, quantity, kategori, recUser, recDate)
                                VALUES('" . $tanggal . "','" . $itemNo . "','" . $detailName . "', '" . $quantity . "','" . $kategori . "', '" . $_SESSION["userid"] . "','" . date("Y-m-d H:i:s") . "')";
            $run_dt = $sqlLib->insert($sql_save_dt);
            /*
            $sql_up ="UPDATE ac_stock SET adjustment = '" . $quantity . "'
                        WHERE tanggal ='".$tanggal."' AND itemNo = '".$itemNo."' AND lokasiGudang ='".$warehouse."' ";
            $run_up =$sqlLib->update($sql_up);   
            */
        }
        $sukses = true;
    }

    return  $sukses;
    /*  
    echo "<pre>";
    print_r($row_result_api);
    echo "</pre>";
    */
}

function cekDo($accessToken, $session, $host, $dari, $sampai, $sqlLib)
{
    // Header
    $header = array(
        "Content-Type: application/json",
        "Authorization: Bearer $accessToken",
        "X-SESSION-ID: $session"
    );

    // Content
    $content = array(

        "fields" => "number,approvalStatus,statusName,transDate",
        "filter.transDate.op" => "BETWEEN",
        "filter.transDate.val[0]" => $dari,
        "filter.transDate.val[1]" => $sampai,


    );

    // URL 
    $url = $host . "/accurate/api/delivery-order/list.do?" . http_build_query($content);

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
    $result = file_get_contents($url, false, $context);

    // Membuat array dari data JSON
    $row_result_api = json_decode($result);
    $jmldata = $row_result_api->sp->rowCount;
    //return  $row_result_api;          
    return  $jmldata;
}

function getDo($accessToken, $session, $host, $dari, $sampai, $sqlLib)
{
    // Header
    $header = array(
        "Content-Type: application/json",
        "Authorization: Bearer $accessToken",
        "X-SESSION-ID: $session"
    );

    // Content
    $content = array(

        "fields" => "number,approvalStatus,statusName,transDate",
        "filter.transDate.op" => "BETWEEN",
        "filter.transDate.val[0]" => $dari,
        "filter.transDate.val[1]" => $sampai,

    );

    // URL 
    $url = $host . "/accurate/api/delivery-order/list.do?" . http_build_query($content);

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
    $result = file_get_contents($url, false, $context);

    // Membuat array dari data JSON
    $row_result_api = json_decode($result);
    $jmldata = $row_result_api->sp->rowCount;
    //return  $row_result_api;          

    $sukses = false;
    foreach ($row_result_api->d as $row_api) {
        $number = $row_api->number;
        // Content
        $content = array(
            "number" => $number
        );

        // URL
        $url = $host . "/accurate/api/delivery-order/detail.do?" . http_build_query($content);

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
        $result = file_get_contents($url, false, $context);

        // Membuat array dari data JSON
        $row_hd = json_decode($result);

        $number = $row_hd->d->number;
        $customer = $row_hd->d->customer->name;
        $dokumenBC = $row_hd->d->charField2;
        $nomorAju = $row_hd->d->charField1;
        $nomorDaftar = $row_hd->d->charField3;
        $tglDaftar = $row_hd->d->dateField1;
        $transDate = $row_hd->d->transDate;
        $approvalStatus = $row_hd->d->approvalStatus;
        $status = $row_hd->d->status;
        $statusName = $row_hd->d->statusName;


        // Menggunakan DateTime untuk parsing
        $tanggal_obj1 = DateTime::createFromFormat('d/m/Y', $tglDaftar);
        $tanggal_obj2 = DateTime::createFromFormat('d/m/Y', $transDate);

        // Menggunakan format() untuk mengonversi ke format yang diinginkan
        $tglDaftar = $tanggal_obj1->format('Y-m-d');
        $transDate = $tanggal_obj2->format('Y-m-d');


        //delete pengeluaran
        $sql_del = "DELETE FROM ac_pengeluaran WHERE DO = '" . $number . "' ";
        $run1 = $sqlLib->delete($sql_del);

        $sql_del2 = "DELETE FROM ac_pengeluaran_detail WHERE DO = '" . $number . "' ";
        $run2 = $sqlLib->delete($sql_del2);

        if ($dokumenBC != "") {
            $sql_save = "INSERT INTO ac_pengeluaran 
                                    (DO, tglDO, customer, dokumenBC, nomorAju, nomorDaftar, tglDaftar,  transDate, approvalStatus, status, statusName, recuser, recdate)
                                    VALUES('" . $number . "' ,'" . $transDate . "','" . $customer . "','" . $dokumenBC . "'  ,'" . $nomorAju . "','" . $nomorDaftar . "','" . $tglDaftar . "',
                                            '" . $transDate . "','" . $approvalStatus . "','" . $status . "','" . $statusName . "','" . $_SESSION['userid'] . "','" . date("Y-m-d H:i:s") . "') ";
            //$run = sqlsrv_query($conn, $sql_save); 
            $run = $sqlLib->insert($sql_save);

            if ($run == "1") {

                foreach ($row_hd->d->detailItem as $row) // detail receive
                {

                    $itemNo = $row->item->no;
                    $detailName = $row->item->name;
                    $unitPrice = $row->unitPrice;
                    $quantity = $row->quantity;
                    $itemDiscPercent = $row->itemDiscPercent;
                    $itemCost = $row->itemCost;
                    $satuan = $row->itemUnit->name;
                    $totalPrice = $row->totalPrice;

                    $hsNumber = $row->charField1;
                    $bruto = $row->numericField1;
                    $netto = $row->numericField2;
                    $volume = $row->numericField3;

                    $itemCategoryId = $row->item->itemCategoryId;


                    $sql_save_dt = "INSERT INTO ac_pengeluaran_detail (DO,itemNo,detailName,unitPrice,quantity,itemDiscPercent,itemCost,satuan, totalPrice,bruto, netto, volume, hsNumber, itemCategoryId, recUser, recDate)
                                            VALUES('" . $number . "','" . $itemNo . "','" . $detailName . "','" . $unitPrice . "','" . $quantity . "','" . $itemDiscPercent . "','" . $itemCost . "','" . $satuan . "','" . $totalPrice . "',
                                        '" . $bruto . "','" . $netto . "','" . $volume . "','" . $hsNumber . "', '" . $itemCategoryId . "', '" . $_SESSION["userid"] . "','" . date("Y-m-d H:i:s") . "')";
                    $run_dt = $sqlLib->insert($sql_save_dt);
                }

                //cek detail
                $sql_dtl = "SELECT COUNT(DO) as jmldata FROM ac_pengeluaran_detail WHERE DO = '" . $number . "' ";
                $data_dtl = $sqlLib->select($sql_dtl);
                if ($data_dtl[0]['jmldata'] < 1) {
                    $sql_del3 = "DELETE FROM ac_pengeluaran WHERE DO = '" . $number . "' ";
                    $run3 = $sqlLib->delete($sql_del3);
                }

                $sukses = true;
            }
        }
    }


    return  $sukses;
}

function getStock($accessToken, $session, $host, $dari, $sampai, $sqlLib)
{

    $tanggal_obj1 = DateTime::createFromFormat('d/m/Y', $sampai);
    $tglakhir = $tanggal_obj1->format('d');
    $bulan = $tanggal_obj1->format('m');
    $tahun = $tanggal_obj1->format('Y');

    $tanggal_obj2 = DateTime::createFromFormat('d/m/Y', $dari);
    $tglDaftar = $tanggal_obj2->format('Y-m-d');
    $tanggal_lalu   = date('Y-m-d', strtotime('-1 day', strtotime($tglDaftar)));

    $tanggal_lalu = date('d/m/Y', strtotime($tanggal_lalu));
    //    echo $tanggal_lalu ;


    $sukses = 0;
    for ($tgl = 1; $tgl <= $tglakhir; $tgl++) {
        /*
        if($tgl==1){
            $fromDate= $tanggal_lalu;
        }
        else{
            $tgl2 = $tgl -1;
            $fromDate= $tgl2."/".$bulan."/".$tahun;
        }
        */

        $fromDate = $tgl . "/" . $bulan . "/" . $tahun;
        $toDate = $tgl . "/" . $bulan . "/" . $tahun;
        $tanggal2 = $tahun . "-" . $bulan . "-" . $tgl;

        //echo $fromDate.'-'.$toDate;


        // Header
        $header = array(
            "Content-Type: application/json",
            "Authorization: Bearer $accessToken",
            "X-SESSION-ID: $session"
        );

        // Content
        $content = array(
            "fields" => "id,name,no,itemUnit,itemCategory",
            "filter.itemCategoryId.op" => "EQUAL",
            "filter.itemCategoryId.val[0]" => "100", // Bahan Baku
            "filter.itemCategoryId.val[1]" => "200", // Finish Good
            "filter.itemCategoryId.val[2]" => "101", // Mesin
            "filter.itemCategoryId.val[3]" => "201", // Scarp
            "filter.itemCategoryId.val[4]" => "150", // WIP
            //"filter.no.op" =>"EQUAL",
            //"filter.no.val[0]" =>"100001",

        );

        // URL
        $url = $host . "/accurate/api/item/list.do?" . http_build_query($content);

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
        $result = file_get_contents($url, false, $context);

        // Membuat array dari data JSON
        $row_result_api = json_decode($result);


        //delete penerimaan
        $sql_del = "DELETE FROM ac_stock WHERE tanggal = '" . $tanggal2 . "' ";
        $run_del = $sqlLib->delete($sql_del);

        foreach ($row_result_api->d as $row_api) {
            $itemNo = $row_api->no;
            $itemName = $row_api->name;
            $unit1Name = $row_api->unit1Name;
            $itemCategory = $row_api->itemCategory->name;

            $sql_save = "INSERT INTO ac_stock (tanggal, itemNo, itemName, unit1Name, itemCategory, recuser, recdate)
                                VALUES ('" . $tanggal2 . "','" . $itemNo . "','" . $itemName . "','" . $unit1Name . "','" . $itemCategory . "','sistem','" . date("Y-m-d H:i:s") . "') ";
            $run_save = $sqlLib->insert($sql_save);
            //echo $sql_save  ;    
            if ($run_save == "1") {
                $content = array(
                    "fromDate" => $fromDate,
                    "toDate" => $toDate,
                    "itemNo" => $itemNo,
                    "warehouseName" => "Utama"
                );

                $url = $host . "/accurate/api/report/stock-mutation-summary.do?" . http_build_query($content);

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
                $result = file_get_contents($url, false, $context);

                // Membuat array dari data JSON
                $row_result_stock = json_decode($result);
                foreach ($row_result_stock->d as $row_stock) {
                    $awal = $row_stock->startBalance;
                    $masuk = $row_stock->quantityIn;
                    $keluar = $row_stock->quantityOut;
                    $akhir = $row_stock->lastBalance;
                    $sql_up = "UPDATE ac_stock SET awal = '0', masuk = '" . $masuk . "',keluar = '" . $keluar . "', akhir = '0' , adjustment = '0' , so = '0' 
                                    WHERE tanggal ='" . $tanggal2 . "' AND itemNo = '" . $itemNo . "' ";
                    $run_up = $sqlLib->update($sql_up);
                }
            }
            $sukses = 1;

            //delete Stock
            $sql_del2 = "DELETE FROM ac_stock WHERE tanggal = '" . $tanggal2 . "' AND itemNo = '" . $itemNo . "'
                                 AND awal = '0' AND masuk = '0' AND keluar = '0' AND akhir = '0'  AND adjustment = '0'  AND so = '0'  ";
            $run_del2 = $sqlLib->delete($sql_del2);
        }
    }
    return  $sukses;
}

function cekSalesInvoice($accessToken, $session, $host, $dari, $sampai, $sqlLib)
{
    // Header
    $header = array(
        "Content-Type: application/json",
        "Authorization: Bearer $accessToken",
        "X-SESSION-ID: $session"
    );

    // Content
    $content = array(

        "fields" => "number,approvalStatus,statusName,transDate",
        "filter.transDate.op" => "BETWEEN",
        "filter.transDate.val[0]" => $dari,
        "filter.transDate.val[1]" => $sampai,
        "filter.outstanding" => "false", //true=belum lunas, false=lunas

    );

    // URL 
    $url = $host . "/accurate/api/sales-invoice/list.do?" . http_build_query($content);

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
    $result = file_get_contents($url, false, $context);

    // Membuat array dari data JSON
    $row_result_api = json_decode($result);
    $jmldata = $row_result_api->sp->rowCount;
    return  $jmldata;
}

function getSalesInvoice($accessToken, $session, $host, $dari, $sampai, $sqlLib)
{
    // Header
    $header = array(
        "Content-Type: application/json",
        "Authorization: Bearer $accessToken",
        "X-SESSION-ID: $session"
    );

    // Content
    $content = array(

        "fields" => "number,approvalStatus,statusName,transDate",
        "filter.transDate.op" => "BETWEEN",
        "filter.transDate.val[0]" => $dari,
        "filter.transDate.val[1]" => $sampai,
        "filter.outstanding" => "false", //true=belum lunas, false=lunas

    );

    // URL 
    $url = $host . "/accurate/api/sales-invoice/list.do?" . http_build_query($content);

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
    $result = file_get_contents($url, false, $context);

    // Membuat array dari data JSON
    $row_result_api = json_decode($result);
    $jmldata = $row_result_api->sp->rowCount;
    //return  $row_result_api;          

    $sukses = false;
    foreach ($row_result_api->d as $row_api) {
        $number = $row_api->number;
        // Content
        $content = array(
            "number" => $number
        );

        // URL
        $url = $host . "/accurate/api/sales-invoice/detail.do?" . http_build_query($content);

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
        $result = file_get_contents($url, false, $context);

        // Membuat array dari data JSON
        $row_hd = json_decode($result);

        $number = $row_hd->d->number;
        $billNumber = $row_hd->d->billNumber;
        $customer = $row_hd->d->customer->name;
        $dokumenBC = $row_hd->d->charField2;
        $nomorAju = $row_hd->d->charField1;
        $nomorDaftar = $row_hd->d->charField3;
        $tglDaftar = $row_hd->d->dateField1;
        $transDate = $row_hd->d->transDate;
        $shipDate = $row_hd->d->shipDate;
        $approvalStatus = $row_hd->d->approvalStatus;
        $status = $row_hd->d->status;
        $statusName = $row_hd->d->statusName;


        // Menggunakan DateTime untuk parsing
        $tanggal_obj1 = DateTime::createFromFormat('d/m/Y', $tglDaftar);
        $tanggal_obj2 = DateTime::createFromFormat('d/m/Y', $transDate);
        $tanggal_obj3 = DateTime::createFromFormat('d/m/Y', $shipDate);

        // Menggunakan format() untuk mengonversi ke format yang diinginkan
        $tglDaftar = $tanggal_obj1->format('Y-m-d');
        $transDate = $tanggal_obj2->format('Y-m-d');
        $shipDate = $tanggal_obj3->format('Y-m-d');

        //delete penerimaan
        $sql_del = "DELETE FROM ac_pengeluaran WHERE salesInvoice = '" . $number . "' ";
        $run1 = $sqlLib->delete($sql_del);

        $sql_del2 = "DELETE FROM ac_pengeluaran_detail WHERE salesInvoice = '" . $number . "' ";
        $run2 = $sqlLib->delete($sql_del2);


        $sql_save = "INSERT INTO ac_pengeluaran 
                                (salesInvoice, billNumber, customer, dokumenBC, nomorAju, nomorDaftar, tglDaftar,  transDate, shipDate, approvalStatus, status, statusName, recuser, recdate)
                                VALUES('" . $number . "' ,'" . $billNumber . "','" . $customer . "','" . $dokumenBC . "'  ,'" . $nomorAju . "','" . $nomorDaftar . "','" . $tglDaftar . "','" . $transDate . "','" . $shipDate . "','" . $approvalStatus . "','" . $status . "','" . $statusName . "','sistem','" . date("Y-m-d H:i:s") . "') ";
        //$run = sqlsrv_query($conn, $sql_save);                      
        $run = $sqlLib->insert($sql_save);
        if ($run == "1") {

            foreach ($row_hd->d->detailItem as $row) // sales invoice
            {
                $salesOrder = $row->salesOrder->number;
                $deliveryOrder = $row->deliveryOrder->number;
                $itemNo = $row->item->no;
                $detailName = $row->detailName;
                $unitPrice = $row->unitPrice;
                $quantity = $row->quantity;
                $itemDiscPercent = $row->itemDiscPercent;
                $itemCost = $row->itemCost;
                $satuan = $row->itemUnit->name;
                $totalPrice = $row->totalPrice;

                // Content
                $content = array(
                    "number" => $deliveryOrder
                );

                // URL
                $url = $host . "/accurate/api/delivery-order/detail.do?" . http_build_query($content);

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
                $result = file_get_contents($url, false, $context);
                // Membuat array dari data JSON
                $row_do = json_decode($result);

                $transDate = $row_do->d->transDate;

                // Menggunakan DateTime untuk parsing
                $tanggal_obj1 = DateTime::createFromFormat('d/m/Y', $transDate);

                // Menggunakan format() untuk mengonversi ke format yang diinginkan
                $deliveryDate = $tanggal_obj1->format('Y-m-d');

                $sql_save_dt = "INSERT INTO ac_pengeluaran_detail 
                                (salesInvoice,salesOrder,deliveryOrder,deliveryDate,itemId,detailName,unitPrice,quantity,itemDiscPercent,itemCost,satuan, totalPrice,
                                    bruto, netto, volume, hsNumber,  recUser, recDate)
                                VALUES('" . $number . "','" . $salesOrder . "','" . $deliveryOrder . "','" . $deliveryDate . "','" . $itemNo . "','" . $detailName . "',
                                        '" . $unitPrice . "','" . $quantity . "','" . $itemDiscPercent . "','" . $itemCost . "','" . $satuan . "','" . $totalPrice . "',
                                    '0','0','0','0',    'sistem','" . date("Y-m-d H:i:s") . "')";
                $run_dt = $sqlLib->insert($sql_save_dt);


                foreach ($row_do->d->detailItem as $row_item) {

                    $itemNo = $row_item->item->no;
                    $quantity = $row_item->quantity;

                    $hsNumber = $row_item->charField1;
                    $bruto = $row_item->numericField1;
                    $netto = $row_item->numericField2;
                    $volume = $row_item->numericField3;

                    $sql_up = "UPDATE ac_pengeluaran_detail SET bruto ='" . $bruto . "',netto ='" . $netto . "',volume ='" . $volume . "',hsNumber ='" . $hsNumber . "' 
                                        WHERE salesInvoice = '" . $number . "' AND  deliveryOrder = '" . $deliveryOrder . "' 
                                            AND itemId = '" . $itemNo . "' AND quantity = '" . $quantity . "' ";
                    $run_up = $sqlLib->update($sql_up);
                }
            }

            $sukses = true;
        }
    }


    return  $sukses;
}

function tgl_indo($tanggal)
{
    $bulan = array(
        1 =>   'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    );
    $pecahkan = explode('-', $tanggal);

    // variabel pecahkan 0 = tahun
    // variabel pecahkan 1 = bulan
    // variabel pecahkan 2 = tanggal

    return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
}
