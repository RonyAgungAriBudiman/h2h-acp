<?php
function getStockGudang($accessToken, $session, $host, $dari, $sampai, $sqlLib)
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
            "filter.itemCategoryId.val[0]" =>"100", // Bahan Baku
            "filter.itemCategoryId.val[1]" =>"200", // Finish Good
            "filter.itemCategoryId.val[2]" =>"101", // Mesin
            "filter.itemCategoryId.val[3]" =>"201", // Scrap
            "filter.itemCategoryId.val[4]" =>"150", // WIP

            //"filter.no.op" =>"EQUAL",
            //"filter.no.val[0]" =>"100001",

              );

        // URL
        $url = $host . "/accurate/api/item/list.do?" . http_build_query($content);    

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

        
        //delete Stock
        //$sql_del = "DELETE FROM ac_stock WHERE tanggal = '" . $tanggal . "' AND awal ='0' AND lokasiGudang='Utama' ";
        //$run_del = $sqlLib->delete($sql_del);

        //delete ac_daily_request
        $sql_del2 = "DELETE FROM ac_daily_request WHERE TglRequest = '" . $tanggal . "' ";
        $run_del2 = $sqlLib->delete($sql_del2);

        foreach($row_result_api->d as $row_api)
        {                
            $itemId = $row_api->id;
            $itemNo = $row_api->no;
            $itemName = $row_api->name;
            $unit1Name = $row_api->unit1Name;
            $itemCategory = $row_api->itemCategory->name;

            //cek data
            $sql_cek = "SELECT TOP 1 itemNo FROM ac_stock WHERE tanggal ='".$tanggal."' AND itemNo ='".$itemNo."' AND lokasiGudang ='Utama' ";
            $data_cek=$sqlLib->select($sql_cek);
            if(count($data_cek)>0){
                $content = array(
                 "fromDate" => $dari,
                 "toDate"=> $sampai,            
                 "itemNo" => $itemNo,
                 "warehouseName" => "Utama"
                  );

                $url = $host . "/accurate/api/report/stock-mutation-summary.do?" . http_build_query($content);    

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
                $row_result_stock = json_decode($result);
                foreach($row_result_stock->d as $row_stock)
                {
                    $awal = $row_stock->startBalance;
                    $masuk = $row_stock->quantityIn;
                    $keluar = $row_stock->quantityOut;
                    $akhir = $row_stock->lastBalance;   

                    //cek stock sebelumnya
                    $tglkemarin = date('Y-m-d', strtotime('-1 days', strtotime($tanggal)));
                    $sql_kemarin = "SELECT COALESCE(akhir,NULL,0) as stockkemarin FROM ac_stock WHERE tanggal ='".$tglkemarin."' AND itemNo = '".$itemNo."' AND lokasiGudang ='Utama' ";
                    $data_kemarin= $sqlLib->select($sql_kemarin);
                    if($data_kemarin[0]['stockkemarin']=="") {
                        $qtyawal = 0;
                    }else{
                        $qtyawal = $data_kemarin[0]['stockkemarin'];
                    }

                    $qtyakhir = $qtyawal + $masuk - $keluar; 

                    $sql_up ="UPDATE ac_stock SET awal = '".$qtyawal."', masuk = '".$masuk."',keluar = '".$keluar."', akhir = '".$qtyakhir."' , adjustment = '0' , so = '0' 
                                WHERE tanggal ='".$tanggal."' AND itemNo = '".$itemNo."' AND lokasiGudang ='Utama' ";
                    $run_up =$sqlLib->update($sql_up);            
                }

            }
            else 
            {  
                 $sql_save ="INSERT INTO ac_stock (tanggal, itemNo, itemName, unit1Name, itemCategory,  lokasiGudang, itemId, recuser, recdate)
                                VALUES ('".$tanggal."','".$itemNo."','".$itemName."','".$unit1Name."','".$itemCategory."','Utama','".$itemId."', '".$_SESSION['userid']."','".date("Y-m-d H:i:s")."') ";
                $run_save = $sqlLib->insert($sql_save);     
               //echo $sql_save  ;    
               if($run_save=="1")        
               {
                    $content = array(
                     "fromDate" => $dari,
                     "toDate"=> $sampai,            
                     "itemNo" => $itemNo,
                     "warehouseName" => "Utama"
                      );

                    $url = $host . "/accurate/api/report/stock-mutation-summary.do?" . http_build_query($content);    

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
                    $row_result_stock = json_decode($result);
                    foreach($row_result_stock->d as $row_stock)
                    {
                        $awal = $row_stock->startBalance;
                        $masuk = $row_stock->quantityIn;
                        $keluar = $row_stock->quantityOut;
                        $akhir = $row_stock->lastBalance;    

                        //cek stock sebelumnya
                        $tglkemarin = date('Y-m-d', strtotime('-1 days', strtotime($tanggal)));
                        $sql_kemarin = "SELECT COALESCE(akhir,NULL,0) as stockkemarin FROM ac_stock WHERE tanggal ='".$tglkemarin."' AND itemNo = '".$itemNo."' AND lokasiGudang ='Utama' ";
                        $data_kemarin= $sqlLib->select($sql_kemarin);
                        if($data_kemarin[0]['stockkemarin']=="") {
                            $qtyawal = 0;
                        }else{
                            $qtyawal = $data_kemarin[0]['stockkemarin'];
                        }

                        $qtyakhir = $qtyawal + $masuk - $keluar;
                        $sql_up ="UPDATE ac_stock SET awal = '".$qtyawal."', masuk = '".$masuk."',keluar = '".$keluar."', akhir = '".$qtyakhir."' , adjustment = '0' , so = '0' 
                                    WHERE tanggal ='".$tanggal."' AND itemNo = '".$itemNo."' AND lokasiGudang ='Utama' ";
                        $run_up =$sqlLib->update($sql_up);            
                    }
                }
            }    

            //delete Stock
            $sql_del3 = "DELETE FROM ac_stock WHERE tanggal = '" . $tanggal . "' AND itemNo = '".$itemNo."'
                             AND awal = '0' AND masuk = '0' AND keluar = '0' AND akhir = '0'  AND adjustment = '0'  AND so = '0'  ";
            $run_del3 = $sqlLib->delete($sql_del3);  

            $sukses = 1;

        }        

        $sql_save ="INSERT INTO ac_daily_request (TglRequest, Gudang, recuser, recdate)
                                VALUES ('".$tanggal."', 'Utama' ,  '".$_SESSION['userid']."','".date("Y-m-d H:i:s")."') ";
        $run_save = $sqlLib->insert($sql_save);  
    
    return  $sukses;   
}