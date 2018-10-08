<?php
function generateToken($name) {
	return substr(md5($name), -5);
}
function checkToken($token, $name) {
	return ($token == generateToken($name));
}
function checkTokenError() {
	global $tokenErrorRedirect;
	$tokenError = 0;

	if ($_REQUEST['ams'] != "") {
		
		if (!checkToken($_REQUEST['token'],$_REQUEST['alias'].'-'.$_REQUEST['ams'].'-'.$_REQUEST['no'])) {
			$tokenError = 1;
		?>
			<form name='FormError' method='post' action='<?php echo $tokenErrorRedirect ?>'><input type="hidden" name="notificationerror" value="1"><input type='hidden' name='notification' value='Token <?php echo $_REQUEST['token'] ?> tidak valid'></form>
			<script language="javascript">
			document.FormError.submit();
			</script>
		<?php
		}
	}
	
	return $tokenError;
}
?>