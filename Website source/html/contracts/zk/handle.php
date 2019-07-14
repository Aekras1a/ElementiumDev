<?php

$db = new mysqli("localhost", "root", "69iSC5O7rabuS3mqCnrH", "zk");

if(!isset($_GET['phrase'])) {
	die("No phrase specified.");
}

$_GET['phrase'] = urldecode($_GET['phrase']);
if(!isset($_GET['content'])) {
	$stmt = $db->prepare("SELECT * FROM main WHERE phrase = ?");
	$stmt->bind_param("s",$_GET['phrase']);
	$stmt->execute();
	$result = $stmt->get_result();
	while($row = $result->fetch_assoc()) {
		die(json_encode(array("content"=>urlencode($row['content']))));
	}
	echo json_encode(array("content"=>""));
	exit;
} else {
	$_GET['content'] = urldecode($_GET['content']);
	$stmt = $db->prepare("SELECT phrase FROM main WHERE phrase = ?");
	$stmt->bind_param("s",$_GET['phrase']);
	$stmt->execute();
	$result = $stmt->get_result();
	if($result->num_rows == 0) {
		$stmt2 = $db->prepare("INSERT INTO main (phrase,content) VALUES (?,?)");
		$stmt2->bind_param("ss", $_GET['phrase'],$_GET['content']);
		$stmt2->execute();
		echo json_encode(array());
		exit;
	} else {
		$stmt2 = $db->prepare("UPDATE main SET content = ? WHERE phrase = ?");
		$stmt2->bind_param("ss", $_GET['content'],$_GET['phrase']);
		$stmt2->execute();
		echo json_encode(array());
		exit;
	}
}