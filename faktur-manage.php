<?php
include_once("module/sales.php");
include_once("module/item.php");

session_start();

/*
include_once("../include/library.php");
include_once("../include/conn.php");
include_once("library/admin_sessioncheck.php");
*/

// ACTION
if (isset($_REQUEST['simpan']) &&  $_REQUEST['simpan'] == "Simpan" && $tokenError == 0) {
	$totalSales = 0;
	foreach ($_REQUEST['itemdetails'] as $itemDetail) {
		$priceAfterDiscount = $itemDetail[6] - $itemDetail[7];
		$totalPrice = $priceAfterDiscount * $itemDetail[5];
		$totalSales += $totalPrice;
	}
	$curDate = date('Y-m-d');
	insertSales($curDate, $_REQUEST['nopelanggan'], $totalSales, $_REQUEST['tanggaljatuhtempo'], $_REQUEST['catatan'], $totalSales);
	$salesno = getNewSalesNo()-1;
	foreach ($_REQUEST['itemdetails'] as $itemDetail) {
		$priceAfterDiscount = $itemDetail[6] - $itemDetail[7];
		$totalPrice = $priceAfterDiscount * $itemDetail[5];
		insertSalesDetail($salesno, $itemDetail[0], $itemDetail[2], $itemDetail[5], $itemDetail[6], $itemDetail[7], $totalPrice);
		insertItemStock($itemDetail[0], $itemDetail[2], (0-$itemDetail[5]), 0, 1);
	}
	insertLog(getLogCategoryNoByName('Tambah'), $_SESSION['userno'], getPageNoByName('+ Buat Faktur'), $salesno.";".$_REQUEST['nopelanggan'].";".$totalSales.";".$_REQUEST['tanggaljatuhtempo'].";".$_REQUEST['catatan']);
	if ($_REQUEST['printsuratjalan'] == "") {
?>
	<form name='FormSuccess' method='post' action='faktur-lihat'><input type="hidden" name="notificationerror" value="0"><input type='hidden' name='notification' value='Sukses <?php echo ($_REQUEST['ams'] == 1 ? "merubah" : "menambahkan") ?> faktur <?php echo sprintf("%07d", $salesno) ?>'></form>
	<script language="javascript">
	document.FormSuccess.submit();
	</script>
<?php
	} else {
?>
	<form name="FormCetakDelivery" method="post" action="print-suratjalan">
		<input type="hidden" name="ams" id="FormCetakDeliveryAms" value="1">
		<input type="hidden" name="no" id="FormCetakDeliveryNo" value="<?php echo $salesno ?>">
		<input type="hidden" name="token" id="FormCetakDeliveryToken" value="<?php echo generateToken("print-suratjalan-1-".$salesno) ?>">
	</form>
	<script language="javascript">
	document.FormCetakDelivery.submit();
	</script>
<?php
	}
} else {
	if (($_REQUEST['ams'] == 1) && ($tokenError == 0)) {
		$sales = getSales(array("s.no" => ' = '.$_REQUEST['no']))->fetch(PDO::FETCH_ASSOC);
		if ($_REQUEST['nofaktur'] == "") $_REQUEST['nofaktur'] = $sales['no'];
		if ($_REQUEST['tanggalfaktur'] == "") $_REQUEST['tanggalfaktur'] = date('d-m-Y', strtotime($sales['event']));
		if ($_REQUEST['nopelanggan'] == "") $_REQUEST['nopelanggan'] = $sales['customer_no'];
		if ($_REQUEST['tanggaljatuhtempo'] == "") $_REQUEST['tanggaljatuhtempo'] = date('Y-m-d', strtotime($sales['deadline']));
		if ($_REQUEST['catatan'] == "") $_REQUEST['catatan'] = $sales['notes'];
		$saleDetails = getSaleDetails(array("sales_no" => ' = '.$_REQUEST['no']));
		while ($saleDetail = $saleDetails->fetch(PDO::FETCH_ASSOC)) {
			$_REQUEST['itemdetails'][] = [$saleDetail['item_no'], $saleDetail['item_name'], $saleDetail['item_unit_no'], $saleDetail['item_unit_name'], $saleDetail['item_image'], $saleDetail['qty'], $saleDetail['price'], $saleDetail['discount']];
		}
	} else {
		$_REQUEST['nofaktur'] = getNewSalesNo();
		$_REQUEST['tanggalfaktur'] = date('d-m-Y');
	}
}

