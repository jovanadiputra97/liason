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
              <h2 class="no-margin-bottom">Lihat Histori Harga Barang</h2>
            </div>
          </header>
          
		  <section class="tables">   
            <div class="container-fluid">
              <div class="row">
                <div class="col-lg-12">
                  <div class="card">
                    <div class="card-header d-flex align-items-center">
                      <h3 class="h4">Histori Harga Barang</h3>
                    </div>
                    <div class="card-body">
						<div class="container">
						
							<?php include_once("module/crud.php") ?>
							<form class="form-horizontal" id="FormCrud" method="post">
								<div class="form-group row">
									<input type="hidden" name="SortField" id="SortField" value="<?php echo $curSortField ?>">
									<input type="hidden" name="SortValue" id="SortValue" value="<?php echo $curSortValue ?>">
									<input type="text" class="form-control formmedium selectheight" name="kodebarang" placeholder="Kode Barang" value="<?php echo $_REQUEST['kodebarang'] ?>">&nbsp;
									<input type="text" class="form-control formmedium selectheight" name="namabarang" placeholder="Nama Barang" value="<?php echo $_REQUEST['namabarang'] ?>">
									&nbsp;
									<select name="jenisbarang" class="form-control selectpadding formauto selectheight">
										<option value="">- Jenis Barang -</option>
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
									<select name="aksi" class="form-control selectpadding formauto selectheight">
										<option value="">- Aksi -</option>
										<option value="Tambah" <?php if ($_REQUEST['aksi'] == "Tambah") echo "selected" ?>>Tambah Data</option>
										<option value="Edit" <?php if ($_REQUEST['aksi'] == "Edit") echo "selected" ?>>Edit Data</option>
										<option value="Hapus" <?php if ($_REQUEST['aksi'] == "Hapus") echo "selected" ?>>Hapus Data</option>
									</select>
									&nbsp;
									<select name="diubaholeh" class="form-control selectpadding formauto selectheight">
										<option value="">- Diubah Oleh -</option>
										<?php
										$accounts = getAccounts();
										while ($rowAccount = $accounts->fetch(PDO::FETCH_ASSOC)) {
										?>
										<option value="<?php echo $rowAccount['no'] ?>" <?php if ($_REQUEST['diubaholeh'] == $rowAccount['no']) echo "selected" ?>><?php echo $rowAccount['username'] ?></option>
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
											<th>Tanggal &nbsp;&nbsp;<a href="javascript:sortCrud('event')"><i <?php getCrudIcon('event') ?>></i></a></th>
											<th>Kode</th>
											<th>Nama Barang</th>
											<th>Jenis Barang</th>
											<th>Gambar</th>
											<th>Ukuran &amp; Harga</th>
											<th>Aksi</th>
											<th>Diubah Oleh</th>
										</tr>
									</thead>
									<tbody>
										<?php
										/* $searchParams = array("i.code" => "LIKE '%".$_REQUEST['kodebarang']."%'", "i.name" => "LIKE '%".$_REQUEST['namabarang']."%'");
										if ($_REQUEST['jenisbarang'] > 0) {
											$searchParams["i.item_type_no"] = " = ".$_REQUEST['jenisbarang'];
										} */
										$searchParams = array();
										$itemhistories = getItemDetailsHistory($searchParams, $curSortField, $curSortValue);
										$no = 0;
										while ($rowItemHistory = $itemhistories->fetch(PDO::FETCH_ASSOC)) {
											$no++;
											$logItem = getItemWithTypes(array("i.no" => " = ".explode(",",$rowItemHistory['description'])[0]),"no","ASC",true)->fetch(PDO::FETCH_ASSOC);
											if (($_REQUEST['kodebarang'] != "") && (strpos($logItem['code'], $_REQUEST['kodebarang']) === false)) continue;
											if (($_REQUEST['jenisbarang'] != "") && ($logItem['item_type_no'] != $_REQUEST['jenisbarang'])) continue;
											if (($_REQUEST['diubaholeh'] != "") && ($rowItemHistory['account_no'] != $_REQUEST['diubaholeh'])) continue;
											$logAccount = getAccounts(array("no" => " = ".$rowItemHistory['account_no']))->fetch(PDO::FETCH_ASSOC);
											preg_match_all("/,\s([0-9]*)-([0-9]*)\s=\s([A-Za-z]+)/i", $rowItemHistory['description'],$logs,PREG_SET_ORDER);
											foreach ($logs as $log) {
												if ($log[3] == "Existing") continue;
												if (($_REQUEST['aksi'] != "") && ($log[3] != $_REQUEST['aksi'])) continue;
												$logItemUnit = getItemUnits(array("no" => " = ".$log[1]))->fetch(PDO::FETCH_ASSOC);
										?>
										<tr>
											<td><?php echo $rowItemHistory['event'] ?></td>
											<td><?php echo $logItem['code'] ?></td>
											<td><?php echo $logItem['name'] ?></td>
											<td><?php echo $logItem['item_type_name'] ?></td>
											<td><img src="<?php echo $logItem['image'] ?>" height="80"></td>
											<td><?php echo $logItemUnit['name'] ?> (Rp <?php echo number_format($log[2],0,",",".") ?>)</td>
											<td><?php echo $log[3] ?> Data</td>
											<td><?php echo $logAccount['username'] ?></td>
										</tr>
										<?php
											}
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