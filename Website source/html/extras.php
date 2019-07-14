<?php

include "/var/www/ctrl/accounts/extras.php";
include "/var/www/ctrl/payment/cp.php";

$user = "0";

if(isset($_COOKIE['user'])) {
	$result = check_auth($_COOKIE['user']);
	if($result['success'] == true) {
		$user = $result['username'];
	} else {
		die(json_encode(array("success"=>false,"error"=>"Not logged in.")));
	}
} else {
	die(json_encode(array("success"=>false,"error"=>"Not logged in.")));
}

if($_GET['id'] == "custom") {
	$amount = $_GET['amount'];
	$cp = cp_api("create_transaction", array("amount" => $amount, "currency1" => "USD", "currency2" => "BTC", "item_number" => "100", "item_name" => "Custom payment", "custom" => $user, "ipn_url" => "https://elementiumdev.com/extrasipn.php"));

	if($cp['error'] == "ok") {
		echo json_encode(array("success"=>true,"free"=>false,"amount"=>$cp['result']['amount'],"address"=>$cp['result']['address'],"url"=>$cp['result']['status_url']));
	} else {
		echo json_encode(array("success"=>false,"free"=>false,"error"=>$cp['error']));
	}
	exit;
}

$id = name_to_id($_GET['id']);

if(!user_owns($id, $user)) {
	$data = extra_data($id);
	$cp = cp_api("create_transaction", array("amount" => $data['price'], "currency1" => "USD", "currency2" => "BTC", "item_number" => $data['id'], "item_name" => $data['disp_name'], "custom" => $user, "ipn_url" => "https://elementiumdev.com/extrasipn.php"));
	if($cp['error'] == "ok") {
		echo json_encode(array("success"=>true,"free"=>false,"amount"=>$cp['result']['amount'],"address"=>$cp['result']['address'],"url"=>$cp['result']['status_url']));
	} else {
		echo json_encode(array("success"=>false,"free"=>false,"error"=>$cp['error']));
	}
} else {
	die(json_encode(array("success"=>false,"error"=>"You already own this addon.")));
}

?>