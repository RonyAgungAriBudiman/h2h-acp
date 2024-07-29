<?php
$srvr = "DESKTOP-ULCKKFI\MSSQLSERVER19"; //"SMI"; //"10.11.12.2";//DESKTOP-ULCKKFI\MSSQLSERVER19;
$db = "db_inv";
$usr = "sa";
$psw = "saadmin";

date_default_timezone_set("Asia/Jakarta");
error_reporting(0);
$connectionInfo = array("Database" => "$db", "UID" => "$usr", "PWD" => "$psw", 'ReturnDatesAsStrings' => true);
$connection = sqlsrv_connect($srvr, $connectionInfo);
if (!$connection) {
    echo "Connection could not be established";
}
