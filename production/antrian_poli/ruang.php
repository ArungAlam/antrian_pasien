<?php
  /**LIBRARY */
     require_once("../penghubung.inc.php");
     require_once($LIB."login.php");
     require_once($LIB."encrypt.php");
     require_once($LIB."datamodel.php");
     require_once($LIB."currency.php");

  /**INITIAL LIBRARY */
    $dtaccess = new DataAccess();  
    $auth = new CAuth();
    $table = new InoTable("table","100%","left");
    $depNama = $auth->GetDepNama();
    $depId = $auth->GetDepId();
    $userName = $auth->GetUserName();
    $userData = $auth->GetUserData();
    $userId = $auth->GetUserId();
    $thisPage = "pengunaan_bed.php";


  /**DEKLARASI AWAL */
     if (!$_POST["klinik"]) $_POST["klinik"]=$depId;
     else  $_POST["klinik"] = $_POST["klinik"];
     $tableHeader = "Ruangan Terpakai";

     
  /** KONFIGURASI */
    $sql = "select * from global.global_departemen where dep_id =".QuoteValue(DPE_CHAR,$depId);
    $rs = $dtaccess->Execute($sql);
    $konfigurasi = $dtaccess->Fetch($rs);
    $fotoName = $ROOT."adm/gambar/img_cfg/".$konfigurasi["dep_logo"]; 
    
    /* SCript  Mencari hari Sunday = 0 , Monday = 1 etc  */
    $day = date('w');
    $week_start = date('m-d-Y', strtotime('-'.$day.' days'));
    $week_end = date('m-d-Y', strtotime('+'.(6-$day).' days'));

/* data Poli */
    $sql_where_poli = "select poli_nama, poli_id  from 
     global.global_auth_poli where poli_tipe ='J'";
    $data_poli = $dtaccess->FetchAll($sql_where_poli);


