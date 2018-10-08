<?php
$crudDefaultField = "code";
$crudEditAction = "barang-manage";
$crudDeleteAction = "barang-manage";
$crudPageEditable = "+ Tambah Barang";

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
              <h2 class="no-margin-bottom">Lihat Barang</h2>
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
                      <h3 class="h4">Barang</h3>
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
									<input type="submit" class="btn btn-primary submitheight" name="SearchButton" value="Cari">
								</div>
							</form>	
							
							<div class="table-responsive">          
								<table class="table">
									<thead>
										<tr>
											<th>#</th>
											<th>Kode &nbsp;&nbsp;<a href="javascript:sortCrud('code')"><i <?php getCrudIcon('code') ?>></i></a></th>
											<th>Nama Barang &nbsp;&nbsp;<a href="javascript:sortCrud('name')"><i <?php getCrudIcon('name') ?>></i></a></th>
											<th>Jenis Barang &nbsp;&nbsp;<a href="javascript:sortCrud('item_type_no')"><i <?php getCrudIcon('item_type_no') ?>></i></a></th>
											<th>Gambar</th>
											<th>Ukuran &amp; Harga</th>
											<?php if ($editable) { ?>
											<th class="tableactionwidth"></th>
											<?php } ?>
										</tr>
									</thead>
									<tbody>
										<?php
										$searchParams = array("i.code" => "LIKE '%".$_REQUEST['kodebarang']."%'", "i.name" => "LIKE '%".$_REQUEST['namabarang']."%'");
										if ($_REQUEST['jenisbarang'] > 0) {
											$searchParams["i.item_type_no"] = " = ".$_REQUEST['jenisbarang'];
										}
										$items = getItemWithTypes($searchParams, $curSortField, $curSortValue);
										$no = 0;
										while ($rowItem = $items->fetch(PDO::FETCH_ASSOC)) {
											$no++;
										?>
										<tr>
											<td><?php echo $no ?></td>
											<td><?php echo $rowItem['code'] ?></td>
											<td><?php echo $rowItem['name'] ?></td>
											<td><?php echo $rowItem['item_type_name'] ?></td>
											<td><img src="<?php echo $rowItem['image'] ?>" height="80"></td>
											<td><?php
											$itemDetails = getItemDetails(array("id.item_no" => " = ".$rowItem['no']));
											$detailNo = 0;
											while ($rowItemDetail = $itemDetails->fetch(PDO::FETCH_ASSOC)) {
												$detailNo++;
												if ($detailNo > 1) echo ", ";
												echo $rowItemDetail['name']." (Rp ".number_format($rowItemDetail['price'],0,",",".").")";
											}
											?></td>
											<?php if ($editable) { ?>
											<td>
												<a href="javascript:editData(1,<?php echo $rowItem['no'] ?>,'<?php echo generateToken($crudEditAction."-1-".$rowItem['no']) ?>')"><img src="img/i-edit.png" /></a>
												<a href="javascript:confirmDelete(2,<?php echo $rowItem['no'] ?>,'<?php echo $rowItem['code'] ?>','<?php echo generateToken($crudDeleteAction."-2-".$rowItem['no']) ?>')"><img src="img/i-delete.png" /></a>
											</td>
											<?php } ?>
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