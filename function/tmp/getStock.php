<?php
function getStock($accessToken, $session, $host,$dari, $sampai,$sqlLib)
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
    for($tgl=1; $tgl<=$tglakhir; $tgl++)
    {
        /*
        if($tgl==1){
            $fromDate= $tanggal_lalu;
        }
        else{
            $tgl2 = $tgl -1;
            $fromDate= $tgl2."/".$bulan."/".$tahun;
        }
        */

        $fromDate = $tgl."/".$bulan."/".$tahun;
        $toDate = $tgl."/".$bulan."/".$tahun;
        $tanggal2= $tahun."-".$bulan."-".$tgl;

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
            "filter.itemCategoryId.val[0]" =>"100", // Bahan Baku
             //"filter.itemCategoryId.val[1]" =>"200", // Finish Good
             //"filter.itemCategoryId.val[2]" =>"101", // Mesin
             //"filter.itemCategoryId.val[3]" =>"201", // Scrap
             //"filter.itemCategoryId.val[4]" =>"150", // WIP

            "filter.no.op" =>"EQUAL",
            "filter.no.val[0]" =>"100001",

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
        $sql_del = "DELETE FROM ac_stock WHERE tanggal = '" . $tanggal2 . "' ";
        $run_del = $sqlLib->delete($sql_del);

            foreach($row_result_api->d as $row_api)
            {                
                $itemNo = $row_api->no;
                $itemName = $row_api->name;
                $unit1Name = $row_api->unit1Name;
                $itemCategory = $row_api->itemCategory->name;

                $sql_save ="INSERT INTO ac_stock (tanggal, itemNo, itemName, unit1Name, itemCategory, recuser, recdate)
                                VALUES ('".$tanggal2."','".$itemNo."','".$itemName."','".$unit1Name."','".$itemCategory."','sistem','".date("Y-m-d H:i:s")."') ";
                $run_save = $sqlLib->insert($sql_save);     
               //echo $sql_save  ;    
               if($run_save=="1")        
               {
                    $content = array(
                     "fromDate" => $fromDate,
                     "toDate"=> $toDate,            
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
                        $sql_up ="UPDATE ac_stock SET awal = '0', masuk = '".$masuk."',keluar = '".$keluar."', akhir = '0' , adjustment = '0' , so = '0' 
                                    WHERE tanggal ='".$tanggal2."' AND itemNo = '".$itemNo."' ";
                        $run_up =$sqlLib->update($sql_up);            
                    }
                }
                $sukses = 1;
    
            }

        //delete Stock
        $sql_del2 = "DELETE FROM ac_stock WHERE tanggal = '" . $tanggal2 . "' AND itemNo = '".$itemNo."'
                         AND awal = '0', masuk = '0',keluar = '0', akhir = '0' , adjustment = '0' , so = '0'  ";
        $run_del2 = $sqlLib->delete($sql_del2);    


            
    } 
    return  $sukses;  
}