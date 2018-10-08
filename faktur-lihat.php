<?php
$crudDefaultField = "event";
$crudDefaultFieldSort = "DESC";
$crudEditAction = "faktur-manage";
$crudDeleteAction = "faktur-manage";
$crudPageEditable = "+ Buat Faktur";

session_start();

/*
include_once("../include/library.php");
include_once("../include/conn.php");
include_once("library/admin_sessioncheck.php");
*/


include_once("header.php");
include_once("header-main.php"); 
?>
        <?php
		include_once("navbar.php"); 
		?>
		
		<?php
		include_once("module/sales.php");
		include_once("module/customer.php");
		?>
        <div class="content-inner">
          <!-- Page Header-->
          <header class="page-header">
            <div class="container-fluid">
              <h2 class="no-margin-bottom">Lihat Faktur</h2>
            </div>
          </header>
          
		  <section class="tables">   
            <div class="container-fluid">
              <div class="row">
                <div class="col-lg-12">
                  <div class="card">
										<?php if ($_REQUEST['notification'] != "") { ?>
										<div class="card-header d-flex align-items-center <?php echo ($_REQUEST['notificationerror'] == 1 ? "notificationerror" : "notificationtext") ?>">
                      <h5><?php echo $_REQUEST['notification'] ?></h5>
                    </div>
										<?php } ?>
                    <div class="card-header d-flex align-items-center">
                      <h3 class="h4">Faktur</h3>
                    </div>
                    <div class="card-body">
						<div class="container">
						
							<?php include_once("module/crud.php") ?>
							<form class="form-horizontal" id="FormCrud" method="post">
								<div class="form-group row">
									<input type="hidden" name="SortField" id="SortField" value="<?php echo $curSortField ?>">
									<input type="hidden" name="SortValue" id="SortValue" value="<?php echo $curSortValue ?>">
									<input type="text" class="form-control formauto selectheight" name="nonota" placeholder="No Nota" value="<?php echo $_REQUEST['nonota'] ?>">
									&nbsp;
									<input type="text" class="form-control formauto selectheight" name="namapelanggan" placeholder="Nama Pelanggan" value="<?php echo $_REQUEST['namapelanggan'] ?>">
									&nbsp;
									<select name="statusfaktur" class="form-control selectpadding formauto selectheight">
										<option value="">- Status Faktur -</option>
										<option value="Belum Dibayar" <?php if ($_REQUEST['statusfaktur'] == "Belum Dibayar") echo "selected" ?>>Belum Dibayar</option>
										<option value="Sebagian Dibayar" <?php if ($_REQUEST['statusfaktur'] == "Sebagian Dibayar") echo "selected" ?>>Sebagian Dibayar</option>
										<option value="LUNAS" <?php if ($_REQUEST['statusfaktur'] == "LUNAS") echo "selected" ?>>LUNAS</option>
									</select>
								</div>
								
								<div class="form-group row">
									Tanggal Faktur : &nbsp;
									<input type="date" class="form-control" name="fakturdari" value="<?php echo $_REQUEST['fakturdari'] ?>" style="width: 200px">
									&nbsp;-&nbsp;
									<input type="date" class="form-control" name="faktursampai" value="<?php echo $_REQUEST['faktursampai'] ?>" style="width: 200px">
								</div>
								
								<div class="form-group row">
									Jatuh Tempo : &nbsp;
									<input type="date" class="form-control" name="jatuhtempodari" value="<?php echo $_REQUEST['jatuhtempodari'] ?>" style="width: 200px">
									&nbsp;-&nbsp;
									<input type="date" class="form-control" name="jatuhtemposampai" value="<?php echo $_REQUEST['jatuhtemposampai'] ?>" style="width: 200px">
									&nbsp;
									<input type="submit" class="btn btn-primary submitheight" name="cari" value="Cari">
								</div>
							</form>
							
							<form id="FormPembayaran" method="post" action="pembayaran-manage">
								<input type="hidden" name="ams" id="FormPembayaranAms" value="">
								<input type="hidden" name="no" id="FormPembayaranNo" value="">
								<input type="hidden" name="token" id="FormPembayaranToken" value="">
							</form>
							<script language="javascript">
							function editPembayaran(ams,no,token) {
								$("#FormPembayaranAms").val(ams);
								$("#FormPembayaranNo").val(no);
								$("#FormPembayaranToken").val(token);
								$("#FormPembayaran").submit();
							}
							</script>
							
							<form id="FormCetakInvoice" method="post" action="print-invoice">
								<input type="hidden" name="ams" id="FormCetakInvoiceAms" value="">
								<input type="hidden" name="no" id="FormCetakInvoiceNo" value="">
								<input type="hidden" name="token" id="FormCetakInvoiceToken" value="">
							</form>
							<script language="javascript">
							function cetakInvoice(ams,no,token) {
								$("#FormCetakInvoiceAms").val(ams);
								$("#FormCetakInvoiceNo").val(no);
								$("#FormCetakInvoiceToken").val(token);
								$("#FormCetakInvoice").submit();
							}
							</script>
							
							<form id="FormCetakDelivery" method="post" action="print-suratjalan">
								<input type="hidden" name="ams" id="FormCetakDeliveryAms" value="">
								<input type="hidden" name="no" id="FormCetakDeliveryNo" value="">
								<input type="hidden" name="token" id="FormCetakDeliveryToken" value="">
							</form>
							<script language="javascript">
							function cetakDelivery(ams,no,token) {
								$("#FormCetakDeliveryAms").val(ams);
								$("#FormCetakDeliveryNo").val(no);
								$("#FormCetakDeliveryToken").val(token);
								$("#FormCetakDelivery").submit();
							}
							</script>
							
							<div class="table-responsive">          
								<table class="table">
									<thead>
										<tr>
											<th>#</th>
											<th>Tanggal &nbsp;&nbsp;<a href="javascript:sortCrud('event')"><i <?php getCrudIcon('event') ?>></i></a><br>No Nota</th>
											<th>Nama Pelanggan &nbsp;&nbsp;<a href="javascript:sortCrud('c.name')"><i <?php getCrudIcon('c.name') ?>></i></a></th>
											<th>Alamat &nbsp;&nbsp;<a href="javascript:sortCrud('c.address')"><i <?php getCrudIcon('c.address') ?>></i></a></th>
											<th>Total &nbsp;&nbsp;<a href="javascript:sortCrud('total')"><i <?php getCrudIcon('total') ?>></i></a></th>
											<th>Dibayar - Sisa</th>
											<th>Status</th>
											<th>Jatuh Tempo &nbsp;&nbsp;<a href="javascript:sortCrud('deadline')"><i <?php getCrudIcon('deadline') ?>></i></a></th>
											<th class="tableactionwidth"></th>
										</tr>
									</thead>
									<tbody>
										<?php
										$searchParams = array("c.name" => "LIKE '%".$_REQUEST['namapelanggan']."%'");
										if ($_REQUEST['nonota'] != "") $searchParams["no"] = " = ".$_REQUEST['nonota'];
										if ($_REQUEST['fakturdari'] != "") $searchParams["event"] = " >= '".$_REQUEST['fakturdari']."'";
										if ($_REQUEST['faktursampai'] != "") $searchParams["event2"] = " <= '".$_REQUEST['faktursampai']."'";
										if ($_REQUEST['jatuhtempodari'] != "") $searchParams["deadline"] = " >= '".$_REQUEST['jatuhtempodari']."'";
										if ($_REQUEST['jatuhtemposampai'] != "") $searchParams["deadline2"] = " <= '".$_REQUEST['jatuhtemposampai']."'";
										$sales = getSales($searchParams, $curSortField, $curSortValue);
										$no = 0;
										while ($rowSales = $sales->fetch(PDO::FETCH_ASSOC)) {
											$no++;
											$statusFaktur = "";
											if ($rowSales['total'] == $rowSales['outstanding']) {
												$statusFaktur =  "Belum Dibayar";
											} elseif ($rowSales['outstanding'] <= 0) {
												$statusFaktur =  "LUNAS";
											} else {
												$statusFaktur =  "Sebagian Dibayar";
											}
											if (($_REQUEST['statusfaktur'] != "") && ($statusFaktur != $_REQUEST['statusfaktur'])) continue;
										?>
										<tr>
											<td><?php echo $no ?></td>
											<td><?php echo date('d-m-Y', strtotime($rowSales['event'])) ?><br><?php echo sprintf("%07d", $rowSales['no']) ?></td>
											<td><?php echo $rowSales['customer_name'] ?></td>
											<td><?php echo $rowSales['customer_address'] ?></td>
											<td align="right">Rp <?php echo number_format($rowSales['total'],0,",",".") ?></td>
											<td align="right">Rp <?php echo number_format($rowSales['total']-$rowSales['outstanding'],0,",",".") ?><div class="red">Rp <?php echo number_format($rowSales['outstanding'],0,",",".") ?></div></td>
											<td class="orange"><?php echo $statusFaktur ?></td>
											<td><?php echo date('d-m-Y', strtotime($rowSales['deadline'])) ?></td>
											<td>
												<a href="javascript:editPembayaran(1,<?php echo $rowSales['no'] ?>,'<?php echo generateToken("pembayaran-manage-1-".$rowSales['no']) ?>')"><img src="img/i-pay.png" /></a>
												<a href="javascript:editData(1,<?php echo $rowSales['no'] ?>,'<?php echo generateToken($crudEditAction."-1-".$rowSales['no']) ?>')"><img src="img/i-view.png" /></a>
												<div class="actiongap"></div>
												<a href="javascript:cetakInvoice(1,<?php echo $rowSales['no'] ?>,'<?php echo generateToken("print-invoice-1-".$rowSales['no']) ?>')"><img src="img/i-print.png" alt="Print Invoice" /></a>
												<a href="javascript:cetakDelivery(1,<?php echo $rowSales['no'] ?>,'<?php echo generateToken("print-suratjalan-1-".$rowSales['no']) ?>')"><img src="img/i-shipping.png" alt="Print Surat Jalan" /></a>
											</td>
										</tr>
										<?php
										}
										?>
									</tbody>
								</table>
							</div>
						</div>
                    </div>
                  </div>
                </div>
                
                
              </div>
            </div>
          </section>

<?php
include_once("footer-main.php");
include_once("footer.php");
?>