<?php
include_once("setting.php");
include_once("database.php");
include_once("log.php");
include_once("module/page.php");
include_once("account.php");

if ($checkSession) {
	session_start();
	checkAccountSession($_SESSION['username'], $_SESSION['password']);
	include_once("security.php");
}
?>