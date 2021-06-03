<?php
	 /**LIBRARY */
     require_once("../penghubung.inc.php");
     require_once($LIB."login.php");
     require_once($LIB."encrypt.php");
     require_once($LIB."datamodel.php");
     require_once($LIB."tampilan.php");
     require_once($LIB."currency.php");

  /**INITIAL LIBRARY */
    $view = new CView($_SERVER['PHP_SELF'],$_SERVER['QUERY_STRING']);
    $dtaccess = new DataAccess();  
    $auth = new CAuth();
    $table = new InoTable("table","100%","left");
    $depNama = $auth->GetDepNama();
    $depId = $auth->GetDepId();
    $userName = $auth->GetUserName();
    $userData = $auth->GetUserData();
    $userId = $auth->GetUserId();
    $thisPage = "pengunaan_bed.php";

    $jenis[1] = 'BPJS';
    $jenis[2] = 'PRIORITY';
    $jenis[3] = 'ASURANSI';
    $jenis[4] = 'JKN MOBILE';

  if ($_GET['id']) {
  	$sql = "UPDATE klinik.klinik_reg_antrian_reguler SET antri_aktif = 'y', reg_panggil = 'n', waktu_panggil = '".date('Y-m-d H:i:s')."', id_loket = '".$_GET['loket']."' WHERE reg_antri_id = ".QuoteValue(DPE_CHAR, $_GET["id"]);
    $dtaccess->Execute($sql);
  }
	$id_poli = $_GET['id_poli'];
 	$sql = "SELECT * FROM klinik.klinik_reg_antrian_reguler WHERE antri_aktif = 'n' AND reg_antri_tanggal = ".QuoteValue(DPE_DATE, date('Y-m-d'));
	if($id_poli > 0){ $sql .=" and id_poli ='$id_poli' "; }
	$sql .=" ORDER BY reg_antri_suara ASC, reg_antri_nomer ASC";
  //$rs = $dtaccess->Execute($sql);
  $data = $dtaccess->FetchAll($sql);

 	$sql = "SELECT * FROM klinik.klinik_reg_antrian_reguler WHERE antri_aktif = 'y' AND reg_antri_tanggal = ".QuoteValue(DPE_DATE, date('Y-m-d'));
	if($id_poli > 0){ $sql .=" and id_poli ='$id_poli' "; }
	$sql .=" ORDER BY reg_antri_suara DESC, reg_antri_nomer DESC Limit 25";
  //$rs = $dtaccess->Execute($sql);
  $data_sudah = $dtaccess->FetchAll($sql);
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
              <div class="col-md-6 col-sm-6 col-xs-6">
                <div class="x_panel"> <h1>Loket <?=$_GET['id_loket']?></h1>
                 <div class="x_title">
                    <h2>Antrian Pasien  </h2>
                    <!-- <a href="#" class="pull-right col-md-r col-sm-3 col-xs-3 btn btn-danger" onclick="reset()">Reset Antrian</a> -->
                    <div class="clearfix"></div>
                  </div>
									  <form action="">
										 <div class="col-md-12">
										 	<div class="col-md-4"><label >Filter Jenis Pasien </label></div>
											<div class="col-md-8">
												<select class="form-control"  name="id_poli" id="id_poli">
													<option value="0" <?php if($id_poli==0){ echo "selected "; } ?>  >Semua</option>
													<option value="1"  <?php if($id_poli==1){ echo "selected "; } ?>  >BPJS</option>
													<option value="2"  <?php if($id_poli==2){ echo "selected "; } ?> >Priority</option>
													<option value="3" <?php if($id_poli==3){ echo "selected "; } ?> >Asuransi</option>
													<option value="4" <?php if($id_poli==4){ echo "selected "; } ?> >Online</option>
												</select>
										 	</div>
										 </div>
										 
										</form>
									<div class="clearfix"></div>
                  <div class="x_content">
                  <form id="id_form" name="frmEdit" action="<?php echo $_SERVER["PHP_SELF"]?>" method="POST" class="">
                    <!-- TABLE VIEW -->
                    <table width="100%" id="" class="table table-striped table-bordered dt-responsive nowrap" border="1">
                      <thead>
                        <tr>
                          <th>No. Antrian</th>
                          <th>Jenis Pasien</th>
                          <th>Panggil</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if ($data): ?>
                          <?php foreach ($data as $value): ?>
                            <tr>
                              <td><?=$value['reg_antri_suara'].sprintf("%03d",$value['reg_antri_nomer'])?></td>
                              <td><?=$jenis[$value['id_poli']]?></td>
                              <td><a href="#" title="panggil" onclick="panggil(<?= $value['reg_antri_id'] ?>, <?= $_GET['id_loket'] ?>)"><center><i class="fa fa-bell"></i></center></a></td>
                            </tr>
                          <?php endforeach ?>
                        <?php endif ?>
                      </tbody>
                    </table>
                    <!-- TABLE VIEW -->
                     
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-6">
                <div class="x_panel">
                 <div class="x_title">
                    <h2>Pasien Sudah di Panggil</h2>
                    <!-- <a href="#" class="pull-right col-md-r col-sm-3 col-xs-3 btn btn-danger" onclick="reset()">Reset Antrian</a> -->
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                  <form id="id_form" name="frmEdit" action="<?php echo $_SERVER["PHP_SELF"]?>" method="POST" class="">
                    <!-- TABLE VIEW -->
                    <table width="100%" id="" class="table table-striped table-bordered dt-responsive nowrap" border="1">
                      <thead>
                        <tr>
                          <th>No. Antrian</th>
                          <th>Jenis Pasien</th>
                          <th>Panggil Ulang</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if ($data_sudah): ?>
                          <?php foreach ($data_sudah as $value): ?>
                            <tr>
                              <td><?=$value['reg_antri_suara'].sprintf("%03d",$value['reg_antri_nomer'])?></td>
                              <td><?=$jenis[$value['id_poli']]?></td>
                              <td><a href="#" title="panggil" onclick="panggil(<?= $value['reg_antri_id'] ?>, <?= $_GET['id_loket'] ?>)"><center><i class="fa fa-bell"></i></center></a></td>
                            </tr>
                          <?php endforeach ?>
                        <?php endif ?>
                      </tbody>
                    </table>
                    <!-- TABLE VIEW -->
                     
                  </div>
                </div>
              </div>
            </div>
          </from>
          </div>
        </div>
        <!-- CONTENT -->
      </div>
      <!-- FOOTER -->
      <?php require_once($LAY."footer.php") ?>
      <!-- FOOTER -->
    </div>
    <!-- JAVASCRIPT -->
    <?php require_once($LAY."js.php") ?>
    <!-- JAVASCRIPT -->
  </body>
  <script type="text/javascript">
    setInterval(function() {
      location.reload();
    }, 3500);
		

		$( "#id_poli" ).change(function() {
			var val = $('#id_poli').val();
			var id_loket = "<?=$_GET['id_loket']?>";
			location.replace(`index.php?id_loket=${id_loket}&id_poli=${val}`);
		});
    function panggil(id, loket) {
      $.get('index.php?id='+id+'&loket='+loket, function(data) {
         location.reload();
      });
    }
  </script>
</html>