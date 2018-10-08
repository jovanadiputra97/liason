<?php
$checkSession = true;
include_once("module/init.php");
include_once("module/sales.php");
include_once("module/payment.php");

$tokenErrorRedirect = "faktur-lihat";
$_REQUEST['alias'] = "pembayaran-manage";
$tokenError = checkTokenError();

session_start();

/*
include_once("../include/library.php");
include_once("../include/conn.php");
include_once("library/admin_sessioncheck.php");
*/

$sales = getSales(array("s.no" => " = ".$_REQUEST['no']))->fetch(PDO::FETCH_ASSOC);
$saleDetails = getSaleDetails(array("sd.sales_no" => " = ".$_REQUEST['no']));
$paymentTypes = getPaymentTypes();

// ACTION
if (isset($_REQUEST['simpan']) &&  $_REQUEST['simpan'] == "Simpan" && $tokenError == 0) 
{
	$curOutstanding = $sales['outstanding'];
	foreach($_REQUEST['itemdetails'] as $itemDetail) {
		if ($itemDetail['newdata']) {
			insertPayment($_REQUEST['no'],date('Y-m-d'),$itemDetail['pembayaran'],$itemDetail['dibayar']);
			$curOutstanding -= $itemDetail['dibayar'];
		}
	}
	updateSalesPayment($_REQUEST['no'], $curOutstanding, $_REQUEST['catatan']);
	insertLog(getLogCategoryNoByName('Tambah'), $_SESSION['userno'], getPageNoByName('Lihat Faktur & Pembayaran'), $salesno.";".$_REQUEST['nopelanggan'].";".$totalSales.";".$_REQUEST['tanggaljatuhtempo'].";".$_REQUEST['catatan']);
?>
	<form name='FormSuccess' method='post' action='faktur-lihat'><input type="hidden" name="notificationerror" value="0"><input type='hidden' name='notification' value='Sukses merubah pembayaran untuk faktur <?php echo sprintf("%07d", $_REQUEST['no']) ?>'></form>
	<script language="javascript">
	document.FormSuccess.submit();
	</script>
<?php
}