include_once("header.php");
include_once("header-main.php"); 
?>
        <?php
		include_once("navbar.php"); 
		?>
		<?php
		include_once("module/customer.php");
		?>
        <div class="content-inner">
          <!-- Page Header-->
          <header class="page-header">
            <div class="container-fluid">
              <h2 class="no-margin-bottom">Buat Faktur</h2>
            </div>
          </header>
          
		  <!-- Forms Section-->
          <section class="forms"> 
            <div class="container-fluid">
              <div class="row">
                
                <!-- Form Elements -->
                <div class="col-lg-12">
                  <div class="card">
                    
                    <div class="card-header d-flex align-items-center">
                      <h3 class="h4">Faktur</h3>
                    </div>
					
                    <div class="card-body">
                      <form class="form-horizontal" name="FormAdd" id="FormAdd" method="post" onsubmit="return verifyForm();">
                        <div class="form-group row">
                          <label class="col-sm-3 form-control-label">Tanggal</label>
                          <div class="col-sm-9">
                            <b><?php echo $_REQUEST['tanggalfaktur'] ?></b>
                          </div>
                        </div>
						
						<div class="form-group row">
                          <label class="col-sm-3 form-control-label">No Nota</label>
                          <div class="col-sm-9">
                            <b><?php echo sprintf("%07d",$_REQUEST['nofaktur']) ?></b>
                          </div>
                        </div>
						
						<div class="form-group row">
                          <label class="col-sm-3 form-control-label">Pelanggan</label>
                          <div class="col-sm-9">
														<?php
														if ($_REQUEST['ams'] == "") {
														?>
                            <select name="nopelanggan" class="form-control selectpadding" onchange="document.FormAdd.submit()" required>
															<option value="">- Pelanggan -</option>
															<?php
															$customer = getCustomer();
															while ($rowCustomer = $customer->fetch(PDO::FETCH_ASSOC)) {
															?>
                              <option value="<?php echo $rowCustomer['no'] ?>" <?php if ($rowCustomer['no'] == $_REQUEST['nopelanggan']) echo "selected" ?>><?php echo $rowCustomer['name'] ?> - <?php echo $rowCustomer['address'] ?></option>
															<?php
															}
															?>
                            </select>
														<?php
														}
														if ($_REQUEST['nopelanggan'] != "") {
															$curCustomer = getCustomer(array("no" => " = ".$_REQUEST['nopelanggan']))->fetch(PDO::FETCH_ASSOC);
														?>
														<b>Kepada Bapak/Ibu <?php echo $curCustomer['name'] ?></b><br>
														<?php echo $curCustomer['address'] ?><br>
														Telepon: <?php echo $curCustomer['phone'] ?><br>
														Email: <?php echo $curCustomer['email'] ?>
														<?php } ?>
                          </div>
                        </div>
						
						<?php if ($_REQUEST['ams'] == "") { ?>
						<div class="form-group row">
                          <label class="col-sm-3 form-control-label">Barang &amp; Banyaknya</label>
                          <div class="col-sm-9">
                            <select name="nobarang" id="nobarang" class="form-control selectpadding formauto displayinline" onchange="document.FormAdd.submit()">
															<option value="">- Kode Nama Barang -</option>
															<?php
															$items = getItems();
															while ($rowItem = $items->fetch(PDO::FETCH_ASSOC)) {
															?>
                              <option value="<?php echo $rowItem['no'] ?>" <?php if ($_REQUEST['nobarang'] == $rowItem['no']) echo "selected" ?>><?php echo $rowItem['code'] ?> - <?php echo $rowItem['name'] ?></option>
															<?php
															}
															?>
                            </select>
							&nbsp;
							<select name="jenisukuran" id="jenisukuran" class="form-control selectpadding formauto displayinline" onchange="document.FormAdd.submit()">
                              <option value="">- Jenis Ukuran -</option>
															<?php
															if ($_REQUEST['nobarang'] != "") {
																$itemDetails = getItemDetails(array("item_no" => " = ".$_REQUEST['nobarang']));
																while ($rowItemDetail = $itemDetails->fetch(PDO::FETCH_ASSOC)) {
															?>
                              <option value="<?php echo $rowItemDetail['item_unit_no'] ?>" <?php if ($_REQUEST['jenisukuran'] == $rowItemDetail['item_unit_no']) echo "selected" ?>><?php echo $rowItemDetail['name'] ?> (Rp <?php echo number_format($rowItemDetail['price'],0,",",".") ?>)</option>
															<?php
																}
															}
															?>
                            </select>
							&nbsp;
							Banyaknya <input type="number" class="form-control formauto displayinline lusinwidth" name="banyaknya" id="banyaknya" min="0.01" step="0.01">&nbsp;lusin
							<br>
							Diskon Rp.<input type="number" class="form-control formauto displayinline" name="diskon" id="diskon" min="0" step="1" value="0">
							&nbsp;
							<input type="button" class="btn btn-primary displayinline" name="tambah" value="Tambah" onclick="javascript:addDataDetails()">&nbsp;&nbsp;&nbsp;<span id="notificationerror" style="color:red; font-weight: bold"></span>
						  </div>
            </div>
						<?php } ?>
						
						<div class="form-group row">
						  <div class="col-sm-12">
							<div class="table-responsive">          
								<table class="table">
									<thead>
										<tr>
											<th>#</th>
											<th>Nama Barang</th>
											<th>Gambar</th>
											<th>Banyaknya</th>
											<th>Harga<br>(Diskon)</th>
											<th>Jumlah</th>
											<?php if ($_REQUEST['ams'] == "") { ?>
											<th class="tableactionwidth"></th>
											<?php } ?>
										</tr>
									</thead>
									<tbody id="tableDetailHarga">
									</tbody>
								</table>
							</div>
                          </div>
                        </div>
						
												<div class="form-group row">
                          <label class="col-sm-3 form-control-label">Tanggal Jatuh Tempo</label>
                          <div class="col-sm-9">
                            <input type="date" class="form-control" name="tanggaljatuhtempo" required value="<?php echo $_REQUEST['tanggaljatuhtempo'] ?>" <?php if ($_REQUEST['ams'] != "") echo "readonly" ?>>
                          </div>
                        </div>
						
												<div class="form-group row">
                          <label class="col-sm-3 form-control-label">Catatan</label>
                          <div class="col-sm-9">
                            <textarea class="form-control" name="catatan" <?php if ($_REQUEST['ams'] != "") echo "readonly" ?>><?php echo $_REQUEST['catatan'] ?></textarea>
                          </div>
                        </div>
						
												<?php if ($_REQUEST['ams'] == "") { ?>
												<div class="form-group row">
                          <label class="col-sm-3 form-control-label">Print Surat Jalan</label>
                          <div class="col-sm-9">
                            <input type="checkbox" name="printsuratjalan" value="1">&nbsp;Ya</span>
                          </div>
                        </div>
												<?php } ?>
						
                        <div class="line"></div>
                        
                        <div class="form-group row">
                          <div class="col-sm-4 offset-sm-3">
														<div id="errorverify" class="notificationerror" style="margin-bottom:25px"></div>
														<?php if ($_REQUEST['ams'] == "") { ?>
                            <input type="submit" class="btn btn-primary" name="simpan" value="Simpan">
														<?php } ?>
                            <input type="button" class="btn btn-secondary" name="batal" value="Batal" onclick="window.location='faktur-lihat'">
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
<script language="javascript">
var dataDetails = [];

