<?php
$crudDefaultField = "code";

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
              <h2 class="no-margin-bottom">Lihat Stok Saat Ini</h2>
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
                      <h3 class="h4">Stok Saat Ini</h3>
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
									<input type="submit" class="btn btn-primary submitheight" name="cari" value="Cari">
								</div>
							</form>	
							
							<div class="table-responsive">          
								<table class="table">
									<thead>
										<tr>
											<th>Kode &nbsp;&nbsp;<a href="javascript:sortCrud('code')"><i <?php getCrudIcon('code') ?>></i></a></th>
											<th>Nama Barang &nbsp;&nbsp;<a href="javascript:sortCrud('name')"><i <?php getCrudIcon('name') ?>></i></a></th>
											<th>Jenis Barang &nbsp;&nbsp;<a href="javascript:sortCrud('item_type_no')"><i <?php getCrudIcon('item_type_no') ?>></i></a></th>
											<th>Gambar</th>
											<th>Ukuran &amp; Harga</th>
											<th>Stok</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$searchParams = array("i.code" => "LIKE '%".$_REQUEST['kodebarang']."%'", "i.name" => "LIKE '%".$_REQUEST['namabarang']."%'");
										if ($_REQUEST['jenisbarang'] > 0) {
											$searchParams["i.item_type_no"] = " = ".$_REQUEST['jenisbarang'];
										}
										if ($_REQUEST['jenisukuran'] > 0) {
											$searchParams["item_unit_no"] = " = ".$_REQUEST['jenisukuran'];
										}
										$items = getItemDetails($searchParams, $curSortField, $curSortValue);
										$no = 0;
										while ($rowItem = $items->fetch(PDO::FETCH_ASSOC)) {
											$no++;
										?>
										<tr>
											<td><?php echo $rowItem['code'] ?></td>
											<td><?php echo $rowItem['item_name'] ?></td>
											<td><?php echo $rowItem['item_type_name'] ?></td>
											<td><img src="<?php echo $rowItem['image'] ?>" height="80"></td>
											<td><?php echo $rowItem['name'] ?> (Rp <?php echo number_format($rowItem['price'],0,",",".") ?>)</td>
											<td><?php echo $rowItem['stock'] ?> lusin</td>
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