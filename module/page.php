<?php
function getPageGroups() {
	global $conn;
	$stmtPageGroups = $conn->prepare("SELECT * FROM page_group ORDER BY sortno");
	$stmtPageGroups->execute();
	return $stmtPageGroups;
}

function getPageRoots($pageGroup = "") {
	global $conn;
	$pageRootSql = "SELECT * FROM page WHERE parent is null";
	if ($pageGroup != "") {
		$pageRootSql .= " AND page_group_no = ".$pageGroup;
	}
	$pageRootSql .= " ORDER BY sortno";
	$stmtPageRoots = $conn->prepare($pageRootSql);
	$stmtPageRoots->execute();
	return $stmtPageRoots;
}

function getSubPages($parentPage) {
	global $conn;
	$stmtSubPages = $conn->prepare("SELECT * FROM page WHERE parent = ".$parentPage." ORDER BY sortno");
	$stmtSubPages->execute();
	return $stmtSubPages;
}

function getPageNoByName($name) {
	global $conn;
	$stmtPage = $conn->prepare("SELECT * FROM page WHERE name = '".$name."'");
	$stmtPage->execute();
	if ($stmtPage->rowCount() > 0) {
		$rowPage = $stmtPage->fetch(PDO::FETCH_ASSOC);
		return $rowPage['no'];
	} else {
		throw new Exception('Gagal mendapatkan no halaman untuk '.$name);
	}
}
?>