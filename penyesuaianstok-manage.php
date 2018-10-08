<?php
include_once("module/item.php");

session_start();

/*
include_once("../include/library.php");
include_once("../include/conn.php");
include_once("library/admin_sessioncheck.php");
*/

// ACTION
if (isset($_REQUEST['simpan']) &&  $_REQUEST['simpan'] == "Simpan" && $tokenError == 0) 
{
	insertItemStock($_REQUEST['nobarang'], $_REQUEST['jenisukuran'], $_REQUEST['stok'], 1);
	$item = getItems(array("no" => " = ".$_REQUEST['nobarang']))->fetch(PDO::FETCH_ASSOC);
	insertLog(getLogCategoryNoByName('Tambah'), $_SESSION['userno'], getPageNoByName('Penyesuaian Stok'), $_REQUEST['nobarang']." - ".$_REQUEST['jenisukuran']." : ".$_REQUEST['stok']);
	
?>
	<form name='FormSuccess' method='post' action='stok-lihat'><input type="hidden" name="notificationerror" value="0"><input type='hidden' name='notification' value='Sukses menyesuaikan stok untuk <?php echo $item['code'] ?>'></form>
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
              <h2 class="no-margin-bottom">Penyesuaian Stok</h2>
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
                      <h3 class="h4">Penyesuaian Stok</h3>
                    </div>
					
                    <div class="card-body">
                      <form class="form-horizontal" name="FormAdd" id="FormAdd" method="post">
                        <div class="form-group row">
                          <label class="col-sm-3 form-control-label">Kode - Nama Barang</label>
                          <div class="col-sm-9">
                            <select name="nobarang" class="form-control selectpadding" onchange="document.FormAdd.submit()" required>
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
                          </div>
                        </div>
						
						<div class="form-group row">
                          <label class="col-sm-3 form-control-label">Ukuran &amp; Harga</label>
                          <div class="col-sm-9">
                            <select name="jenisukuran" class="form-control selectpadding" onchange="document.FormAdd.submit()" required>
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
                          </div>
                        </div>
						
						<div class="form-group row">
                          <label class="col-sm-3 form-control-label">Stok Saat Ini</label>
                          <div class="col-sm-9">
                            <?php
														if (($_REQUEST['nobarang'] != "") && ($_REQUEST['jenisukuran'] != "")) {
															echo getItemDetails(array("item_no" => " = ".$_REQUEST['nobarang'], "item_unit_no" => " = ".$_REQUEST['jenisukuran']))->fetch(PDO::FETCH_ASSOC)['stock']." lusin";
														} else {
															echo "-";
														}
														?>
                          </div>
                        </div>
						
						<div class="form-group row">
                          <label class="col-sm-3 form-control-label">Penyesuaian Stok<br><small class="text-primary">mohon dapat menggunakan tanda - (minus) jika ingin mengurangi stok</small></label>
                          <div class="col-sm-9">
                            <input type="number" class="form-control formauto displayinline" name="stok" required step="0.01"> lusin
                          </div>
                        </div>
						
                        <div class="line"></div>
                        
                        <div class="form-group row">
                          <div class="col-sm-4 offset-sm-3">
                            <input type="submit" class="btn btn-primary" name="simpan" value="Simpan">
                            <input type="button" class="btn btn-secondary" name="batal" value="Batal" onclick="window.location='stok-lihat'">
                          </div>
                        </div>
                      </form>
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