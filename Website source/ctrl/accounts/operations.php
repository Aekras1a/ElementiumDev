<?php

include_once "/var/www/ctrl/config/database.php";

function unique_user($username) {
	
	global $db;

	$stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
	$stmt->bind_param("s", $username);
	
	$stmt->execute();
	
	$result = $stmt->get_result();
	
	if($result->num_rows > 0) {
		return false;
	}
	
	return true;
	
}

function check_pass($username, $password) {
	
	global $db;
	
	$stmt = $db->prepare("SELECT id,password,auth FROM users WHERE username = ?");
	$stmt->bind_param("s", $username);
	$stmt->execute();
	$result = $stmt->get_result();
	while($row = $result->fetch_assoc()) {
		if(hash("sha256", $password) == $row['password']) {
			return array("success" => true, "id" => $row['id'], "auth" => $row['auth']);
		}
	}
	
	return array("success" => false);
	
}

function check_auth($auth) {
	global $db;
	
	$stmt = $db->prepare("SELECT id,username FROM users WHERE auth = ?");
	$stmt->bind_param("s", $auth);
	$stmt->execute();
	$result = $stmt->get_result();
	
	while($row = $result->fetch_assoc()) {
		return array("success" => true, "username" => $row['username'], "uid" => $row['id']);
	}
}

function user_owns($product, $user) {
	$products = user_get($user);
	foreach ($products as $id) {
		if($id == $product) {
			return true;
		}
	}
	
	return false;
}

function user_get($user) {
	global $db;
	
	$stmt = $db->prepare("SELECT product FROM users WHERE username = ?");
	$stmt->bind_param("s", $user);
	$stmt->execute();
	$result = $stmt->get_result();
	
	while($row = $result->fetch_assoc()) {
		
		$prod = $row['product'];
		if(strpos($prod, ",") !== false) {
			$arr = explode(",", $prod);
			return $arr;
		} else {
			return array(0);
		}
		
	}
}

function user_add($product, $user) {
	global $db;
	
	if(!user_owns($product, $user)) {
		$products = user_get($user);
		array_push($products, $product);
		
		$stmt = $db->prepare("UPDATE users SET product = ? WHERE username = ?");
		$stmt->bind_param("ss", implode(",", $products), $user);
		$stmt->execute();
	}
	
}

function generateRandomString($length = 128) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}

function product_exists($product) {
	global $db;
	
	$stmt = $db->prepare("SELECT id FROM products WHERE id = ?");
	$stmt->bind_param("i", $product);
	$stmt->execute();
	$result = $stmt->get_result();
	
	if($result->num_rows > 0) {
		return true;
	}
	
	return false;
}

function product_data($product) {
	global $db;
	
	$stmt = $db->prepare("SELECT * FROM products WHERE id = ?");
	$stmt->bind_param("i", $product);
	$stmt->execute();
	$result = $stmt->get_result();
	
	while($row = $result->fetch_assoc()) {
		return $row;
	}
	
	return array();
}

function check_coupon($code, $product) {
	global $db;
	
	$stmt = $db->prepare("SELECT * FROM coupons WHERE code = ?");
	$stmt->bind_param("s", $code);
	$stmt->execute();
	$result = $stmt->get_result();
	
	while($row = $result->fetch_assoc()) {
		$products = explode(",", $row['products']);
		if(in_array($product, $products)) {
			$data = $row;
			$data['exists'] = true;
			return $data;
		} else {
			return array("exists"=>false);
		}
	}

}