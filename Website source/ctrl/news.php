<?php
include_once "/var/www/ctrl/config/database.php";

function get_news() {
	global $db;
	
	$result = $db->query("SELECT * FROM news ORDER BY id DESC");
	$array = array();
	while($row = $result->fetch_assoc()) {
		$array[$row['id']] = array("title"=>$row['title'],"content"=>$row['content'],"time"=>date("H:i d F Y",strtotime($row['time'])));
	}
	
	return $array;
}

?>