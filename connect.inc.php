<?php
$mysql_host = 'localhost';
$mysql_user = 'dan';
$mysql_pass = 'the most secure';
$mysql_db = 'tc_test';
$connect_error = "Could not connect.";

if (!@mysqli_connect($mysql_host,$mysql_user,$mysql_pass,$mysql_db))
	die($connect_error);
?>