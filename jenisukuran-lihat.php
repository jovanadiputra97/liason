<?php

$crudDefaultField = "name";
$crudEditAction = "jenisukuran-manage";
$crudDeleteAction = "jenisukuran-manage";
$crudPageEditable = "+ Jenis Ukuran";

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
              <h2 class="no-margin-bottom">Masterdata - Lihat Jenis Ukuran</h2>
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
                      <h3 class="h4">Jenis Ukuran</h3>
                    </div>
                    <div class="card-body">
						<div class="container">
						
							<?php include_once("module/crud.php") ?>
							<form class="form-horizontal" id="FormCrud" method="post">
								<div class="form-group row">
									<input type="hidden" name="SortField" id="SortField" value="<?php echo $curSortField ?>">
									<input type="hidden" name="SortValue" id="SortValue" value="<?php echo $curSortValue ?>">
									<input type="text" class="form-control formauto selectheight" name="jenisukuran" placeholder="Jenis Ukuran" value="<?php echo $_REQUEST['jenisukuran'] ?>">
									&nbsp;
									<input type="submit" class="btn btn-primary submitheight" name="SearchButton" value="Cari">
								</div>
							</form>	
							
							<div class="table-responsive">          
								<table class="table">
									<thead>
										<tr>
											<th>#</th>
											<th>Jenis Ukuran &nbsp;&nbsp;<a href="javascript:sortCrud('name')"><i <?php getCrudIcon('name') ?>></i></a></th>
											<?php if ($editable) { ?>
											<th class="tableactionwidth"></th>
											<?php } ?>
										</tr>
									</thead>
									<tbody>
										<?php
										$searchParams = array("name" => "LIKE '%".$_REQUEST['jenisukuran']."%'");
										$item_units = getItemUnits($searchParams, $curSortField, $curSortValue);
										$no = 0;
										while ($rowItemUnit = $item_units->fetch(PDO::FETCH_ASSOC)) {
											$no++;
										?>
										<tr>
											<td><?php echo $no ?></td>
											<td><?php echo $rowItemUnit['name'] ?></td>
											<?php if ($editable) { ?>
											<td>
												<a href="javascript:editData(1,<?php echo $rowItemUnit['no'] ?>,'<?php echo generateToken($crudEditAction."-1-".$rowItemUnit['no']) ?>')"><img src="img/i-edit.png" /></a>
												<a href="javascript:confirmDelete(2,<?php echo $rowItemUnit['no'] ?>,'<?php echo $rowItemUnit['name'] ?>','<?php echo generateToken($crudDeleteAction."-2-".$rowItemUnit['no']) ?>')"><img src="img/i-delete.png" /></a>
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