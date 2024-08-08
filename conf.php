<?php 
	
    if($_SERVER['SERVER_NAME']=='localhost') {
        //accurate
        $clientId ="831f6c0f-0211-49f4-a604-b94cebce5abf";
        $clientSecret ="25b8597a51dc7f9c7ed8b9e4d062d0a4";
        $mainUrl = $_SERVER['SERVER_NAME'];
    }  
    else{
        //accurate online
        $clientId ="85813f2d-04c2-4753-8026-8c7932fcfba1";
        $clientSecret ="a5bc6f243058b0845c70d420484586f5";
        $mainUrl = $_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'];
    } 

	


    $oauthCallback = "http://".$mainUrl."/h2h/index.php?m=QmdVbGRwT01zbnd3bExBK1hIMlJKQT09";
    $scope = "purchase_invoice_view receive_item_view sales_invoice_view sales_order_view delivery_order_view item_view stock_mutation_history_view item_category_view item_adjustment_view fixed_asset_view stock_opname_result_view purchase_order_view";

    date_default_timezone_set('asia/jakarta');
    $oauthUrl = "https://account.accurate.id/oauth/authorize?client_id=$clientId&response_type=code&redirect_uri=$oauthCallback&scope=$scope";

    //ceisa 4.0
    //HKCI DEV
     $username ="hkcidev";
     $password ="Hkci1234";


    //CMMI PROD
    // $username ="cmmi2019";
    // $password ="Cmmi2019";

    

    
?>