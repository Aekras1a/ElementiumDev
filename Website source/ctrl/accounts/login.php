<?php

include_once "/var/www/ctrl/config/database.php";
include_once "operations.php";

function login($username, $pass) {
	$result = check_pass($username, $pass);
	
	return $result;
	
}