<?php
include_once "/var/www/ctrl/payment/cp.php";
include_once "/var/www/ctrl/accounts/operations.php";

$user = "0";

if(isset($_COOKIE['user'])) { 
	$result = check_auth($_COOKIE['user']);
	if($result['success'] == true) {
		$user = $result['username'];
	} else { 
		echo "<b>You need to be logged in to purchase an item. <a href=\"../\">Homepage</a>";
	}
} else {
	echo "<b>You need to be logged in to purchase an item. <a href=\"../\">Homepage</a>";
}

if(!isset($_GET['id'])) {
	echo json_encode(array("success"=>false,"error"=>"Invalid product ID."));
	exit;
}

if(!product_exists($_GET['id'])) {
	echo json_encode(array("success"=>false,"error"=>"Missing product."));
	exit;
}

$data = product_data($_GET['id']);

if(isset($_GET['coupon'])) {
	$price = $data['price'];
	$coupon = check_coupon($_GET['coupon'], $_GET['id']);

	if($coupon['exists']) {
		if($coupon['type'] == 1) {
			// percentage
		
			$percentage = $coupon['percent'];
			$data['price'] = $price * ((100-$percentage)/100);
		} else if($coupon['type'] == 2) {
			//flat
			
			$flat = $coupon['flat'];
			$data['price'] = $price - $flat;
		}
		
		
	}
}

if($data['price'] == 0) {
	user_add($_GET['id'], $user);
	echo json_encode(array("success"=>true,"free"=>true));
	die();
}

if(!isset($_GET['method'])) {
	
}

switch ($_GET['method']):
	case "cp":
		$cp = cp_api("create_transaction", array("amount" => $data['price'], "currency1" => "USD", "currency2" => "BTC", "item_number" => $data['id'], "item_name" => $data['disp_name'], "custom" => $user));
		if($cp['error'] == "ok") {
			echo json_encode(array("success"=>true,"free"=>false,"amount"=>$cp['result']['amount'],"address"=>$cp['result']['address'],"url"=>$cp['result']['status_url']));
		} else {
			echo json_encode(array("success"=>false,"free"=>false,"error"=>$cp['error']));
		}
		break;
	case "pm":
		$url = 'https://perfectmoney.is/api/step1.asp';
		$fields = array(
			'PAYEE_ACCOUNT' => "U9682557",
			'PAYEE_NAME' => "ElementiumDev",
			'PAYMENT_AMOUNT' => $data['price'],
			'PAYMENT_UNITS' => "USD",
			'STATUS_URL' => "https://elementiumdev.com/purchase/pmipn.php",
			'PAYMENT_URL' => "https://elementiumdev.com",
			'NOPAYMENT_URL' => "https://elementiumdev.com",
			'BAGGAGE_FIELDS' => "CUSTOMER_ID",
			'CUSTOMER_ID' => $user
		);
		
		echo json_encode(array("success"=>true,"free"=>false,"fields"=>$fields));
		break;
	case "cfy":
		include "CoinifyAPI.php";
		$api_key = "SMxqK1i1BE1Lb+I/+b+kAoagTxxZuAMuB2iGSR1/qcIZeEYAaS/qq61e+49wA0UY";
		$api_secret = "japyo986OvbU9Mknui/GklUHXdE2OX+yYfoapoq4zgH9ujzju5DW3mjrtrvA/H+s";
		$api = new CoinifyAPI($api_key, $api_secret);
		
		$resp = $api->invoiceCreate($data['price'],"USD","ElementiumDev","1.0",null,array("user"=>$user,"product"=>$_GET['id']),"https://elementiumdev.com/purchase/cfyipn.php",null,"https://elementiumdev.com","https://elementiumdev.com");
		if($resp == false) {
			echo json_encode(array("success"=>false,"free"=>false,"error"=>"Can't create transaction. Please use CoinPayments."));
			exit;
		}
		
		$url = $resp['data']['payment_url'];
		echo json_encode(array("success"=>true,"free"=>false,"url"=>$url));
		
endswitch;