<?php
	/*
	$db_host = "162.241.203.95";
	$db_name = "horyco63_hotberry";

	$db_user = "horyco63_admin";
	$db_pass = "Cjn4&qpuKfmi";

$HOST = 'localhost';
  $USER = 'root';
  $PASS = '';
  $DB = 'hotberry';
*/
  $HOST = 'localhost';
  $USER = 'root';
  $PASS = '';
  $DB = 'hotberry';

try{
	
	$conn = new PDO('mysql:host='.$HOST.';dbname='.$DB, $USER, $PASS);
	$conn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}catch (PDOException $e) {

	echo $e->getMessage();

}
?>
