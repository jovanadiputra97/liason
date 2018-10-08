<?php
try {
	$conn = new PDO("mysql:host=".$server.";dbname=".$db_name, $db_username, $db_password);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
	throw new Exception("Error : Tidak dapat terhubung ke database");
}
?>