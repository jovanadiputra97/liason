<?php

$tokenErrorRedirect = "userkaryawan-lihat";
$tokenError = checkTokenError();

session_start();

/*
include_once("../include/library.php");
include_once("../include/conn.php");
include_once("library/admin_sessioncheck.php");
*/

// ACTION
if ($_REQUEST['ams'] == 2) {
	
	$account = getAccounts(array("no" => ' = '.$_REQUEST['no']))->fetch(PDO::FETCH_ASSOC);
	deleteAccount($_REQUEST['no']);
	insertLog(getLogCategoryNoByName('Hapus'), $_SESSION['userno'], getPageNoByName('+ Tambah User / Karyawan'), $_REQUEST['no']." : ".$account['username']);
?>
	<form name='FormSuccess' method='post' action='userkaryawan-lihat'><input type="hidden" name="notificationerror" value="0"><input type='hidden' name='notification' value='Sukses menghapus user / karyawan <?php echo $account['username'] ?>'></form>
	<script language="javascript">
	document.FormSuccess.submit();
	</script>
<?php
	
}
if (isset($_REQUEST['simpan']) &&  $_REQUEST['simpan'] == "Simpan" && $tokenError == 0) 
{
	$valid = 1;
	if ($_REQUEST['ams'] == 1) {
		
		$updateUserSession = ($_SESSION['userno'] == $_REQUEST['no']);
		$valid = updateAccount($_REQUEST['no'], $_REQUEST['username'], $_REQUEST['jenisakses']);
		if ($valid) {
			$_SESSION['username'] = $_REQUEST['username'];
			$accountLog = "";
			if ($_REQUEST['password'] != "") {
				updatePassword($_REQUEST['no'], $_REQUEST['password']);
				$_SESSION['password'] = $_REQUEST['password'];
				$accountLog = " - Change Password";
			}
			insertLog(getLogCategoryNoByName('Edit'), $_SESSION['userno'], getPageNoByName('+ Tambah User / Karyawan'), $_REQUEST['no']." : ".$_REQUEST['username'].";".$_REQUEST['jenisakses'].$accountLog);
		} else {
			$_REQUEST['notificationerror'] = 1;
			$_REQUEST['notification'] = "User / karyawan ".$_REQUEST['username']." sudah ada. Masukkan yang lain";
		}
		
	} else {
		
		$valid = insertAccount($_REQUEST['username'], $_REQUEST['password'], $_REQUEST['jenisakses']);
		if ($valid) {
			$curAccount = getAccounts(array("username" => " LIKE '".$_REQUEST['username']."'"))->fetch(PDO::FETCH_ASSOC);
			insertLog(getLogCategoryNoByName('Tambah'), $_SESSION['userno'], getPageNoByName('+ Tambah User / Karyawan'), $curAccount['no']." : ".$_REQUEST['username'].";".$_REQUEST['jenisakses']);
		} else {
			$_REQUEST['notificationerror'] = 1;
			$_REQUEST['notification'] = "User / karyawan ".$_REQUEST['username']." sudah ada. Masukkan yang lain";
		}
		
	}
	if ($valid) {
?>
	<form name='FormSuccess' method='post' action='userkaryawan-lihat'><input type="hidden" name="notificationerror" value="0"><input type='hidden' name='notification' value='Sukses <?php echo ($_REQUEST['ams'] == 1 ? "merubah" : "menambahkan") ?> user / karyawan <?php echo $_REQUEST['username'] ?>'></form>
	<script language="javascript">
	document.FormSuccess.submit();
	</script>
<?php
	}
} else {
	if (($_REQUEST['ams'] == 1) && ($tokenError == 0)) {
		$account = getAccounts(array("no" => ' = '.$_REQUEST['no']))->fetch(PDO::FETCH_ASSOC);
		if ($_REQUEST['username'] == "") $_REQUEST['username'] = $account['username'];
		if ($_REQUEST['jenisakses'] == "") $_REQUEST['jenisakses'] = $account['account_level_no'];
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
              <h2 class="no-margin-bottom">Masterdata - <?php echo ($_REQUEST['ams'] == 1 ? "Edit" : "Tambah") ?> User / Karyawan</h2>
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
                      <h3 class="h4">User / Karyawan</h3>
                    </div>
					
                    <div class="card-body">
                      <form class="form-horizontal" method="post">
											
												<input type="hidden" name="ams" value="<?php echo $_REQUEST['ams'] ?>">
												<input type="hidden" name="no" value="<?php echo $_REQUEST['no'] ?>">
												<input type="hidden" name="token" value="<?php echo $_REQUEST['token'] ?>">
											
                        <div class="form-group row">
                          <label class="col-sm-3 form-control-label">Username</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" name="username" value="<?php echo $_REQUEST['username'] ?>" required>
                          </div>
                        </div>
						
						<div class="form-group row">
                          <label class="col-sm-3 form-control-label">Password</label>
                          <div class="col-sm-9">
                            <input type="password" class="form-control" name="password" id="password" <?php if ($_REQUEST['ams'] == "") echo "required" ?>>
                          </div>
                        </div>
						
						<div class="form-group row">
                          <label class="col-sm-3 form-control-label">Ketik Kembali Password</label>
                          <div class="col-sm-9">
                            <input type="password" class="form-control" name="ketikkembalipassword" id="confirm_password" <?php if ($_REQUEST['ams'] == "") echo "required" ?>>
                          </div>
                        </div>
												
												<script language="javascript">
												var password = document.getElementById("password"), confirm_password = document.getElementById("confirm_password");

												function validatePassword(){
													if(password.value != confirm_password.value) {
														confirm_password.setCustomValidity("Passwords Don't Match");
													} else {
														confirm_password.setCustomValidity('');
													}
												}

												password.onchange = validatePassword;
												confirm_password.onkeyup = validatePassword;
												</script>
						
						<div class="form-group row">
                          <label class="col-sm-3 form-control-label">Jenis Akses</label>
                          <div class="col-sm-9">
                            <select name="jenisakses" class="form-control selectpadding" required>
															<option value="">- Jenis Akses -</option>
															<?php
															$accountLevels = getAccountLevels();
															while ($rowAccountLevel = $accountLevels->fetch(PDO::FETCH_ASSOC)) {
															?>
															<option value="<?php echo $rowAccountLevel['no'] ?>" <?php if ($_REQUEST['jenisakses'] == $rowAccountLevel['no']) echo "selected" ?>><?php echo $rowAccountLevel['name'] ?></option>
															<?php
															}
															?>
                            </select>
                          </div>
                        </div>
						
                        <div class="line"></div>
                        
                        <div class="form-group row">
                          <div class="col-sm-4 offset-sm-3">
                            <input type="submit" class="btn btn-primary" name="simpan" value="Simpan">
                            <input type="button" class="btn btn-secondary" name="batal" value="Batal" onclick="window.location='userkaryawan-lihat'">
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