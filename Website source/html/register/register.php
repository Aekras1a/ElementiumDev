<?php

include_once "/var/www/ctrl/accounts/register.php";

$ip = $_SERVER['REMOTE_ADDR'];
if(isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
	$ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
}

$result = register($ip, $_GET['username'], $_GET['password']);

if($result['success'] == true) {
	echo json_encode(array("success"=>true));
} else {
	echo json_encode(array("success"=>false,"error"=>$result['error']));
}
	
?>