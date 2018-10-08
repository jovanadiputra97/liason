<!-- Side Navbar -->
<nav class="side-navbar">
  <!-- Sidebar Header-->
  <div class="sidebar-header d-flex align-items-center">
	<div class="avatar"><img src="img/<?php echo $_REQUEST['brand'] ?>.jpg" alt="..." class="img-fluid"></div>
	<div class="title">
	  <h1 class="h4"><?php echo $_SESSION['username'] ?></h1>
	  <p>Point of Sales</p>
	</div>
  </div>
  <!-- Sidebar Navidation Menus-->
	
	<?php
	
	$accountLevelPages = getAccountPages($_SESSION['userno'])->fetchAll(PDO::FETCH_COLUMN, 0);
	
	$pageGroups = getPageGroups();
	while ($rowPageGroup = $pageGroups->fetch(PDO::FETCH_ASSOC)) {
		$rootPageNo = 0;
		$pageRoots = getPageRoots($rowPageGroup['no']);
		while ($rowPageRoot = $pageRoots->fetch(PDO::FETCH_ASSOC)) {
		?>
		
			<?php
			if ($rowPageRoot['alias'] != NULL) {
				if ((!in_array($rowPageRoot['no'], $accountLevelPages)) && ($rowPageRoot['alias'] != "dashboard")) continue;
				if ($rootPageNo == 0) {
				?>
				
				<span class="heading"><?php echo $rowPageGroup['name'] ?></span>
				<ul class="list-unstyled">
				
				<?php
				}
				$rootPageNo++;
			?>
			
				<li<?php if ($_REQUEST['alias'] == $rowPageRoot['alias']) echo ' class="active"' ?>><a href="<?php echo $rowPageRoot['alias'] ?>"><i class="fa <?php echo $rowPageRoot['icon'] ?>"></i><?php echo $rowPageRoot['name'] ?></a></li>
			
			<?php } else { ?>
					
					<?php
					$subPages = getSubPages($rowPageRoot['no']);
					$subPageNo = 0;
					while ($rowSubPages = $subPages->fetch(PDO::FETCH_ASSOC)) {
						if (!in_array($rowSubPages['no'], $accountLevelPages)) continue;
						if ($rootPageNo == 0) {
						?>
						
						<span class="heading"><?php echo $rowPageGroup['name'] ?></span>
						<ul class="list-unstyled">
						
						<?php
						}
						if ($subPageNo == 0) {
						?>
						
						<li><a href="#menu<?php echo $rowPageRoot['no'] ?>" aria-expanded="false" data-toggle="collapse"> <i class="fa <?php echo $rowPageRoot['icon'] ?>"></i><?php echo $rowPageRoot['name'] ?></a>
						<ul id="menu<?php echo $rowPageRoot['no'] ?>" class="collapse list-unstyled">
						
						<?php
						}
						$subPageNo++;
						$rootPageNo++;
					?>
					
					<li><a href="<?php echo $rowSubPages['alias'] ?>"><?php echo $rowSubPages['name'] ?></a></li>
					
					<?php } ?>
					
					</ul>
				</li>
			
			<?php } ?>
		
		<?php } ?>
		</ul>
		
	<?php } ?>

</nav>