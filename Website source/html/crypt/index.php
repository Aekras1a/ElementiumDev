<?php

include_once '/var/www/ctrl/accounts/operations.php';

$user = "0";

if(isset($_COOKIE['user'])) {
	$result = check_auth($_COOKIE['user']);
	if($result['success'] == true) {
		$user = $result['username'];
	} else {
		echo "<b>You need to be logged in to crypt. <a href=\"../\">Homepage</a>";
	}
} else {
	echo "<b>You need to be logged in to crypt. <a href=\"../\">Homepage</a>";
}

if(!user_owns(3], $user)) {
	?>
		<div class="alert alert-danger" role="alert">
			
		<?php 
	echo "<b>You do not own this product.</b>";
	?></div><?php
	exit;
}

?>

<html>
<head>
<title>ElementiumDev | Crypter</title>

<script   src="https://code.jquery.com/jquery-2.2.3.min.js"   integrity="sha256-a23g1Nt4dtEYOj7bR+vTu7+T8VP13humZFBJNIYoEJo="   crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
<script src="../js/flat-ui.min.js" type="text/javascript"></script>
<script src="../assets/sweetalert.min.js"></script> <link rel="stylesheet" type="text/css" href="../assets/sweetalert.css">

</head>

<body>

<div class="container container-fluid">

	<div class="panel panel-primary">
	
	</div>

</div>

</body>

</html>
