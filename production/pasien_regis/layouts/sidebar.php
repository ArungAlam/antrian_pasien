		<?php require_once("../penghubung.inc.php"); 
		    require_once($LIB."login.php");
			$auth = new CAuth();
		?>
		<div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a class="site_title"> <span style="font-size:16px">ANTRIAN PASIEN</span></a>
            </div>

            <div class="clearfix"></div>

            <!-- menu profile quick info -->
            <div class="profile clearfix">
              <div class="profile_pic">
                <!--<img src="<?php echo $ROOT ?>gambar/logo-rspiss.png" alt="..." class="img-circle profile_img"> -->
              </div>
            </div>
            <!-- /menu profile quick info -->

            <br />

            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <h3>MENU ANTRIAN</h3>
                <ul class="nav side-menu">
                  <li><a style="font-size:15px"><i class="fa fa-home"></i>&nbsp; Pengaturan <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <? if($auth->IsAllowed("man_ganti_password",PRIV_READ)) { ?><li><a href="<?php echo $ROOT; ?>konf_antrian/konfigurasi_antrian.php" target="_blank" style="font-size:15px">Konfigurasi Antrian</a></li><? } ?>
                    </ul>
                  </li>
                  <li><a style="font-size:15px"><i class="fa fa-users fa-lg"></i>&nbsp; User <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
  		                <? if($auth->IsAllowed("man_ganti_password",PRIV_READ)) { ?><li><a href="<?php echo $ROOT; ?>ganti_password/ganti_password.php" target="_blank" style="font-size:15px">Ganti Password</a></li><? } ?>                 
  		            </ul>
                  </li> 
                  <li><a style="font-size:15px"><i class="fa fa-money"></i>&nbsp; Antrian <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <? if($auth->IsAllowed("man_ganti_password",PRIV_READ)) { ?><li><a href="<?php echo $ROOT; ?>lcd/antrian.php" target="_blank" style="font-size:15px">LCD Antrian</a></li><? } ?>
                      <? if($auth->IsAllowed("man_ganti_password",PRIV_READ)) { ?><li><a href="<?php echo $ROOT; ?>pasien/antri_tambah.php" target="_blank" style="font-size:15px">Tampilan Pasien</a></li><? } ?>
                      <? if($auth->IsAllowed("man_ganti_password",PRIV_READ)) { ?><li><a href="<?php echo $ROOT; ?>reset_antrian/reset_antrian.php" target="_blank" style="font-size:15px">Reset Antrian</a></li><? } ?>
                      <? if($auth->IsAllowed("man_ganti_password",PRIV_READ)) { ?><li><a href="<?php echo $ROOT; ?>lcd/lcd_1.php" target="_blank" style="font-size:15px">LCD 1</a></li><? } ?>
                      <? if($auth->IsAllowed("man_ganti_password",PRIV_READ)) { ?><li><a href="<?php echo $ROOT; ?>lcd/lcd_2.php" target="_blank" style="font-size:15px">LCD 2</a></li><? } ?>
                      <? if($auth->IsAllowed("man_ganti_password",PRIV_READ)) { ?><li><a href="<?php echo $ROOT; ?>lcd/lcd_3.php" target="_blank" style="font-size:15px">LCD 3</a></li><? } ?>                      
                      <? if($auth->IsAllowed("man_ganti_password",PRIV_READ)) { ?><li><a href="<?php echo $ROOT; ?>lcd/lcd_4.php" target="_blank" style="font-size:15px">LCD 4</a></li><? } ?>
                      <? if($auth->IsAllowed("man_ganti_password",PRIV_READ)) { ?><li><a href="<?php echo $ROOT; ?>lcd/lcd_5.php" target="_blank" style="font-size:15px">LCD 5</a></li><? } ?>
                      <? if($auth->IsAllowed("man_ganti_password",PRIV_READ)) { ?><li><a href="<?php echo $ROOT; ?>lcd/lcd_6.php" target="_blank" style="font-size:15px">LCD 6</a></li><? } ?>
                  </ul>
                  </li>
                   <li><a style="font-size:15px"><i class="fa fa-money"></i>&nbsp;Loket <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                        <li><a href="<?php echo $ROOT; ?>tampilan_loket_1_reguler/next_antrian_1.php" target="_blank" style="font-size:15px">Tampilan Loket 1</a></li>
                      <li><a href="<?php echo $ROOT; ?>tampilan_loket_2_reguler/next_antrian_2.php" target="_blank" style="font-size:15px">Tampilan Loket 2</a></li>
                      <li><a href="<?php echo $ROOT; ?>tampilan_loket_3_reguler/next_antrian_3.php" target="_blank" style="font-size:15px">Tampilan Loket 3</a></li>
                      <li><a href="<?php echo $ROOT; ?>tampilan_loket_4_reguler/next_antrian_4.php" target="_blank" style="font-size:15px">Tampilan Loket 4</a></li>
                      <li><a href="<?php echo $ROOT; ?>tampilan_loket_5_reguler/next_antrian_5.php" target="_blank" style="font-size:15px">Tampilan Loket 5</a></li>                                          
                      <li><a href="<?php echo $ROOT; ?>tampilan_loket_6_reguler/next_antrian_6.php" target="_blank" style="font-size:15px">Tampilan Loket 6</a></li>                                          
                  </ul>
                  </li>
                   <li><a style="font-size:15px"><i class="glyphicon glyphicon-plus"></i>&nbsp; Laporan <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <? if($auth->IsAllowed("man_ganti_password",PRIV_READ)) { ?><li><a href="<?php echo $ROOT; ?>lap_antrian/lap_antrian.php" target="_blank" style="font-size:15px">Laporan Antrian</a></li><? } ?>
                      <? if($auth->IsAllowed("man_ganti_password",PRIV_READ)) { ?><li><a href="<?php echo $ROOT; ?>rekap_antrian/rekap_antrian.php" target="_blank" style="font-size:15px">Rekap Antrian</a></li><? } ?>
                    </ul>
                  </li> 
                  <!--
                  <li><a style="font-size:15px"><i class="glyphicon glyphicon-file"></i>&nbsp;&nbsp;Dokumentasi <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?php echo $ROOT; ?>user_guide/User_Manual_Loket.pdf" target="blank" style="font-size:15px">User Guide</a></li>
                    </ul>
                  </li>	 -->
                </ul>
              </div>
            </div>
            <!-- /sidebar menu -->

     
          </div>
        </div>