<?php
 /** LIBRARY */
 require_once("../penghubung.inc.php");
 require_once($LIB."datamodel.php");

/** INITIAL LIBRARY  */
 $dtaccess = new DataAccess();
 $skr  = date('Y-m-d');

/* SCript  Mencari hari Sunday = 0 , Monday = 1 etc  */
    $day = date('w');
    $week_start = date('m-d-Y', strtotime('-'.$day.' days'));
    $week_end = date('m-d-Y', strtotime('+'.(6-$day).' days'));

 $sql="select a.id_dokter, b.usr_name, b.kode_nama, c.poli_nama,jadwal_dokter_jam_mulai as mulai ,jadwal_dokter_jam_selesai as selesai
 from klinik.klinik_jadwal_dokter a
 left join global.global_auth_user b on b.usr_id = a.id_dokter 
 left join global.global_auth_poli c on c.poli_id = a.id_poli
 where a.jadwal_dokter_hari =".QuoteValue(DPE_NUMERIC,$day)."
 and usr_name != '' and usr_name is not null and poli_tipe ='J'  order by poli_nama";
 $dokter = $dtaccess->FetchAll($sql);
 

 ?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
    <link rel="stylesheet" type="text/css" href="box.css">
    <link rel="stylesheet" type="text/css" href="bootstraps/bootstrap.min.css">
    <body >
        
           
    <div class="container">
      <div class="down">
        <h2 class="text-center ">Mobile Antrian</h2>
      </div>
     
    <!-- combo dokter -->
      <div class="form-group">
          <label class="col-md-6 col-sm-6 "><h4>Pilih Dokter</h4></label>
          <div class="col-md-6 col-sm-6">
          <select name="dokter" class="form-control" id="dokter" onchange="getKode(this.value)">
              <option value="">[-- Pilih Dokter --]</option>
              <?php foreach ($dokter as $key => $value) {?>
                <option value="<?= $value['kode_nama']?>">
                      <?php echo $value['usr_name']." ( ".$value['poli_nama']." )"; ?>
                </option>
              <?php }?>
          </select>

          </div>
      </div>
    <!-- DATA  Store -->
    <input type="hidden" id="kode" value="--">
    <!-- Kolom -->
      <div class="col-md-6 col-sm-6 text-center">
        <div class="col bulat">
          <h4>Antrian kini </h4>
        </div >
        <div id="call"  class="down">

        </div>
      </div>
      <!-- next -->
      <div class="col-md-6 col-sm-6 text-center">
        <div class="col bulat">
          <h4>Antrian berikutnya</h4>
        </div>
        <div id="next"  class="down">

        </div>
      </div>

      <div class="col-md-6 col-sm-6 text-center">
        <div class="col bulat">
          <h4>Selesai di layani</h4>
        </div>
        <div id="last"  class="down">

        </div>
      </div>

    </div>
      
    
   


     <script type="text/javascript" src="jquery.min.js"></script>
     <script type="text/javascript" src="bootstraps/bootstrap.min.js"></script>
      <script>
        /*Tiap 3 detik Refresh  */
        $(document).ready(function() {
            setInterval(function() {
              get_antrian();
              get_last_pasien();
            }, 3000);

          });
        
        function getKode(kode){
            $('#kode').val(kode);
        }
        function get_antrian() {
            kode = $('#kode').val()
            $.getJSON(`get_antrian.php?kode=${kode}`, function(nilai) {
              $("#next").html('');
              $("#call").html('');
              $.each(nilai, function(index, val) {
                if(!val.call){
                  val.call = '--'
                };
                if(!val.next){
                  val.next = '--'
                };
                  $("#call").append(
                    `<div class="col ">
                    <h4>${val.call}</h4>
                  </div>`);
                  $("#next").append(
                    `<div class="col ">
                    <h4>${val.next}</h4>
                  </div>`);
                });
            });
          }
          function get_last_pasien() {
            kode = $('#kode').val()
            $.getJSON(`get_last_pasien.php?kode=${kode}`, function(nilai) {
              $('#last').html('');

              $.each(nilai, function(index, val) {
                
                  $("#last").append(
                    `<div class="col ">
                    <h4>${val.nomer} || ${val.nama} </h4>
                  </div>`);
                 
                });
            });
          }
        
         
     </script>
     <div >
            
     </div>
  </body>
  <!-- <footer>
    <div class="text-right lisensi">
    <a href="http://www.freepik.com">Designed by Freepik</a>
    </div>
  </footer> -->
</html>