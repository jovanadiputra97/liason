<?php
$checkSession = true;
include_once("module/init.php");
$tokenErrorRedirect = "faktur-lihat";
$_REQUEST['alias'] = "print-invoice";
//$tokenError = checkTokenError();

include_once("module/sales.php");

include_once("header.php");
?>
		<div class="printcontainer">
            <table width="100%">
                <?php
								include_once("printer-header.php");
								?>
                <tr>
                	<td colspan="2">
                    	<table border="1" cellspacing="0" cellpadding="5" width="100%">
                        	<tr>
                            	<td width="50">No</td>
															<td width="100">No Nota</td>
															<td width="100">Penerima</td>
                              <td>Nama Barang</td>
                              <td width="100">Banyaknya</td>
                              <td width="150">Harga</td>
                              <td width="150">Jumlah</td>
                            </tr>
														<?php
														$detailNo = 0;
														$nos = explode(",",$_REQUEST['no']);
														$total = 0;
														foreach ($nos as $no) {
															$sales = getSales(array("s.no" => ' = '.$no))->fetch(PDO::FETCH_ASSOC);
															$saleDetails = getSaleDetails(array("sd.sales_no" => " = ".$no));
															while ($rowSaleDetail = $saleDetails->fetch(PDO::FETCH_ASSOC)) {
																$detailNo++;
																$total += $rowSaleDetail['subtotal'];
														?>
                            <tr>
                            	<td><?php echo $detailNo ?></td>
															<td><?php echo sprintf("%07d", $sales['no']) ?></td>
															<td><?php echo $sales['customer_name'] ?></td>
                              <td><?php echo $rowSaleDetail['item_code'] ?> - <?php echo $rowSaleDetail['item_name'] ?>, <?php echo $rowSaleDetail['item_unit_name'] ?></td>
                              <td><?php echo $rowSaleDetail['qty'] ?> lusin</td>
                              <td align="right">Rp <?php echo number_format($rowSaleDetail['price']-$rowSaleDetail['discount'],0,",",".") ?></td>
                              <td align="right">Rp <?php echo number_format($rowSaleDetail['subtotal'],0,",",".") ?></td>
                            </tr>
														<?php
															}
														}
														?>
														<tr>
                            	<td colspan="5"></td>
                              <td align="right">Rp <?php echo number_format($total,0,",",".") ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
		</div>
	</body>
</html>