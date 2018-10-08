<?php
$crudDefaultField = "event";
$crudDefaultFieldSort = "DESC";

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
              <h2 class="no-margin-bottom">Cetak - Multiple Invoice &amp; Surat Jalan</h2>
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
                      <h3 class="h4">Multiple Invoice &amp; Surat Jalan</h3>
                    </div>
                    <div class="card-body">
						<div class="container">
						
							<?php include_once("module/crud.php") ?>
							<form class="form-horizontal" id="FormCrud" method="post">
								<div class="form-group row">
									<input type="hidden" name="SortField" id="SortField" value="<?php echo $curSortField ?>">
									<input type="hidden" name="SortValue" id="SortValue" value="<?php echo $curSortValue ?>">
									<input type="text" class="form-control formauto selectheight" name="nonotadari" placeholder="No Nota Dari" value="<?php echo $_REQUEST['nonotadari'] ?>">
									&nbsp;- 
									<input type="text" class="form-control formauto selectheight" name="nonotasampai" placeholder="No Nota Sampai" value="<?php echo $_REQUEST['nonotasampai'] ?>">
									&nbsp;
									<input type="text" class="form-control formauto selectheight" name="namapelanggan" placeholder="Nama Pelanggan" value="<?php echo $_REQUEST['namapelanggan'] ?>">
									&nbsp;
									<input type="submit" class="btn btn-primary submitheight" name="cari" value="Cari">
								</div>
								
								
							</form>	
							
							<form id="FormCetakInvoice" method="post" action="print-multipleinvoice">
								<input type="hidden" name="ams" id="FormCetakInvoiceAms" value="">
								<input type="hidden" name="no" id="FormCetakInvoiceNo" value="">
								<input type="hidden" name="token" id="FormCetakInvoiceToken" value="">
							</form>
							<script language="javascript">
							function cetakInvoice(ams,token) {
								$("#FormCetakInvoiceAms").val(ams);
								$("#FormCetakInvoiceNo").val($('#printsuratjalan:checked').map(function() {return this.value;}).get().join(','));
								$("#FormCetakInvoiceToken").val(token);
								$("#FormCetakInvoice").submit();
							}
							</script>
							
							<form id="FormCetakDelivery" method="post" action="print-multiplesuratjalan">
								<input type="hidden" name="ams" id="FormCetakDeliveryAms" value="">
								<input type="hidden" name="no" id="FormCetakDeliveryNo" value="">
								<input type="hidden" name="token" id="FormCetakDeliveryToken" value="">
							</form>
							<script language="javascript">
							function cetakDelivery(ams,token) {
								$("#FormCetakDeliveryAms").val(ams);
								$("#FormCetakDeliveryNo").val($('#printsuratjalan:checked').map(function() {return this.value;}).get().join(','));
								$("#FormCetakDeliveryToken").val(token);
								$("#FormCetakDelivery").submit();
							}
							</script>
							
							<div class="table-responsive">          
								<table class="table">
									<thead>
										<tr>
											<th>#</th>
											<th>Tanggal<br>No Nota</th>
											<th>Nama Pelanggan</th>
											<th>Alamat</th>
											<th>Total</th>
											<th>Dibayar - Sisa</th>
											<th>Status</th>
											<th>Jatuh Tempo</th>
											<th class="tableactionwidth">
												Cetak:<br>
												<a href="javascript:cetakInvoice(1,'<?php echo generateToken("print-multipleinvoice-1") ?>')"><img src="img/i-print.png" alt="Print Invoice" /></a> 
												&nbsp;- 
												<a href="javascript:cetakDelivery(1,'<?php echo generateToken("print-multiplesuratjalan-1") ?>')"><img src="img/i-shipping.png" alt="Print Surat Jalan" /></a>
							
											</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$searchParams = array("c.name" => "LIKE '%".$_REQUEST['namapelanggan']."%'");
										if ($_REQUEST['nonotadari'] != "") $searchParams['s.no'] = " >= ".$_REQUEST['nonotadari'];
										if ($_REQUEST['nonotasampai'] != "") $searchParams['s.no'] = " <= ".$_REQUEST['nonotasampai'];
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
												<input type="checkbox" name="printsuratjalan" id="printsuratjalan" value="<?php echo $rowSales['no'] ?>" checked></span>
											</td>
										</tr>
										<?php } ?>										
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