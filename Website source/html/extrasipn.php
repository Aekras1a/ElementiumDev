<?php

include_once '/var/www/ctrl/accounts/extras.php';

$merch_id = "8340ef34e786d7e9cc2bbb8bcd69fc20";
$merch_secret = "peelpeel";

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