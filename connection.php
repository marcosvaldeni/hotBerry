<?php
try{
	$db_host = "162.241.203.95";
	$db_name = "horyco63_hotberry";

	$db_user = "horyco63_admin";
	$db_pass = "Cjn4&qpuKfmi";

	$conn = new PDO("mysql:host={$db_host};dbname={$db_name}", $db_user, $db_pass);
	$conn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch (PDOException $e) {echo $e->getMessage();}
?>
