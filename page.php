<?php
$checkSession = true;
include_once("module/init.php");

$stmt = $conn->prepare("SELECT * FROM page WHERE alias = ?");
$stmt->execute(array($_REQUEST['alias']));
if (($stmt->rowCount() > 0) || ($_REQUEST['alias'] == "dashboard")) {
	$page = $stmt->fetch(PDO::FETCH_ASSOC);
	$allowToAccess = checkAccountLevelPage($_SESSION['userno'], $page['no']);
	if (($allowToAccess) || ($_REQUEST['alias'] == "dashboard")) {
		$_SESSION['pageno'] = $page['no'];
		if (file_exists($page['filename'])) include_once($page['filename']);
		else {
			include_once("header.php");
			include_once("header-main.php"); 
			include_once("navbar.php"); 
		?>
			<div class="content-inner">
				<!-- Page Header-->
				<header class="page-header">
					<div class="container-fluid">
						<h2 class="no-margin-bottom errormessage">Error : Halaman tidak ditemukan</h2><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
					</div>
				</header>
		<?php
			include_once("footer-main.php");
			include_once("footer.php");
		}
	} else {
		//header("HTTP/1.0 404 Not Found");
		echo "Invalid".$_SESSION['userno'];
	}
} else {
	header("HTTP/1.0 404 Not Found");
}
?>