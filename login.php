<?php
if ($_REQUEST['loginButton'] == 'Login') {
	try {
		include_once("module/init.php");
		checkAccount($_REQUEST['loginUsername'],$_REQUEST['loginPassword']);
		header("Location: dashboard");
	} catch (Exception $e) {
		$error_msg = $e->getMessage();
	}
}
include_once("header.php");
?>
    <div class="page login-page">
      <div class="container d-flex align-items-center">
        <div class="form-holder has-shadow">
          <div class="row">
            <!-- Logo & Information Panel-->
            <div class="col-lg-6">
              <div class="info d-flex align-items-center">
                <div class="content">
                  <div class="logo">
										<img src="img/<?php echo $_REQUEST['brand'] ?>.jpg" id="liason">
                    <!--<h1>Liason</h1>-->
                  </div>
                  <p>Point of Sales</p>
									<br><br>
									<a href="../index.php" class="btn btn-secondary">Ganti Brand</a>
                </div>
              </div>
            </div>
            <!-- Form Panel    -->
            <div class="col-lg-6 bg-white">
              <div class="form d-flex align-items-center">
                <div class="content">
                  <form id="login-form" method="post" action="login">
                    <div class="form-group">
                      <input id="login-username" type="text" name="loginUsername" class="input-material">
                      <label for="login-username" class="label-material">User Name</label>
                    </div>
                    <div class="form-group">
                      <input id="login-password" type="password" name="loginPassword" class="input-material">
                      <label for="login-password" class="label-material">Password</label>
                    </div>
										<?php if ($error_msg != "") { ?>
										<p class="errormessage"><?php echo $error_msg ?></p>
										<?php } ?>
										<input type="submit" id="login" name="loginButton" class="btn btn-primary" value="Login">
                  </form><!--<a href="#" class="forgot-pass">Forgot Password?</a>-->
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
<?php
include_once("footer.php"); 
?>