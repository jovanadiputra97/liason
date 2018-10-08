<?php
$editable = false;
if ($crudPageEditable != "") $editable = checkAccountLevelPage($_SESSION['userno'], getPageNoByName($crudPageEditable));
?>
<script language="javascript">
function postCrud() {
	$("#FormCrud").submit();
}
function sortCrud(name) {
	if ($("#SortField").val() != name) {
		$("#SortField").val(name);
		$("#SortValue").val("ASC");
	} else {
		if ($("#SortValue").val() == "ASC") {
			$("#SortValue").val("DESC");
		} else {
			$("#SortValue").val("ASC");
		}
	}
	postCrud();
}
</script>
<?php
function getCrudIcon($name) {
	global $crudDefaultField;
	global $curSortField;
	global $curSortValue;
	$SortValue = "asc";
	$SortColor = "grey";
	if ($curSortField == $name) {
		$SortColor = "green";
		if ($curSortValue == "DESC") {
			$SortValue = "desc";
		}
	}
	echo 'class="fa fa-sort-alpha-'.$SortValue.' '.$_REQUEST['SortValue'].'" style="color: '.$SortColor.'"';
}
$curSortField = $_REQUEST['SortField'];
$curSortValue = $_REQUEST['SortValue'];
if ($curSortField == "") {
	$curSortField = $crudDefaultField;
	$curSortValue = "ASC";
	if ($crudDefaultFieldSort != "") $curSortValue = $crudDefaultFieldSort;
}
?>
<form id="FormEdit" method="post" action="<?php echo $crudEditAction ?>">
	<input type="hidden" name="ams" id="FormEditAms" value="">
	<input type="hidden" name="no" id="FormEditNo" value="">
	<input type="hidden" name="token" id="FormEditToken" value="">
</form>
<script language="javascript">
function editData(ams,no,token) {
	$("#FormEditAms").val(ams);
	$("#FormEditNo").val(no);
	$("#FormEditToken").val(token);
	$("#FormEdit").submit();
}
</script>
<dialog id="confirm-delete" class="site-dialog">
	<form id="FormDelete" method="post" action="<?php echo $crudDeleteAction ?>">
		<input type="hidden" name="ams" id="FormDeleteAms" value="">
		<input type="hidden" name="no" id="FormDeleteNo" value="">
		<input type="hidden" name="token" id="FormDeleteToken" value="">
	</form>
	<header class="dialog-header">
		<h1>Konfirmasi Hapus Data</h1>
	</header>
	<div class="dialog-content">
		<p>Anda akan menghapus <span id="FormDeleteContent"></span>. Data yang sudah terhapus tidak dapat dikembalikan. Apakah anda setuju untuk melakukan penghapusan data?</p>
	</div>
	<div class="btn-cgroup cf">
		<button class="btn btn-danger" id="popupbtndelete">Hapus</button>
		<button class="btn btn-cancel" id="popupbtncancel">Batal</button>

	</div>
</dialog>

<script language="javascript">
function confirmDelete(ams,no,name,token) {
	$("#FormDeleteAms").val(ams);
	$("#FormDeleteNo").val(no);
	$("#FormDeleteToken").val(token);
	$("#FormDeleteContent").html(name);
	$("#confirm-delete")[0].showModal();
	transition = setTimeout(function() {
			$("#confirm-delete").addClass('dialog-scale');
	}, 0.5);
}
document.onreadystatechange = () => {
	if (document.readyState === 'complete') {
		$('#popupbtncancel').on('click', function() {
			$("#confirm-delete")[0].close();
			$("#confirm-delete").removeClass('dialog-scale');
			clearTimeout(transition);
		});
		$('#popupbtndelete').on('click', function() {
			$("#FormDelete").submit();
		});
	}
};
</script>