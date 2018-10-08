<?php
function getCustomer($searchvalues = array(), $sortfield = 'name', $sortvalue = 'ASC') {
	global $conn;
	$customerSql = "SELECT * FROM customer WHERE 1 = 1 AND isdelete = 0";
	foreach ($searchvalues as $searchkey => $searchvalue) {
		$customerSql .= " AND ".$searchkey." ".$searchvalue;
	}
	$customerSql .= " ORDER BY ".$sortfield." ".$sortvalue;
	$stmtCustomer = $conn->prepare($customerSql);
	$stmtCustomer->execute();
	return $stmtCustomer;
}

function getTotalCustomers() {
	global $conn;
	$itemSql = "SELECT COUNT(*) as Total FROM customer WHERE isdelete = 0";
	$stmtItems = $conn->prepare($itemSql);
	$stmtItems->execute();
	$result = $stmtItems->fetch(PDO::FETCH_ASSOC);
	return $result['Total'];
}

function getLastCustomerNo() {
	global $conn;
	$itemSql = "SELECT MAX(no) as Last FROM customer WHERE isdelete = 0";
	$stmtItems = $conn->prepare($itemSql);
	$stmtItems->execute();
	$result = $stmtItems->fetch(PDO::FETCH_ASSOC);
	return $result['Last'];
}

function deleteCustomer($customerNo) {
	global $conn;
	$isdeletevalue = 1;
	$stmtCustomer = $conn->prepare("UPDATE customer SET isdelete = ? WHERE no = ?");
	$stmtCustomer->execute(array($isdeletevalue, $customerNo));
}

function updateCustomer($customerNo, $newName, $newAddress, $newPhone, $newEmail, $newNotes) {
	global $conn;
	$stmtItemType = $conn->prepare("UPDATE customer SET name = ?, address = ?, phone =?, email=?, notes=? WHERE no = ?");
	$stmtItemType->execute(array($newName, $newAddress, $newPhone, $newEmail, $newNotes, $customerNo));
}

function insertCustomer($newName, $newAddress, $newPhone, $newEmail, $newNotes) {
	global $conn;
	$stmtItemType = $conn->prepare("INSERT INTO customer(name, address, phone, email, notes) VALUES(?, ?, ?, ?, ?)");
	$stmtItemType->execute(array($newName, $newAddress, $newPhone, $newEmail, $newNotes));
}
?>