<?php

include_once("module/customer.php");

$tokenErrorRedirect = "pelanggan-lihat";
$tokenError = checkTokenError();

session_start();

// ACTION
if ($_REQUEST['ams'] == 2) {
	
	include_once("module/sales.php");
	
	$valid = 1;
	$customer = getCustomer(array("no" => ' = '.$_REQUEST['no']))->fetch(PDO::FETCH_ASSOC);
	if (getSales(array("customer_no" => " = ".$_REQUEST['no']))->rowCount() > 0) $valid = 0;
	
	if ($valid) {
		deleteCustomer($_REQUEST['no']);
		insertLog(getLogCategoryNoByName('Hapus'), $_SESSION['userno'], getPageNoByName('+ Tambah Pelanggan'), $_REQUEST['no']." : ".$customer['name']);
?>
	<form name='FormSuccess' method='post' action='pelanggan-lihat'><input type="hidden" name="notificationerror" value="0"><input type='hidden' name='notification' value='Sukses menghapus nama pelanggan <?php echo $customer['name'] ?>'></form>
	<script language="javascript">
	document.FormSuccess.submit();
	</script>
<?php
	} else {
?>
	<form name='FormError' method='post' action='pelanggan-lihat'><input type="hidden" name="notificationerror" value="1"><input type='hidden' name='notification' value='Gagal menghapus nama pelanggan <?php echo $customer['name'] ?> karena digunakan faktur'></form>
	<script language="javascript">
	document.FormError.submit();
	</script>
<?php
	}
}
if (isset($_REQUEST['simpan']) &&  $_REQUEST['simpan'] == "Simpan" && $tokenError == 0) 
{
	if ($_REQUEST['ams'] == 1) {
		
		updateCustomer($_REQUEST['no'], $_REQUEST['namapelanggan'], $_REQUEST['alamat'], $_REQUEST['telepon'], $_REQUEST['email'], $_REQUEST['catatan']);
		insertLog(getLogCategoryNoByName('Edit'), $_SESSION['userno'], getPageNoByName('+ Tambah Pelanggan'), $_REQUEST['no']." : ".$_REQUEST['namapelanggan'].";".$_REQUEST['alamat'].";".$_REQUEST['telepon'].";".$_REQUEST['email'].";".$_REQUEST['catatan']." - Edit Pelanggan");
		
	} else {
		
		insertCustomer($_REQUEST['namapelanggan'], $_REQUEST['alamat'], $_REQUEST['telepon'], $_REQUEST['email'], $_REQUEST['catatan']);
		$lastCustomerNo = getLastCustomerNo();
		insertLog(getLogCategoryNoByName('Tambah'), $_SESSION['userno'], getPageNoByName('+ Tambah Pelanggan'), $lastCustomerNo." : ".$_REQUEST['namapelanggan'].";".$_REQUEST['alamat'].";".$_REQUEST['telepon'].";".$_REQUEST['email'].";".$_REQUEST['catatan']." - Tambah Pelanggan");
		
	}
?>
	<form name='FormSuccess' method='post' action='pelanggan-lihat'><input type="hidden" name="notificationerror" value="0"><input type='hidden' name='notification' value='Sukses <?php echo ($_REQUEST['ams'] == 1 ? "merubah" : "menambahkan") ?> nama pelanggan <?php echo $_REQUEST['namapelanggan'] ?>'></form>
	<script language="javascript">
	document.FormSuccess.submit();
	</script>
<?php
} else {
	if (($_REQUEST['ams'] == 1) && ($tokenError == 0)) {
		$customer = getCustomer(array("no" => ' = '.$_REQUEST['no']))->fetch(PDO::FETCH_ASSOC);
		if ($_REQUEST['namapelanggan'] == "") $_REQUEST['namapelanggan'] = $customer['name'];
		if ($_REQUEST['alamat'] == "") $_REQUEST['alamat'] = $customer['address'];
		if ($_REQUEST['telepon'] == "") $_REQUEST['telepon'] = $customer['phone'];
		if ($_REQUEST['email'] == "") $_REQUEST['email'] = $customer['email'];
		if ($_REQUEST['catatan'] == "") $_REQUEST['catatan'] = $customer['notes'];
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
              <h2 class="no-margin-bottom"><?php echo ($_REQUEST['ams'] == 1 ? "Edit" : "Tambah") ?> Pelanggan</h2>
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
                      <h3 class="h4">Pelanggan</h3>
                    </div>
					
                    <div class="card-body">
                      <form class="form-horizontal" method="post">
											
												<input type="hidden" name="ams" value="<?php echo $_REQUEST['ams'] ?>">
												<input type="hidden" name="no" value="<?php echo $_REQUEST['no'] ?>">
												<input type="hidden" name="token" value="<?php echo $_REQUEST['token'] ?>">
											
                        <div class="form-group row">
                          <label class="col-sm-3 form-control-label">Nama Pelanggan</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" name="namapelanggan" value="<?php echo $_REQUEST['namapelanggan'] ?>" required>
                          </div>
                        </div>
						
												<div class="form-group row">
                          <label class="col-sm-3 form-control-label">Alamat</label>
                          <div class="col-sm-9">
                            <textarea class="form-control" name="alamat" required><?php echo $_REQUEST['alamat'] ?></textarea>
                          </div>
                        </div>
						
												<div class="form-group row">
                          <label class="col-sm-3 form-control-label">Telepon</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" name="telepon" value="<?php echo $_REQUEST['telepon'] ?>">
                          </div>
                        </div>
						
												<div class="form-group row">
                          <label class="col-sm-3 form-control-label">Email</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" name="email" value="<?php echo $_REQUEST['email'] ?>">
                          </div>
                        </div>
						
												<div class="form-group row">
                          <label class="col-sm-3 form-control-label">Catatan</label>
                          <div class="col-sm-9">
                            <textarea class="form-control" name="catatan"><?php echo $_REQUEST['catatan'] ?></textarea>
                          </div>
                        </div>
						
                        <div class="line"></div>
                        
                        <div class="form-group row">
                          <div class="col-sm-4 offset-sm-3">
                            <input type="submit" class="btn btn-primary" name="simpan" value="Simpan">
                            <input type="button" class="btn btn-secondary" name="batal" value="Batal" onclick="window.location='pelanggan-lihat'">
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