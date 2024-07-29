<?php
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
        $opts = array("http" =>
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
        foreach($row_result_api->d as $row_api)
            {                
                $number = $row_api->number;
                // Content
                $content = array(
                    "number" => $number
                );
                
                // URL
                $url = $host . "/accurate/api/receive-item/detail.do?" . http_build_query($content);

                // Connect API
                $opts = array("http" =>
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

                        
                $sql_save = "INSERT INTO ac_penerimaan 
                                (receiveItem, receiveNumber, receiveDate, vendor, dokumenBC, nomorAju, nomorDaftar, tglDaftar,  transDate, shipDate, approvalStatus, status, statusName, recuser, recdate)
                                VALUES('" . $number . "' ,'" . $receiveNumber . "','" . $transDate . "','" . $vendor . "','" . $dokumenBC . "'  ,'" . $nomorAju . "','" . $nomorDaftar . "','" . $tglDaftar . "',
                                        '" . $transDate . "','" . $shipDate . "','" . $approvalStatus . "','" . $status . "','" . $statusName . "','sistem','" . date("Y-m-d H:i:s") . "') ";
                //$run = sqlsrv_query($conn, $sql_save);                      
                $run = $sqlLib->insert($sql_save); 
                if($run=="1"){
                    
                    foreach($row_hd->d->detailItem as $row) // detail receive
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
                                    '".$bruto."','".$netto."','".$volume."','".$hsNumber."', '".$itemCategoryId."', '".$_SESSION["userid"]."','" . date("Y-m-d H:i:s") . "')";
                        $run_dt = $sqlLib->insert($sql_save_dt);
                        
                    } 
                    $sukses = true; 
                } 
    	    }  

        
        return  $sukses;        
             

}