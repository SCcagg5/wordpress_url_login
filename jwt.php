<?php 

function get_jwt($email, $secret, $time = 1) {
	$time = time() + ($time);
	$header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
	$payload = json_encode(['user' => $email, 'time' => $time]);
	$base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
	$base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
	$signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
	$base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
	$jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
	return $jwt;
}

function verify_jwt($jwt, $secret){
	$header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
	$base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));

	$res = explode('.', $jwt);
	if (count($res) != 3)
		return false;

	$signature = hash_hmac('sha256', $base64UrlHeader . "." . $res[1], $secret, true);
	$base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
	if ($base64UrlSignature != $res[2])
		return false;
	$payload = json_decode(base64_decode($res[1]), true);

	if ($payload["time"] < time())
		return false;

	return $payload["user"];
}

//$secret = "1q2W3e4R";
//$jwt = get_jwt("username", $secret, 1000);
//echo $jwt;
//$res = verify_jwt($jwt, $secret);
//echo $res;
