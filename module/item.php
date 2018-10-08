<?php
function getItems($searchvalues = array(), $sortfield = 'name', $sortvalue = 'ASC', $historyLookup = false) {
	global $conn;
	$itemSql = "SELECT * FROM item WHERE 1 = 1";
	if (!$historyLookup) $itemSql .= " AND isdelete = 0";
	foreach ($searchvalues as $searchkey => $searchvalue) {
		$itemSql .= " AND ".$searchkey." ".$searchvalue;
	}
	$itemSql .= " ORDER BY ".$sortfield." ".$sortvalue;
	$stmtItems = $conn->prepare($itemSql);
	$stmtItems->execute();
	return $stmtItems;
}

function getTotalItems() {
	global $conn;
	$itemSql = "SELECT COUNT(*) as Total FROM item WHERE isdelete = 0";
	$stmtItems = $conn->prepare($itemSql);
	$stmtItems->execute();
	$result = $stmtItems->fetch(PDO::FETCH_ASSOC);
	return $result['Total'];
}

function getItemWithTypes($searchvalues = array(), $sortfield = 'i.name', $sortvalue = 'ASC', $historyLookup = false) {
	global $conn;
	$itemSql = "SELECT i.*, it.name as item_type_name FROM item i LEFT JOIN item_type it ON i.item_type_no = it.no WHERE 1 = 1";
	if (!$historyLookup) $itemSql .= " AND i.isdelete = 0 AND it.isdelete = 0";
	foreach ($searchvalues as $searchkey => $searchvalue) {
		$itemSql .= " AND ".$searchkey." ".$searchvalue;
	}
	$itemSql .= " ORDER BY ".$sortfield." ".$sortvalue;
	$stmtItems = $conn->prepare($itemSql);
	$stmtItems->execute();
	return $stmtItems;
}

function getItemDetails($searchvalues = array(), $sortfield = 'name', $sortvalue = 'ASC') {
	global $conn;
	$itemTypeSql = "SELECT id.*, iu.name, i.code, i.name as item_name, i.image as item_image, it.name as item_type_name, i.image FROM item_detail id LEFT JOIN item_unit iu ON id.item_unit_no = iu.no LEFT JOIN item i ON id.item_no = i.no LEFT JOIN item_type it ON i.item_type_no = it.no WHERE 1 = 1 AND iu.isdelete = 0 AND i.isdelete = 0 AND it.isdelete = 0";
	foreach ($searchvalues as $searchkey => $searchvalue) {
		$itemTypeSql .= " AND ".$searchkey." ".$searchvalue;
	}
	$itemTypeSql .= " ORDER BY ".$sortfield." ".$sortvalue;
	$stmtItemTypes = $conn->prepare($itemTypeSql);
	$stmtItemTypes->execute();
	return $stmtItemTypes;
}

function getItemDetailsHistory($searchvalues = array(), $sortfield = 'event', $sortvalue = 'DESC') {
	global $conn;
	$itemTypeSql = "SELECT l.*, lc.name as lcname FROM log l LEFT JOIN log_category lc ON l.log_category_no = lc.no WHERE 1 = 1 AND l.page_no = 5";
	foreach ($searchvalues as $searchkey => $searchvalue) {
		$itemTypeSql .= " AND ".$searchkey." ".$searchvalue;
	}
	$itemTypeSql .= " ORDER BY ".$sortfield." ".$sortvalue;
	$stmtItemTypes = $conn->prepare($itemTypeSql);
	$stmtItemTypes->execute();
	return $stmtItemTypes;
}

function getItemDetailStocks($searchvalues = array(), $sortfield = 'event', $sortvalue = 'DESC') {
	global $conn;
	$itemTypeSql = "SELECT ids.*, id.price, id.stock as stock_last, iu.name, i.code, i.name as item_name, i.image as item_image, it.name as item_type_name, i.image, a.username FROM item_detail_stock ids LEFT JOIN item_detail id ON ids.item_no = id.item_no AND ids.item_unit_no = id.item_unit_no LEFT JOIN item_unit iu ON ids.item_unit_no = iu.no LEFT JOIN item i ON ids.item_no = i.no LEFT JOIN item_type it ON i.item_type_no = it.no LEFT JOIN account a ON ids.account_no = a.no WHERE 1 = 1 AND iu.isdelete = 0 AND i.isdelete = 0 AND it.isdelete = 0";
	foreach ($searchvalues as $searchkey => $searchvalue) {
		$itemTypeSql .= " AND ".$searchkey." ".$searchvalue;
	}
	$itemTypeSql .= " ORDER BY ".$sortfield." ".$sortvalue;
	$stmtItemTypes = $conn->prepare($itemTypeSql);
	$stmtItemTypes->execute();
	return $stmtItemTypes;
}

