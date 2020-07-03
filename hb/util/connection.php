<?php
 /*
  * This file takes care of all the connections to the database 
  * using the PDO technology from php documentation. It is important
  * as a security measure as it prevents SQL injection
  */

  $HOST = 'localhost';
  $USER = 'root';
  $PASS = '';
  $DB = 'hotberry';

try{
	// Creating the connection and saving in the variable $conn
  // It needs to be in a try/ catch block to test the connection
	$conn = new PDO('mysql:host='.$HOST.';dbname='.$DB, $USER, $PASS);
	$conn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}catch (PDOException $e) {

	echo $e->getMessage();

}
?>
