<?php

if(!isset($_GET['product'])) {
	echo json_encode(array("success"=>false));
	exit();
}

include "/var/www/ctrl/accounts/operations.php";

if($_GET['product'] == "mosdl") {
	$product = 1;
} else if($_GET['product'] == "irc") {
	$product = 2;
} else if($_GET['product'] == "shellexec") {
	$product = 3;
} else {
	echo json_encode(array("success"=>false));
	exit();
}

$data = product_data($product);

$array = array();
$array['success'] = true;
$array['name'] = $data['disp_name'];
$array['content'] = $data['description'];
echo json_encode($array);
exit();

?>