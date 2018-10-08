<?php
include_once("header.php");
include_once("header-main.php"); 
?>
        <?php
		include_once("navbar.php"); 
		?>
		
		<?php
		include("module/item.php");
		include("module/customer.php");
		include("module/sales.php");
		$jatuhtempodari = date('Y-m')."-01";
		$jatuhtemposampai = date('Y-m-d', strtotime("+1 month",strtotime(date('Y-m')."-01")));
		$deadlineTotal = getSales(array("deadline" => " >= '".$jatuhtempodari."'","deadline2" => " < '".$jatuhtemposampai."'","outstanding" => " > 0"))->rowCount();
		$invoiceTotal = getSales(array("outstanding" => " > 0"))->rowCount();
		$invoicePercent = 0;
		if ($deadlineTotal > 0) $invoicePercent = round($deadlineTotal / $invoiceTotal * 100);
		?>
        <div class="content-inner">
          <!-- Page Header-->
          <header class="page-header">
            <div class="container-fluid">
              <h2 class="no-margin-bottom">Dashboard</h2>
            </div>
          </header>
          <!-- Dashboard Counts Section-->
					<?php if ($allowToAccess) { ?>
          <section class="dashboard-counts no-padding-bottom">
            <div class="container-fluid">
              <div class="row bg-white has-shadow">
                <!-- Item -->
                <div class="col-xl-4 col-sm-6">
                  <div class="item d-flex align-items-center">
                    <div class="icon bg-violet"><a href="barang-lihat"><i class="fa fa-archive"></i></a></div>
                    <div class="title"><span>Total Barang</span>
                      <div class="progress">
                        <div role="progressbar" style="width: 100%; height: 4px;" aria-valuenow="{#val.value}" aria-valuemin="0" aria-valuemax="100" class="progress-bar bg-violet"></div>
                      </div>
                    </div>
                    <div class="number"><strong><?php echo getTotalItems(); ?></strong></div>
                  </div>
                </div>
				<!-- Item -->
                <div class="col-xl-4 col-sm-6">
                  <div class="item d-flex align-items-center">
                    <div class="icon bg-orange"><a href="pelanggan-lihat"><i class="fa fa-id-card-o"></i></a></div>
                    <div class="title">Total Pelanggan</span>
                      <div class="progress">
                        <div role="progressbar" style="width: 100%; height: 4px;" aria-valuenow="{#val.value}" aria-valuemin="0" aria-valuemax="100" class="progress-bar bg-orange"></div>
                      </div>
                    </div>
                    <div class="number"><strong><?php echo getTotalCustomers(); ?></strong></div>
                  </div>
                </div>
								
								<form name="FormInvoice" id="FormInvoice" action="faktur-lihat" method="post">
								<input type="hidden" name="jatuhtempodari" value="<?php echo $jatuhtempodari ?>">
								<input type="hidden" name="jatuhtemposampai" value="<?php echo date("Y-m-d", strtotime("-1 day", strtotime($jatuhtemposampai))) ?>">
								</form>
								<script language="javascript">
								function goToInvoice() {
									$("#FormInvoice").submit();
								}
								</script>
                
                <!-- Item -->
                <div class="col-xl-4 col-sm-6">
                  <div class="item d-flex align-items-center">
                    <div class="icon bg-green"><a href="javascript:goToInvoice()"><i class="icon-bill"></i></a></div>
                    <div class="title">Jatuh Tempo <?php echo date('m-Y') ?></span>
                      <div class="progress">
                        <div role="progressbar" style="width: <?php echo $invoicePercent ?>%; height: 4px;" aria-valuenow="{#val.value}" aria-valuemin="0" aria-valuemax="100" class="progress-bar bg-green"></div>
                      </div>
                    </div>
                    <div class="number"><strong><?php echo $deadlineTotal ?></strong></div>
                  </div>
                </div>
				
              </div>
            </div>
          </section>
          <!-- Dashboard Header Section    -->
          <section class="dashboard-header">
            <div class="container-fluid">
              <div class="row">
                <!-- Line Chart            -->
                <div class="chart col-lg-12 col-12">
                  <div class="line-chart bg-white d-flex align-items-center justify-content-center has-shadow">
                    <canvas id="lineCahrt"></canvas>
                  </div>
                </div>
 
              </div>
            </div>
          </section>
					<?php } else { ?>
					<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
					<?php } ?>

<?php
include_once("footer-main.php");
include_once("footer.php");
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>

<script>
$(document).ready(function () {
	'use strict';

    // ------------------------------------------------------- //
    // Line Chart
    // ------------------------------------------------------ //
    var legendState = true;
    if ($(window).outerWidth() < 576) {
        legendState = false;
    }
	
	var LINECHART = $('#lineCahrt');
    var myLineChart = new Chart(LINECHART, {
        type: 'line',
        options: {
            scales: {
                xAxes: [{
                    display: true,
                    gridLines: {
                        display: false
                    }
                }],
                yAxes: [{
                    display: true,
                    gridLines: {
                        display: false
                    }
                }]
            },
            legend: {
                display: legendState
            }
        },
        data: {
            labels: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12"],
            datasets: [
                {
                    label: "Total Penjualan <?php echo date('Y') ?>",
                    fill: true,
                    lineTension: 0,
                    backgroundColor: "transparent",
                    borderColor: "#54e69d",
                    pointHoverBackgroundColor: "#44c384",
                    borderCapStyle: 'butt',
                    borderDash: [],
                    borderDashOffset: 0.0,
                    borderJoinStyle: 'miter',
                    borderWidth: 1,
                    pointBorderColor: "#44c384",
                    pointBackgroundColor: "#fff",
                    pointBorderWidth: 1,
                    pointHoverRadius: 5,
                    pointHoverBorderColor: "#fff",
                    pointHoverBorderWidth: 2,
                    pointRadius: 1,
                    pointHitRadius: 10,
                    data: [<?php
										for ($curMonth = 1; $curMonth <= 12; $curMonth++) {
											if ($curMonth > 1) echo ", ";
											echo getSales(array("MONTH(event)" => " = ".$curMonth))->rowCount();
										}
										?>],
                    spanGaps: false
                }
            ]
        }
    });
});
</script>