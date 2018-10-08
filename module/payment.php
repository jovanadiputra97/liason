<?php
function getPayments($searchvalues = array(), $sortfield = 'name', $sortvalue = 'ASC') {
	global $conn;
	$salesSql = "SELECT p.*, pt.name as payment_type_name FROM payment p LEFT JOIN payment_type pt ON p.payment_type_no = pt.no WHERE 1 = 1";
	foreach ($searchvalues as $searchkey => $searchvalue) {
		$salesSql .= " AND ".$searchkey." ".$searchvalue;
	}
	$salesSql .= " ORDER BY ".$sortfield." ".$sortvalue;
	$stmtSales = $conn->prepare($salesSql);
	$stmtSales->execute();
	return $stmtSales;
}

function getPaymentTypes($searchvalues = array(), $sortfield = 'name', $sortvalue = 'ASC') {
	global $conn;
	$salesSql = "SELECT * FROM payment_type WHERE 1 = 1";
	foreach ($searchvalues as $searchkey => $searchvalue) {
		$salesSql .= " AND ".$searchkey." ".$searchvalue;
	}
	$salesSql .= " ORDER BY ".$sortfield." ".$sortvalue;
	$stmtSales = $conn->prepare($salesSql);
	$stmtSales->execute();
	return $stmtSales;
}

function insertPayment($salesNo, $event, $paymentTypeNo, $total) {
	global $conn;
	$stmtPayment = $conn->prepare("INSERT INTO payment(sales_no, event, payment_type_no, total) VALUES(?,?,?,?)");
	$stmtPayment->execute(array($salesNo, $event, $paymentTypeNo, $total));
}
?>