<?php

include_once("module/item.php");

$tokenErrorRedirect = "jenisbarang-lihat";
$tokenError = checkTokenError();

session_start();

// ACTION
if ($_REQUEST['ams'] == 2) {
	
	$valid = 1;
	$item_types = getItemTypes(array("no" => ' = '.$_REQUEST['no']))->fetch(PDO::FETCH_ASSOC);
	if (getItems(array("item_type_no" => " = ".$_REQUEST['no']))->rowCount() > 0) $valid = 0;
	
	if ($valid) {
		
		deleteItemType($_REQUEST['no']);
		insertLog(getLogCategoryNoByName('Hapus'), $_SESSION['userno'], getPageNoByName('+ Jenis Barang'), $_REQUEST['no']." : ".$item_types['name']);
?>
	<form name='FormSuccess' method='post' action='jenisbarang-lihat'><input type="hidden" name="notificationerror" value="0"><input type='hidden' name='notification' value='Sukses menghapus jenis barang <?php echo $item_types['name'] ?>'></form>
	<script language="javascript">
	document.FormSuccess.submit();
	</script>
<?php
	} else {
?>
	<form name='FormError' method='post' action='jenisbarang-lihat'><input type="hidden" name="notificationerror" value="1"><input type='hidden' name='notification' value='Gagal menghapus jenis barang <?php echo $item_types['name'] ?> karena digunakan barang'></form>
	<script language="javascript">
	document.FormError.submit();
	</script>
<?php
	}
}
if (isset($_REQUEST['simpan']) &&  $_REQUEST['simpan'] == "Simpan" && $tokenError == 0) 
{
	$valid = 1;
	if ($_REQUEST['ams'] == 1) {
		
		$valid = updateItemType($_REQUEST['no'], $_REQUEST['jenisbarang']);
		if ($valid) {
			insertLog(getLogCategoryNoByName('Edit'), $_SESSION['userno'], getPageNoByName('+ Jenis Barang'), $_REQUEST['no']." : ".$_REQUEST['jenisbarang']);
		} else {
			$_REQUEST['notificationerror'] = 1;
			$_REQUEST['notification'] = "Jenis barang ".$_REQUEST['jenisbarang']." sudah ada. Masukkan yang lain";
		}
		
	} else {
		
		$valid = insertItemType($_REQUEST['jenisbarang']);
		if ($valid) {
			$itemType = getItemTypes(array("name" => " LIKE '".$_REQUEST['jenisbarang']."'"))->fetch(PDO::FETCH_ASSOC);
			insertLog(getLogCategoryNoByName('Tambah'), $_SESSION['userno'], getPageNoByName('+ Jenis Barang'), $itemType['no']." : ".$_REQUEST['jenisbarang']);
		} else {
			$_REQUEST['notificationerror'] = 1;
			$_REQUEST['notification'] = "Jenis barang ".$_REQUEST['jenisbarang']." sudah ada. Masukkan yang lain";
		}
		
	}
	if ($valid) {
?>
	<form name='FormSuccess' method='post' action='jenisbarang-lihat'><input type="hidden" name="notificationerror" value="0"><input type='hidden' name='notification' value='Sukses <?php echo ($_REQUEST['ams'] == 1 ? "merubah" : "menambahkan") ?> jenis barang <?php echo $_REQUEST['jenisbarang'] ?>'></form>
	<script language="javascript">
	document.FormSuccess.submit();
	</script>
<?php
	}
} else {
	if (($_REQUEST['ams'] == 1) && ($tokenError == 0)) {
		$item_types = getItemTypes(array("no" => ' = '.$_REQUEST['no']))->fetch(PDO::FETCH_ASSOC);
		if ($_REQUEST['jenisbarang'] == "") $_REQUEST['jenisbarang'] = $item_types['name'];
	}
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
              <h2 class="no-margin-bottom">Masterdata - <?php echo ($_REQUEST['ams'] == 1 ? "Edit" : "Tambah") ?> Jenis Barang</h2>
            </div>
          </header>
          
		  <!-- Forms Section-->
          <section class="forms"> 
            <div class="container-fluid">
              <div class="row">
                
                <!-- Form Elements -->
                <div class="col-lg-12">
                  <div class="card">
                    <?php if ($_REQUEST['notification'] != "") { ?>
										<div class="card-header d-flex align-items-center <?php echo ($_REQUEST['notificationerror'] == 1 ? "notificationerror" : "notificationtext") ?>">
                      <h5><?php echo $_REQUEST['notification'] ?></h5>
                    </div>
										<?php } ?>
                    <div class="card-header d-flex align-items-center">
                      <h3 class="h4">Jenis Barang</h3>
                    </div>
					
                    <div class="card-body">
                      <form class="form-horizontal" method="post">
											
												<input type="hidden" name="ams" value="<?php echo $_REQUEST['ams'] ?>">
												<input type="hidden" name="no" value="<?php echo $_REQUEST['no'] ?>">
												<input type="hidden" name="token" value="<?php echo $_REQUEST['token'] ?>">
											
                        <div class="form-group row">
                          <label class="col-sm-3 form-control-label">Jenis Barang</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" name="jenisbarang" value="<?php echo $_REQUEST['jenisbarang'] ?>" required>
                          </div>
                        </div>
						
                        <div class="line"></div>
                        
                        <div class="form-group row">
                          <div class="col-sm-4 offset-sm-3">
                            <input type="submit" class="btn btn-primary" name="simpan" value="Simpan">
                            <input type="button" class="btn btn-secondary" name="batal" value="Batal" onclick="window.location='jenisbarang-lihat'">
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