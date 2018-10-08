<?php
include_once("header.php");
?>
    <div class="page login-page">
		<style>
		.login-page
		{
			content: '';
			width: 100%;
			height: 100%;
			display: block;
			background: url(img/1.jpg) !important;
		}
		
		.login-page::before
		{
			content: '';
			width: 100%;
			height: 100%;
			display: block;
			background: url(img/1.jpg) !important;
		}
		</style>

      <div class="container d-flex align-items-center">
        <div class="form-holder has-shadow">
          <div class="row">
            <!-- Logo & Information Panel-->
            <div class="col-lg-3 bg-white">
              <div class="info d-flex align-items-center">
                <table width="100%">
					<tr>
						<td align="center">
							<a href="sinfung/login">
								<img src="img/sinfung.jpg">
							</a>
						</td>
					</tr>
				</table>
              </div>
            </div>
			
			<div class="col-lg-3 bg-white">
              <div class="info d-flex align-items-center">
                <table width="100%">
					<tr>
						<td align="center">
							<a href="liason/login">
								<img src="img/liason.jpg" id="liason">
							</a>
						</td>
					</tr>
				</table>
              </div>
            </div>
			
			<div class="col-lg-3 bg-white">
              <div class="info d-flex align-items-center">
                <table width="100%">
					<tr>
						<td align="center">
							<a href="hokhu/login">
								<img src="img/hokhu.jpg">
							</a>
						</td>
					</tr>
				</table>
              </div>
            </div>
			
			<div class="col-lg-3 bg-white">
              <div class="info d-flex align-items-center">
                <table width="100%">
					<tr>
						<td align="center">
							<a href="jungfat/login">
								<img src="img/jungfat.jpg">
							</a>
						</td>
					</tr>
				</table>
              </div>
            </div>
			
          </div>
        </div>
      </div>

    </div>
<?php
include_once("footer.php"); 
?>