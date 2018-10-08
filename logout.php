<?php
$checkSession = true;
include_once("module/init.php");
insertLog(getLogCategoryNoByName('Login'), $_SESSION['userno'], null, 'Logout Berhasil');
session_start();
session_destroy();
header("Location: login");
?>