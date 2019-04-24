<?php
  /*
  $HOST = 'localhost';
  $USER = 'root';
  $PASS = '';
  $DB = 'hotberry';
  */
  
  $HOST = '160.153.128.24';
  $USER = 'admin_kfdsona';
  $PASS = 'Cjn4&qpuKfmi';
  $DB = 'hotberry';

try{
	
	$conn = new PDO('mysql:host='.$HOST.';dbname='.$DB, $USER, $PASS);
	$conn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}catch (PDOException $e) {

	echo $e->getMessage();

}
?>
