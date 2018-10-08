<?php

$crudDefaultField = "name";
$crudEditAction = "jenisakses-manage";
$crudDeleteAction = "jenisakses-manage";
$crudPageEditable = "+ Tambah Jenis Akses";

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
        <div class="content-inner">
          <!-- Page Header-->
          <header class="page-header">
            <div class="container-fluid">
              <h2 class="no-margin-bottom">Masterdata - Lihat Jenis Akses</h2>
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
                      <h3 class="h4">Jenis Akses</h3>
                    </div>
                    <div class="card-body">
						<div class="container">
						
							<?php include_once("module/crud.php") ?>
							<form class="form-horizontal" id="FormCrud" method="post">
								<div class="form-group row">
									<input type="hidden" name="SortField" id="SortField" value="<?php echo $curSortField ?>">
									<input type="hidden" name="SortValue" id="SortValue" value="<?php echo $curSortValue ?>">
									<input type="text" class="form-control formauto selectheight" name="jenisakses" placeholder="Jenis Akses" value="<?php echo $_REQUEST['jenisakses'] ?>">
									&nbsp;
									<input type="submit" class="btn btn-primary submitheight" name="SearchButton" value="Cari">
								</div>
							</form>
							
							<?php
							$editable = checkAccountLevelPage($_SESSION['userno'], getPageNoByName("+ Tambah Jenis Akses"));
							?>
							
							<div class="table-responsive">          
								<table class="table">
									<thead>
										<tr>
											<th>#</th>
											<th>Jenis Akses &nbsp;&nbsp;<a href="javascript:sortCrud('name')"><i <?php getCrudIcon('name') ?>></i></a></th>
											<th>Menu Yang Dapat Diakses</th>
											<?php if ($editable) { ?>
											<th class="tableactionwidth"></th>
											<?php } ?>
										</tr>
									</thead>
									<tbody>
										<?php
										$accountLevels = getAccountLevels(array("name" => "LIKE '%".$_REQUEST['jenisakses']."%'"), $curSortField, $curSortValue);
										$no = 0;
										while ($rowAccountLevel = $accountLevels->fetch(PDO::FETCH_ASSOC)) {
											$no++;
										?>
										<tr>
											<td><?php echo $no ?></td>
											<td><?php echo $rowAccountLevel['name'] ?></td>
											<td><?php
											$accountLevelPages = getAccountLevelPages($rowAccountLevel['no']);
											$accountLevelNo = 0;
											while ($rowAccountLevelPages = $accountLevelPages->fetch(PDO::FETCH_ASSOC)) {
												$accountLevelNo++;
												if ($accountLevelNo > 1) echo ", ";
												echo $rowAccountLevelPages['name'];
											}
											?></td>
											<?php if ($editable) { ?>
											<td>
												<a href="javascript:editData(1,<?php echo $rowAccountLevel['no'] ?>,'<?php echo generateToken($crudEditAction."-1-".$rowAccountLevel['no']) ?>')"><img src="img/i-edit.png" /></a>
												<a href="javascript:confirmDelete(2,<?php echo $rowAccountLevel['no'] ?>,'<?php echo $rowAccountLevel['name'] ?>','<?php echo generateToken($crudDeleteAction."-2-".$rowAccountLevel['no']) ?>')"><img src="img/i-delete.png" /></a>
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