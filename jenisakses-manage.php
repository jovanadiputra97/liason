<?php

$tokenErrorRedirect = "jenisakses-lihat";
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
	$accountLevel = getAccountLevels(array("no" => ' = '.$_REQUEST['no']))->fetch(PDO::FETCH_ASSOC);
	if (getAccountWithLevels(array("al.no" => " = ".$_REQUEST['no']))->rowCount() > 0) $valid = 0;
	
	if ($valid) {
		deleteAccountLevel($_REQUEST['no']);
		insertLog(getLogCategoryNoByName('Hapus'), $_SESSION['userno'], getPageNoByName('+ Tambah Jenis Akses'), $_REQUEST['no']." : ".$accountLevel['name']);
?>
	<form name='FormSuccess' method='post' action='jenisakses-lihat'><input type="hidden" name="notificationerror" value="0"><input type='hidden' name='notification' value='Sukses menghapus jenis akses <?php echo $accountLevel['name'] ?>'></form>
	<script language="javascript">
	document.FormSuccess.submit();
	</script>
<?php
	} else {
?>
	<form name='FormError' method='post' action='jenisakses-lihat'><input type="hidden" name="notificationerror" value="1"><input type='hidden' name='notification' value='Gagal menghapus jenis akses <?php echo $accountLevel['name'] ?> karena digunakan user / karyawan'></form>
	<script language="javascript">
	document.FormError.submit();
	</script>
<?php
	}
}
if (isset($_REQUEST['simpan']) &&  $_REQUEST['simpan'] == "Simpan" && $tokenError == 0) 
{
	$valid = 1;
	
	$totalAccess = 0;
	foreach ($_REQUEST as $reqKey => $reqVal) {
		if (strpos($reqKey, "menu") !== false) {
			$totalAccess++;
		}			
	}
	
	if ($totalAccess == 0) {
		$valid = false;
		$_REQUEST['notificationerror'] = 1;
		$_REQUEST['notification'] = "Menu Yang Dapat Diakses minimal ada 1";
	} else {
	
		if ($_REQUEST['ams'] == 1) {
			
			$valid = updateAccountLevel($_REQUEST['no'], $_REQUEST['jenisakses']);
			
			if ($valid) {
			
				$accountLevel = getAccountLevels(array("no" => ' = '.$_REQUEST['no']))->fetch(PDO::FETCH_ASSOC);
				$accountLevelPages = getAccountLevelPages($_REQUEST['no'])->fetchAll(PDO::FETCH_COLUMN, 0);
				
				$menuLog = "";
				
				foreach ($_REQUEST as $reqKey => $reqVal) {
					if (strpos($reqKey, "menu") !== false) {
						$curMenu = explode("_",$reqKey)[1];
						if (in_array($curMenu, $accountLevelPages)) {
							//echo "Exist : ".$curMenu."<br />";
							array_splice($accountLevelPages, array_search($curMenu, $accountLevelPages), 1);
							$menuLog .= ", ".$curMenu." = Existing";
						} else {
							//echo "Insert : ".$curMenu."<br />";
							insertAccountLevelPage($_REQUEST['no'], $curMenu);
							$menuLog .= ", ".$curMenu." = Add";
						}
					}
				}
				
				foreach ($accountLevelPages as $accountLevelPage) {
					//echo "Delete : ".$accountLevelPage."<br />";
					deleteAccountLevelPage($_REQUEST['no'], $accountLevelPage);
					$menuLog .= ", ".$accountLevelPage." = Delete";
				}
				
				insertLog(getLogCategoryNoByName('Edit'), $_SESSION['userno'], getPageNoByName('+ Tambah Jenis Akses'), $_REQUEST['no'].$menuLog);
				
			} else {
				$_REQUEST['notificationerror'] = 1;
				$_REQUEST['notification'] = "Jenis akses ".$_REQUEST['jenisakses']." sudah ada. Masukkan yang lain";
			}
			
		} else {
			
			$valid = insertAccountLevel($_REQUEST['jenisakses']);
			if ($valid) {
				$accountLevel = getAccountLevels(array("name" => " LIKE '".$_REQUEST['jenisakses']."'"))->fetch(PDO::FETCH_ASSOC);
				
				$menuLog = "";
				
				foreach ($_REQUEST as $reqKey => $reqVal) {
					if (strpos($reqKey, "menu") !== false) {
						$curMenu = explode("_",$reqKey)[1];
						insertAccountLevelPage($accountLevel['no'], $curMenu);
						if ($menuLog != "") $menuLog .= ", ";
						$menuLog .= $curMenu;
					}
				}
				
				insertLog(getLogCategoryNoByName('Tambah'), $_SESSION['userno'], getPageNoByName('+ Tambah Jenis Akses'), $accountLevel['no']." : ".$menuLog);
			} else {
				$_REQUEST['notificationerror'] = 1;
				$_REQUEST['notification'] = "Jenis akses ".$_REQUEST['jenisakses']." sudah ada. Masukkan yang lain";
			}
			
		}
	}
	if ($valid) {
?>
	<form name='FormSuccess' method='post' action='jenisakses-lihat'><input type="hidden" name="notificationerror" value="0"><input type='hidden' name='notification' value='Sukses <?php echo ($_REQUEST['ams'] == 1 ? "merubah" : "menambahkan") ?> jenis akses <?php echo $_REQUEST['jenisakses'] ?>'></form>
	<script language="javascript">
	document.FormSuccess.submit();
	</script>
<?php
	}
} else {
	if (($_REQUEST['ams'] == 1) && ($tokenError == 0)) {
		$accountLevel = getAccountLevels(array("no" => ' = '.$_REQUEST['no']))->fetch(PDO::FETCH_ASSOC);
		if ($_REQUEST['jenisakses'] == "") $_REQUEST['jenisakses'] = $accountLevel['name'];
		
		$accountLevelPages = getAccountLevelPages($_REQUEST['no']);
		while ($rowAccountLevelPage = $accountLevelPages->fetch(PDO::FETCH_ASSOC)) {
			$_REQUEST['menu_'.$rowAccountLevelPage['no']] = 1;
		}
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
              <h2 class="no-margin-bottom">Masterdata - <?php echo ($_REQUEST['ams'] == 1 ? "Edit" : "Tambah") ?> Jenis Akses</h2>
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
                      <h3 class="h4">Jenis Akses</h3>
                    </div>
					
                    <div class="card-body">
                      <form class="form-horizontal" method="post">
											
												<input type="hidden" name="ams" value="<?php echo $_REQUEST['ams'] ?>">
												<input type="hidden" name="no" value="<?php echo $_REQUEST['no'] ?>">
												<input type="hidden" name="token" value="<?php echo $_REQUEST['token'] ?>">
												
                        <div class="form-group row">
                          <label class="col-sm-3 form-control-label">Jenis Akses</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" name="jenisakses" id="jenisakses" value="<?php echo $_REQUEST['jenisakses'] ?>" required>
                          </div>
                        </div>
						
												<div class="form-group row">
													<label class="col-sm-3 form-control-label">Menu Yang Dapat Diakses<br><small class="text-primary">Dapat dipilih lebih dari satu</small></label>
													<div class="col-sm-9">
														<?php
														$pageRoots = getPageRoots();
														while ($rowPageRoot = $pageRoots->fetch(PDO::FETCH_ASSOC)) {
															if ($rowPageRoot['alias'] != NULL) {
														?>
															<div>
																<input id="option" type="checkbox" value="1" name="menu_<?php echo $rowPageRoot['no'] ?>" <?php if ($_REQUEST['menu_'. $rowPageRoot['no']] == "1") echo "checked" ?>>
																<label for="option" value=""><?php echo $rowPageRoot['name'] ?></label>
															</div>
														<?php
															} else {
																echo "<div>".$rowPageRoot['name']."</div>";
																$subPages = getSubPages($rowPageRoot['no']);
																while ($rowSubPages = $subPages->fetch(PDO::FETCH_ASSOC)) {
																?>
																<div class="jenisaksessubmenu">
																	<input id="option" type="checkbox" value="1" name="menu_<?php echo $rowSubPages['no'] ?>" <?php if ($_REQUEST['menu_'. $rowSubPages['no']] == "1") echo "checked" ?>>
																	<label for="option" value=""><?php echo $rowSubPages['name'] ?></label>
																</div>
																<?php
																}
															}
														}
														?>
													</div>
												</div>
						
                        <div class="line"></div>
                        
                        <div class="form-group row">
                          <div class="col-sm-4 offset-sm-3">
                            <input type="submit" class="btn btn-primary" name="simpan" value="Simpan">
                            <input type="button" class="btn btn-secondary" name="batal" value="Batal" onclick="window.location='jenisakses-lihat'">
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