<?php

$secret = "1q2W3e4R";

function verify_jwt($jwt, $secret){
        $header = json_encode(['alg' => 'HS256']);
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

if (isset($_GET["jwt"])) {
        $res = verify_jwt($_GET["jwt"], $secret);
        if ($res != false){
		$username = $res;
		$user = get_user_by('login', $username );

		if ( !is_wp_error( $user ) )
		{
    			wp_clear_auth_cookie();    wp_set_current_user ( $user->ID );
    			wp_set_auth_cookie  ( $user->ID );
		}
	}
	$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$link = explode('?', $actual_link)[0];
	header('Location: '.$link); exit;
}
