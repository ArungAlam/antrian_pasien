<?php
	 // LIBRARY
     require_once("../penghubung.inc.php");
     require_once($LIB."login.php");
     require_once($LIB."encrypt.php");
     require_once($LIB."datamodel.php");
     require_once($LIB."tampilan.php");
     require_once($LIB."currency.php");
     //INISIALISASI LIBRARY
     $enc = new textEncrypt();
     $dtaccess = new DataAccess();
     $auth = new CAuth();
	   $depId = $auth->GetDepId();
     $view = new CView($_SERVER["PHP_SELF"],$_SERVER['QUERY_STRING']);
     $table = new InoTable("table1","100%","center");
     
     //$depNama = $auth->GetDepNama(); 
     $userName = $auth->GetUserName();
     //AUTHENTIKASI
     if(!$auth->IsAllowed("man_ganti_password",PRIV_READ)){
          die("access_denied");
          exit(1);
          
     } elseif($auth->IsAllowed("man_ganti_password",PRIV_READ)===1){
          echo"<script>window.parent.document.location.href='".$MASTER_APP."login/login.php?msg=Session Expired'</script>";
          exit(1);
     }
     
     $tableHeader = "Menu Antrian Pasien"; 
?>
<!DOCTYPE html>
<html>
  <?php require_once($LAY."header.php") ?>
  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <!-- $LAY" -->        
		  <?php require_once($LAY."sidebar.php") ?>
		<!-- //sidebar -->
        <!-- top navigation -->
		 <?php require_once($LAY."topnav.php") ?>
        <!-- /top navigation -->

		
    <!-- == KONTEN DISINI == KONTEN DISINI == KONTEN DISINI == KONTEN DISINI == KONTEN DISINI == KONTEN DISINI -->
    <!-- == KONTEN DISINI == KONTEN DISINI == KONTEN DISINI == KONTEN DISINI == KONTEN DISINI == KONTEN DISINI -->
        <!-- page content -->
        <div class="right_col" role="main">
          <!-- top tiles -->
          <div class="row tile_count">
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i> Total Pengunjung</span>
              <div class="count">120</div>
              <span class="count_bottom"><i class="green">4% </i> Dari Minggu Lalu</span>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-clock-o"></i> Waktu Tunggu</span>
              <div class="count">12.50</div>
              <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>3% </i> Dari Minggu Lalu</span>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i> Pengunjung Lama</span>
              <div class="count green">130</div>
              <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> Dari Minggu Lalu</span>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i> Pengunjung Baru</span>
              <div class="count">20</div>
              <span class="count_bottom"><i class="red"><i class="fa fa-sort-desc"></i>12% </i> Dari Minggu Lalu</span>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i> Pasien Umum</span>
              <div class="count">15</div>
              <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> Dari Minggu Lalu</span>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i> Pasien Jaminan</span>
              <div class="count">135</div>
              <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> Dari Minggu Lalu</span>
            </div>
          </div>
          <!-- /top tiles -->

          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="dashboard_graph">

                <div class="row x_title">
                  <div class="col-md-6">
                    <h3>Rekap Antrian Pasien</h3>
                  </div>
                  <div class="col-md-6">
                    <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
                      <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                      <span>October 10, 2017 - October 19, 2017</span> <b class="caret"></b>
                    </div>
                  </div>
                </div>

                <div class="col-md-9 col-sm-9 col-xs-12">
                  <div id="chart_plot_01" class="demo-placeholder"></div>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-12 bg-white">
                  <div class="x_title">
                    <h2>4 Besar Kunjungan Poli</h2>
                    <div class="clearfix"></div>
                  </div>

                  <div class="col-md-12 col-sm-12 col-xs-6">
                    <div>
                      <p>Anak</p>
                      <div class="">
                        <div class="progress progress_sm" style="width: 76%;">
                          <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="80"></div>
                        </div>
                      </div>
                    </div>
                    <div>
                      <p>Penyakit Dalam</p>
                      <div class="">
                        <div class="progress progress_sm" style="width: 76%;">
                          <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="60"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12 col-sm-12 col-xs-6">
                    <div>
                      <p>Syaraf</p>
                      <div class="">
                        <div class="progress progress_sm" style="width: 76%;">
                          <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="55"></div>
                        </div>
                      </div>
                    </div>
                    <div>
                      <p>Mata</p>
                      <div class="">
                        <div class="progress progress_sm" style="width: 76%;">
                          <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="50"></div>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>

                <div class="clearfix"></div>
              </div>
            </div>

          </div>
          <br />

          <div class="row">


          </div>
          <div class="row">
          </div>
        </div>
        <!-- /page content -->
		
    <!-- // == BATAS KONTEN // == BATAS KONTEN // == BATAS KONTEN // == BATAS KONTEN // == BATAS KONTEN // == BATAS KONTEN -->
    <!-- // == BATAS KONTEN // == BATAS KONTEN // == BATAS KONTEN // == BATAS KONTEN // == BATAS KONTEN // == BATAS KONTEN -->
        <!-- footer content -->
       <?php require_once($LAY."footer.php") ?>
        <!-- /footer content -->
      </div>
    </div>
<?php require_once($LAY."js.php") ?>
  </body>
</html>
