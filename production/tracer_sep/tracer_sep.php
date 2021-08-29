<?php 
     require_once("../penghubung.inc.php");
     require_once($LIB."login.php");
     require_once($LIB."datamodel.php");
     require_once($LIB."dateLib.php");
     require_once($LIB."tampilan.php");

     $dtaccess = new DataAccess();
     $depId = $auth->GetDepId();
     $userName = $auth->GetUserName();

   ?>

<!DOCTYPE html>
<html lang="en">
    <?php require_once($LAY."header.php") ?>
    <body class="nav-md">
      <div class="container body">
        <div class="main_container">
          <!-- SIDEBAR -->
          <?php require_once($LAY."sidebar.php") ?>
          <!-- SIDEBAR -->
          <!-- TOP NAVIGATION -->
          <?php require_once($LAY."topnav.php") ?>
          <!-- TOP NAVIGATION -->        
          <!-- CONTENT -->
          <div class="right_col" role="main">
            <div class="">
              <div class="clearfix"></div>
  
              <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="x_panel">
                    <div class="x_content">
                      <!-- TABLE VIEW -->
                      <table width="100%" id="datatable" class="table table-striped table-bordered " border="1">
                        <thead>
                          <tr>
                            <th>No rm</th>
                            <th>Nama</th>
                          </tr>
                        </thead>
                        <tbody id="isi_tabelku">
                          
                        </tbody>
                      </table>
                      <!-- TABLE VIEW -->
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- CONTENT -->
        </div>
        <!-- FOOTER -->
        <?php require_once($LAY."footer.php") ?>
        <!-- FOOTER -->
				<script>
				$(document).ready(function() {
				setInterval(function() {
				
					$.post( "get_data_tracer.php",function(json){
						json = JSON.parse(json);
						$('#isi_tabelku').html('');
						
						$('#isi_tabelku').append(`<tr>
													<td>${json.cust_usr_kode}</td>
													<td>${json.cust_usr_nama}</td>
							`)
						 cetak(json.reg_id);
								

					});


					
				}, 6000);
				
			});

			function cetak(id_reg) {
					$.post( "print_tracer.php",{id_reg:id_reg});	
          window.open(`cetak_pasien_lama.php?id_reg=${id_reg}`, `_blank`);
          			
			}
				
				</script>
      </div>
      <!-- JAVASCRIPT -->
      <?php require_once($LAY."js.php") ?>
      <!-- JAVASCRIPT -->
  </body>
</html>


