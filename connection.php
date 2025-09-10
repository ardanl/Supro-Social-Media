<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

@ob_start();

date_default_timezone_set('Europe/Istanbul');

$db_user = ""; 
$db_pass = ""; 
$db_name = ""; 
$host_name = "";

try {
	$conn = new PDO("mysql:host=$host_name;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	try {
		$conn = new PDO("mysql:host=$host_name", $db_user, $db_pass);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$conn->exec("CREATE DATABASE $db_name");

		$conn = new PDO("mysql:host=$host_name;dbname=$db_name", $db_user, $db_pass);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $ex) {
		echo 'Connection failed: ' . $ex->getMessage();
	}
}
$conn->query("SET NAMES utf8mb4");
$conn->query("SET CHARACTER SET utf8mb4");
$conn->query("SET COLLATION_CONNECTION = 'utf8mb4_general_ci'");

session_start();

include "DB.php";

include "fonksiyonlar.php";