function getItemTypes($searchvalues = array(), $sortfield = 'name', $sortvalue = 'ASC') {
	global $conn;
	$itemTypeSql = "SELECT * FROM item_type WHERE 1 = 1 AND isdelete = 0";
	foreach ($searchvalues as $searchkey => $searchvalue) {
		$itemTypeSql .= " AND ".$searchkey." ".$searchvalue;
	}
	$itemTypeSql .= " ORDER BY ".$sortfield." ".$sortvalue;
	$stmtItemTypes = $conn->prepare($itemTypeSql);
	$stmtItemTypes->execute();
	return $stmtItemTypes;
}

function getItemUnits($searchvalues = array(), $sortfield = 'name', $sortvalue = 'ASC') {
	global $conn;
	$itemUnitSql = "SELECT * FROM item_unit WHERE 1 = 1 AND isdelete = 0";
	foreach ($searchvalues as $searchkey => $searchvalue) {
		$itemUnitSql .= " AND ".$searchkey." ".$searchvalue;
	}
	$itemUnitSql .= " ORDER BY ".$sortfield." ".$sortvalue;
	$stmtItemUnits = $conn->prepare($itemUnitSql);
	$stmtItemUnits->execute();
	return $stmtItemUnits;
}

function deleteItem($itemNo) {
	global $conn;
	
	$stmtItem = $conn->prepare("UPDATE item SET isdelete = 1 WHERE no = ?");
	$stmtItem->execute(array($itemNo));
}

function deleteItemDetail($itemNo,$itemUnitNo,$price) {
	global $conn;
	
	$stmtItem = $conn->prepare("DELETE FROM item_detail WHERE item_no = ? AND item_unit_no = ? AND price = ?");
	$stmtItem->execute(array($itemNo,$itemUnitNo,$price));
}

function deleteItemType($itemTypeNo) {
	global $conn;
	
	$stmtItemType = $conn->prepare("UPDATE item_type SET isdelete = 1 WHERE no = ?");
	$stmtItemType->execute(array($itemTypeNo));
}

function deleteItemUnit($itemUnitNo) {
	global $conn;
	
	$stmtItemUnit = $conn->prepare("UPDATE item_unit SET isdelete = 1 WHERE no = ?");
	$stmtItemUnit->execute(array($itemUnitNo));
}

function updateItem($itemNo, $kodeBarang, $namaBarang, $jenisBarang, $prevGambar, $gambar, $catatan) {
	global $conn;
	
	if (getItems(array("no" => " <> ".$itemNo, "LOWER(code)" => " LIKE '".strtolower($kodeBarang)."'"))->rowCount() > 0) return false;
	
	$uploadGambar = 0;
	$filename = "";
	if(file_exists($gambar['tmp_name']) && is_uploaded_file($gambar['tmp_name'])) {
		$uploadGambar = 1;
		unlink($prevGambar);
		$filename = "img/barang/".$kodeBarang."_".$gambar['name'];
		move_uploaded_file($gambar['tmp_name'], $filename);
	}
	
	$itemSql = "UPDATE item SET code = ?, name = ?, item_type_no = ?";
	if ($uploadGambar) $itemSql .= ", image = ?";
	$itemSql .= ", notes = ? WHERE no = ?";
	$stmtItem = $conn->prepare($itemSql);
	$itemParams = array($kodeBarang, $namaBarang, $jenisBarang);
	if ($uploadGambar) array_push($itemParams, $filename);
	array_push($itemParams, $catatan, $itemNo);
	$stmtItem->execute($itemParams);
	
	return true;
}

function updateItemDetail($itemNo, $itemUnitNo, $newPrice) {
	global $conn;
	$stmtItemType = $conn->prepare("UPDATE item_detail SET price = ? WHERE item_no = ? AND item_unit_no = ?");
	$stmtItemType->execute(array($newPrice, $itemNo, $itemUnitNo));
}

function updateItemType($itemTypeNo, $newName) {
	global $conn;
	if (getItemTypes(array("no" => " <> ".$itemTypeNo, "LOWER(name)" => " LIKE '".strtolower($newName)."'"))->rowCount() > 0) return false;
	$stmtItemType = $conn->prepare("UPDATE item_type SET name = ? WHERE no = ?");
	$stmtItemType->execute(array($newName, $itemTypeNo));
	return true;
}

