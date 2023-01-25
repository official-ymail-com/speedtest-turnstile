<?php
/* error_reporting(E_ALL);
ini_set("display_errors", 1); */

require_once('config.php');
$post_body = file_get_contents('php://input');
$arr = json_decode($post_body, true);

$action = '';
if(isset($arr['action'])){
	$action = filter_var( $arr['action'], FILTER_SANITIZE_SPECIAL_CHARS); 
}

$captcha = '';
$res = array( 'result' => 'fail' );
$res_code = '404';
if($action == 'validation' && isset($arr['cf_turnstile_response'])){
	$captcha = filter_var ( $arr['cf_turnstile_response'], FILTER_SANITIZE_SPECIAL_CHARS); 
	$validation_result = TurnStile_validation($captcha);
	if($validation_result){
		$res_code = '200';
		$res = array( 'result' => 'success' );
		$expiration = time() + TurnStile_Expiration;
		$secret = get_secret($captcha, $expiration);
		setcookie("cf-turnstile-response", $captcha, $expiration);
		setcookie("cf-turnstile-expiration", $expiration, $expiration);
		setcookie("cf-turnstile-secret", $secret, $expiration);
		http_response_code($res_code);
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($res);
		exit();
	}
}
if(isset($_COOKIE['cf-turnstile-response'], $_COOKIE['cf-turnstile-expiration'], $_COOKIE['cf-turnstile-secret']) ){
	$secret_validation = TurnStile_Check($_COOKIE['cf-turnstile-secret'], $_COOKIE['cf-turnstile-response'], $_COOKIE['cf-turnstile-expiration']);
	if($secret_validation){
		$res_code = '200';
	}
}

if($res_code != '200'){
	http_response_code($res_code);
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode($res);
	exit();
}

function TurnStile_validation($captcha){
	
	$res = false;
	$ip = $_SERVER['REMOTE_ADDR'];
	
	if (!$captcha) {
		return $res;
	}
	$data = array(
		'secret' => TurnStile_Site_Key, 
		'response' => $captcha, 
		'remoteip' => $_SERVER['REMOTE_ADDR']
	);
	$options = array(
		'http' => array(
			'header' => "Content-Type: application/x-www-form-urlencoded",
			'method' => 'POST',
			'content' => http_build_query($data)
		)
	);
	
	$stream = stream_context_create($options);
	try{
		$result = file_get_contents( TurnStile_API_URI, false, $stream );
	}catch (Exception $e) {
		echo 'Caught exception: ',  $e->getMessage(), "\n";
		exit;
	} 
	
	$responseKeys = json_decode($result, true);
	if(intval($responseKeys["success"]) == 1) {
		$res = true;
	}
	return $res;
}

function get_secret($captcha, $expiration){
	$str = "$captcha|$expiration|TurnStile_Site_Key";
	$md5 = md5($str);
	$res = substr($md5, 10, 15);
	return $res;
}

function TurnStile_Check($secret, $captcha, $expiration){
	$res = false;
	if(get_secret($captcha, $expiration) == $secret){
		$res = true;
	}
	if($expiration < time()){
		$res = false;
	}
	return $res;
}
