<?php

include_once '/var/www/ctrl/accounts/operations.php';

$merch_id = "MERCH_ID";
$merch_secret = "MERCH_SECRET";

if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
	if ($_SERVER['PHP_AUTH_USER'] == $merch_id && $_SERVER['PHP_AUTH_PW'] == $merch_secret) {
		$status = intval($_POST['status']);
		if($status >= 100 || $status == 2) {
			$txn_id = $_POST['txn_id'];
			$product = $_POST['item_number'];
			$user = $_POST['custom'];
			
			user_add($product, $user);
			
		}
	}
	die("IPN OK");
}