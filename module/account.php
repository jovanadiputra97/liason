<?php
function checkAccount($username, $password, $saveSession = true) {
	global $conn;
	$stmt = $conn->prepare("SELECT * FROM account WHERE username = ? AND password = ? AND isdelete = 0");
	$stmt->execute(array($username, md5($password)));
	if ($stmt->rowCount() > 0) {
		session_start();
		$account = $stmt->fetch(PDO::FETCH_ASSOC);
		$_SESSION['userno'] = $account['no'];
		$_SESSION['username'] = $username;
		$_SESSION['password'] = $password;
		if ($saveSession) insertLog(getLogCategoryNoByName('Login'), $account['no'], getPageNoByName('Home'), 'Login Berhasil');
	} else {
		if ($saveSession) insertLog(getLogCategoryNoByName('Login'), null, null, 'Login Gagal dengan username '.$username.' dan password '.$password);
		throw new Exception("Username dan atau password salah");
	}
}
function checkAccountSession($username, $password) {
	try {
		checkAccount($username, $password, false);
	} catch (Exception $e) {
		session_destroy();
		header("Location: login");
	}
}

function checkAccountLevelPage($accountNo, $pageNo) {
	global $conn;
	$stmtAccountLevelPage = $conn->prepare("SELECT * FROM account a LEFT JOIN account_level_page alp ON a.account_level_no = alp.account_level_no WHERE a.no = ? AND page_no = ? AND a.isdelete = 0");
	$stmtAccountLevelPage->execute(array($accountNo, $pageNo));
	return ($stmtAccountLevelPage->rowCount() > 0);
}

function getAccounts($searchvalues = array(), $sortfield = 'username', $sortvalue = 'ASC') {
	global $conn;
	$accountSql = "SELECT * FROM account WHERE 1 = 1 AND isdelete = 0";
	foreach ($searchvalues as $searchkey => $searchvalue) {
		$accountSql .= " AND ".$searchkey." ".$searchvalue;
	}
	$accountSql .= " ORDER BY ".$sortfield." ".$sortvalue;
	$stmtAccounts = $conn->prepare($accountSql);
	$stmtAccounts->execute();
	return $stmtAccounts;
}

function getAccountWithLevels($searchvalues = array(), $sortfield = 'username', $sortvalue = 'ASC') {
	global $conn;
	$accountSql = "SELECT a.*, al.name FROM account a LEFT JOIN account_level al ON a.account_level_no = al.no WHERE 1 = 1 AND a.isdelete = 0 AND al.isdelete = 0";
	foreach ($searchvalues as $searchkey => $searchvalue) {
		$accountSql .= " AND ".$searchkey." ".$searchvalue;
	}
	$accountSql .= " ORDER BY ".$sortfield." ".$sortvalue;
	$stmtAccounts = $conn->prepare($accountSql);
	$stmtAccounts->execute();
	return $stmtAccounts;
}

function getAccountLevels($searchvalues = array(), $sortfield = 'name', $sortvalue = 'ASC') {
	global $conn;
	$accountLevelSql = "SELECT * FROM account_level WHERE 1 = 1 AND isdelete = 0";
	foreach ($searchvalues as $searchkey => $searchvalue) {
		$accountLevelSql .= " AND ".$searchkey." ".$searchvalue;
	}
	$accountLevelSql .= " ORDER BY ".$sortfield." ".$sortvalue;
	$stmtAccountLevels = $conn->prepare($accountLevelSql);
	$stmtAccountLevels->execute();
	return $stmtAccountLevels;
}

function getAccountPages($accountNo) {
	global $conn;
	$stmtAccountLevelPages = $conn->prepare("SELECT p.no, p.name FROM account a LEFT JOIN account_level_page alp ON a.account_level_no = alp.account_level_no LEFT JOIN page p ON alp.page_no = p.no WHERE a.no = ? AND a.isdelete = 0 ORDER BY parent, sortno");
	$stmtAccountLevelPages->execute(array($accountNo));
	return $stmtAccountLevelPages;
}

