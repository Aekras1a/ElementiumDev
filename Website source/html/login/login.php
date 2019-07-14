<?php 

include_once "/var/www/ctrl/accounts/login.php";

$result = login($_GET['username'], $_GET['password']);
if($result['success'] == true) {
	$user = $result['auth'];
	setcookie("user", $result['auth'], time()+60*60*24*7, "/");
	echo json_encode(array("success"=>true));
} else {
	echo json_encode(array("success"=>false,"error"=>"Invalid login."));
}

?>
