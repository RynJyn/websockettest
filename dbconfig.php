<?php	
//Ensure this file is not openly visible
$host = 'DBSERVER';
$db = 'DBNAME';
$user = 'DBUSER'; 
$pass = 'DBPASSWORD';
$charset = 'utf8';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$isDBConnected = false;

try {
	$pdo = new PDO($dsn,$user,$pass);
	$isDBConnected = true;
}
catch (PDOException $e)
{
	
}
?>