function getAccountLevelPages($accountLevelNo) {
	global $conn;
	$stmtAccountLevelPages = $conn->prepare("SELECT p.no, p.name FROM account_level_page alp LEFT JOIN page p ON alp.page_no = p.no WHERE account_level_no = ? ORDER BY parent, sortno");
	$stmtAccountLevelPages->execute(array($accountLevelNo));
	return $stmtAccountLevelPages;
}

function updateAccount($accountNo, $newUsername, $newAccountLevelNo) {
	global $conn;
	if (getAccounts(array("no" => " <> ".$accountNo, "LOWER(username)" => " LIKE '".strtolower($newUsername)."'"))->rowCount() > 0) return false;
	$stmtAccounts = $conn->prepare("UPDATE account SET username = ?, account_level_no = ? WHERE no = ?");
	$stmtAccounts->execute(array($newUsername, $newAccountLevelNo, $accountNo));
	return true;
}

function updatePassword($accountNo, $newPassword) {
	global $conn;
	$stmtAccounts = $conn->prepare("UPDATE account SET password = ? WHERE no = ?");
	$stmtAccounts->execute(array(md5($newPassword), $accountNo));
}

function updateAccountLevel($accountLevelNo, $newAccountLevelName) {
	global $conn;
	if (getAccountLevels(array("no" => " <> ".$accountLevelNo, "LOWER(name)" => " LIKE '".strtolower($newAccountLevelName)."'"))->rowCount() > 0) return false;
	$stmtAccountLevels = $conn->prepare("UPDATE account_level SET name = ? WHERE no = ?");
	$stmtAccountLevels->execute(array($newAccountLevelName, $accountLevelNo));
	return true;
}

function insertAccount($newUsername, $newPassword, $newAccountLevelNo) {
	global $conn;
	if (getAccounts(array("username" => " LIKE '".$newUsername."'"))->rowCount() > 0) return false;
	$stmtAccount = $conn->prepare("INSERT INTO account(username, password, account_level_no) VALUES(?, ?, ?)");
	$stmtAccount->execute(array($newUsername, md5($newPassword), $newAccountLevelNo));
	return true;
}

function insertAccountLevel($newAccountLevelName) {
	global $conn;
	if (getAccountLevels(array("LOWER(name)" => " LIKE '".strtolower($newAccountLevelName)."'"))->rowCount() > 0) return false;
	$stmtAccountLevels = $conn->prepare("INSERT INTO account_level(name) VALUES(?)");
	$stmtAccountLevels->execute(array($newAccountLevelName));
	return true;
}

function deleteAccount($accountNo) {
	global $conn;
	
	$stmtAccounts = $conn->prepare("UPDATE account SET isdelete = 1 WHERE no = ?");
	$stmtAccounts->execute(array($accountNo));
}

function deleteAccountLevel($accountLevelNo) {
	global $conn;
	
	deleteAccountLevelPage($accountLevelNo);
	
	$stmtAccountLevels = $conn->prepare("UPDATE account_level SET isdelete = 1 WHERE no = ?");
	$stmtAccountLevels->execute(array($accountLevelNo));
}

function insertAccountLevelPage($accountLevelNo, $pageNo) {
	global $conn;
	$stmtAccountLevelPages = $conn->prepare("INSERT INTO account_level_page(account_level_no, page_no) VALUES(?, ?)");
	$stmtAccountLevelPages->execute(array($accountLevelNo, $pageNo));
}

function deleteAccountLevelPage($accountLevelNo, $pageNo = "") {
	global $conn;
	$accountLevelPageSql = "DELETE FROM account_level_page WHERE account_level_no = ?";
	if ($pageNo != "") {
		$accountLevelPageSql .= " AND page_no = ?";
	}
	$stmtAccountLevelPages = $conn->prepare($accountLevelPageSql);
	$paramArray = array($accountLevelNo);
	if ($pageNo != "") {
		array_push($paramArray, $pageNo);
	}
	$stmtAccountLevelPages->execute($paramArray);
}
?>