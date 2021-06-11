<?php
     // LIBRARY RADIOLOGI
     require_once("../penghubung.inc.php");
     require_once($LIB."login.php");
     require_once($LIB."encrypt.php");
     require_once($LIB."datamodel.php");

     $dtaccess = new DataAccess();  
     $auth = new CAuth();
     $table = new InoTable("table","100%","left");
     $depNama = $auth->GetDepNama();
	   $depId = $auth->GetDepId();
	   $userName = $auth->GetUserName();
	   $userData = $auth->GetUserData();
  	 $userId = $auth->GetUserId();
     $thisPage = "report_pasien.php";
     $poliId = $auth->IdPoli();
     
  
    if($_POST['btnLanjut']){

      /* update ruangan  */
      // $sql="update klinik.klinik_ruangan 
      //       set is_ready='n' where ruangan_id =".QuoteValue(DPE_CHAR,$_POST['id_ruangan']);
      // $rs = $dtaccess->Execute($sql);

      /* update jadwal dokter */
      // $sql="update klinik.klinik_jadwal_dokter 
      // set id_ruangan=".QuoteValue(DPE_CHAR,$_POST['id_ruangan'])."
      // where id_dokter=".QuoteValue(DPE_CHAR,$_POST['id_dokter'])." 
      // and  id_poli=".QuoteValue(DPE_CHAR,$_POST['id_poli'])."
      // and  jadwal_dokter_hari=".QuoteValue(DPE_NUMERIC,$_POST['day']);
      // $rs = $dtaccess->Execute($sql);

      $monitor ="monitor.php?id=".$_POST['id_ruangan'];
      echo "<script>document.location.href='" . $monitor . "';</script>";
      exit();
    }

     $tableHeader = "Setup Monitor";
  
  
/* SCript  Mencari hari Sunday = 0 , Monday = 1 etc  */
    $day = date('w');
    $week_start = date('m-d-Y', strtotime('-'.$day.' days'));
    $week_end = date('m-d-Y', strtotime('+'.(6-$day).' days'));

/* data Poli */
    $sql_where_poli = "select poli_nama, poli_id  from 
     global.global_auth_poli where poli_tipe ='J'";
    $data_poli = $dtaccess->FetchAll($sql_where_poli);


/* data Ruangan */
  $sql = "select ruangan_id ,ruangan_nama  from klinik.klinik_ruangan where is_ready !=".QuoteValue(DPE_CHAR,'n');
  $data_ruang = $dtaccess->FetchAll($sql);



?>


<!DOCTYPE html>
<html lang="en">
 
    <?php require_once($LAY."header.php") ?>
    <script type="text/javascript">
    function get_dokter(isi) {
      // alert(isi);
      var day = $('#day').val();
      $.getJSON(`get_dokter.php?id=${isi}&day=${day}`, function(nilai) {
        // alert(nilai);
        $("#id_dokter").html(`<option value="">- Pilih Dokter -</option>`);
        $.each(nilai, function(index, val) {
          $("#id_dokter").append(
            "<option value = '"+val.id_dokter+"'>"+val.usr_name+"</option>");
        });
      });
    }
  </script>
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
              <!-- FILTER -->
              <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="x_panel">
                    <div class="x_title">
                      <h2>Setting Monitor</h2>
                      <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                      <form name="frmView" action="<?php echo $_SERVER["PHP_SELF"]?>" method="POST">
                        
                        <!-- FILTER KIRI -->
                        <div class="col-md-4 col-sm-4 col-xs-4">
                          <!-- Filter Poli-->
                          <!-- <label class="control-label col-md-12 col-sm-12 col-xs-12">Nama Poli</label>
                              <select name="id_poli" class="select2_single form-control" onchange="get_dokter(this.value);" >
                                <option class="inputField" value="" >- Pilih Poli -</option>
                                <?php for($i=0,$n=count($data_poli
                              );$i<$n;$i++){ ?>
                                <option class="inputField" value="<?php echo $data_poli
                                [$i]["poli_id"];?>"><?php echo $data_poli[$i]["poli_nama"];?></option>
                                <?php } ?>
                              </select>  -->
                          <!-- Filter Poli-->
                           
                          <!-- Filter Klinik / Ruangan -->
                          <label class="control-label col-md-12 col-sm-12 col-xs-12">Nama Ruangan</label>
                          <div class='input-group col-md-12 col-sm-12 col-xs-12'>
                            <select class="select2_single form-control" name="id_ruangan">
                              <option value="">[Pilih Ruangan]</option>
                              <?php for($i=0,$n=count($data_ruang);$i<$n;$i++){ ?>
                                <option value="<?php echo $data_ruang[$i]["ruangan_id"];?>" ><?php echo $data_ruang[$i]["ruangan_nama"];?></option>
                              <?php } ?>
                            </select>
                          </div>
                          <!-- Filter Klinik / Ruangan -->
     
                        </div>
                        <!-- FILTER KIRI -->
                        <!-- FILTER TENGAH -->
                        <div class="col-md-4 col-sm-4 col-xs-4">
                           <!-- Filter Kondisi Akhir -->
                           <!-- <label class="control-label col-md-12 col-sm-12 col-xs-12">Pilih Dokter</label>
                          <div class='input-group col-md-12 col-sm-12 col-xs-12'>
                            <select class="select2_single form-control" name="id_dokter" id="id_dokter">
                              <option value="" >[ Pilih Dokter ]</option>
                            </select>
                          </div> -->
                          <!-- Filter Kondisi Akhir -->
                           
                      
                        </div>
                        <!-- FILTER TENGAH -->
                        <!-- FILTER KANAN -->
                        <div class="col-md-4 col-sm-4 col-xs-4">
                        
                         
                        </div>
                        <!-- FILTER KANAN -->
                        <!-- TOMBOL -->
                        <div class="col-md-4 col-sm-4 col-xs-4 pull-right">
                          <label class="control-label col-md-12 col-sm-12 col-xs-12">&nbsp;</label>
                          <input type="submit" name="btnLanjut" value="Lanjut" class="pull-right btn btn-primary">
                        </div>
                        <!-- TOMBOL -->
                        <!-- data store -->
                       <input type="hidden" id="day" name="day" value="<?=$day?>">
                      </form>
                    </div>
                  </div>
                </div>
              </div>
              <!-- FILTER -->

              
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
</html>

