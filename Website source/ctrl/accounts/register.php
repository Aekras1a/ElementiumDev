<?php

include_once "/var/www/ctrl/config/database.php";
include_once "operations.php";

function register($ip, $username, $password) {
	
	if(!unique_user($username)) {
		return array("success" => false, "error" => "Username exists.");
	}
	
	if(strlen($password) < 8) {
		return array("success" => false, "error" => "Password must be at least eight characters.");
	}
	
	global $db;
	
	$stmt = $db->prepare("INSERT INTO users (ip, username, password, auth) VALUES (?,?,?,?)");
	$stmt->bind_param("ssss", $ip, $username, hash("sha256", $password), generateRandomString());
	
	$stmt->execute();
	
	return array("success" => true, "error" => "");
	
}