<?php
function getSales($searchvalues = array(), $sortfield = 'event', $sortvalue = 'DESC') {
	global $conn;
	$salesSql = "SELECT s.*, c.name as customer_name, c.address as customer_address, c.phone as customer_phone, c.email as customer_email FROM sales s LEFT JOIN customer c ON s.customer_no = c.no WHERE 1 = 1";
	foreach ($searchvalues as $searchkey => $searchvalue) {
		$searchkey = str_replace("2", "", $searchkey);
		$salesSql .= " AND ".$searchkey." ".$searchvalue;
	}
	$salesSql .= " ORDER BY ".$sortfield." ".$sortvalue;
	$stmtSales = $conn->prepare($salesSql);
	$stmtSales->execute();
	return $stmtSales;
}

function getSaleDetails($searchvalues = array(), $sortfield = 'sales_no', $sortvalue = 'ASC') {
	global $conn;
	$saleDetailsSql = "SELECT sd.*, i.code as item_code, i.name as item_name, i.image as item_image, iu.name as item_unit_name FROM sales_detail sd LEFT JOIN item i ON sd.item_no = i.no LEFT JOIN item_unit iu ON sd.item_unit_no = iu.no WHERE 1 = 1";
	foreach ($searchvalues as $searchkey => $searchvalue) {
		$saleDetailsSql .= " AND ".$searchkey." ".$searchvalue;
	}
	$saleDetailsSql .= " ORDER BY ".$sortfield." ".$sortvalue;
	$stmtSaleDetails = $conn->prepare($saleDetailsSql);
	$stmtSaleDetails->execute();
	return $stmtSaleDetails;
}

function getNewSalesNo() {
	return getSales(array(), 'no', "DESC")->fetch(PDO::FETCH_ASSOC)['no']+1;
}

function insertSales($event, $customerNo, $total, $deadline, $notes, $outstanding) {
	global $conn;
	$stmtItem = $conn->prepare("INSERT INTO sales(event, customer_no, total, deadline, notes, outstanding) VALUES(?,?,?,?,?,?)");
	$stmtItem->execute(array($event, $customerNo, $total, $deadline, $notes, $outstanding));
}

function insertSalesDetail($salesNo, $itemNo, $itemUnitNo, $qty, $price, $discount, $subtotal) {
	global $conn;
	$stmtItem = $conn->prepare("INSERT INTO sales_detail(sales_no, item_no, item_unit_no, qty, price, discount, subtotal) VALUES(?,?,?,?,?,?,?)");
	$stmtItem->execute(array($salesNo, $itemNo, $itemUnitNo, $qty, $price, $discount, $subtotal));
}

function updateSalesPayment($salesNo, $newOutstanding, $newNotes) {
	global $conn;
	$stmtSales = $conn->prepare("UPDATE sales SET outstanding = ?, payment_notes = ? WHERE no = ?");
	$stmtSales->execute(array($newOutstanding, $newNotes, $salesNo));
}
?>