function addDataDetails() {
	<?php
	if (($_REQUEST['nobarang'] != "") && ($_REQUEST['jenisukuran'] != "")) {
		$itemDetails = getItemDetails(array("item_no" => " = ".$_REQUEST['nobarang'], "item_unit_no" => " = ".$_REQUEST['jenisukuran']))->fetch(PDO::FETCH_ASSOC);
	?>
	if ($("#banyaknya").val() > <?php echo $itemDetails['stock'] ?>) {
		$("#notificationerror").html("Stok tidak cukup");
	} else {
		$("#notificationerror").html("");
		dataDetails.push([$("#nobarang").val(), $("#nobarang option:selected").text(), $("#jenisukuran").val(), '<?php echo $itemDetails['name'] ?>', '<?php echo $itemDetails['item_image'] ?>', $("#banyaknya").val(), <?php echo $itemDetails['price'] ?>, $("#diskon").val()]);
		$("#nobarang").val("");
		$("#jenisukuran").val("");
		$("#banyaknya").val("");
		$("#diskon").val("");
		printDataDetails();
	}
	<?php } ?>
}
function addExistingDataDetail(d1, d2, d3, d4, d5, d6, d7, d8) {
	dataDetails.push([d1, d2, d3, d4, d5, d6, d7, d8]);
	printDataDetails();
}
function deleteDataDetails(no) {
	dataDetails.splice(no, 1);
	printDataDetails();
}
function printDataDetails() {
	$("#tableDetailHarga").html("");
	dataDetails.forEach(function(item, index) {
		var hiddenstr = "";
		for (var i = 0; i < 8; i++) {
			hiddenstr += '<input type="hidden" name="itemdetails['+index+']['+i+']" value="'+item[i]+'">';
		}
		var priceAfterDiscount = item[6] - item[7];
		var totalPrice = priceAfterDiscount * item[5];
		$("#tableDetailHarga").append(`
		<tr>
			<td>`+(index+1)+` `+hiddenstr+`</td>
			<td>`+item[1]+`<br>`+item[3]+`</td>
			<td><img src="`+item[4]+`" height="80"></td>
			<td>`+item[5]+` lusin</td>
			<td align="right">Rp `+priceAfterDiscount.toLocaleString('id')+`<br>(Rp `+item[6].toLocaleString('id')+` - Rp `+item[7].toLocaleString('id')+`)</td>
			<td align="right">Rp `+totalPrice.toLocaleString('id')+`</td>
			<?php if ($_REQUEST['ams'] == "") { ?>
			<td>
				<a href="javascript:deleteDataDetails(`+index+`)"><img src="img/i-delete.png" /></a>
			</td>
			<?php } ?>
		</tr>
		`);
	});
}
document.onreadystatechange = () => {
	if (document.readyState === 'complete') {
<?php
if ($_REQUEST['itemdetails'] != "") {
	foreach ($_REQUEST['itemdetails'] as $itemdetail) {
?>
		addExistingDataDetail(<?php
		for ($i = 0; $i < 8; $i++) {
			if ($i > 0) echo ",";
			echo "'".$itemdetail[$i]."'";
		}
		?>);
<?php
	}
}
?>
	}
}
function verifyForm() {
	if (dataDetails.length == 0) {
		$("#errorverify").html("Tambahkan barang di dalam faktur ini..");
		return false;
	} else {
		return true;
	}
}
</script>
<?php
include_once("footer-main.php");
include_once("footer.php");
?>