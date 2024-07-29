<?php
function getlogin($username, $password)
{
	$curl = curl_init();
    //https://apisdev-gw.beacukai.go.id/nle-oauth/v1/user/login
    //https://nlehub-dev.kemenkeu.go.id/auth-amws/v1/user/login
  curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://apisdev-gw.beacukai.go.id/nle-oauth/v1/user/login',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
    "username": "'.$username.'",
    "password": "'.$password.'"
  }',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
  ));

  $response = curl_exec($curl);

  curl_close($curl);
  $result = json_decode($response);
  $access_login = $result->status;
  return $access_login;

}



?>