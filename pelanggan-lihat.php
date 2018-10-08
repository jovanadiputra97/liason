<?php
$crudDefaultField = "name";
$crudEditAction = "pelanggan-manage";
$crudDeleteAction = "pelanggan-manage";
$crudPageEditable = "+ Tambah Pelanggan";

session_start();

include_once("header.php");
include_once("header-main.php"); 
?>
    <?php
		include_once("navbar.php"); 
		?>
		
		<?php include_once("module/customer.php") ?>
		
        <div class="content-inner">
          <!-- Page Header-->
          <header class="page-header">
            <div class="container-fluid">
              <h2 class="no-margin-bottom">Lihat Pelanggan</h2>
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
                      <h3 class="h4">Pelanggan</h3>
                    </div>
                    <div class="card-body">
						<div class="container">
						
							<?php include_once("module/crud.php") ?>
							<form class="form-horizontal" id="FormCrud" method="post">
								<div class="form-group row">
									<input type="hidden" name="SortField" id="SortField" value="<?php echo $curSortField ?>">
									<input type="hidden" name="SortValue" id="SortValue" value="<?php echo $curSortValue ?>">
									<input type="text" class="form-control formmedium selectheight" name="namapelanggan" placeholder="Nama Pelanggan" value="<?php echo $_REQUEST['namapelanggan'] ?>">&nbsp;&nbsp;
									<input type="text" class="form-control formmedium selectheight" name="alamat" placeholder="Alamat" value="<?php echo $_REQUEST['alamat'] ?>">&nbsp;&nbsp;
									<input type="text" class="form-control formmedium selectheight" name="telepon" placeholder="Telepon" value="<?php echo $_REQUEST['telepon'] ?>">&nbsp;&nbsp;
									<input type="text" class="form-control formmedium selectheight" name="email" placeholder="Email" value="<?php echo $_REQUEST['email'] ?>">&nbsp;&nbsp;
									<input type="text" class="form-control formmedium selectheight" name="catatan" placeholder="Catatan" value="<?php echo $_REQUEST['catatan'] ?>">
									&nbsp;&nbsp;
									<input type="submit" class="btn btn-primary submitheight" name="SearchButton" value="Cari">
								</div>
							</form>	
							
							<div class="table-responsive">          
								<table class="table">
									<thead>
										<tr>
											<th>#</th>
											<th>Nama Pelanggan &nbsp;&nbsp;<a href="javascript:sortCrud('name')"><i <?php getCrudIcon('name') ?>></i></a></th>
											<th>Alamat &nbsp;&nbsp;<a href="javascript:sortCrud('address')"><i <?php getCrudIcon('address') ?>></i></a></th>
											<th>Telepon &nbsp;&nbsp;<a href="javascript:sortCrud('phone')"><i <?php getCrudIcon('phone') ?>></i></a></th>
											<th>Email &nbsp;&nbsp;<a href="javascript:sortCrud('email')"><i <?php getCrudIcon('email') ?>></i></a></th>
											<th>Catatan &nbsp;&nbsp;<a href="javascript:sortCrud('notes')"><i <?php getCrudIcon('notes') ?>></i></a></th>
											<?php if ($editable) { ?>
											<th class="tableactionwidth"></th>
											<?php } ?>
										</tr>
									</thead>
									<tbody>
										<?php
										$searchParams = array("name" => "LIKE '%".$_REQUEST['namapelanggan']."%'", "address" => "LIKE '%".$_REQUEST['alamat']."%'", "phone" => "LIKE '%".$_REQUEST['telepon']."%'", "email" => "LIKE '%".$_REQUEST['email']."%'", "notes" => "LIKE '%".$_REQUEST['catatan']."%'");
										$customer = getCustomer($searchParams, $curSortField, $curSortValue);
										$no = 0;
										while ($rowCustomer = $customer->fetch(PDO::FETCH_ASSOC)) {
											$no++;
										?>
										<tr>
											<td><?php echo $no ?></td>
											<td><?php echo $rowCustomer['name'] ?></td>
											<td><?php echo $rowCustomer['address'] ?></td>
											<td><?php echo $rowCustomer['phone'] ?></td>
											<td><?php echo $rowCustomer['email'] ?></td>
											<td><?php echo $rowCustomer['notes'] ?></td>
											<?php if ($editable) { ?>
											<td>
												<a href="javascript:editData(1,<?php echo $rowCustomer['no'] ?>,'<?php echo generateToken($crudEditAction."-1-".$rowCustomer['no']) ?>')"><img src="img/i-edit.png" /></a>
												<a href="javascript:confirmDelete(2,<?php echo $rowCustomer['no'] ?>,'<?php echo $rowCustomer['name'] ?>','<?php echo generateToken($crudDeleteAction."-2-".$rowCustomer['no']) ?>')"><img src="img/i-delete.png" /></a>
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