function updateItemUnit($itemUnitNo, $newName) {
	global $conn;
	if (getItemUnits(array("no" => " <> ".$itemUnitNo, "LOWER(name)" => " LIKE '".strtolower($newName)."'"))->rowCount() > 0) return false;
	$stmtItemUnit = $conn->prepare("UPDATE item_unit SET name = ? WHERE no = ?");
	$stmtItemUnit->execute(array($newName, $itemUnitNo));
	return true;
}

function insertItem($kodeBarang, $namaBarang, $jenisBarang, $gambar, $itemDetails, $catatan) {
	global $conn;
	if (getItems(array("LOWER(code)" => " LIKE '".strtolower($kodeBarang)."'"))->rowCount() > 0) return false;
	
	$filename = "img/barang/".$kodeBarang."_".$gambar['name'];
	move_uploaded_file($gambar['tmp_name'], $filename);
	$stmtItem = $conn->prepare("INSERT INTO item(code,name,item_type_no,image,notes) VALUES(?,?,?,?,?)");
	$stmtItem->execute(array($kodeBarang, $namaBarang, $jenisBarang, $filename, $catatan));
	
	insertItemDetails(getItems(array("code" => " LIKE '%".$kodeBarang."%'"))->fetch(PDO::FETCH_ASSOC)['no'], $itemDetails);
	
	return true;
}

function insertItemDetail($no, $itemDetail) {
	global $conn;
	$stmtItem = $conn->prepare("INSERT INTO item_detail(item_no, item_unit_no, price) VALUES(?,?,?)");
	$stmtItem->execute(array($no, $itemDetail['ukuran'], $itemDetail['harga']));
}

function insertItemDetails($no, $itemDetails) {
	global $conn;
	foreach ($itemDetails as $itemDetail) {
		$stmtItem = $conn->prepare("INSERT INTO item_detail(item_no, item_unit_no, price) VALUES(?,?,?)");
		$stmtItem->execute(array($no, $itemDetail['ukuran'], $itemDetail['harga']));
	}
}

function insertItemType($newName) {
	global $conn;
	if (getItemTypes(array("LOWER(name)" => " LIKE '".strtolower($newName)."'"))->rowCount() > 0) return false;
	$stmtItemType = $conn->prepare("INSERT INTO item_type(name) VALUES(?)");
	$stmtItemType->execute(array($newName));
	return true;
}

function insertItemUnit($newName) {
	global $conn;
	if (getItemUnits(array("LOWER(name)" => " LIKE '".strtolower($newName)."'"))->rowCount() > 0) return false;
	$stmtItemUnit = $conn->prepare("INSERT INTO item_unit(name) VALUES(?)");
	$stmtItemUnit->execute(array($newName));
	return true;
}

function addItemStock($noBarang, $jenisUkuran, $stok) {
	global $conn;
	$itemDetail = getItemDetails(array("item_no" => " = ".$noBarang, "item_unit_no" => " = ".$jenisUkuran))->fetch(PDO::FETCH_ASSOC);
	$stmtItemDetail = $conn->prepare("UPDATE item_detail SET stock = ? WHERE item_no = ? AND item_unit_no = ?");
	$stmtItemDetail->execute(array(((double) $itemDetail['stock'] + (double) $stok), $noBarang, $jenisUkuran));
}

function insertItemStock($noBarang, $jenisUkuran, $stok, $adjustment = 0, $sales = 0) {
	global $conn;
	$itemDetailSql = "INSERT INTO item_detail_stock(event, item_no, item_unit_no, stock, account_no";
	if ($adjustment) $itemDetailSql .= ", adjustment";
	if ($sales) $itemDetailSql .= ", sales";
	$itemDetailSql .= ") VALUES(?, ?, ?, ?, ?";
	if ($adjustment) $itemDetailSql .= ", ?";
	if ($sales) $itemDetailSql .= ", ?";
	$itemDetailSql .= ")";
	$stmtItemDetail = $conn->prepare($itemDetailSql);
	$itemDetailParams = array(date("Y-m-d H:i:s"), $noBarang, $jenisUkuran, $stok, $_SESSION['userno']);
	if ($adjustment) array_push($itemDetailParams, 1);
	if ($sales) array_push($itemDetailParams, 1);
	$stmtItemDetail->execute($itemDetailParams);
	
	addItemStock($noBarang, $jenisUkuran, $stok);
	return true;
}
?>