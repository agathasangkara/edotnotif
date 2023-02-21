<?php
error_reporting(0);
date_default_timezone_set("Asia/Jakarta");

class Alarmedot {
	
	const referral = '2DLU46A7D6';
	
	public function curl($param,$headers,$url,$customreq,$post,$curlheader,$method)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
		if($post==true)
		{
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
		}
		curl_setopt($ch, CURLOPT_ENCODING, "GZIP");
		if($customreq==true)
		{
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		}
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		if($curlheader==true)
		{
			curl_setopt($ch, CURLOPT_HEADER, 1);
		}
		$result = curl_exec($ch);
		if (curl_errno($ch)) {
   		 echo 'Error:' . curl_error($ch);
   	 }
		curl_close($ch);
		return $result;
	}

	public function getSso($device)
	{
		$url = "https://api-accounts.edot.id/api/token/get";
		$query = array(
			"name" => "web-sso",
			"secret_key" => "3e53440178c568c4f32c170f",
			"device_type" => "web",
			"device_id" => $device
			);
		$param = json_encode($query, true);
		$headers = array();
		$headers[] = 'Content-Length: '.strlen($param);
		$headers[] = 'User-Agent: Mozilla/5.0 (Linux; Android 11; RMX2063 Build/RKQ1.201112.002; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/94.0.4606.85 Mobile Safari/537.36';
		$headers[] = 'Content-Type: application/json';
		$headers[] = 'Accept: */*';
		$headers[] = 'Accept-Encoding: gzip, deflate';
		$headers[] = 'Accept-Language: id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7';
		return $this->curl($param,$headers,$url,$customreq = null,$post = true,$curlheader = false,$method = null);
	}

	public function checkCode($referral,$ssotoken)
	{
		$url = "https://api-accounts.edot.id/api/user/check_referral_code?referral_code=$referral";
		$method = "GET";
		$headers = array();
		//$headers[] = 'Content-Length: '.strlen($param);
		$headers[] = 'User-Agent: Mozilla/5.0 (Linux; Android 10; Redmi Note 8 Build/QQ3A.200905.001; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/93.0.4577.82 Mobile Safari/537.36';
		$headers[] = 'Sso-token: '.$ssotoken;
		return $this->curl($param =null,$headers,$url,$customreq = true,$post = false,$curlheader = false,$method = "GET");
	}
}

$index = new Alarmedot();
checkpoint:
$device = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'), 0, 36);
$getsso = $index->getSso($device);
$json = json_decode($getsso, true);
if($json['code'] == 200)
	{
		$ssotoken = $json['data']['token']['token_code'];
		echo " + $ssotoken ";
	} else {
		echo " + Failed get token sso device\n"; goto checkpoint;
	}

checkingcode:
$referral = Alarmedot::referral;
$cek = $index->checkCode($referral,$ssotoken);
var_dump($cek);
if(preg_match('/Kode referral tidak bisa digunakan karena melebihi kuota/i',$cek)){
	# THREADING TO GET OPENED EVENT (MAYBE)
	# USE FAST INTERNET TO GET MAX RESULT
	goto checkingcode;
	} else if(preg_match('/Must input referral code in query params/i',$cek))
	{
		# CHECKING PARAMETER CONST REFERRAL
		goto checkingcode;
		} else {
	#OPEN PAGE PHPV2.0
	system("php v2.php");
}
