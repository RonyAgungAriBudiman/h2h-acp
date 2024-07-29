<?php
class sqlLib
{
	var $conn;
	var $srvr;
	var $db;
	var $usr;
	var $psw;

	function sqlLib()
	{
		include dirname(__FILE__) . "/config.php";
		$this->srvr = $srvr;
		$this->db = $db;
		$this->usr = $usr;
		$this->psw = $psw;
		$connectionInfo = array("Database" => $this->db, "UID" => $this->usr, "PWD" => $this->psw, 'ReturnDatesAsStrings' => true);
		$this->conn = sqlsrv_connect($this->srvr, $connectionInfo);

		if (!$this->conn)
			print "Connection not establish!!!";

		//sqlsrv_close( $this->conn );
	}

	function select($sql = "")
	{
		$params = array();
		$options =  array("Scrollable" => SQLSRV_CURSOR_KEYSET);

		if (empty($sql) || empty($this->conn)) return false;

		$result = sqlsrv_query($this->conn, $sql, $params, $options);
		if (empty($result)) return false;
		if (!$result) return false;;
		$data = array();
		$inc = 0;
		while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			$data[$inc] = $row;
			$inc++;
		}

		return $data;
	}

	function insert($sql = "")
	{
		$status = "1";
		if (empty($sql)) $status = "0";
		else {
			$sql = trim($sql);
			$sql_arr = explode(" ", strtolower($sql));
			if ($sql_arr[0] != "insert") $status = "0";
			else {
				$result = sqlsrv_query($this->conn, $sql);
				if (!$result) $status = "0";
			}
		}
		return $status;
	}

	function update($sql = "")
	{
		$status = "1";
		if (empty($sql)) $status = "0";
		else {
			$sql = trim($sql);
			$sql_arr = explode(" ", strtolower($sql));
			if ($sql_arr[0] != "update") $status = "0";
			else {
				$result = sqlsrv_query($this->conn, $sql);
				if (!$result) $status = "0";
			}
		}
		return $status;
	}
	function delete($sql = "")
	{
		$status = "1";
		if (empty($sql)) $status = "0";
		else {
			$sql = trim($sql);
			$sql_arr = explode(" ", strtolower($sql));
			if ($sql_arr[0] != "delete") $status = "0";
			else {
				$result = sqlsrv_query($this->conn, $sql);
				if (!$result) $status = "0";
			}
		}
		return $status;
	}
}
date_default_timezone_set("Asia/Jakarta");



function encrypt($string, $key)
{
	$result = '';
	for ($i = 0; $i < strlen($string); $i++) {
		$char = substr($string, $i, 1);
		$keychar = substr($key, ($i % strlen($key)) - 1, 1);
		$char = chr(ord($char) + ord($keychar));
		$result .= $char;
	}
	return base64_encode($result);
}

function decrypt($string, $key)
{
	$result = '';
	$string = base64_decode($string);

	for ($i = 0; $i < strlen($string); $i++) {
		$char = substr($string, $i, 1);
		$keychar = substr($key, ($i % strlen($key)) - 1, 1);
		$char = chr(ord($char) - ord($keychar));
		$result .= $char;
	}
	return $result;
}


function antisqlinject($string)
{
	include dirname(__FILE__) . "/config.php";

	$link = mysqli_connect($srvr, $usr, $psw, $db);
	$string = stripslashes($string);
	$string = strip_tags($string);
	$string = mysqli_real_escape_string($link, $string);
	return $string;
}

function acakacak($action, $string)
{
	$output = false;

	$encrypt_method = "AES-256-CBC";
	$secret_key = 'This is my secret key';
	$secret_iv = 'This is my secret iv';

	// hash
	$key = hash('sha256', $secret_key);

	// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
	$iv = substr(hash('sha256', $secret_iv), 0, 16);

	if ($action == 'encode') {
		$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
		$output = base64_encode($output);
	} else if ($action == 'decode') {
		$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
	}

	return $output;
}


//$dateopen = "2022-01-01";


//$ipserver = "10.11.12.8";
