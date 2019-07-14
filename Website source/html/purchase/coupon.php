<?php

include_once '/var/www/ctrl/accounts/operations.php';

$coupon = check_coupon($_GET['code'], $_GET['id']);
if($coupon['exists'] == true) {
	
	$price = floatval($_GET['price']);
	if($coupon['type'] == 1) {
		// percentage
		
		$percentage = $coupon['percent'];
		$new_price = $price * ((100-$percentage)/100);
		echo json_encode(array("success"=>true, "new_price"=>$new_price));
	} else if($coupon['type'] == 2) {
		//flat
		
		$flat = $coupon['flat'];
		$new_price = $price - $flat;
		
		if($new_price > 0) {
			echo json_encode(array("success"=>true, "new_price"=>$new_price));
		} else {
			echo json_encode(array("success"=>false, "error"=>"Price cannot be less than zero."));
		}
	}
} else {
	echo json_encode(array("success"=>false, "error"=>"Coupon does not exist."));
}

?>