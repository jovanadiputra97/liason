<?php

include_once("module/item.php");

$tokenErrorRedirect = "jenisukuran-lihat";
$tokenError = checkTokenError();

session_start();

/*
include_once("../include/library.php");
include_once("../include/conn.php");
include_once("library/admin_sessioncheck.php");
*/

// ACTION
if ($_REQUEST['ams'] == 2) {
	
	$valid = 1;
	$item_units = getItemUnits(array("no" => ' = '.$_REQUEST['no']))->fetch(PDO::FETCH_ASSOC);
	if (getItemDetails(array("item_unit_no" => " = ".$_REQUEST['no']))->rowCount() > 0) $valid = 0;
	
	if ($valid) {
		deleteItemUnit($_REQUEST['no']);
		insertLog(getLogCategoryNoByName('Hapus'), $_SESSION['userno'], getPageNoByName('+ Jenis Ukuran'), $_REQUEST['no']." : ".$item_units['name']);
?>
	<form name='FormSuccess' method='post' action='jenisukuran-lihat'><input type="hidden" name="notificationerror" value="0"><input type='hidden' name='notification' value='Sukses menghapus jenis ukuran <?php echo $item_units['name'] ?>'></form>
	<script language="javascript">
	document.FormSuccess.submit();
	</script>
<?php
	} else {
?>
	<form name='FormError' method='post' action='jenisukuran-lihat'><input type="hidden" name="notificationerror" value="1"><input type='hidden' name='notification' value='Gagal menghapus jenis ukuran <?php echo $item_units['name'] ?> karena digunakan barang'></form>
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
		
		$valid = updateItemUnit($_REQUEST['no'], $_REQUEST['jenisukuran']);
		if ($valid) {
			insertLog(getLogCategoryNoByName('Edit'), $_SESSION['userno'], getPageNoByName('+ Jenis Ukuran'), $_REQUEST['no']." : ".$_REQUEST['jenisukuran']);
		} else {
			$_REQUEST['notificationerror'] = 1;
			$_REQUEST['notification'] = "Jenis ukuran ".$_REQUEST['jenisukuran']." sudah ada. Masukkan yang lain";
		}
		
	} else {
		
		$valid = insertItemUnit($_REQUEST['jenisukuran']);
		if ($valid) {
			$itemUnit = getItemUnits(array("name" => " LIKE '".$_REQUEST['jenisukuran']."'"))->fetch(PDO::FETCH_ASSOC);
			insertLog(getLogCategoryNoByName('Tambah'), $_SESSION['userno'], getPageNoByName('+ Jenis Ukuran'), $itemUnit['no']." : ".$_REQUEST['jenisukuran']);
		} else {
			$_REQUEST['notificationerror'] = 1;
			$_REQUEST['notification'] = "Jenis ukuran ".$_REQUEST['jenisukuran']." sudah ada. Masukkan yang lain";
		}
		
	}
	if ($valid) {
?>
	<form name='FormSuccess' method='post' action='jenisukuran-lihat'><input type="hidden" name="notificationerror" value="0"><input type='hidden' name='notification' value='Sukses <?php echo ($_REQUEST['ams'] == 1 ? "merubah" : "menambahkan") ?> jenis ukuran <?php echo $_REQUEST['jenisukuran'] ?>'></form>
	<script language="javascript">
	document.FormSuccess.submit();
	</script>
<?php
	}
} else {
	if (($_REQUEST['ams'] == 1) && ($tokenError == 0)) {
		$item_units = getItemUnits(array("no" => ' = '.$_REQUEST['no']))->fetch(PDO::FETCH_ASSOC);
		if ($_REQUEST['jenisukuran'] == "") $_REQUEST['jenisukuran'] = $item_units['name'];
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
              <h2 class="no-margin-bottom">Masterdata - <?php echo ($_REQUEST['ams'] == 1 ? "Edit" : "Tambah") ?> Jenis Ukuran</h2>
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
                      <h3 class="h4">Jenis Ukuran</h3>
                    </div>
					
                    <div class="card-body">
                      <form class="form-horizontal" method="post">
											
												<input type="hidden" name="ams" value="<?php echo $_REQUEST['ams'] ?>">
												<input type="hidden" name="no" value="<?php echo $_REQUEST['no'] ?>">
												<input type="hidden" name="token" value="<?php echo $_REQUEST['token'] ?>">
											
                        <div class="form-group row">
                          <label class="col-sm-3 form-control-label">Jenis Ukuran</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" name="jenisukuran" value="<?php echo $_REQUEST['jenisukuran'] ?>" required>
                          </div>
                        </div>
						
                        <div class="line"></div>
                        
                        <div class="form-group row">
                          <div class="col-sm-4 offset-sm-3">
                            <input type="submit" class="btn btn-primary" name="simpan" value="Simpan">
                            <input type="button" class="btn btn-secondary" name="batal" value="Batal" onclick="window.location='jenisukuran-lihat'">
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