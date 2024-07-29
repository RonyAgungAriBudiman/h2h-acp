<?php
function getSo($accessToken, $session, $host, $dari, $sampai, $sqlLib)
{   
        // Header
        $header = array(
            "Content-Type: application/json",
            "Authorization: Bearer $accessToken",
            "X-SESSION-ID: $session"
        );

        // Content
        $content = array(
            "fields" => "number",
            "filter.transDate.op" => "BETWEEN",   
            "filter.transDate.val[0]" => $dari,
            "filter.transDate.val[1]" => $sampai,
            "filter.status.op" => "EQUAL",
            "filter.status.val[0]" =>"QUEUE" // QUEUE untuk uji coba, PROCEED untuk live
            //"filter.approvalStatus.op" => "EQUAL",
            //"filter.approvalStatus" =>"APPROVED"
            //04/11/2023
        );

        // URL
        $url = $host . "/accurate/api/sales-order/list.do?" . http_build_query($content);    

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
        $status = $row_result_api->s;
         
        /*   
        echo "<pre>";
        print_r($row_result_api);
        echo "</pre>";
         */           
          
        $sukses = false; 
        if($status=="1")
        {   
            
            foreach($row_result_api->d as $row_api)
            {
                
                $number = $row_api->number;
                // Content
                $content = array(
                    "number" => $number
                );
                
                // URL
                $url = $host . "/accurate/api/sales-order/detail.do?" . http_build_query($content);

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
                $row_so = json_decode($result);

                	$number = $row_so->d->number;
                    $id = $row_so->d->id; 
                    $customer = $row_so->d->customer->contactInfo->companyName;
                    $npwpNo = $row_so->d->customer->npwpNo;
                    $transDate = $row_so->d->transDate; 
                    $shipDate = $row_so->d->shipDate;                     
                    $currency = $row_so->d->currency->code; 
                    $subTotal = $row_so->d->subTotal;
                    $cashDiscount = $row_so->d->cashDiscount;
                    $cashDiscPercent = $row_so->d->cashDiscPercent;
                    $salesAmount = $row_so->d->salesAmount;
                    $tax1Amount = $row_so->d->tax1Amount;
                    $taxable  = $row_so->d->taxable;
                    $tax1Rate = $row_so->d->tax1Rate;
                    $totalAmount = $row_so->d->totalAmount;
                    $toAddress = $row_so->d->toAddress;
                    $status = $row_so->d->status;
                    $statusName = $row_so->d->statusName;
                    $approvalStatus = $row_so->d->approvalStatus;

                    
                     
                    $shipDate = date("Y-d-m",strtotime($shipDate));
                    $transDate= date("Y-d-m",strtotime($transDate));

                    //delete so
                    $sql_del = "DELETE FROM ac_so WHERE number = '" . $number . "' ";
                    $run_so = $sqlLib->delete($sql_del);

                    $sql_del2 = "DELETE FROM ac_so_detail WHERE number = '" . $number . "' ";
                    $run_so2 = $sqlLib->delete($sql_del2);

                            
                    $sql_save = "INSERT INTO ac_so(id, number, customer, transDate, shipDate, currency, subTotal, cashDiscount, cashDiscPercent, salesAmount, taxable, tax1Rate, tax1Amount, totalAmount, toAddress, npwpNo, status, statusName, approvalStatus, recuser, recdate)
                                    VALUES( '" . $id . "','" . $number . "' ,'" . $customer . "','" . $transDate . "','" . $shipDate . "'  ,'" . $currency . "','" . $subTotal . "','" . $cashDiscount . "','" . $cashDiscPercent . "','" . $salesAmount . "'
                                      ,'" . $taxable . "','" . $tax1Rate . "','" . $tax1Amount . "','" . $totalAmount . "','" . $toAddress . "','" . $npwpNo . "','" . $status . "','" . $statusName . "','" . $approvalStatus . "','sistem','" . date("Y-m-d H:i:s") . "') ";
                    //$run = sqlsrv_query($conn, $sql_save);                      
                    $run = $sqlLib->insert($sql_save);  


                        foreach($row_so->d->detailItem as $row)
            	        {
            	            $unitPrice = $row->unitPrice;
            	            $itemId = $row->itemId; 
            	            $detailName = $row->detailName;
            	            $unitPrice = $row->unitPrice;
            	            $quantity = $row->quantity;  
                            $satuan = $row->itemUnit->name;  
            	            $itemDiscPercent = $row->itemDiscPercent;   
            	            $itemCashDiscount = $row->itemCashDiscount; 
                            $useTax1 =  $row->useTax1;  
                            $tax1Amount =  $row->tax1Amount;  
                            $salesAmount =  $row->salesAmount;     

            	            $sql_save_dt = "INSERT INTO ac_so_detail
            	            	(number,itemId,detailName,unitPrice,quantity,satuan,itemDiscPercent,itemCashDiscount,useTax1,tax1Amount,salesAmount,recuser,recdate)
                                VALUES('" . $number . "','" . $itemId . "','" . $detailName . "','" . $unitPrice . "','" . $quantity . "',
                                '" . $satuan . "','" . $itemDiscPercent . "','" . $itemCashDiscount . "','" . $useTax1 . "',
                                '" . $tax1Amount . "','".$salesAmount."','sistem','" . date("Y-m-d H:i:s") . "')";
                        	$run_dt = $sqlLib->insert($sql_save_dt); 
                            //echo $sql_save_dt;  
            	            
            	        } 
    	    }  

        }
        return  $status;              

}