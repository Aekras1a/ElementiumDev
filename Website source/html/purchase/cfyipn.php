<?php

include "CoinifyCallback.php";
include_once '/var/www/ctrl/accounts/operations.php';

$api_secret = "API_SECRET";

$expected_signature = strtolower( hash_hmac('sha256', file_get_contents("php://input"), $api_secret, false) );
$sig = $_SERVER['HTTP_X_COINIFY_CALLBACK_SIGNATURE'];

//$cbvalidator = new CoinifyCallback($api_secret);

if(/*$cbvalidator->validateCallback(file_get_contents("php://input"),$_SERVER['HTTP_X_COINIFY_CALLBACK_SIGNATURE'])*/$expected_signature == $sig) {
	$stuff = json_decode(file_get_contents("php://input"),true);
	
	if($stuff['event'] == "invoice_state_change" || $stuff['event'] == "invoice_manual_resend") { 
		if($stuff['data']['state'] == "complete") {
			$user = $stuff['data']['custom']['user'];
			$product = $stuff['data']['custom']['product'];
			
			user_add($product, $user);
			
		}
	} else {
		error_log("unrecog event: " . $stuff['event']);
	}
} else {
	error_log("Invalid callback");
	error_log("expected $expected_signature");
	error_log("received $sig");
}



?>
