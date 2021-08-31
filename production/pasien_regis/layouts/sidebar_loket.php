		<?php require_once("../penghubung.inc.php"); 
    require_once($LIB."login.php");
    $auth = new CAuth();
    
    
    ?>
    
		<div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a class="site_title"> <span style="font-size:15px">RSPI Prof. Dr. Sulianti Saroso</span></a>
            </div>

            <div class="clearfix"></div>

            <!-- menu profile quick info -->
            <div class="profile clearfix">
              <div class="profile_pic">
                <img src="<?php echo $ROOT ?>gambar/logo-rspiss.png" alt="..." class="img-circle profile_img">
              </div>
            </div>
            <!-- /menu profile quick info -->

            <br />

            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
              	<h3>Rekam Medik</h3>
                <ul class="nav side-menu">                          
                  <li><a style="font-size:15px"><i class="fa fa-users fa-lg"></i>&nbsp;Loket <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
					    <? if($auth->IsAllowed("fo_loket_registrasi",PRIV_READ)) { ?><li><a href="<?php echo $ROOT; ?>data_pasien/registrasi_pasien_awal.php" target="_blank" style="font-size:15px">Registrasi Pasien</a></li> <? } ?>
						<? if($auth->IsAllowed("fo_loket_registrasi",PRIV_READ)) { ?><li><a href="<?php echo $ROOT; ?>loket_jpk/registrasi_pasien_awal.php" target="_blank" style="font-size:15px">Create SEP</a></li> <? } ?>
                       <? if($auth->IsAllowed("fo_loket_edit_registrasi",PRIV_READ)) { ?><li><a href="<?php echo $ROOT; ?>edit_registrasi/registrasi_irj_view.php" target="_blank" style="font-size:15px">Edit Registrasi</a></li> <? } ?>
                       <? if($auth->IsAllowed("fo_loket_edit_registrasi",PRIV_READ)) { ?><li><a href="<?php echo $ROOT; ?>penata_jasa_irj/ar_main.php" target="_blank" style="font-size:15px">Penata Jasa IRJ</a></li> <? } ?>

                       <!--<li><a href="<?php echo $ROOT; ?>registrasi_irj/registrasi_irj_view.php" target="_blank" style="font-size:15px">Registrasi Pasien IRJ</a></li>
                       <li><a href="<?php echo $ROOT; ?>registrasi_igd/registrasi_igd_view.php" target="_blank" style="font-size:15px">Registrasi Pasien IGD</a></li>
                       <li><a href="<?php echo $ROOT; ?>registrasi_irna/registrasi_irna_view.php" target="_blank" style="font-size:15px">Registrasi Pasien IRNA</a></li>
                       <li><a href="<?php echo $ROOT; ?>registrasi_lab/registrasi_irj_view.php" target="_blank" style="font-size:15px">Registrasi Laboratorium</a></li>
                       <li><a href="<?php echo $ROOT; ?>registrasi_radiologi/registrasi_radiologi_view.php" target="_blank" style="font-size:15px">Registrasi Radiologi</a></li>
                       <li><a href="<?php echo $ROOT; ?>registrasi_ipj/registrasi_irj_view.php" target="_blank" style="font-size:15px">Registrasi IPJ</a></li>  -->
                       <!--<li><a href="<?php echo $ROOT; ?>antrian_irna/antrian.php" target="_blank" style="font-size:15px">Antrian IRNA</a></li>-->
          				<!--<? if($auth->IsAllowed("fo_loket_registrasi",PRIV_READ)) { ?><li><a href="<?php echo $ROOT; ?>update_tgl_pulang/update_tgl_pulang.php" target="_blank" style="font-size:15px">Update Tanggal Pulang </a></li> <? } ?>					             -->
                      <!--
                      <li><a href="<?php echo $ROOT; ?>tampilan_loket_1_reguler/next_antrian_1.php" target="_blank" style="font-size:15px">Tampilan Loket 1</a></li>
                      <li><a href="<?php echo $ROOT; ?>tampilan_loket_2_reguler/next_antrian_2.php" target="_blank" style="font-size:15px">Tampilan Loket 2</a></li>
                      <li><a href="<?php echo $ROOT; ?>tampilan_loket_3_reguler/next_antrian_3.php" target="_blank" style="font-size:15px">Tampilan Loket 3</a></li>
                      <li><a href="<?php echo $ROOT; ?>tampilan_loket_4_reguler/next_antrian_4.php" target="_blank" style="font-size:15px">Tampilan Loket 4</a></li>
                      <li><a href="<?php echo $ROOT; ?>tampilan_loket_5_reguler/next_antrian_5.php" target="_blank" style="font-size:15px">Tampilan Loket 5</a></li>                                          
                      <li><a href="<?php echo $ROOT; ?>tampilan_loket_6_reguler/next_antrian_6.php" target="_blank" style="font-size:15px">Tampilan Loket 6</a></li>
                      <li><a href="<?php echo $ROOT; ?>registrasi_online/registrasi_online.php" target="_blank" style="font-size:15px">Registrasi Online</a></li>                                          
                      -->
                    </ul>
                  </li> 
                   <li><a style="font-size:15px"><i class="glyphicon glyphicon-bed"></i>&nbsp;Informasi <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">   
                      <? if($auth->IsAllowed("fo_daftar_pasien",PRIV_READ)) { ?><li><a href="<?php echo $ROOT; ?>data_pasien/data_pasien_view.php" target="_blank" style="font-size:15px">Data Pasien</a></li> <? } ?>
                      <? if($auth->IsAllowed("fo_lap_kunjungan",PRIV_READ)) { ?><li><a href="<?php echo $ROOT; ?>lap_kunjungan_all/lap_kunjungan.php" target="_blank" style="font-size:15px">Laporan Kunjungan</a></li><? } ?>
                      <? if($auth->IsAllowed("fol_lap_pengunjung",PRIV_READ)) { ?><li><a href="<?php echo $ROOT; ?>lap_pengunjung_irj/report_pasien.php" target="_blank" style="font-size:15px">Laporan Pengunjung</a></li><? } ?>
                      <? if($auth->IsAllowed("fol_lap_batal_kunjung",PRIV_READ)) { ?><li><a href="<?php echo $ROOT; ?>lap_batal_registrasi/lap_batal.php" target="_blank" style="font-size:15px">Laporan Batal Kunjungan</a></li><? } ?>
                      <? if($auth->IsAllowed("man_ganti_password",PRIV_READ)) { ?><li><a href="<?php echo $ROOT; ?>lap_antrian/lap_antrian.php" target="_blank" style="font-size:15px">Laporan Antrian</a></li><? } ?>
                      <? if($auth->IsAllowed("man_ganti_password",PRIV_READ)) { ?><li><a href="<?php echo $ROOT; ?>rekap_antrian/rekap_antrian.php" target="_blank" style="font-size:15px">Rekap Antrian</a></li><? } ?>

                    </ul>
                  </li>
					<li><a style="font-size:15px"><i class="glyphicon glyphicon-file"></i>&nbsp;Dokumentasi <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?php echo $ROOT; ?>user_guide/User_Manual_Loket.pdf" target="blank" style="font-size:15px">User Guide</a></li>
                    </ul>
                  </li>	
                </ul>
              </div>
            </div>
            <!-- /sidebar menu -->


          </div>
        </div>
