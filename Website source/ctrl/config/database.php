<?php

define("HOST", "127.0.0.1");
define("USERNAME", "root");
define("PASSWORD", "password goes here");
define("DB", "Elementium");

$db = new mysqli(HOST, USERNAME, PASSWORD, DB) or die("Cannot access MySQL database.");

/*

Accounts
- id
- username
- password (sha256 crypt)
- products

Products
- id
- disp_name
- name
- price
- live

*/


?>
