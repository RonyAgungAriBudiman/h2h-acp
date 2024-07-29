<?php
function jsonPage($accessToken, $session, $host)
{
    // Header
    $header = array(
        "Content-Type: application/json",
        "Authorization: Bearer $accessToken",
        "X-SESSION-ID: $session"
    );

    // Content
    $content = array(
        "id" => "101"
        //"fields" => "id,no,number,approvalStatus,statusName,transDate",
        // "filter.transDate.op" => "BETWEEN",
        // "filter.transDate.val[0]" => $dari,
        // "filter.transDate.val[1]" => $sampai,


    );

    // URL 
    $url = $host . "/accurate/api/item-category/detail.do?" . http_build_query($content);

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
    echo "<pre>";
    print_r($row_result_api);
    echo "</pre>";
}
