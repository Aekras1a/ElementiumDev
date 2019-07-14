<?php
include_once "/var/www/ctrl/config/database.php";

function name_to_id($name) {
	global $db;
	
	$stmt = $db->prepare("SELECT id FROM addons WHERE name = ?");
	$stmt->bind_param("s", $name);
	$stmt->execute();
	$result = $stmt->get_result();
	while($row = $result->fetch_assoc()) {
		return $row['id'];
	}
	
	return 0;
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

function user_owns($extra, $user) {
	$extras = user_get($user);
	foreach ($extras as $id) {
		if($id == $extra) {
			return true;
		}
	}

	return false;
}

function user_get($user) {
	global $db;

	$stmt = $db->prepare("SELECT extra FROM users WHERE username = ?");
	$stmt->bind_param("s", $user);
	$stmt->execute();
	$result = $stmt->get_result();

	while($row = $result->fetch_assoc()) {

		$ex = $row['extra'];
		if(strpos($ex, ",") !== false) {
			$arr = explode(",", $ex);
			return $arr;
		} else {
			return array(0);
		}

	}
}

function user_add($extra, $user) {
	global $db;

	if(!user_owns($extra, $user)) {
		$extras = user_get($user);
		array_push($extras, $extra);

		$stmt = $db->prepare("UPDATE users SET extra = ? WHERE username = ?");
		$stmt->bind_param("ss", implode(",", $extras), $user);
		$stmt->execute();
	}

}

function extra_data($extra) {
	global $db;

	$stmt = $db->prepare("SELECT * FROM addons WHERE id = ?");
	$stmt->bind_param("i", $extra);
	$stmt->execute();
	$result = $stmt->get_result();

	while($row = $result->fetch_assoc()) {
		return $row;
	}

	return array();
}

?>