include_once("header.php");
include_once("header-main.php"); 
?>
        <?php
		include_once("navbar.php"); 
		?>
        <div class="content-inner">
          <!-- Page Header-->
          <header class="page-header">
            <div class="container-fluid">
              <h2 class="no-margin-bottom">Pembayaran Faktur</h2>
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
                      <h3 class="h4">Pembayaran</h3>
                    </div>
					
                    <div class="card-body">
                      <form class="form-horizontal" method="post">
											
												<input type="hidden" name="ams" value="<?php echo $_REQUEST['ams'] ?>">
												<input type="hidden" name="no" value="<?php echo $_REQUEST['no'] ?>">
												<input type="hidden" name="token" value="<?php echo $_REQUEST['token'] ?>">
											
                        <div class="form-group row">
                          <label class="col-sm-3 form-control-label">Tanggal</label>
                          <div class="col-sm-9">
                            <b><?php echo date('d-m-Y', strtotime($sales['event'])) ?></b>
                          </div>
                        </div>
						
						<div class="form-group row">
                          <label class="col-sm-3 form-control-label">No Nota</label>
                          <div class="col-sm-9">
                            <b><?php echo sprintf("%07d",$_REQUEST['no']) ?></b>
                          </div>
                        </div>
						
						<div class="form-group row">
                          <label class="col-sm-3 form-control-label">Pelanggan</label>
                          <div class="col-sm-9">
														<b><?php echo $sales['customer_name'] ?></b><br>
														<?php echo $sales['customer_address'] ?><br>
														Telepon: <?php echo $sales['customer_phone'] ?><br>
														Email: <?php echo $sales['customer_email'] ?>
                          </div>
                        </div>
						
						<div class="form-group row">
                          <label class="col-sm-3 form-control-label">Barang &amp; Total Harga</label>
						</div>
						
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
										</tr>
									</thead>
									<tbody>
										<?php
										$detailNo = 0;
										while ($rowSaleDetail = $saleDetails->fetch(PDO::FETCH_ASSOC)) {
											$detailNo++;
										?>
										<tr>
											<td><?php echo $detailNo ?></td>
											<td><?php echo $rowSaleDetail['item_code'] ?> - <?php echo $rowSaleDetail['item_name'] ?><br><?php echo $rowSaleDetail['item_unit_name'] ?></td>
											<td><img src="<?php echo $rowSaleDetail['item_image'] ?>" height="80"></td>
											<td><?php echo $rowSaleDetail['qty'] ?> lusin</td>
											<td align="right">Rp <?php echo number_format($rowSaleDetail['price']-$rowSaleDetail['discount'],0,",",".") ?><br>(Rp <?php echo number_format($rowSaleDetail['price'],0,",",".") ?> - Rp <?php echo number_format($rowSaleDetail['discount'],0,",",".") ?>)</td>
											<td align="right">Rp <?php echo number_format($rowSaleDetail['subtotal'],0,",",".") ?></td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
                          </div>
                        </div>
						
						<div class="form-group row">
                          <label class="col-sm-3 form-control-label">Pembayaran</label>
                          <div class="col-sm-9">
							<b>Sudah Dibayar: Rp <?php echo number_format($sales['total']-$sales['outstanding'],0,",",".") ?></b>
							<div class="red"><b>Sisa Pembayaran: Rp <?php echo number_format($sales['outstanding'],0,",",".") ?></b></div><br>
							<select name="jenispembayaran" id="jenispembayaran" class="form-control selectpadding formauto displayinline">
								<option value="">- Jenis Pembayaran -</option>
								<?php while ($rowPayment = $paymentTypes->fetch(PDO::FETCH_ASSOC)) { ?>
                <option value="<?php echo $rowPayment['no'] ?>"><?php echo $rowPayment['name'] ?></option>
								<?php } ?>
                            </select>
							&nbsp;
							, Dibayar Rp.<input type="number" class="form-control formauto displayinline" name="dibayar" id="dibayar" min="0" step="1">
							
							&nbsp;
							<input type="button" class="btn btn-primary displayinline" name="tambah" value="Tambah" onclick="javascript:addDataDetails()">
						  
						
							<div class="table-responsive">          
								<table class="table">
									<thead>
										<tr>
											<th>#</th>
											<th>Tanggal</th>
											<th>Jenis Pembayaran</th>
											<th>Dibayar</th>
											<th class="tableactionwidth"></th>
										</tr>
									</thead>
									<tbody id="tableDetailHarga">
									</tbody>
								</table>
							</div>
                          </div>
                        </div>
						
						<div class="form-group row">
                          <label class="col-sm-3 form-control-label">Catatan</label>
                          <div class="col-sm-9">
                            <textarea class="form-control" name="catatan"><?php echo $sales['payment_notes'] ?></textarea>
                          </div>
                        </div>
						
                        <div class="line"></div>
                        
                        <div class="form-group row">
                          <div class="col-sm-4 offset-sm-3">
                            <input type="submit" class="btn btn-primary" name="simpan" value="Simpan">
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
	var curDate = new Date();
	var dateString = curDate.getDate()+"-"+(curDate.getMonth()+1)+"-"+curDate.getFullYear();
	dataDetails.push([dateString, $("#jenispembayaran").val(), $("#jenispembayaran option:selected").text(), $("#dibayar").val(), 1]);
	$("#jenispembayaran").val("");
	$("#dibayar").val("");
	printDataDetails();
}
function addExistingDataDetail(tanggal, noPembayaran, namaPembayaran, dibayar, dataBaru) {
	dataDetails.push([tanggal, noPembayaran, namaPembayaran, dibayar, dataBaru]);
	printDataDetails();
}
function deleteDataDetails(no) {
	dataDetails.splice(no, 1);
	printDataDetails();
}
function printDataDetails() {
	$("#tableDetailHarga").html("");
	dataDetails.forEach(function(item, index) {
		$("#tableDetailHarga").append(`
		<tr>
			<td>`+(index+1)+`</td>
			<td>`+item[0]+`</td>
			<td>`+item[2]+`</td>
			<td><input type="hidden" name="itemdetails[`+index+`][pembayaran]" value="`+item[1]+`"><input type="hidden" name="itemdetails[`+index+`][dibayar]" value="`+item[3]+`"><input type="hidden" name="itemdetails[`+index+`][newdata]" value="`+item[4]+`">Rp. `+item[3].toLocaleString('id')+`,-</td>
			<td>
				<a href="javascript:deleteDataDetails(`+index+`)"><img src="img/i-delete.png" /></a>
			</td>
		</tr>
		`);
	});
}
document.onreadystatechange = () => {
	if (document.readyState === 'complete') {
<?php
if ($_REQUEST['ams'] == 1) {
	$payments = getPayments(array("sales_no" => " = ".$_REQUEST['no']));
	while ($rowPayment = $payments->fetch(PDO::FETCH_ASSOC)) {
?>
		addExistingDataDetail('<?php echo date('j-n-Y', strtotime($rowPayment['event'])) ?>',<?php echo $rowPayment['payment_type_no'] ?>,'<?php echo $rowPayment['payment_type_name'] ?>',<?php echo $rowPayment['total'] ?>,0);
<?php
	}
}
?>
	}
}
</script>
					
<?php
include_once("footer-main.php");
include_once("footer.php");
?>