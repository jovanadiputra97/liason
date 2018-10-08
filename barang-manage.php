<?php
include_once("module/item.php");

$tokenErrorRedirect = "barang-lihat";
$tokenError = checkTokenError();

session_start();

/*
include_once("../include/library.php");
include_once("../include/conn.php");
include_once("library/admin_sessioncheck.php");
*/

// ACTION
if ($_REQUEST['ams'] == 2) {
	
	$items = getItems(array("no" => ' = '.$_REQUEST['no']))->fetch(PDO::FETCH_ASSOC);
	deleteItem($_REQUEST['no']);
	insertLog(getLogCategoryNoByName('Hapus'), $_SESSION['userno'], getPageNoByName('+ Tambah Barang'), $_REQUEST['no'].", ".$items['code']);
?>
	<form name='FormSuccess' method='post' action='barang-lihat'><input type="hidden" name="notificationerror" value="0"><input type='hidden' name='notification' value='Sukses menghapus barang <?php echo $items['code'] ?>'></form>
	<script language="javascript">
	document.FormSuccess.submit();
	</script>
<?php
	
}
if (isset($_REQUEST['simpan']) &&  $_REQUEST['simpan'] == "Simpan" && $tokenError == 0) 
{
	$valid = 1;
	if ($_REQUEST['ams'] == 1) {
		
		$items = getItems(array("no" => ' = '.$_REQUEST['no']))->fetch(PDO::FETCH_ASSOC);
		$valid = updateItem($_REQUEST['no'], $_REQUEST['kodebarang'], $_REQUEST['namabarang'], $_REQUEST['jenisbarang'], $items['image'], $_FILES['gambar'], $_REQUEST['catatan']);
		
		if ($valid) {
		
			$itemDetails = getItemDetails(array("id.item_no" => " = ".$_REQUEST['no']))->fetchAll();
			
			$menuLog = "";
			
			function inArray($itemNo, $itemDetail, &$itemDetails, &$menuLog) {
				$searchNo = 0;
				foreach ($itemDetails as $cItemDetail) {
					if ($itemDetail['ukuran'] == $cItemDetail['item_unit_no']) {
						
						if ($itemDetail['harga'] != $cItemDetail['price']) {
							updateItemDetail($itemNo, $itemDetail['ukuran'], $itemDetail['harga']);
							$menuLog .= ", ".$itemDetail['ukuran']."-".$itemDetail['harga']." = Edit";
						} else {
							$menuLog .= ", ".$itemDetail['ukuran']."-".$itemDetail['harga']." = Existing";
						}
						array_splice($itemDetails, $searchNo, 1);
						return true;
						
					}
					$searchNo++;
				}
				return false;
			}
			
			foreach ($_REQUEST['itemdetails'] as $itemDetail) {
				if (inArray($_REQUEST['no'], $itemDetail, $itemDetails, $menuLog)) {
					//echo "Exist : ".$itemDetail['ukuran']."-".$itemDetail['harga']."<br />";
				} else {
					//echo "Insert : ".$itemDetail['ukuran']."-".$itemDetail['harga']."<br />";
					insertItemDetail($_REQUEST['no'], $itemDetail);
					$menuLog .= ", ".$itemDetail['ukuran']."-".$itemDetail['harga']." = Tambah";
				}
			}
			
			foreach ($itemDetails as $itemDetail) {
				//echo "Delete : ".$itemDetail['item_unit_no']."-".$itemDetail['price']."<br />";
				deleteItemDetail($_REQUEST['no'], $itemDetail['item_unit_no'], $itemDetail['price']);
				$menuLog .= ", ".$itemDetail['item_unit_no']."-".$itemDetail['price']." = Hapus";
			}
			
			insertLog(getLogCategoryNoByName('Edit'), $_SESSION['userno'], getPageNoByName('+ Tambah Barang'), $_REQUEST['no'].", ".$_REQUEST['kodebarang'].$menuLog);
		
		} else {
			$_REQUEST['notificationerror'] = 1;
			$_REQUEST['notification'] = "Barang ".$_REQUEST['kodebarang']." sudah ada. Masukkan yang lain";
		}
		
	} else {
		
		$valid = insertItem($_REQUEST['kodebarang'], $_REQUEST['namabarang'], $_REQUEST['jenisbarang'], $_FILES['gambar'], $_REQUEST['itemdetails'], $_REQUEST['catatan']);
		if ($valid) {
			$items = getItems(array("code" => " LIKE '%".$_REQUEST['kodebarang']."%'"))->fetch(PDO::FETCH_ASSOC);
			$insertLogText = $items['no'].",".$_REQUEST['kodebarang'].";".$_REQUEST['namabarang'].";".$_REQUEST['jenisbarang'].";".$_FILES['gambar']['name'].";".$_REQUEST['catatan'];
			$detailNo = 0;
			foreach ($_REQUEST['itemdetails'] as $itemDetail) {
				$detailNo++;
				if ($detailNo > 1) $insertLogText .= ", ";
				$insertLogText .= $itemDetail['ukuran']."-".$itemDetail['harga']." = Tambah";
			}
			insertLog(getLogCategoryNoByName('Tambah'), $_SESSION['userno'], getPageNoByName('+ Tambah Barang'), $insertLogText);
		} else {
			$_REQUEST['notificationerror'] = 1;
			$_REQUEST['notification'] = "Barang ".$_REQUEST['kodebarang']." sudah ada. Masukkan yang lain";
		}
		
	}
	if ($valid) {
?>
	<form name='FormSuccess' method='post' action='barang-lihat'><input type="hidden" name="notificationerror" value="0"><input type='hidden' name='notification' value='Sukses <?php echo ($_REQUEST['ams'] == 1 ? "merubah" : "menambahkan") ?> barang <?php echo $_REQUEST['kodebarang'] ?>'></form>
	<script language="javascript">
	document.FormSuccess.submit();
	</script>
<?php
	}
} else {
	if (($_REQUEST['ams'] == 1) && ($tokenError == 0)) {
		$item = getItems(array("no" => ' = '.$_REQUEST['no']))->fetch(PDO::FETCH_ASSOC);
		if ($_REQUEST['kodebarang'] == "") $_REQUEST['kodebarang'] = $item['code'];
		if ($_REQUEST['namabarang'] == "") $_REQUEST['namabarang'] = $item['name'];
		if ($_REQUEST['jenisbarang'] == "") $_REQUEST['jenisbarang'] = $item['item_type_no'];
		if ($_REQUEST['gambar'] == "") $_REQUEST['gambar'] = $item['image'];
		if ($_REQUEST['catatan'] == "") $_REQUEST['catatan'] = $item['notes'];
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
              <h2 class="no-margin-bottom"><?php echo ($_REQUEST['ams'] == 1 ? "Edit" : "Tambah") ?> Barang</h2>
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
                      <h3 class="h4">Barang</h3>
                    </div>
					
                    <div class="card-body">
                      <form name="FormAdd" class="form-horizontal" method="post" enctype="multipart/form-data" onsubmit="return verifyForm();">
											
												<input type="hidden" name="ams" value="<?php echo $_REQUEST['ams'] ?>">
												<input type="hidden" name="no" value="<?php echo $_REQUEST['no'] ?>">
												<input type="hidden" name="token" value="<?php echo $_REQUEST['token'] ?>">
											
                        <div class="form-group row">
                          <label class="col-sm-3 form-control-label">Kode Barang</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" name="kodebarang" required value="<?php echo $_REQUEST['kodebarang'] ?>">
                          </div>
                        </div>
						
						<div class="form-group row">
                          <label class="col-sm-3 form-control-label">Nama Barang</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" name="namabarang" required value="<?php echo $_REQUEST['namabarang'] ?>">
                          </div>
                        </div>
						
						<div class="form-group row">
                          <label class="col-sm-3 form-control-label">Jenis Barang</label>
                          <div class="col-sm-9">
                            <select name="jenisbarang" class="form-control selectpadding" required>
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
                          </div>
                        </div>
						
						<div class="form-group row">
                          <label class="col-sm-3 form-control-label">Gambar</label>
                          <div class="col-sm-9">
														<?php if ($_REQUEST['ams'] == 1) { ?>
														<img src="<?php echo $_REQUEST['gambar'] ?>" height="200px"><br /><br />
														<?php } ?>
                            <input type="file" class="form-control" name="gambar" accept="image/*" <?php if ($_REQUEST['ams'] == "") echo 'required' ?>>
                          </div>
                        </div>
						
						<div class="form-group row">
                          <label class="col-sm-3 form-control-label">Ukuran &amp; Harga</label>
                          <div class="col-sm-9">
                            <select name="jenisukuran" id="jenisukuran" class="form-control selectpadding formauto displayinline">
															<option value="">- Jenis Ukuran -</option>
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
							Rp. <input type="number" class="form-control formauto displayinline" name="harga" id="harga" min="0" step="1">
							&nbsp;
							<input type="button" class="btn btn-primary displayinline" name="tambah" value="Tambah" onclick="javascript:addDataDetails()">
							<div class="table-responsive">          
								<table class="table">
									<thead>
										<tr>
											<th>Ukuran</th>
											<th>Harga</th>
											<th class="tableactionwidth"></th>
										</tr>
									</thead>
									<tbody id="tableDetailHarga">
									</tbody>
								</table>
							</div>
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
														<div id="errorverify" class="notificationerror" style="margin-bottom:25px"></div>
                            <input type="submit" class="btn btn-primary" name="simpan" value="Simpan">
                            <input type="button" class="btn btn-secondary" name="batal" value="Batal" onclick="window.location='barang-lihat'">
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>

<script language="javascript">
var dataDetails = [];
function addDataDetails() {
	dataDetails.push([$("#jenisukuran").val(), $("#jenisukuran option:selected").text(), $("#harga").val()]);
	$("#jenisukuran").val("");
	$("#harga").val("");
	printDataDetails();
}
function addExistingDataDetail(noUkuran, namaUkuran, harga) {
	dataDetails.push([noUkuran, namaUkuran, harga]);
	printDataDetails();
}
function deleteDataDetails(no) {
	dataDetails.splice(no, 1);
	printDataDetails();
}
function printDataDetails() {
	$("#tableDetailHarga").html("");
	dataDetails.forEach(function(item, index) {
		$("#tableDetailHarga").append(`
		<tr>
			<td>`+item[1]+`</td>
			<td><input type="hidden" name="itemdetails[`+index+`][ukuran]" value="`+item[0]+`"><input type="hidden" name="itemdetails[`+index+`][nama]" value="`+item[1]+`"><input type="hidden" name="itemdetails[`+index+`][harga]" value="`+item[2]+`">Rp. `+item[2]+`,-</td>
			<td>
				<a href="javascript:deleteDataDetails(`+index+`)"><img src="img/i-delete.png" /></a>
			</td>
		</tr>
		`);
	});
}
document.onreadystatechange = () => {
	if (document.readyState === 'complete') {
<?php
if ($_REQUEST['ams'] == 1) {
	$itemDetails = getItemDetails(array("id.item_no" => " = ".$_REQUEST['no']));
	while ($rowItemDetail = $itemDetails->fetch(PDO::FETCH_ASSOC)) {
?>
		addExistingDataDetail(<?php echo $rowItemDetail['item_unit_no'] ?>,'<?php echo $rowItemDetail['name'] ?>',<?php echo $rowItemDetail['price'] ?>);
<?php
	}
} else {
	foreach ($_REQUEST['itemdetails'] as $itemDetail) {
?>
		addExistingDataDetail(<?php echo $itemDetail['ukuran'] ?>,'<?php echo $itemDetail['nama'] ?>',<?php echo $itemDetail['harga'] ?>);
<?php
	}
}
?>
	}
}
function verifyForm() {
	if (dataDetails.length == 0) {
		$("#errorverify").html("Tambahkan ukuran di dalam barang ini..");
		return false;
	} else {
		return true;
	}
}
</script>
					
<?php
include_once("footer-main.php");
include_once("footer.php");
?>