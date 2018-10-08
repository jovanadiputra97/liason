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
		
		<?php include_once("module/item.php") ?>
        <div class="content-inner">
          <!-- Page Header-->
          <header class="page-header">
            <div class="container-fluid">
              <h2 class="no-margin-bottom">Lihat Histori Stok</h2>
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
                      <h3 class="h4">Histori Stok</h3>
                    </div>
                    <div class="card-body">
						<div class="container">
						
							<?php include_once("module/crud.php") ?>
							<form class="form-horizontal" id="FormCrud" method="post">
								<div class="form-group row">
									<input type="hidden" name="SortField" id="SortField" value="<?php echo $curSortField ?>">
									<input type="hidden" name="SortValue" id="SortValue" value="<?php echo $curSortValue ?>">
									<input type="text" class="form-control formauto selectheight" name="kodebarang" placeholder="Kode Barang" value="<?php echo $_REQUEST['kodebarang'] ?>">
									&nbsp;
									<input type="text" class="form-control formauto selectheight" name="namabarang" placeholder="Nama Barang" value="<?php echo $_REQUEST['namabarang'] ?>">
									&nbsp;
									<select name="jenisbarang" class="form-control selectpadding formauto selectheight">
										<option value="0">- Jenis Barang -</option>
										<?php
										$itemTypes = getItemTypes();
										while ($rowItemType = $itemTypes->fetch(PDO::FETCH_ASSOC)) {
										?>
										<option value="<?php echo $rowItemType['no'] ?>" <?php if ($_REQUEST['jenisbarang'] == $rowItemType['no']) echo "selected" ?>><?php echo $rowItemType['name'] ?></option>
										<?php
										}
										?>
									</select>
									&nbsp;
									<select name="jenisukuran" class="form-control selectpadding formauto selectheight">
										<option value="0">- Jenis Ukuran -</option>
										<?php
										$itemUnits = getItemUnits();
										while ($rowItemUnit = $itemUnits->fetch(PDO::FETCH_ASSOC)) {
										?>
										<option value="<?php echo $rowItemUnit['no'] ?>" <?php if ($_REQUEST['jenisukuran'] == $rowItemUnit['no']) echo "selected" ?>><?php echo $rowItemUnit['name'] ?></option>
										<?php
										}
										?>
									</select>
									&nbsp;
									<select name="Aksi" class="form-control selectpadding formauto selectheight">
										<option value="">- Aksi -</option>
										<option value="Tambah" <?php if ($_REQUEST['Aksi'] == "Tambah") echo "selected" ?>>Tambah Stok</option>
										<option value="Penyesuaian" <?php if ($_REQUEST['Aksi'] == "Penyesuaian") echo "selected" ?>>Penyesuaian Stok</option>
										<option value="Penjualan" <?php if ($_REQUEST['Aksi'] == "Penjualan") echo "selected" ?>>Penjualan</option>
									</select>
									&nbsp;
									<select name="diubaholeh" class="form-control selectpadding formauto selectheight">
										<option value="0">- Diubah Oleh -</option>
										<?php
										$accounts = getAccounts();
										while ($rowAccount = $accounts->fetch(PDO::FETCH_ASSOC)) {
										?>
										<option value="<?php echo $rowAccount['no'] ?>" <?php if ($_REQUEST['diubaholeh'] == $rowAccount['no']) echo "selected" ?>><?php echo $rowAccount['username'] ?></option>
										<?php } ?>
									</select>
									&nbsp;
									<input type="submit" class="btn btn-primary submitheight" name="cari" value="Cari">
								</div>
							</form>	
							
							<div class="table-responsive">          
								<table class="table">
									<thead>
										<tr>
											<th>Tanggal &nbsp;&nbsp;<a href="javascript:sortCrud('event')"><i <?php getCrudIcon('event') ?>></i></a></th>
											<th>Kode &nbsp;&nbsp;<a href="javascript:sortCrud('i.code')"><i <?php getCrudIcon('i.code') ?>></i></a></th>
											<th>Nama Barang &nbsp;&nbsp;<a href="javascript:sortCrud('item_name')"><i <?php getCrudIcon('item_name') ?>></i></a></th>
											<th>Jenis Barang &nbsp;&nbsp;<a href="javascript:sortCrud('item_type_name')"><i <?php getCrudIcon('item_type_name') ?>></i></a></th>
											<th>Gambar</th>
											<th>Ukuran &amp; Harga</th>
											<th>Aksi</th>
											<th>Stok Akhir</th>
											<th>Diubah Oleh</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$searchParams = array("i.code" => "LIKE '%".$_REQUEST['kodebarang']."%'", "i.name" => "LIKE '%".$_REQUEST['namabarang']."%'");
										if ($_REQUEST['jenisbarang'] > 0) {
											$searchParams["i.item_type_no"] = " = ".$_REQUEST['jenisbarang'];
										}
										if ($_REQUEST['jenisukuran'] > 0) {
											$searchParams["iu.no"] = " = ".$_REQUEST['jenisukuran'];
										}
										if ($_REQUEST['diubaholeh'] > 0) {
											$searchParams["a.no"] = " = ".$_REQUEST['diubaholeh'];
										}
										$itemDetailStocks = getItemDetailStocks($searchParams, $curSortField, $curSortValue);
										$no = 0;
										while ($rowItemDetailStock = $itemDetailStocks->fetch(PDO::FETCH_ASSOC)) {
											$no++;
											$adjType = "Tambah";
											if ($rowItemDetailStock['adjustment'] == 1) $adjType = "Penyesuaian";
											elseif ($rowItemDetailStock['sales'] == 1) $adjType = "Penjualan";
											if (($_REQUEST['Aksi'] != "") && ($_REQUEST['Aksi'] != $adjType)) continue;
										?>
										<tr>
											<td><?php echo date('d-m-Y', strtotime($rowItemDetailStock['event'])) ?></td>
											<td><?php echo $rowItemDetailStock['code'] ?></td>
											<td><?php echo $rowItemDetailStock['item_name'] ?></td>
											<td><?php echo $rowItemDetailStock['item_type_name'] ?></td>
											<td><img src="<?php echo $rowItemDetailStock['item_image'] ?>" height="80"></td>
											<td><?php echo $rowItemDetailStock['name'] ?> (Rp <?php echo number_format($rowItemDetailStock['price'],0,",",".") ?>)</td>
											<td><?php echo $adjType." ".$rowItemDetailStock['stock'] ?></td>
											<td><?php echo $rowItemDetailStock['stock_last'] ?> lusin</td>
											<td><?php echo $rowItemDetailStock['username'] ?></td>
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