<?php
function insertLog($logCategory, $account, $page, $desc) {
	global $conn;
	$insertsql = "INSERT INTO log(event, log_category_no";
	if ($account != null) $insertsql .= ", account_no";
	if ($page != null) $insertsql .= ", page_no";
	$insertsql .= ", description) VALUES('".date('Y-m-d H:i:s')."', ".$logCategory;
	if ($account != null) $insertsql .= ", ".$account;
	if ($page != null) $insertsql .= ", ".$page;
	$insertsql .= ", '".$desc."')";
	$stmt = $conn->prepare($insertsql);
	$stmt->execute();
}

function getLogCategoryNoByName($name) {
	global $conn;
	$stmtLogCategory = $conn->prepare("SELECT * FROM log_category WHERE name = '".$name."'");
	$stmtLogCategory->execute();
	if ($stmtLogCategory->rowCount() > 0) {
		$rowLogCategory = $stmtLogCategory->fetch(PDO::FETCH_ASSOC);
		return $rowLogCategory['no'];
	} else {
		throw new Exception('Gagal mendapatkan no kategori log untuk '.$name);
	}
}

function getLogCategoryNameByNo($no) {
	global $conn;
	$stmtLogCategory = $conn->prepare("SELECT * FROM log_category WHERE no = '".$no."'");
	$stmtLogCategory->execute();
	if ($stmtLogCategory->rowCount() > 0) {
		$rowLogCategory = $stmtLogCategory->fetch(PDO::FETCH_ASSOC);
		return $rowLogCategory['name'];
	} else {
		throw new Exception('Gagal mendapatkan no kategori log untuk '.$name);
	}
}
?>