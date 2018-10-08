<?php
$checkSession = true;
include_once("module/init.php");
$tokenErrorRedirect = "faktur-lihat";
$_REQUEST['alias'] = "print-invoice";
$tokenError = checkTokenError();

include_once("module/sales.php");
$sales = getSales(array("s.no" => ' = '.$_REQUEST['no']))->fetch(PDO::FETCH_ASSOC);
$saleDetails = getSaleDetails(array("sd.sales_no" => " = ".$_REQUEST['no']));

include_once("header.php");
?>
		<div class="printcontainer">
            <table width="100%">
                <?php
								include_once("printer-header.php");
								?>
                <tr>
                	<td colspan="2">
                    	&nbsp;<br>
                        No. Nota <?php echo sprintf("%07d",$sales['no']) ?><br>&nbsp;
                    </td>
                </tr>
                <tr>
                	<td colspan="2">
                    	<table border="1" cellspacing="0" cellpadding="5" width="100%">
                        	<tr>
                            	<td width="50">No</td>
                              <td>Nama Barang</td>
                              <td width="100">Banyaknya</td>
                              <td width="150">Harga</td>
                              <td width="150">Jumlah</td>
                            </tr>
														<?php
														$detailNo = 0;
														while ($rowSaleDetail = $saleDetails->fetch(PDO::FETCH_ASSOC)) {
															$detailNo++;
														?>
                            <tr>
                            	<td><?php echo $detailNo ?></td>
                              <td><?php echo $rowSaleDetail['item_code'] ?> - <?php echo $rowSaleDetail['item_name'] ?>, <?php echo $rowSaleDetail['item_unit_name'] ?></td>
                              <td><?php echo $rowSaleDetail['qty'] ?> lusin</td>
                              <td align="right">Rp <?php echo number_format($rowSaleDetail['price']-$rowSaleDetail['discount'],0,",",".") ?></td>
                              <td align="right">Rp <?php echo number_format($rowSaleDetail['subtotal'],0,",",".") ?></td>
                            </tr>
														<?php
														}
														?>
														<tr>
                            	<td colspan="4"></td>
                              <td align="right">Rp <?php echo number_format($sales['total'],0,",",".") ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
		</div>
	</body>
</html>