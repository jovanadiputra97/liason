<?php
setlocale(LC_TIME, 'en_ID');
?>
<tr valign="top">
		<td>
				<img src="img/<?php echo $_REQUEST['brand'] ?>.jpg" width="135" align="left" class="printlogopadding">
				<h3>TOKO <?php echo strtoupper($_REQUEST['brand']) ?></h3>
				<h6>Menjual rupa<sup>2</sup> kaos anak<sup>2</sup></h6>
				<div class="printtokoalamat">
					- Pasar Jatinegara Lantai I BKS No, 225 - 226<br>
						&nbsp;&nbsp;&nbsp;Jakarta Timur Telp.: (021) 8510413<br>
						- Pasar Tanah Abang Blok A<br>
						&nbsp;&nbsp;&nbsp;Lantai Ground Los D No. 55<br>
						&nbsp;&nbsp;&nbsp;Jakarta Pusat Telp.: (021) 23571761
				</div>
		</td>
		<td align="right">
			<table>
					<tr>
							<td>
									Jakarta, <?php echo date('d-M-Y') ?><br>&nbsp;
								</td>
						</tr>
						<tr>
							<td>
									<?php if ($sales['customer_name'] != "") { ?>
									Kepada Bapak/Ibu <?php echo $sales['customer_name'] ?><br>
									<?php echo $sales['customer_address'] ?>
									<?php } ?>
								</td>
						</tr>
				</table>
		</td>
</tr>