/* data Ruangan */
  $sql = "select ruangan_id ,ruangan_nama  from klinik.klinik_ruangan where is_ready ='y' order by ruangan_id ";
  $data_ruang = $dtaccess->FetchAll($sql);

    /* jika dokter keluar ruangan */
    if($_POST['id_ruangan_keluar'] !=''){

        /* update ruangan  */
            $sql="update klinik.klinik_ruangan 
            set is_ready='y' where ruangan_id =".QuoteValue(DPE_CHAR,$_POST['id_ruangan_keluar']);
            $rs = $dtaccess->Execute($sql);

        /* update jadwal dokter */
        $sql="update klinik.klinik_jadwal_dokter 
        set id_ruangan = NULL
        where jadwal_dokter_id=".QuoteValue(DPE_CHAR,$_POST['id_jadwal_dokter']);

        $rs = $dtaccess->Execute($sql);
    }

    // if($_POST['reset'] == "oke"){
    //   echo "reset";
    //    $sql ="DELETE FROM klinik.klinik_nomer_antrian";
    //    $rs  = $dtaccess->Execute($sql);
    // }

    if($_POST['pakai']){

      

      /* kosongan jadwal pakai ruang_id di jadwal dokter */
      $sql="update  klinik.klinik_jadwal_dokter
        set id_ruangan = NULL 
        where id_ruangan =".QuoteValue(DPE_CHAR,$_POST['id_ruangan']);
      $rs = $dtaccess->Execute($sql);


       /* update ruangan  */
      $sql="update klinik.klinik_ruangan 
            set is_ready='n' where ruangan_id =".QuoteValue(DPE_CHAR,$_POST['id_ruangan']);
      $rs = $dtaccess->Execute($sql);

      /* update jadwal dokter */
      $sql="update klinik.klinik_jadwal_dokter 
      set id_ruangan=".QuoteValue(DPE_CHAR,$_POST['id_ruangan'])."
      where id_dokter=".QuoteValue(DPE_CHAR,$_POST['id_dokter'])." 
      and  id_poli=".QuoteValue(DPE_CHAR,$_POST['id_poli'])."
      and  jadwal_dokter_hari=".QuoteValue(DPE_NUMERIC,$_POST['day']);
      $rs = $dtaccess->Execute($sql);

    }
  
    

    /**SQL TABEL */
      $sql = "select a.ruangan_id,a.ruangan_nama,c.usr_name ,d.poli_nama ,
                b.jadwal_dokter_id , a.is_ready
             from klinik.klinik_ruangan a  
			       left join klinik.klinik_jadwal_dokter b on b.id_ruangan = a.ruangan_id
             left join global.global_auth_user c on c.usr_id = b.id_dokter
             left join global.global_auth_poli d on d.poli_id = b.id_poli
              order by  a.ruangan_id ";
     $rs = $dtaccess->Execute($sql,DB_SCHEMA);
     $dataTable = $dtaccess->FetchAll($rs);
     /**SQL TABEL */
    
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
    <script>
      function sendVal(ruang_keluar,jadwal){
       if( confirm("yakin mengkosongkan ruang ini")){
        $('#id_ruangan_keluar').val(ruang_keluar);
        $('#id_jadwal_dokter').val(jadwal);
        $('#id_form').submit();
       }
      };

      function reset(){
       if( confirm("yakin mengapus semua antrian")){
        $('#reset').val('oke');
        $('#id_form').submit();
       }
      };
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
              <div class="row">
                <!-- FILTER -->
                <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="x_panel">
                    <div class="x_title">
                      <h2>Setting Ruangan</h2>
                      <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                      <form name="frmView" action="<?php echo $_SERVER["PHP_SELF"]?>" method="POST">
                        
                        <!-- FILTER KIRI -->
                        <div class="col-md-4 col-sm-4 col-xs-4">
                          <!-- Filter Poli-->
                          <label class="control-label col-md-12 col-sm-12 col-xs-12">Nama Poli</label>
                              <select name="id_poli" class="select2_single form-control" onchange="get_dokter(this.value);" >
                                <option class="inputField" value="" >- Pilih Poli -</option>
                                <?php for($i=0,$n=count($data_poli
                              );$i<$n;$i++){ ?>
                                <option class="inputField" value="<?php echo $data_poli
                                [$i]["poli_id"];?>"><?php echo $data_poli[$i]["poli_nama"];?></option>
                                <?php } ?>
                              </select> 
                          <!-- Filter Poli-->
     
                        </div>
                        <!-- FILTER KIRI -->
                        <!-- FILTER TENGAH -->
                        <div class="col-md-4 col-sm-4 col-xs-4">
                           <!-- Filter Kondisi Akhir -->
                           <label class="control-label col-md-12 col-sm-12 col-xs-12">Pilih Dokter</label>
                          <div class='input-group col-md-12 col-sm-12 col-xs-12'>
                            <select class="select2_single form-control" name="id_dokter" id="id_dokter">
                              <option value="" >[ Pilih Dokter ]</option>
                            </select>
                          </div>
                          <!-- Filter Kondisi Akhir -->
                           
                      
                        </div>
                        <!-- FILTER TENGAH -->
                        <!-- FILTER KANAN -->
                        <div class="col-md-4 col-sm-4 col-xs-4">
                         
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
                        <!-- FILTER KANAN -->
                        <!-- TOMBOL -->
                        <div class="col-md-4 col-sm-4 col-xs-4 pull-right">
                          <label class="control-label col-md-12 col-sm-12 col-xs-12">&nbsp;</label>
                          <input type="submit" name="pakai" value="Pakai Ruang" class="pull-right btn btn-primary">
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
              
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="x_panel">
                   <div class="x_title">
                      <h2>Ruangan Terpakai</h2>
                      <!-- <a href="#" class="pull-right col-md-r col-sm-3 col-xs-3 btn btn-danger" onclick="reset()">Reset Antrian</a> -->
                      <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                    <form id="id_form" name="frmEdit" action="<?php echo $_SERVER["PHP_SELF"]?>" method="POST" class="">
                      <!-- TABLE VIEW -->
                      <table width="100%" id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" border="1">
                        <thead>
                          <tr>
                            <th>No</th>
                            <th>Nama Ruangan</th>
                            <th>Nama Dokter</th>
                            <th>Nama Poli</th>
                            <th>Keluar Ruangan</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if ($dataTable): ?>
                            <?php foreach ($dataTable as $key => $value): ?>
                              <tr>
                                <td><?=$key+1?></td>
                                <td><?=$value['ruangan_nama']?></td>
                                <td>
                                 <?php if($value['is_ready'] == 'y'){?>
                                    Sedang Tidak Digunakan
                                 <?php  }else { ?>
                                   <?=$value['usr_name']?>
                                 <?php } ?>
                                </td>
                                <td>
                                 <?php if($value['is_ready'] == 'y'){?>
                                    Sedang Tidak Digunakan
                                 <?php  }else { ?>
                                     <?=$value['poli_nama']?>
                                 <?php } ?>
                                </td>
                                <td> 
                                 <?php if($value['is_ready'] == 'y'){?>

                                
                                 <?php  }else { ?>

                                    <a href="#" type="button" onclick="sendVal('<?= $value['ruangan_id']?>','<?= $value['jadwal_dokter_id']?>')" ><center><i class="fa fa-sign-out" style="font-size: 25px; color:red"></i></center></a>
                                
                                <?php } ?>
                                
                                </td>                    
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
              <input type="hidden"  id="id_ruangan_keluar" name="id_ruangan_keluar" value="">
              <input type="hidden"  id="id_jadwal_dokter" name="id_jadwal_dokter" value="">
              <input type="hidden"  id="reset" name="reset" value="">
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
</html>
 
