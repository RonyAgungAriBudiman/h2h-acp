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
            "fields" => "approvalStatus,availableDownPayment,branch,cashDiscPercent,cashDiscount,charField1,charField10,charField2,charField3,charField4,charField5,charField6,charField7,charField8,charField9,createdByUserName,currency,customer,dateField1,dateField2,description,detailItemCount,detailItemTotalQuantity,dppAmount,id,lastUpdate,masterSalesman,number,numericField1,numericField10,numericField2,numericField3,numericField4,numericField5,numericField6,numericField7,numericField8,numericField9,paymentTerm,percentShipped,poNumber,shipDate,shipment,status,statusName,tax1Amount,tax2Amount,tax3Amount,tax4Amount,totalAmount,totalDiscount,totalDiscountDetail,totalDownPayment,totalDownPaymentUsed,totalExpense,transDate",
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
                $id = $row_api->id;
                $number = $row_api->number;
                $customer = $row_api->customer->name;
                $transDate = $row_api->transDate;
                $shipDate = $row_api->shipDate;

                $shipDate = date("Y-d-m",strtotime($shipDate));
                $transDate= date("Y-d-m",strtotime($transDate));

                $currency = $row_api->currency->code;
                $totalDiscount = $row_api->totalDiscount;
                $tax1Amount = $row_api->tax1Amount;
                $totalAmount = $row_api->totalAmount;

                $detailItemCount = $row_api->detailItemCount;
                $detailItemTotalQuantity = $row_api->detailItemTotalQuantity;

                $status = $row_api->status;
                $dppAmount = $row_api->dppAmount;
                $description = $row_api->description;
                $statusName = $row_api->statusName;
                $cashDiscPercent = $row_api->cashDiscPercent;
                $approvalStatus = $row_api->approvalStatus;
                $totalDownPayment = $row_api->totalDownPayment;
                $totalDownPaymentUsed = $row_api->totalDownPaymentUsed;
                $totalDiscountDetail = $row_api->totalDiscountDetail;
                $totalExpense = $row_api->totalExpense;

                	
                //delete so
                $sql_del = "DELETE FROM ac_so WHERE number = '" . $number . "' ";
                $run_so = $sqlLib->delete($sql_del);

                $sql_del2 = "DELETE FROM ac_so_detail WHERE number = '" . $number . "' ";
                $run_so2 = $sqlLib->delete($sql_del2);
                          
                $sql_save = "INSERT INTO ac_so(id,number,customer,transDate,shipDate,currency,totalDiscount,tax1Amount,totalAmount,detailItemCount,detailItemTotalQuantity,status,
                                                   dppAmount,description,statusName,cashDiscPercent,approvalStatus,totalDownPayment,totalDownPaymentUsed,totalDiscountDetail,totalExpense,recuser,recdate)
                                    VALUES( '" . $id . "','" . $number . "','" . $customer . "','" . $transDate . "','" . $shipDate . "','" . $currency . "','" . $totalDiscount . "',
                                    '" . $tax1Amount . "','" . $totalAmount . "','" . $detailItemCount . "','" . $detailItemTotalQuantity . "','" . $status . "','" . $dppAmount . "','" . $description . "',
                                    '" . $statusName . "','" . $cashDiscPercent . "','" . $approvalStatus . "','" . $totalDownPayment . "','" . $totalDownPaymentUsed . "','" . $totalDiscountDetail . "','" . $totalExpense . "',
                                    'sistem','" . date("Y-m-d H:i:s") . "')";
                //$run = sqlsrv_query($conn, $sql_save);  
                $run = $sqlLib->insert($sql_save);    

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
            	$row_detail_so = json_decode($result);

    		    foreach($row_detail_so->d->detailItem as $row)
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